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
namespace app\formguide\controller;

use app\common\controller\Adminbase;
use think\Config;
use think\Db;
use think\Loader;

/**
 * 表单管理
 * @author 御宅男  <530765310@qq.com>
 */
class Formguide extends Adminbase
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->db = Loader::model("formguide/Formguide");
        //模块安装后，模板安装在default主题下！
        $this->filepath = TEMPLATE_PATH . "default/formguide/";
    }

    //表单列表
    public function index()
    {
        if ($this->request->isPost()) {

        } else {
            $where = array("type" => 3);
            $data = $this->lists('Model', $where, ["modelid" => "DESC"]);
            $this->assign("data", $data);
            return $this->fetch();
        }
    }

    //添加表单
    public function add()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $info = $data['info'];
            $info['tablename'] = $info['tablename'] ? 'form_' . $info['tablename'] : '';

            $setting = $data['setting'];
            $setting['starttime'] = strtotime($setting['starttime']);
            $setting['endtime'] = strtotime($setting['endtime']);
            $info['setting'] = serialize($setting);
            $info['type'] = $this->db->getModelType(); //类型
            $modelid = $this->db->FormAdd($info);
            if (false === $modelid) {
                $this->error($this->db->getError());
            }
            //创建表
            $statis = $this->db->addModelFormguide($info['tablename'], $modelid);
            if (false === $statis) {
                $this->db->delete(['modelid' => $modelid]);
                $this->error("表创建失败！");
            };
            $this->success("添加表单成功！", url('formguide/index'));
        } else {
            $this->tpl = str_replace($this->filepath, "", glob($this->filepath . 'show*'));
            //$this->tpl = str_replace('.' . Config::get("template.view_suffix"), "", $this->tpl);
            foreach ($this->tpl as $v) {
                $show_template[$v] = $v;
            }
            $this->tpl = str_replace($this->filepath, "", glob($this->filepath . 'js*'));
            //$this->tpl = str_replace(Config::get("template.view_suffix"), "", $this->tpl);
            foreach ($this->tpl as $v) {
                $show_js_template[$v] = $v;
            }
            $this->assign('show_template', $show_template);
            $this->assign("show_js_template", $show_js_template);
            return $this->fetch();
        }

    }

    //表单编辑
    public function edit()
    {
        if ($this->request->isPost()) {
            $modelid = $this->request->param('modelid/d', 0);
            $info = $this->request->param('info/a');
            $setting = $this->request->param('setting/a');
            $setting['starttime'] = strtotime($setting['starttime']);
            $setting['endtime'] = strtotime($setting['endtime']);
            $info['setting'] = serialize($setting);
            unset($info['type'], $info['tablename']);
            /*if ($this->request->param('post._name') == $info['name']) {
            unset($info['name']);
            }*/
            //$data = $this->db->create($info, 2);
            $rs = $this->db->FormSave($info, $modelid);
            if (false === $rs) {
                $this->error($this->db->getError());
            }
            $this->success("更新表单成功！", url("formguide/index"));

            /*if ($this->db->where(array('modelid' => $modelid, 'type' => $this->db->getModelType()))->update($data) !== false) {
        $this->success("更新模型成功！", url("formguide/index"));
        } else {
        $this->error("更新失败！");
        }*/
        } else {
            $formid = $this->request->param('formid/d', 0);
            $r = $this->db->where(array("modelid" => $formid))->find();
            if (!$r) {
                $this->error("该表单不存在！");
            }
            $r['setting'] = unserialize($r['setting']);
            $r['tablename'] = str_replace("form_", "", $r['tablename']);
            $this->assign("data", $r);
            $this->tpl = str_replace($this->filepath, "", glob($this->filepath . 'show*'));
            //$this->tpl = str_replace(array("Show" . DIRECTORY_SEPARATOR, C("TMPL_TEMPLATE_SUFFIX")), "", $this->tpl);
            foreach ($this->tpl as $v) {
                $show_template[$v] = $v;
            }
            $this->tpl = str_replace($this->filepath, "", glob($this->filepath . 'js*'));
            //$this->tpl = str_replace(array("Show" . DIRECTORY_SEPARATOR, C("TMPL_TEMPLATE_SUFFIX")), "", $this->tpl);
            foreach ($this->tpl as $v) {
                $show_js_template[$v] = $v;
            }
            $this->assign('show_template', $show_template);
            $this->assign("show_js_template", $show_js_template);
            return $this->fetch();
        }

    }

    //调用
    public function public_call()
    {
        $formid = $this->request->param('formid/d');
        $this->assign("formid", $formid);
        return $this->fetch("call");
    }

    //删除表单
    public function delete()
    {
        $formid = $this->request->param('formid/d', 0);
        if ($this->db->deleteModel($formid)) {
            $this->success("删除成功！");
        } else {
            $this->error('删除失败！');
        }
    }
}
