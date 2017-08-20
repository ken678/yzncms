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
namespace app\attachment\controller;
use think\Controller;

class Attachments extends Controller
{

    //上传用户
    public $upname = null;
    //上传用户ID
    public $upuserid = 0;
    //会员组
    public $groupid = 0;
    //是否后台
    public $isadmin = 0;
    //上传模块
    public $module = 'Content';

    //初始化
    protected function _initialize() {
        //检查是否后台登录，后台登录下优先级最高，用于权限判断
        if (\Admin\Service\User::getInstance()->id) {
            define('IN_ADMIN', true);
            $this->isadmin = 1;
            $this->upname = \Admin\Service\User::getInstance()->username;
            $this->upuserid = \Admin\Service\User::getInstance()->id;
        } else {
            $this->upname = service('Passport')->username;
            $this->upuserid = service('Passport')->userid;
            $this->groupid = service('Passport')->groupid ? service('Passport')->groupid : 8;
        }
        parent::_initialize();
    }

    //检查是否有上传权限，json
    public function competence() {
        //上传个数,允许上传的文件类型,是否允许从已上传中选择,图片高度,图片高度,是否添加水印1是
        $args = I('get.args');
        //参数验证码
        $authkey = I('get.authkey');
        //模块
        $module = I('get.module', 'content');
        //验证是否可以上传
        $info = $this->isUpload($module, $args, $authkey);
        if (true !== $info) {
            $status = false;
        } else {
            $status = true;
        }
        // jsonp callback
        $callback = I('get.callback');
        $this->ajaxReturn(array(
            'data' => '',
            'info' => $info,
            'status' => $status,
                ), (isset($_GET['callback']) && $callback ? 'JSONP' : 'JSON'));
    }

    //显示 swfupload 上传界面 通过swf上传成功以后回调处理时会调用swfupload_json方法增加cookies！
    public function swfupload() {
        //网站配置
        $config = cache('Config');
        //上传个数,允许上传的文件类型,是否允许从已上传中选择,图片高度,图片高度,是否添加水印1是
        $args = I('get.args');
        //参数验证码
        $authkey = I('get.authkey');
        //模块
        $module = I('get.module', 'content');
        //栏目id
        $catid = I('get.catid', 0, 'intval');
        //验证是否可以上传
        $status = $this->isUpload($module, $args, $authkey);
        if (true !== $status) {
            $this->error($status);
        }
        //具体配置参数
        $info = explode(",", $args);
        //是否有已上传文件
        $att_not_used = cookie('att_json');
        if (empty($att_not_used)) {
            $tab_status = ' class="on"';
        }
        if (!empty($att_not_used)) {
            $div_status = ' hidden';
        }
        //参数补充完整
        if (empty($info[1])) {
            //如果允许上传的文件类型为空，启用网站配置的 uploadallowext
            if ($this->isadmin) {
                $info[1] = $config['uploadallowext'];
            } else {
                $info[1] = $config['qtuploadallowext'];
            }
        }

        //获取临时未处理的图片
        $att = $this->att_not_used();
        $this->assign("initupload", initupload($this->module, $catid, $info, $this->upuserid, $this->groupid, $this->isadmin));
        //上传格式显示
        $this->assign("file_types", implode(',', explode('|', $info[1])));
        $this->assign("file_size_limit", $this->isadmin ? $config['uploadmaxsize'] : $config['qtuploadmaxsize']);
        $this->assign("file_upload_limit", (int) $info[0]);
        //临时未处理的图片
        $this->assign("att", $att);
        $this->assign("tab_status", $tab_status);
        $this->assign("div_status", $div_status);
        $this->assign("att_not_used", $att_not_used);
        $this->assign('module', $this->module);
        $this->assign('catid', $catid);
        $this->assign('upuserid', $this->upuserid);
        $this->assign('upname', $this->upname);
        $this->assign('groupid', $this->groupid);
        $this->assign('isadmin', $this->isadmin);
        //是否添加水印
        $this->assign("watermark_enable", (int) $info[5]);
        $this->display(T('Attachment@Attachments/swfupload'));
    }

    //设置swfupload上传的json格式cookie 
    public function swfupload_json() {
        $arr = array();
        $arr['aid'] = I('get.aid', 0, 'intval');
        $arr['src'] = I('get.src', '', 'trim');
        $arr['filename'] = urlencode(I('get.filename'));
        return $this->upload_json($arr['aid'], $arr['src'], $arr['filename']);
    }

