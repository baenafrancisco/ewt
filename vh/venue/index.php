<?php
if (!isset($_GET['id'])){
	header("Location: ../");
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>VisitHampshire - Venue</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

</head>
<body>

<div class="container">
<h1>VisitHampshire</a></h1>
<ol class="breadcrumb">
  <li><a href="../">VisitHampshire</a></li>
  <li id='venue-bc-name' class="active">Venue name</li>
</ol>
<div class="row">
	<div class="col-md-7">
		<h3 id='venue-name'>Venue name</h3>
			<h5><span id='venue-region'>Region</span>, <em><span id='venue-country'>Country</span></em></h5>
			<p id='venue-description'>………</p>
			<hr>
		<h4>Reviews</h4> 
		<button class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-target="#review-modal">Add review</button>
		<div id='venue-reviews'>
		</div>
	</div>

	<div class="col-md-5">
		<div id="map-canvas" style="width:100%; height:500px;"></div>
	</div>	
</div>



</div>

<div class="modal fade" id="review-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add review</h4>
      </div>
      <div class="modal-body">
      	User: <input type="text" id="username"> Password: <input type="password" id="password"><br><br>
        <textarea id="new-review-text" style="width:100%;"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button onclick="add_review()" type="button" class="btn btn-primary">Add review</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript">

	var venue;
	var venue_id = '<?php echo isset($_GET["id"])?$_GET["id"]:"undefined" ?>';
	var v_name = document.getElementById('venue-name');
	var region = document.getElementById('venue-region');
	var country = document.getElementById('venue-country');
	var description = document.getElementById('venue-description');
	var reviews_box = document.getElementById('venue-reviews');
	var bc_name = document.getElementById('venue-bc-name'); 
	/* Add review vars */
	var new_review_text = document.getElementById('new-review-text'); 
	var username_text = document.getElementById('username'); 
	var password_text = document.getElementById('password'); 
	var map;


	/* GET VENUE DATA */
	var init_venue = function(){
		if (venue_id!='undefined'){
			var venue_request = new XMLHttpRequest();
	    	venue_request.addEventListener ("load", get_venue);
	    	var url = "../core/POIRequest.php?id=" + venue_id;
	    	venue_request.open("GET" , url);
	    	venue_request.send();
		}
	}


	var get_venue = function(e){
		var venues = JSON.parse(e.target.responseText);

		if (venues.length>0){
			venue = venues[0];
			v_name.innerHTML = venue.name;
			region.innerHTML = venue.region;
			country.innerHTML = venue.country;
			description.innerHTML = venue.description;
			bc_name.innerHTML = venue.name;
			mapcenter = new google.maps.LatLng(venue.lat,venue.lon);
			initialize_map(mapcenter);
		}
	}

	/* GET REVIEWS */

	var fetch_reviews = function(){
		if (venue_id!='undefined'){
			var reviews_request = new XMLHttpRequest();
	    	reviews_request.addEventListener ("load", get_reviews);
	    	var url = "../core/ReviewRequest.php?poi_id=" + venue_id;
	    	reviews_request.open("GET" , url);
	    	reviews_request.send();
		}
	}

	var get_reviews = function(e){
		var reviews = JSON.parse(e.target.responseText);
		if (reviews.length>0){
			reviews_box.innerHTML=''; // Clear reviews
			for (var i = 0; i < reviews.length; i++){
				var review = document.createElement("blockquote");
				var t = document.createTextNode(reviews[i].review);
				review.appendChild(t);
				reviews_box.appendChild(review);
				
			};		
		} else {
			reviews_box.innerHTML='<p class="lead">There are no reviews at the moment!</p>';
		}
	}


	/* ADD REVIEW */

	var add_review = function(){
		var review = new_review_text.value;
		var user = username_text.value;
		var pass = password_text.value;

		if (review!=''){
			var add_review_request = new XMLHttpRequest();
	    	add_review_request.addEventListener ("load", add_review_handler);
	    	var url = "../core/ReviewRequest.php";
	    	add_review_request.open("POST" , url, true);
	    	add_review_request.setRequestHeader("Authorization","Basic " + btoa(user+":"+pass));
	    	var data = new FormData();
	    	data.append("poi_id", venue_id);
	    	data.append("review", review);
	    	add_review_request.send(data);
	    	$('#review-modal').modal('hide');
		} else {
			alert("Empty review! Write something!");
		}
	}

	var add_review_handler = function(e){
		if (e.target.status==201){
			fetch_reviews();
			new_review_text.value = '';
		} else if (e.target.status==401) {
			alert("Your username or password is incorrect!");
			$('#review-modal').modal('show');
		} else{
			alert("Something went wrong with your review! Try again!");
			$('#review-modal').modal('show');
		}
		
	}


	

	function initialize_map(mapcenter) {
	  	var mapOptions = {
	    	zoom: 12,
	    	center: mapcenter,
	    	scrollwheel: false
	  	};
	  	map = new google.maps.Map(document.getElementById('map-canvas'),
	      mapOptions);

	  	var marker = new google.maps.Marker({
      			position: mapcenter,
      			map: map,
      			title: venue.name
  			});	  
	}  
	

	function init(){
		init_venue();
		fetch_reviews();
	}

	window.onload = init;
	
</script>
</body>
</html>