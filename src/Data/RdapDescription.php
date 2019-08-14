<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapDescription extends RdapObject {
    /**
     * @var string|null
     */
    protected $description;

    public function __construct(string $key, $content) {
        parent::__construct($key, null);
        if (is_array($content)) {
            $this->description = $content[0];
        } else {
            $this->description = $content;
        }
    }

    public function dumpContents(): void {
        echo '  - Description: ' . $this->getDescription() . PHP_EOL;
    }

    public function getDescription(): string {
        return $this->description??'';
    }
}
