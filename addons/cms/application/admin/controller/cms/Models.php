<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 模型管理
// +----------------------------------------------------------------------
namespace app\admin\controller\cms;

use app\admin\model\cms\Models as ModelsModel;
use app\common\controller\Adminbase;

class Models extends Adminbase
{
    protected $modelClass    = null;
    protected $modelValidate = true;

    protected function initialize()
    {
        parent::initialize();
        $this->modelClass = new ModelsModel;
        //取得当前内容模型模板存放目录
        $this->filepath = TEMPLATE_PATH . (empty(config('site.theme')) ? "default" : config('site.theme')) . DS . "cms" . DS;
        //取得栏目频道模板列表
        $this->tp_category = str_replace($this->filepath . DS, '', glob($this->filepath . DS . 'category*'));
        //取得栏目列表模板列表
        $this->tp_list = str_replace($this->filepath . DS, '', glob($this->filepath . DS . 'list*'));
        //取得内容页模板列表
        $this->tp_show = str_replace($this->filepath . DS, '', glob($this->filepath . DS . 'show*'));
        $this->assign("tp_category", $this->tp_category);
        $this->assign("tp_list", $this->tp_list);
        $this->assign("tp_show", $this->tp_show);
    }

    //模型列表
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = $this->modelClass->where('module', 'cms')->select();
            return json(["code" => 0, "data" => $data]);
        }
        return $this->fetch();
    }
}
