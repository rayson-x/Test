<?php
namespace Reflection;

final class test extends parentClass implements set,get{
    use traitClass;

    private $array = [
        'alex'  =>  18,
        'annie' =>  19,
        'ben'   =>  17,
    ];

    public function __construct($array = [])
    {
        $this->array = array_merge($this->array,$array);
    }

    private function __clone()
    {
    }

    public function get($name){
        return $this->array[$name];
    }

    public function set($name,$value){
        $this->array[$name] = $value;
    }

    final protected function secondMethod() { }
    private static function thirdMethod() { }
}