<?php

namespace Lit\Parameter;

use Lit\Parameter\Types\IsArray;
use Lit\Parameter\Types\IsNumber;
use Lit\Parameter\Types\IsNumeric;
use Lit\Parameter\Types\IsString;

class Checker
{

    private $parameters = [];
    private static $calledClass = null;
    private static $code = 0;
    private static $msg = "";
    private static $debug = [];

    public function isString($param) {
        if (!isset($this->parameters[$param])) {
            $this->parameters[$param] = new IsString();
        }
        return $this->parameters[$param];
    }

    public function isNumber($param) {
        if (!isset($this->parameters[$param])) {
            $this->parameters[$param] = new IsNumber();
        }
        return $this->parameters[$param];
    }

    public function isArray($param) {
        if (!isset($this->parameters[$param])) {
            $this->parameters[$param] = new IsArray();
        }
        return $this->parameters[$param];
    }

    public function isNumeric($param) {
        if (!isset($this->parameters[$param])) {
            $this->parameters[$param] = new IsNumeric();
        }
        return $this->parameters[$param];
    }

    /**
     * 获取错误码
     * @date 2021/8/23
     * @return int
     * @author litong
     */
    public static function getCode() {
        return self::$code;
    }

    /**
     * 获取错误提示
     * @date 2021/8/23
     * @return string
     * @author litong
     */
    public static function getMsg() {
        return self::$msg;
    }

    /**
     * 获取调试信息
     * @date 2021/8/23
     * @return array
     * @author litong
     */
    public static function debug() {
        return self::$debug;
    }

    /**
     * 执行参数校验
     * @date 2021/8/23
     * @param array $params 要验证的参数
     * @return bool
     * @author litong
     */
    public static function check($params) {
        if (self::$calledClass === null) {
            $called = get_called_class();
            self::$calledClass = new $called;
        }
        $userParams = self::$calledClass->getParameters();
        foreach ($params as $name => $value) {
            if (isset($userParams[$name]) && $checker = $userParams[$name]) {
                foreach ($checker->getChecker() as $func => $arg) {
                    if (!call_user_func_array(array(__NAMESPACE__ . '\Workshop', $func), [$value, $arg])) {
                        self::errorBuild($checker->getCode(), $checker->getMsg(), $name, $func, $value, $arg);
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

    private static function errorBuild($code, $msg, $param, $func, $actual, $expect) {
        $debug["expect"] = $expect;
        $debug["actual"] = $actual;
        $debug["function"] = $func;
        $debug["errorCode"] = $code;
        $debug["errorMsg"] = $msg;
        $expect = str_replace("\n", "", var_export($expect, true));
        $actual = str_replace("\n", "", var_export($actual, true));
        $debug["message"] = sprintf("Parameter \"%s\" check error on \"%s\",  expect: \"%s\", actual: \"%s\"", $param, $func, $expect, $actual);
        self::setErrorMsg($code, $msg, $debug);
    }

    private static function setErrorMsg($code, $msg, $debug) {
        self::$code = $code;
        self::$msg = $msg;
        self::$debug = $debug;
    }
}