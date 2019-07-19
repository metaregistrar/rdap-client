<?php


/**
 *
 */
require('Protocol/autoload.php');


//$search = 59980;
//$protocol = Metaregistrar\RDAP\rdap::ASN;
//$search = 'RIPE-NCC-END-MNT';
//$search = '81.4.97.200';
//$search = '196.216.2.6';
$search = '8.8.4.4';
//$search = 'gamma.com';
$protocol = Metaregistrar\RDAP\rdap::IPV4;
//$protocol = Metaregistrar\RDAP\rdap::DOMAIN;

try {
    $rdap = new Metaregistrar\RDAP\rdap($protocol);
    $test = $rdap->search($search);

    if ($test) {
        echo "class name: ".$test->getClassname()."\n";
        echo "handle: ".$test->getHandle()."\n";
        echo "LDH (letters, digits, hyphens) name: ".$test->getLDHName()."\n";
        //echo "name: ".$test->getName()."\n";
        //echo "country: ".$test->getCountry()."\n";
        //echo "type: ".$test->getType()."\n";
        //echo "port 43 service: ".$test->getPort43()."\n";
        if (is_array($test->getNameservers())) {
            echo "\nNameservers:\n";
            foreach ($test->getNameservers() as $nameserver) {
                $nameserver->dumpContents();
            }
        }
        if (is_array($test->getSecureDNS())) {
            echo "DNSSEC:\n";
            foreach ($test->getSecureDNS() as $dnssec) {
                $dnssec->dumpContents();
            }
            echo "\n";
        }
        echo "rdap conformance: \n";
        foreach ($test->getConformance() as $conformance) {
            $conformance->dumpContents();
        }
        echo "\n";
        if (is_array($test->getEntities())) {
            echo "Entities found:\n";
            foreach($test->getEntities() as $entity) {
                $entity->dumpContents();
                echo "\n";
            }
        }
        if (is_array($test->getLinks())) {
            echo "Links:\n";
            foreach ($test->getLinks() as $link) {
                $link->dumpContents();
            }
            echo "\n";
        }
        if (is_array($test->getNotices())) {
            echo "Notices:\n";
            foreach ($test->getNotices() as $notice) {
                $notice->dumpContents();
            }
            echo "\n";
        }
        if (is_array($test->getRemarks())) {
            echo "Remarks:\n";
            foreach ($test->getRemarks() as $remark) {
                $remark->dumpContents();
            }
            echo "\n";
        }
        if (is_array($test->getStatus())) {
            echo "Statuses:\n";
            foreach ($test->getStatus() as $status) {
                $status->dumpContents();
            }
            echo "\n";
        }


        if (is_array($test->getEvents())) {
            echo "Events:\n";
            foreach ($test->getEvents() as $event) {
                $event->dumpContents();
            }
        }
    } else {
        echo "$search was not found on any RDAP service\n";
    }



} catch (Metaregistrar\RDAP\rdapException $e) {
    echo "ERROR: ".$e->getMessage()."\n";
}
