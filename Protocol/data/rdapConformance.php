<?php
namespace Metaregistrar\RDAP {

    class rdapConformance extends rdapObject
    {
        public $rdapConformance;

        /**
         * @return mixed
         */
        public function getRdapConformance()
        {
            return $this->rdapConformance;
        }

        /**
         * @param mixed $rdapConformance
         */
        public function setRdapConformance($rdapConformance)
        {
            $this->rdapConformance = $rdapConformance;
        }

    }
}