<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Responses;

use Metaregistrar\RDAP\Data\RdapConformance;
use Metaregistrar\RDAP\Data\RdapEntity;
use Metaregistrar\RDAP\Data\RdapEvent;
use Metaregistrar\RDAP\Data\RdapLink;
use Metaregistrar\RDAP\Data\RdapNameserver;
use Metaregistrar\RDAP\Data\RdapNotice;
use Metaregistrar\RDAP\Data\RdapObject;
use Metaregistrar\RDAP\Data\RdapPort43;
use Metaregistrar\RDAP\Data\RdapRemark;
use Metaregistrar\RDAP\Data\RdapSecureDNS;
use Metaregistrar\RDAP\Data\RdapStatus;
use Metaregistrar\RDAP\RdapException;

class RdapResponse {
    /**
     * @var string|null
     */
    private $objectClassName;
    /**
     * @var string|null
     */
    private $ldhName ;
    /**
     * @var string
     */
    private $handle = '';
    /*
    * @var  string
    */
    private $name = '';
    /**
     * @var string
     */
    private $type = '';
    /**
     * @var null|RdapConformance[]
     */
    private $rdapConformance;
    /**
     * @var null|RdapEntity[]
     */
    private $entities;
    /**
     * @var null|RdapLink[]
     */
    private $links;
    /**
     * @var null|RdapRemark[]
     */
    private $remarks;
    /**
     * @var null|RdapNotice[]
     */
    private $notices;
    /**
     * @var null|RdapEvent[]
     */
    private $events;
    /**
     * @var null|RdapPort43[]
     */
    private $port43;
    /**
     * @var null|RdapNameserver[]
     */
    private $nameservers;
    /**
     * @var null|RdapStatus[]
     */
    private $status;
    /**
     * @var null|RdapSecureDNS[]
     */
    private $secureDNS;

    /**
     * RdapResponse constructor.
     *
     * @param string $json
     *
     * @throws \Metaregistrar\RDAP\RdapException
     */
    public function __construct(string $json) {
        if ($data = json_decode($json, true)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    // $value is an array
                    foreach ($value as $k => $v) {
                        $this->{$key}[] = RdapObject::KeyToObject($key, $v);
                    }
                } else {
                    // $value is not an array, just create a var with this value (startAddress endAddress ipVersion etc etc)
                    $this->{$key} = $value;
                }
            }
        } else {
            throw new RdapException('Response object could not be validated as proper JSON');
        }
    }

    /**
     * @return string
     */
    final public function getHandle(): string {
        return $this->handle;
    }

    /**
     * @return RdapConformance[]|null
     */
    final public function getConformance(): ?array {
        return $this->rdapConformance;
    }

    /**
     * @return string
     */
    final public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    final public function getType(): string {
        return $this->type;
    }

    /**
     * @return RdapEntity[]|null
     */
    final public function getEntities(): ?array {
        return $this->entities;
    }

    /**
     * @return RdapLink[]|null
     */
    final public function getLinks(): ?array {
        return $this->links;
    }

    /**
     * @return RdapRemark[]|null
     */
    final public function getRemarks(): ?array {
        return $this->remarks;
    }

    /**
     * @return RdapNotice[]|null
     */
    final public function getNotices(): ?array {
        return $this->notices;
    }

    /**
     * @return RdapPort43[]|null
     */
    final public function getPort43(): ?array {
        return $this->port43;
    }

    /**
     * @return RdapNameserver[]|null
     */
    final public function getNameservers(): ?array {
        return $this->nameservers;
    }

    /**
     * @return RdapStatus[]|null
     */
    final public function getStatus(): ?array {
        return $this->status;
    }

    /**
     * @return RdapEvent[]|null
     */
    final public function getEvents(): ?array {
        return $this->events;
    }

    /**
     * @return string|null
     */
    final public function getClassname(): ?string {
        return $this->objectClassName;
    }

    /**
     * @return string|null
     */
    final public function getLDHName(): ?string {
        return $this->ldhName;
    }

    /**
     * @return RdapSecureDNS[]|null
     */
    final public function getSecureDNS(): ?array {
        return $this->secureDNS;
    }
}
