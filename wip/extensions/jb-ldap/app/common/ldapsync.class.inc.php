<?php 

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-10-28 13:58:34
 *
 * Definition of LDAPSyncProcessor
 */
 
namespace jb_itop_extensions\ldap_sync;

	/**
	 * Class LDAPSyncProcessor. Contains methods to compress files
	 */
	abstract class LDAPSyncProcessor {
		
		/**
		 *
		 * Constructor
		 *
		 * @return void
		 */
		public function __construct() {
			
		}
		
		/**
		 * Logs trace message
		 *
		 * @var \String $sMessage Message
		 *
		 * @return void
		 */
		public function Trace($sMessage, $bThrowException = false) {
			echo date('Y-m-d H:i:s').' - '. \utils::GetCurrentModuleName().' - '.$sMessage.PHP_EOL;
		}

		/**
		 * Logs trace message and throws exception
		 *
		 * @var \String $sMessage Message
		 *
		 * @return void
		 */
		public function Throw($sMessage) {
			
			self::Trace($sMessage);
			throw new \Exception($sMessage);
			
		}
		
		/**
		 * Processes each LDAP in the config settings
		 *
		 * @return void
		 */
		public function ProcessLDAPs() {
		
			self::Trace('Start processing sync_rules...');
			
			$aDefaultSyncRule = \utils::GetCurrentModuleSetting('default_sync_rule', []);
			$aSyncRules = \utils::GetCurrentModuleSetting('sync_rules', []);
			
			// Process each LDAP.
			// Each LDAP can have a different settings.
			foreach($aSyncRules as $sIndex => $aSyncRule) {
				
				$aSyncRule = array_replace_recursive($aDefaultSyncRule, $aSyncRule);
				
				try {
					self::ProcessLDAP($sIndex, $aSyncRule);		
				}
				catch(\Exception $e) {
					// Nothing for now?
					self::Trace('Failed to process sync rule (index '.$sIndex.'): '.$e->GetMessage());
				}
				
			}
			
			self::Trace('Finished synchronization.');
			
		}
		
		/**
		 * Processes an LDAP Sync
		 *
		 * @var \String $sIndex Index of the sync rule
		 * @var \Array $aSyncRule Hash table of scope settings
		 *
		 * @return void
		 */
		public function ProcessLDAP($sIndex, $aSyncRule) {
			
			$aKeys = ['host', 'port', 'default_user', 'default_pwd', 'base_dn', 'options', 'ldap_attributes', 'create_objects', 'update_objects'];
			
			// Check if there is enough info to connect to an LDAP
			foreach($aKeys as $sKey) {
				if(isset($aSyncRule[$sKey]) == false) {
					self::Throw('Error: sync rule (index '.$sIndex.'): "'.$sKey.'" is missing.');
					return;
				}
			}
			
			if(is_array($aSyncRule['options']) == false) {
				self::Throw('Error: sync rule (index '.$sIndex.'): "options" expects an array');
			}
			
			
			// Create objects as needed
			foreach($aSyncRule['objects'] as $sIndex => $aObject) {
				
				// OQL query specified?
				if(isset($aObject['reconcile_on']) == false) {
					self::Throw('Error: sync rule (index '.$sIndex.'): no "reconcile_on" specified for object index '.$sIndex);
				}
				
				// Valid class specified?
				preg_match('/SELECT ([A-z0-9]{1,}).*$/', $aObject['reconcile_on'], $aMatches);
				
				if(count($aMatches) < 2) {
					self::Throw('Error: sync rule (index '.$sIndex.'): invalid "reconcile_on" specified for object index '.$sIndex);
				}
				
				$sClass = $aMatches[1];
				
				if(\MetaModel::IsValidClass($sClass) == false) {
					self::Throw('Error: sync rule (index '.$sIndex.'): invalid "reconcile_on" specified for object index '.$sIndex.' - class: '.$sClass);					
				}
				
				// Valid attributes specified in configuration?
				// Check with iTop Datamodel
				$aValidAttributes = \MetaModel::GetAttributesList($sClass);
				foreach($aObject['attributes'] as $sAttCode => $sAttValue) {
					if(in_array($sAttCode, $aValidAttributes) == false) {
						self::Throw('Error: sync rule (index '.$sIndex.'): invalid attribute "'.$sAttCode.'" specified for object index '.$sIndex.' - class: '.$sClass);	
					}
				}
				
				
			}
			
			// Connect
			// ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7); // Enable for debugging issues with XAMPP and LDAPS
			$oConnection = @ldap_connect($aSyncRule['host'], $aSyncRule['port']);
			
			if($oConnection === false) {
				self::Throw('Error: sync rule (index '.$sIndex.'): unable to connect to the LDAP-server: '.$aSyncRule['host'].':'.$aSyncRule['port']);
			}
			
			foreach($aSyncRule['options'] as $sKey => $uValue) {
				if(!ldap_set_option($oConnection, $sKey, $uValue)) {
					self::Throw('Error: sync rule (index '.$sIndex.'): invalid LDAP-option or value: '.$sKey);
				}
			}
			
			// Try to bind
			@ldap_bind($oConnection, $aSyncRule['default_user'], $aSyncRule['default_pwd']) or self::Throw('Error: sync rule (index '.$sIndex.'): unable to bind to server '.$aSyncRule['host'].':'.$aSyncRule['port'].' with user '.$aSyncRule['default_user']);

			$oResult = ldap_search($oConnection, $aSyncRule['base_dn'], $aSyncRule['user_query'], $aSyncRule['ldap_attributes']);
			$aLDAP_Entries = [];

			if($oResult !== false) {
				$aLDAP_Entries = ldap_get_entries($oConnection, $oResult);
			}
			else {
				self::Throw('Error: sync rule (index '.$sIndex.'): no results');
			}

			// Process
			foreach($aLDAP_Entries as $sKey => $aEntry) {
				
				// The result has a 'count' key.
				if(strtolower($sKey) == 'count') {
					continue;
				}
				
				// Start for each LDAP user with an empty placeholders set
				$aPlaceHolders = [];
				$aPlaceHolders['first_object->id'] = -1;
				
				// All other entries should have the values listed.
				// Since the limitation in this extension is that it should be a string/integer,
				// only the first item is taken into account for now.
				foreach($aEntry as $sKey => $aValue) {
					if(in_array((String)$sKey, $aSyncRule['ldap_attributes']) == false) {
						// Should unset numbers (but keys are strings here, not integers)
						// Should also unset 'count' and likely 'dn'
						unset($aEntry[(String)$sKey]);
					}
					else {
						// Setting an object instead of 'standalone' variables won't work well, 
						// since it then requires a GetForTemplate() method (see \MetaModel::ApplyParams())
						// Usually 'Count' and '0'
						$aPlaceHolders['ldap_user->'.$sKey] = $aValue[(String)'0'];
					}
				}
				
				// If null, LDAP does not return certain attributes (example: no phone number specified).
				// For this implementation, set empty values.
				foreach($aSyncRule['ldap_attributes'] as $sAttLDAP) {
					if(isset($aPlaceHolders['ldap_user->'.$sAttLDAP]) == false) {
						$aPlaceHolders['ldap_user->'.$sAttLDAP] = '';
					}
				}

				self::Trace('..' . json_encode($aEntry));
				
				// Create objects as needed
				foreach($aSyncRule['objects'] as $sIndex => $aObject) {
					
					if(isset($aObject['class']) == false) {
						self::Throw('Error: sync rule (index '.$sIndex.'): Class not defined');
					}
										
					$sOQL = \MetaModel::ApplyParams($aObject['reconcile_on'], $aPlaceHolders);
					self::Trace('.. OQL: '.$sOQL);
					
					$oFilter = \DBObjectSearch::FromOQL($sOQL);
					$oSet = new \CMDBObjectSet($oFilter);
					$sClassName = $oSet->GetClass();
					
					switch($oSet->Count()) {
						case 0:
							// Create							
							
							if($aSyncRule['create_objects'] != true) {
								self::Trace('... Object does not exist. Not creating object: create_objects = false');
								break;
							}
							
							self::Trace('... Object does not exist. Create ' . $sClassName);
							
							try {
								
								$oObj = \MetaModel::NewObject($aObject['class']);
								
								foreach($aObject['attributes'] as $sAttCode => $sAttValue) {
									// Allow placeholders in attributes; replace them here
									$sAttValue = \MetaModel::ApplyParams($sAttValue, $aPlaceHolders);
									self::Trace('....' . $sAttCode . '=> ' . $sAttValue);
									$oObj->Set($sAttCode, $sAttValue);
								}
								
								// This may throw errors. 
								// Example: using $ldap_user->telephonenumber$ (but empty value) while a NULL value is not allowed
								// Silently supress
								$iKey = $oObj->DBInsert();
								
								$aPlaceHolders['previous_object->id'] = $iKey;
								
								// Only if first object in chain
								if($sIndex == 0) {
									$aPlaceHolders['first_object->id'] = $iKey;
								}
								
								self::Trace('.... '.$aObject['class'].' for LDAP-user.');
								
							}
							catch(\Exception $e) {
								self::Trace('.... Unable to create a new '.$aObject['class'].' for LDAP-user:' . $e->GetMessage());
							}
							
							
							break;
							
						case 1:
						
							if($aSyncRule['update_objects'] != true) {
								self::Trace('... Not updating object. update_objects = false update_object');
								break;
							}
							// Fetch first object from set
							$oObj = $oSet->Fetch();
							
							// Update							
							self::Trace('... Update ' . $sClassName.'::'.$oObj->GetKey());
							
							$bUpdated = false;
							
							foreach($aObject['attributes'] as $sAttCode => $sAttValue) {
								// Allow placeholders in attributes; replace them here
								$sAttValue = \MetaModel::ApplyParams($sAttValue, $aPlaceHolders);
								
								if($oObj->Get($sAttCode) != $sAttValue) {
									$oObj->Set($sAttCode, $sAttValue);
									$bUpdated = true;
								}
							}
							
							if($bUpdated == true) {
								$oObj->DBUpdate();
								self::Trace('.... '.$aObject['class'].' updated for LDAP-user.');
							}
							else {
								self::Trace('.... '.$aObject['class'].' NOT updated for LDAP-user.');								
							}
							
							$aPlaceHolders['previous_object->id'] = $oObj->GetKey();
							if($sIndex == 0) {
								$aPlaceHolders['first_object->id'] = $oObj->GetKey();
							}
							
							break;
							
						default:
							// Unable to uniquely reconcile. Skip, no error.
							// Set first object ID (if unset!) to something non-existing to prevent errors in chained instructions.
							// Set previous object ID to something non-existing to prevent errors in chained instructions.
							$aPlaceHolders['previous_object->id'] = -1;
							self::Trace('... Could not uniquely reconcile ' . $sClassName . '. Ignoring this for the current user.');
							break;
							
					}
					
				}
				
			
			}
	
			return;
			
		}
		
	}
	
