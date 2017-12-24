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
use think\Db;

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
        if ($this->request->isAjax()) {
            $page = $this->request->param('page/d');
            $limit = $this->request->param('limit/d');
            $num = Db::name('Announce')->where(['passed' => 1])->whereTime('endtime', '>', time())->count('aid');
            $result = Db::name('Announce')->where(['passed' => 1])->whereTime('endtime', '>', time())->field(['aid', 'title', 'addtime'])->page($page, $limit)->select();
            $res = array('code' => 0, 'count' => $num, 'data' => $result);
            exit(json_encode($res));
        } else {
            return $this->fetch();
        }
    }

    public function show()
    {
        return $this->fetch();

    }

}
