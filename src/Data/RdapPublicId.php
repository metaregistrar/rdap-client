<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapPublicId extends RdapObject {
    protected $ids;

    public function __construct(string $key, $content) {
        $this->objectClassName = 'PublicId';
        parent::__construct($key, null);
        if (is_array($content)) {
            if (array_key_exists('type', $content)) {
                $this->ids[$content['type']] = $content['identifier'];
            } else {
                foreach ($content as $index=>$id) {
                    if (isset($id['type'])) {
                        $this->ids[$id['type']] = $id['identifier'];
                    } else {
                        $this->ids[$index] = $id['identifier'];
                    }
                }
            }
        }
    }

    /**
     * @return void
     */
    public function dumpContents(): void {
        foreach ($this->ids as $type => $identifier) {
            echo "- $type: $identifier\n";
        }
    }
}
