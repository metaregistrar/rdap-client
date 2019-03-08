<?php
namespace Metaregistrar\RDAP {
    class rdapVcard {

        /**
         * @var null|string
         */
        protected $name = null;
        /**
         * @var null|string
         */
        protected $fieldtype = null;
        protected $content;
        /**
         * @var null|int
         */
        protected $preference=null;
        /**
         * @var null|array
         */
        protected $contenttypes = null;

        public function __construct($name,$extras,$type,$contents) {
            $this->name = $name;
            if (is_array($extras)) {
                if (isset($extras['type'])) {
                    if (is_array($extras['type'])) {
                        foreach ($extras['type'] as $type)
                        $this->contenttypes[] = $type;
                    } else {
                        $this->contenttypes[] = $extras['type'];
                    }
                }
                if (isset($extras['pref'])) {
                    $this->preference = $extras['pref'];
                }
            }
            $this->fieldtype = $type;
            $this->content = $contents;
        }
        public function getName() {
            return $this->name;
        }

        public function getFieldtype() {
            return $this->fieldtype;
        }

        public function getContent() {
            if ($this->name == 'version') {
                return "Version: ".$this->content;
            }
            if ($this->name == 'tel') {
                return "Type: ".$this->fieldtype.", Preference: ".$this->preference.", Content: ".$this->content." (".$this->dumpContentTypes().")";
            }
            if ($this->name == 'email') {
                return "Type: ".$this->name.", Content: ".$this->content;
            }
            if ($this->name == 'adr') {
                $return = "Type: ".$this->name.", Content: ";
                foreach ($this->content as $content) {
                    if (is_array($content)) {
                        foreach ($content as $cont) {
                            $return .= $cont." ";
                        }
                    } else {
                        if (strlen(trim($content))>0) {
                            $return .= $content." ";
                        }
                    }

                }
                return $return;
            }
            if ($this->name == 'fn') {
                return "Type: ".$this->name.", Content: ".$this->content;
            }
            if ($this->name == 'kind') {
                return "Kind: ".$this->content;
            }
            if ($this->name == 'ISO-3166-1-alpha-2') {
                return "Language: ".$this->content." (".$this->name.")";
            }
            return null;
        }


        public function getContentTypes() {
            return $this->contenttypes;
        }

        public function dumpContentTypes() {
            $return = '';
            if (is_array($this->contenttypes)) {
                foreach ($this->contenttypes as $type) {
                    if (strlen($return)>0) {
                        $return .= ', ';
                    }
                    $return .= $type;
                }
            }
            return $return;
        }

        public function dumpContents() {
            echo '  - '.$this->getContent()."\n";
        }
    }
}