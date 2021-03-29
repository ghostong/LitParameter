<?php

namespace Lit\Parameter\Types;

class IsArray extends Types implements InterfaceTypes
{

    public function minSize($size) {
        $this->setChecker(__FUNCTION__, $size);
        return $this;
    }

    public function maxSize($size) {
        $this->setChecker(__FUNCTION__, $size);
        return $this;
    }

    public function incFields($fields) {
        $this->setChecker(__FUNCTION__, $fields);
        return $this;
    }

    public function excFields($fields) {
        $this->setChecker(__FUNCTION__, $fields);
        return $this;
    }

}