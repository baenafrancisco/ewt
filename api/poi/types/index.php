<?php

/*
Types of POI
*/

include "../../../core/db.php";
$db = new DBManager();


//GET
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		
		/*
		GET request handler
		*/
		header("Content-type: application/json");
		$values = array();
		foreach ($db->select('pointsofinterest',array('DISTINCT type as v')) as $v) {
			$values[] = $v['v'];
		}
		echo json_encode($values);

		break;

	default:
		// Request not supported
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
		echo "<strong>Error 400</strong>: Request method not supported.";
		break;
}


?>