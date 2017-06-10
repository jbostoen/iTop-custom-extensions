<?php
// Copyright (C) 2014 TeemIp
//
//   This file is part of TeemIp.
//
//   TeemIp is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   TeemIp is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with TeemIp. If not, see <http://www.gnu.org/licenses/>

/**
 * @copyright   Copyright (C) 2014 TeemIp
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class _IPAddress extends IPObject
{
	/**
	 * Get the subnet mask of the subnet that the IP belongs to, if any.
	 */
	function GetSubnetMaskFromIp()
	{
		return "";
	}
	
	/**
	 * Get the gateway of the subnet that the IP belongs to, if any.
	 */
	function GetSubnetGatewayFromIp()
	{
		return "";
	}
	/**
	 * Check if IP's FQDN is unique.
	 */
	function IsFqdnUnique()
	{
		$sOrgId = $this->Get('org_id');
		if ($this->IsNew())
		{
			$iKey = -1;
		}
		else
		{
			$iKey = $this->GetKey();
		}
		$sFqdn = $this->Get('fqdn');
		
		// The check takes into account the global parameters that defines if duplicate FQDNs are authorized or not
		$sIpAllowDuplicateName = utils::ReadPostedParam('attr_ip_allow_duplicate_name', '');
		if (empty($sIpAllowDuplicateName))
		{
			$sIpAllowDuplicateName = IPConfig::GetFromGlobalIPConfig('ip_allow_duplicate_name', $sOrgId);
		}
		if ($sIpAllowDuplicateName == 'ipdup_no')
		{
			if ($sFqdn != "")
			{
				$oIpSet = new CMDBObjectSet(DBObjectSearch::FromOQL("SELECT IPAddress AS i WHERE i.fqdn = '$sFqdn' AND i.org_id = $sOrgId AND i.id != $iKey"));
				// Match for creations is verbiden. Match for modifications as well unless the current name is kept
				while ($oIp = $oIpSet->Fetch())
				{
					// Check status of IP before complaining - Released IPs don't count.
					if ($oIp->Get('status') != 'released')
					{
						return false;
					}
				}
			}
		}
		return true;
	}

	/**
	 * Displays additional tabs related to IP addresses
	 */
	function DisplayBareRelations(WebPage $oPage, $bEditMode = false)
	{
		// Execute parent function first 
		parent::DisplayBareRelations($oPage, $bEditMode);
			
		$sOrgId = $this->Get('org_id');
		if ($sOrgId != null)
		{
			if ($bEditMode)
			{
				// Tab for Global Parameters
				$oPage->SetCurrentTab(Dict::Format('Class:IPAddress/Tab:globalparam'));
				$oPage->p(Dict::Format('UI:IPManagement:Action:Modify:GlobalConfig'));
				$oPage->add('<table style="vertical-align:top"><tr>');
				$oPage->add('<td style="vertical-align:top">');
				
				if ($this->IsNew())
				{
					$sParameter = array ('ip_allow_duplicate_name', 'ping_before_assign', null);
				}
				else
				{
					$sParameter = array ('ip_allow_duplicate_name', null);
				}
				$this->DisplayGlobalParametersInLocalModifyForm($oPage, $sParameter);
				
				$oPage->add('</td>');
				$oPage->add('</tr></table>');
			}
			else
			{
				$iKey = $this->GetKey();

				// Tab for NAT IPs
				$oNatIpSearch = DBObjectSearch::FromOQL("SELECT lnkIPAdressToIPAddress AS ln WHERE (ln.ip1_id = $iKey OR ln.ip2_id = $iKey)");
				$oNatIpSet = new CMDBObjectSet($oNatIpSearch);
				$oIpSet = array();
				$iCountIp1 = 0;
				$iCountIp2 = 0;
				while ($oNatIp = $oNatIpSet->fetch())
				{
					if ($oNatIp->Get('ip1_id') == $iKey)
					{
						$iIpKey = $oNatIp->Get('ip2_id');
						$iCountIp1++;
					}
					else
					{
						$iIpKey = $oNatIp->Get('ip1_id');
						$iCountIp2++;
					}
					$oIpSet[] = MetaModel::GetObject('IPAddress', $iIpKey, false);
				}
				$oSet = CMDBObjectSet::FromArray('IPAddress', $oIpSet);
				// Ugly: this is to handle the current (2.0.3) iTop's limitation that doesn't handle the symetrical AttributeLinkedSetIndirect to lnkIPAdressToIPAddress
				// Note that $oPage->FinTab doesn't work...
				$oPage->RemoveTab(Dict::Format('Class:IPAddress/Attribute:ip_list'));
				$oPage->RemoveTab(Dict::Format('Class:IPAddress/Tab:ip_list', $iCountIp1));
				$oPage->SetCurrentTab(Dict::Format('Class:IPAddress/Tab:ip_list', $oNatIpSet->Count()));
				$oPage->p(MetaModel::GetClassIcon('IPAddress').'&nbsp;'.Dict::Format('Class:IPAddress/Tab:ip_list+'));
				if ($oNatIpSet->Count() != 0)
				{
					$oBlock = DisplayBlock::FromObjectSet($oSet, 'list');
					$oBlock->Display($oPage, 'nat_ips', array('menu' => false));
				}
				
				// Tab for CIs using the IP
				$iNbCIs = 0;
				$oDatacenterDeviceSearch = DBObjectSearch::FromOQL("SELECT DatacenterDevice AS dd WHERE dd.managementip_id = $iKey");
				$oDatacenterDeviceSet = new CMDBObjectSet($oDatacenterDeviceSearch);
				$iNbDatacenterDevices = $oDatacenterDeviceSet->Count();
				$iNbCIs += $iNbDatacenterDevices;
				
				$iNbVirtualMachines = 0;
				if  (MetaModel::IsValidClass('VirtualMachine'))
				{
					$oVirtualMachineSearch = DBObjectSearch::FromOQL("SELECT VirtualMachine AS vm WHERE vm.managementip_id = $iKey");
					$oVirtualMachineSet = new CMDBObjectSet($oVirtualMachineSearch);
					$iNbVirtualMachines = $oVirtualMachineSet->Count();
					$iNbCIs += $iNbVirtualMachines;
				}

				$iNbMobilePhones = 0;
				if  (MetaModel::IsValidClass('MobilePhone'))
				{
					if (MetaModel::IsValidAttCode('MobilePhone', 'ipaddress_id'))
					{
					$oMobilePhoneSearch = DBObjectSearch::FromOQL("SELECT MobilePhone WHERE ipaddress_id = $iKey");
					$oMobilePhoneSet = new CMDBObjectSet($oMobilePhoneSearch);
					$iNbMobilePhones = $oMobilePhoneSet->Count();
					$iNbCIs += $iNbMobilePhones;
					}
				}

				$iNbIPPhones = 0;
				if  (MetaModel::IsValidClass('IPPhone'))
				{
					if (MetaModel::IsValidAttCode('IPPhone', 'ipaddress_id'))
					{
					$oIPPhoneSearch = DBObjectSearch::FromOQL("SELECT IPPhone WHERE ipaddress_id = $iKey");
					$oIPPhoneSet = new CMDBObjectSet($oIPPhoneSearch);
					$iNbIPPhones = $oIPPhoneSet->Count();
					$iNbCIs += $iNbIPPhones;
					}
				}

				$iNbTablets = 0;
				if  (MetaModel::IsValidClass('Tablet'))
				{
					if (MetaModel::IsValidAttCode('Tablet', 'ipaddress_id'))
					{
					$oTabletSearch = DBObjectSearch::FromOQL("SELECT Tablet WHERE ipaddress_id = $iKey");
					$oTabletSet = new CMDBObjectSet($oTabletSearch);
					$iNbTablets = $oTabletSet->Count();
					$iNbCIs += $iNbTablets;
					}
				}

				$iNbPCs = 0;
				if  (MetaModel::IsValidClass('PC'))
				{
					if (MetaModel::IsValidAttCode('PC', 'ipaddress_id'))
					{
					$oPCSearch = DBObjectSearch::FromOQL("SELECT PC WHERE ipaddress_id = $iKey");
					$oPCSet = new CMDBObjectSet($oPCSearch);
					$iNbPCs = $oPCSet->Count();
					$iNbCIs += $iNbPCs;
					}
				}

				/*JB START*/
				$iNbIPDevices = 0;
				if  (MetaModel::IsValidClass('IPDevice'))
				{
					if (MetaModel::IsValidAttCode('IPDevice', 'managementip_id'))
					{
					$oIPDevicesSearch = DBObjectSearch::FromOQL("SELECT IPDevice WHERE managementip_id = $iKey");
					$oIPDevicesSet = new CMDBObjectSet($oIPDevicesSearch);
					$iNbIPDevices = $oIPDevicesSet->Count();
					$iNbCIs += $iNbIPDevices;
					}
				}
				/*JB END*/

				$iNbPrinters = 0;
				if  (MetaModel::IsValidClass('Printer'))
				{
					if (MetaModel::IsValidAttCode('Printer', 'ipaddress_id'))
					{
					$oPrinterSearch = DBObjectSearch::FromOQL("SELECT Printer WHERE ipaddress_id = $iKey");
					$oPrinterSet = new CMDBObjectSet($oPrinterSearch);
					$iNbPrinters = $oPrinterSet->Count();
					$iNbCIs += $iNbPrinters;
					}
				}

				$oIPInterfaceToIPAddressSearch = DBObjectSearch::FromOQL("SELECT lnkIPInterfaceToIPAddress AS l WHERE l.ipaddress_id = $iKey");
				$oIPInterfaceToIPAddressSet = new CMDBObjectSet($oIPInterfaceToIPAddressSearch);
				$iNbIPInterfaces = $oIPInterfaceToIPAddressSet->Count();
				$iNbCIs += $iNbIPInterfaces;
				$oIPInterfaceSet = array();
				while ($oLnk = $oIPInterfaceToIPAddressSet->fetch())
				{
					$iIpIntKey = $oLnk->Get('ipinterface_id');
					$oIPInterfaceSet[] = MetaModel::GetObject('IPInterface', $iIpIntKey, false);
				}
				$oSet = CMDBObjectSet::FromArray('IPInterface', $oIPInterfaceSet);
				
				$oPage->SetCurrentTab(Dict::Format('Class:IPAddress/Tab:ci_list', $iNbCIs));
				if ($iNbCIs != 0)
				{
					$oPage->p(MetaModel::GetClassIcon('FunctionalCI').'&nbsp;'.Dict::Format('Class:IPAddress/Tab:ci_list+', $iNbCIs));
					if ($iNbDatacenterDevices != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oDatacenterDeviceSet, 'list');
						$oBlock->Display($oPage, 'dd_id', array('menu' => false));
					}
					if ($iNbVirtualMachines != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oVirtualMachineSet, 'list');
						$oBlock->Display($oPage, 'vm_id', array('menu' => false));
					}
					if ($iNbMobilePhones != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oMobilePhoneSet, 'list');
						$oBlock->Display($oPage, 'mp_id', array('menu' => false));
					}
					if ($iNbIPPhones != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oIPPhoneSet, 'list');
						$oBlock->Display($oPage, 'ip_id', array('menu' => false));
					}
					if ($iNbTablets != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oTabletSet, 'list');
						$oBlock->Display($oPage, 'tb_id', array('menu' => false));
					}
					if ($iNbPCs != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oPCSet, 'list');
						$oBlock->Display($oPage, 'pc_id', array('menu' => false));
					}
					/* JB START */
					if ($iNbIPDevices != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oIPDevicesSet, 'list');
						$oBlock->Display($oPage, 'ipdev_id', array('menu' => false));
					}
					/* JB END */
					if ($iNbPrinters != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oPrinterSet, 'list');
						$oBlock->Display($oPage, 'pr_id', array('menu' => false));
					}
					if ($iNbIPInterfaces != 0)
					{
						$oBlock = DisplayBlock::FromObjectSet($oSet, 'list');
						$oBlock->Display($oPage, 'ii_id', array('menu' => false));
					}
				}
				else
				{
					$oPage->p(Dict::Format('Class:IPAddress/Tab:NoCi+'));
				}

				
				// Tab for related IP Requests, if any
				if  (MetaModel::IsValidClass('IPRequestAddress'))
				{
					$oIpRequestSearch = DBObjectSearch::FromOQL("SELECT IPRequestAddress AS r WHERE r.ip_id = $iKey");
					$oIpRequestSet = new CMDBObjectSet($oIpRequestSearch);
					$sCount = $oIpRequestSet->Count();
					if ($sCount > 0)
					{
					$oPage->SetCurrentTab(Dict::Format('Class:IPAddress/Tab:requests', $sCount));
					$oPage->p(MetaModel::GetClassIcon('IPRequestAddress').'&nbsp;'.Dict::Format('Class:IPAddress/Tab:requests+'));
					$oBlock = new DisplayBlock($oIpRequestSearch, 'list');
					$oBlock->Display($oPage, 'ip_requests', array('menu' => false));
					}
				}
			}
		}
	}
	
	/*
	 * Compute attributes before writing object 
	 */     
	public function ComputeValues()
	{
		// Set FQDN
		$sShortName = $this->Get('short_name');
		if ($sShortName != "")
		{
			$sDomainName = $this->Get('domain_name');
			if ($sDomainName != "")
			{
				// Domain names are always fully qualified
				$this->Set('fqdn', $sShortName.'.'.$sDomainName);			
			}
			else
			{
				$this->Set('fqdn', $sShortName.'.');			
			}
		}
		else
		{
			$this->Set('fqdn', '');			
		}
	}

	/**
	 * Check validity of new IP attributes before creation
	 */
	public function DoCheckToWrite()
	{
		// Run standard iTop checks first
		parent::DoCheckToWrite();
		
		// Make sure name doesn't already exist (matches)
		if (! $this->IsFqdnUnique())
		{
			return (Dict::Format('UI:IPManagement:Action:New:IPAddress:IPNameCollision'));
		}
	}
	
	/**
	 * Change flag of attributes that shouldn't be modified beside creation.
	 */
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		if ($sAttCode == 'fqdn')
		{
			return OPT_ATT_READONLY;
		}
		if ((!$this->IsNew()) && ($sAttCode == 'org_id'))
		{
			return OPT_ATT_READONLY;
		}
		return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
	}
	
	/**
	 * Manage status of IP when attached to a device 
	 */					   
	public static function SetStatusOnAttachment ($iIpId = null, $iPreviousIpId = null)
	{
		if ($iIpId != $iPreviousIpId) 
		{
			if ($iIpId != null)
			{
				$oIP = MetaModel::GetObject('IPAddress', $iIpId, false /* MustBeFound */);
				if ($oIP != null)
				{
					if ($oIP->Get('status') != 'allocated')
					{
						$oIP->Set('status', 'allocated');	
						$oIP->DBUpdate();
					}
				}
			}
			if ($iPreviousIpId != null)
			{
				$oIP = MetaModel::GetObject('IPAddress', $iPreviousIpId, false /* MustBeFound */);
				if ($oIP != null)
				{
					if ($oIP->Get('status') == 'allocated')
					{
						$oIP->Set('status', 'unassigned');	
						$oIP->DBUpdate();
					}
				}
			}
		}
	}
	
	/**
	 * Manage status of IP when deattached from a device 
	 */
	public static function SetStatusOnDetachment ($iIpId = null)
	{
		if ($iIpId != null)
		{
			$oIP = MetaModel::GetObject('IPAddress', $iIpId, false /* MustBeFound */);
			if ($oIP != null)
			{
				if ($oIP->Get('status') == 'allocated')
				{
					$oIP->Set('status', 'unassigned');
					$oIP->DBUpdate();
				}
			}
		}
	}
	
}
