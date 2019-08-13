<?php

namespace Metaregistrar\RDAP\Data;

class RdapStatus extends RdapObject {

    /**
     * @var null|string
     */
    protected $rdapStatus = null;

    public function dumpContents(): void {
        echo "- Status: " . $this->getStatus() . "\n";
    }

    public function getStatus() {
        return $this->rdapStatus ?? $this->{0} ?? null;
    }
}
