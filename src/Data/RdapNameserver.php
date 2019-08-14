<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapNameserver extends RdapObject {
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
    protected $links;
    /** @var RdapEvent[] */
    protected $events     ;
    protected $ipAddresses;

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
        echo '- Object Classname: ' . $this->getObjectClassname() . PHP_EOL;
        echo '- LDH Name: ' . $this->ldhName . PHP_EOL;
        if (isset($this->status)) {
            //echo "- Status: ".$this->status->getStatus().PHP_EOL;
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
