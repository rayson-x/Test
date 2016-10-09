<?php
/**
 * @route Class\Method
 * @param \Reflection\test $a
 * @param array $b
 * @param $c
 * @param null $d
 */
function test(Reflection\test $a,array $b,$c,$d = null){

};

class TestReflectionFunction extends PHPUnit_Framework_TestCase
{
    //////////////////////////////////////////////////
    // 下列函数使用方式与反射类类似,所以不做重复测试//
    // ReflectionFunctionAbstract::getEndLine       //
    // ReflectionFunctionAbstract::getExtension     //
    // ReflectionFunctionAbstract::getExtensionName //
    // ReflectionFunctionAbstract::getFileName      //
    // ReflectionFunctionAbstract::getName          //
    // ReflectionFunctionAbstract::getNamespaceName //
    // ReflectionFunctionAbstract::inNamespace      //
    // ReflectionFunctionAbstract::isInternal       //
    // ReflectionFunctionAbstract::isUserDefined    //
    //////////////////////////////////////////////////

    public function testFunctionTest(){
        return new ReflectionFunction('test');
    }

    /**
     * @depends testFunctionTest
     * @param $test ReflectionFunction
     */
    public function testGetClosure($test){
        $this->assertInstanceOf(Closure::class,$test->getClosure());
    }

    /**
     * @depends testFunctionTest
     * @param $test ReflectionFunction
     */
    public function testDocComment($test){
        $text = $test->getDocComment();
        $lines = explode("\n",$text);
        foreach($lines as $line){
            if($pos = strpos($line,'@route')){
                $text = trim(substr($line,$pos + 6));
                break;
            }
        }

        $this->assertEquals('Class\Method',$text);
    }
}