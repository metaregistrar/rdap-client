<?php
namespace Metaregistrar\RDAP {

    class rdapEntity extends rdapObject {

        public function __construct($key, $content) {
            echo "rdapObject\n";
            if (is_array($content)) {
                // $content is an array
                echo "CONTENT IS AN ARRAY WITH COUNT ".count($content)."\n";
                //var_dump($content);
                foreach ($content AS $key => $value) {
                    if (is_array($value)) {
                        // $value is an array, create new objects from the array contents
                        echo "VALUE IS AN ARRAY WITH COUNT ".count($value)."\n";
                        //echo "CREATE OBJECT $key\n";
                        foreach ($value as $k => $v) {
                            if (is_numeric($key)) {
                                $this->data[$k] = $this->createObject($k,$v);
                            } else {
                                $this->{$k} = $this->createObject($k,$v);
                                var_dump($this->{$key});
                            }
                        }

                    } else {
                        // $value is not an array
                        echo "CREATE ARRAY $key\n";
                        if (is_numeric($key)) {
                            $this->data[$key] = $value;
                        } else {
                            $this->{$key} = $value;
                        }
                    }
                } // end for ($contend AS $key => $value)
            } else {
                // $content is not an array
                echo "$content IS NOT AN ARRAY\n";
                $this->{$key} = $content;
                //var_dump($this);
                //die();
                //$this->{$key} = $this->createObject($key,$content);
            }
        }
    }
}
