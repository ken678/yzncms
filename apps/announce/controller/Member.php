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
namespace app\announce\controller;

use app\member\Controller\Memberbase;
use think\Config;

/**
 * 会员系统公告管理
 * @author 御宅男  <530765310@qq.com>
 */
class Member extends Memberbase
{
    public function __construct()
    {
        //会员模板
        $config['template'] = Config::get('template');
        $Theme = empty(self::$Cache["Config"]['theme']) ? 'default' : self::$Cache["Config"]['theme'];
        $config['template']['view_base'] = TEMPLATE_PATH . $Theme . '/';
        Config::set($config);
        parent::__construct();
    }

    public function index()
    {
        return $this->fetch(TEMPLATE_PATH . 'default/announce/member/index.html');

    }

    public function show()
    {
        return $this->fetch();

    }

}
