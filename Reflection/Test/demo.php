<?php
namespace Reflection\Test;

/**
 * Class demo
 * @demo this is class demo
 */
class demo extends parentClass{
    const NAME = 'test';
    const TYPE = 'object';

    public $string = 'string';

    private $int = 123;

    protected $double = 123.4;

    protected $bool = true;

    protected $null;

    public function __construct(test $test,array &$array = [],string $name = demo::NAME)
    {
        $this->obj = $test;
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        $this->obj->$name($arguments);
    }
}

