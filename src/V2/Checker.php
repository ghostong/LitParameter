<?php


namespace Lit\Parameter\V2;


use Lit\Parameter\V2\Types\TypeArray;
use Lit\Parameter\V2\Types\TypeInteger;
use Lit\Parameter\V2\Types\TypeMixed;
use Lit\Parameter\V2\Types\TypeNumeric;
use Lit\Parameter\V2\Types\Types;
use Lit\Parameter\V2\Types\TypeString;

class Checker
{

    private static $typeCache = [];
    private $errCode = 0;
    private $errMsg = "";
    private $errName = null;
    private $errValue = null;

    public function __construct($params = []) {
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }
    }

    public function check($params = []) {
        $params = empty($params) ? $this->getAssigned() : $params;
        foreach ($params as $name => $value) {
            /**
             * @var TypeInteger $typeObject
             * @var TypeNumeric $typeObject
             * @var TypeString $typeObject
             * @var TypeArray $typeObject
             * @var TypeMixed $typeObject
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

    public function getAssigned() {
        return array_filter($this->getAttributes(), function ($v) {
            return !is_null($v);
        });
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

    private function typeCache($name) {
        if (!(self::$typeCache[$name] instanceof Types)) {
            self::$typeCache[$name] = new Types($name);
        }
        return self::$typeCache[$name];
    }

    private function getAttributes() {
        $tmp = [];
        foreach (self::$typeCache as $key => $value) {
            $tmp[$key] = $value->getOBject()->getValue();
        }
        return $tmp;
    }

    /**
     *
     * @date 2023/3/3
     * @param TypeInteger|TypeNumeric|TypeString|TypeArray|TypeMixed $typeObject
     * @param null $value
     * @return bool
     * @author litong
     */
    private function checkValue($typeObject, $value = null) {
        foreach ($typeObject->getWorkShop() as $callback) {
            if ("callback" == $typeObject->getName()) {
//                var_dump($callback);
//                var_dump(call_user_func($callback, $value ?: $typeObject->getValue()));
//                exit;
            }
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

    private function setError($errCode, $errMsg, $errName, $errValue) {
        $this->errCode = $errCode;
        $this->errMsg = $errMsg;
        $this->errName = $errName;
        $this->errValue = $errValue;
    }

    public function getCode() {
        return $this->errCode;
    }

    public function getMsg() {
        return $this->errMsg;
    }

    public function getName() {
        return $this->errName;
    }

    public function getValue() {
        return $this->errValue;
    }
}