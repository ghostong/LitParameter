<?php


namespace Lit\Parameter\V2\Types;


class CommonNumeric extends Common
{


    public function gt($num) {
        $this->setWorkShop(function ($value) use ($num) {
            return $value > $num;
        });
        return $this;
    }

    public function lt($num) {
        $this->setWorkShop(function ($value) use ($num) {
            return $value < $num;
        });
        return $this;
    }

    public function ge($num) {
        $this->setWorkShop(function ($value) use ($num) {
            return $value >= $num;
        });
        return $this;
    }

    public function le($num) {
        $this->setWorkShop(function ($value) use ($num) {
            return $value <= $num;
        });
        return $this;
    }

    public function between($num1, $num2) {
        $this->setWorkShop(function ($value) use ($num1, $num2) {
            return $value >= $num1 && $value <= $num2;
        });
        return $this;
    }

}