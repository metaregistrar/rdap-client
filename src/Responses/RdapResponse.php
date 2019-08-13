<?php
namespace Metaregistrar\RDAP\Responses;

class RdapResponse
{
    /**
     * @var string|null
     */
    protected $objectClassName=null;
    /**
     * @var string|null
     */
    protected $ldhName=null;
    /**
     * @var string
     */
    protected $handle;
    /*
    * @var  string
    */
    protected $name;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var null|rdapConformance[]
     */
    protected $rdapConformance=null;
    /**
     * @var null|rdapEntity[]
     */
    protected $entities = null;
    /**
     * @var null|rdapLink[]
     */
    protected $links = null;
    /**
     * @var null|rdapRemark[]
     */
    protected $remarks = null;
    /**
     * @var null|rdapNotice[]
     */
    protected $notices = null;
    /**
     * @var null|rdapEvent[]
     */
    protected $events = null;
    /**
     * @var null|rdapPort43[]
     */
    protected $port43;
    /**
     * @var null|rdapNameserver[]
     */
    protected $nameservers=null;
    /**
     * @var null|rdapStatus[]
     */
    protected $status=null;
    /**
     * @var null|rdapSecureDNS[]
     */
    protected $secureDNS = null;


    public function __construct($json)
    {
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
            throw new rdapException('Response object could not be validated as proper JSON');
        }
    }


    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return rdapConformance[]|null
     */
    public function getConformance()
    {
        return $this->rdapConformance;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * @return rdapEntity[]|null
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @return rdapLink[]|null
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @return rdapRemark[]|null
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * @return rdapNotice[]|null
     */
    public function getNotices()
    {
        return $this->notices;
    }

    /**
     * @return string
     */
    public function getPort43()
    {
        return $this->port43;
    }

    /**
     * @return rdapNameserver[]|null
     */
    public function getNameservers()
    {
        return $this->nameservers;
    }

    /**
     * @return rdapStatus[]|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return rdapEvent[]|null
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @return string|null
     */
    public function getClassname()
    {
        return $this->objectClassName;
    }

    /**
     * @return string|null
     */
    public function getLDHName()
    {
        return $this->ldhName;
    }

    /**
     * @return rdapSecureDNS[]|null
     */
    public function getSecureDNS()
    {
        return $this->secureDNS;
    }
}
