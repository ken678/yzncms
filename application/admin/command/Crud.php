<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | crud主程序
// +----------------------------------------------------------------------

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\Exception;
use think\exception\ErrorException;
use think\facade\Config;
use think\Loader;

class Crud extends Command
{
    protected $stubList = [];

    //关键保留字
    protected $internalKeywords = [
        'abstract',
        'and',
        'array',
        'as',
        'break',
        'callable',
        'case',
        'catch',
        'class',
        'clone',
        'const',
        'continue',
        'declare',
        'default',
        'die',
        'do',
        'echo',
        'else',
        'elseif',
        'empty',
        'enddeclare',
        'endfor',
        'endforeach',
        'endif',
        'endswitch',
        'endwhile',
        'eval',
        'exit',
        'extends',
        'final',
        'for',
        'foreach',
        'function',
        'global',
        'goto',
        'if',
        'implements',
        'include',
        'include_once',
        'instanceof',
        'insteadof',
        'interface',
        'isset',
        'list',
        'namespace',
        'new',
        'or',
        'print',
        'private',
        'protected',
        'public',
        'require',
        'require_once',
        'return',
        'static',
        'switch',
        'throw',
        'trait',
        'try',
        'unset',
        'use',
        'var',
        'while',
        'xor',
    ];

    /**
     * 受保护的系统表, crud不会生效
     */
    protected $systemTables = [
        'admin',
        'adminlog',
        'attachment',
        'auth_group',
        'auth_rule',
        'cache',
        'config',
        'field_type',
        'model',
        'model_field',
        'terms',
        'ems',
        'sms',
    ];

    /**
     * Enum类型识别为单选框的结尾字符,默认会识别为单选下拉列表
     */
    protected $enumRadioSuffix = ['data', 'state', 'status'];

    /**
     * Set类型识别为复选框的结尾字符,默认会识别为多选下拉列表
     */
    protected $setCheckboxSuffix = ['data', 'state', 'status'];

    /**
     * Int类型识别为日期时间的结尾字符,默认会识别为日期文本框
     */
    protected $intDateSuffix = ['time'];

    /**
     * 开关后缀
     */
    protected $switchSuffix = ['switch'];

    /**
     * 富文本后缀
     */
    protected $editorSuffix = ['content'];

    /**
     * 城市后缀
     */
    protected $citySuffix = ['city'];

    /**
     * 时间区间后缀
     */
    protected $rangeSuffix = ['range'];

    /**
     * JSON后缀
     */
    protected $jsonSuffix = ['json'];

    /**
     * 标签后缀
     */
    protected $tagSuffix = ['tag', 'tags'];

    /**
     * Selectpage对应的后缀
     */
    protected $selectpageSuffix = ['_id', '_ids'];

    /**
     * Selectpage多选对应的后缀
     */
    protected $selectpagesSuffix = ['_ids'];

    /**
     * 保留字段
     */
    protected $reservedField = ['admin_id'];

    /**
     * 排除字段
     */
    protected $ignoreFields = [];

    /**
     * 识别为图片字段
     */
    protected $imageField = ['image', 'images', 'avatar', 'avatars'];

    /**
     * 识别为文件字段
     */
    protected $fileField = ['file', 'files'];

    /**
     * 添加时间字段
     * @var string
     */
    protected $createTimeField = 'create_time';

    /**
     * 更新时间字段
     * @var string
     */
    protected $updateTimeField = 'update_time';

    /**
     * 软删除时间字段
     * @var string
     */
    protected $deleteTimeField = 'delete_time';

    protected function configure()
    {
        $this->setName('crud')
            ->addOption('table', 't', Option::VALUE_REQUIRED, 'table name without prefix', null)
            ->addOption('controller', 'c', Option::VALUE_OPTIONAL, 'controller name', null)
            ->addOption('model', 'm', Option::VALUE_OPTIONAL, 'model name', null)
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override or force delete,without tips', null)
            ->addOption('local', 'l', Option::VALUE_OPTIONAL, 'local model', 1)
            ->addOption('ignorefields', null, Option::VALUE_OPTIONAL | Option::VALUE_IS_ARRAY, 'ignore fields', null)
            ->addOption('db', null, Option::VALUE_OPTIONAL, 'database config name', 'database')
            ->setDescription('Build CRUD controller and model from table');

    }

