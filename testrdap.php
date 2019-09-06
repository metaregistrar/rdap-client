<?php

use Metaregistrar\RDAP\RdapException;

include './vendor/autoload.php';

//$search = 59980;
//$protocol = Metaregistrar\RDAP\rdap::ASN;
//$search = 'RIPE-NCC-END-MNT';
//$search = '81.4.97.200';
//$search = '82.135.96.210';
//$search = '8.8.4.4';
//$search = 'bleibtreu.berlin';
$search = 'wasser.bayern';
//$protocol = Metaregistrar\RDAP\Rdap::IPV4;
$protocol = Metaregistrar\RDAP\Rdap::DOMAIN;

try {
    $rdap  = new Metaregistrar\RDAP\Rdap($protocol);
    $test  = $rdap->search($search);

    if ($test && $test->getErrorCode() === null) {
        //var_dump ($test);
        echo 'class name: ' . $test->getClassname() . PHP_EOL;
        echo 'handle: ' . $test->getHandle() . PHP_EOL;
        echo 'LDH (letters, digits, hyphens) name: ' . $test->getLDHName() . PHP_EOL;
        echo "name: ".$test->getName().PHP_EOL;
        echo "type: ".$test->getType().PHP_EOL;
        echo "port 43 service: ".$test->getPort43().PHP_EOL;
        if (is_array($test->getNameservers())) {
            echo "\nNameservers:\n";
            foreach ($test->getNameservers() as $nameserver) {
                $nameserver->dumpContents();
            }
            echo PHP_EOL;
        }
        if (is_array($test->getSecureDNS())) {
            echo "DNSSEC:\n";
            foreach ($test->getSecureDNS() as $dnssec) {
                $dnssec->dumpContents();
            }
            echo PHP_EOL;
        }
        echo "rdap conformance: \n";
        foreach ($test->getConformance() as $conformance) {
            $conformance->dumpContents();
        }
        echo PHP_EOL;
        if (is_array($test->getEntities())) {
            echo "Entities found:\n";
            foreach ($test->getEntities() as $entity) {
                $entity->dumpContents();
                echo PHP_EOL;
            }
        }
        if (is_array($test->getLinks())) {
            echo "Links:\n";
            foreach ($test->getLinks() as $link) {
                $link->dumpContents();
            }
            echo PHP_EOL;
        }
        if (is_array($test->getNotices())) {
            echo "Notices:\n";
            foreach ($test->getNotices() as $notice) {
                $notice->dumpContents();
            }
            echo PHP_EOL;
        }
        if (is_array($test->getRemarks())) {
            echo "Remarks:\n";
            foreach ($test->getRemarks() as $remark) {
                $remark->dumpContents();
            }
            echo PHP_EOL;
        }
        if (is_array($test->getStatus())) {
            echo "Statuses:\n";
            foreach ($test->getStatus() as $status) {
                $status->dumpContents();
            }
            echo PHP_EOL;
        }

        if (is_array($test->getEvents())) {
            echo "Events:\n";
            foreach ($test->getEvents() as $event) {
                $event->dumpContents();
            }
            echo PHP_EOL;
        }
    } else {
        $title = '';
        if ($test) {
            $title = $test->getTitle();
        }
        echo "$search was not found on any RDAP service. $title\n";
    }
} catch (RdapException $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
