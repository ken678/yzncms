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
namespace app\api\controller;

use app\common\controller\Base;

/**
 * 验证码处理
 */
class Checkcode extends Base
{
    /**
     * 获取验证码
     */
    public function getVerify()
    {
        $captcha = [];
        //设置长度
        $codelen = $this->request->param('length', 4);
        if ($codelen) {
            if ($codelen > 8 || $codelen < 2) {
                $codelen = 4;
            }
            $captcha['length'] = $codelen;
        }
        //设置验证码字体大小
        $fontsize = $this->request->param('font_size', 15);
        if ($fontsize) {
            $captcha['fontSize'] = $fontsize;
        }
        //设置验证码图片宽度
        $width = $this->request->param('imageW', 40);
        if ($width) {
            $captcha['imageW'] = $width;
        }
        //设置验证码图片高度
        $height = $this->request->param('imageH', 110);
        if ($height) {
            $captcha['imageH'] = $height;
        }
        $captcha = new \think\captcha\Captcha($captcha);
        return $captcha->entry();
    }

}
