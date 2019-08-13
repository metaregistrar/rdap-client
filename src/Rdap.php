<?php declare(strict_types=1);

namespace Metaregistrar\RDAP;

use Metaregistrar\RDAP\Responses\RdapResponse;

final class Rdap {
    public const ASN = 'asn';
    public const IPV4       = 'ipv4';
    public const IPV6       = 'ipv6';
    public const NS         = 'ns';
    public const DOMAIN     = 'domain';
    public const SEARCH     = 'search';
    public const HOME       = 'home';

    private static $protocols = [
        'ipv4'   => [self::HOME => 'https://data.iana.org/rdap/ipv4.json', self::SEARCH => 'ip/'],
        'domain' => [self::HOME => 'https://data.iana.org/rdap/dns.json', self::SEARCH => 'domain/'],
        'ns'     => [self::HOME => 'https://data.iana.org/rdap/dns.json', self::SEARCH => 'nameserver/'],
        'ipv6'   => [self::HOME => 'https://data.iana.org/rdap/ipv6.json', self::SEARCH => 'ip/'],
        'asn'    => [self::HOME => 'https://data.iana.org/rdap/asn.json', self::SEARCH => 'autnum/']
    ];

    private $protocol;
    private $publicationdate = '';
    private $version         = '';
    private $description     = '';

    /**
     * Rdap constructor.
     *
     * @param string $protocol
     *
     * @throws \Metaregistrar\RDAP\RdapException
     */
    public function __construct(string $protocol) {
        if (($protocol !== self::ASN) && ($protocol !== self::IPV4) && ($protocol !== self::IPV6) && ($protocol !== self::DOMAIN)) {
            throw new RdapException('Protocol ' . $protocol . ' is not recognized by this rdap client implementation');
        }

        $this->protocol = $protocol;
    }

    /**
     * @return string
     */
    public function getPublicationdate(): string {
        return $this->publicationdate;
    }

    /**
     * @param string $publicationdate
     */
    public function setPublicationdate(string $publicationdate): void {
        $this->publicationdate = $publicationdate;
    }

    /**
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void {
        $this->description = $description;
    }

    /**
     *
     *
     * @param string $search
     *
     * @return \Metaregistrar\RDAP\Responses\RdapAsnResponse|\Metaregistrar\RDAP\Responses\RdapIpResponse|\Metaregistrar\RDAP\Responses\RdapResponse|null
     * @throws \Metaregistrar\RDAP\RdapException
     */
    public function search(string $search): ?RdapResponse {
        if (!isset($search) || ($search === '')) {
            throw new RdapException('Search parameter may not be empty');
        }

        $search = trim($search);
        if ((!is_string($search)) && in_array($this->getProtocol(), [self::DOMAIN, self::NS, self::IPV4, self::IPV6], true)) {
            throw new RdapException('Search parameter must be a string for ipv4, ipv6, domain or nameserver searches');
        }

        if (($this->getProtocol() === self::ASN) && (!is_numeric($search))) {
            throw new RdapException('Search parameter must be a number or a string with numeric info for asn searches');
        }

        $parameter = $this->prepareSearch($search);
        $services  = $this->readRoot();

        foreach ($services as $service) {
            foreach ($service[0] as $number) {
                // ip address range match
                if (strpos($number, '-') > 0) {
                    list($start, $end) = explode('-', $number);
                    if (($parameter >= $start) && ($parameter <= $end)) {
                        // check for slash as last character in the server name, if not, add it
                        if ($service[1][0]{strlen($service[1][0]) - 1} !== '/') {
                            $service[1][0] .= '/';
                        }
                        $rdap = file_get_contents($service[1][0] . self::$protocols[$this->protocol][self::SEARCH] . $search);

                        return $this->createResponse($this->getProtocol(), $rdap);
                    }
                } else {
                    // exact match
                    if ($number === $parameter) {
                        // check for slash as last character in the server name, if not, add it
                        if ($service[1][0]{strlen($service[1][0]) - 1} !== '/') {
                            $service[1][0] .= '/';
                        }
                        //echo $service[1][0].$this->protocols[$this->protocol][self::SEARCH].$number;
                        $rdap = file_get_contents($service[1][0] . self::$protocols[$this->protocol][self::SEARCH] . $search);

                        return $this->createResponse($this->getProtocol(), $rdap);
                    }
                }
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getProtocol(): string {
        return $this->protocol;
    }

    private function prepareSearch(string $string): string {
        switch ($this->getProtocol()) {
            case self::IPV4:
                list($start) = explode('.', $string);

                return $start . '.0.0.0/8';
            case self::DOMAIN:
                $extension = explode('.', $string, 2);

                return $extension[1];
            default:
                return $string;
        }
    }

    private function readRoot() {
        $rdap = file_get_contents(self::$protocols[$this->protocol][self::HOME]);
        $json = json_decode($rdap);
        $this->setDescription($json->description);
        $this->setPublicationdate($json->publication);
        $this->setVersion($json->version);

        return $json->services;
    }

    private function createResponse($protocol, $json): RdapResponse {
        switch ($protocol) {
            case Rdap::IPV4:
                return new RdapIpResponse($json);
            case Rdap::ASN:
                return new RdapAsnResponse($json);
            default:
                return new RdapResponse($json);
        }
    }

    public function case(): void {
    }
}
