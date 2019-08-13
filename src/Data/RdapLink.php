<?php

namespace Metaregistrar\RDAP\Data;

class RdapLink extends RdapObject {
    /**
     * @var string
     */
    protected $rel;
    /**
     * @var string
     */
    protected $href;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $value;

    public function __construct($key, $content) {
        parent::__construct($key, null);
        if (is_array($content)) {
            if (isset($content[0])) {
                $this->rel   = $content[0]['rel'];
                $this->href  = $content[0]['href'];
                $this->type  = $content[0]['type'];
                $this->value = $content[0]['value'];
            } else {
                $this->rel   = $content['rel'];
                $this->href  = $content['href'];
                $this->type  = $content['type'];
                $this->value = $content['value'];
            }
        }
    }

    public function dumpContents(): void {
        echo "  - Link: " . $this->rel . ': ', $this->href . " (" . $this->title . ")\n";
    }

    /**
     * @return string
     */
    public function getRel(): string {
        return $this->rel;
    }

    /**
     * @return string
     */
    public function getHref(): string {
        return $this->href;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }
}
