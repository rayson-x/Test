<?php
namespace Reflection\Test;

trait traitClass{

    public static $staticString = 'staticStringValue';
    public $string = 'stringValue';

    public function demo(){
        return 'demo';
    }
}