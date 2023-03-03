<?php


namespace Lit\Parameter\V2\Types;


class TypeMixed extends TypeCommon
{
    protected $valueType = "mixed";

    public function __construct($name) {
        $this->name = $name;
        return $this;
    }

}