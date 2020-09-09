<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapEntity extends RdapObject
{
    /**
     * @var string|null
     */
    protected $type;
    /**
     * @var string
     */
    protected $lang;
    /**
     * @var string
     */
    protected $handle;
    /**
     * @var null|RdapStatus[]
     */
    protected $status;
    /**
     * @var null|string
     */
    protected $port43;
    /**
     * @var RdapVcard[]|null
     */
    protected $vcards = [];

    protected $vcardArray;

    protected $objectClassName;

    protected $remarks;
    /**
     * @var RdapRole[]|null
     */
    protected $roles;
    /**
     * @var null|RdapPublicId[]
     */
    protected $publicIds;

    /**
     * @var array
     */
    protected $entities = [];

    protected $events;

    protected $links;

    protected $legalRepresentative;

    protected $nicbrDomainCount;

    protected $nicbrInetCount;

    protected $nicbrAutnumCount;

    public function __construct(string $key, $content)
    {
        parent::__construct($key, $content);
        if ($this->vcardArray && count($this->vcardArray) > 0) {
            foreach ($this->vcardArray as $id => $vcard) {
                if (is_array($vcard)) {
                    foreach ($vcard as $v) {
                        if (is_array($v)) {
                            foreach ($v as $card) {
                                $this->vcards[$id][] = new RdapVcard($card[0], $card[1], $card[2], $card[3]);
                            }
                        }
                    }
                } else {
                    $this->type = $vcard;
                }
            }
            unset($this->vcardArray);
        }
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->lang;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $return = [];
        if (is_array($this->roles)) {
            foreach ($this->roles as $role) {
                foreach ($role->getRoles() as $roles) {
                    $return[] = $roles;
                }
            }
        }
        return $return;
    }

    /**
     *
     */
    public function dumpContents(): void
    {
        echo '- Handle: ' . $this->getHandle() . PHP_EOL;
        if (isset($this->roles)) {
            foreach ($this->roles as $role) {
                echo '- Role: ' . $role->getRole() . PHP_EOL;
            }
        }
        //if (isset($this->port43)) {
        //    echo '- Port 43 whois: ' . $this->getPort43() . PHP_EOL;
        //}
        if (isset($this->publicIds) && is_array($this->publicIds)) {
            foreach ($this->publicIds as $publicid) {
                $publicid->dumpContents();
            }
        }
        if (is_array($this->vcards) && (count($this->vcards) > 0)) {
            foreach ($this->vcards as $vcard) {
                $vcard->dumpContents();
            }
        }
    }

    /**
     * @return string|null
     */
    public function getHandle(): ?string
    {
        if (is_array($this->handle)) {
            return $this->handle[0];
        }

        return $this->handle;

    }

    /**
     * @return null|string
     */
    public function getPort43(): ?string
    {
        return $this->port43;
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function getPublicIds()
    {
        return $this->publicIds;
    }

    public function getVcards()
    {
        return $this->vcards;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getLegalRepresentative()
    {
        return $this->legalRepresentative;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }


    public function getStatus()
    {
        return $this->status;
    }


    public function getNicbrDomainCount()
    {
        return $this->nicbrDomainCount;
    }

    public function getNicbrInetCount()
    {
        return $this->nicbrInetCount;
    }

    public function getNicbrAutnumCount()
    {
        return $this->nicbrAutnumCount;
    }
}
