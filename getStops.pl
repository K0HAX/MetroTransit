#!/usr/bin/perl
use File::Basename;
use XML::Simple;
use LWP::Simple;
use Data::Dumper;
use DBI;

my $dirname = dirname(__FILE__);
my %config = do "$dirname/config.pl";

$database = $config{database};
$host = $config{host};
$user = $config{user};
$pass = $config{pass};

$dsn = "DBI:mysql:database=$database;host=$host";
$dbh = DBI->connect($dsn, $user, $pass, {'RaiseError' => 1, 'AutoCommit' => 0});

my $file = "stops.csv";
my $fh = Tie::Handle::CSV->new ($file, header => 1);

$dbh->do("TRUNCATE TABLE Stops;");

while (my $csv_line = <$fh>)
{
    $dbh->do("INSERT INTO Stops VALUES (\"$csv_line->{stop_id}\", \"$csv_line->{stop_name}\", \"$csv_line->{stop_desc}\", \"$csv_line->{stop_lat}\", \"$csv_line->{stop_lon}\", \"$csv_line->{stop_street}\", \"$csv_line->{stop_city}\", \"$csv_line->{stop_region}\", \"$csv_line->{stop_postcode}\", \"$csv_line->{stop_country}\", \"$csv_line->{zone_id}\", \"$csv_line->{wheelchair_boarding}\", \"$csv_line->{stop_url}\");");
}

$dbh->commit();
$dbh->disconnect();
