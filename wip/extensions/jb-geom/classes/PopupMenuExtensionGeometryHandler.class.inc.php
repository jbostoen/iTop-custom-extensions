<?php

/**
 * @copyright   Copyright (C) 2019 Jeffrey Bostoen
 * @license     https://www.gnu.org/licenses/gpl-3.0.en.html
 * @version     -
 *
 * Defines PopupMenuExtensionGeometryHandler
 */
 

/**
* Note: for compatibility with 2.5, you need to have some specific definitions ( DBObjectSet $oSet, WebPage $oPage )
**/

/**
* Class PopupMenuExtensionGeometryHandler. Adds popup menu (open OpenStreetMap.org, copy as GeoJSON)
*/

class PopupMenuExtensionGeometryHandler implements iPopupMenuExtension
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
							// For any object, add a menu "Google this..." that opens google search in another window
							// with the name of the object as the text to search
							
							// Only for objects with features (if none specified yet, don't show menu. 'Other actions' is not shown when modifying.)
							
							// Get list of attributes
							$aAttributeList = Metamodel::GetAttributesList(get_class($param));
							
							if( in_array('geom', $aAttributeList) == true ) {
								if( $param->Get('geom') != '' ) {
								
									// Add a separator
									$aResult[] = new SeparatorPopupMenuItem(); // Note: separator does not work in iTop 2.0 due to Trac #698, fixed in 2.0.1
	 
									// Add a new menu item that triggers a custom JS function defined in our own javascript file: js/sample.js
									$sModuleDir = basename(dirname(dirname(__FILE__)));
									$sJSFileUrl = utils::GetAbsoluteUrlModulesRoot().$sModuleDir.'/js/geometry_actions.js';
									
									// It doesn't seem to be necessary to self-check if $aIncludeJSFiles should only include the JavaScript file once.
									$aResult[] = new JSPopupMenuItem(/* $sUUID */ 'geometryHandler_Open_OpenStreetMap', /* $sLabel */ Dict::S('UI:Geom:Menu:ShowOpenStreetMap'), /* $sJSCode */ 'geometryHandler["common"].Show_OpenStreetMap()', /* $aIncludeJSFiles */ array($sJSFileUrl));
									$aResult[] = new JSPopupMenuItem(/* $sUUID */ 'geometryHandler_Copy_As_GeoJSON', /* $sLabel */ Dict::S('UI:Geom:Menu:CopyAsGeoJSON'), /* $sJSCode */ 'geometryHandler["common"].Copy_As_GeoJSON()', /* $aIncludeJSFiles */ array($sJSFileUrl));
	 
								}
							}
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
