<?php

/*
Reviews
*/

include "../../core/db.php";
$db = new DBManager();


//GET, POST, PUT or DELETE
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		
		/*
		GET request handler

		-Optional fields-

		  - id (gets any id)

		  - poi_id (gets all reviews for a givven poi)

		*/

		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$poi_id = isset($_GET['poi_id']) ? $_GET['poi_id'] : null;

		$where = null;

		header("Content-type: application/json");
		if ($id){
			//If an ID is given
			$where="id = '$id'";
		} elseif ($poi_id){
			$where="poi_id = '$poi_id'";
		}
		
		header("Content-type: application/json");
		if ($where){
			echo json_encode($db->select('poi_reviews',array('*'), $where));
		} else {
			echo json_encode($db->select('poi_reviews'));
		}

		break;

	case 'POST':
			/*
			POST REQUEST (example)

			  - poi_id: 123,
			  - review: "Many nice place, darlin",

			*/
		$error = true;

		if (isset($_POST['poi_id']) &&
			isset($_POST['review'])){

			// All fields in the request
			$values = array('poi_id' => $_POST['poi_id'] ,
							'review' => $_POST['review']);

			// Check if the point of interest exists…
			if ($db->exists('pointsofinterest', $_POST['poi_id'])){
				// And insert it into de database
				$db->insert('poi_reviews', $values);
				$error = false;
			}
				
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
		$request_vars = array();

		parse_str(getRAWParameters(), $request_vars);

		$error = true;

		if (isset($request_vars['id'])&&($db->exists('poi_reviews', $request_vars['id']))){

			// TODO: try/catch
			$db->delete('poi_reviews', $request_vars['id']);
			$error = false;
		}

		if ($error) {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
			echo "<strong>Error 400</strong>: Bad request.<br>";
		} else {
			header($_SERVER['SERVER_PROTOCOL'] . ' 200 Deleted', true, 200);
			echo "<strong>200 Deleted</strong>: the resource has been deleted.<br>";
		}

		break;
	
	default:
		// Request not supported
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
		echo "<strong>Error 400</strong>: Request method not supported.";
		break;
}

function getRAWParameters(){
    if (strlen(trim($content = file_get_contents('php://input')))===0){
      $content = false;
    }
  return $content;
}

?>