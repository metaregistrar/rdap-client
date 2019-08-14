<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapPublicId extends RdapObject {
    protected $ids;

    public function __construct(string $key, $content) {
        $this->objectClassName = 'PublicId';
        parent::__construct($key, null);
        if (is_array($content)) {
            foreach ($content as $id) {
                $this->ids[$id['type']] = $id['identifier'];
            }
        }
    }

    public function dumpContents(): void {
        foreach ($this->ids as $type => $identifier) {
            echo "- $type: $identifier\n";
        }
    }
}
