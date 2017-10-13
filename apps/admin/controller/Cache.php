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
namespace app\admin\controller;

use app\common\controller\Adminbase;

class Cache extends Adminbase
{

    /**
     * 缓存更新首页
     */
    public function index()
    {
        if ($this->request->param('type')) {
            $Dir = new \util\Dir();
            $cache = model('common/Cache');
            $type = $this->request->param('type');
            set_time_limit(0);
            //清除站点缓存
            switch ($type) {
                case "site":
                    $stop = $this->request->param('stop', 0);
                    if (empty($stop)) {
                        //已经清除过的目录
                        $dirList = explode(',', $this->request->param('dir', ''));
                        //删除缓存目录下的文件
                        $Dir->del(RUNTIME_PATH);
                        //获取子目录
                        $subdir = glob(RUNTIME_PATH . '*', GLOB_ONLYDIR | GLOB_NOSORT);
                        if (is_array($subdir)) {
                            foreach ($subdir as $path) {
                                $dirName = str_replace(RUNTIME_PATH, '', $path);
                                //忽略目录
                                if (in_array($dirName, array('cache', 'log'))) {
                                    continue;
                                }
                                if (in_array($dirName, $dirList)) {
                                    continue;
                                }
                                $dirList[] = $dirName;
                                //删除目录
                                $Dir->delDir($path);
                                $this->success("清理缓存目录[{$dirName}]成功！", url('cache/index', array('type' => 'site', 'dir' => implode(',', $dirList))), '', 1);
                                exit;
                            }
                        }
                    }
                    if ($stop) {
                        $modules = $cache->getCacheList();
                        //需要更新的缓存信息
                        $cacheInfo = $modules[$stop - 1];
                        if ($cacheInfo) {
                            if ($cache->runUpdate($cacheInfo) !== false) {
                                $this->success('更新缓存：' . $cacheInfo['name'], url('cache/index', array('type' => 'site', 'stop' => $stop + 1)), '', 0);
                                exit;
                            } else {
                                $this->error('缓存[' . $cacheInfo['name'] . ']更新失败！', url('cache/index', array('type' => 'site', 'stop' => $stop + 1)));
                            }
                        } else {
                            $this->success('缓存更新完毕！', url('cache/index'));
                            exit;
                        }
                    }
                    $this->success("即将更新站点缓存！", url('cache/index', array('type' => 'site', 'stop' => 1)));
                    break;
                case "template":
                    //删除缓存目录下的文件
                    $Dir->del(RUNTIME_PATH);
                    $Dir->delDir(RUNTIME_PATH . "cache/");
                    $Dir->delDir(RUNTIME_PATH . "temp/");
                    $this->success("模板缓存清理成功！", url('cache/index'));
                    break;
                case "logs":
                    $Dir->delDir(RUNTIME_PATH . "log/");
                    $this->success("站点日志清理成功！", url('cache/index'));
                    break;
                default:
                    $this->error("请选择清楚缓存类型！");
                    break;

            }
        } else {
            return $this->fetch();
        }

    }

}
