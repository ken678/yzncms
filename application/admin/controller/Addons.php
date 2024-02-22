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
// | 插件管理
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Adminlog;
use app\common\controller\Adminbase;
use think\addons\AddonException;
use think\addons\Service;
use think\Db;
use think\Exception;
use think\facade\Config;

class Addons extends Adminbase
{
    //初始化
    protected function initialize()
    {
        parent::initialize();
        if (!$this->auth->isAdministrator() && in_array($this->request->action(), ['install', 'uninstall', 'local'])) {
            $this->error('非超级管理员禁止操作！');
        }
    }

    //显示插件列表
    public function index()
    {
        $type   = $this->request->param("type", 'online');
        $limit  = $this->request->param("limit/d");
        $page   = $this->request->param("page/d", 1);
        $search = $this->request->param("search", '', 'strip_tags,htmlspecialchars');

        if ($this->request->isAjax()) {
            if ($type == 'local') {
                $addons = get_addon_list();
                if ($search) {
                    $addons = array_filter($addons, function ($v) use ($search) {
                        return stripos($v['name'], $search) !== false ||
                        stripos($v['title'], $search) !== false ||
                        stripos($v['description'], $search) !== false;
                    });
                }
                $list = [];
                foreach ($addons as $k => $v) {
                    $config              = get_addon_config($v['name']);
                    $v['config']         = $config ? 1 : 0;
                    $addons[$k]['addon'] = $v;
                }
                $count = count($addons);
                if ($limit) {
                    $addons = array_slice($addons, ($page - 1) * $limit, $limit);
                }
                $result = ["code" => 0, "data" => $addons, 'count' => $count];
            } else {
                //在线插件
                $list         = $this->getAddonList($search, $page, $limit);
                $onlineaddons = $list['list'] ?? [];
                $category     = $list['category'] ?? [];
                $count        = $list['count'] ?? -1;
                //本地插件
                $addons = get_addon_list();
                foreach ($addons as $k => &$v) {
                    $config      = get_addon_config($v['name']);
                    $v['config'] = $config ? 1 : 0;
                }
                foreach ($onlineaddons as $index => &$item) {
                    $item['addon'] = $addons[$item['name']] ?? '';
                }
                $result = ["code" => 0, "data" => $onlineaddons, "category" => $category, 'count' => $count];
            }

            return json($result);
        }
        $this->assign([
            'api_url' => config('api_url'),
            'type'    => $type,
        ]);
        return $this->fetch();
    }

