<?php
error_reporting(E_ERROR | E_PARSE );
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

class content_input
{
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
    //处理后的数据
    protected $infoData = array();
    //内容模型对象
    protected $ContentModel = NULL;
    //最近错误信息
    protected $error = '';
    // 数据表名（不包含表前缀）
    protected $tablename = '';

    /**
     * 构造函数
     * @param type $modelid 模型ID
     * @param type $Action 传入this
     */
    public function __construct($modelid)
    {
        $this->model = cache("Model");
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
     * 数据入库前处理
     * @param type $data
     * @param type $type 状态1插入数据，2更新数据，3包含以上两种
     * @return boolean|string
     */
    public function get($data, $type = 3)
    {
        //数据
        $this->data = $data;
        $info = array();
        foreach($data as $field=>$value) {
            //主表附表数据分离
            if($this->fields[$field]['issystem']) {
                $info['system'][$field] = $value;
            } else {
                $info['model'][$field] = $value;
            }
        }
        return $info;
    }


    ##{字段处理函数}##

























}