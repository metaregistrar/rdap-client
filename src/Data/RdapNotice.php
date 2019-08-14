<?php declare(strict_types=1);

namespace Metaregistrar\RDAP\Data;

final class RdapNotice extends RdapObject {
    protected $title;
    protected $type ;
    /**
     * @var RdapDescription[]|null
     */
    protected $description;
    /**
     * @var RdapLink[]|null
     */
    protected $links;

    public function __construct(string $key, $content) {
        $this->objectClassName = 'Notice';
        parent::__construct($key, $content);
    }

    public function dumpContents(): void {
        echo '- ' . $this->getTitle() . ": " . $this->getType() . PHP_EOL;
        if (is_array($this->description)) {
            foreach ($this->description as $descr) {
                $descr->dumpContents();
            }
        }
        if (is_array($this->links)) {
            foreach ($this->links as $link) {
                $link->dumpContents();
            }
        }
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getDescription(): array {
        $return = '';
        if (is_array($this->description)) {
            foreach ($this->description as $descr) {
                $return .= $descr . PHP_EOL;
            }
        } else {
            $return = $this->description;
        }

        return $return;
    }
}
