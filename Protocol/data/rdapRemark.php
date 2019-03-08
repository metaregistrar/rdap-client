<?php
namespace Metaregistrar\RDAP {

    class rdapRemark extends rdapObject {
        protected $description = [];

        public function dumpContents(){
            echo "- ".$this->getDescription()."\n";
        }

        public function getDescription() {
            return $this->description;
        }

    }
}