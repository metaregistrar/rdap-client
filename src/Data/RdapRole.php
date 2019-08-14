<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapRole extends RdapObject {
    public function dumpContents(): void {
        echo '- Role: ' . $this->getRole() . PHP_EOL;
    }

    public function getRole() {
        return $this->{0};
    }
}