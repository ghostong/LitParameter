# LitParameter 实参验证器

## 说明

实参验证器主要是解决业务中, 验证实参有效性的解决方案.

## 初始化

````php
class paramChecker extends \Lit\Parameter\Checker
{

    public function __construct() {
        
        //初始化一个字符串类型形参 bookName, 非空, 非null, 最小长度3, 最大长度9. 如果验证失败, 错误代码: 1007, 错误信息: 书籍名称错误
        $this->isString("bookName")->notEmpty()->notNull()->minLength(3)->maxLength(9)->setCode(1007)->setMsg("书籍名称错误");
        
        //初始化一个整型类型形参 bookId, 非空, 非null,介于5-10之间 . 如果验证失败, 错误代码: 1008, 错误信息: 书籍ID错误
        $this->isNumber("bookId")->notNull()->between(5, 10)->setCode(1008)->setMsg("书籍ID错误");
        
        //初始化一个数组类型形参 bookTags, 非空, 非null, 数组最小元素数2, 最大元素数5, 必须包含id,name两个字段, 不能包含bug1,bug2两个字段 . 如果验证失败, 错误代码: 1009, 错误信息: tags错误
        $this->isArray("bookTags")->notNull()->notEmpty()->minSize(2)->maxSize(5)->incFields(["id", "name"])->excFields(["bug1", "bug2"])->setCode(1009)->setMsg("tags错误");
        
        //初始化一个字符串类型形参, 使用回调函数验证参数正确性, 如果验证失败, 错误代码: 1010, 错误信息: tags错误
        $this->isString("bookDesc")->callback(function ($desc) {
            return strlen($desc) == 1;
        })->setCode(1010)->setMsg("desc 错误");
        //初始化一个数字或数字字符串
        $this->isNumeric("book_id")->in([1, 2, "1", "2"]);
    }

}
````

## 使用方法

````php
//业务中写了个函数或者类方法, 保证要验证的形参已经编写过规则. (参考初始化)
function testFunction($bookId, $bookName, $bookTags, $bookDesc) {
    if (!paramChecker::check(get_defined_vars())) { //如果验证失败
        var_dump(paramChecker::getCode()); //上一步设置的错误代码
        var_dump(paramChecker::getMsg()); //上一步设置的错误信息
        var_dump(paramChecker::debug()); //调试信息
        return false;
    }
    return true;
}

//在调用时传入实参
testFunction(1234,"abcd",["id"=>890,"name"=>"xyz","target"=>"abc","bug1"=>"bug1"],"opqrst");

//打印结果
/*
int(1008) // paramChecker::getCode() 错误代码
string(14) "书籍ID错误" // paramChecker::getMsg() 错误信息
array(6) {
  ["expect"]=> //预期值
  array(2) {
    [0]=>
    int(5)
    [1]=>
    int(10)
  }
  ["actual"]=> //实际传入
  int(1234)
  ["function"]=> //未验证成功规则
  string(7) "between"
  ["errorCode"]=> //错误码
  int(1008)
  ["errorMsg"]=> //错误信息 
  string(14) "书籍ID错误"
  ["message"]=> //完整错误
  string(99) "Parameter "bookId" check error on "between",  expect: "array (  0 => 5,  1 => 10,)", actual: "1234""
}
*/
````

## 支持方法

|    方法    |           说明         |
| :---:     |          :---:        |
| isString()| 初始化一个字符串类型参数验证|
| isNumber()| 初始化一个整型类型参数验证 |
| isArray() | 初始化一个数组类型参数验证 |
| isFloat() | 初始化一个浮点类型参数验证 |
| getCode() | 获取验证错误代码 |
| getMsg()  | 获取验证错误信息 |
| debug()   | 获取错误调试信息 |
|getChecker()| |

## 方法列表

| 可用选项     | isString |isNumber| isArray | isNumeric |   说明  |
|  :---:     |   :---:  | :---: |  :---:  |  :---: | :---: |
| notNull()  |    ✔️    |   ✔️  |   ✔️   |   ✔️  | 非null |
| notEmpty() |    ✔️    |   ✔️  |   ✔️   |   ✔️  | 非空   |
| callback() |    ✔️    |   ✔️  |   ✔️   |   ✔️  | 使用回调函数验证 |
| length()   |    ✔️    |   ❌  |   ❌️   |   ❌  | 字符串长度 |
| maxLength()|    ✔️    |   ❌  |   ❌️   |   ❌  | 字符串最大长度 |
| minLength()|    ✔️    |   ❌  |   ❌️   |   ❌  |字符串最小长度 |
|    in()    |    ✔️    |   ✔️  |   ❌️   |   ✔️  | 参数值白名单 |
| between()  |    ❌️    |   ✔️  |   ❌️   |   ✔️  | 在 a...b 两个数字(包含)之间 |
|   gt()     |    ❌️    |   ✔️  |   ❌️   |   ✔️  | 参数值小于一个数字 |
|   lt()     |    ❌️    |   ✔️  |   ❌️   |   ✔️  | 参数值大于一个数字 |
|   ge()     |    ❌️    |   ✔️  |   ❌️   |   ✔️  | 参数值大于等于一个数字 |
|   le()     |    ❌️    |   ✔️  |   ❌️   |   ✔️  | 参数值小于等于一个数字 |
| minSize()  |    ❌️    |   ❌️  |   ✔️   |   ❌️  | 数组最小元素数 |
| maxSize()  |    ❌️    |   ❌️  |   ✔️   |   ❌️  | 数组最大元素数 |
|incFields() |    ❌️    |   ❌️  |   ✔️   |   ❌️  | 必须包含某些键 |
|excFields() |    ❌️    |   ❌️  |   ✔️   |   ❌️  | 必须排除某些键 |
| setCode()  |    ✔️    |   ✔️  |   ✔️   |   ✔️  | 设置验证错误代码 |
| setMsg()   |    ✔️    |   ✔️  |   ✔️   |   ✔️  | 设置验证错误信息 |
|getChecker()|    ✔️    |   ✔️  |   ✔️   |   ✔️  | 获取所有验证信息 |
