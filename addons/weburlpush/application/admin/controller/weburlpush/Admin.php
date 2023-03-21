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
// | 聚合推送管理
// +----------------------------------------------------------------------
namespace app\admin\controller\weburlpush;

use addons\weburlpush\library\Push;
use app\common\controller\Adminbase;

class Admin extends Adminbase
{
    public function index()
    {
        $config           = get_addon_config('weburlpush');
        $config['status'] = explode(',', $config['status']);
        $this->assign('Config', $config);
        return $this->fetch();
    }

    //百度推送
    public function baidu(Push $push)
    {
        $action = $this->request->post("action");
        $urls   = $this->request->post("urls");

        $urls = explode("\n", $urls);
        $urls = array_unique(array_filter($urls));
        if (!$urls) {
            $this->error("URL列表不能为空");
        }
        try {
            if ($action == 'push') {
                $result = $push->channel('baidu')->push($urls);
            } elseif ($action == 'del') {
                $result = $push->channel('baidu')->del($urls);
            }
        } catch (\Exception $e) {
            $this->error("百度推送失败：" . $e->getMessage());
        }
        $this->success("百度推送成功", '', $result);
    }

    //神马推送
    public function shenma(Push $push)
    {
        $action = $this->request->post("action");
        $urls   = $this->request->post("urls");

        $urls = explode("\n", $urls);
        $urls = array_unique(array_filter($urls));
        if (!$urls) {
            $this->error("URL列表不能为空");
        }
        try {
            if ($action == 'push') {
                $result = $push->channel('shenma')->push($urls);
            } elseif ($action == 'del') {
                $result = $push->channel('shenma')->del($urls);
            }
        } catch (\Exception $e) {
            $this->error("神马推送失败：" . $e->getMessage());
        }
        $this->success("神马推送成功", '', $result);
    }
}
