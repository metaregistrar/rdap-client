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

        public function __construct($key, $content) {
            parent::__construct($key, $content);
            if (count($this->remarks)>0) {
                foreach ($this->remarks as $id=>$remark) {
                    $r = new rdapRemark('remark',$remark);
                    $this->remarks[$id]=$r;
                    //var_dump($r);
                }
            }
            if (count($this->vcardArray)>0) {
                foreach($this->vcardArray as $id=>$vcard){
                    $v = new vcardArray('vcard',$vcard);
                    $this->vcardArray[$id] = $v;
                }
            }
        }
    }
}
