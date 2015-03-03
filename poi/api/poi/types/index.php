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

		-- Optional values -- 

		- region limits the resuts to an specific region
		*/
		header("Content-type: application/json");

		$region = isset($_GET['region']) ? $_GET['region'] : null;

		$values = array();

		if ($region){
			$results = $db->select('pointsofinterest',array('DISTINCT type as v'), "region='$region'");
		} else {
			$results = $db->select('pointsofinterest',array('DISTINCT type as v'));
		}
		foreach ($results as $v) {
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