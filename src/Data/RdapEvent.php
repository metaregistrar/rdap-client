<?php
namespace Metaregistrar\RDAP\Data;

class RdapEvent extends RdapObject
{
    protected $events=null;

    public function __construct($key, $content)
    {
        parent::__construct($key, null);
        if (isset($content[0])) {
            foreach ($content as $c) {
                $this->events[$c['eventAction']] = $c['eventDate'];
            }
        } else {
            $this->events[$content['eventAction']] = $content['eventDate'];
        }
    }

    /**
     * @return array|null
     */
    public function getEvents()
    {
        return $this->events;
    }


    public function dumpContents()
    {
        if (is_array($this->events)) {
            foreach ($this->events as $action=>$date) {
                echo "  - $action: $date\n";
            }
        }
    }
}
