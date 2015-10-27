<?php

require('Protocol/autoload.php');


$search = 59980;
$protocol = Metaregistrar\RDAP\rdap::ASN;
//$search = '81.4.97.200';
//$search = '196.216.2.6';
//$search = '8.8.4.4';
//$protocol = Metaregistrar\RDAP\rdap::IPV4;

try {
    $rdap = new Metaregistrar\RDAP\rdap($protocol);
    $test = $rdap->search($search);
    echo "handle: ".$test->getHandle()."\n";
    echo "name: ".$test->getName()."\n";
    //echo "country: ".$test->getCountry()."\n";
    echo "type: ".$test->getType()."\n";
    echo "port 43 service: ".$test->getPort43()."\n";
//    var_dump($test);
    //die();
    echo "rdap conformance: \n";
    foreach ($test->rdapConformance as $conformance) {
       echo '-  '.$conformance->rdapConformance."\n";
    }
    if (is_array($test->entities)) {
        echo "Entities:\n";
        foreach($test->entities as $entity) {
//            var_dump($entity);

            echo "- Handle: ".$entity->handle."\n";
            if (count($entity->roles)>0) {
                echo "- Roles:\n";
                foreach ($entity->roles as $role) {
                    echo '  - '.$role->role."\n";
                }
            }
            if ((is_array($entity->vcardArray)) && (count($entity->vcardArray)>0)) {
                foreach ($entity->vcardArray as $vcard) {
                    echo '  - '.$vcard->type.': '.$vcard->content.' ('.$vcard->fieldtype.")\n";
                }
            }
        }
    }
    if (is_array($test->links)) {
        echo "Links:\n";
        foreach ($test->links as $link) {
            echo '-  '.$link->rel.': ',$link->href."\n";
        }
    }
    if (is_array($test->notices)) {
        echo "Notices:\n";
        foreach ($test->notices as $notice) {
            echo '-  '.$notice->title."\n";
        }
    }
    if (is_array($test->remarks)) {
        echo "Remarks:\n";
        foreach ($test->remarks as $remark) {
            foreach ($remark->description as $d) {
                echo '-  '.$d."\n";
            }
        }
    }
    if (is_array($test->events)) {
        echo "Events:\n";
        foreach ($test->events as $event) {
            echo '-  '.$event->eventAction.': '.$event->eventDate."\n";
        }
    }


} catch (Metaregistrar\RDAP\rdapException $e) {
    echo "ERROR: ".$e->getMessage()."\n";
}
