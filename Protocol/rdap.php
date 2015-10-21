<?php

//require __DIR__ . '/../vendor/autoload.php';

class rdap {

    const ASN = 'asn';
    const IPV4 = 'ipv4';
    const IPV6 = 'ipv6';
    const NS = 'ns';
    const DOMAIN = 'domain';
    const SEARCH = 'search';
    const HOME = 'home';

    private $protocols = array(
        'ipv4'=> array(self::HOME=>'http://data.iana.org/rdap/ipv4.json',self::SEARCH=>'ip/'),
        'domain'=>array(self::HOME=>'http://data.iana.org/rdap/dns.json',self::SEARCH=>'domain/'),
        'ns'=>array(self::HOME=>'http://data.iana.org/rdap/dns.json',self::SEARCH=>'nameserver/'),
        'ipv6'=>array(self::HOME=>'http://data.iana.org/rdap/ipv6.json',self::SEARCH=>'ip/'),
        'asn'=>array(self::HOME=>'http://data.iana.org/rdap/asn.json',self::SEARCH=>'autnum/')
    );

    private $protocol = '';
    private $publicationdate = '';
    private $version = '';
    private $description = '';

    function __construct($protocol) {
        if (($protocol != self::ASN) && ($protocol != self::IPV4) && ($protocol != self::IPV6) && ($protocol!=self::DOMAIN)) {
            throw new rdapException('Protocol '.$protocol.' is not recognized by this rdap client implementation');
        } else {
            $this->protocol = $protocol;
        }

    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return string
     */
    public function getPublicationdate()
    {
        return $this->publicationdate;
    }

    /**
     * @param string $publicationdate
     */
    public function setPublicationdate($publicationdate)
    {
        $this->publicationdate = $publicationdate;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    function search($search) {
        if ((!isset($search)) || ($search == '')) {
            throw new rdapException('Search parameter may not be empty');
        }
        if ((in_array($this->getProtocol(),array(self::DOMAIN,self::NS,self::IPV4,self::IPV6))) && (!is_string($search))) {
            throw new rdapException('Search parameter must be a string for ipv4, ipv6, domain or nameserver searches');
        }

        if (($this->getProtocol()==self::ASN) && (!is_numeric($search))) {
            throw new rdapException('Search parameter must be a string or a number for asn searches');
        }
        $parameter = $this->prepareSearch($search);
        $services = $this->readRoot();
        foreach ($services as $service) {
            foreach ($service[0] as $number) {
                // ip address range match
                if (strpos($number,'-')>0) {
                    list($start,$end) = explode('-',$number);
                    if (($parameter >= $start) && ($parameter <= $end)) {
                        $rdap = file_get_contents($service[1][0].$this->protocols[$this->protocol][self::SEARCH].$search);
                        //echo $rdap;
                        return $this->createResponse($this->getProtocol(),$rdap);
                    }
                } else {
                    // exact match
                    if ($number == $parameter) {
                        //var_dump($service[0]);
                        //echo $service[1][0].$protocols[$protocol]['search'].$number;
                        $rdap = file_get_contents($service[1][0].$this->protocols[$this->protocol][self::SEARCH].$search);
                        //var_dump(json_decode($rdap));
                        return $this->createResponse($this->getProtocol(),$rdap);
                    }
                }
            }
        }
        return null;
    }

    private function readRoot() {
        $rdap = file_get_contents($this->protocols[$this->protocol][self::HOME]);
        $json = json_decode($rdap);
        $this->setDescription($json->description);
        $this->setPublicationdate($json->publication);
        $this->setVersion($json->version);
        return $json->services;
    }

    private function prepareSearch($string) {
        switch($this->getProtocol()) {
            case self::IPV4:
                list($start)=explode('.',$string);
                return $start.'.0.0.0/8';
            default:
                return $string;
        }
    }

    private function createResponse($protocol,$json) {
        switch ($protocol) {
            case rdap::IPV4:
                return new rdapIpResponse($json);
            case rdap::ASN:
                return new rdapAsnResponse($json);
            default:
                return new rdapResponse($json);
        }
    }
}

