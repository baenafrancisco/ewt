<?php
/*
POIRequest
*/
include('cURLConfig.php');

$connection = curl_init();

$api_path = API_ROOT . '/poi/';
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		/*
		GET Request Handler
		Performs a GET Request to POI API

		Parameters
		  - id: id of the point of interest to return (optional)
		  - type: if specified, filters the POIs by type (optional)

			If id is specified, type parameter will be ignored.
		
		Response
			- Returns a list of POIs filtered by the search criteria.
		*/

		$id = isset($_GET['id']) ? $_GET['id'] : null;
	 	$type = isset($_GET['type']) ? $_GET['type'] : null;
	 	$parameters = '?region=Hampshire';
	 	if ($id){
	 		$parameters = '?id=' . $id; 
	 	} else {
	 		if ($type){
	 			$parameters = '?region=Hampshire&type=' . $type; 
	 		}
	 	}
	 	$api_path = $api_path . $parameters;
		curl_setopt($connection, CURLOPT_URL, 'http://' . $_SERVER['HTTP_HOST'] . $api_path);
		curl_setopt($connection,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($connection,CURLOPT_HEADER, 0);
		header("Content-type: application/json");
		echo curl_exec($connection);

		break;

	default:
		// Request not supported
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
		echo "<strong>Error 400</strong>: Request method not supported.";
		break;
}

curl_close($connection);
?>