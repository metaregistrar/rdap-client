<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapVcard {

    /**
     * @var null|string
     */
    protected $name;
    /**
     * @var null|string
     */
    protected $fieldtype;
    protected $content;
    /**
     * @var null|int
     */
    protected $preference;
    /**
     * @var null|array
     */
    protected $contenttypes;

    public function __construct($name, $extras, $type, $contents) {
        $this->name = $name;
        if (is_array($extras)) {
            if (isset($extras['type'])) {
                if (is_array($extras['type'])) {
                    foreach ($extras['type'] as $contentType) {
                        $this->contenttypes[] = $contentType;
                    }
                } else {
                    $this->contenttypes[] = $extras['type'];
                }
            }
            if (isset($extras['pref'])) {
                $this->preference = $extras['pref'];
            }
        }
        $this->fieldtype = $type;
        $this->content   = $contents;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function getFieldtype(): ?string {
        return $this->fieldtype;
    }

    public function getContentTypes(): ?array {
        return $this->contenttypes;
    }

    public function dumpContents(): void {
        echo '  - ' . $this->getContent() . PHP_EOL;
    }

    public function getContent(): ?string {
        if ($this->name === 'version') {
            return 'Version: ' . $this->content;
        }
        if ($this->name === 'tel') {
            return 'Type: ' . $this->fieldtype . ', Preference: ' . $this->preference . ', Content: ' . $this->content . ' (' . $this->dumpContentTypes() . ')';
        }
        if ($this->name === 'email') {
            return 'Type: ' . $this->name . ', Content: ' . $this->content;
        }
        if ($this->name === 'adr') {
            $return = 'Type: ' . $this->name . ', Content: ';
            foreach ($this->content as $content) {
                if (is_array($content)) {
                    foreach ($content as $cont) {
                        $return .= $cont . ' ';
                    }
                } else if (trim($content) !== '') {
                    $return .= $content . ' ';
                }
            }

            return $return;
        }
        if ($this->name === 'fn') {
            return 'Type: ' . $this->name . ', Content: ' . $this->content;
        }
        if ($this->name === 'kind') {
            return 'Kind: ' . $this->content;
        }
        if ($this->name === 'ISO-3166-1-alpha-2') {
            return 'Language: ' . $this->content . ' (' . $this->name . ')';
        }

        return null;
    }

    public function dumpContentTypes(): string {
        $return = '';
        if (is_array($this->contenttypes)) {
            foreach ($this->contenttypes as $type) {
                if ($return !== '') {
                    $return .= ', ';
                }
                $return .= $type;
            }
        }

        return $return;
    }
}
