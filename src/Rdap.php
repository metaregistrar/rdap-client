<?php declare(strict_types=1);

namespace Metaregistrar\RDAP;

use Metaregistrar\RDAP\Responses\RdapAsnResponse;
use Metaregistrar\RDAP\Responses\RdapDomainResponse;
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
     * @var bool
     */
    private $cache_enabled;
    /**
     * @var string
     */
    private $cache_dir;

    /**
     * Rdap constructor.
     *
     * @param bool $use_cache
     * @param string $cache_dir
     */
    public function __construct(bool $use_cache = true, string $cache_dir = 'rdap-cache')
    {
        $this->cache_enabled = $use_cache;
        $this->cache_dir = $cache_dir;
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
        $this->setProtocol($this->guessProtocol($search));

        $parameters = $this->prepareSearch($search);
        $services = $this->readRoot();
        foreach ($parameters as $parameter) {
            foreach ($services as $service) {
                foreach ($service[0] as $number) {
                    // ip address range match
                    if (strpos($number, '-') > 0) {
                        [$start, $end] = explode('-', $number);
                        if (($parameter >= $start) && ($parameter <= $end)) {
                            return $this->request($service[1][0], $search);
                        }
                    } else {
                        $number = explode('/', $number);
                        if ($number[0] === $parameter) {
                            return $this->request($service[1][0], $search);
                        }
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
    public function guessProtocol(string $search): string
    {
        $search = trim($search);
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

    private function prepareSearch(string $string): array
    {
        switch ($this->getProtocol()) {
            case self::IPV4:
                [$start] = explode('.', $string);
                return [$start . '.0.0.0'];
            case self::IPV6:
                [$first, $second] = explode(':', $string);

                return [$first . '::', $first . ':' . $second . '::'];
            case self::DOMAIN:
                $extension = [];
                $count = substr_count($string, '.');
                for ($i = $count; $i >= 1; $i--) {
                    $extension[] = implode('.', array_slice(explode('.', $string), $i * -1));
                }
                return $extension;
            default:
                return [$string];
        }
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

    /**
     * @return array
     * @throws RdapException
     */
    private function readRoot(): array
    {
        [$rdap, $http_code] = $this->readCache();

        if ($rdap === false && $http_code !== 404 && $http_code !== 403) {
            throw new RdapException('Faled to connect with: ' . $this->getRootUrl());
        }
        if ($rdap) {
            $json = json_decode($rdap, false);
            $this->setDescription($json->description);
            $this->setPublicationdate($json->publication);
            $this->setVersion($json->version);

            return $json->services;
        }
        return [];
    }

    /**
     * @return array
     */
    private function readCache(): array
    {
        if ($this->isCacheEnabled()) {
            $cache_file = $this->getCacheFile();
            if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * 60 * 24))) {
                $rdap = file_get_contents($cache_file);
                $http_code = 200;
            } else {
                [$rdap, $http_code] = $this->doRequest($this->getRootUrl());
                file_put_contents($cache_file, $rdap, LOCK_EX);
            }
        } else {
            [$rdap, $http_code] = $this->doRequest($this->getRootUrl());
        }
        return array($rdap, $http_code);
    }

    /**
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->cache_enabled;
    }

    /**
     * @return string
     */
    public function getCacheFile(): string
    {

        return $this->getCacheDir() . '/' . array_slice(explode('/', $this->getRootUrl()), -1)[0];
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        if (!file_exists($this->cache_dir) && !mkdir($concurrentDirectory = $this->cache_dir, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        return $this->cache_dir;
    }

    /**
     * @return string
     */
    private function getRootUrl(): string
    {
        return self::$protocols[$this->getProtocol()][self::HOME];
    }

    /**
     * @param string $url
     * @return array
     */
    private function doRequest(string $url): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $rdap = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return array($rdap, $http_code);
    }

    /**
     * @param string $service
     * @param mixed $search
     * @return RdapResponse
     * @throws RdapException
     */
    private function request(string $service, string $search): ?RdapResponse
    {

        [$rdap, $http_code] = $this->doRequest($this->getSearchUrl($service, $search));

        if ($rdap === false && $http_code !== 404 && $http_code !== 403) {
            throw new RdapException('Faled to connect with: ' . $this->getSearchUrl($service, $search));
        }
        if ($rdap) {
            return $this->createResponse($rdap);
        }
        return null;
    }

    /**
     * @param string $service
     * @param mixed $search
     * @return string
     */
    private function getSearchUrl(string $service, string $search): string
    {
        if ($service[strlen($service) - 1] !== '/') {
            $service .= '/';
        }
        return $service . self::$protocols[$this->getProtocol()][self::SEARCH] . $search;
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
            case self::IPV6:
                return new RdapIpResponse($json);
            case self::ASN:
                return new RdapAsnResponse($json);
            case self::DOMAIN:
                return new RdapDomainResponse($json);
            default:
                return new RdapResponse($json);
        }
    }

    public function case(): void
    {
    }
}
