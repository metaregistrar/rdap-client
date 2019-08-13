<?php

namespace Metaregistrar\RDAP\Data;

class RdapRemark extends RdapObject {
    protected $description = [];

    public function dumpContents() {
        echo "- " . $this->getDescription() . "\n";
    }

    public function getDescription() {
        return $this->description;
    }
}
