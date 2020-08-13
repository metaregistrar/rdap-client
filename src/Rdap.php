<?php declare(strict_types=1);

namespace Metaregistrar\RDAP;

use Metaregistrar\RDAP\Responses\RdapAsnResponse;
use Metaregistrar\RDAP\Responses\RdapIpResponse;
use Metaregistrar\RDAP\Responses\RdapResponse;

class Rdap
{
    public const ASN = 'asn';
    public const IPV4 = 'ipv4';
    public const IPV6 = 'ipv6';
    public const NS = 'ns';
    public const DOMAIN = 'domain';
    public const SEARCH = 'search';
    public const HOME = 'home';

    private static $protocols = [
        'ipv4' => [self::HOME => 'https://data.iana.org/rdap/ipv4.json', self::SEARCH => 'ip/'],
        'domain' => [self::HOME => 'https://data.iana.org/rdap/dns.json', self::SEARCH => 'domain/'],
        'ns' => [self::HOME => 'https://data.iana.org/rdap/dns.json', self::SEARCH => 'nameserver/'],
        'ipv6' => [self::HOME => 'https://data.iana.org/rdap/ipv6.json', self::SEARCH => 'ip/'],
        'asn' => [self::HOME => 'https://data.iana.org/rdap/asn.json', self::SEARCH => 'autnum/']
    ];

    private $protocol = '';
    private $publicationdate = '';
    private $version = '';
    private $description = '';

    /**
     * Rdap constructor.
     *
     * @param string $protocol
     *
     * @throws RdapException
     */
    public function __construct(string $protocol = '')
    {
        if ($protocol) {
            if (($protocol !== self::ASN) && ($protocol !== self::IPV4) && ($protocol !== self::IPV6) && ($protocol !== self::DOMAIN)) {
                throw new RdapException('Protocol ' . $protocol . ' is not recognized by this rdap client implementation');
            }
            $this->protocol = $protocol;
        }
    }

    /**
     * @return string
     */
    public function getPublicationdate(): string
    {
        return $this->publicationdate;
    }

    /**
     * @param string $publicationdate
     */
    public function setPublicationdate(string $publicationdate): void
    {
        $this->publicationdate = $publicationdate;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     *
     *
     * @param string $search
     *
     * @return RdapAsnResponse|RdapIpResponse|RdapResponse|null
     * @throws RdapException
     */
    public function search(string $search): ?RdapResponse
    {
        if (!isset($search) || ($search === '')) {
            throw new RdapException('Search parameter may not be empty');
        }
        $search = trim($search);
        $this->setProtocol($this->guessProtocol($search));
        if ((!is_string($search)) && in_array($this->getProtocol(), [self::DOMAIN, self::NS, self::IPV4, self::IPV6], true)) {
            throw new RdapException('Search parameter must be a string for ipv4, ipv6, domain or nameserver searches');
        }

        if ((!is_numeric($search)) && ($this->getProtocol() === self::ASN)) {
            throw new RdapException('Search parameter must be a number or a string with numeric info for asn searches');
        }

        $parameter = $this->prepareSearch($search);
        $services = $this->readRoot();

        foreach ($services as $service) {
            foreach ($service[0] as $number) {
                // ip address range match
                if (strpos($number, '-') > 0) {
                    [$start, $end] = explode('-', $number);
                    if (($parameter >= $start) && ($parameter <= $end)) {
                        return $this->request($service[1][0], $search);
                    }
                } else {
                    // exact match
                    if ($number === $parameter) {
                        return $this->request($service[1][0], $search);
                    }
                }
            }
        }
        return null;
    }

    /**
     * @param string|int $search
     * @return Rdap
     * @throws RdapException
     */
    public function guessProtocol($search): string
    {
        if (is_numeric($search)) {
            return self::ASN;
        }
        if (filter_var($search, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return self::IPV4;
        }

        if (filter_var($search, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return self::IPV6;

        }
        if (filter_var($search, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return self::DOMAIN;
        }
        throw new RdapException('No valid protocol for the given serach parameter.');
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     * @return Rdap
     */
    public function setProtocol(string $protocol): Rdap
    {
        $this->protocol = $protocol;
        return $this;
    }

    private function prepareSearch(string $string): string
    {
        switch ($this->getProtocol()) {
            case self::IPV4:
                [$start] = explode('.', $string);

                return $start . '.0.0.0/8';
            case self::DOMAIN:
                $extension = explode('.', $string, 2);

                return $extension[1];
            default:
                return $string;
        }
    }

    /**
     * @return array
     */
    private function readRoot(): array
    {
        $rdap = file_get_contents(self::$protocols[$this->protocol][self::HOME]);
        $json = json_decode($rdap, false);
        $this->setDescription($json->description);
        $this->setPublicationdate($json->publication);
        $this->setVersion($json->version);

        return $json->services;
    }

    /**
     * @param string $service
     * @param int $search
     * @return RdapResponse
     * @throws RdapException
     */
    private function request(string $service, $search): ?RdapResponse
    {
        if ($service[strlen($service) - 1] !== '/') {
            $service .= '/';
        }
        $rdap = file_get_contents($service . self::$protocols[$this->getProtocol()][self::SEARCH] . $search);
        if ($rdap === false) {
            throw new RdapException('Faled to connect with: ' . $service . self::$protocols[$this->protocol][self::SEARCH] . $search);
        }
        if ($rdap) {
            return $this->createResponse($rdap);
        }
        return null;
    }

    /**
     * @param string $json
     * @return RdapResponse
     * @throws RdapException
     */
    private function createResponse(string $json): RdapResponse
    {
        switch ($this->getProtocol()) {
            case self::IPV4:
                return new RdapIpResponse($json);
            case self::ASN:
                return new RdapAsnResponse($json);
            default:
                return new RdapResponse($json);
        }
    }

    public function case(): void
    {
    }
}
