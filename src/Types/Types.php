<?php

namespace Lit\Parameter\Types;

class Types
{
    protected $checkData = [];
    private $errorCode = 0;
    private $errorMsg = "";

    public function notNull() {
        $this->setChecker(__FUNCTION__, true);
        return $this;
    }

    public function notEmpty() {
        $this->setChecker(__FUNCTION__, true);
        return $this;
    }

    /**
     * 设置识别错误码
     * @date 2021/8/23
     * @param int $code 错误码
     * @return Types
     * @author litong
     */
    public function setCode($code) {
        $this->errorCode = $code;
        return $this;
    }

    public function callback($callback) {
        $this->setChecker(__FUNCTION__, $callback);
        return $this;
    }

    /**
     * 设置识别错误信息
     * @date 2021/8/23
     * @param $msg
     * @return Types
     * @author litong
     */
    public function setMsg($msg) {
        $this->errorMsg = $msg;
        return $this;
    }

    public function getCode() {
        return $this->errorCode;
    }

    public function getMsg() {
        return $this->errorMsg;
    }

    protected function setChecker($check, $val) {
        $this->checkData[$check] = $val;
    }

    public function getChecker() {
        return $this->checkData;
    }

}