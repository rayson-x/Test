<?php
$basePath = __DIR__;
spl_autoload_register(function($class)use($basePath){
        include "{$basePath}/{$class}.php";
});


//function demo(Reflection\Test\test $a,array $b,$c,$d = null){
//
//};
//
//
//
//$demo = new ReflectionFunction(function($a,$b){
//
//});
//
//var_dump($demo->getClosureScopeClass());
////if($demo->getClosure() instanceof Closure){
////    var_dump($demo->getClosure());
////}



