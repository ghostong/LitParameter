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
     * 数字/值声明
     * @date 2023/3/5
     * @return TypeNumeric
     * @author litong
     */
    public function isNumeric() {
        $this->typeObject = new TypeNumeric($this->name);
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

}