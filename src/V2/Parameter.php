<?php


namespace Lit\Parameter\V2;


use Lit\Parameter\V2\Types\TypeArray;
use Lit\Parameter\V2\Types\TypeFloat;
use Lit\Parameter\V2\Types\TypeInteger;
use Lit\Parameter\V2\Types\TypeMixed;
use Lit\Parameter\V2\Types\TypeNumeric;
use Lit\Parameter\V2\Types\Types;
use Lit\Parameter\V2\Types\TypeString;

class Parameter
{

    private $typeCache = [];
    private $assignedCache = [];
    private $errCode = 0;
    private $errMsg = '';
    private $errName = '';
    private $errValue = null;
    private $errCondition = '';
    private $mustFields = [];

    //是否使用默认报错
    protected $defaultError = false;
    protected $defaultErrorCode = 77777;
    protected $defaultErrorMsg = '参数错误(%s)';

    public function __construct($params = []) {
        foreach ($params as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * 参数校验
     * @date 2023/3/5
     * @param array $params 要验证的参数
     * @return bool
     * @author litong
     */
    public function check($params = []) {
        $params = empty($params) ? $this->getAssigned() : $params;
        if (!$this->checkMustFields($params)) {
            return false;
        }
        foreach ($params as $name => $value) {
            /**
             * @var TypeInteger $typeObject
             * @var TypeNumeric $typeObject
             * @var TypeString $typeObject
             * @var TypeArray $typeObject
             * @var TypeMixed $typeObject
             * @var TypeFloat $typeObject
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

    /**
     * 设置必须存在的字段
     * @date 2023/3/5
     * @param array $must
     * @return Parameter
     * @author litong
     */
    public function must($must = []) {
        $this->mustFields = $must;
        return $this;
    }

    /**
     * 获取已赋值的属性
     * @date 2023/3/5
     * @return array
     * @author litong
     */
    public function getAssigned() {
        $tmp = [];
        foreach ($this->typeCache as $key => $value) {
            if (in_array($key, $this->assignedCache) && $value->getOBject()->getType() !== "mixed") {
                $tmp[$key] = $value->getOBject()->getValue();
            }
        }
        return $tmp;
    }

    /**
     * 获取所有值的属性
     * @date 2023/3/5
     * @return array
     * @author litong
     */
    public function toArray() {
        $tmp = [];
        foreach ($this->typeCache as $key => $value) {
            if ($value->getOBject()->getType() !== "mixed") {
                $tmp[$key] = $value->getOBject()->getValue();
            }
        }
        return $tmp;
    }

    public function __get($name) {
        return $this->typeCache($name);
    }

    public function __set($name, $value) {
        $this->assignedCache[] = $name;
        $this->typeCache($name)->getObject()->setValue($value);
    }

    private function typeCache($name) {
        if (!isset($this->typeCache[$name]) || !($this->typeCache[$name] instanceof Types)) {
            $this->typeCache[$name] = new Types($name);
        }
        return $this->typeCache[$name];
    }

    /**
     * @date 2023/3/3
     * @param TypeInteger|TypeNumeric|TypeString|TypeArray|TypeMixed $typeObject
     * @param null $value
     * @return bool
     */
    private function checkValue($typeObject, $value = null) {
        foreach ($typeObject->getWorkShop() as $worker) {
            $callable = $worker["callable"];
            $condition = $worker["condition"];
            if (!call_user_func($callable, $value ?: $typeObject->getValue())) {
                $this->setError(
                    $typeObject->getCode(),
                    $typeObject->getMsg(),
                    $typeObject->getName(),
                    $condition,
                    $value ?: $typeObject->getValue()
                );
                return false;
            }
        }
        return true;
    }

    private function checkMustFields($params) {
        $diffKey = array_diff_key(array_flip($this->mustFields), $params);
        if (empty($diffKey)) {
            return true;
        } else {
            $key = current(array_keys($diffKey));
            $typeObject = $this->typeCache($key)->getObject();
            $this->setError(
                10000,
                "参数为必填参数!",
                $typeObject->getName(),
                "must",
                isset($params[$key]) ?: null
            );
            return false;
        }
    }

    private function setError($errCode, $errMsg, $errName, $condition, $errValue) {
        $this->errCode = $errCode == 0 && $this->defaultError == true ? $this->defaultErrorCode : $errCode;
        $this->errMsg = $errMsg == '' && $this->defaultError == true ? sprintf($this->defaultErrorMsg, $errName) : $errMsg;
        $this->errName = $errName;
        $this->errValue = $errValue;
        $this->errCondition = $condition;
    }

    /**
     * 获取所有错误提示
     * @date 2024/2/20
     * @return array
     * @author litong
     */
    public function errAll() {
        return [
            'errCode' => $this->errCode,
            'errMsg' => $this->errMsg,
            'errName' => $this->errName,
            'errValue' => $this->errValue,
            'errCondition' => $this->errCondition,
        ];
    }

    /**
     * 校验错误时, 获取错误码
     * @date 2023/3/5
     * @return int
     * @author litong
     */
    public function errCode() {
        return $this->errCode;
    }

    /**
     * 校验错误时, 获取信息
     * @date 2023/3/5
     * @return string
     * @author litong
     */
    public function errMsg() {
        return $this->errMsg;
    }

    /**
     * 校验错误时, 获取出错的属性名称
     * @date 2023/3/5
     * @return string
     * @author litong
     */
    public function errName() {
        return $this->errName;
    }

    /**
     * 校验错误时, 获取出错属性的值
     * @date 2023/3/5
     * @return int|null
     * @author litong
     */
    public function errValue() {
        return $this->errValue;
    }

    /**
     * 校验错误时, 获取未校验通过的条件
     * @date 2023/3/5
     * @return string
     * @author litong
     */
    public function errCondition() {
        return $this->errCondition;
    }
}