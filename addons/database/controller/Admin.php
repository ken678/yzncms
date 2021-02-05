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
        $config = get_addon_config('database');
        if (empty($config)) {
            $this->error('请先进行相关配置！');
        }
        $this->databaseConfig = array(
            //数据库备份根路径（路径必须以 / 结尾）
            'path'     => ROOT_PATH . $config['path'],
            //数据库备份卷大小 （该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M）
            'part'     => (int) $config['part'],
            //数据库备份文件是否启用压缩 （压缩备份文件需要PHP环境支持gzopen,gzwrite函数）
            'compress' => (int) $config['compress'],
            //数据库备份文件压缩级别 （数据库备份文件的压缩级别，该配置在开启压缩时生效） 1普通 4一般 9最高
            'level'    => (int) $config['level'],
        );
    }

    //数据库备份
    public function index()
    {
        if ($this->request->isAjax()) {
            $list   = Db::query('SHOW TABLE STATUS');
            $list   = array_map('array_change_key_case', $list); //全部小写
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
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, time());
            }
            //检查备份目录是否可写
            if (!is_writeable($config['path'])) {
                $this->error('备份目录不存在或不可写，请检查后重试！');
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
                $this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
            } else {
                $this->error('初始化失败，备份文件创建失败！');
            }
        } elseif ($this->request->isGet() && is_numeric($id) && is_numeric($start)) {
            //备份数据
            $tables = session('backup_tables');
            //备份指定表
            $Database = new Database(session('backup_file'), session('backup_config'));
            $start    = $Database->backup($tables[$id], $start);
            if (false === $start) {
                //出错
                $this->error('备份出错！');
            } elseif (0 === $start) {
                //下一表
                if (isset($tables[++$id])) {
                    $tab = array('id' => $id, 'start' => 0);
                    $this->success('备份完成！', '', array('tab' => $tab));
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
                $this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
            }

        } else {
            $this->error('参数错误！');
        }

    }

    //备份恢复
    public function restore()
    {
        if ($this->request->isAjax()) {
            //列出备份文件列表
            $path = $this->databaseConfig['path'];
            $glob = glob($path . '*.gz', GLOB_BRACE);
            $list = array();
            foreach ($glob as $key => $file) {
                $fileInfo = pathinfo($file);
                //文件名
                $name = $fileInfo['basename'];
                if (preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql(?:\.gz)?$/', $name)) {
                    $name = sscanf($name, '%4s%2s%2s-%2s%2s%2s-%d');

                    $date = "{$name[0]}-{$name[1]}-{$name[2]}";
                    $time = "{$name[3]}:{$name[4]}:{$name[5]}";
                    $part = $name[6];

                    if (isset($list["{$date} {$time}"])) {
                        $info         = $list["{$date} {$time}"];
                        $info['part'] = max($info['part'], $part);
                        $info['size'] = $info['size'] + filesize($file);
                    } else {
                        $info['part'] = $part;
                        $info['size'] = filesize($file);
                    }

                    $extension        = strtoupper($fileInfo['extension']);
                    $info['compress'] = ($extension === 'SQL') ? '-' : $extension;
                    $info['date']     = date('Y-m-d H:i:s', strtotime("{$date} {$time}"));
                    $info['time']     = strtotime("{$date} {$time}");
                    $info['title']    = date('Ymd-His', strtotime("{$date} {$time}"));
                    $list[$key]       = $info;
                }
            }
            $result = array("code" => 0, "data" => $list);
            return json($result);
        } else {
            return $this->fetch('import');
        }

    }

    //下载
    public function download()
    {
        $time = $id = $this->request->param('time/d');
        if ($time) {
            //备份数据库文件名
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = $this->databaseConfig['path'] . $name;
            $path = glob($path);
            if (empty($path)) {
                $this->error('下载文件不存在！');
            }
            $file      = $path[0];
            $file_part = pathinfo($file);

            $basename = $file_part['basename'];
            $download = new \think\response\Download($file);
            return $download->name($basename);
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 还原数据库
     */
    public function import()
    {
        //时间
        $time = $this->request->param('time', 0, 'intval');
        $part = $this->request->param('part', null);
        //起始行数
        $start = $this->request->param('start', null);
        if (is_numeric($time) && is_null($part) && is_null($start)) {
            //获取备份文件信息
            $name  = date('Ymd-His', $time) . '-*.sql*';
            $path  = $this->databaseConfig['path'] . $name;
            $files = glob($path);
            $list  = array();
            foreach ($files as $name) {
                $basename        = basename($name);
                $match           = sscanf($basename, '%4s%2s%2s-%2s%2s%2s-%d');
                $gz              = preg_match('/^\d{8,8}-\d{6,6}-\d+\.sql.gz$/', $basename);
                $list[$match[6]] = array($match[6], $name, $gz);
            }
            ksort($list);
            //检测文件正确性
            $last = end($list);
            if (count($list) === $last[0]) {
                session('backup_list', $list); //缓存备份列表
                $this->success('初始化完成！', '', array('part' => 1, 'start' => 0));
            } else {
                $this->error('备份文件可能已经损坏，请检查！');
            }
        } elseif (is_numeric($part) && is_numeric($start)) {
            $list  = session('backup_list');
            $db    = new Database($list[$part], array('path' => realpath(config('data_backup_path')) . DIRECTORY_SEPARATOR, 'compress' => $list[$part][2]));
            $start = $db->import($start);
            if (false === $start) {
                $this->error('还原数据出错！');
            } elseif (0 === $start) {
                //下一卷
                if (isset($list[++$part])) {
                    $data = array('part' => $part, 'start' => 0);
                    $this->success("正在还原...#{$part}", '', $data);
                } else {
                    session('backup_list', null);
                    $this->success('还原完成！');
                }
            } else {
                $data = array('part' => $part, 'start' => $start[0]);
                if ($start[1]) {
                    $rate = floor(100 * ($start[0] / $start[1]));
                    $this->success("正在还原...#{$part} ({$rate}%)", '', $data);
                } else {
                    $data['gz'] = 1;
                    $this->success("正在还原...#{$part}", '', $data);
                }
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /**
     * 删除备份文件
     * @param  Integer $time 备份时间
     */
    public function del()
    {
        $time = $id = $this->request->param('time/d');
        if ($time) {
            $name = date('Ymd-His', $time) . '-*.sql*';
            $path = $this->databaseConfig['path'] . $name;
            array_map("unlink", glob($path));
            if (count(glob($path))) {
                $this->error('备份文件删除失败，请检查权限！');
            } else {
                $this->success('备份文件删除成功！');
            }
        } else {
            $this->error('参数错误！');
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
                $list   = Db::query("OPTIMIZE TABLE `{$tables}`");
                if ($list) {
                    $this->success("数据表优化完成！");
                } else {
                    $this->error("数据表优化出错请重试！");
                }
            }
        } else {
            $this->error("请指定要优化的表！");
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
                $list   = Db::query("REPAIR TABLE `{$tables}`");
                if ($list) {
                    $this->success("数据表修复完成！");
                } else {
                    $this->error("数据表修复出错请重试！");
                }
            }
        } else {
            $this->error("请指定要修复的表！");
        }
    }

}
