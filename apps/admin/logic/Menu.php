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
namespace app\admin\logic;

use \think\Loader;
use \think\Model;

/**
 * 菜单模型
 */
class Menu extends Model
{
    /**
     * 新增菜单
     */
    public function add($data)
    {
        $validate = Loader::validate('Menu');
        $result = $validate->scene('add')->check($data);
        if (!$result) {
            $this->error = $validate->getError();
            return false;
        }
        return $this->allowField(true)->save($data) !== false ? true : false;
    }

    /**
     * 修改菜单
     */
    public function edit($data)
    {
        $validate = Loader::validate('Menu');
        $result = $validate->scene('edit')->check($data);
        if (!$result) {
            $this->error = $validate->getError();
            return false;
        }
        return $this->allowField(true)->isUpdate(true)->save($data) !== false ? true : false;
    }

}
