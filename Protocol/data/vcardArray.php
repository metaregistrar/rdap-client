<?php
namespace Metaregistrar\RDAP {
    class vcardArray
    {
        public $type = null;
        public $status = array();
        public $fieldtype = null;
        public $content;

        public function __construct($content) {
            $this->type = $content[0];
            $this->status = $content[1];
            $this->fieldtype = $content[2];
            $this->content = $content[3];
        }
    }
}