<?php

class rdapResponse {
    public $handle;
    public $name;
    public $type;
    public $rdapConformance;
    public $entities = null;
    public $links = null;
    public $remarks= null;
    public $notices = null;
    public $events = null;
    public $port43;

    public function __construct($json) {
        if ($data = json_decode($json, true)) {
            foreach ($data AS $key => $value) {
                if (is_array($value)) {
                    // $value is an array
                    if ($key != 'entities') {
                        foreach ($value as $k=>$v) {
                            $this->{$key}[] = rdapObject::KeyToObject($key,$v);
                        }
                    }

                } else {
                    // $value is not an array, just create a var with this value (startAddress endAddress ipVersion etc etc)
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
        return $this->conformance;
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