<?php

namespace Metaregistrar\RDAP\Data;

class RdapRemark extends RdapObject {
    protected $description = [];

    public function dumpContents(): void {
        echo "- " . $this->getDescription() . "\n";
    }

    public function getDescription(): array {
        return $this->description;
    }
}
