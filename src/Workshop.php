<?php

namespace Lit\Parameter;

class Workshop
{
    public static function IsInt($value) {
        return is_int($value);
    }

    public static function isString($value) {
        return is_string($value);
    }

    public static function notNull($value) {
        return null !== $value;
    }

    public static function notEmpty($value) {
        return !empty($value);
    }

    public static function length($value, $length) {
        return strlen($value) === $length;
    }

    public static function minLength($value, $length) {
        return !(strlen($value) < $length);
    }

    public static function maxLength($value, $length) {
        return !(strlen($value) > $length);
    }

    public static function between($value, $between) {
        return min($between) <= $value && $value <= max($between);
    }

    public static function in($value, $array) {
        return in_array($value, $array, true);
    }

    public static function gt($value, $number) {
        return $value > $number;
    }

    public static function lt($value, $number) {
        return $value < $number;
    }

    public static function ge($value, $number) {
        return $value >= $number;
    }

    public static function le($value, $number) {
        return $value <= $number;
    }

}