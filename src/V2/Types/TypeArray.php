<?php


namespace Lit\Parameter\V2\Types;


class TypeArray extends Common
{
    protected $valueType = "numeric";

    public function __construct($name) {
        $this->name = $name;
        $this->setWorkShop(function ($value) {
            return is_array($value);
        });
        return $this;
    }

    /**
     * 数组最小元素数
     * @date 2023/3/5
     * @param $size
     * @return TypeArray
     * @author litong
     */
    public function minSize($size) {
        $this->setWorkShop(function ($value) use ($size) {
            return count($value) >= $size;
        });
        return $this;
    }

    /**
     * 数组最大元素数
     * @date 2023/3/5
     * @param $size
     * @return TypeArray
     * @author litong
     */
    public function maxSize($size) {
        $this->setWorkShop(function ($value) use ($size) {
            return count($value) <= $size;
        });
        return $this;
    }

    /**
     * 必须包含某些键
     * @date 2023/3/5
     * @param array $fields 包含字段 ["id","name"]
     * @return TypeArray
     * @author litong
     */
    public function incFields($fields) {
        $this->setWorkShop(function ($value) use ($fields) {
            return empty(array_diff_key(array_flip($fields), $value));
        });
        return $this;
    }

    /**
     * 必须排除某些键
     * @date 2023/3/5
     * @param array $fields 排除字段 ["age","gender"]
     * @return TypeArray
     * @author litong
     */
    public function excFields($fields) {
        $this->setWorkShop(function ($value) use ($fields) {
            return !empty(array_diff_key(array_flip($fields), $value));
        });
        return $this;
    }

}