    protected function execute(Input $input, Output $output)
    {
        $adminPath = dirname(__DIR__) . DS;
        //数据库
        $db = $input->getOption('db');
        //表名
        $table = $input->getOption('table') ?: '';
        if (!$table) {
            throw new Exception('table name can\'t empty');
        }
        //强制覆盖
        $force = $input->getOption('force');
        //自定义控制器
        $controller = $input->getOption('controller');
        //自定义模型
        $model = $input->getOption('model');
        $model = $model ?: $controller;
        //验证器类
        $validate = $model;
        //是否为本地model,为0时表示为全局model将会把model放在app/common/model中
        $local = $input->getOption('local');
        //排除字段
        $ignoreFields = $input->getOption('ignorefields');
        if ($ignoreFields) {
            $this->ignoreFields = $ignoreFields;
        }
        $this->reservedField = array_merge($this->reservedField, [$this->createTimeField, $this->updateTimeField, $this->deleteTimeField]);

        $dbconnect = Db::connect('mysql');
        $dbname    = Config::get($db . '.database');
        $prefix    = Config::get($db . '.prefix');
        //系统表无法生成，防止后台错乱
        if (in_array(str_replace($prefix, "", $table), $this->systemTables)) {
            throw new Exception('system table can\'t be crud');
        }

        //模块
        $moduleName         = 'admin';
        $modelModuleName    = $local ? $moduleName : 'common';
        $validateModuleName = $local ? $moduleName : 'common';

        //检查主表
        $modelName          = $table          = stripos($table, $prefix) === 0 ? substr($table, strlen($prefix)) : $table;
        $modelTableType     = 'table';
        $modelTableTypeName = $modelTableName = $modelName;
        $modelTableInfo     = $dbconnect->query("SHOW TABLE STATUS LIKE '{$modelTableName}'", [], true);
        if (!$modelTableInfo) {
            $modelTableType = 'name';
            $modelTableName = $prefix . $modelName;
            $modelTableInfo = $dbconnect->query("SHOW TABLE STATUS LIKE '{$modelTableName}'", [], true);
            if (!$modelTableInfo) {
                throw new Exception("table not found");
            }
        }
        $modelTableInfo = $modelTableInfo[0];

        //控制器
        list($controllerNamespace, $controllerName, $controllerFile, $controllerArr) = $this->getControllerData($moduleName, $controller, $table);
        //模型
        list($modelNamespace, $modelName, $modelFile, $modelArr) = $this->getModelData($modelModuleName, $model, $table);
        //验证器
        list($validateNamespace, $validateName, $validateFile, $validateArr) = $this->getValidateData($validateModuleName, $validate, $table);

        //处理基础文件名，取消所有下划线并转换为小写
        $baseNameArr  = $controllerArr;
        $baseFileName = Loader::parseName(array_pop($baseNameArr), 0);
        array_push($baseNameArr, $baseFileName);
        $controllerUrl = $this->getControllerUrl($moduleName, $baseNameArr);

        //视图文件
        $viewArr   = $controllerArr;
        $lastValue = array_pop($viewArr);
        $viewArr[] = Loader::parseName($lastValue, 0);
        array_unshift($viewArr, 'view');
        $viewDir = $adminPath . strtolower(implode(DS, $viewArr)) . DS;

        //最终将生成的文件路径
        $addFile        = $viewDir . 'add.html';
        $editFile       = $viewDir . 'edit.html';
        $indexFile      = $viewDir . 'index.html';
        $recyclebinFile = $viewDir . 'recyclebin.html';

        //非覆盖模式时如果存在控制器文件则报错
        if (is_file($controllerFile) && !$force) {
            throw new Exception("controller already exists!\nIf you need to rebuild again, use the parameter --force=true ");
        }

        //非覆盖模式时如果存在模型文件则报错
        if (is_file($modelFile) && !$force) {
            throw new Exception("model already exists!\nIf you need to rebuild again, use the parameter --force=true ");
        }

        //非覆盖模式时如果存在验证文件则报错
        if (is_file($validateFile) && !$force) {
            throw new Exception("validate already exists!\nIf you need to rebuild again, use the parameter --force=true ");
        }

        //从数据库中获取表字段信息
        $sql = "SELECT * FROM `information_schema`.`columns` "
            . "WHERE TABLE_SCHEMA = ? AND table_name = ? "
            . "ORDER BY ORDINAL_POSITION";
        //加载主表的列
        $columnList = $dbconnect->query($sql, [$dbname, $modelTableName]);
        $fieldArr   = [];
        foreach ($columnList as $k => $v) {
            $fieldArr[] = $v['COLUMN_NAME'];
        }

        $addList  = [];
        $editList = [];
        try {
            $appendAttrList       = [];
            $controllerAssignList = [];

            foreach ($columnList as $k => $v) {
                $field        = $v['COLUMN_NAME'];
                $fieldComment = $v['COLUMN_COMMENT'] ?: $field;

                $itemArr = [];

                $inputType = '';
                //保留字段不能修改和添加
                if ($v['COLUMN_KEY'] != 'PRI' && !in_array($field, $this->reservedField) && !in_array($field, $this->ignoreFields)) {
                    $inputType = $this->getFieldType($v);

                    // 如果是number类型时增加一个步长
                    $step = $inputType == 'number' && $v['NUMERIC_SCALE'] > 0 ? "0." . str_repeat(0, $v['NUMERIC_SCALE'] - 1) . "1" : 0;

                    $attrArr      = ['id' => "c-{$field}"];
                    $cssClassArr  = ['form-control'];
                    $fieldName    = "row[{$field}]";
                    $defaultValue = $v['COLUMN_DEFAULT'];
                    $editValue    = "{\$row.{$field}|row}";
                    if ($inputType == 'select') {
                        $formAddElement  = '';
                        $formEditElement = '';

                    } elseif ($inputType == 'datetime') {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } elseif ($inputType == 'datetimerange') {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } elseif ($inputType == 'checkbox' || $inputType == 'radio') {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } elseif ($inputType == 'textarea' && !$this->isMatchSuffix($field, $this->selectpagesSuffix) && !$this->isMatchSuffix($field, $this->imageField)) {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } elseif ($inputType == 'switch') {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } elseif ($inputType == 'citypicker') {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } elseif ($inputType == 'tagsinput') {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } elseif ($inputType == 'fieldlist') {
                        $formAddElement  = '';
                        $formEditElement = '';
                    } else {
                        $formAddElement  = \Form::input($inputType, $fieldName, $defaultValue, $attrArr);
                        $formEditElement = \Form::input($inputType, $fieldName, $editValue, $attrArr);
                    }
                    //构造添加和编辑HTML信息
                    $addList[]  = $this->getFormGroup($fieldComment, $formAddElement);
                    $editList[] = $this->getFormGroup($fieldComment, $formEditElement);
                }

            }

            $addList  = implode("\n", array_filter($addList));
            $editList = implode("\n", array_filter($editList));

            //表注释
            $tableComment = $modelTableInfo['Comment'];
            $tableComment = mb_substr($tableComment, -1) == '表' ? mb_substr($tableComment, 0, -1) . '管理' : $tableComment;

            $data = [
                'controllerNamespace'     => $controllerNamespace,
                'controllerName'          => $controllerName,
                'controllerIndex'         => '',
                'controllerUrl'           => $controllerUrl,
                'modelConnection'         => $db == 'database' ? '' : "protected \$connection = '{$db}';",
                'modelNamespace'          => $modelNamespace,
                'modelName'               => $modelName,
                'modelTableType'          => $modelTableType,
                'modelTableTypeName'      => $modelTableTypeName,
                'validateName'            => $validateName,
                'validateNamespace'       => $validateNamespace,
                'addList'                 => $addList,
                'editList'                => $editList,
                'softDeleteClassPath'     => in_array($this->deleteTimeField, $fieldArr) ? "use think\model\concern\SoftDelete;" : '',
                'softDelete'              => in_array($this->deleteTimeField, $fieldArr) ? "use SoftDelete;" : '',
                'tableComment'            => $tableComment,
                'modelAutoWriteTimestamp' => in_array($this->createTimeField, $fieldArr) || in_array($this->updateTimeField, $fieldArr) ? "'int'" : 'false',
                'createTime'              => in_array($this->createTimeField, $fieldArr) ? "'{$this->createTimeField}'" : 'false',
                'updateTime'              => in_array($this->updateTimeField, $fieldArr) ? "'{$this->updateTimeField}'" : 'false',
                'deleteTime'              => in_array($this->deleteTimeField, $fieldArr) ? "'{$this->deleteTimeField}'" : 'false',
                'appendAttrList'          => implode(",\n", $appendAttrList),
            ];
            // 生成控制器文件
            $this->writeToFile('controller', $data, $controllerFile);
            // 生成模型文件
            $this->writeToFile('model', $data, $modelFile);
            // 生成验证文件
            $this->writeToFile('validate', $data, $validateFile);
            // 生成视图文件
            $this->writeToFile('add', $data, $addFile);
            $this->writeToFile('edit', $data, $editFile);
            $this->writeToFile('index', $data, $indexFile);

        } catch (ErrorException $e) {
            throw new Exception("Code: " . $e->getCode() . "\nLine: " . $e->getLine() . "\nMessage: " . $e->getMessage() . "\nFile: " . $e->getFile());
        }
        $output->info("Build Successed");
    }

