<?php

namespace Metaregistrar\RDAP\Data;

class RdapNotice extends RdapObject {
    public $title = null;
    public $type  = null;
    /**
     * @var RdapDescription[]|null
     */
    public $description = null;
    /**
     * @var RdapLink[]|null
     */
    public $links = null;

    public function __construct($key, $content) {
        $this->objectClassName = 'Notice';
        parent::__construct($key, $content);
    }

    public function dumpContents() {
        echo '- ' . $this->getTitle() . ": " . $this->getType() . "\n";
        if (is_array($this->description)) {
            foreach ($this->description as $descr) {
                $descr->dumpContents();
            }
        }
        if (is_array($this->links)) {
            foreach ($this->links as $link) {
                $link->dumpContents();
            }
        }
    }

    /**
     * @return null
     */
    public function getTitle() {
        return $this->title;
    }

    public function getType() {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getDescription() {
        $return = '';
        if (is_array($this->description)) {
            foreach ($this->description as $descr) {
                $return .= $descr . "\n";
            }
        } else {
            $return = $this->description;
        }

        return $return;
    }
}
