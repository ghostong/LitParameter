<?php

namespace Lit\Parameter;

use Lit\Parameter\Types\IsArray;
use Lit\Parameter\Types\IsInt;
use Lit\Parameter\Types\IsString;

class Checker
{

    private $parameters = [];
    private static $calledClass = null;
    private static $code = null;
    private static $msg = null;
    private static $debug = null;

    public function isString($param) {
        if (!isset($this->parameters[$param])) {
            $this->parameters[$param] = new IsString();
        }
        return $this->parameters[$param];
    }

    public function isInt($param) {
        if (!isset($this->parameters[$param])) {
            $this->parameters[$param] = new IsInt();
        }
        return $this->parameters[$param];
    }

    public function isArray($param) {
        if (!isset($this->parameters[$param])) {
            $this->parameters[$param] = new IsArray();
        }
        return $this->parameters[$param];
    }

    public static function getCode() {
        return self::$code;
    }

    public static function getMsg() {
        return self::$msg;
    }

    public static function debug() {
        return self::$debug;
    }

    public static function check($args) {
        if (self::$calledClass === null) {
            $called = get_called_class();
            self::$calledClass = new $called;
        }
        $params = self::$calledClass->getParameters();
        foreach ($args as $name => $value) {
            if (isset($params[$name]) && $checker = $params[$name]) {
                foreach ($checker->getChecker() as $func => $arg) {
                    if (!call_user_func_array(array(__NAMESPACE__ . '\Workshop', $func), [$value, $arg])) {
                        self::setErrorMsg($checker->getCode(), $checker->getMsg(), "[" . $name . "]" . " check error [" . $func . "] expect : " . (is_array($arg) ? implode(",", $arg) : $arg));
                        return false;
                    }
                }
            }
        }
        return true;
    }

    private function getParameters() {
        return $this->parameters;
    }

    private static function setErrorMsg($code, $msg, $debug) {
        self::$code = $code;
        self::$msg = $msg;
        self::$debug = $debug;
    }
}