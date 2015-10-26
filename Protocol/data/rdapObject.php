<?php

class rdapObject {

    public function __construct($key, $content) {
        if (is_array($content)) {
            foreach ($content as $ck=>$cv) {
                $this->{$ck}=$cv;
            }
        } else {
            $this->{$key}=$content;
        }
    }

    public static function createObject($key,$value) {
        echo "CREATEOBJECT $key\n";
        if (is_numeric($key)) {
            if (is_array($value)) {
                foreach ($value as $k=>$v) {
                    return self::KeyToObject($k,$v);
                }
            } else {
                return $value;
            }

        } else {
            return self::KeyToObject($key,$value);
        }
        return null;
    }

    public static function KeyToObjectName($name) {
        switch ($name) {
            case 'entities':
                return 'rdapEntity';
            case 'remarks':
                return 'rdapRemark';
            case 'links':
                return 'rdapLink';
            case 'notices':
                return 'rdapNotice';
            case 'events':
                return 'rdapEvent';
            case 'roles':
                return 'rdapRole';
            case 'description':
                return 'rdapDescription';
            case 'port43':
                return 'rdapPort43';
            default:
                return $name;
        }
    }

    public static function KeyToObject($name,$content) {
        $name = self::KeyToObjectName($name);
        if (class_exists($name)) {
            return new $name($name,$content);
        } else {
            return $content;
        }
    }
}














