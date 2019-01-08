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
// | 推荐位管理
// +----------------------------------------------------------------------
namespace app\cms\controller;

use app\common\controller\Adminbase;
use think\Db;

class Position extends Adminbase
{
    /**
     * 首页
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            $data = Db::name('Position')->order(array('listorder' => 'ASC', 'id' => 'DESC'))
                ->withAttr('modelid', function ($value, $data) {
                    if ($data['modelid']) {
                        return getModel($data['modelid'], 'name');
                    } else {
                        return '不限模型';
                    }
                })->withAttr('catid', function ($value, $data) {
                if ($data['catid']) {
                    return getCategory($data['catid'], 'name');
                } else {
                    return '不限栏目';
                }
            })->withAttr('create_time', function ($value, $data) {
                return date('Y-m-d H:i:s', $value);
            })->select();
            $result = array("code" => 0, "data" => $data);
            return json($result);
        }
        return $this->fetch();
    }

}
