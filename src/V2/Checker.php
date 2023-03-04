<?php


namespace Lit\Parameter\V2;


use Lit\Parameter\V2\Types\TypeInteger;
use Lit\Parameter\V2\Types\TypeMixed;
use Lit\Parameter\V2\Types\Types;
use Lit\Parameter\V2\Types\TypeString;

class Checker
{

    protected static $typeCache = [];
    protected $errCode = 0;
    protected $errMsg = "";
    protected $errName = null;
    protected $errValue = null;

    public function check($params = []) {
        $params = empty($params) ? $this->getAttributes() : $params;
        foreach ($params as $name => $value) {
            /**
             * @var TypeInteger $typeObject
             */
            $typeObject = $this->typeCache($name)->getObject();
            if ($typeObject) {
                if (!$this->checkValue($typeObject, $value)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function toArray() {
        return $this->getAttributes();
    }

    public function __get($name) {
        return $this->typeCache($name);
    }

    public function __set($name, $value) {
        $this->typeCache($name)->getObject()->setValue($value);
    }

    protected function typeCache($name) {
        if (!(self::$typeCache[$name] instanceof Types)) {
            self::$typeCache[$name] = new Types($name);
        }
        return self::$typeCache[$name];
    }

    protected function getAttributes() {
        $tmp = [];
        foreach (self::$typeCache as $key => $value) {
            $tmp[$key] = $value->getOBject()->getValue();
        }
        return $tmp;
    }

    /**
     *
     * @date 2023/3/3
     * @param TypeInteger|TypeString|TypeMixed $typeObject
     * @param null $value
     * @return bool
     * @author litong
     */
    protected function checkValue($typeObject, $value = null) {
        foreach ($typeObject->getWorkShop() as $callback) {
            if (!call_user_func($callback, $value ?: $typeObject->getValue())) {
                $this->setError(
                    $typeObject->getCode(),
                    $typeObject->getMsg(),
                    $typeObject->getName(),
                    $value ?: $typeObject->getValue()
                );
                return false;
            }
        }
        return true;
    }

    protected function setError($errCode, $errMsg, $errName, $errValue) {
        $this->errCode = $errCode;
        $this->errMsg = $errMsg;
        $this->errName = $errName;
        $this->errValue = $errValue;
    }

    public function getErrCode() {
        return $this->errCode;
    }

    public function getErrMsg() {
        return $this->errMsg;
    }

    public function getErrName() {
        return $this->errName;
    }

    public function getErrValue() {
        return $this->errValue;
    }
}