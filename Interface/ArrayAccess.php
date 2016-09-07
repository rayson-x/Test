<?php

//提供像访问数组一样访问对象的能力的接口
//就是可以吧对象当初数组用
class arr implements \ArrayAccess{

    public $config = [];

    public function __construct(array $config){
        $this->config = $config;
    }

    public function offsetExists($offset){
        return isset($this->config[$offset]);
    }


    public function offsetGet($offset){
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }


    public function offsetSet($offset, $value){
        $this->config[$offset] = $value;
    }


    public function offsetUnset($offset){
        if(isset($this->config[$offset])){
            unset($this->config[$offset]);
        }
    }
}

$arr = new arr([1,2,3,4,5]);

$arr[1] = 456;

var_dump($arr);
