<html>
<head>
<title>Metro Transit Bus Locator</title>
<style type="text/css">
	table, th, td {
		border: 1px solid #00FF00;
		text-align: center;
	}
	#map-canvas { height: 100%; margin: 0; padding: 0; }
</style>

<script type="text/javascript"
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrWJCRX05c9gq6abDXZ1Qg6zGmSCUU0Q4">
</script>
<script type="text/javascript">
	function initialize() {
		var bounds = new google.maps.LatLngBounds();
		var mapOptions = {
			mapTypeId: 'roadmap'
		};
		var map = new google.maps.Map(document.getElementById('map-canvas'),
			mapOptions);
	
	var markers = [
	<?php
			include('config.php');
			$conn = new mysqli($host, $user, $pass, "MetroTransit");
			if ($conn->connect_error) {
			        die("Connection failed: " . $conn->connect_error);
			}
			$sql = "SELECT `VehicleLocations`.`Route`, `Routes`.`Description`, `VehicleLocations`.`VehicleLatitude`, `VehicleLocations`.`VehicleLongitude`, `VehicleLocations`.`BlockNumber`, `VehicleLocations`.`LocationTime`, `VehicleLocations`.`Direction`, `VehicleLocations`.`Terminal`, `Providers`.`Name` AS `Provider`
				FROM VehicleLocations
				LEFT JOIN `Routes` ON (`VehicleLocations`.`Route` = `Routes`.`Route`)
				LEFT JOIN `Providers` ON (`Routes`.ProviderID = `Providers`.ProviderID)
				ORDER BY Route;";

			$result = $conn->query($sql);
			$num = 0;
			if ($result->num_rows > 0) {
			        while($row = $result->fetch_assoc()) {
					if($num == 0) {
						echo("['" . $row['Description'] . "', " . $row['VehicleLatitude'] . ", " . $row['VehicleLongitude'] . "]");
						$num = 1;
					} else {
						echo(",\n['" . $row['Description'] . "', " . $row['VehicleLatitude'] . ", " . $row['VehicleLongitude'] . "]");
					}
					//echo("var " . $row['BlockNumber'] . "LatLon = new google.maps.LatLng(" . $row['VehicleLatitude'] . "," . $row['VehicleLongitude'] . ");\n");
					//echo("var marker" . $row['BlockNumber'] . " = new google.maps.Marker({ position: " . $row['BlockNumber'] . "LatLon, map: map});\n");
					//echo("marker" . $row['BlockNumber'] . ".setMap(map);\n\n");
				}
			}
		?>
	];

	for(i = 0; i < markers.length; i++) {
		var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
		bounds.extend(position);
		marker = new google.maps.Marker({
			position: position,
			map: map,
			title: markers[i][0]
		});
		map.fitBounds(bounds);
	}
	
	var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
		//this.setZoom(14);
		google.maps.event.removeListener(boundsListener);
	});
}
	google.maps.event.addDomListener(window, 'load', initialize);
</script>

</head>
<body>
<div id="map-canvas"></div>
</body>
</html>

