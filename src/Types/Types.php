<?php

namespace Lit\Parameter\Types;

class Types
{
    protected $checkData = [];
    private $errorCode = null;
    private $errorMsg = null;

    public function notNull() {
        $this->setChecker(__FUNCTION__, true);
        return $this;
    }

    public function notEmpty() {
        $this->setChecker(__FUNCTION__, true);
        return $this;
    }

    public function setCode($code) {
        $this->errorCode = $code;
        return $this;
    }

    public function callback($callback) {
        $this->setChecker(__FUNCTION__, $callback);
        return $this;
    }

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