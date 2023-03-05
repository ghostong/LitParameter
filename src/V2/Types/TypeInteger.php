<?php


namespace Lit\Parameter\V2\Types;


class TypeInteger extends CommonNumeric
{
    protected $valueType = "integer";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop($this->valueType, function ($value) {
            return is_int($value);
        });
        return $this;
    }

}