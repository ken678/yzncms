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

/**
 * 数据库管理
 */
class Database extends Adminbase {

    protected function _initialize() {
        $this->assign('__GROUP_MENU__', $this->get_group_menu());
    }

    /**
     * 数据库列表
     */
    public function index() {
        $list  = db()->query('SHOW TABLE STATUS');
        $list  = array_map('array_change_key_case', $list);//全部小写
        $this->assign('_list', $list);
        return $this->fetch();
    }

    /**
     * 数据库恢复
     */
    public function restore() {

    }

    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     */
    public function export($tables = null, $id = null, $start = null) {
        if (request()->isPost() && !empty($tables)) {
            $tables = explode(',',trim($tables,','));
            //初始化检测
            $path = config('data_backup_path');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path' => realpath($path) . DIRECTORY_SEPARATOR,
                'part' => config('data_backup_part_size'),
                'compress' => config('data_backup_compress'),
                'level' => config('data_backup_compress_level')
            );
            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if (is_file($lock)) {
                return $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, time());
            }
            //检查备份目录是否可写
            if (!is_writeable($config['path'])) {
                return $this->error('备份目录不存在或不可写，请检查后重试！');
            }
            session('backup_config', $config);
            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', time()),
                'part' => 1
            );
            session('backup_file', $file);
            //缓存要备份的表
            session('backup_tables', $tables);
            //创建备份文件
            $Database = new \com\Database($file, $config);
            if (false !== $Database->create()) {
                $tab = array('id' => 0, 'start' => 0);
                return $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                return $this->error('初始化失败，备份文件创建失败！');
            }
        }elseif (request()->isGet() && is_numeric($id) && is_numeric($start)) {
            //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new \com\Database(session('backup_file'), session('backup_config'));
            $start    = $Database->backup($tables[$id], $start);
            if (false === $start) {
                //出错
                return $this->error('备份出错！');
            } elseif (0 === $start) {
                //下一表
                if (isset($tables[++$id])) {
                    $tab = array('id' => $id, 'start' => 0);
                    return $this->success('备份完成！', '', array('tab' => $tab));
                } else {
                    //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
                    session('backup_tables', null);
                    session('backup_file', null);
                    session('backup_config', null);
                    return $this->success('备份完成！');
                }
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                return $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }
        }else {
            return $this->error('参数错误！');
        }
    }










}