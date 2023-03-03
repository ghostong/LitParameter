<?php


namespace Lit\Parameter\V2\Types;


class TypeCommon
{
    protected $name = null;
    protected $value = null;
    protected $valueType = "";
    protected $defaultValue = null;

    protected $errorCode = 0;
    protected $errorMsg = "";

    protected $workShop = [];

    public function defaultValue($value) {
        $this->defaultValue = $value;
        return $this;
    }

    public function setCode($code) {
        $this->errorCode = $code;
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

    public function getValue() {
        if (is_null($this->value) && !is_null($this->defaultValue)) {
            return $this->defaultValue;
        } else {
            return $this->value;
        }
    }

    public function setValue($value) {
        $this->value = $value;
        return $value;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->valueType;
    }

    protected function setWorkShop($callback) {
        $this->workShop[] = $callback;
    }

    public function getWorkShop() {
        return $this->workShop;
    }

}