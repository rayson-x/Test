<?php
/**
 * 测试 反射类
 *
 * Class TestReflectionClass
 */
class TestReflectionClass extends PHPUnit_Framework_TestCase{

    public function testClassTest(){
        return new \ReflectionClass('Reflection\test');
    }

    public function testClassDemo(){
        return new \ReflectionClass('Reflection\demo');
    }

    public function testClassIterator(){
        return new \ReflectionClass('Iterator');
    }

    /**
     * 反射类名称
     *
     * @depends testClassDemo
     * @param $instances ReflectionClass
     */
    public function testClassName($instances)
    {
        $this->assertEquals('Reflection\demo', $instances->getName());
    }

    /**
     * 反射一个类中所有已定义的常量
     *
     * @depends testClassDemo
     * @param $instances ReflectionClass
     */
    public function testClassConstant($instances){
        $this->assertTrue($instances->hasConstant('NAME'));
        $this->assertFalse($instances->hasConstant('DEMO'));

        $this->assertEquals('test',$instances->getConstant('NAME'));

        $array = [
            'NAME'  => 'test',
            'TYPE'  => 'object',
        ];
        $this->assertEquals($array,$instances->getConstants());
    }

    /**
     * 反射类的默认属性
     *
     * @depends testClassTest
     * @depends testClassDemo
     * @param $test ReflectionClass
     * @param $demo ReflectionClass
     */
    public function testClassDefaultProperties($test,$demo){
        /* 测试属性默认值 */
        $testProperties = [
            'staticString'  =>  'staticStringValue',
            'string'        =>  'stringValue',
            'array' =>  [
                'alex'  =>  18,
                'annie' =>  19,
                'ben'   =>  17,
            ],
        ];
        $this->assertEquals($testProperties,$test->getDefaultProperties());

        $demoProperties = [
            'string'    =>  'string',
            'int'       =>  123,
            'double'    =>  123.4,
            'bool'      =>  true,
            'null'      =>  null,
        ];
        $this->assertEquals($demoProperties,$demo->getDefaultProperties());

        /* 测试反射属性对象 */
        $this->assertTrue($demo->hasProperty('string'));
        $this->assertFalse($demo->hasProperty('str'));

        $this->assertEquals('stringValue',$test->getProperty('string')->getValue(new Reflection\test()));
        $this->assertEquals('staticStringValue',$test->getProperty('staticString')->getValue());

        foreach($demo->getProperties(ReflectionProperty::IS_PROTECTED) as $property){
            $this->assertInstanceOf(ReflectionProperty::class,$property);
            $this->assertTrue($property->isProtected());
        }
    }

    /**
     * 反射一个类中的文档注释
     *
     * @depends testClassDemo
     * @param $demo ReflectionClass
     */
    public function testClassDocComment($demo){
        $text = $demo->getDocComment();
        $lines = explode("\n",$text);
        foreach($lines as $line){
            if($pos = strpos($line,'@demo')){
                $text = trim(substr($line,$pos + 5));
                break;
            }
        }

        $this->assertEquals('this is class demo',$text);
    }

    /**
     * 反射从用户定义的类起始行与结束行
     *
     * @depends testClassDemo
     * @param $demo ReflectionClass
     */
    public function testClassLine($demo){
        $this->assertEquals('8',$demo->getStartLine());
        $this->assertEquals('35',$demo->getEndLine());
    }

    /**
     * 反射已定义的类所在的扩展
     *
     * @depends testClassIterator
     * @param $iterator ReflectionClass
     */
    public function testClassExtension($iterator){
        $this->assertInstanceOf(ReflectionExtension::class,$iterator->getExtension());

        $this->assertEquals('Core',$iterator->getExtensionName());
    }

    /**
     * 反射定义类的文件名
     *
     * @depends testClassIterator
     * @depends testClassDemo
     * @param $iterator ReflectionClass
     * @param $demo ReflectionClass
     */
    public function testClassFileName($iterator,$demo){
        $this->assertFalse($iterator->getFileName());

        $path = dirname(__DIR__).DIRECTORY_SEPARATOR.'demo.php';
        $this->assertEquals($path,$demo->getFileName());
    }

