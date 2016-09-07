<?php

//创建外部迭代器的接口
//当需要进行迭代时,如果没用使用迭代器接口,可以通过创建外部迭代器进行迭代
class myData implements IteratorAggregate {

    private $array = [];
    const TYPE_INDEXED = 1;         //使用索引
    const TYPE_ASSOCIATIVE = 2;     //使用原key值

    public function __construct( array $data, $type = self::TYPE_INDEXED ) {
        reset($data);
        while( list($k, $v) = each($data) ) {
            $type == self::TYPE_INDEXED ?
                $this->array[] = $v :
                $this->array[$k] = $v;
        }
    }

    public function getIterator() {
        return new ArrayIterator($this->array);
    }

}

$obj = new myData(['one'=>'php','javascript','three'=>'c#','java',], 1 );

foreach($obj as $key => $value) {
    echo "{$key} => {$value}".PHP_EOL;
}