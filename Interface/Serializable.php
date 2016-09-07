<?php

//如果使用了自定义序列化的接口,__wakeup,__sleep 函数将会无法调用
//不论何时，只要有实例需要被序列化，serialize 方法都将被调用。序列化时不会调用__destruct()函数
//当数据被反序列化时，类将被感知并且调用合适的 unserialize() 方法而不是调用 __construct()
class Example implements \Serializable
{
    protected $property1;
    protected $property2;
    protected $property3;

    public function __construct($property1, $property2, $property3)
    {
        $this->property1 = $property1;
        $this->property2 = $property2;
        $this->property3 = $property3;
    }

    //序列化时调用,可以自定义序列化
    public function serialize()
    {
        return serialize([
            $this->property1,
            $this->property2,
            $this->property3,
        ]);
    }

    //反序列化调用
    public function unserialize($data)
    {
        list(
            $this->property1,
            $this->property2,
            $this->property3
            ) = unserialize($data);
    }

    //序列化时不会调用
    public function __destruct(){
        echo 'end';
    }
}

$a = serialize(new Example(1,2,3));

echo $a.PHP_EOL;

$a = unserialize($a);

print_r($a);

