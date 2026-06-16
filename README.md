# LitParameter 映射验证器

LitParameter 是一个 PHP 参数映射与验证库，用于在业务代码中声明输入结构、快速赋值、校验参数并读取规范化后的字段值。

当前维护版本为 V2，推荐通过继承 `Lit\Parameter\V2\Parameter` 定义 Mapper。

## 安装

```bash
composer require lit/parameter
```

项目使用 PSR-4 自动加载：

```php
require __DIR__ . '/vendor/autoload.php';
```

## 快速开始

```php
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;

/**
 * @property Types $id
 * @property Types $name
 * @property Types $email
 * @property Types $tags
 */
class UserMapper extends Parameter
{
    protected $defaultError = true;
    protected $defaultErrorCode = 77777;
    protected $defaultErrorMsg = '参数错误(%s)';

    public function __construct($params = []) {
        $this->id->isInteger()->gt(0)->setCode(1001)->setMsg('ID 错误');
        $this->name->isString()->notEmpty()->maxLength(32)->setCode(1002)->setMsg('名称错误');
        $this->email->isString()->emailFormat()->setCode(1003)->setMsg('邮箱错误');
        $this->tags->isArray()->minSize(1)->setDefault([]);

        parent::__construct($params);
    }
}

$mapper = new UserMapper([
    'id' => 1,
    'name' => 'lit',
    'email' => 'lit@example.com',
    'tags' => ['php'],
]);

if (!$mapper->must(['id', 'name'])->check()) {
    var_export($mapper->errAll());
    return;
}

var_dump($mapper->id->value());
var_dump($mapper->toArray());
```

## 定义 Mapper

Mapper 继承 `Lit\Parameter\V2\Parameter`，在构造函数中声明字段类型和校验规则。

```php
use Lit\Parameter\V2\Parameter;
use Lit\Parameter\V2\Types\Types;

/**
 * @property Types $age
 * @property Types $height
 * @property Types $gender
 * @property Types $children
 * @property Types $data
 * @property Types $create_time
 */
class ExampleMapper extends Parameter
{
    protected $defaultError = true;
    protected $defaultErrorCode = 390;
    protected $defaultErrorMsg = '参数错误(%s)';

    public function __construct($params = []) {
        $this->age->isInteger()->between(12, 18)->setCode(10001)->setMsg('年龄错误');
        $this->height->isInteger()->ge(120)->le(180)->setCode(10002)->setMsg('身高错误');
        $this->gender->isString()->in(['m', 'f'])->setCode(10003)->setMsg('性别错误');
        $this->children->isArray()->minSize(2)->maxSize(3)->setCode(10004)->setMsg('子集错误');
        $this->data->isArray()->excFields(['aa', 'bb'])->incFields(['cc', 'dd'])->setCode(10005)->setMsg('数据集错误');
        $this->create_time->isDateTime()->formatIs('Y-m-d H')->setCode(10006)->setMsg('创建时间错误');

        parent::__construct($params);
    }
}
```

`parent::__construct($params)` 用于支持构造时快速赋值。

## 赋值与读取

```php
$mapper = new ExampleMapper();

$mapper->age = 12;
$mapper->height = 170;
$mapper->gender = 'f';
$mapper->children = ['a', 'b'];
$mapper->data = ['cc' => 11, 'dd' => 22];
$mapper->create_time = '2026-06-16 10';

// 读取单个字段值
var_dump($mapper->age->value());

// 读取字段类型
var_dump($mapper->age->type());

// 读取已赋值字段
var_dump($mapper->getAssigned());

// 读取所有已声明字段
var_dump($mapper->toArray());

// 判断是否未赋值
var_dump($mapper->isEmpty());
```

不要直接把字段代理对象当字符串使用，读取值请使用 `value()`。

## 校验

### 校验已赋值字段

```php
$mapper = new ExampleMapper([
    'age' => 12,
    'height' => 170,
    'gender' => 'f',
    'children' => ['a', 'b'],
    'data' => ['cc' => 11, 'dd' => 22],
    'create_time' => '2026-06-16 10',
]);

if ($mapper->must(['age', 'gender'])->check()) {
    var_dump('验证成功');
} else {
    var_export($mapper->errAll());
}
```

`check()` 不传参数时，会校验当前 Mapper 已赋值的字段。

### 校验数组

```php
$mapper = new ExampleMapper();

$params = [
    'age' => 12,
    'height' => 170,
    'gender' => 'm',
    'children' => ['a', 'b'],
    'data' => ['cc' => 11, 'dd' => 22],
    'create_time' => '2026-06-16 10',
];

if (!$mapper->must(['age'])->check($params)) {
    var_export($mapper->errAll());
}
```

