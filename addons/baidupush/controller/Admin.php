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
// | 百度推送管理
// +----------------------------------------------------------------------
namespace addons\baidupush\Controller;

use addons\baidupush\library\Push;
use app\addons\util\Adminaddon;

class Admin extends Adminaddon
{
    public function index()
    {
        $config = get_addon_config('baidupush');
        $this->assign('Config', $config);
        return $this->fetch();
    }

    /**
     * 熊掌号推送
     */
    public function xiongzhang()
    {
        $action = $this->request->post("action");
        $urls = $this->request->post("urls");
        $urls = explode("\n", $urls);
        $urls = array_unique(array_filter($urls));
        if (!$urls) {
            $this->error("URL列表不能为空");
        }
        $result = false;
        if ($action == 'urls') {
            $type = $this->request->post("type");
            if ($type == 'realtime') {
                $result = Push::init(['type' => 'xiongzhang'])->realtime($urls);
            } else {
                $result = Push::init(['type' => 'xiongzhang'])->history($urls);
            }
        } elseif ($action == 'del') {
            $result = Push::init(['type' => 'xiongzhang'])->delete($urls);
        }

        if ($result) {
            $data = Push::init()->getData();
            $this->success("推送成功", null, $data);
        } else {
            $this->error("推送失败：" . Push::init()->getError());
        }
    }

    /**
     * 百度站长推送
     */
    public function zhanzhang()
    {
        $action = $this->request->post("action");
        $urls = $this->request->post("urls");
        $urls = explode("\n", $urls);
        $urls = array_unique(array_filter($urls));
        if (!$urls) {
            $this->error("URL列表不能为空");
        }
        $result = false;
        if ($action == 'urls') {
            $result = Push::init(['type' => 'zhanzhang'])->realtime($urls);
        } elseif ($action == 'del') {
            $result = Push::init(['type' => 'zhanzhang'])->delete($urls);
        }
        if ($result) {
            $data = Push::init()->getData();
            $this->success("推送成功", null, $data);
        } else {
            $this->error("推送失败：" . Push::init()->getError());
        }
    }

}
