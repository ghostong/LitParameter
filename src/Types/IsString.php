<?php

namespace Lit\Parameter\Types;

class IsString extends Types implements InterfaceTypes
{

    public function __construct() {
        $this->setChecker("isString", true);
    }

    /**
     * 字符串长度(strlen) 等于
     * @param int $length 字符串长度
     * @return IsString
     */
    public function length($length) {
        $this->setChecker(__FUNCTION__, $length);
        return $this;
    }

    /**
     * 字符串长度(strlen) 最长
     * @param int $length 最大长度
     * @return IsString
     */
    public function maxLength($length) {
        $this->setChecker(__FUNCTION__, $length);
        return $this;
    }

    /**
     * 字符串长度(strlen) 最短
     * @param int $length 最小长度
     * @return IsString
     */
    public function minLength($length) {
        $this->setChecker(__FUNCTION__, $length);
        return $this;
    }

    /**
     * 在范围内
     * @param array $array 数组白名单范围
     * @return IsString
     */
    public function in($array) {
        $this->setChecker(__FUNCTION__, $array);
        return $this;
    }

}