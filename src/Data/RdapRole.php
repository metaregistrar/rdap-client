<?php

namespace Metaregistrar\RDAP\Data;

class RdapRole extends RdapObject {
    public function dumpContents() {
        echo "- Role: " . $this->getRole() . "\n";
    }

    public function getRole() {
        return $this->{0};
    }
}