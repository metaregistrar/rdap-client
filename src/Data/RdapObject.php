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
        $name = self::KeyToObjectName($name);
        if (class_exists($name)) {
            return new $name($name, $content);
        }

        return $content;
    }

    public static function KeyToObjectName($name): ?string {
        switch ($name) {
            case 'rdapConformance':
                return 'Metaregistrar\RDAP\Data\RdapConformance';
            case 'entities':
                return 'Metaregistrar\RDAP\Data\RdapEntity';
            case 'remarks':
                return 'Metaregistrar\RDAP\Data\RdapRemark';
            case 'links':
                return 'Metaregistrar\RDAP\Data\RdapLink';
            case 'notices':
                return 'Metaregistrar\RDAP\Data\RdapNotice';
            case 'events':
                return 'Metaregistrar\RDAP\Data\RdapEvent';
            case 'roles':
                return 'Metaregistrar\RDAP\Data\RdapRole';
            case 'description':
                return 'Metaregistrar\RDAP\Data\RdapDescription';
            case 'port43':
                return 'Metaregistrar\RDAP\Data\RdapPort43';
            case 'nameservers':
                return 'Metaregistrar\RDAP\Data\RdapNameserver';
            case 'secureDNS':
                return 'Metaregistrar\RDAP\Data\RdapSecureDNS';
            case 'status':
                return 'Metaregistrar\RDAP\Data\RdapStatus';
            case 'publicIds':
                return 'Metaregistrar\RDAP\Data\RdapPublicId';
            default:
                return $name;
        }
    }

    public function getObjectClassname(): string {
        return $this->objectClassName;
    }
}
