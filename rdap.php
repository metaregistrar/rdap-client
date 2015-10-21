<?php

require('Protocol/autoload.php');


$search = 59980;
$protocol = rdap::ASN;
$search = '81.4.97.200';
$protocol = rdap::IPV4;

try {
    $rdap = new rdap($protocol);
    $test = $rdap->search($search);
    echo "handle: ".$test->getHandle()."\n";
    echo "name: ".$test->getName()."\n";
    //echo "country: ".$test->getCountry()."\n";
    echo "type: ".$test->getType()."\n";
    echo "port 43 service: ".$test->getPort43()."\n";
    echo "rdap conformance: \n";
    foreach ($test->rdapConformance as $conformance) {
        echo $conformance."\n";
    }
    foreach ($test->rdapEntities as $entity) {
      var_dump($entity);
    }
    foreach ($test->rdapLinks as $link) {
        var_dump($link);
    }
    foreach ($test->rdapNotices as $notice) {
        var_dump($notice);
    }
} catch (rdapException $e) {
    echo "ERROR: ".$e->getMessage()."\n";
}
