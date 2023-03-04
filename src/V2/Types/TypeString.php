<?php


namespace Lit\Parameter\V2\Types;


class TypeString extends Common
{
    protected $valueType = "string";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop(function ($value) {
            return is_string($value);
        });
        return $this;
    }

    /**
     * 字符串最小长度
     * @date 2023/3/5
     * @param $length
     * @return TypeString
     * @author litong
     */
    public function minLength($length) {
        $this->setWorkShop(function ($value) use ($length) {
            return !(strlen($value) < $length);
        });
        return $this;
    }

    /**
     * 字符串最大长度
     * @date 2023/3/5
     * @param $length
     * @return TypeString
     * @author litong
     */
    public function maxLength($length) {
        $this->setWorkShop(function ($value) use ($length) {
            return !(strlen($value) > $length);
        });
        return $this;
    }

    /**
     * 字符串长度
     * @date 2023/3/5
     * @param $length
     * @return TypeString
     * @author litong
     */
    public function length($length) {
        $this->setWorkShop(function ($value) use ($length) {
            return strlen($value) === $length;
        });
        return $this;
    }

}