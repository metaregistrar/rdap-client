<?php
namespace Metaregistrar\RDAP {

    class rdapRole extends rdapObject {

        public function dumpContents() {
            echo "- Role: ".$this->getRole()."\n";
        }

        public function getRole() {
            return $this->{0};
        }
    }
}