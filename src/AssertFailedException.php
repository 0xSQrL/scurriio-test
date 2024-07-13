<?php

namespace Scurriio\Test;

class AssertFailedException extends \Exception{

    public function __construct(public $expected, public $actual, bool $equals=true)
    {
        $actualStr = VarDump::get($actual);
        $expectedStr = VarDump::get($expected);

        if($equals){
            parent::__construct("Assert failed. Actual value does not equal expected.\nExpected: $expectedStr\nActual: $actualStr");
        }else{
            parent::__construct("Assert failed. Actual value equals unwanted value.\nUnwanted: $expectedStr\nActual: $actualStr");
        }
    }

}