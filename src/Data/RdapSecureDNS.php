<?php

namespace Metaregistrar\RDAP\Data;

class RdapSecureDNS extends RdapObject {
    /**
     * @var bool
     */
    protected $rdapSecureDNS;
    /**
     * @var null|string
     */
    protected $delegationSigned = null;
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
     * @return boolean
     */
    public function isRdapSecureDNS(): bool {
        return $this->rdapSecureDNS;
    }

    /**
     *
     */
    public function dumpContents(): void {
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

    /**
     * @return null|string
     */
    public function getKeyTag(): ?string {
        return $this->keyTag;
    }

    /**
     *
     */
    public function dumpDigest(): void {
        echo "- Delegation signed: " . $this->getDelegationSigned() . "\n";
        echo "- Max sig life: " . $this->getMaxSigLife() . "\n";
        echo "- Keytag: " . $this->getKeyTag() . "\n";
        echo "- Algorithm: " . $this->getAlgorithm() . "\n";
        echo "- Digest Type :" . $this->getDigestType() . "\n";
        echo "- Digest: " . $this->getDigest() . "\n";
    }

    /**
     * @return null|string
     */
    public function getDelegationSigned(): ?string {
        return $this->delegationSigned;
    }

    /**
     * @return int|null
     */
    public function getMaxSigLife(): ?int {
        return $this->maxSigLife;
    }

    /**
     * @return null|string
     */
    public function getAlgorithm(): ?string {
        return $this->algorithm;
    }

    /**
     * @return null|string
     */
    public function getDigestType(): ?string {
        return $this->digestType;
    }

    /**
     * @return null|string
     */
    public function getDigest(): ?string {
        return $this->digest;
    }

    /**
     * @return array|null
     */
    public function getDsData(): ?array {
        return $this->dsData;
    }

    /**
     *
     */
    public function dumpDnskey(): void {
        echo "- Delegation signed: " . $this->getDelegationSigned() . "\n";
        echo "- Max sig life: " . $this->getMaxSigLife() . "\n";
        echo "- DNS Key: " . $this->getDsData() . "\n";
    }
}
