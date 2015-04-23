<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>PointsOfInterest</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

</head>
<body>

<div class="container">
<h1><a href="./">Points of interest</a></h1>

<form>
	<label for="types">Filter by type:</label>
	<select id="types">
		<option value='?' selected>--- All ---</option>
	</select>
</form>

<table class="table table-striped" id="pois">
	
</table>
</div>

<script type="text/javascript">
	

	var pois_table = document.getElementById('pois');
	var types_select = document.getElementById('types');



	function init(){

	    var types_request = new XMLHttpRequest();
	    types_request.addEventListener ("load", receiveTypes);
	    types_request.open("GET" , "./api/poi/types/");
	    types_request.send();

	    ajax_get_pois();
	    
	}



	function receiveTypes(e){
		var types = JSON.parse(e.target.responseText);

		for (var i = 0; i<types.length; i++){
			types_select.innerHTML += '<option>' + types[i] + '</option>';
		}

	}

	function ajax_get_pois(type){

		clear_pois_table();
		
		var all_pois_request = new XMLHttpRequest();
	    all_pois_request.addEventListener ("load", receivePOIs);
	    var url = "./api/poi/";
	    if (type!==undefined){
	    	url += "?type=" + type;
	    }
	    all_pois_request.open("GET" , url);
	    all_pois_request.send();

	}

	function receivePOIs(e){
		var pois = JSON.parse(e.target.responseText);
		for (var i = 0; i<pois.length; i++){
			add_poi_to_table(pois[i]);
		}
	}

	function clear_pois_table(){

		pois_table.innerHTML = ['<tr>',
								'<th>Name</th>',
								'<th>Type</th>',
								'<th>Country</th>',
								'<th>Region</th>',
								'<th>Latitude</th>',
								'<th>Longitude</th>',
								'</tr>'].join('\n');
	}

	function add_poi_to_table(poi){

		var row = pois_table.insertRow(-1);

		var name_cell = row.insertCell(-1);
		var type_cell = row.insertCell(-1);
		var country_cell = row.insertCell(-1);
		var region_cell = row.insertCell(-1);
		var lat_cell = row.insertCell(-1);
		var lon_cell = row.insertCell(-1);

		name_cell.innerHTML = poi.name;
		type_cell.innerHTML = poi.type;
		country_cell.innerHTML = poi.country;
		region_cell.innerHTML = poi.region;
		lat_cell.innerHTML = poi.lat;
		lon_cell.innerHTML = poi.lon;

	}

	var types_select_handler = function(){
		if (types_select.value=='?'){
			ajax_get_pois();
		} else {
			ajax_get_pois(types_select.value);
		}
	}

	types_select.onchange = types_select_handler;

	window.onload = init;
</script>
</body>
</html>