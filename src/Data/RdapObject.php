<?php

namespace Metaregistrar\RDAP\Data;

/**
 * This is the parent class for all RdapXXXXX objects. This class will interpret the json that was received and convert it into objects that give back the data required
 * Class RdapObject
 *
 * @package Metaregistrar\RDAP
 */
class RdapObject {
    /**
     * @var string
     */
    protected $objectClassName;

    public function __construct($key, $content) {
        if ($content) {
            if (is_array($content)) {
                foreach ($content as $ck => $cv) {
                    if (is_array($cv)) {
                        if (is_numeric($ck)) {
                            if (is_array($cv)) {
                                foreach ($cv as $k => $v) {
                                    $this->{$k}[] = self::createObject($k, $v);
                                }
                            } else {
                                $this->{$ck} = $cv;
                            }
                        } else {
                            $this->{$ck}[] = self::createObject($ck, $cv);
                        }
                    } else {
                        $this->{$ck} = $cv;
                    }
                }
            } else {
                $var          = str_replace('Metaregistrar\RDAP\\', '', $key);
                $this->{$var} = $content;
            }
        }
    }

    public static function createObject($key, $value) {
        //echo "CREATEOBJECT $key\n";
        if (is_numeric($key)) {
            if (is_array($value)) {
                die("$key VALUE MAG GEEN ARRAY ZIJN\n");
            } else {
                return $value;
            }
        } else {
            return self::KeyToObject($key, $value);
        }
    }

    public static function KeyToObject($name, $content) {
        //echo "KEYTOOBJECT $name\n";
        $name = self::KeyToObjectName($name);
        if (class_exists($name)) {
            return new $name($name, $content);
        } else {
            return $content;
        }
    }

    public static function KeyToObjectName($name) {
        switch ($name) {
            case 'rdapConformance':
                return 'Metaregistrar\RDAP\RdapConformance';
            case 'entities':
                return 'Metaregistrar\RDAP\RdapEntity';
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
            case 'nameservers':
                return 'Metaregistrar\RDAP\rdapNameserver';
            case 'secureDNS':
                return 'Metaregistrar\RDAP\rdapSecureDNS';
            case 'status':
                return 'Metaregistrar\RDAP\rdapStatus';
            case 'publicIds':
                return 'Metaregistrar\RDAP\rdapPublicId';
            default:
                return $name;
        }
    }

    public function getObjectClassname() {
        return $this->objectClassName;
    }
}
