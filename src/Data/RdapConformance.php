<?php

namespace Metaregistrar\RDAP\Data;

class RdapConformance extends RdapObject {
    protected $rdapConformance;

    public function dumpContents(): void {
        echo '- ' . $this->getRdapConformance() . "\n";
    }

    /**
     * @return mixed
     */
    public function getRdapConformance() {
        return $this->rdapConformance;
    }

    /**
     * @param mixed $rdapConformance
     */
    public function setRdapConformance($rdapConformance): void {
        $this->rdapConformance = $rdapConformance;
    }
}
