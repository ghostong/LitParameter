<?php


namespace Lit\Parameter\V2\Types;


class TypeDateTime extends Common
{
    protected $valueType = "datetime";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop($this->valueType, function ($value) {
            return strtotime($value) > 0;
        });
        return $this;
    }

    public function formatIs($format = "Y-m-d H:i:s") {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($format) {
            return $value === date($format, strtotime($value));
        });
        return $this;
    }

}