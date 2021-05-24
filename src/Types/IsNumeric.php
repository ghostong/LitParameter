<?php

namespace Lit\Parameter\Types;

class IsNumeric extends Types implements InterfaceTypes
{

    public function __construct() {
        $this->setChecker("isNumeric", true);
    }

    /**
     * 介于两者之间(包含)
     * @param int $a 开始值
     * @param int $b 结束值
     * @return IsNumeric
     */
    public function between($a, $b) {
        $this->setChecker(__FUNCTION__, [$a, $b]);
        return $this;
    }

    /**
     * 在范围内
     * @param array $array 数组白名单范围
     * @return IsNumeric
     */
    public function in($array) {
        $this->setChecker(__FUNCTION__, $array);
        return $this;
    }

    /**
     * 小于
     * @param int $number 数字
     * @return IsNumeric
     */
    public function gt($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }

    /**
     * 大于
     * @param int $number 数字
     * @return IsNumeric
     */
    public function lt($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }

    /**
     * 小于等于
     * @param int $number 数字
     * @return IsNumeric
     */
    public function ge($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }

    /**
     * 大于等于
     * @param int $number 数字
     * @return IsNumeric
     */
    public function le($number) {
        $this->setChecker(__FUNCTION__, $number);
        return $this;
    }
}