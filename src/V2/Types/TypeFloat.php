<?php


namespace Lit\Parameter\V2\Types;


class TypeFloat extends CommonNumeric
{
    protected $valueType = "float";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop($this->valueType, function ($value) {
            return is_float($value);
        });
        return $this;
    }

}