<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapConformance extends RdapObject {
    protected $rdapConformance;

    /**
     * @return void
     */
    public function dumpContents(): void {
        echo '- ' . $this->getRdapConformance() . PHP_EOL;
    }

    /**
     * @return string|null
     */
    public function getRdapConformance(): ?string {
        return $this->rdapConformance;
    }
}
