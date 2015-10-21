<?php

class rdapObject {

    public function __construct($key, $content) {
        //var_dump($key);
        //var_dump($content);
        if (is_array($content)) {
            // $content is an array
            if ($key == 'rdapEntities') {
                //var_dump($content);
            }
            foreach ($content AS $key => $value) {
                if (is_array($value)) {
                    // $value is an array, create new objects from the array contents
                    if (is_numeric($key)) {
                        $this->data[$key] = $this->createObject($key,$value);
                    } else {
                        $this->{$key} = $this->createObject($key,$value);
                    }
                } else {
                    // $value is not an array
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
        if ($key === 0) {
            return $value;
        } else {
            $key = self::translateObjectName($key);
            return new $key($key,$value);
        }
    }

    public static function translateObjectName($name) {
        if (is_numeric($name)) {
            return 'rdapObject';
        }
        switch ($name) {
            case 'entities':
                return 'rdapEntities';
            case 'remarks':
                return 'rdapRemarks';
            case 'links':
                return 'rdapLinks';
            case 'notices':
                return 'rdapNotices';
            case 'events':
                return 'rdapEvents';
            case 'roles':
                return 'rdapRoles';
            case 'description':
                return 'rdapDescription';
            case 'port43':
                return 'rdapPort43';
            case '':
                return 'rdapObject';
            default:
                return $name;
        }
    }
}














