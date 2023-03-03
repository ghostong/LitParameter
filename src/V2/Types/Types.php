<?php


namespace Lit\Parameter\V2\Types;


class Types
{
    protected $name = "";

    protected $typeObject = null;

    public function __construct($name) {
        $this->name = $name;
    }

    public function integer() {
        $this->typeObject = new TypeInteger($this->name);
        return $this->typeObject;
    }

    public function getObject() {
        if (!$this->typeObject) {
            $this->typeObject = new TypeMixed($this->name);
        }
        return $this->typeObject;
    }

    public function value() {
        return $this->getObject()->getValue();
    }

    public function type() {
        return $this->getObject()->getType();
    }

}