`check([])` 表示校验显式传入的空数组，不会回退校验已赋值字段。

### 校验函数参数

```php
function createUser($id, $name, $email) {
    $mapper = new UserMapper();

    if (!$mapper->must(['id', 'name'])->check(get_defined_vars())) {
        var_export($mapper->errAll());
        return false;
    }

    return true;
}
```

### 必填字段

```php
$mapper->must(['id', 'name'])->check($params);
```

`must()` 只检查字段是否存在，不判断字段值是否为空。是否允许 `null`、空字符串或空数组，请继续使用 `notNull()`、`notEmpty()` 等规则。

## 错误处理

校验失败后可以读取最近一次错误：

```php
var_export($mapper->errAll());
```

返回结构：

```php
[
    'errCode' => 1001,
    'errMsg' => 'ID 错误',
    'errName' => 'id',
    'errValue' => 0,
    'errCondition' => 'gt',
]
```

也可以分别读取：

```php
$mapper->errCode();
$mapper->errMsg();
$mapper->errName();
$mapper->errValue();
$mapper->errCondition();
```

每次调用 `check()` 都会重置上一轮错误状态。如果本轮校验成功，错误信息会保持为空。

开启错误位置调试：

```php
Parameter::debugOn();
```

## API 参考

### 字段类型

| 方法 | 说明 |
| --- | --- |
| `isInteger()` | 整型，使用 `is_int()` 校验 |
| `isFloat()` | 浮点型，使用 `is_float()` 校验 |
| `isNumeric()` | 数值或数字字符串，使用 `is_numeric()` 校验 |
| `isString()` | 字符串，使用 `is_string()` 校验 |
| `isArray()` | 数组，使用 `is_array()` 校验 |
| `isDateTime()` | 日期时间，判断 `strtotime()` 是否可解析 |

### 公共规则

| 方法 | 说明 |
| --- | --- |
| `notNull()` | 值不为 `null` |
| `notEmpty()` | 值不为空 |
| `in(array $array)` | 值在白名单内，使用严格比较 |
| `callback(callable $callback)` | 使用回调函数自定义校验 |
| `setDefault($value)` | 字段值为 `null` 时读取默认值 |
| `setCode($code)` | 设置验证失败错误码 |
| `setMsg($msg)` | 设置验证失败错误信息 |

### 字符串规则

| 方法 | 说明 |
| --- | --- |
| `length($length)` | 字符串长度等于指定值 |
| `minLength($length)` | 字符串长度大于等于指定值 |
| `maxLength($length)` | 字符串长度小于等于指定值 |
| `emailFormat()` | 邮箱格式 |
| `ipV4Format()` | IPv4 格式 |
| `ipV6Format()` | IPv6 格式 |

字符串长度规则使用 `strlen()`，多字节字符会按字节数计算。

### 数值规则

`isInteger()`、`isFloat()`、`isNumeric()` 支持以下规则：

| 方法 | 说明 |
| --- | --- |
| `gt($num)` | 大于 |
| `lt($num)` | 小于 |
| `ge($num)` | 大于等于 |
| `le($num)` | 小于等于 |
| `between($num1, $num2)` | 在闭区间内 |
| `pastTsFormat()` | 过去的时间戳，要求数值大于 0 且小于等于当前时间 |

### 数组规则

| 方法 | 说明 |
| --- | --- |
| `minSize($size)` | 元素数大于等于指定值 |
| `maxSize($size)` | 元素数小于等于指定值 |
| `incFields(array $fields)` | 必须包含指定键 |
| `excFields(array $fields)` | 不能包含指定键 |

### 日期时间规则

| 方法 | 说明 |
| --- | --- |
| `formatIs($format = 'Y-m-d H:i:s')` | 必须严格符合指定日期格式 |

示例：

```php
$this->create_time->isDateTime()->formatIs('Y-m-d H');
```

## DDL Mapper 生成器

包内提供一个辅助脚本，可以根据简单 DDL 生成 V2 Mapper 骨架：

```bash
php bin/MapperWithDDL.php
```

脚本默认读取当前目录下的 `.mapperDdlFile.sql`，并输出 Mapper 类代码。该工具适合生成基础骨架，复杂字段约束、错误码和错误信息仍建议手动补充。

## 注意事项

- V2 当前维护入口是 `Lit\Parameter\V2\Parameter`。
- 未声明具体类型的字段会被视为 `mixed`，不会出现在 `getAssigned()` 和 `toArray()` 的结果中。
- `in()` 使用严格比较，`'1'` 与 `1` 不相等。
- `isDateTime()` 只判断值能否被 `strtotime()` 解析；严格格式请使用 `formatIs()`。
- V1 已不再维护，历史文档见 [README_V1.md](README_V1.md)。
