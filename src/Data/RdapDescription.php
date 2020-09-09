<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

use Metaregistrar\RDAP\RdapException;

final class RdapDescription extends RdapObject
{
    /**
     * @var string|null
     */
    protected $description;

    /**
     * RdapDescription constructor.
     * @param string $key
     * @param mixed $content
     * @throws RdapException
     */
    public function __construct(string $key, $content)
    {
        parent::__construct($key, null);
        if (is_array($content) && isset($content[0])) {
            $this->description = $content[0];
        } else {
            $this->description = $content;
        }
    }

    /**
     * @return void
     */
    public function dumpContents(): void
    {
        echo '  - Description: ' . $this->getDescription() . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description ?? '';
    }
}
