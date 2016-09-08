<?php
function testReflectionParameter($testString){

}

class TestReflectionParameter extends PHPUnit_Framework_TestCase{

    public function testClassDemoConstructParameters(){
        $demo = new ReflectionClass(Reflection\Test\demo::class);

        $constructMethod = $demo->getConstructor();

        return ($constructMethod->getParameters());
    }

    public function testClassDemoTestMethodParameters(){
        $demo = new ReflectionClass(Reflection\Test\demo::class);

        $constructMethod = $demo->getMethod('test');

        return ($constructMethod->getParameters());
    }

    public function testFunctionParameters(){
        $Function = new ReflectionFunction('testReflectionParameter');

        return ($Function->getParameters());
    }

    /**
     * 测试参数类型
     *
     * @depends testClassDemoConstructParameters
     * @param $params array
     */
    public function testParameterType($params){
        /* 获取参数类型 */
        foreach($params as $param){
            if($param instanceof ReflectionParameter){
                /* 检查参数类型是否为空 */
                $this->assertFalse($param->allowsNull());

                //PHP7添加反射参数类型类
                if (version_compare(PHP_VERSION, '7.0.0', '>')) {
                    /* 检查是否定义参数类型 */
                    /* 如果参数为一个类的实例,那么参数类型就是那个类的名称 */
                    $this->assertTrue($param->hasType());
                    $this->assertTrue(in_array((string)$param->getType(),['string','array','Reflection\Test\test']));
                }
            }
        }
        list($test,$array,$name) = $params;

        /* 传入类型是否为数组 */
        $this->assertFalse($test->isArray());
        $this->assertTrue($array->isArray());
        $this->assertFalse($name->isArray());

        /* 传入类型是否为可回调结构 */
        $this->assertFalse($test->isCallable());
        $this->assertFalse($array->isCallable());
        $this->assertFalse($name->isCallable());
    }


    /**
     * 测试多个不同作用域,不同参数需求的方法
     *
     * @depends testClassDemoConstructParameters
     * @depends testClassDemoTestMethodParameters
     * @depends testFunctionParameters
     * @param $constructParams array
     * @param $testMethodParams array
     * @param $functionParams array
     */
    public function testParametersInfo($constructParams,$testMethodParams,$functionParams){
        ///////////////////////测试Demo类父类的test方法///////////////////////
        foreach($testMethodParams as $testMethodParam){
            /* 获取声明类 */
            $this->assertInstanceOf(ReflectionClass::class,$testMethodParam->getDeclaringClass());
            $this->assertEquals('Reflection\Test\parentClass',$testMethodParam->getDeclaringClass()->getName());

            /* 获取声明函数 */
            $this->assertInstanceOf(ReflectionMethod::class,$testMethodParam->getDeclaringFunction());
            $this->assertEquals('test',$testMethodParam->getDeclaringFunction()->getName());
        }

        ///////////////////////测试testReflectionParameter方法///////////////////////
        foreach($functionParams as $functionParam){
            /* 获取声明类 */
            $this->assertNull($functionParam->getDeclaringClass());

            /* 获取声明函数 */
            $this->assertInstanceOf(ReflectionFunction::class,$functionParam->getDeclaringFunction());
            $this->assertEquals('testReflectionParameter',$functionParam->getDeclaringFunction()->getName());
        }

        ///////////////////////测试Demo类的构造函数///////////////////////
        foreach($constructParams as $constructParam){
            /* 获取声明类 */
            $this->assertInstanceOf(ReflectionClass::class,$constructParam->getDeclaringClass());
            $this->assertEquals('Reflection\Test\demo',$constructParam->getDeclaringClass()->getName());

            /* 获取声明函数 */
            $this->assertInstanceOf(ReflectionMethod::class,$constructParam->getDeclaringFunction());
            $this->assertEquals('__construct',$constructParam->getDeclaringFunction()->getName());

            /* 如果为可选参数,获取其默认值 */
            if($constructParam->isDefaultValueAvailable()){
                $this->assertTrue(in_array($constructParam->getDefaultValue(),[[],'test']));
            }

            /* 获取方法所依赖的类 */
            if (version_compare(PHP_VERSION, '7.0.0', '>')) {
                //PHP7支持的方法
                if($constructParam->hasType() && $constructParam->getClass()){
                    $this->assertInstanceOf(ReflectionClass::class,$constructParam->getClass());
                    $this->assertEquals(Reflection\Test\test::class,$constructParam->getClass()->getName());
                }
            }else{
                //向老版本兼容的方法
                if(!$constructParam->allowsNull() && $constructParam->getClass()){
                    $this->assertInstanceOf(ReflectionClass::class,$constructParam->getClass());
                    $this->assertEquals(Reflection\Test\test::class,$constructParam->getClass()->getName());
                }
            }
        }

        list($test,$array,$name) = $constructParams;
        /* 检查参数是否可选 */
        $this->assertFalse($test->isOptional());
        $this->assertTrue($array->isOptional());
        $this->assertTrue($name->isOptional());

        /* 检查是否通过引用 */
        $this->assertFalse($test->isPassedByReference());
        $this->assertTrue($array->isPassedByReference ());
        $this->assertFalse($name->isPassedByReference ());

        /* 检查参数默认值是否可用 */
        $this->assertFalse($test->isDefaultValueAvailable());
        $this->assertTrue($array->isDefaultValueAvailable());
        $this->assertTrue($name->isDefaultValueAvailable());

        /* 获取参数位置 */
        $this->assertEquals(0,$test->getPosition());
        $this->assertEquals(1,$array->getPosition());
        $this->assertEquals(2,$name->getPosition());

        /* 检查默认值是不是常量 */
        /* 如果没有参数默认值不是常量,调用此方法会抛出一个异常 */
        $this->assertTrue($name->isDefaultValueConstant());
    }
}