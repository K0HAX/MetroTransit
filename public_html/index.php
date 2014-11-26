<html>
<head>
<title>Metro Transit Bus Locator</title>
<style>
	table, th, td {
		border: 1px solid #00FF00;
		text-align: center;
	}
</style>
</head>
<body bgcolor="#000000" text="#00FF00">
<div style="text-align: center;">
<table>
<tr>
<th>Route</th>
<th>Description</th>
<th>Latitude</th>
<th>Longitude</th>
<th>Time Reported</th>
<th>Direction</th>
<th>Terminal</th>
<th>Provider</th>
</tr>
<?php
require('config.php');
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

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		switch ($row["Direction"]) {
			case 1:
				$direction = 'South';
				break;
			case 2:
				$direction = 'East';
				break;
			case 3:
				$direction = 'West';
				break;
			case 4:
				$direction = 'North';
				break;
			default:
				$direction = "INVALID";
				break;
		}
		echo("<tr>\n");
		echo("<td>" . $row["Route"] . "</td><td>" . $row["Description"] . "</td><td>" . $row["VehicleLatitude"] . "</td><td>" . $row["VehicleLongitude"] . "</td><td>" . $row["LocationTime"] . "</td><td>" . $direction . "</td><td>" . $row["Terminal"] . "</td><td>" . $row["Provider"] . "</td>\n");
		echo("</tr>\n");
	}
}
?>
</table>
</body>
</html>

