<?php
$basePath = __DIR__;
spl_autoload_register(function($class)use($basePath){
    include "{$basePath}/{$class}.php";
});


$demo = new ReflectionClass(Reflection\Test\demo::class);

$constructMethod = $demo->getConstructor();

//var_dump($constructMethod->getNumberOfParameters());
//var_dump($constructMethod->getNumberOfRequiredParameters());

$params = ($constructMethod->getParameters());
//var_dump(current($params)->getDefaultValue());

foreach ($params as $i => $param) {
//    printf(
//        "-- Parameter #%d: %s {\n".
//        "   是否可以通过值传递: %s\n".
//        "   依赖的类: %s\n".
//        "   参数是否为弱类型: %s\n".
//        "   是否通过引用: %s\n".
//        "   非必要参数: %s\n".
//        "}\n",
//        $i,
//        $param->getName(),
//        var_export($param->canBePassedByValue() ,1),
//        var_export($param->getClass(), 1),
//        var_export($param->allowsNull(), 1),
//        var_export($param->isPassedByReference(), 1),
//        $param->isOptional() ? 'yes' : 'no'
//    );
//    echo "\n";
}