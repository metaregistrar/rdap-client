<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapRemark extends RdapObject
{
    protected $description = [];

    /**
     * @return void
     */
    public function dumpContents(): void
    {
        foreach ($this->getDescription() as $description) {
            $description->dumpContents();
        }
    }

    /**
     * @return RdapDescription[]
     */
    public function getDescription(): array
    {
        return $this->description;
    }
}
