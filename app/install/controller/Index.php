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
// | 安装控制模块
// +----------------------------------------------------------------------
namespace app\install\controller;

use app\admin\library\Install;
use app\common\controller\BaseController;

class Index extends BaseController
{
    public function index()
    {
        define('INSTALL_PATH', app()->getBasePath() . 'admin' . DS . 'command' . DS . 'Install' . DS);

        $installLockFile = INSTALL_PATH . "install.lock";

        if (is_file($installLockFile)) {
            echo '当前已经安装成功，如果需要重新安装，请手动移除install.lock文件';
            exit;
        }

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
                $this->error('两次输入的密码不一致');
            }
            try {
                $adminName = Install::installation($mysqlHostname, $mysqlHostport, $mysqlDatabase, $mysqlUsername, $mysqlPassword, $mysqlPrefix, $adminUsername, $adminPassword, $adminEmail);
            } catch (\PDOException $e) {
                throw new Exception($e->getMessage());
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->success('安装成功！', null, ['adminName' => $adminName]);
        }
        $errInfo = '';
        try {
            Install::checkenv();
        } catch (\Exception $e) {
            $errInfo = $e->getMessage();
        }
        return $this->fetch("/install", ['errInfo' => $errInfo]);
    }

}
