<?php

// post_review.php
// Ajax POST btw PointsOfInterest & VisitHampshire
switch ($_SERVER['REQUEST_METHOD']) {

	case 'POST':	

		if (isset($_POST['poi_id']) &&
			isset($_POST['review'])){

			$connection = curl_init();
			curl_setopt($connection, CURLOPT_URL, "http://localhost/ewt/poi/api/review/");
			$poi_id = $_POST['poi_id'];
			$review = $_POST['review'];
			$post_data = array(	"poi_id" => $poi_id,
			     				"review" => $review);
			curl_setopt($connection,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($connection,CURLOPT_POSTFIELDS,$post_data);
			$response = curl_exec($connection);
			$httpcode = curl_getinfo($connection, CURLINFO_HTTP_CODE); 
			curl_close($connection);
			header($_SERVER['SERVER_PROTOCOL'] . ' ' . $httpcode, true, $httpcode);
			echo $response;
			break;

		} else {

			// Request not supported
			header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
			echo "<strong>Error 400</strong>: Malformed request.";
			break;



		}

		

		break;


	default:
		// Request not supported
		header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
		echo "<strong>Error 400</strong>: Request method not supported.";
		break;

}



?>