    protected function getFieldType(&$v)
    {
        $inputType = 'text';
        switch ($v['DATA_TYPE']) {
            case 'bigint':
            case 'int':
            case 'mediumint':
            case 'smallint':
            case 'tinyint':
                $inputType = 'number';
                break;
            case 'enum':
            case 'set':
                $inputType = 'select';
                break;
            case 'decimal':
            case 'double':
            case 'float':
                $inputType = 'number';
                break;
            case 'longtext':
            case 'text':
            case 'mediumtext':
            case 'smalltext':
            case 'tinytext':
                $inputType = 'textarea';
                break;
            case 'year':
            case 'date':
            case 'time':
            case 'datetime':
            case 'timestamp':
                $inputType = 'datetime';
                break;
            default:
                break;
        }
        $fieldsName = $v['COLUMN_NAME'];
        // 指定后缀说明也是个时间字段
        if ($this->isMatchSuffix($fieldsName, $this->intDateSuffix)) {
            $inputType = 'datetime';
        }
        // 指定后缀结尾且类型为enum,说明是个单选框
        if ($this->isMatchSuffix($fieldsName, $this->enumRadioSuffix) && $v['DATA_TYPE'] == 'enum') {
            $inputType = "radio";
        }
        // 指定后缀结尾且类型为set,说明是个复选框
        if ($this->isMatchSuffix($fieldsName, $this->setCheckboxSuffix) && $v['DATA_TYPE'] == 'set') {
            $inputType = "checkbox";
        }
        // 指定后缀结尾且类型为char或tinyint且长度为1,说明是个Switch复选框
        if ($this->isMatchSuffix($fieldsName, $this->switchSuffix) && ($v['COLUMN_TYPE'] == 'tinyint(1)' || $v['COLUMN_TYPE'] == 'char(1)') && $v['COLUMN_DEFAULT'] !== '' && $v['COLUMN_DEFAULT'] !== null) {
            $inputType = "switch";
        }
        // 指定后缀结尾城市选择框
        if ($this->isMatchSuffix($fieldsName, $this->citySuffix) && ($v['DATA_TYPE'] == 'varchar' || $v['DATA_TYPE'] == 'char')) {
            $inputType = "citypicker";
        }
        // 指定后缀结尾城市选择框
        if ($this->isMatchSuffix($fieldsName, $this->rangeSuffix) && ($v['DATA_TYPE'] == 'varchar' || $v['DATA_TYPE'] == 'char')) {
            $inputType = "datetimerange";
        }
        // 指定后缀结尾JSON配置
        if ($this->isMatchSuffix($fieldsName, $this->jsonSuffix) && ($v['DATA_TYPE'] == 'varchar' || $v['DATA_TYPE'] == 'text')) {
            $inputType = "fieldlist";
        }
        // 指定后缀结尾标签配置
        if ($this->isMatchSuffix($fieldsName, $this->tagSuffix) && ($v['DATA_TYPE'] == 'varchar' || $v['DATA_TYPE'] == 'text')) {
            $inputType = "tagsinput";
        }
        return $inputType;
    }

