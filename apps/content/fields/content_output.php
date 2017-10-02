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
class content_output
{
    //信息ID
    public $id = 0;
    //栏目ID
    public $catid = 0;
    //模型ID
    public $modelid = 0;
    //字段信息
    public $fields = array();
    //模型缓存
    public $model = array();
    //数据
    protected $data = array();
    //最近错误信息
    protected $error = '';
    // 数据表名（不包含表前缀）
    protected $tablename = '';

    public function __construct($modelid)
    {
        $this->model = cache('Model');
        if ($modelid) {
            $this->setModelid($modelid);
        }
    }

    /**
     * 初始化
     * @param type $modelid
     * @return boolean
     */
    public function setModelid($modelid)
    {
        if (empty($modelid)) {
            return false;
        }
        $this->modelid = $modelid;
        if (empty($this->model[$this->modelid])) {
            return false;
        }
        $modelField = cache('ModelField');
        $this->fields = $modelField[$this->modelid];
        $this->tablename = trim($this->model[$this->modelid]['tablename']);
    }

    /**
     * 数据处理
     * @param type $data
     * @return type
     */
    public function get($data)
    {
        $this->data = $data;
        $this->catid = $data['catid'];
        $this->id = $data['id'];
        $info = array();
        foreach ($this->fields as $fieldInfo) {
            $field = $fieldInfo['field'];
            if (!isset($this->data[$field])) {
                continue;
            }
            //字段类型
            $func = $fieldInfo['formtype'];
            //字段内容
            $value = $this->data[$field];
            $result = method_exists($this, $func) ? $this->$func($field, $value) : $value;
            if ($result !== false) {
                $info[$field] = $result;
            }
        }
        return array_merge($this->data, $info);
    }

    ##{字段处理函数}##

}
