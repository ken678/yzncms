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
use \think\Cache;

class Cache_factory {

    /**
     * 获取缓存
     * @param type $name 缓存名称
     * @return null
     */
    public function get($name) {
        $cache = Cache::get($name);
        if (!empty($cache)) {
            return $cache;
        } else {
            //尝试生成缓存
            return $this->runUpdate($name);
        }
        return null;
    }

    /**
     * 写入缓存
     * @param string $name 缓存变量名
     * @param type $value 存储数据
     * @param type $expire 有效时间（秒）
     * @return boolean
     */
    public function set($name, $value, $expire = null) {
        return Cache::set($name, $value, $expire);
    }

    /**
     * 删除缓存
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function remove($name) {
        return Cache::rm($name, NULL);
    }

    /**
     * 更新缓存
     * @param type $name 缓存key
     * @return boolean
     */
    public function runUpdate($name) {
        if (empty($name)) {
            return false;
        }
        $cacheModel = model('Common/Cache');
        //查询缓存key
        $cacheList = db('cache')->where(array('key' => $name))->order(array('id' => 'DESC'))->select();
        if (empty($cacheList)) {
            return false;
        }
        foreach ($cacheList as $cache) {
            $cacheModel->runUpdate($cache);
        }
        //再次加载
        return Cache::get($name);
    }

}
