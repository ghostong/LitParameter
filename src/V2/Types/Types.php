<?php


namespace Lit\Parameter\V2\Types;


class Types
{
    protected $name = "";

    protected $typeObject = null;

    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * 整型声明
     * @date 2023/3/5
     * @return TypeInteger
     * @author litong
     */
    public function isInteger() {
        $this->typeObject = new TypeInteger($this->name);
        return $this->typeObject;
    }

    /**
     * 数字声明
     * @date 2023/3/5
     * @return TypeNumeric
     * @author litong
     */
    public function isNumeric() {
        $this->typeObject = new TypeNumeric($this->name);
        return $this->typeObject;
    }

    /**
     * 浮点声明
     * @date 2023/3/5
     * @return TypeFloat
     * @author litong
     */
    public function isFloat() {
        $this->typeObject = new TypeFloat($this->name);
        return $this->typeObject;
    }

    /**
     * 字符串声明
     * @date 2023/3/5
     * @return TypeString
     * @author litong
     */
    public function isString() {
        $this->typeObject = new TypeString($this->name);
        return $this->typeObject;
    }

    /**
     * 数组声明
     * @date 2023/3/5
     * @return TypeArray
     * @author litong
     */
    public function isArray() {
        $this->typeObject = new TypeArray($this->name);
        return $this->typeObject;
    }

    /**
     * 获取属性声明类型
     * @date 2023/3/5
     * @return TypeMixed|TypeInteger|TypeNumeric|TypeArray|TypeString
     * @author litong
     */
    public function getObject() {
        if (!$this->typeObject) {
            $this->typeObject = new TypeMixed($this->name);
        }
        return $this->typeObject;
    }

    /**
     * 获取属性值
     * @date 2023/3/5
     * @return mixed
     * @author litong
     */
    public function value() {
        return $this->getObject()->getValue();
    }

    /**
     * 获取属性类型
     * @date 2023/3/5
     * @return string
     * @author litong
     */
    public function type() {
        return $this->getObject()->getType();
    }

    /**
     * 禁止直接 toString
     * @date 2023/12/22
     * @return void
     * @author litong
     */
    public function __toString() {
        $trace = debug_backtrace(0);
        trigger_error("Use the value() method to read the variable value. in {$trace[0]["file"]} on line {$trace[0]["line"]}  -> ", E_USER_ERROR);
    }

//    /**
//     * 禁止直接 debugInfo
//     * @date 2023/12/22
//     * @return void
//     * @author litong
//     */
//    public function __debugInfo() {
//        $trace = debug_backtrace(1);
//        trigger_error("Use the value() method to read the variable value. in {$trace[1]["file"]} on line {$trace[1]["line"]}  -> ", E_USER_ERROR);
//    }
}