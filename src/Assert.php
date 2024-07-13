<?php

namespace Scurriio\Test;

use \Exception;
use PDOStatement;

class Assert{

    public static function pdoNoError(PDOStatement $pdoStatement){
        $error = $pdoStatement->errorInfo();
        if($error[1] != 0){
            throw new Exception("SQL Error Code(".$error[1].") ".$error[2]);
        }
    }

        
    public static function true($condition){
        if(!!!$condition){
            throw new AssertFailedException(true, $condition);
        }
    }

    public static function notNull($condition){
        if(is_null($condition)){
            throw new AssertFailedException(null, $condition, false);
        }
    }

    public static function null($condition){
        if(!is_null($condition)){
            throw new AssertFailedException(null, $condition);
        }
    }

    public static function false(bool $condition){
        if(!!$condition){
            throw new AssertFailedException(false, $condition);
        }
    }

    public static function equals($expected, $real){
        if($expected !== $real){
            throw new AssertFailedException($expected, $real);
        }
    }

    public static function notEqual($expected, $real){
        if($expected === $real){
            throw new AssertFailedException($expected, $real, false);
        }
    }

    public static function throws(callable $function){
        $thrown = false;
        try{
            $function();
        }catch(Exception $e){
            $thrown = true;
        }
        if(!$thrown){
            throw new Exception("Did not throw");
        }
    }
}




?>