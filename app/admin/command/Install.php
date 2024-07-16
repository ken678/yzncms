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
// | Original reference: https://gitee.com/karson/fastadmin
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 安装主程序
// +----------------------------------------------------------------------
namespace app\admin\command;

use app\admin\library\Install as InstallLib;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Exception;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Request;
use util\Random;

class Install extends Command
{
    /**
     * @var \think\Request Request 实例
     */
    protected $request;

    protected function configure()
    {
        $config = Config::get('database.connections.mysql');
        $this->setName('install')
            ->addOption('hostname', 'a', Option::VALUE_OPTIONAL, 'mysql hostname', $config['hostname'])
            ->addOption('hostport', 'o', Option::VALUE_OPTIONAL, 'mysql hostport', $config['hostport'])
            ->addOption('database', 'd', Option::VALUE_OPTIONAL, 'mysql database', $config['database'])
            ->addOption('prefix', 'r', Option::VALUE_OPTIONAL, 'table prefix', $config['prefix'])
            ->addOption('username', 'u', Option::VALUE_OPTIONAL, 'mysql username', $config['username'])
            ->addOption('password', 'p', Option::VALUE_OPTIONAL, 'mysql password', $config['password'])
            ->addOption('force', 'f', Option::VALUE_OPTIONAL, 'force override', false)
            ->setDescription('New installation of YznCMS');
    }

    /**
     * 命令行安装
     */
    protected function execute(Input $input, Output $output)
    {
        define('INSTALL_PATH', app()->getBasePath() . 'admin' . DS . 'command' . DS . 'Install' . DS);

        // 覆盖安装
        $force    = $input->getOption('force');
        $hostname = $input->getOption('hostname');
        $hostport = $input->getOption('hostport');
        $database = $input->getOption('database');
        $prefix   = $input->getOption('prefix');
        $username = $input->getOption('username');
        $password = $input->getOption('password');

        $installLockFile = INSTALL_PATH . "install.lock";
        if (is_file($installLockFile) && !$force) {
            $output->error("\nYznCMS already installed!\nIf you need to reinstall again, use the parameter --force=true");
            return false;
        }
        $adminUsername = 'admin';
        $adminPassword = Random::alnum(10);
        $adminEmail    = 'admin@admin.com';
        try {
            $adminName = InstallLib::installation($hostname, $hostport, $database, $username, $password, $prefix, $adminUsername, $adminPassword, $adminEmail);
        } catch (\Exception $e) {
            $output->error($e->getMessage());
            return false;
        }
        if ($adminName) {
            $output->highlight("Admin url:http://www.yoursite.com/{$adminName}");
        }
        $output->highlight("Admin username: {$adminUsername}");
        $output->highlight("Admin password: {$adminPassword}");
        Cache::delete("__menu__");
        $output->info("Install Successed!");
    }
}
