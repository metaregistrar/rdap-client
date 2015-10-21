<?php

include('Protocol/bootstrap.php');


$search = 59981;
$protocol = rdap::ASN;
$search = '81.4.97.200';
$protocol = rdap::IPV4;

try {
    $rdap = new rdap($protocol);
    $test = $rdap->search($search);
    echo "handle: ".$test->getHandle()."\n";
    echo "name: ".$test->getName()."\n";
    echo "country: ".$test->getCountry()."\n";
    echo "type: ".$test->getType()."\n";
    echo "port 43 service: ".$test->getPort43()."\n";
    var_dump($test);
} catch (rdapException $e) {
    echo "ERROR: ".$e->getMessage()."\n";
}
