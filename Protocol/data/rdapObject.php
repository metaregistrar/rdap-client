<?php
namespace Metaregistrar\RDAP {

    class rdapObject
    {

        public function __construct($key, $content)
        {
            if (is_array($content)) {
                foreach ($content as $ck => $cv) {
                    $this->{$ck} = $cv;
                }
            } else {
                $var = str_replace('Metaregistrar\RDAP\\','',$key);
                $this->{$var} = $content;
            }
        }

        public static function createObject($key, $value)
        {
            //echo "CREATEOBJECT $key\n";
            if (is_numeric($key)) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        return self::KeyToObject($k, $v);
                    }
                } else {
                    return $value;
                }

            } else {
                return self::KeyToObject($key, $value);
            }
            return null;
        }

        public static function KeyToObjectName($name)
        {
            switch ($name) {
                case 'rdapConformance':
                    return 'Metaregistrar\RDAP\rdapConformance';
                case 'entities':
                    return 'Metaregistrar\RDAP\rdapEntity';
                case 'remarks':
                    return 'Metaregistrar\RDAP\rdapRemark';
                case 'links':
                    return 'Metaregistrar\RDAP\rdapLink';
                case 'notices':
                    return 'Metaregistrar\RDAP\rdapNotice';
                case 'events':
                    return 'Metaregistrar\RDAP\rdapEvent';
                case 'roles':
                    return 'Metaregistrar\RDAP\rdapRole';
                case 'description':
                    return 'Metaregistrar\RDAP\rdapDescription';
                case 'port43':
                    return 'Metaregistrar\RDAP\rdapPort43';
                default:
                    return $name;
            }
        }

        public static function KeyToObject($name, $content)
        {
            $name = self::KeyToObjectName($name);
            if (class_exists($name)) {
                return new $name($name, $content);
            } else {
                return $content;
            }
        }
    }
}














