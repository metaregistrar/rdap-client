<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapRemark extends RdapObject {
    protected $description = [];

    public function dumpContents(): void {
        echo '- ' . implode(', ', $this->getDescription()) . PHP_EOL;
    }

    public function getDescription(): array {
        return $this->description;
    }
}
