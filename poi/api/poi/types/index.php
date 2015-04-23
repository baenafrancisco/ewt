<?php
/*
Types of POI
*/

include "../../../core/db.php";
$db = new DBManager();

switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		/*
		GET Request Handler
		Returns a list of types of POIs

		Parameters
		  - region: limits the results to an specific region (optional)
		
		Response
		  - Returns a list of types of POIs.
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