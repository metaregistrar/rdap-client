<?php
namespace Metaregistrar\RDAP {
    class rdapSecureDNS extends rdapObject {
        /**
         * @var bool
         */
        protected $rdapSecureDNS;
        /**
         * @var null|string
         */
        protected $delegationSigned=null;
        /**
         * @var null|int
         */
        protected $maxSigLife = null;
        /**
         * @var null|array
         */
        protected $dsData = null;
        /**
         * @var null|string
         */
        protected $keyTag = null;
        /**
         * @var null|string
         */
        protected $digestType = null;
        /**
         * @var null|string
         */
        protected $digest = null;
        /**
         * @var null|string
         */
        protected $algorithm = null;

        /**
         * @return null|string
         */
        public function getDelegationSigned() {
            return $this->delegationSigned;
        }

        /**
         * @return int|null
         */
        public function getMaxSigLife() {
            return $this->maxSigLife;
        }

        /**
         * @return array|null
         */
        public function getDsData() {
            return $this->dsData;
        }

        /**
         * @return boolean
         */
        public function isRdapSecureDNS() {
            return $this->rdapSecureDNS;
        }

        /**
         * @return null|string
         */
        public function getKeyTag() {
            return $this->keyTag;
        }

        /**
         * @return null|string
         */
        public function getDigestType() {
            return $this->digestType;
        }

        /**
         * @return null|string
         */
        public function getDigest() {
            return $this->digest;
        }

        /**
         * @return null|string
         */
        public function getAlgorithm() {
            return $this->algorithm;
        }

        /**
         *
         */
        public function dumpDnskey() {
            echo "- Delegation signed: ".$this->getDelegationSigned()."\n";
            echo "- Max sig life: ".$this->getMaxSigLife()."\n";
            echo "- DNS Key: ".$this->getDsData()."\n";
        }

        /**
         *
         */
        public function dumpDigest() {
            echo "- Delegation signed: ".$this->getDelegationSigned()."\n";
            echo "- Max sig life: ".$this->getMaxSigLife()."\n";
            echo "- Keytag: ".$this->getKeyTag()."\n";
            echo "- Algorithm: ".$this->getAlgorithm()."\n";
            echo "- Digest Type :".$this->getDigestType()."\n";
            echo "- Digest: ".$this->getDigest()."\n";

        }

        /**
         *
         */
        public function dumpContents() {
            if ($this->delegationSigned) {
                echo "- Domain name is signed\n";
            } else {
                echo "- Domain name is not signed\n";
            }
            if ($this->getKeyTag()) {
                $this->dumpDigest();
            }
            if ($this->getDsData()) {
                $this->dumpDnskey();
            }
        }

    }
}