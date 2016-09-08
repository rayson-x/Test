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
     * ���Բ�������
     *
     * @depends testClassDemoConstructParameters
     * @param $params array
     */
    public function testParameterType($params){
        /* ��ȡ�������� */
        foreach($params as $param){
            if($param instanceof ReflectionParameter){
                /* �����������Ƿ�Ϊ�� */
                $this->assertFalse($param->allowsNull());

                //PHP7��ӷ������������
                if (version_compare(PHP_VERSION, '7.0.0', '>')) {
                    /* ����Ƿ���������� */
                    /* �������Ϊһ�����ʵ��,��ô�������;����Ǹ�������� */
                    $this->assertTrue($param->hasType());
                    $this->assertTrue(in_array((string)$param->getType(),['string','array','Reflection\Test\test']));
                }
            }
        }
        list($test,$array,$name) = $params;

        /* ���������Ƿ�Ϊ���� */
        $this->assertFalse($test->isArray());
        $this->assertTrue($array->isArray());
        $this->assertFalse($name->isArray());

        /* ���������Ƿ�Ϊ�ɻص��ṹ */
        $this->assertFalse($test->isCallable());
        $this->assertFalse($array->isCallable());
        $this->assertFalse($name->isCallable());
    }


    /**
     * ���Զ����ͬ������,��ͬ��������ķ���
     *
     * @depends testClassDemoConstructParameters
     * @depends testClassDemoTestMethodParameters
     * @depends testFunctionParameters
     * @param $constructParams array
     * @param $testMethodParams array
     * @param $functionParams array
     */
    public function testParametersInfo($constructParams,$testMethodParams,$functionParams){
        ///////////////////////����Demo�ุ���test����///////////////////////
        foreach($testMethodParams as $testMethodParam){
            /* ��ȡ������ */
            $this->assertInstanceOf(ReflectionClass::class,$testMethodParam->getDeclaringClass());
            $this->assertEquals('Reflection\Test\parentClass',$testMethodParam->getDeclaringClass()->getName());

            /* ��ȡ�������� */
            $this->assertInstanceOf(ReflectionMethod::class,$testMethodParam->getDeclaringFunction());
            $this->assertEquals('test',$testMethodParam->getDeclaringFunction()->getName());
        }

        ///////////////////////����testReflectionParameter����///////////////////////
        foreach($functionParams as $functionParam){
            /* ��ȡ������ */
            $this->assertNull($functionParam->getDeclaringClass());

            /* ��ȡ�������� */
            $this->assertInstanceOf(ReflectionFunction::class,$functionParam->getDeclaringFunction());
            $this->assertEquals('testReflectionParameter',$functionParam->getDeclaringFunction()->getName());
        }

        ///////////////////////����Demo��Ĺ��캯��///////////////////////
        foreach($constructParams as $constructParam){
            /* ��ȡ������ */
            $this->assertInstanceOf(ReflectionClass::class,$constructParam->getDeclaringClass());
            $this->assertEquals('Reflection\Test\demo',$constructParam->getDeclaringClass()->getName());

            /* ��ȡ�������� */
            $this->assertInstanceOf(ReflectionMethod::class,$constructParam->getDeclaringFunction());
            $this->assertEquals('__construct',$constructParam->getDeclaringFunction()->getName());

            /* ���Ϊ��ѡ����,��ȡ��Ĭ��ֵ */
            if($constructParam->isDefaultValueAvailable()){
                $this->assertTrue(in_array($constructParam->getDefaultValue(),[[],'test']));
            }

            /* ��ȡ�������������� */
            if (version_compare(PHP_VERSION, '7.0.0', '>')) {
                //PHP7֧�ֵķ���
                if($constructParam->hasType() && $constructParam->getClass()){
                    $this->assertInstanceOf(ReflectionClass::class,$constructParam->getClass());
                    $this->assertEquals(Reflection\Test\test::class,$constructParam->getClass()->getName());
                }
            }else{
                //���ϰ汾���ݵķ���
                if(!$constructParam->allowsNull() && $constructParam->getClass()){
                    $this->assertInstanceOf(ReflectionClass::class,$constructParam->getClass());
                    $this->assertEquals(Reflection\Test\test::class,$constructParam->getClass()->getName());
                }
            }
        }

        list($test,$array,$name) = $constructParams;
        /* �������Ƿ��ѡ */
        $this->assertFalse($test->isOptional());
        $this->assertTrue($array->isOptional());
        $this->assertTrue($name->isOptional());

        /* ����Ƿ�ͨ������ */
        $this->assertFalse($test->isPassedByReference());
        $this->assertTrue($array->isPassedByReference ());
        $this->assertFalse($name->isPassedByReference ());

        /* ������Ĭ��ֵ�Ƿ���� */
        $this->assertFalse($test->isDefaultValueAvailable());
        $this->assertTrue($array->isDefaultValueAvailable());
        $this->assertTrue($name->isDefaultValueAvailable());

        /* ��ȡ����λ�� */
        $this->assertEquals(0,$test->getPosition());
        $this->assertEquals(1,$array->getPosition());
        $this->assertEquals(2,$name->getPosition());

        /* ���Ĭ��ֵ�ǲ��ǳ��� */
        /* ���û�в���Ĭ��ֵ���ǳ���,���ô˷������׳�һ���쳣 */
        $this->assertTrue($name->isDefaultValueConstant());
    }
}