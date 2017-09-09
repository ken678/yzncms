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
namespace app\admin\model;

use think\Db;
use \think\Model;

/**
 * 菜单基础模型
 */
class Menu extends Model
{

     /**
     * 模块安装时进行菜单注册
     * @param array $data 菜单数据
     * @param array $config 模块配置
     * @param type $parentid 父菜单ID
     * @return boolean
     */
    public function installModuleMenu(array $data, array $config, $parentid = 0) {
        if (empty($data) || !is_array($data)) {
            $this->error = '没有数据！';
            return false;
        }
        if (empty($config)) {
            $this->error = '模块配置信息为空！';
            return false;
        }
        //默认安装时父级ID
        $defaultMenuParentid = $this->where(array('app' => 'Admin', 'controller' => 'Module', 'action' => 'local'))->value('id')? : 41;
        //安装模块名称
        $moduleNama = $config['module'];
        foreach ($data as $rs) {
            if (empty($rs['route'])) {
                $this->error = '菜单信息配置有误，route 不能为空！';
                return false;
            }
            $route = $this->menuRoute($rs['route']);
            $pid = $parentid ? : ((is_null($rs['parentid']) || !isset($rs['parentid'])) ? (int) $defaultMenuParentid : $rs['parentid']);
            $newData = array_merge(array(
                'title' => $rs['name'],
                'parentid' => $pid,
                'status' => isset($rs['status']) ? $rs['status'] : 0,
                'tip' => $rs['remark']? : '',
                'listorder' => $rs['listorder']? : 0,
                    ), $route);

            /*if (!$this->create($newData)) {
                $this->error = '菜单信息配置有误，' . $this->error;
                return false;
            }*/

            $result = self::create($newData);
            if (!$result) return false;
            //是否有子菜单
            if (!empty($rs['child'])) {
                if ($this->installModuleMenu($rs['child'], $config, $result['id']) !== true) {
                    return false;
                }
            }
        }
        //清除缓存
        cache('Menu', NULL);
        return true;
    }

    /**
     * 把模块安装时，Menu.php中配置的route进行转换
     * @param type $route route内容
     * @param type $moduleNama 安装模块名称
     * @return array
     */
    private function menuRoute($route, $moduleNama) {
        $route = explode('/', $route, 3);
        if (count($route) < 3) {
            array_unshift($route, $moduleNama);
        }
        $data = array(
            'app' => $route[0],
            'controller' => $route[1],
            'action' => $route[2],
        );
        return $data;
    }

    //获取菜单列表
    public static function getList()
    {
        return Db::name('menu')->order(array('listorder', 'id' => 'DESC'))->select();
    }

    // 获取菜单
    public static function getInfo($map)
    {
        return Db::name('menu')->where($map)->find();
    }

    // 更新数据
    public static function edit($data)
    {
        return Db::name('menu')->update($data);
    }

    // 删除数据
    public static function remove($id)
    {
        return Db::name('menu')->delete($id);
    }

}
