<?php
namespace Metaregistrar\RDAP {

    class rdapEntity extends rdapObject {
        public $lang;
        public $handle;
        public $status = array();
        public $port43 = null;
        public $vcardArray = array();
        public $objectClassName = null;
        public $remarks = array();
        public $roles = array();

        public function __construct($key, $content) {
            parent::__construct($key, $content);
            // All data has been stored in an internal array, now put it in the correct object structures
            if (count($this->remarks)>0) {
                foreach ($this->remarks as $id=>$remark) {
                    $r = new rdapRemark('remark',$remark);
                    $this->remarks[$id]=$r;
                }
            }
            if (count($this->vcardArray)>0) {
                foreach($this->vcardArray as $id=>$vcard){
                    if (is_array($vcard)) {
                        foreach ($vcard as $v) {
                            unset($this->vcardArray[$id]);
                            $this->vcardArray[] = new vcardArray($v);
                        }
                    } else {
                        unset($this->vcardArray[$id]);
                        //var_dump($vcard);
                    }

                }
            }
            if (count($this->remarks)>0) {
                foreach ($this->remarks as $id=>$remark) {
                    unset ($this->remarks[$id]);
                    $this->remarks[] = new rdapRemark('remark',$remark);
                }
            }
            if (count($this->roles)>0) {
                foreach ($this->roles as $id=>$role) {
                    unset ($this->roles[$id]);
                    $this->roles[] = new rdapRole('role',$role);
                }
            }

        }
    }
}
