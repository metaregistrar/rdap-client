<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapStatus extends RdapObject
{

    /**
     * @var null|string
     */
    protected $rdapStatus;

    /**
     * @return void
     */
    public function dumpContents(): void
    {
        echo '- Status: ' . $this->getStatus() . PHP_EOL;
    }

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->rdapStatus ?? $this->{0} ?? null;
    }
}
