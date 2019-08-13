<?php

namespace Metaregistrar\RDAP\Data;

class RdapEntity extends RdapObject {
    /**
     * @var string|null
     */
    protected $type = null;
    /**
     * @var string
     */
    protected $lang;
    /*
     * @var string
     */
    protected $handle;
    /**
     * @var null|RdapStatus[]
     */
    protected $status = null;
    /**
     * @var null|string
     */
    protected $port43 = null;
    /**
     * @var RdapVcard[]|null
     */
    protected $vcards          = null;
    protected $vcardArray      = null;
    protected $objectClassName = null;
    protected $remarks         = array();
    /**
     * @var RdapRole[]|null
     */
    protected $roles = null;
    /**
     * @var null|RdapPublicId[]
     */
    protected $publicIds = null;
    protected $entities  = null;

    public function __construct($key, $content) {
        parent::__construct($key, $content);
        if ($this->vcardArray && count($this->vcardArray) > 0) {
            foreach ($this->vcardArray as $id => $vcard) {
                if (is_array($vcard)) {
                    foreach ($vcard as $v) {
                        if (is_array($v)) {
                            foreach ($v as $card) {
                                $this->vcards[] = new RdapVcard($card[0], $card[1], $card[2], $card[3]);
                            }
                        }
                    }
                } else {
                    $this->type = $vcard;
                }
            }
            unset($this->vcardArray);
        }
    }

    public function getLanguage() {
        return $this->lang;
    }

    /*
     * return string|null
     */

    /**
     * @return string
     */
    public function getRoles() {
        $return = '';
        if (is_array($this->roles)) {
            foreach ($this->roles as $role) {
                if (strlen($return) > 0) {
                    $return .= ', ';
                }
                $return .= $role->getRole();
            }
        }

        return $return;
    }

    /**
     *
     */
    public function dumpContents() {
        echo "- Handle: " . $this->getHandle() . "\n";
        if (isset($this->roles)) {
            foreach ($this->roles as $role) {
                echo "- Role: " . $role->getRole() . "\n";
            }
        }
        if (isset($this->port43)) {
            echo "- Port 43 whois: " . $this->getPort43() . "\n";
        }
        if (isset($this->publicIds)) {
            if (is_array($this->publicIds)) {
                foreach ($this->publicIds as $publicid) {
                    $publicid->dumpContents();
                }
            }
        }
        if ((is_array($this->vcards)) && (count($this->vcards) > 0)) {
            foreach ($this->vcards as $vcard) {
                $vcard->dumpContents();
            }
        }
    }

    /**
     * @return string|null
     */
    public function getHandle() {
        return $this->handle;
    }

    /**
     * @return null|string
     */
    public function getPort43() {
        return $this->port43;
    }
}
