<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace app\content\model;
use think\Db;
use think\Model;
use think\Loader;
use think\Request;
use app\common\Model\Modelbase;

/**
 * 字段模型
 */
class ModelField extends Modelbase
{
    //字段类型存放路径
    private $fieldPath = '';
    //不显示的字段类型（字段类型）
    public $not_allow_fields = array('catid', 'typeid', 'title', 'keyword', 'template', 'username', 'tags');
    //允许添加但必须唯一的字段（字段名）
    public $unique_fields = array('pages', 'readpoint', 'author', 'copyfrom', 'islink', 'posid');
     //禁止被禁用（隐藏）的字段列表（字段名）
    public $forbid_fields = array('catid', 'title', /* 'updatetime', 'inputtime', 'url', 'listorder', 'status', 'template', 'username', 'allow_comment', 'tags' */);
    //禁止被删除的字段列表（字段名）
    public $forbid_delete = array('catid', 'title', 'thumb', 'keyword', 'keywords', 'updatetime', 'tags', 'inputtime', 'posid', 'url', 'listorder', 'status', 'template', 'username', 'allow_comment');
    //可以追加 JS和CSS 的字段（字段名）
    public $att_css_js = array('text', 'textarea', 'box', 'number', 'keyword', 'typeid');

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        $this->fieldPath = APP_PATH . 'content/fields/';
    }

    /**
     *  编辑字段
     * @param type $data 编辑字段数据
     * @param type $fieldid 字段id
     * @return boolean
     */
    public function editField($data, $fieldid = 0)
    {
        /* 获取数据对象 */
        $oldData = $data = empty($data) ? Request::instance()->post() : $data;
        if (!$fieldid && !isset($data['fieldid'])) {
            $this->error = '缺少字段id！';
            return false;
        } else {
            $fieldid = $fieldid ? $fieldid : (int) $data['fieldid'];
        }
        //原字段信息
        $info = $this->where(array("fieldid" => $fieldid))->find();
        if (empty($info)) {
            $this->error = '该字段不存在！';
            return false;
        }
        //字段主表副表不能修改
        unset($data['issystem']);
        //字段类型
        if (empty($data['formtype'])) {
            $data['formtype'] = $info['formtype'];
        }
        //模型id
        $modelid = $info['modelid'];
        //完整表名获取 判断主表 还是副表
        $tablename = $this->getModelTableName($modelid, $info['issystem']);
        if (!$this->table_exists($tablename)) {
            $this->error = '数据表不存在！';
            return false;
        }

         //数据验证
        $validate = Loader::validate('ModelField');
        if(!$validate->scene('edit')->check($data)){
            $this->error = $validate->getError();
            return false;
        }
        /**
         * 对应字段配置
         * $field_type = 'varchar'; //字段数据库类型
         * $field_basic_table = 1; //是否允许作为主表字段
         * $field_allow_index = 1; //是否允许建立索引
         * $field_minlength = 0; //字符长度默认最小值
         * $field_maxlength = ''; //字符长度默认最大值
         * $field_allow_search = 1; //作为搜索条件
         * $field_allow_fulltext = 0; //作为全站搜索信息
         * $field_allow_isunique = 1; //是否允许值唯一
         */
        require $this->fieldPath . "{$data['formtype']}/config.php";

        if (false !== $this->where(array("fieldid" => $fieldid))->update($data)) {
            //清理缓存
            cache('ModelField', NULL);
            //如果字段名变更 需要继续执行
            if ($data['field'] && $info['field']) {
                //检查字段是否存在，只有当字段名改变才检测 不允许存在相同字段
                if ($data['field'] != $info['field'] && $this->field_exists($tablename, $data['field'])) {
                    $this->error = '该字段已经存在！';
                    //回滚
                    $this->where(array("fieldid" => $fieldid))->update($info);
                    return false;
                }
                $field = array(
                    'tablename' => config('database.prefix') . $tablename,
                    'newfilename' => $data['field'],
                    'oldfilename' => $info['field'],
                    'maxlength' => '',
                    'minlength' => '',
                    'defaultvalue' => '',
                    'minnumber' => 0,
                    'decimaldigits' => '',
                );
                if (false === $this->editFieldSql($field_type, $field)) {
                    $this->error = '数据库字段结构更改失败！';
                    //回滚
                    $this->where(array("fieldid" => $fieldid))->update($info);
                    return false;
                }
            }
            return true;
        }else {
            $this->error = '数据库更新失败！';
            return false;
        }

    }

    //添加字段
    public function addField($data = null)
    {
        /* 获取数据对象 */
        $oldData = $data = empty($data) ? Request::instance()->post() : $data;
        //字段附加配置
        //$setting = $data['setting'];
        //附加属性值
        //$data['setting'] = serialize($setting);
        //模型id
        $modelid = $data['modelid'];
        //完整表名获取 判断主表 还是副表
        $tablename = $this->getModelTableName($modelid, $data['issystem']);
        if (!$this->table_exists($tablename)) {
            $this->error = '数据表不存在！';
            return false;
        }
        //数据验证
        $validate = Loader::validate('ModelField');
        if(!$validate->scene('add')->check($data)){
            $this->error = $validate->getError();
            return false;
        }
        /**
         * 对应字段配置
         * $field_type = 'varchar'; //字段数据库类型
         * $field_basic_table = 1; //是否允许作为主表字段
         * $field_allow_index = 1; //是否允许建立索引
         * $field_minlength = 0; //字符长度默认最小值
         * $field_maxlength = ''; //字符长度默认最大值
         * $field_allow_search = 1; //作为搜索条件
         * $field_allow_fulltext = 0; //作为全站搜索信息
         * $field_allow_isunique = 1; //是否允许值唯一
         */
        require $this->fieldPath . "{$data['formtype']}/config.php";

        //检查该字段是否允许添加
        if (false === $this->isAddField($data['field'], $data['formtype'], $modelid)) {
            $this->error = '该字段名称/类型不允许添加！';
            return false;
        }
        //增加字段
        $field = array(
            'tablename' => config('database.prefix') . $tablename,
            'fieldname' => $data['field'],
            'maxlength' => '',
            'minlength' => '',
            'defaultvalue' => '',
            'minnumber' => 0,
            'decimaldigits' => '',
        );
        //先将字段存在设置的主表或附表里面 再将数据存入ModelField
        if ($this->addFieldSql($field_type, $field)) {
            $fieldid = $this->allowField(true)->insert($data);
            if ($fieldid) {
                //清理缓存
                cache('ModelField', NULL);
                return $fieldid;
            } else {
                $this->error = '字段信息入库失败！';
                //回滚
                Db::connect()->execute("ALTER TABLE  `{$field['tablename']}` DROP  `{$field['fieldname']}`");
                return false;
            }
        }else {
                $this->error = '数据库字段添加失败！';
                return false;
        }

    }

    /**
     * 删除字段
     * @param type $fieldid 字段id
     * @return boolean
     */
    public function deleteField($fieldid)
    {

        //原字段信息
        $info = $this->where(array("fieldid" => $fieldid))->find();
        if (empty($info)) {
            $this->error = '该字段不存在！';
            return false;
        }
        //模型id
        $modelid = $info['modelid'];
        //完整表名获取 判断主表 还是副表
        $tablename = $this->getModelTableName($modelid, $info['issystem']);
        if (!$this->table_exists($tablename)) {
            $this->error = '数据表不存在！';
            return false;
        }
        //判断是否允许删除
        if (false === $this->isDelField($info['field'])) {
            $this->error = '该字段不允许被删除！';
            return false;
        }
        if ($this->deleteFieldSql($info['field'], config('database.prefix') . $tablename)) {
            $this->where(array("fieldid" => $fieldid, "modelid" => $modelid))->delete();
            return true;
        } else {
            $this->error = '数据库表字段删除失败！';
            return false;
        }
    }

    /**
     * 检查该字段是否允许添加
     * @param type $field 字段名称
     * @param type $field_type 字段类型
     * @param type $modelid 模型
     * @return boolean
     */
    public function isAddField($field, $field_type, $modelid) {
        //判断是否唯一字段 已经有了就不能添加
        if (in_array($field, $this->unique_fields)) {
            $f_datas = Db::name('ModelField')->where(array("modelid" => $modelid))->column("field,formtype,name");
            return empty($f_datas[$field]) ? true : false;
        }
        //不显示的字段类型（字段类型）
        if (in_array($field_type, $this->not_allow_fields)) {
            return false;
        }
        //禁止被禁用的字段列表（字段名）
        if (in_array($field, $this->forbid_fields)) {
            return false;
        }
        //禁止被删除的字段列表（字段名）
        if (in_array($field, $this->forbid_delete)) {
            return false;
        }
        return true;
    }

    /**
     * 判断字段是否允许删除
     * @param type $field 字段名称
     * @return boolean
     */
    public function isDelField($field) {
        //禁止被删除的字段列表（字段名）
        if (in_array($field, $this->forbid_delete)) {
            return false;
        }
        return true;
    }

     /**
     * 判断字段是否允许被编辑
     * @param type $field 字段名称
     * @return boolean
     */
    public function isEditField($field) {
        //判断是否唯一字段
        if (in_array($field, $this->unique_fields)) {
            return false;
        }
        //禁止被禁用的字段列表（字段名）
        if (in_array($field, $this->forbid_fields)) {
            return false;
        }
        //禁止被删除的字段列表（字段名）
        if (in_array($field, $this->forbid_delete)) {
            return false;
        }
        return true;
    }

    /**
     * 根据模型ID，返回表名
     * @param type $modelid
     * @param type $modelid
     * @return string
     */
    protected function getModelTableName($modelid, $issystem = 1) {
        //读取模型配置 以后优化缓存形式
        $model_cache = cache("Model");
        //表名获取
        $model_table = $model_cache[$modelid]['tablename'];
        //完整表名获取 判断主表 还是副表
        $tablename = $issystem ? $model_table : $model_table . "_data";
        return $tablename;
    }

    /**
     * 根据模型ID读取全部字段信息
     */
    public function getModelField($modelid) {
        return $this->where(array("modelid" => $modelid))->order(array("listorder" => "ASC"))->select();
    }

    /**
     * 获取可用字段类型列表
     * @return array
     */
    public function getFieldTypeList() {
        $fields = include $this->fieldPath . 'fields.inc.php';
        $fields = $fields? : array();
        return $fields;
    }


    /**
     * 根据字段类型，增加对应的字段到相应表里面
     * @param type $field_type 字段类型
     * @param type $field 相关配置
     * $field = array(
     *      'tablename' 表名(完整表名)
     *      'fieldname' 字段名
     *      'maxlength' 最大长度
     *      'minlength' 最小值
     *      'defaultvalue' 默认值
     *      'minnumber' 是否正整数 和整数 1为正整数，-1是为整数
     *      'decimaldigits' 小数位数
     * )
     */
    protected function addFieldSql($field_type, $field){
        //表名
        $tablename = $field['tablename'];
        //字段名
        $fieldname = $field['fieldname'];
        //最大长度
        $maxlength = $field['maxlength'];
        //最小值
        $minlength = $field['minlength'];
        //默认值
        $defaultvalue = isset($field['defaultvalue']) ? $field['defaultvalue'] : '';
        //是否正整数 和整数 1为正整数，-1是为整数
        $minnumber = isset($field['minnumber']) ? $field['minnumber'] : 1;
        //小数位数
        $decimaldigits = isset($field['decimaldigits']) ? $field['decimaldigits'] : '';

        switch ($field_type) {
            case "varchar":
                if (!$maxlength) {
                    $maxlength = 255;
                }
                $maxlength = min($maxlength, 255);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` VARCHAR( {$maxlength} ) NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
             case "tinyint":
                if (!$maxlength) {
                    $maxlength = 3;
                }
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` TINYINT( {$maxlength} ) " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "number"://特殊字段类型，数字类型，如果小数位是0字段类型为 INT,否则是FLOAT
                $minnumber = intval($minnumber);
                $defaultvalue = $decimaldigits == 0 ? intval($defaultvalue) : floatval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` " . ($decimaldigits == 0 ? 'INT' : 'FLOAT') . " " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "smallint":
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` SMALLINT " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "mediumint":
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` INT " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "int":
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` INT " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "mediumtext":
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` MEDIUMTEXT";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "text":
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` TEXT";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "date":
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` DATE";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "datetime":
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "timestamp":
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case 'readpoint'://特殊字段类型
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD  `readpoint` SMALLINT(5) unsigned NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "double":
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` DOUBLE NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "float":
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` FLOAT NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "bigint":
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}` BIGINT NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "longtext":
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}`  LONGTEXT ";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '数据库字段添加失败！';
                    return false;
                }
                break;
            case "char":
                $sql = "ALTER TABLE `{$tablename}` ADD `{$fieldname}`  CHAR(255) NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "pages"://特殊字段类型
                Db::connect()->execute("ALTER TABLE `{$tablename}` ADD `paginationtype` TINYINT( 1 ) NOT NULL DEFAULT '0'");
                Db::connect()->execute("ALTER TABLE `{$tablename}` ADD `maxcharperpage` MEDIUMINT( 6 ) NOT NULL DEFAULT '0'");
                return true;
                break;
            default:
                return false;
                break;
        }
        return true;
    }

    /**
     * 执行数据库表结构更改
     * @param type $field_type 字段类型
     * @param type $field 相关配置
     * $field = array(
     *      'tablename' 表名(完整表名)
     *      'newfilename' 新字段名
     *      'oldfilename' 原字段名
     *      'maxlength' 最大长度
     *      'minlength' 最小值
     *      'defaultvalue' 默认值
     *      'minnumber' 是否正整数 和整数 1为正整数，-1是为整数
     *      'decimaldigits' 小数位数
     * )
     */
    protected function editFieldSql($field_type, $field) {
        //表名
        $tablename = $field['tablename'];
        //原字段名
        $oldfilename = $field['oldfilename'];
        //新字段名
        $newfilename = $field['newfilename'] ? $field['newfilename'] : $oldfilename;
        //最大长度
        $maxlength = $field['maxlength'];
        //最小值
        $minlength = $field['minlength'];
        //默认值
        $defaultvalue = isset($field['defaultvalue']) ? $field['defaultvalue'] : '';
        //是否正整数 和整数 1为正整数，-1是为整数
        $minnumber = isset($field['minnumber']) ? $field['minnumber'] : 1;
        //小数位数
        $decimaldigits = isset($field['decimaldigits']) ? $field['decimaldigits'] : '';

        if (empty($tablename) || empty($newfilename)) {
            $this->error = '表名或者字段名不能为空！';
            return false;
        }

        switch ($field_type) {
            case 'varchar':
                //最大值
                if (!$maxlength) {
                    $maxlength = 255;
                }
                $maxlength = min($maxlength, 255);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` VARCHAR( {$maxlength} ) NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'tinyint':
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` TINYINT " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'number'://特殊字段类型，数字类型，如果小数位是0字段类型为 INT,否则是FLOAT
                $minnumber = intval($minnumber);
                $defaultvalue = $decimaldigits == 0 ? intval($defaultvalue) : floatval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` " . ($decimaldigits == 0 ? 'INT' : 'FLOAT') . " " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'smallint':
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` SMALLINT " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'mediumint':
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` MEDIUMINT " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'int':
                $minnumber = intval($minnumber);
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` INT " . ($minnumber >= 0 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'mediumtext':
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` MEDIUMTEXT";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'text':
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` TEXT";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'date':
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` DATE";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'datetime':
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'timestamp':
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case 'readpoint'://特殊字段类型
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `readpoint` SMALLINT(5) unsigned NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "double":
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` DOUBLE NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "float":
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}` FLOAT NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "bigint":
                $defaultvalue = intval($defaultvalue);
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}`  BIGINT NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "longtext":
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}`  LONGTEXT";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            case "char":
                $sql = "ALTER TABLE `{$tablename}` CHANGE `{$oldfilename}` `{$newfilename}`  CHAR(255) NOT NULL DEFAULT '{$defaultvalue}'";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段结构更改失败！';
                    return false;
                }
                break;
            //特殊自定义字段
            case 'pages':
                break;
            default:
                $this->error = "字段类型" . $field_type . "不存在相应信息！";
                return false;
                break;
        }
        return true;
    }

    /**
     * 根据字段类型，删除对应的字段到相应表里面
     * @param type $filename 字段名称
     * @param type $tablename 完整表名
     */
    protected function deleteFieldSql($filename, $tablename) {
        //不带表前缀的表名
        $noprefixTablename = str_replace(config('database.prefix'), '', $tablename);
        if (empty($tablename) || empty($filename)) {
            $this->error = '表名或者字段名不能为空！';
            return false;
        }

        if (false === $this->table_exists($noprefixTablename)) {
            $this->error = '该表不存在！';
            return false;
        }
        switch ($filename) {
            case 'readpoint'://特殊字段类型
                $sql = "ALTER TABLE `{$tablename}` DROP `readpoint`;";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段删除失败！';
                    return false;
                }
                break;
            //特殊自定义字段
            case 'pages':
                if ($this->field_exists($noprefixTablename, "paginationtype")) {
                    Db::connect()->execute("ALTER TABLE `{$tablename}` DROP `paginationtype`;");
                }
                if ($this->field_exists($noprefixTablename, "maxcharperpage")) {
                    Db::connect()->execute("ALTER TABLE `{$tablename}` DROP `maxcharperpage`;");
                }
                break;
            default:
                $sql = "ALTER TABLE `{$tablename}` DROP `{$filename}`;";
                if (false === Db::connect()->execute($sql)) {
                    $this->error = '字段删除失败！';
                    return false;
                }
                break;
        }
        return true;
    }

    //生成模型字段缓存
    public function model_field_cache() {
        $cache = array();
        $modelList = Db::name("Model")->select();
        foreach ($modelList as $info) {
            $data = Db::name("ModelField")->where(array("modelid" => $info['modelid'], "disabled" => 0))->order(" listorder ASC ")->select();
            $fieldList = array();
            if (!empty($data) && is_array($data)) {
                foreach ($data as $rs) {
                    $fieldList[$rs['field']] = $rs;
                }
            }
            $cache[$info['modelid']] = $fieldList;
        }
        cache('ModelField', $cache);
        return $cache;
    }


}