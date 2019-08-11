<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     2019-08-11 20:40:30
 *
 * Defines PopupMenuExtensionTicketMerge
 */
 
/**
* Class PopupMenuExtensionTicketMerge. Adds popup menu to allow merging of (Ticket) objects
*/

class PopupMenuExtensionTicketMerge implements iPopupMenuExtension
{
        /**
         * Get the list of items to be added to a menu.
         *
         * This method is called by the framework for each menu.
         * The items will be inserted in the menu in the order of the returned array.
         * @param int $iMenuId The identifier of the type of menu, as listed by the constants MENU_xxx
         * @param mixed $param Depends on $iMenuId, see the constants defined above
         * @return object[] An array of ApplicationPopupMenuItem or an empty array if no action is to be added to the menu
         */
        public static function EnumItems($iMenuId, $param)
        {
                $aResult = array();
 
                switch($iMenuId) // type of menu in which to add menu items
                {
                        /**
                         * Insert an item into the Actions menu of a list
                         *
                         * $param is a DBObjectSet containing the list of objects       
                         */      
                        case iPopupMenuExtension::MENU_OBJLIST_ACTIONS:
							
							$oObjectSet = $param;
							
							// Module settings, defaults.
							$aModuleSettings = utils::GetCurrentModuleSetting('default', []);
							
							$bMergeAllowed = (count(array_intersect(UserRights::ListProfiles(), explode(',', $aModuleSettings['allowed_profiles']))) > 0);
							$bMergeAllowed = ($aModuleSettings['allowed_profiles'] == '' ? true : $bMergeAllowed);
								
							// Only if subclass of Ticket, for now.
							if( is_subclass_of($oObjectSet->GetClass(), 'Ticket') == true && $bMergeAllowed == true ) {
							 
								// Add a separator
								// $aResult[] = new SeparatorPopupMenuItem(); // Note: separator does not work in iTop 2.0 due to Trac #698, fixed in 2.0.1
								$oFilter = $oObjectSet->GetFilter();
								
								$sUID = utils::GetCurrentModuleName().'_show_list'; // Make sure that each menu item has a unique "ID"
								$sLabel = Dict::S('UI:TicketMerge:ObjectList:Merge');
								$sURL = utils::GetAbsoluteUrlExecPage().'?'.
									'exec_module='.utils::GetCurrentModuleName().
									'&exec_page=ui.'.utils::GetCurrentModuleName().'.php'.
									'&exec_env='.MetaModel::GetEnvironment().
									'&filter='.htmlentities($oFilter->Serialize(), ENT_QUOTES, 'UTF-8').
									'&class='.$oObjectSet->GetClass().
									'&operation=merge_list';
								$sTarget = '_top';
								$oMenuItem = new URLPopupMenuItem($sUID, $sLabel, $sURL, $sTarget);

								$aResult[] = $oMenuItem;

							}
								
							
							break;
 
                        /**
                         * Insert an item into the Toolkit menu of a list
                         *
                         * $param is a DBObjectSet containing the list of objects
                         */      
                        case iPopupMenuExtension::MENU_OBJLIST_TOOLKIT:
							break;
 
                        /**
                         * Insert an item into the Actions menu on an object's details page
                         *
                         * $param is a DBObject instance: the object currently displayed
                         */      
                        case iPopupMenuExtension::MENU_OBJDETAILS_ACTIONS:
							break;
 
                        /**
                         * Insert an item into the Dashboard menu
                         *
                         * The dashboad menu is shown on the top right corner of the page when
                         * a dashboard is being displayed.
                         * 
                         * $param is a Dashboard instance: the dashboard currently displayed
                         */      
                        case iPopupMenuExtension::MENU_DASHBOARD_ACTIONS:
							break;
 
                        /**
                         * Insert an item into the User menu (upper right corner of the page)
                         *
                         * $param is null
                         */
                        case iPopupMenuExtension::MENU_USER_ACTIONS:
							break;
 
                }
                return $aResult;
        }
}