    /**
     * 判断是否符合指定后缀
     * @param string $field     字段名称
     * @param mixed  $suffixArr 后缀
     * @return boolean
     */
    protected function isMatchSuffix($field, $suffixArr)
    {
        $suffixArr = is_array($suffixArr) ? $suffixArr : explode(',', $suffixArr);
        foreach ($suffixArr as $k => $v) {
            if (preg_match("/{$v}$/i", $field)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取表单分组数据
     * @param string $field
     * @param string $content
     * @return string
     */
    protected function getFormGroup($field, $content)
    {
        return <<<EOD
    <div class="layui-form-item">
        <label class="layui-form-label">{$field}</label>
        <div class="layui-input-block">
            {$content}
        </div>
    </div>
EOD;
    }

    /**
     * 获取控制器URL
     * @param string $moduleName
     * @param array  $baseNameArr
     * @return string
     */
    protected function getControllerUrl($moduleName, $baseNameArr)
    {
        for ($i = 0; $i < count($baseNameArr) - 1; $i++) {
            $temp           = array_slice($baseNameArr, 0, $i + 1);
            $temp[$i]       = ucfirst($temp[$i]);
            $controllerFile = APP_PATH . $moduleName . DS . 'controller' . DS . implode(DS, $temp) . '.php';
            //检测父级目录同名控制器是否存在，存在则变更URL格式
            if (is_file($controllerFile)) {
                $baseNameArr = [implode('.', $baseNameArr)];
                break;
            }
        }
        $controllerUrl = strtolower(implode('/', $baseNameArr));
        return $controllerUrl;
    }

    /**
     * 获取控制器相关信息
     * @param $module
     * @param $controller
     * @param $table
     * @return array
     */
    protected function getControllerData($module, $controller, $table)
    {
        return $this->getParseNameData($module, $controller, $table, 'controller');
    }

    /**
     * 获取模型相关信息
     * @param $module
     * @param $model
     * @param $table
     * @return array
     */
    protected function getModelData($module, $model, $table)
    {
        return $this->getParseNameData($module, $model, $table, 'model');
    }

    /**
     * 获取验证器相关信息
     * @param $module
     * @param $validate
     * @param $table
     * @return array
     */
    protected function getValidateData($module, $validate, $table)
    {
        return $this->getParseNameData($module, $validate, $table, 'validate');
    }

    /**
     * 获取已解析相关信息
     * @param string $module 模块名称
     * @param string $name   自定义名称
     * @param string $table  数据表名
     * @param string $type   解析类型，本例中为controller、model、validate
     * @return array
     */
    protected function getParseNameData($module, $name, $table, $type)
    {
        $arr = [];
        if (!$name) {
            $parseName = Loader::parseName($table, 1);
            $name      = str_replace('_', '/', $table);
        }

        $name      = str_replace(['.', '/', '\\'], '/', $name);
        $arr       = explode('/', $name);
        $parseName = ucfirst(array_pop($arr));
        $parseArr  = $arr;
        array_push($parseArr, $parseName);
        //类名不能为内部关键字
        if (in_array(strtolower($parseName), $this->internalKeywords)) {
            throw new Exception('Unable to use internal variable:' . $parseName);
        }
        $appNamespace   = 'app';
        $parseNamespace = "{$appNamespace}\\{$module}\\{$type}" . ($arr ? "\\" . implode("\\", $arr) : "");
        $moduleDir      = APP_PATH . $module . DS;
        $parseFile      = $moduleDir . $type . DS . ($arr ? implode(DS, $arr) . DS : '') . $parseName . '.php';
        return [$parseNamespace, $parseName, $parseFile, $parseArr];
    }

    /**
     * 写入到文件
     * @param string $name
     * @param array  $data
     * @param string $pathname
     * @return mixed
     */
    protected function writeToFile($name, $data, $pathname)
    {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $content = $this->getReplacedStub($name, $data);

        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }
        return file_put_contents($pathname, $content);
    }

    /**
     * 获取替换后的数据
     * @param string $name
     * @param array  $data
     * @return string
     */
    protected function getReplacedStub($name, $data)
    {
        foreach ($data as $index => &$datum) {
            $datum = is_array($datum) ? '' : $datum;
        }
        unset($datum);
        $search = $replace = [];
        foreach ($data as $k => $v) {
            $search[]  = "{%{$k}%}";
            $replace[] = $v;
        }
        $stubname = $this->getStub($name);
        if (isset($this->stubList[$stubname])) {
            $stub = $this->stubList[$stubname];
        } else {
            $this->stubList[$stubname] = $stub = file_get_contents($stubname);
        }
        $content = str_replace($search, $replace, $stub);
        return $content;
    }

    /**
     * 获取基础模板
     * @param string $name
     * @return string
     */
    protected function getStub($name)
    {
        return __DIR__ . DS . 'Crud' . DS . 'stubs' . DS . $name . '.stub';
    }
}
