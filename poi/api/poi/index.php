<?php

/*
Points of Interest
*/

include "../../core/db.php";
$db = new DBManager();


//GET, POST, PUT or DELETE
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		
		/*
		GET request handler

		- region returns just the types available in a region

		*/

		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$region = isset($_GET['region']) ? $_GET['region'] : null;
		$type = isset($_GET['type']) ? $_GET['type'] : null;

		$where = null;


		if ($id){
			//If an ID is given
			$where="id = '$id'";
		} else {
			//If not
			if ($region && $type){
				$where="region = '$region' AND type = '$type'";
			} elseif ($region){
				$where="region = '$region'";
			} elseif ($type){
				$where="type = '$type'";
			} 
		}
		
		header("Content-type: application/json");
		if ($where){
			echo json_encode($db->select('pointsofinterest',array('*'), $where));
		} else {
			echo json_encode($db->select('pointsofinterest'));
		}

		break;

	case 'POST':
			/*
			POST REQUEST (example)
			
			  - name: "The Bugle",
			  - type: "pub",
			  - country: "England",
			  - region: "Hampshire",
			  - lon: "-1.31342",
			  - lat: "50.8582",
			  - description: "A very interesting place"
			*/
		$error = true;

		if (isset($_POST['name']) &&
			isset($_POST['type']) &&
			isset($_POST['country']) &&
			isset($_POST['region']) &&
			isset($_POST['lon']) &&
			isset($_POST['lat']) &&
			isset($_POST['description'])){

			// All fields in the request
			$values = array('name' => $_POST['name'] ,
							'type' => $_POST['type'] ,
							'country' => $_POST['country'] ,
							'region' => $_POST['region'] ,
							'lon' => $_POST['lon'] ,
							'lat' => $_POST['lat'] ,
							'description' => $_POST['description']);
			// And insert it into de database
			$db->insert('pointsofinterest', $values);
			$error = false;

		} 

		if ($error) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
			echo "<strong>Error 400</strong>: Bad request.<br>";
		} else {
			header($_SERVER['SERVER_PROTOCOL'] . ' 201 Created', true, 201);
			echo "<strong>201</strong>: The request has been fulfilled and resulted in a new resource being created.<br>";
		}

		break;

	case 'PUT':

		break;

	case 'DELETE':

		break;
	
	default:
		// Request not supported
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
		echo "<strong>Error 400</strong>: Request method not supported.";
		break;
}


?>