    //删除swfupload上传的json格式cookie 
    public function swfupload_json_del() {
        $arr['aid'] = I('get.aid', 0, 'intval');
        $arr['src'] = I('get.src', '', '');
        $arr['filename'] = urlencode(I('get.filename', '', ''));
        $json_str = json_encode($arr);
        $att_arr_exist = cookie('att_json');
        cookie('att_json', NULL);
        $att_arr_exist = str_replace(array($json_str, '||||'), array('', '||'), $att_arr_exist);
        $att_arr_exist = preg_replace('/^\|\|||\|\|$/i', '', $att_arr_exist);
        cookie('att_json', $att_arr_exist);
    }

    /**
     * 设置upload上传的json格式cookie 
     * @param type $aid 附件id
     * @param type $src 附件路径
     * @param type $filename 附件名称
     * @return type
     */
    protected function upload_json($aid, $src, $filename) {
        return service("Attachment")->upload_json($aid, $src, $filename);
    }

    /**
     * 检查是否可以上传
     * @param string $module 模块名
     * @param type $args 上传参数
     * @param type $authkey 验证参数
     * @return boolean|string
     */
    protected function isUpload($module, $args, $authkey) {
        $module_list = cache('Module');
        if ($module_list[ucwords($module)] || ucwords($module) == 'Content') {
            $this->module = strtolower($module);
        } else {
            return false;
        }
        //验证参数是否合法
        if (empty($args) || upload_key($args) != $authkey) {
            return false;
        }
        //如果是前台上传，判断用户组权限
        if ($this->isadmin == 0) {
            $member_group = cache('Member_group');
            if ((int) $member_group[$this->groupid]['allowattachment'] < 1) {
                return "所在的用户组没有附件上传权限！";
            }
        }
        return true;
    }

    /**
     * 获取临时未处理的图片
     * @return type
     */
    protected function att_not_used() {
        //获取临时未处理文件列表
        // 水平凡 修复如果cookie里面有加反斜杠，去除
        $att_json = \Input::getVar(cookie('att_json'));
        if ($att_json) {
            if ($att_json) {
                $att_cookie_arr = explode('||', $att_json);
            }
            foreach ($att_cookie_arr as $_att_c)
                $att[] = json_decode($_att_c, true);
            if (is_array($att) && !empty($att)) {
                foreach ($att as $n => $v) {
                    $ext = fileext($v['src']);
                    if (in_array($ext, array('jpg', 'gif', 'png', 'bmp', 'jpeg'))) {
                        $att[$n]['fileimg'] = $v['src'];
                        $att[$n]['width'] = '80';
                        $att[$n]['filename'] = urldecode($v['filename']);
                    } else {
                        $att[$n]['fileimg'] = file_icon($v['src']);
                        $att[$n]['width'] = '64';
                        $att[$n]['filename'] = urldecode($v['filename']);
                    }
                    $this->cookie_att .= '|' . $v['src'];
                }
            }
        }
        return $att;
    }

    /**
     * 用于图片附件上传加水印回调方法
     * @param type $_this
     * @param type $fileInfo
     * @param type $params 
     */
    public static function water($_this, $fileInfo, $params) {
        //网站拍照
        $config = cache('Config');
        //是否开启水印
        if (empty($config['watermarkenable'])) {
            return false;
        }
        //水印文件
        $water = SITE_PATH . $config['watermarkimg'];
        //水印位置
        $waterPos = (int) $config['watermarkpos'];
        //水印透明度
        $alpha = (int) $config['watermarkpct'];
        //jpg图片质量
        $quality = (int) $config['watermarkquality'];

        foreach ($fileInfo as $file) {
            //原图文件
            $source = $file['savepath'] . $file['savename'];
            //图像信息
            $sInfo = \Image::getImageInfo($source);
            //如果图片小于系统设置，不进行水印添加
            if ($sInfo["width"] < (int) $config['watermarkminwidth'] || $sInfo['height'] < (int) $config['watermarkminheight']) {
                continue;
            }
            \Image::water($source, $water, $source, $alpha, $waterPos, $quality);
        }
    }

}
