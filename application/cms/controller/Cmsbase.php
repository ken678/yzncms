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
// | 前台控制模块
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\member\controller\MemberBase;
use think\facade\Config;

class Cmsbase extends MemberBase
{
    //CMS模型相关配置
    protected $cmsConfig = [];

    //初始化
    protected function initialize()
    {
        parent::initialize();
        Config::set('url_common_param', true);
        $siteurl         = url('cms/index/index', '', true, false);
        $this->cmsConfig = cache("Cms_Config");
        $this->assign("cms_config", $this->cmsConfig);
        $this->assign("siteurl", $siteurl);
        if (!$this->cmsConfig['web_site_status']) {
            $this->error("站点已经关闭，请稍后访问~");
        }
    }
}
