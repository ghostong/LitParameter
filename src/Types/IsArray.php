<?php

namespace Lit\Parameter\Types;

class IsArray extends Types implements InterfaceTypes
{

    /**
     * 数组最小元素数
     * @param int $size 数字
     * @return IsArray
     */
    public function minSize($size) {
        $this->setChecker(__FUNCTION__, $size);
        return $this;
    }

    /**
     * 数组最大元素数
     * @param int $size 数字
     * @return IsArray
     */
    public function maxSize($size) {
        $this->setChecker(__FUNCTION__, $size);
        return $this;
    }

    /**
     * 必须包含某些键
     * @param array $fields 包含字段 ["id","name"]
     * @return IsArray
     */
    public function incFields($fields) {
        $this->setChecker(__FUNCTION__, $fields);
        return $this;
    }

    /**
     * 必须排除某些键
     * @param array $fields 排除字段 ["id","name"]
     * @return IsArray
     */
    public function excFields($fields) {
        $this->setChecker(__FUNCTION__, $fields);
        return $this;
    }

}