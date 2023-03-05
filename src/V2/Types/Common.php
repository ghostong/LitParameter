<?php


namespace Lit\Parameter\V2\Types;


class Common
{
    protected $name = null;
    protected $value = null;
    protected $valueType = "";
    protected $defaultValue = null;

    protected $errorCode = 0;
    protected $errorMsg = "";

    protected $workShop = [];

    /**
     * 值在数组指定范围内
     * @date 2023/3/5
     * @param $array
     * @return Common
     * @author litong
     */
    public function in($array) {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($array) {
            return in_array($value, $array);
        });
        return $this;
    }

    /**
     * 值不为null
     * @date 2023/3/5
     * @return Common
     * @author litong
     */
    public function notNull() {
        $this->setWorkShop(__FUNCTION__, function ($value) {
            return null !== $value;
        });
        return $this;
    }

    /**
     * 值不为空
     * @date 2023/3/5
     * @return Common
     * @author litong
     */
    public function notEmpty() {
        $this->setWorkShop(__FUNCTION__, function ($value) {
            return !empty($value);
        });
        return $this;
    }

    /**
     * 自定义验证
     * @date 2023/3/5
     * @param $callback
     * @return Common
     * @author litong
     */
    public function callback($callback) {
        $this->setWorkShop(__FUNCTION__, $callback);
        return $this;
    }

    /**
     * 设置默认值
     * @date 2023/3/5
     * @param $value
     * @return Common
     * @author litong
     */
    public function setDefault($value) {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * 设置错误代码
     * @date 2023/3/5
     * @param $code
     * @return Common
     * @author litong
     */
    public function setCode($code) {
        $this->errorCode = $code;
        return $this;
    }

    /**
     * 设置错误消息
     * @date 2023/3/5
     * @param $msg
     * @return Common
     * @author litong
     */
    public function setMsg($msg) {
        $this->errorMsg = $msg;
        return $this;
    }

    /**
     * 获取错误代码
     * @date 2023/3/5
     * @return int
     * @author litong
     */
    public function getCode() {
        return $this->errorCode;
    }

    /**
     * 获取错误信息
     * @date 2023/3/5
     * @return int
     * @author litong
     */
    public function getMsg() {
        return $this->errorMsg;
    }

    /**
     * 获取属性值
     * @date 2023/3/5
     * @return Mixed
     * @author litong
     */
    public function getValue() {
        if (is_null($this->value) && !is_null($this->defaultValue)) {
            return $this->defaultValue;
        } else {
            return $this->value;
        }
    }

    /**
     * 设置属性值
     * @date 2023/3/5
     * @param $value
     * @return mixed
     * @author litong
     */
    public function setValue($value) {
        $this->value = $value;
        return $value;
    }

    /**
     * 获取属性名称
     * @date 2023/3/5
     * @return string
     * @author litong
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 获取属性类型
     * @date 2023/3/5
     * @return string
     * @author litong
     */
    public function getType() {
        return $this->valueType;
    }

    protected function setWorkShop($conditionName, $callable) {
        $this->workShop[] = [
            "condition" => $conditionName,
            "callable" => $callable
        ];
    }

    /**
     * 获取验证器数组
     * @date 2023/3/5
     * @return array
     * @author litong
     */
    public function getWorkShop() {
        return $this->workShop;
    }

}