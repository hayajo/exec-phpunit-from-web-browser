<?php

class String
{
    var $data;

    function String($data) {
        $this->data = $data;
    }

    function copy() {
        $ret = new String($this->data);
        return $ret;
    }

    function add($string) {
        $this->data = $this->data . $string->toString("%s");
    }

    function toString($format) {
        $ret = sprintf($format, $this->data);
        return $ret;
    }

}

?>
