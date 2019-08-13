<?php

namespace Metaregistrar\RDAP\Responses;

class RdapIpResponse extends RdapResponse {
    public $startAddress = null;
    public $endAddress   = null;
    public $ipVersion    = null;
    public $country      = null;

    /**
     * @return string
     */
    public function getStartAddress(): string {
        return $this->startAddress;
    }

    /**
     * @return string
     */
    public function getEndAddress(): string {
        return $this->endAddress;
    }

    /**
     * @return string
     */
    public function getIpVersion(): string {
        return $this->ipVersion;
    }

    /**
     * @return string
     */
    public function getCountry(): string {
        return $this->country;
    }
}
