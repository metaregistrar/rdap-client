<?php

class rdapIpResponse extends rdapResponse {

    public $startAddress=null;
    public $endAddress=null;
    public $ipVersion=null;
    public $country=null;

    /**
     * @return string
     */
    public function getStartAddress() {
        return $this->startAddress;
    }

    /**
     * @return string
     */
    public function getEndAddress() {
        return $this->endAddress;
    }

    /**
     * @return string
     */
    public function getIpVersion() {
        return $this->ipVersion;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }
}