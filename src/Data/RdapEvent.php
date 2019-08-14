<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapEvent extends RdapObject {
    protected $events;

    public function __construct(string $key, $content) {
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
    public function getEvents(): ?array {
        return $this->events;
    }

    public function dumpContents(): void {
        if (is_array($this->events)) {
            foreach ($this->events as $action => $date) {
                echo "  - $action: $date\n";
            }
        }
    }
}
