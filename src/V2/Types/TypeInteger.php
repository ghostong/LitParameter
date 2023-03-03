<?php


namespace Lit\Parameter\V2\Types;


class TypeInteger extends TypeCommon
{
    protected $valueType = "integer";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop(function ($value) {
            return is_int($value);
        });
        return $this;
    }

    public function gt($num) {
        $this->setWorkShop(function ($value) use ($num) {
            return $value > $num;
        });
        return $this;
    }

    public function lt($num) {
        $this->setWorkShop(function ($value) use ($num) {
            return $value < $num;
        });
        return $this;
    }

}