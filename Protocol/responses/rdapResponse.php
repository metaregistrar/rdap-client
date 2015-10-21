<?php

class rdapResponse {
    public $handle;
    public $name;
    public $type;
    public $rdapConformance;
    public $rdapEntities;
    public $rdapLinks;
    public $rdapRemarks;
    public $rdapNotices;
    public $rdapPort43;

    public function __construct($json) {
        if ($data = json_decode($json, true)) {
            foreach ($data AS $key => $value) {
                $key = rdapObject::translateObjectName($key);
                //var_dump($key);
                if (is_array($value)) {
                    // oud: $this->{$key} = rdapObject::createObject($key,$value);
                    foreach ($value as $counter => $data) {
                        $this->{$key}[$counter] = rdapObject::createObject($counter,$data);
                    }

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
     * @return array
     */
    public function getEntities() {
        return $this->rdapEntities;
    }

    /**
     * @return array
     */
    public function getLinks() {
        return $this->rdapLinks;
    }

    /**
     * @return array
     */
    public function getRemarks() {
        return $this->rdapRemarks;
    }

    /**
     * @return array
     */
    public function getNotices() {
        return $this->rdapNotices;
    }

    /**
     * @return string
     */
    public function getPort43() {
        return $this->rdapPort43;
    }
}