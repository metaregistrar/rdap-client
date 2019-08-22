<?php declare(strict_types=1);

namespace Metaregistrar\RDAP;

use Metaregistrar\RDAP\Responses\RdapAsnResponse;
use Metaregistrar\RDAP\Responses\RdapIpResponse;
use Metaregistrar\RDAP\Responses\RdapResponse;
use Psr\SimpleCache\CacheInterface;

final class Rdap {
    public const ASN    = 'asn';
    public const IPV4   = 'ipv4';
    public const IPV6   = 'ipv6';
    public const NS     = 'ns';
    public const DOMAIN = 'domain';
    public const SEARCH = 'search';
    public const HOME   = 'home';

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

    /** @var \Psr\SimpleCache\CacheInterface */
    private $cache;

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
     *
     * @return void
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
     *
     * @return void
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
     *
     * @return void
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
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function search(string $search): ?RdapResponse {
        if (!isset($search) || ($search === '')) {
            throw new RdapException('Search parameter may not be empty');
        }

        $search = trim($search);
        if ((!is_string($search)) && in_array($this->getProtocol(), [self::DOMAIN, self::NS, self::IPV4, self::IPV6], true)) {
            throw new RdapException('Search parameter must be a string for ipv4, ipv6, domain or nameserver searches');
        }

        if ((!is_numeric($search)) && ($this->getProtocol() === self::ASN)) {
            throw new RdapException('Search parameter must be a number or a string with numeric info for asn searches');
        }

        $parameter = $this->prepareSearch($search);
        $services  = $this->readRoot();

        foreach ($services as $service) {
            foreach ($service[0] as $number) {
                // ip address range match
                if (strpos($number, '-') > 0) {
                    [$start, $end] = explode('-', $number);
                    if (($parameter >= $start) && ($parameter <= $end)) {
                        return $this->getAndCreateResponse($search, $service);
                    }
                } else if ($number === $parameter) {
                    return $this->getAndCreateResponse($search, $service);
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

    /**
     * @param string $search
     *
     * @return string
     * @throws \Metaregistrar\RDAP\RdapException
     */
    private function prepareSearch(string $search): string {
        switch ($this->getProtocol()) {
            case self::IPV4:
                [$start] = explode('.', $search);

                return $start . '.0.0.0/8';
            case self::DOMAIN:
                $extension = explode('.', $search, 2);
                if (count($extension) < 2) {
                    throw new RdapException("Invalid domain name '$search'.");
                }

                return $extension[1];
            default:
                return $search;
        }
    }

    /**
     * @return array
     * @throws RdapException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function readRoot(): array {
        $rdap = $this->getResponse(self::$protocols[$this->protocol][self::HOME], 'root');
        $json = json_decode($rdap, false);
        $this->setDescription($json->description);
        $this->setPublicationdate($json->publication);
        $this->setVersion($json->version);

        return $json->services;
    }

    /**
     *
     *
     * @param string $protocol
     * @param string $json
     *
     * @return \Metaregistrar\RDAP\Responses\RdapResponse
     * @throws \Metaregistrar\RDAP\RdapException
     */
    private function createResponse(string $protocol, string $json): RdapResponse {
        switch ($protocol) {
            case self::IPV4:
                return new RdapIpResponse($json);
            case self::ASN:
                return new RdapAsnResponse($json);
            default:
                return new RdapResponse($json);
        }
    }

    /**
     * @return void
     */
    public function case(): void {
    }

    /**
     *
     *
     * @param string $url
     *
     * @param string $key
     * @param int    $ttl
     *
     * @return string
     * @throws \Metaregistrar\RDAP\RdapException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getResponse(string $url, string $key = '', int $ttl = 86400): string {
        if ($this->cache && !empty($key)) {
            if ($this->cache->has($key) === false) {
                $contents = $this->getFileContents($url);
                $this->cache->set($key, $contents, $ttl);
            } else {
                $contents = $this->cache->get($key);
            }
        } else {
            $contents = $this->getFileContents($url);
        }

        return $contents;
    }

    /**
     * ${CARET}
     *
     * @param string $url
     *
     * @return string
     * @throws \Metaregistrar\RDAP\RdapException
     */
    private function getFileContents(string $url): string {
        $options = array(
            'http' => array(
                'ignore_errors'    => true,
                'protocol_version' => '1.1',
                'method'           => 'GET'
            )
        );
        $context = stream_context_create($options);

        $contents = file_get_contents($url, false, $context);

        if ($contents === false) {
            throw new RdapException("Problem getting response from '$url'.");
        }

        return $contents;
    }

    /**
     * @param string $search
     * @param array  $service
     *
     * @return \Metaregistrar\RDAP\Responses\RdapResponse
     * @throws \Metaregistrar\RDAP\RdapException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getAndCreateResponse(string $search, array $service): RdapResponse {
        // check for slash as last character in the server name, if not, add it
        if ($service[1][0]{strlen($service[1][0]) - 1} !== '/') {
            $service[1][0] .= '/';
        }
        $rdap = $this->getResponse($service[1][0] . self::$protocols[$this->protocol][self::SEARCH] . $search, $search);

        return $this->createResponse($this->getProtocol(), $rdap);
    }

    /**
     * @param CacheInterface $cache
     *
     * @return void
     */
    public function setCache(CacheInterface $cache): void {
        $this->cache = $cache;
    }
}
