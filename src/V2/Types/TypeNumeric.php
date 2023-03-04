<?php


namespace Lit\Parameter\V2\Types;


class TypeNumeric extends CommonNumeric
{
    protected $valueType = "numeric";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop(function ($value) {
            return is_numeric($value);
        });
        return $this;
    }

}