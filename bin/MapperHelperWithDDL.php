#!/usr/bin/env php
<?php

$sqlFile = "./.mapperDdlFile.sql";
if (!is_file($sqlFile)) {
    $answer = ask("[ {$sqlFile} ] 不存在(Y创建/N退出)");
    if ($answer != 'y') {
        exit('退出');
    }
    if (!touch($sqlFile)) {
        exit("[ {$sqlFile} ] 创建失败\n");
    }
    ask("[ $sqlFile} ] 请把ddl语句放入文件中(回车键继续)\n" . realpath($sqlFile));
}

$sql = file_get_contents($sqlFile);

if (empty(trim($sql))) {
    exit("[ {$sqlFile} ] 为空, 退出\n");
} else {
    $fields = getFields($sql);
    if (empty($fields)) {
        exit("[ {$sqlFile} ] ddl 解析错误, 退出\n");
    }
    echo "\n", makeMapper($fields), "\n\n\n";

    $answer = ask("生成完毕, 是否删除文件[ {$sqlFile} ](Y删除/N不删除)");
    if ($answer == 'y') {
        exit("文件删除: " . (unlink($sqlFile) ? "成功" : "失败"));
    } else {
        exit("文件未删除,稍后可手动删除\n" . realpath($sqlFile) . "\n");
    }
}

function ask($ask) {
    echo $ask, "\n";
    $handle = fopen("php://stdin", "r");
    return strtolower(trim(fgets($handle)));
}

function makeMapper($fields) {
    $mapper[] = '<?php';
    $mapper[] = 'use Lit\Parameter\V2\Parameter;';
    $mapper[] = 'use Lit\Parameter\V2\Types\Types;';
    $mapper[] = '';
    $mapper[] = '/**';
    foreach ($fields as $field => $type) {
        $mapper[] = " * @property Types \${$field}";
    }
    $mapper[] = ' */';
    $mapper[] = '';
    $mapper[] = 'class ParamChecker extends Parameter {';
    $mapper[] = '';
    $mapper[] = '    protected $defaultError = true;';
    $mapper[] = '    //protected $defaultErrorMsg = \'参数有错误, 请更正!\';';
    $mapper[] = '    //protected $defaultErrorCode = \'10068\';';
    $mapper[] = '';
    $mapper[] = '    public function __construct($params = []) {';
    foreach ($fields as $field => $type) {
        $mapper[] = "        \$this->{$field}->" . getIsType($type) . "();";
    }
    $mapper[] = '';
    $mapper[] = '        parent::__construct($params);';
    $mapper[] = '    }';
    $mapper[] = '}';

    return implode("\n", $mapper);
}

function getIsType($type) {
    switch (true) {
        case stripos($type, 'char') !== false:
        case stripos($type, 'text') !== false:
        case stripos($type, 'enum') !== false:
            return 'isString';
        case stripos($type, 'int') !== false:
        case stripos($type, 'timestamp') !== false:
            return 'isNumeric';
        case stripos($type, 'year') !== false:
        case stripos($type, 'date') !== false:
        case stripos($type, 'time') !== false:
            return 'isDateTime';
        default:
            return "";
    }
}

function getFields($sql) {
    $fields = array();
    $sql = substr($sql, strpos($sql, '(') + 1, strrpos($sql, ')') - strpos($sql, '(') - 1);
    $segments = explode(',', $sql);
    foreach ($segments as $segment) {
        $segment = trim($segment);
        if (preg_match('/^`(.+?)`\s+(.+?)(\s|$)/', $segment, $matches)) {
            $fields[$matches[1]] = $matches[2];
        }
    }
    return $fields;
}