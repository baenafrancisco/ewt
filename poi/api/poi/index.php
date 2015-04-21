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
		GET Request Handler
		Returns a list of POIs (or just one)

		Parameters
		  - id: id of the point of interest to return (optional)
		  - region: if specified, filters the POIs by region (optional)
		  - type: if specified, filters the POIs by type (optional)

		If id is specified, region and type parameters will be ignored.
		
		Response
			- Returns a list of POIs.
			- If not POIs are specified, returns an empty list.
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
			POST Request Handler
			Returns a list of POIs (or just one)

			Parameters
			  - name: name of the Point of Interest (required)
			  - type: type of Point of Interest (required)
			  - country: country where the Point of Interest is located (required)
			  - region: region of the Point of Interest (required)
			  - lon: longitude of the Point of Interest (required)
			  - lat: latitude of the Point of Interest (required)
			  - description: description of the Point of Interest (required)

			If id is specified, region and type parameters will be ignored.
			
			Response
				- HTTP 201 - If a new resource has been created
				- HTTP 400 - For Bad Requests
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
							'type' => $_POST['type'] , //NEEDS TO BE CHECKED
							'country' => $_POST['country'] ,
							'region' => $_POST['region'] ,
							'lon' => $_POST['lon'] , // CHECK -180 to 180
							'lat' => $_POST['lat'] , // CHECK -90 to 90 
							'description' => $_POST['description']);
			// And insert it into de database
			$error = !$db->insert('pointsofinterest', $values);
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
		parse_str(file_get_contents('php://input'), $_PUT);

		break;

	case 'DELETE':
		// As PHP doesn't recognizes that
		parse_str(file_get_contents('php://input'), $_DELETE);

		break;
	
	default:
		// Request not supported
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
		echo "<strong>Error 400</strong>: Request method not supported.";
		break;
}


?>