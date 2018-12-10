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
// | 数据库管理
// +----------------------------------------------------------------------
namespace addons\database\Controller;

use addons\database\lib\Database;
use app\addons\util\Adminaddon;
use think\Db;

class Admin extends Adminaddon
{
    //配置
    protected $databaseConfig = array();
    protected function initialize()
    {
        parent::initialize();
        //获取插件配置
        $config = $this->getAddonConfig();
        if (empty($config)) {
            $this->error('请先进行相关配置！');
        }
        $this->databaseConfig = array(
            //数据库备份根路径（路径必须以 / 结尾）
            'path' => ROOT_PATH . $config['path'],
            //数据库备份卷大小 （该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M）
            'part' => (int) $config['part'],
            //数据库备份文件是否启用压缩 （压缩备份文件需要PHP环境支持gzopen,gzwrite函数）
            'compress' => (int) $config['compress'],
            //数据库备份文件压缩级别 （数据库备份文件的压缩级别，该配置在开启压缩时生效） 1普通 4一般 9最高
            'level' => (int) $config['level'],
        );
    }

    //数据库备份
    public function index()
    {
        if ($this->request->isAjax()) {
            $list = db()->query('SHOW TABLE STATUS');
            $list = array_map('array_change_key_case', $list); //全部小写
            $result = array("code" => 0, "data" => $list);
            return json($result);
        }
        return $this->fetch();
    }

    /**
     * 备份数据库
     */
    public function export()
    {
        //表名
        $tables = $this->request->param('tables/a');
        //表ID
        $id = $this->request->param('id/d');
        //起始行数
        $start = $this->request->param('start/d');
        if ($this->request->isPost() && !empty($tables) && is_array($tables)) {
            //读取备份配置
            $config = $this->databaseConfig;
            if (!is_dir($config['path'])) {
                mkdir($config['path'], 0755, true);
            }
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
                'part' => 1,
            );
            session('backup_file', $file);
            //缓存要备份的表
            session('backup_tables', $tables);
            //创建备份文件
            $Database = new Database($file, $config);
            if (false !== $Database->create()) {
                $tab = array('id' => 0, 'start' => 0);
                return $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                return $this->error('初始化失败，备份文件创建失败！');
            }
        } elseif ($this->request->isGet() && is_numeric($id) && is_numeric($start)) {
            //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new Database(session('backup_file'), session('backup_config'));
            $start = $Database->backup($tables[$id], $start);
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
                $tab = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
                return $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }

        } else {
            return $this->error('参数错误！');
        }

    }

    /**
     * 优化表
     * @param  String $tables 表名
     */
    public function optimize()
    {
        //表名
        $tables = $this->request->param('tables/a');
        if ($tables) {
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = Db::query("OPTIMIZE TABLE `{$tables}`");
                if ($list) {
                    return $this->success("数据表优化完成！");
                } else {
                    return $this->error("数据表优化出错请重试！");
                }
            }
        } else {
            return $this->error("请指定要优化的表！");
        }
    }

    /**
     * 修复表
     * @param  String $tables 表名
     */
    public function repair()
    {
        //表名
        $tables = $this->request->param('tables/a');
        if ($tables) {
            if (is_array($tables)) {
                $tables = implode('`,`', $tables);
                $list = Db::query("REPAIR TABLE `{$tables}`");
                if ($list) {
                    return $this->success("数据表修复完成！");
                } else {
                    return $this->error("数据表修复出错请重试！");
                }
            }
        } else {
            return $this->error("请指定要修复的表！");
        }
    }

}
