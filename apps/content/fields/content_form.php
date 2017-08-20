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
use think\Url;
class content_form {

    //validate表单验证
    public $formValidateRules, $formValidateMessages, $formJavascript;
    //栏目ID
    public $catid = 0;
    //栏目缓存
    public $categorys = array();
    //模型ID
    public $modelid = 0;
    //字段信息
    public $fields = array();
    //模型缓存
    protected $model = array();
    //数据
    protected $data = array();
    //最近错误信息
    protected $error = '';
    // 数据表名（不包含表前缀）
    protected $tablename = '';

    /**
     * 构造函数
     * @param type $modelid 模型ID
     * @param type $catid 栏目id
     */
    public function __construct($modelid, $catid) {
        $this->model = cache("Model");
        if ($modelid) {
            $this->setModelid($modelid, $catid);
        }
    }

    /**
     * 初始化
     * @param type $modelid
     * @return boolean
     */
    public function setModelid($modelid, $catid) {
        if (empty($modelid)) {
            return false;
        }
        $this->modelid = $modelid;
        if (empty($this->model[$this->modelid])) {
            return false;
        }
        $modelField = cache('ModelField');
        $this->catid = $catid;
        $this->fields = $modelField[$this->modelid];
        $this->tablename = trim($this->model[$this->modelid]['tablename']);
    }

    /**
     * 魔术方法，获取配置
     * @param type $name
     * @return type
     */
    public function __get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : (isset($this->$name) ? $this->$name : NULL);
    }

    /**
     *  魔术方法，设置options参数
     * @param type $name
     * @param type $value
     */
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * 获取模型字段信息
     * @param type $data
     * @return type 
     */
    public function get($data = array()) {
        $this->data = $data;
        $info = array();
        foreach ($this->fields as $fieldInfo) {
            $field = $fieldInfo['field'];//字段名
            //判断是否后台
            if (defined('IN_ADMIN') && IN_ADMIN) {
                //判断是否内部字段，如果是，跳过
                if ($fieldInfo['iscore']) {
                    continue;
                }
            } else {
                //判断是否内部字段或者，是否禁止前台投稿字段
                if ($fieldInfo['iscore']) {
                    continue;
                }
                //是否在前台投稿中显示
                if (!$fieldInfo['isadd']) {
                    continue;
                }
            }
            //字段类型
            $func = $fieldInfo['formtype'];
            //判断对应方法是否存在，不存在跳出本次循环
            if (!method_exists($this, $func)) {
                continue;
            }
            $value = isset($this->data[$field]) ? $this->data[$field] : '';
            //如果是分页类型字段
            if ($func == 'pages' && isset($this->data['maxcharperpage'])) {
                $value = $this->data['paginationtype'] . '|' . $this->data['maxcharperpage'];
            }
            //取得表单HTML代码 传入参数 字段名 字段值 字段信息
            $form = $this->$func($field, $value, $fieldInfo);
            if ($form !== false) {
                $star = $fieldInfo['minlength'] || $fieldInfo['pattern'] ? 1 : 0;
                $fieldConfg = array(
                    'name' => $fieldInfo['name'],
                    'tips' => $fieldInfo['tips'],
                    'form' => $form,
                    'star' => $star,
                    'isomnipotent' => $fieldInfo['isomnipotent'],
                    'formtype' => $fieldInfo['formtype']
                );
                //作为基本信息
                if ($fieldInfo['isbase']) {
                    $info['base'][$field] = $fieldConfg;
                } else {
                    $info['senior'][$field] = $fieldConfg;
                }
            }
        }
        return $info;
    }

    /**
     * 转换为validate表单验证相关的json数据
     * @param type $ValidateRules
     */
    public function ValidateRulesJson($ValidateRules, $suang = false) {
        foreach ($ValidateRules as $formname => $value) {
            $va = array();
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    //如果作为消息，消息内容需要加引号，不然会JS报错，是否验证不需要
                    if ($suang) {
                        $va[] = "$k:'$v'";
                    } else {
                        $va[] = "$k:$v";
                    }
                }
            }
            $va = "{" . implode(",", $va) . "}";
            $formValidateRules[] = "'$formname':$va";
        }
        $formValidateRules = "{" . implode(",", $formValidateRules) . "}";
        return $formValidateRules;
    }

    ##{字段处理函数}##
}
