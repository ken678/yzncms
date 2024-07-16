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
// |  安装服务
// +----------------------------------------------------------------------
namespace app\admin\library;

use PDO;
use think\Exception;
use think\facade\Config;
use think\facade\Db;
use util\File;
use util\Random;

class Install
{
    /**
     * 执行安装
     */
    public static function installation($mysqlHostname, $mysqlHostport, $mysqlDatabase, $mysqlUsername, $mysqlPassword, $mysqlPrefix, $adminUsername, $adminPassword, $adminEmail = null)
    {
        self::checkenv();
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
        $config = Config::get('database.connections.mysql');
        try {
            $pdo = new PDO("{$config['type']}:host={$mysqlHostname}" . ($mysqlHostport ? ";port={$mysqlHostport}" : ''), $mysqlUsername, $mysqlPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->query("CREATE DATABASE IF NOT EXISTS `{$mysqlDatabase}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            $dbConfig                         = Config::get('database');
            $dbConfig['connections']['mysql'] = array_merge($dbConfig['connections']['mysql'], [
                'type'     => "{$config['type']}",
                'hostname' => "{$mysqlHostname}",
                'hostport' => "{$mysqlHostport}",
                'database' => "{$mysqlDatabase}",
                'username' => "{$mysqlUsername}",
                'password' => "{$mysqlPassword}",
                'prefix'   => "{$mysqlPrefix}",
            ]);

            // 连接install命令中指定的数据库
            Config::set(['connections' => $dbConfig['connections']], 'database');
            $instance = Db::connect('mysql');

            // 查询一次SQL,判断连接是否正常
            $instance->execute("SELECT 1");

            $db = new PDO("{$config['type']}:dbname={$mysqlDatabase};host={$mysqlHostname}", $mysqlUsername, $mysqlPassword);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
            // 调用原生PDO对象进行批量查询
            $db->exec($sql);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }

        // 后台入口文件
        $adminFile = public_path() . 'admin.php';

        // 数据库配置文件
        $dbConfigFile = app()->getConfigPath() . 'database.php';
        $dbConfigText = @file_get_contents($dbConfigFile);
        $callback     = function ($matches) use ($mysqlHostname, $mysqlHostport, $mysqlUsername, $mysqlPassword, $mysqlDatabase, $mysqlPrefix) {
            $field   = "mysql" . ucfirst($matches[1]);
            $replace = $$field;
            if ($matches[1] == 'hostport' && $mysqlHostport == 3306) {
                $replace = '';
            }
            return "'{$matches[1]}'{$matches[2]}=>{$matches[3]}env('database.{$matches[1]}', '{$replace}'),";
        };
        $dbConfigText = preg_replace_callback("/'(hostname|database|username|password|hostport|prefix)'(\s+)=>(\s+)env\((.*)\)\,/", $callback, $dbConfigText);

        // 检测能否成功写入数据库配置
        $result = @file_put_contents($dbConfigFile, $dbConfigText);
        if (!$result) {
            throw new Exception('当前权限不足，无法写入文件 config/database.php');
        }

        // 写入.example.env文件
        $envFile        = root_path() . '.example.env';
        $envFileContent = @file_get_contents($envFile);
        if ($envFileContent) {
            $databasePos = stripos($envFileContent, '[DATABASE]');
            if ($databasePos !== false) {
                // 清理已有数据库配置
                $envFileContent = substr($envFileContent, 0, $databasePos);
            }
            $envFileContent .= "\n" . '[DATABASE]' . "\n";
            $envFileContent .= 'TYPE = mysql' . "\n";
            $envFileContent .= 'HOSTNAME = ' . $mysqlHostname . "\n";
            $envFileContent .= 'DATABASE = ' . $mysqlDatabase . "\n";
            $envFileContent .= 'USERNAME = ' . $mysqlUsername . "\n";
            $envFileContent .= 'PASSWORD = ' . $mysqlPassword . "\n";
            $envFileContent .= 'HOSTPORT = ' . $mysqlHostport . "\n";
            $envFileContent .= 'PREFIX = ' . $mysqlPrefix . "\n";
            $envFileContent .= 'CHARSET = utf8mb4' . "\n";
            $envFileContent .= 'DEBUG = true' . "\n";
            $result = @file_put_contents($envFile, $envFileContent);
            if (!$result) {
                throw new Exception('当前权限不足，无法写入文件 .example.env');
            }
        }

        // 设置新的Token随机密钥key
        $oldTokenKey    = Config::get('token.key');
        $newTokenKey    = Random::alnum(32);
        $coreConfigFile = app()->getConfigPath() . 'token.php';
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
        $newPassword   = md5(md5($adminPassword) . $newSalt);
        $data          = ['username' => $adminUsername, 'email' => $adminEmail, 'password' => $newPassword, 'encrypt' => $newSalt];
        $instance->name('admin')->where('username', 'admin')->update($data);

        // 变更前台默认用户的密码,随机生成
        $newSalt     = substr(md5(uniqid(true)), 0, 6);
        $newPassword = md5(md5(Random::alnum(8)) . $newSalt);
        $instance->name('user')->where('username', 'admin')->update(['password' => $newPassword, 'encrypt' => $newSalt]);

        // 修改后台入口
        $adminName = '';
        if (is_file($adminFile)) {
            $adminName = Random::alnum(10) . '.php';
            rename($adminFile, public_path() . $adminName);
        }

        $installLockFile = INSTALL_PATH . "install.lock";
        //检测能否成功写入lock文件
        $result = @file_put_contents($installLockFile, 1);
        if (!$result) {
            throw new Exception('当前权限不足，无法写入文件 app/admin/command/Install/install.lock');
        }
        try {
            //删除安装脚本
            @unlink(public_path() . 'install.php');
        } catch (\Exception $e) {

        }
        return $adminName;
    }

    /**
     * 检测环境
     */
    public static function checkenv()
    {
        //数据库配置文件
        $dbConfigFile = app()->getConfigPath() . 'database.php';

        if (version_compare(PHP_VERSION, '8.0.0', '<')) {
            throw new Exception("当前版本" . PHP_VERSION . "过低，请使用PHP8.0.0以上版本");
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
