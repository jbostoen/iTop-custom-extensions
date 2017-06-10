<?php
// Copyright (C) 2016 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Lesser General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
/**
 * @copyright   Copyright (C) 2016 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Adding an entry in the admin menu
 */ 
class StdEmailSynchro extends ModuleHandlerAPI
{
	public static function OnMenuCreation()
	{
		// Add an item in the admin menus
		if (UserRights::IsAdministrator())
		{
			$oAdminMenu = new MenuGroup('AdminTools', 80 /* fRank */);
			new OQLMenuNode('MailInboxes', 'SELECT MailInboxStandard', $oAdminMenu->GetIndex(), 20 /* fRank */, true);
			new OQLMenuNode('MailInboxPolicies', 'SELECT MailInboxPolicy', $oAdminMenu->GetIndex(), 21 /* fRank */, true);
		}
	}
}