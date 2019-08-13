<?php

namespace Metaregistrar\RDAP\Data;

class RdapNameserver extends RdapObject {
    /**
     * @var string
     */
    protected $objectClassName;
    /**
     * @var string
     */
    protected $ldhName;
    /**
     * @var null|RdapStatus
     */
    protected $status;
    /**
     * @var RdapLink[]|null
     */
    protected $links = null;
    /** @var RdapEvent[] */
    protected $events      = null;
    protected $ipAddresses = null;

    /**
     * @return string
     */
    public function getStatus(): string {
        $return = '';
        if (is_array($this->status)) {
            foreach ($this->status as $status) {
                if ($return !== '') {
                    $return .= ', ';
                }
                $return .= $status;
            }
        }

        return $return;
    }

    public function dumpContents(): void {
        echo "- Object Classname: " . $this->getObjectClassname() . "\n";
        echo "- LDH Name: " . $this->ldhName . "\n";
        if (isset($this->status)) {
            echo "- Status: ".$this->status->getStatus()."\n";
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
