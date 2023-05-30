<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | fastadmin: https://www.fastadmin.net/
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 安装主程序
// +----------------------------------------------------------------------

namespace app\admin\command;

use PDO;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\Db;
use think\Exception;
use think\facade\Config;
use think\facade\Request;
use think\facade\View;
use util\File;
use util\Random;

class Install extends Command
{
    /**
     * @var \think\Request Request 实例
     */
    protected $request;

    protected function configure()
    {
        $config = Config::get('database.');
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
        define('INSTALL_PATH', APP_PATH . 'admin' . DS . 'command' . DS . 'Install' . DS);
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
            $this->installation($hostname, $hostport, $database, $username, $password, $prefix, $adminUsername, $adminPassword, $adminEmail);
        } catch (\Exception $e) {
            $output->error($e->getMessage());
            return false;
        }
        $output->highlight("Admin url: http://www.yoursite.com/admin");
        $output->highlight("Admin username: {$adminUsername}");
        $output->highlight("Admin password: {$adminPassword}");
        $output->info("Install Successed!");
    }

    /**
     * PC端安装
     */
    public function index()
    {
        $this->request = Request::instance();

        define('INSTALL_PATH', APP_PATH . 'admin' . DS . 'command' . DS . 'Install' . DS);

        $installLockFile = INSTALL_PATH . "install.lock";

        if (is_file($installLockFile)) {
            echo '当前已经安装成功，如果需要重新安装，请手动移除install.lock文件';
            exit;
        }
        $output = function ($code, $msg, $url = null, $data = null) {
            return json(['code' => $code, 'msg' => $msg, 'url' => $url, 'data' => $data]);
        };

        if ($this->request->isPost()) {
            $mysqlHostname = $this->request->post('mysqlHostname', '127.0.0.1');
            $mysqlHostport = $this->request->post('mysqlHostport', '3306');
            $hostArr       = explode(':', $mysqlHostname);
            if (count($hostArr) > 1) {
                $mysqlHostname = $hostArr[0];
                $mysqlHostport = $hostArr[1];
            }
            $mysqlUsername             = $this->request->post('mysqlUsername', 'root');
            $mysqlPassword             = $this->request->post('mysqlPassword', '');
            $mysqlDatabase             = $this->request->post('mysqlDatabase', '');
            $mysqlPrefix               = $this->request->post('mysqlPrefix', 'yzn_');
            $adminUsername             = $this->request->post('adminUsername', 'admin');
            $adminPassword             = $this->request->post('adminPassword', '');
            $adminPasswordConfirmation = $this->request->post('adminPasswordConfirmation', '');
            $adminEmail                = $this->request->post('adminEmail', 'admin@admin.com');

            if ($adminPassword !== $adminPasswordConfirmation) {
                return $output(0, '两次输入的密码不一致');
            }
            try {
                $this->installation($mysqlHostname, $mysqlHostport, $mysqlDatabase, $mysqlUsername, $mysqlPassword, $mysqlPrefix, $adminUsername, $adminPassword, $adminEmail);
            } catch (\PDOException $e) {
                throw new Exception($e->getMessage());
            } catch (\Exception $e) {
                return $output(0, $e->getMessage());
            }
            return $output(1, '安装成功！', null);
        }
        $errInfo = '';
        try {
            $this->checkenv();
        } catch (\Exception $e) {
            $errInfo = $e->getMessage();
        }
        return view::fetch(INSTALL_PATH . "install.html", ['errInfo' => $errInfo]);
    }

    /**
     * 执行安装
     */
    protected function installation($mysqlHostname, $mysqlHostport, $mysqlDatabase, $mysqlUsername, $mysqlPassword, $mysqlPrefix, $adminUsername, $adminPassword, $adminEmail = null)
    {
        $this->checkenv();

        if ($mysqlDatabase == '') {
            throw new Exception('请输入正确的数据库名');
        }
        if (!preg_match("/^\w{3,12}$/", $adminUsername)) {
            throw new Exception('用户名只能由3-30位数字、字母、下划线组合');
        }
        if (!preg_match("/^[\S]{6,16}$/", $adminPassword)) {
            throw new Exception('密码长度必须在6-30位之间，不能包含空格');
        }
        $weakPasswordArr = ['123456', '12345678', '123456789', '654321', '111111', '000000', 'password', 'qwerty', 'abc123', '1qaz2wsx'];
        if (in_array($adminPassword, $weakPasswordArr)) {
            throw new Exception('密码太简单，请重新输入');
        }
        $sql = file_get_contents(INSTALL_PATH . 'yzncms.sql');

        $sql = str_replace("`yzn_", "`{$mysqlPrefix}", $sql);

        // 先尝试能否自动创建数据库
        $config = Config::get('database.');
        try {
            $pdo = new PDO("{$config['type']}:host={$mysqlHostname}" . ($mysqlHostport ? ";port={$mysqlHostport}" : ''), $mysqlUsername, $mysqlPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->query("CREATE DATABASE IF NOT EXISTS `{$mysqlDatabase}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            // 连接install命令中指定的数据库
            $instance = Db::connect([
                'type'     => "{$config['type']}",
                'hostname' => "{$mysqlHostname}",
                'hostport' => "{$mysqlHostport}",
                'database' => "{$mysqlDatabase}",
                'username' => "{$mysqlUsername}",
                'password' => "{$mysqlPassword}",
                'prefix'   => "{$mysqlPrefix}",
            ]);

            // 查询一次SQL,判断连接是否正常
            $instance->execute("SELECT 1");

            $db = new PDO("{$config['type']}:dbname={$mysqlDatabase};host={$mysqlHostname}", $mysqlUsername, $mysqlPassword);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
            // 调用原生PDO对象进行批量查询
            $db->exec($sql);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }

        // 数据库配置文件
        $dbConfigFile = ROOT_PATH . 'config' . DS . 'database.php';
        $dbConfigText = @file_get_contents($dbConfigFile);
        $callback     = function ($matches) use ($mysqlHostname, $mysqlHostport, $mysqlUsername, $mysqlPassword, $mysqlDatabase, $mysqlPrefix) {
            $field   = "mysql" . ucfirst($matches[1]);
            $replace = $$field;
            if ($matches[1] == 'hostport' && $mysqlHostport == 3306) {
                $replace = '';
            }
            return "'{$matches[1]}'{$matches[2]}=>{$matches[3]}Env::get('database.{$matches[1]}', '{$replace}'),";
        };
        $dbConfigText = preg_replace_callback("/'(hostname|database|username|password|hostport|prefix)'(\s+)=>(\s+)Env::get\((.*)\)\,/", $callback, $dbConfigText);

        // 检测能否成功写入数据库配置
        $result = @file_put_contents($dbConfigFile, $dbConfigText);
        if (!$result) {
            throw new Exception('当前权限不足，无法写入文件 config/database.php');
        }

        // 设置新的Token随机密钥key
        $oldTokenKey    = config('token.key');
        $newTokenKey    = Random::alnum(32);
        $coreConfigFile = ROOT_PATH . 'config' . DS . 'token.php';
        $coreConfigText = @file_get_contents($coreConfigFile);
        $coreConfigText = preg_replace("/'key'(\s+)=>(\s+)'{$oldTokenKey}'/", "'key'\$1=>\$2'{$newTokenKey}'", $coreConfigText);

        $result = @file_put_contents($coreConfigFile, $coreConfigText);
        if (!$result) {
            throw new Exception('当前权限不足，无法写入文件 config/token.php');
        }

        // 变更默认管理员密码
        $adminPassword = $adminPassword ? $adminPassword : Random::alnum(8);
        $adminEmail    = $adminEmail ? $adminEmail : "admin@admin.com";
        $newSalt       = substr(md5(uniqid(true)), 0, 6);
        $newPassword   = md5(trim($adminPassword) . $newSalt);
        $data          = ['username' => $adminUsername, 'email' => $adminEmail, 'password' => $newPassword, 'encrypt' => $newSalt];
        $instance->name('admin')->where('username', 'admin')->update($data);

        $installLockFile = INSTALL_PATH . "install.lock";
        //检测能否成功写入lock文件
        $result = @file_put_contents($installLockFile, 1);
        if (!$result) {
            throw new Exception('当前权限不足，无法写入文件 application/admin/command/Install/install.lock');
        }

        return true;
    }

    /**
     * 检测环境
     */
    protected function checkenv()
    {
        //数据库配置文件
        $dbConfigFile = ROOT_PATH . 'config' . DS . 'database.php';

        if (version_compare(PHP_VERSION, '7.1.0', '<')) {
            throw new Exception("当前版本" . PHP_VERSION . "过低，请使用PHP7.1以上版本");
        }
        if (!extension_loaded("PDO")) {
            throw new Exception("当前未开启PDO，无法进行安装");
        }
        if (!File::is_really_writable($dbConfigFile)) {
            throw new Exception("当前权限不足，无法写入文件 config/database.php");
        }
        return true;
    }
}
