<?php

namespace Metaregistrar\RDAP\Data;

use Metaregistrar\RDAP\RdapException;

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

    /**
     * RdapObject constructor.
     *
     * @param string $key
     * @param mixed  $content
     *
     * @throws \Metaregistrar\RDAP\RdapException
     */
    public function __construct(string $key, $content) {
        if ($content) {
            if (is_array($content)) {
                foreach ($content as $contentKey => $contentValue) {
                    if (is_array($contentValue)) {
                        if (is_numeric($contentKey)) {
                            foreach ($contentValue as $k => $v) {
                                $this->{$k}[] = self::createObject($k, $v);
                            }
                        } else {
                            $this->{$contentKey}[] = self::createObject($contentKey, $contentValue);
                        }
                    } else {
                        $this->{$contentKey} = $contentValue;
                    }
                }
            } else {
                $var          = str_replace('Metaregistrar\RDAP\\', '', $key);
                $this->{$var} = $content;
            }
        }
    }

    /**
     *
     *
     * @param $key
     * @param $value
     *
     * @return mixed
     * @throws \Metaregistrar\RDAP\RdapException
     */
    private static function createObject($key, $value) {
        if (is_numeric($key)) {
            if (is_array($value)) {
                throw new RdapException("'$key' can not be an array.");
            }

            return $value;
        }

        return self::KeyToObject($key, $value);
    }

    /**
     *
     *
     * @param string $name
     * @param $content
     *
     * @return mixed
     */
    public static function KeyToObject(string $name, $content) {
        $name = self::KeyToObjectName($name);
        if (class_exists($name)) {
            return new $name($name, $content);
        }

        return $content;
    }

    /**
     *
     *
     * @param string $name
     *
     * @return string
     */
    private static function KeyToObjectName(string $name): string {
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

    /**
     * @return string
     */
    final public function getObjectClassname(): string {
        return $this->objectClassName;
    }
}
