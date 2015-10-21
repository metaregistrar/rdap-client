<?php

class rdapResponse {
    public $handle;
    public $rdapConformance;
    public $name;
    public $type;
    public $country;
    public $entities;
    public $links;
    public $remarks;
    public $notices;
    public $port43;

    public function __construct($json) {
        if ($data = json_decode($json, true)) {
            foreach ($data AS $key => $value) {
                if (is_array($value)) {
                    $this->{$key} = rdapObject::createObject($key,$value);
                } else {
                    $this->{$key} = $value;
                }
            }
        } else {
            throw new rdapException('Response object could not be validated as proper JSON');
        }
    }


    /**
     * @return string
     */
    public function getHandle() {
        return $this->handle;
    }

    /**
     * @return array
     */
    public function getConformance() {
        return $this->rdapConformance;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @return array
     */
    public function getEntities() {
        return $this->entities;
    }

    /**
     * @return array
     */
    public function getLinks() {
        return $this->links;
    }

    /**
     * @return array
     */
    public function getRemarks() {
        return $this->remarks;
    }

    /**
     * @return array
     */
    public function getNotices() {
        return $this->notices;
    }

    /**
     * @return string
     */
    public function getPort43() {
        return $this->port43;
    }
}