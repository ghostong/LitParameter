<?php


namespace Lit\Parameter\V2\Types;


class CommonNumeric extends Common
{

    /**
     * 数字大于 $num
     * @date 2023/3/5
     * @param $num
     * @return CommonNumeric
     * @author litong
     */
    public function gt($num) {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($num) {
            return $value > $num;
        });
        return $this;
    }

    /**
     * 数字小于 $num
     * @date 2023/3/5
     * @param $num
     * @return CommonNumeric
     * @author litong
     */
    public function lt($num) {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($num) {
            return $value < $num;
        });
        return $this;
    }

    /**
     * 数字大于等于 $num
     * @date 2023/3/5
     * @param $num
     * @return CommonNumeric
     * @author litong
     */
    public function ge($num) {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($num) {
            return $value >= $num;
        });
        return $this;
    }


    /**
     * 数字小于等于 $num
     * @date 2023/3/5
     * @param $num
     * @return CommonNumeric
     * @author litong
     */
    public function le($num) {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($num) {
            return $value <= $num;
        });
        return $this;
    }

    /**
     * 数字在 $num1 与 $num2 之间 (包含边界)
     * @date 2023/3/5
     * @param $num1
     * @param $num2
     * @return CommonNumeric
     * @author litong
     */
    public function between($num1, $num2) {
        $this->setWorkShop(__FUNCTION__, function ($value) use ($num1, $num2) {
            return $value >= $num1 && $value <= $num2;
        });
        return $this;
    }

}