    /**
     * 设置插件页面
     */
    public function config($name = null)
    {
        $name = $name ? $name : $this->request->get("name");
        if (!$name) {
            $this->error('参数不得为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件名称不正确！');
        }
        if (!is_dir(ADDON_PATH . $name)) {
            $this->error('目录不存在！');
        }
        $info   = get_addon_info($name);
        $config = get_addon_fullconfig($name);
        if (!$info) {
            $this->error('配置不存在！');
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("config/a", [], 'trim');
            if ($params) {
                foreach ($config as $k => &$v) {
                    if (isset($params[$v['name']])) {
                        if ($v['type'] == 'array') {
                            $params[$v['name']] = is_array($params[$v['name']]) ? $params[$v['name']] : (array) json_decode($params[$v['name']],
                                true);
                            $value = $params[$v['name']];
                        } else {
                            $value = is_array($params[$v['name']]) ? implode(',',
                                $params[$v['name']]) : $params[$v['name']];
                        }
                        $v['value'] = $value;
                    } elseif ($v['type'] == 'checkbox' && !isset($params[$v['name']])) {
                        //单独处理多选框为空不传参
                        $v['value'] = '';
                    }
                }
                try {
                    $addon = get_addon_instance($name);
                    //插件自定义配置实现逻辑
                    if (method_exists($addon, 'config')) {
                        $addon->config($name, $config);
                    } else {
                        //更新配置文件
                        set_addon_fullconfig($name, $config);
                        Service::refresh();
                    }
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->success('插件配置成功！');
        }
        $tips = [];
        foreach ($config as $index => &$item) {
            if ($item['name'] == '__tips__') {
                $tips = $item;
                unset($config[$index]);
            }
        }
        $this->assign('data', ['info' => $info, 'config' => $config, 'tips' => $tips]);
        $configFile = ADDON_PATH . $name . DS . 'config.html';
        $viewFile   = is_file($configFile) ? $configFile : '';
        return $this->fetch($viewFile);
    }

    /**
     * 禁用启用.
     */
    public function state()
    {
        $name   = $this->request->param('name');
        $action = $this->request->param('action');
        $force  = $this->request->post("force/d");
        if (!$name) {
            $this->error('参数不得为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件名称不正确');
        }
        try {
            $action = $action == 'enable' ? $action : 'disable';
            //调用启用、禁用的方法
            Service::$action($name, $force);
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('操作成功');
    }

    /**
     * 安装插件
     */
    public function install()
    {
        Adminlog::setTitle('插件安装');
        $name  = $this->request->param('name');
        $force = $this->request->param("force/d");
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        $info = [];
        try {
            $uid     = $this->request->post("uid");
            $token   = $this->request->post("token");
            $version = $this->request->post("version");
            $extend  = [
                'uid'            => $uid,
                'token'          => $token,
                'version'        => $version,
                'yzncms_version' => Config::get('version.yzncms_version'),
            ];
            $info = Service::install($name, $force, $extend);
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件安装成功！清除浏览器缓存和框架缓存后生效！', '', ['addon' => $info]);
    }

    /**
     * 卸载插件
     */
    public function uninstall()
    {
        Adminlog::setTitle('插件卸载');
        $name       = $this->request->param('name');
        $force      = $this->request->param("force/d");
        $droptables = $this->request->param("droptables/d");
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        //只有开启调试且为超级管理员才允许删除相关数据库
        $tables = [];
        if ($droptables && Config::get("app_debug") && $this->auth->isAdministrator()) {
            $tables = get_addon_tables($name);
        }
        try {
            Service::uninstall($name, $force);
            if ($tables) {
                $prefix = Config::get('database.prefix');
                //删除插件关联表
                foreach ($tables as $index => $table) {
                    Db::execute("DROP TABLE IF EXISTS `{$table}`");
                }
            }
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件卸载成功！清除浏览器缓存和框架缓存后生效！');
    }

    /**
     * 本地上传
     */
    public function local()
    {
        Adminlog::setTitle('插件上传安装');
        if (!Config::get("app_debug")) {
            $this->error('本地上传安装需要开启调试模式！');
        }
        $file = $this->request->file('file');
        try {
            $info = Service::local($file);
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('插件安装成功！清除浏览器缓存和框架缓存后生效！', '', ['addon' => $info]);
    }

    /**
     * 更新插件
     */
    public function upgrade()
    {
        $name        = $this->request->param('name');
        $addonTmpDir = ROOT_PATH . 'runtime' . DS . 'addons' . DS;
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $this->error('插件标识错误！');
        }
        if (!is_dir($addonTmpDir)) {
            @mkdir($addonTmpDir, 0755, true);
        }
        $info = [];
        try {
            $info    = get_addon_info($name);
            $uid     = $this->request->post("uid");
            $token   = $this->request->post("token");
            $version = $this->request->post("version");
            $extend  = [
                'uid'            => $uid,
                'token'          => $token,
                'version'        => $version,
                'oldversion'     => $info['version'] ?? '',
                'yzncms_version' => Config::get('version.yzncms_version'),
            ];
            //调用更新的方法
            $info = Service::upgrade($name, $extend);
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('升级成功', '', ['addon' => $info]);
    }

    /**
     * 测试数据
     */
    public function testdata()
    {
        $name = $this->request->post("name");
        if (empty($name)) {
            $this->error('请选择需要安装的插件！');
        }
        if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
            $this->error('插件标识错误！');
        }

        try {
            Service::runSQL($name, 'testdata');
        } catch (AddonException $e) {
            $this->result($e->getData(), $e->getCode(), $e->getMessage());
        } catch (Exception $e) {
            $this->error($e->getMessage(), $e->getCode());
        }
        $this->success('导入成功');
    }

    /**
     * 检测
     */
    public function isbuy()
    {
        $name    = $this->request->post("name");
        $uid     = $this->request->post("uid");
        $token   = $this->request->post("token");
        $version = $this->request->post("version");
        $extend  = [
            'uid'            => $uid,
            'token'          => $token,
            'version'        => $version,
            'yzncms_version' => Config::get('version.yzncms_version'),
        ];
        try {
            $result = Service::isBuy($name, $extend);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
        return json($result);
    }

    /**
     * 获取插件相关表
     */
    public function get_table_list()
    {
        $name = $this->request->post("name");
        if (!preg_match("/^[a-zA-Z0-9]+$/", $name)) {
            $this->error('插件标识错误！');
        }
        $tables = get_addon_tables($name);
        $prefix = Config::get('database.prefix');
        $tables = array_values($tables);
        $this->success('', null, ['tables' => $tables]);
    }

    protected function getAddonList($search, $page, $limit)
    {
        $params = [
            'uid'         => $this->request->param('uid/d'),
            'token'       => $this->request->param('token'),
            'category_id' => $this->request->param('category_id/d'),
            'version'     => Config::get('version.yzncms_version'),
            'page'        => $page,
            'limit'       => $limit,
            'search'      => $search,
        ];
        $json = [];
        try {
            $json = Service::addons($params);
        } catch (\Exception $e) {

        }
        return $json;
    }
}
