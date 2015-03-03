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
		<div id='venue-reviews'>
		</div>
	</div>

	<div class="col-md-5">
		Venue map
	</div>	
</div>



</div>

<script type="text/javascript">

	var venue_id = '<?php echo isset($_GET["id"])?$_GET["id"]:"undefined" ?>';
	var v_name = document.getElementById('venue-name');
	var region = document.getElementById('venue-region');
	var country = document.getElementById('venue-country');
	var description = document.getElementById('venue-description');
	var reviews_box = document.getElementById('venue-reviews');
	var bc_name = document.getElementById('venue-bc-name'); 

	var init_venue = function(){
		if (venue_id!='undefined'){
			var venue_request = new XMLHttpRequest();
	    	venue_request.addEventListener ("load", get_venue);
	    	var url = "../../poi/api/poi/?id=" + venue_id;
	    	venue_request.open("GET" , url);
	    	venue_request.send();
		}
	}


	var get_venue = function(e){
		var venues = JSON.parse(e.target.responseText);

		if (venues.length>0){
			var venue = venues[0];
			v_name.innerHTML = venue.name;
			region.innerHTML = venue.region;
			country.innerHTML = venue.country;
			description.innerHTML = venue.description;
			bc_name.innerHTML = venue.name;
		}
	}

	var fetch_reviews = function(){
		if (venue_id!='undefined'){
			var reviews_request = new XMLHttpRequest();
	    	reviews_request.addEventListener ("load", get_reviews);
	    	var url = "../../poi/api/review/?poi_id=" + venue_id;
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

	function init(){
		init_venue();
		fetch_reviews();
	}

	window.onload = init;
	
</script>
</body>
</html>