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
// | 后台欢迎页
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\model\Attachment;
use think\facade\Db;
use util\Date;

class Main extends Backend
{
    protected $noNeedRight = ['index'];
    //欢迎首页
    public function index()
    {
        if ($this->auth->password == encrypt_password('admin', $this->auth->encrypt)) {
            $this->assign('default_pass', 1);
        }
        $column    = [];
        $starttime = Date::unixtime('day', -6);
        $endtime   = Date::unixtime('day', 0, 'end');
        $joinlist  = Db::name("user")
            ->whereBetween('reg_time', [$starttime, $endtime])
            ->field('COUNT(*) AS nums, DATE(FROM_UNIXTIME(reg_time)) AS join_date')
            ->group('join_date')
            ->select();
        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("Y-m-d", $time);
            $time += 86400;
        }
        $userlist = array_fill_keys($column, 0);
        foreach ($joinlist as $k => $v) {
            $userlist[$v['join_date']] = $v['nums'];
        }

        $addonList         = get_addon_list();
        $totalworkingaddon = 0;
        $totaladdon        = count($addonList);
        foreach ($addonList as $index => $item) {
            if ($item['status']) {
                $totalworkingaddon += 1;
            }
        }

        $dbTableList = Db::query("SHOW TABLE STATUS");

        $this->assign([
            'sys_info'          => $this->get_sys_info(),
            'totaladdon'        => $totaladdon,
            'totalworkingaddon' => $totalworkingaddon,
            'dbtablenums'       => count($dbTableList),
            'dbsize'            => array_sum(array_map(function ($item) {
                return $item['Data_length'] + $item['Index_length'];
            }, $dbTableList)),
            'attachmentnums'    => Attachment::count(),
            'attachmentsize'    => Attachment::sum('size'),
            'picturenums'       => Attachment::where('mime', 'like', 'image/%')->count(),
            'picturesize'       => Attachment::where('mime', 'like', 'image/%')->sum('size'),
        ]);

        $this->assignconfig('column', array_keys($userlist));
        $this->assignconfig('userdata', array_values($userlist));
        $this->assignconfig('username', $this->auth->username);
        return $this->fetch();
    }

    //phpinfo信息 按需显示在前台
    protected function get_sys_info()
    {
        //$sys_info['os'] = PHP_OS; //操作系统
        $sys_info['ip']           = GetHostByName($_SERVER['SERVER_NAME']); //服务器IP
        $sys_info['web_server']   = $_SERVER['SERVER_SOFTWARE']; //服务器环境
        $sys_info['phpv']         = phpversion(); //php版本
        $sys_info['fileupload']   = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknown'; //文件上传限制
        $sys_info['memory_limit'] = ini_get('memory_limit'); //最大占用内存
        //$sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false; //最大执行时间
        //$sys_info['zlib'] = function_exists('gzclose') ? 'YES' : 'NO'; //Zlib支持
        //$sys_info['safe_mode'] = (boolean) ini_get('safe_mode') ? 'YES' : 'NO'; //安全模式
        //$sys_info['timezone'] = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
        //$sys_info['curl'] = function_exists('curl_init') ? 'YES' : 'NO'; //Curl支持
        //$sys_info['max_ex_time'] = @ini_get("max_execution_time") . 's';
        $sys_info['domain']          = $_SERVER['HTTP_HOST']; //域名
        $sys_info['remaining_space'] = function_exists('disk_free_space') ? round((disk_free_space(".") / (1024 * 1024)), 2) . 'M' : '未知'; //剩余空间
        //$sys_info['user_ip'] = $_SERVER['REMOTE_ADDR']; //用户IP地址
        //$sys_info['web_directory'] = $_SERVER["DOCUMENT_ROOT"]; //网站目录
        $mysqlinfo                 = Db::query("SELECT VERSION() as version");
        $sys_info['mysql_version'] = $mysqlinfo[0]['version'];
        if (function_exists("gd_info")) {
            //GD库版本
            $gd                 = gd_info();
            $sys_info['gdinfo'] = $gd['GD Version'];
        } else {
            $sys_info['gdinfo'] = "未知";
        }
        return $sys_info;
    }

}
