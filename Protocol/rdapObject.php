<?php

class rdapObject {

    public function __construct($key, $content) {
        //var_dump($key);
        //var_dump($content);
        if (is_array($content)) {
            // $content is an array
            foreach ($content AS $key => $value) {
                if (is_array($value)) {
                    $this->{$key} = $this->createObject($key,$value);
                } else {
                    if (is_numeric($key)) {
                        $this->data[$key] = $value;
                    } else {
                        $this->{$key} = $value;
                    }

                }
            }
        } else {
            // $content is not an array
            $this->{$key} = $this->createObject($key,$content);
        }
    }

    public static function createObject($key,$value) {
        switch ($key) {
            case 'entities':
                return new rdapEntities($key,$value);
            case 'remarks':
                return new rdapRemarks($key,$value);
            case 'links':
                return new rdapLinks($key,$value);
            case 'notices':
                return new rdapNotices($key,$value);
            case 'events':
                return new rdapEvents($key,$value);
            case 'port43':
                return new rdapPort43($key,$value);
            case 'rdapConformance':
                return new rdapConformance($key,$value);
            case 0:
                return $value;
            default:
                return new rdapObject($key,$value);
        }
    }
}