    /**
     * 反射类实现的接口
     *
     * @depends testClassTest
     * @param $test ReflectionClass
     */
    public function testClassExtendsAndImplementsInterface($test){
        /* 继承的父类 */
        $this->assertEquals(Reflection\parentClass::class,$test->getParentClass()->getName());

        /* 类实现的接口 */
        $interfaces = [
            Reflection\set::class,
            Reflection\get::class
        ];

        $this->assertEquals($interfaces,$test->getInterfaceNames());

        reset($interfaces);
        foreach($test->getInterfaces() as $interface){
            $this->assertEquals(current($interfaces),$interface->name);
            next($interfaces);
        }

        $this->assertTrue($test->implementsInterface(Reflection\set::class));

        /* 类继承的trait */
        $this->assertEquals([Reflection\traitClass::class],$test->getTraitNames());

        foreach($test->getTraits() as $trait){
            $this->assertInstanceOf(ReflectionClass::class,$trait);
            $this->assertEquals(Reflection\traitClass::class,$trait->getName());
        }
    }

    /**
     * 反射类的方法
     *
     * @depends testClassTest
     * @param $test ReflectionClass
     */
    public function testClassMethod($test){
        $this->assertTrue($test->hasMethod('set'));
        $this->assertFalse($test->hasMethod('unset'));

        $this->assertEquals('get',$test->getMethod('get')->getName());
        $this->assertEquals('__construct',$test->getConstructor()->getName());

        foreach($test->getMethods() as $method){
            $this->assertInstanceOf(ReflectionMethod::class,$method);
        }

        /* 测试类方法修饰符 */
        foreach($test->getMethods(\ReflectionMethod::IS_STATIC) as $staticMethod){
            $this->assertTrue($staticMethod->isStatic());
        }

        foreach($test->getMethods(\ReflectionMethod::IS_FINAL) as $staticMethod){
            $this->assertTrue($staticMethod->isFinal());
        }

        foreach($test->getMethods(\ReflectionMethod::IS_PRIVATE) as $staticMethod){
            $this->assertTrue($staticMethod->isPrivate());
        }

        foreach($test->getMethods(\ReflectionMethod::IS_PROTECTED) as $staticMethod){
            $this->assertTrue($staticMethod->isProtected());
        }
    }

    /**
     * 反射类修饰符
     *
     * @depends testClassTest
     * @param $test ReflectionClass
     */
    public function testClassModifiers($test){
        /* 类是否为指定类的子类，或者实现了指定的接口 */
        $this->assertTrue($test->isSubclassOf(\Reflection\set::class));
        $this->assertTrue($test->isSubclassOf(\Reflection\get::class));
        $this->assertTrue($test->isSubclassOf(\Reflection\parentClass::class));

        /* 类可执行操作 */
        $this->assertFalse($test->isCloneable());
        $this->assertFalse($test->isIterateable());

        /* 类的定义者(PHP扩展或核心定义与开发者定义) */
        $this->assertTrue($test->isUserDefined());
        $this->assertFalse($test->isInternal());

        /* 类修饰符 */
        $this->assertTrue($this->testClassIterator()->isInterface());
        $this->assertTrue($test->isFinal());
        $this->assertFalse($test->isTrait());
        $this->assertFalse($test->isAbstract());
        //PHP7之前不存在匿名类
        if (version_compare(PHP_VERSION, '7.0.0', '>')) {
            $this->assertFalse($test->isAnonymous());
        }
    }

    /**
     * 反射类所在的命名空间
     *
     * @depends testClassDemo
     * @param $demo ReflectionClass
     */
    public function testClassNamespaceName($demo){
        $this->assertTrue($demo->inNamespace());
        $this->assertEquals('Reflection',$demo->getNamespaceName());
        $this->assertEquals('demo',$demo->getShortName());
    }

    /**
     * 实例化
     *
     * @depends testClassTest
     * @depends testClassDemo
     * @param $test ReflectionClass
     * @param $demo ReflectionClass
     */
    public function testClassInstance($test,$demo){
        $testInstance = new Reflection\test();
        $this->assertTrue($test->isInstance($testInstance));

        $this->assertTrue($demo->isInstantiable());
        /* 实例一个类 类似 call_user_func*/
        $this->assertInstanceOf(Reflection\demo::class,$demo->newInstance($testInstance));
        /* 通过一个参数数组实例一个类 类似 call_user_func_array */
        $this->assertInstanceOf(Reflection\demo::class,$demo->newInstanceArgs([$testInstance]));
        /* 绕过构造函数实例一个类 */
        $this->assertInstanceOf(Reflection\demo::class,$demo->newInstanceWithoutConstructor());
    }
}