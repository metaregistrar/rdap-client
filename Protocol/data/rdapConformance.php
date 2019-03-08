<?php
namespace Metaregistrar\RDAP {

    class rdapConformance extends rdapObject {
        protected $rdapConformance;

        /**
         * @return mixed
         */
        public function getRdapConformance() {
            return $this->rdapConformance;
        }

        /**
         * @param mixed $rdapConformance
         */
        public function setRdapConformance($rdapConformance) {
            $this->rdapConformance = $rdapConformance;
        }

        public function dumpContents() {
            echo '- '.$this->getRdapConformance()."\n";
        }

    }
}