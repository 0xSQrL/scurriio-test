<?php
namespace Scurriio\Test;

use \Exception;
use \ReflectionClass;
use \ReflectionFunction;


error_reporting(E_ERROR);
ini_set("display_errors", 1);

abstract class Test{
    function beforeEach(){

    }

    function afterEach(){

    }

    function prepare(){

    }

    function cleanup(){

    }

        
    static function run_test_class(string $class){
        $reflectionClass = new ReflectionClass($class);

        $functions = $reflectionClass->getMethods();
        $instance = $reflectionClass->newInstance();

        $run = 0;
        $pass = 0;
        $fail = 0;

        echo "<h2>Running tests for $class</h2>";

        $instance->prepare();
        
        foreach($functions as $function){

            if(strstr($function->getName(), "test_")){
                $functionName = $function->getName();
                    if($reflectionClass->hasProperty($function."_params")){
                        $paramset = $reflectionClass->getProperty($functionName."_params")->getValue($instance);
                        echo "<p>Test '$functionName':</p><ul>";
                        foreach($paramset as $params){
                            $run ++;
                            $instance->beforeEach();
                            $vars = VarDump::get($params);
                            try{
                                $bm = new Benchmark();
                                $bm->start();
                                $function->invoke($instance, $params);
                                $bm->end();
                                $pass ++;
                                echo "<li style=\"color: darkgreen;\">Passed $vars in $bm->seconds</li>";
                            }catch(Exception $e){
                                $fail ++;
                                $trace = $e->getTrace();
                                $file = $trace[0]["file"];
                                $line = $trace[0]["line"];
                                echo "<li style=\"color: red;\">Failed $vars at $file($line) with exception: ".$e->getMessage()."</li>";
                            }
                            $instance->afterEach();
                        }
                        echo "</ul>";
                    }else{
                        $instance->beforeEach();
                        $run ++;
                        try{
                            $bm = new Benchmark();
                            $bm->start();
                            $function->invoke($instance);
                            $bm->end();
                            $pass ++;
                            echo "<p style=\"color: darkgreen;\">Passed Test '".$functionName."' in $bm->seconds</p>";
                        }catch(Exception $e){
                            $fail ++;
                            $file = $e->getFile();
                            $line = $e->getLine();
                            echo "<p style=\"color: red;\">Failed Test '".$functionName."' at $file($line) with exception: ".$e->getMessage().'<br>'.
                            $e->getTraceAsString()."</p>";
                        }
                        $instance->afterEach();
                    }
                    
            }
        
        }

        $instance->cleanup();
    }

    static function run_tests(){
        global $GLOBALS;
        $functions = get_defined_functions()["user"];
        $run = 0;
        $pass = 0;
        $fail = 0;

        foreach($functions as $function){
            if(strstr($function, "test_")){
                $funcReflect = new ReflectionFunction($function);
                    if(isset($GLOBALS[$function."_params"])){
                        $paramset = $GLOBALS[$function."_params"];
                        echo "<p>Test '$function':</p><ul>";
                        foreach($paramset as $params){
                            $run ++;
                            $vars = VarDump::get($params);
                            try{
                                $funcReflect->invoke($params);
                                $pass ++;
                                echo "<li style=\"color: darkgreen;\">Passed $vars</li>";
                            }catch(Exception $e){
                                $fail ++;
                                $trace = $e->getTrace();
                                $file = $trace[0]["file"];
                                $line = $trace[0]["line"];
                                echo "<li style=\"color: red;\">Failed $vars at $file($line) with exception: ".$e->getMessage()."</li>";
                            }
                        }
                        echo "</ul>";
                    }else{
                        $run ++;
                        try{
                            $funcReflect->invoke();
                            $pass ++;
                            echo "<p style=\"color: darkgreen;\">Passed Test '".$function."'</p>";
                        }catch(Exception $e){
                            $fail ++;
                            $file = $e->getFile();
                            $line = $e->getLine();
                            echo "<p style=\"color: red;\">Failed Test '".$function."' at $file($line) with exception: ".$e->getMessage().'<br>'.
                            $e->getTraceAsString()."</p>";
                        }
                    }
                    
            }
        }
    }
}



?>