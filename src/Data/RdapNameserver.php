<?php
namespace Metaregistrar\RDAP\Data;

class RdapNameserver extends RdapObject
{
    /**
     * @var string
     */
    protected $objectClassName;
    /**
     * @var string
     */
    protected $ldhName;
    /**
     * @var null|rdapStatus
     */
    protected $status;
    /**
     * @var rdapLink[]|null
     */
    protected $links=null;
    /** @var rdapEvent[]  */
    protected $events = null;
    protected $ipAddresses = null;

    /**
     * @return string
     */
    public function getStatus()
    {
        $return = '';
        if (is_array($this->status)) {
            foreach ($this->status as $status) {
                if (strlen($return)>0) {
                    $return .= ', ';
                }
                $return .= $status;
            }
        }
        return $return;
    }

    public function dumpContents()
    {
        echo "- Object Classname: ".$this->getObjectClassname()."\n";
        echo "- LDH Name: ".$this->ldhName."\n";
        if (isset($this->status)) {
            //echo "- Status: ".$this->status->getStatus()."\n";
        }
        if (isset($this->links)) {
            foreach ($this->links as $link) {
                $link->dumpContents();
            }
        }
        if (isset($this->events)) {
            foreach ($this->events as $event) {
                $event->dumpContents();
            }
        }
    }
}
