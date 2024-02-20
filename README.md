# LitParameter 映射验证器

## 说明

映射验证器: 是解决业务中数据映射与参数验证有效性的解决方案.

## 旧版本

V1 实参验证器 [参考文档](README_V1.md)

此文档为最新版本文档.

## 初始化

````php
/**
 * @property Types $name
 * @property Types $age
 * @property Types $height
 * @property Types $gender
 * @property Types $title
 * @property Types $description
 * @property Types $children
 * @property Types $city
 * @property Types $number
 * @property Types $data
 * @property Types $create_time
 */
class AbcMapper extends \Lit\Parameter\V2\Parameter
{
    //是否使用默认错误, 开启此选项后, 不调用setCode(), setMsg() 即使用默认错误码和错误信息
    protected $defaultError = true;
    //默认错误码 默认值: 77777 可自定义
    protected $defaultErrorCode = '390';
    //默认错误信息 默认值: 参数错误(%s), %s 会被替换为 参数名成
    protected $defaultErrorMsg = '参数错误了';

    public function __construct($params = []) {

        //声明一个属性 age, 整型, 介于12值18之间, 如果验证出错, 错误代码: 10001, 错误信息: 年龄错误 (下同)
        $this->age->isInteger()->between(12, 18)->setCode(10001)->setMsg("年龄错误");

        //声明一个属性 height, 整型, 大于120, 小于180
        $this->height->isInteger()->ge(120)->le(180)->setCode(10002)->setMsg("身高错误");

        //声明一个属性 name, 字符串, 最小长度3, 最大长度32
        $this->name->isString()->minLength(3)->maxLength(32)->setCode(10003)->setMsg("名称错误");

        //声明一个属性 gender, 字符串, 枚举: m, f
        $this->gender->isString()->in(["m", "f"])->setCode(10004)->setMsg("性别错误");

        //声明一个属性 title, 字符串, 长度为5
        $this->title->isString()->length(5)->notEmpty()->setCode(10005)->setMsg("标题错误");

        //声明一个属性 description, 字符串, 不为 null
        $this->description->isString()->notNull()->setCode(10006)->setMsg("描述错误");

        //声明一个属性 children, 数组, 元素数最小2个, 最大3个
        $this->children->isArray()->minSize(2)->maxSize(3)->setCode(10007)->setMsg("子集错误");

        //声明一个属性 city, 字符串, 无赋值时默认为
        $this->city->isString()->setDefault("zz")->setCode(10008)->setMsg("城市错误");

        //声明一个属性 number, 数值, 使用回调函数自行验证
        $this->number->isNumeric()->callback(function ($number) {
            return $number > 999;
        })->setCode(10009)->setMsg("编号错误");

        //声明一个属性 data, 数组, 数组的键不能包含 aa, bb , 数组的键必须包含 cc, dd
        $this->data->isArray()->excFields(["aa", "bb"])->incFields(["cc", "dd"])->setCode(10010)->setMsg("数据集错误");
        
        $this->create_time->isDateTime()->formatIs('Y-m-d H');

        //启用快速赋值(非必须)
        parent::__construct($params);
    }

}
````

## 使用方法

### 使用验证器

#### 映射器赋值

````php
//赋值
$abcMapper = new AbcMapper();
$abcMapper->age = 12;
$abcMapper->height = 180;
$abcMapper->name = "good boy";
$abcMapper->gender = "f";
$abcMapper->title = "title";
$abcMapper->description = "";
$abcMapper->children = ["a", "b", "c"];
$abcMapper->number = 9990;
$abcMapper->data = ["cc" => 11, "dd" => 22, "ee" => 33];

//快速赋值 (需在映射器中调用父类构造函数)
$abcMapper = new AbcMapper(["age"=>12, "name" =>"good boy"]);
````

#### 获取已赋值属性的值

````php
var_dump($abcMapper->name->value());
var_dump($abcMapper->age->value());
````

#### 获取批量获取属性的值

````php
//已赋值的属性
var_dump($abcMapper->getAssigned());
//所有属性
var_dump($abcMapper->toArray());
````

#### 指定必填参数并验证

````php
var_dump($abcMapper->must(["age", "name"])->check());
````

#### 验证失败的调试方法

````php
//错误码
$abcMapper->errCode();
//错误码
$abcMapper->errMsg();
//错误的属性名称
$abcMapper->errName();
//错误属性的值
$abcMapper->errValue();
//出错的验证条件
$abcMapper->errCondition();
````

### 示例

#### 验证一个数组的有效性

````php
//验证一个数组是否符合规范, 保证要验证的形参已经编写过规则. (参考初始化)
$array = ["title"=>"title","description"=>"description"];
if ($abcMapper->must(["age"])->check($array)) { //如果验证失败
    var_dump("验证成功");
}else{
    var_export([
        "errCode" => $abcMapper->errCode(),
        "errMsg" => $abcMapper->errMsg(),
        "errName" => $abcMapper->errName(),
        "errValue" => $abcMapper->errValue(),
        "errCondition" => $abcMapper->errCondition()
    ]);//调试信息
}
````

#### 验证一个函数参数的有效性

````php
//验证函数或者类方法, 保证要验证的形参已经编写过规则. (参考初始化)
function test($bookId, $bookName, $bookTags, $bookDesc) {
    if (!$abcMapper->check(get_defined_vars())) { //如果验证失败
        var_export([
            "errCode" => $abcMapper->errCode(),
            "errMsg" => $abcMapper->errMsg(),
            "errName" => $abcMapper->errName(),
            "errValue" => $abcMapper->errValue(),
            "errCondition" => $abcMapper->errCondition()
        ]);//调试信息
        return false;
    }
    //正常业务逻辑
    return true;
}

//在调用时传入实参
test(1234,"abcd",["id"=>890,"name"=>"xyz","target"=>"abc","bug1"=>"bug1"],"opqrst");
````

## 支持方法

|    方法    |           说明         |
| :---:      |          :---:        |
| isString() | 初始化一个字符串类型参数验证|
| isNumber() | 初始化一个整型类型参数验证 |
| isNumeric()| 初始化一个数值类型参数验证 |
| isArray()  | 初始化一个数组类型参数验证 |
| isFloat()  | 初始化一个浮点类型参数验证 |
| errCode()  | 获取验证错误代码 |
| errMsg()   | 获取验证错误信息 |
| errName()   | 获取错误的属性名称 |
| errValue()    | 获取错误属性的值 |
|errCondition()| 出错的验证条件 |

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
