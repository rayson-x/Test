<?php

/**
 * 迭代器接口,实现以下接口可以进行迭代
 * 开始遍历的时候 Iterator::rewind() 将会被调起
 * 调用 Iterator::valid() 接口判断指针指向的值是否存在，如果返回false就会结束遍历
 * 如果 Iterator::valid() 接口返回true , 回调 Iterator::current() 和 Iterator::key()
 * 执行完 Iterator::current() 和 Iterator::key() 后,遍历将会执行 Iterator::next()函数,将指针向下偏移
 * 再次调用 Iterator::valid() 检查当前指针指向的值是否存在 重复上述操作
 *
 * 流程
 * Iterator::rewind() 遍历开始
 * Iterator::valid() 返回true
 *
 * Iterator::current() 返回当前value
 * Iterator::key() 返回当前key值
 * Iterator::next() 指针向下偏移
 *
 * Iterator::valid() 返回true 重复 上述操作
 * .
 * .
 * .
 * .
 * Iterator::valid() 返回 false 遍历结束
 *
 * Class myIterator
 */
class myIterator  implements \Iterator {

    private $array;

    //注入数组
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * 返回当前数组指针指向的value
     * @return mixed
     */
    public function current()
    {
        return current($this->array);
    }

    /**
     * 返回当前数组指针指向的key
     * @return mixed
     */
    public function key()
    {
        return key($this->array);
    }

    /**
     * 将指针向下偏移一位
     * @return mixed
     */
    public function next()
    {
        return next($this->array);
    }

    /**
     * 将指针指向数组第一位
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->array);
    }

    /**
     * 检查当前指针指向元素是否可用
     * @return bool
     */
    public function valid()
    {
        return key($this->array) !== null;
    }
}

$array = new myIterator(['ASDF'=>'12312','ADSF'=>12512,145123423,46546]);

foreach($array as $key=>$item){
    echo "{$key} => {$item}".PHP_EOL;
}