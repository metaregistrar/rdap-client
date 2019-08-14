<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Responses;

final class RdapIpResponse extends RdapResponse {
    protected $startAddress;
    protected $endAddress;
    protected $ipVersion;
    protected $country;

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
