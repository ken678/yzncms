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
// | 表单后台管理
// +----------------------------------------------------------------------
namespace app\admin\controller\formguide;

use app\admin\model\formguide\Models;
use app\common\controller\Adminbase;

class Formguide extends Adminbase
{

    //模板存放目录
    protected $filepath, $tpl;
    protected $modelValidate = true;
    //是否开启模型场景验证
    protected $modelSceneValidate = true;

    protected function initialize()
    {
        parent::initialize();
        //模块安装后，模板安装在Default主题下！
        $this->filepath   = TEMPLATE_PATH . (empty(config('site.theme')) ? "default" : config('site.theme')) . DS . "index" . DS . "formguide" . DS;
        $this->modelClass = new Models;
    }

    //首页
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = $this->modelClass->where(['module' => 'formguide'])->select();
            return json(["code" => 0, "data" => $data]);
        } else {
            return $this->fetch();
        }
    }

    //添加表单
    public function add()
    {
        $show_template = [];
        $this->tpl     = str_replace($this->filepath, "", glob($this->filepath . 'show*'));
        $this->tpl     = str_replace("." . config("template.view_suffix"), "", $this->tpl);
        foreach ($this->tpl as $v) {
            $show_template[$v] = $v;
        }
        $this->assign('show_template', $show_template);
        return parent::add();
    }

    //编辑表单
    public function edit()
    {
        $show_template = [];
        $this->tpl     = str_replace($this->filepath, "", glob($this->filepath . 'show*'));
        $this->tpl     = str_replace("." . config("template.view_suffix"), "", $this->tpl);
        foreach ($this->tpl as $v) {
            $show_template[$v] = $v;
        }
        $this->assign('show_template', $show_template);
        return parent::edit();
    }
}
