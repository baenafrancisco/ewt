<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>PointsOfInterest</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
</head>
<body>

<div class="container">
	<h1>Points of interest<a href="./req4.php"><button class="btn btn-primary pull-right">Requirement 4 &gt;</button></a></h1>
	<form>
		<label for="regions">Filter by region:</label>
		<select id="regions">
			<option value='?' selected>---- All ----</option>
		</select>
	</form>
	<div id="map1" style="width:100%; height:500px"> </div>
</div>

<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script type="text/javascript">

	var map;
	var regions_select = document.getElementById('regions');
	var markers = [];

	if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) &&
		navigator.geolocation) {
		 navigator.geolocation.getCurrentPosition (processPosition);

	}

    function processPosition(pos){
    	var new_pos = new L.LatLng(pos.coords.latitude, pos.coords.longitude);
    	map.setView(new_pos, 14);
	}

	function load_regions(){
		// Loads the regions_select with all regions from the database
		var regions_request = new XMLHttpRequest();
	    regions_request.addEventListener ("load", receive_regions);
	    regions_request.open("GET" , "./api/poi/regions/");
	    regions_request.send();
	}

	function receive_regions(e){
		var types = JSON.parse(e.target.responseText);

		for (var i = 0; i<types.length; i++){
			regions_select.innerHTML += '<option>' + types[i] + '</option>';
		}

	}

	var regions_select_handler = function(){
		//Handles when an user changes the region select
		if (regions_select.value=='?'){
			ajax_get_pois();
		} else {
			ajax_get_pois(regions_select.value);
		}
	}

	regions_select.onchange = regions_select_handler;

	function ajax_get_pois(region){
		//Function to make the API call
		var all_pois_request = new XMLHttpRequest();
	    all_pois_request.addEventListener ("load", receivePOIs);
	    var url = "./api/poi/";
	    if (region!==undefined){
	    	url += "?region=" + region;
	    }
	    all_pois_request.open("GET" , url);
	    all_pois_request.send();

	}

	function receivePOIs(e){
		clear_markers();
		//Receives the POIs from the asynchronous petition
		var pois = JSON.parse(e.target.responseText);
		for (var i = 0; i<pois.length; i++){
			add_marker(pois[i]);
		}
	}

	function add_marker(poi){
		// Adds a marker for an spe
		var marker = new L.Marker(new L.LatLng(poi.lat, poi.lon));
		map.addLayer(marker);
		markers.push(marker);
	}

	function clear_markers(){
		// Removes all markers from the map
		for (var i=0; i<markers.length; i++){
			map.removeLayer(markers[i]);
		}
		markers = [];
		
	}


	function init(){

		load_regions();
		ajax_get_pois();
	    
		map = new L.Map ("map1");
	    var attrib="Map data copyright OpenStreetMap contributors, Open Database Licence";

	    var layerOSM = new L.TileLayer
	        ("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
	            { attribution: attrib } );
	            
	    map.addLayer(layerOSM);

	    var pos = new L.LatLng(50.9,-1.4);

	    map.setView(pos, 14);

	    map.on("click",click_handler)
	}

	function click_handler(e){
	    console.log("Click @ <" + e.latlng.lat + ", " + e.latlng.lng + ">");
	}

window.onload = init;


</script>
</body>
</html>