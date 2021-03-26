<?php

namespace Lit\Parameter\Types;

class IsInt extends Types implements InterfaceTypes
{

    public function __construct() {
        $this->setChecker("isInt", true);
    }

    /**
     * 介于两者之间(包含)
     * @param int $a 开始值
     * @param int $b 结束值
     * @return IsInt
     */
    public function between($a, $b) {
        $this->setChecker(__FUNCTION__, [$a, $b]);
        return $this;
    }

    /**
     * 在范围内
     * @param array $array 数组白名单范围
     * @return IsInt
     */
    public function in($array) {
        $this->setChecker(__FUNCTION__, $array);
        return $this;
    }

    public function gt($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }

    public function lt($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }

    public function ge($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }

    public function le($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }
}