#!/usr/bin/perl
use XML::Simple;
use LWP::Simple;
use Data::Dumper;
use DBI;

my %config = do './config.pl';

$database = $config{database};
$host = $config{host};
$user = $config{user};
$pass = $config{pass};

$dsn = "DBI:mysql:database=$database;host=$host";
$dbh = DBI->connect($dsn, $user, $pass, {'RaiseError' => 1, 'AutoCommit' => 0});

$xml = new XML::Simple;
$data2 = get('http://svc.metrotransit.org/NexTrip/VehicleLocations/0');
$data = $xml->XMLin($data2);

$dbh->do("TRUNCATE TABLE VehicleLocations;");

foreach $e (@{$data->{VehicleLocation}})
{
    if($e->{Terminal} =~ /^[a-zA-Z0-9]{1,30}$/)
    {
        $dbh->do("INSERT INTO VehicleLocations VALUES ('', '$e->{Route}', '$e->{VehicleLatitude}', '$e->{VehicleLongitude}', '$e->{BlockNumber}', '$e->{LocationTime}', '$e->{Direction}', '$e->{Terminal}');");
    } else {
        $dbh->do("INSERT INTO VehicleLocations VALUES ('', '$e->{Route}', '$e->{VehicleLatitude}', '$e->{VehicleLongitude}', '$e->{BlockNumber}', '$e->{LocationTime}', '$e->{Direction}', '');");
    }
}

$dbh->commit();
$dbh->disconnect();
