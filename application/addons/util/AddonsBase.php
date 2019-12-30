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
// | 前台插件类
// +----------------------------------------------------------------------
namespace app\addons\util;

use app\addons\model\Addons as Addons_model;
use app\common\controller\Base;

class AddonsBase extends Base
{
    //插件标识
    public $addonName = null;
    //插件基本信息
    protected $addonInfo = null;
    //插件路径
    protected $addonPath = null;

    protected function initialize()
    {
        parent::initialize();
        $this->addonName = \think\Loader::parseName($this->request->controller());
        $this->Addons_model = new Addons_model;
        $this->addonInfo = $this->Addons_model->where(array('name' => $this->addonName))->find();
        if (empty($this->addonInfo)) {
            $this->error('该插件没有安装！');
        }
        if (!$this->addonInfo['status']) {
            $this->error('该插件已被禁用！');
        }
        $this->addonPath = ADDON_PATH . $this->addonName . DIRECTORY_SEPARATOR;
    }

    /**
     * 获取插件配置
     * @staticvar array $_config
     * @return type
     */
    final public function getAddonConfig()
    {
        $config = $this->addonInfo['config'];
        if ($config) {
            $config = json_decode($config, true);
        }
        return $config;
    }

    /**
     * 加载模板输出
     * @access protected
     * @param  string $template 模板文件名
     * @param  array  $vars     模板输出变量
     * @param  array  $config   模板参数
     * @return mixed
     */
    protected function fetch($template = '', $vars = [], $config = [])
    {
        return $this->view->fetch($this->parseAddonTemplateFile($template, $this->addonPath), $vars, $config);
    }
}
