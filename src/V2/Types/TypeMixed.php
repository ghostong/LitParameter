<?php


namespace Lit\Parameter\V2\Types;


class TypeMixed extends Common
{
    protected $valueType = "mixed";

    public function __construct($name) {
        $this->name = $name;
        return $this;
    }

}