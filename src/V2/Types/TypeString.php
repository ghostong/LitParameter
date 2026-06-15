<?php


namespace Lit\Parameter\V2\Types;


class TypeString extends Common
{
    protected $valueType = "string";

    public function __construct($name)
    {
        $this->name = $name;
        $this->setWorkShop($this->valueType, function ($value) {
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
    public function minLength($length)
    {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($length) {
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
    public function maxLength($length)
    {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($length) {
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
    public function length($length)
    {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($length) {
            return strlen($value) === $length;
        });
        return $this;
    }


    /**
     * 邮箱格式
     * @date 2026/6/15
     * @return TypeString
     * @author litong
     */
    public function emailFormat()
    {
        $this->setWorkShop(__FUNCTION__, function ($value) {
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        });
        return $this;
    }

    /**
     * ipv4格式
     * @date 2026/6/15
     * @return TypeString
     * @author litong
     */
    public function ipV4Format()
    {
        $this->setWorkShop(__FUNCTION__, function ($value) {
            return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        });
        return $this;
    }

    /**
     * ipv6格式
     * @date 2026/6/15
     * @return TypeString
     * @author litong
     */
    public function ipV6Format()
    {
        $this->setWorkShop(__FUNCTION__, function ($value) {
            return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        });
        return $this;
    }


}