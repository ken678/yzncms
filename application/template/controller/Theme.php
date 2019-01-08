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
// | 模板管理
// +----------------------------------------------------------------------
namespace app\template\controller;

use app\common\controller\Adminbase;
use think\Db;

class Theme extends Adminbase
{
    //主题显示
    public function index()
    {
        $filed = glob(TEMPLATE_PATH . '*');
        $count = 0;
        $arr = [];
        foreach ($filed as $key => $v) {
            if (is_dir($v) == false) {
                continue;
            }
            $arr[$key]['name'] = basename($v);
            if (is_file(TEMPLATE_PATH . $arr[$key]['name'] . '/preview.jpg')) {
                $arr[$key]['preview'] = ROOT_URL . 'templates/' . $arr[$key]['name'] . '/preview.jpg';
            } else {
                $arr[$key]['preview'] = config('public_url') . "static/admin/img/none.png";
            }
            if (config('theme') == $arr[$key]['name']) {
                $arr[$key]['use'] = 1;
            }
            $count++;
        }
        $this->assign('themes', $arr);
        $this->assign('count', $count);
        return $this->fetch();
    }

    //风格选择
    public function chose()
    {
        $theme = $this->request->param('theme/s');
        if (empty($theme)) {
            $this->error("主题名称不能为空！");
        }
        if ($theme == config('theme')) {
            $this->error("主题未改变！", url("Theme/index"));
        }
        $status = Db::name('Config')->where('name', 'theme')->save(['value' => $theme]);
        if ($status !== false) {
            $this->success("更新成功！");
        } else {
            $this->error("更新失败！");
        }
    }
}
