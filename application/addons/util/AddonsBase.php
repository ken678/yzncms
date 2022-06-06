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
        $this->addonName = parse_name($this->request->controller());
        $this->addonPath = ADDON_PATH . $this->addonName . DIRECTORY_SEPARATOR;
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

    /**
     * 自动定位模板文件
     * @access private
     * @param  string $template 模板文件规则
     * @param type $addonPath 插件目录
     * @return string
     */
    private function parseAddonTemplateFile($template, $addonPath)
    {
        if (0 !== strpos($template, '/')) {
            $template = str_replace(['/', ':'], DIRECTORY_SEPARATOR, $template);
            if ($this->addonName) {
                if ('' == $template) {
                    // 如果模板文件名为空 按照默认规则定位
                    $template = str_replace('.', DIRECTORY_SEPARATOR, $this->addonName) . DIRECTORY_SEPARATOR . $this->request->action();
                } elseif (false === strpos($template, DIRECTORY_SEPARATOR)) {
                    $template = str_replace('.', DIRECTORY_SEPARATOR, $this->addonName) . DIRECTORY_SEPARATOR . $template;
                }
            }
        } else {
            $template = str_replace(['/', ':'], DIRECTORY_SEPARATOR, substr($template, 1));
        }
        return $addonPath . 'view' . DIRECTORY_SEPARATOR . ltrim($template, '/') . '.html';

    }
}
