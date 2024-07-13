<?php
namespace Scurriio\Test;


class VarDump{

    public static function get($var){
        
        ob_flush();
        ob_start();
        var_dump($var);
        $var = ob_get_clean();
        ob_end_clean();
        return $var;

    }
}