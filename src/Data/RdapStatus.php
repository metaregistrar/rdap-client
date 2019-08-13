<?php
namespace Metaregistrar\RDAP\Data;

class RdapStatus extends RdapObject
{

        /**
         * @var null|string
         */
    protected $rdapStatus=null;

    public function dumpContents()
    {
        echo "- Status: ".$this->getStatus()."\n";
    }

    public function getStatus()
    {
        if (isset($this->rdapStatus)) {
            return $this->rdapStatus;
        }
        if (isset($this->{0})) {
            return $this->{0};
        }
        return null;
    }
}
