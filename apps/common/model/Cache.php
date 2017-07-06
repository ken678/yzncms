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
namespace app\common\model;
use \think\Model;
use \think\Loader;
/**
 * 缓存模型
 */
class Cache extends Model
{
    /**
     * 执行更新缓存
     * @param array $config 缓存配置
     * @return boolean
     */
    public function runUpdate(array $config) {
        if (empty($config)) {
            $this->error = '没有可需要更新的缓存信息！';
            return false;
        }
        $mo = '';
        if (empty($config['module'])) {
            $mo = "Common/{$config['model']}";
        } else {
            $mo = "{$config['module']}/{$config['model']}";
        }
        $model = Loader::model($mo);
        if ($config['action']) {
            $action = $config['action'];
            $model->$action();//执行方法
            return true;
        }
        return false;
    }
















}