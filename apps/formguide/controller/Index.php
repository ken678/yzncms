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

use app\common\controller\Homebase;
use think\Db;
use think\Loader;

/**
 * 表单管理
 * @author 御宅男  <530765310@qq.com>
 */
class Index extends Homebase
{
    //表单模型缓存
    protected $Model_form;
    //数据模型
    protected $db = null, $formguide;
    //当前表单ID
    public $formid;
    //配置
    protected $setting = array();
    //模型信息
    protected $modelInfo = array();
    //输出类型
    protected $showType = null;

    //初始化
    protected function _initialize()
    {
        parent::_initialize();
        $this->showType = $this->request->param('action');
        $this->formguide = Loader::model("formguide/Formguide");
        $this->Model_form = cache("Model_form");
        $this->formid = $this->request->param('formid/d', 0);
        if (!empty($this->formid)) {
            $this->db = Db::name(get_table_name($this->formid));
        }
        //模型
        $this->modelInfo = $this->Model_form[$this->formid];
        if (empty($this->modelInfo)) {
            if ($this->showType == "js") {
                exit($this->format_js('该表单不存在或者已经关闭！'));
            }
            $this->error('该表单不存在或者已经关闭！');
        }
        //配置
        $this->modelInfo['setting'] = $this->setting = unserialize($this->modelInfo['setting']);
        $this->assign('formid', $this->formid);
    }

    //显示表单
    public function index()
    {
        if (empty($this->formid)) {
            if ($this->showType == "js") {
                exit($this->format_js('该表单不存在或者已经关闭！'));
            }
            $this->error('该表单不存在或者已经关闭！');
        }
        $r = $this->formguide->where(array("modelid" => $this->formid))->find();
        if (empty($r)) {
            if ($this->showType == "js") {
                exit($this->format_js('该表单不存在或者已经关闭！'));
            }
            $this->error('该表单不存在或者已经关闭！');
        }
        //模板
        $show_template = $this->setting['show_template'] ? $this->setting['show_template'] : "show";
        //js模板
        $show_js_template = $this->setting['show_js_template'] ? $this->setting['show_js_template'] : "js";
        //实例化表单类 传入 模型ID 栏目ID 栏目数组
        $content_form = new \content_form($this->formid);
        //生成对应字段的输入表单
        $forminfos = $content_form->get();
        $forminfos = $forminfos['senior'];
        $this->assign($this->modelInfo);
        $this->assign("forminfos", $forminfos);
        if ($this->showType == 'js') {
            $html = $this->fetch(TEMPLATE_PATH . 'default/formguide/' . $show_js_template);
            //输出js
            exit($this->format_js($html));
        }
        return $this->fetch(TEMPLATE_PATH . 'default/formguide/' . $show_template);
    }

    //表单提交
    public function post()
    {
        //提交间隔
        if ($this->setting['interval']) {
            $formguide = cookie('formguide_' . $this->formid);
            if ($formguide) {
                $this->error("操作过快，请歇息后再次提交！");
            }
        }
        //开启验证码
        if ($this->setting['isverify']) {
            $verify = $this->request->param('verify');
            if (empty($verify)) {
                $this->error('请输入验证码！');
            }

            if (!captcha_check($verify)) {
                $this->error('验证码输入错误！');
                return false;
            }
        }
        $info = $this->request->param('info/a');
        $content_input = new \content_input($this->formid);
        $inputinfo = $content_input->get($info);
        dump($inputinfo);
        exit();
    }

    /**
     * 将文本格式成适合js输出的字符串
     * @param string $string 需要处理的字符串
     * @param intval $isjs 是否执行字符串格式化，默认为执行
     * @return string 处理后的字符串
     */
    protected function format_js($str, $isjs = 1)
    {
        preg_match_all("/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e", $str, $r);
        //匹配utf-8字符，
        $str = $r[0];
        $l = count($str);
        for ($i = 0; $i < $l; $i++) {
            $value = ord($str[$i][0]);
            if ($value < 223) {
                $str[$i] = rawurlencode(utf8_decode($str[$i]));
                //先将utf8编码转换为ISO-8859-1编码的单字节字符，urlencode单字节字符.
                //utf8_decode()的作用相当于iconv("UTF-8","CP1252",$v)。
            } else {
                $str[$i] = "%u" . strtoupper(bin2hex(iconv("UTF-8", "UCS-2", $str[$i])));
            }
        }
        $reString = join("", $str);
        //$string = addslashes(str_replace(array("\r", "\n", "\t"), array('', '', ''), $string));
        return $isjs ? 'document.write(unescape("' . $reString . '"));' : $reString;
    }

    //验证提交权限
    protected function competence()
    {

    }

}
