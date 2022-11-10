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
// | 表单前台管理
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\index\model\Formguide as FormguideModel;
use app\member\controller\MemberBase;
use think\Db;
use think\Validate;

class Formguide extends MemberBase
{
    //当前表单ID
    public $formid;
    //表单模型缓存
    protected $tableName;
    //模型信息
    protected $modelInfo = array();
    //配置
    protected $setting     = array();
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = [];

    protected function initialize()
    {
        parent::initialize();
        $this->formid = $this->request->param('id/d', 0);
        //模型
        $this->modelInfo = Db::name('model')->where('id', $this->formid)->where('module', 'formguide')->find();
        if (empty($this->modelInfo)) {
            $this->error('该表单不存在或者已经关闭！');
        }
        if (!empty($this->formid)) {
            $model_cache     = cache("Model");
            $this->tableName = $model_cache[$this->formid]['tablename'];
        }
        //配置
        $this->modelInfo['setting'] = $this->setting = unserialize($this->modelInfo['setting']);
        $this->FormguideModel       = new FormguideModel;
        $this->assign('id', $this->formid);
    }

    //显示表单
    public function index()
    {
        //模板
        $show_template      = $this->setting['show_template'] ? $this->setting['show_template'] : "show";
        $modelid            = $this->request->param('id/d', 0);
        $fieldList          = $this->FormguideModel->getFieldList($modelid);
        $seo                = [];
        $seo['site_title']  = $this->modelInfo['name'] . "_自定义表单";
        $seo['keyword']     = "";
        $seo['description'] = $this->modelInfo['description'];
        $this->assign([
            'SEO'       => $seo,
            'modelInfo' => $this->modelInfo,
            'fieldList' => $fieldList,
        ]);
        return $this->fetch("{$show_template}");
    }

    //表单提交
    public function post()
    {
        $token = $this->request->post('__token__');
        //验证Token
        if (!Validate::make()->check(['__token__' => $token], ['__token__' => 'require|token'])) {
            $this->error('令牌错误！', null, ['__token__' => $this->request->token()]);
        }
        //验证权限
        $this->competence();
        //提交间隔
        if ($this->setting['interval']) {
            $formguide = cookie('formguide_' . $this->formid);
            if ($formguide) {
                $this->error("操作过快，请歇息后再次提交！", null, ['__token__' => $this->request->token()]);
            }
        }
        $data = $this->request->post();
        //开启验证码
        if ($this->setting['isverify']) {
            // 验证码
            if (!captcha_check($data['captcha'])) {
                $this->error('验证码错误或失效', null, ['__token__' => $this->request->token()]);
            }
        }
        try {
            $this->FormguideModel->addFormguideData($this->formid, $data['modelField']);
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }
        if ($this->setting['interval']) {
            cookie('formguide_' . $this->formid, 1, $this->setting['interval']);
        }
        //发送邮件
        if ($this->setting['mails']) {
            //$ems['email'] = explode(",", $this->setting['mails']);
            $ems['email'] = $this->setting['mails'];
            $ems['title'] = "[" . $this->modelInfo['name'] . "]表单消息提醒！";
            $ems['msg']   = "刚刚有人在[" . $this->modelInfo['name'] . "]中提交了新的信息，请进入后台查看！";
            $result       = hook('ems_notice', $ems, true, true);
        }
        //跳转地址
        $forward = $this->setting['forward'] ? ((strpos($this->setting['forward'], '://') !== false) ? $this->setting['forward'] : url($this->setting['forward'])) : '';
        $this->success('提交成功！', $forward);
    }

    //验证提交权限
    protected function competence()
    {
        //是否允许游客提交
        if ((int) $this->setting['allowunreg'] == 0) {
            //判断是否登陆
            if (!$this->auth->isLogin()) {
                $this->error('该表单不允许游客提交，请登陆后操作！', url('member/Index/login'));
            }
        }
        //是否允许同一IP多次提交
        if ((int) $this->setting['allowmultisubmit'] == 0) {
            $ip    = $this->request->ip();
            $count = Db::name($this->tableName)->where("ip", $ip)->count();
            if ($count) {
                $this->error('你已经提交过了！');
            }
        }
    }

}
