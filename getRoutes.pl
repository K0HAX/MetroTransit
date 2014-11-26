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
$dbh = DBI->connect($dsn, $user, $pass, {'RaiseError' => 1});

$xml = new XML::Simple;
$data2 = get('http://svc.metrotransit.org/NexTrip/Routes');
$data = $xml->XMLin($data2);

$dbh->do("TRUNCATE TABLE Routes;");

foreach $e (@{$data->{NexTripRoute}})
{
    $dbh->do("INSERT INTO Routes VALUES (\"\", \"$e->{Route}\", \"$e->{Description}\", \"$e->{ProviderID}\");");
}
