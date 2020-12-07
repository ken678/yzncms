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
// | 后台常用ajax
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\common\controller\Adminbase;

class Ajax extends Adminbase
{
    //过滤内容的敏感词
    public function filterWord($content)
    {
        $content = $this->request->post('content');
        // 获取感词库文件路径
        $wordFilePath = ROOT_PATH . 'data/words.txt';
        $handle       = \util\SensitiveHelper::init()->setTreeByFile($wordFilePath);
        $word         = $handle->getBadWord($content);
        if ($word) {
            $this->error('内容包含违禁词！', null, $word);
        } else {
            $this->success('内容没有违禁词！');
        }
    }

    /**
     * 生成后缀图标
     */
    public function icon()
    {
        $suffix = $this->request->request("suffix");
        header('Content-type: image/svg+xml');
        $suffix = $suffix ? $suffix : "FILE";
        echo build_suffix_image($suffix);
        exit;
    }
}
