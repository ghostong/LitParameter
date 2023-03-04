<?php


namespace Lit\Parameter\V2\Types;


class TypeString extends TypeCommon
{
    protected $valueType = "string";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop(function ($value) {
            return is_string($value);
        });
        return $this;
    }

    public function minLength($length) {
        $this->setWorkShop(function ($value) use ($length) {
            return !(strlen($value) < $length);
        });
        return $this;
    }

    public function maxLength($length) {
        $this->setWorkShop(function ($value) use ($length) {
            return !(strlen($value) > $length);
        });
        return $this;
    }

}