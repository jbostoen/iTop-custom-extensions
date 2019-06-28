<?php

	if(isset($_REQUEST['action']) == true) {

		switch($_REQUEST['action']) {
			
			case 'remember_last_used_basemap':
			
				if(isset($_REQUEST['data']['basemap']) == true && isset($_REQUEST['data']['class']) == true) {
					// Remember for 30 days by default.
					setcookie('itop_geometryHandler_basemap_used_for_'.$_REQUEST['data']['class'], $_REQUEST['data']['basemap'], time()+3600*24*30, '/');
					echo json_encode(Array('action' => $_REQUEST['action'], 'request' => $_REQUEST, 'msg' => 'Cookie set'));
				}
				else {
					echo json_encode(Array('action' => $_REQUEST['action'], 'request' => $_REQUEST, 'msg' => 'Cookie NOT set'));
				}
				break;
			
			default:
				echo json_encode(Array('error' => 'Unknown action', 'request' => $_REQUEST));
				
		}
		
	}
	else {
		echo json_encode(Array());
	}
