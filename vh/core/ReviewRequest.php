<?php
/*
POIRequest
*/
$connection = curl_init();

$api_path = '/ewt/poi/api/review/';
switch ($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		/*
		GET Request Handler
		Performs a GET Request to PointsOfInterest's Reviews

		Parameters
		  - id: id of the review to return (optional)
		  - poi_id: if specified, filters the POIs by region (optional)

			If id is specified, poi_id will be ignored.
		
		Response
			- Returns a list of Reviews.
		*/

		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$poi_id = isset($_GET['poi_id']) ? $_GET['poi_id'] : null;

		$parameters = '';
	 	if ($id){
	 		$parameters = '?id=' . $id; 
	 	} else {
	 		if ($poi_id){
	 			$parameters = '?poi_id=' . $poi_id; 
	 		}
	 	}
	 	$api_path = $api_path . $parameters;
		curl_setopt($connection, CURLOPT_URL, 'http://' . $_SERVER['HTTP_HOST'] . $api_path);
		curl_setopt($connection,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($connection,CURLOPT_HEADER, 0);
		header("Content-type: application/json");
		echo curl_exec($connection);

		break;

	case 'POST':

		if (isset($_SERVER["PHP_AUTH_USER"]) &&
			isset($_SERVER["PHP_AUTH_PW"])&&
			isset($_POST['poi_id']) &&
			isset($_POST['review']) ){

			$username = $_SERVER["PHP_AUTH_USER"];
			$password = $_SERVER["PHP_AUTH_PW"];
			$poi_id = $_POST['poi_id'];
			$review = $_POST['review'];

			$post_data = array(	"poi_id" => $poi_id, "review" => $review);

			curl_setopt($connection, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($connection, CURLOPT_URL, 'http://' . $_SERVER['HTTP_HOST'] . $api_path);
			curl_setopt($connection,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($connection,CURLOPT_POSTFIELDS,$post_data);
			$response = curl_exec($connection);
			$httpcode = curl_getinfo($connection, CURLINFO_HTTP_CODE); 
			header($_SERVER['SERVER_PROTOCOL'] . ' ' . $httpcode, true, $httpcode);
			echo $response;
		} else {
			header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
			echo "<strong>Error 400</strong>: Bad request.<br>";
		}

		break;

	default:
		// Request not supported
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
		echo "<strong>Error 400</strong>: Request method not supported.";
		break;
}

curl_close($connection);
?>