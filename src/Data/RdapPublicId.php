<?php

namespace Metaregistrar\RDAP\Data;

class RdapPublicId extends RdapObject {
    protected $ids;

    public function __construct($key, $content) {
        $this->objectClassName = 'PublicId';
        parent::__construct($key, null);
        if (is_array($content)) {
            foreach ($content as $id) {
                $this->ids[$id['type']] = $id['identifier'];
            }
        }
    }

    public function dumpContents() {
        foreach ($this->ids as $type => $identifier) {
            echo "- $type: $identifier\n";
        }
    }
}
