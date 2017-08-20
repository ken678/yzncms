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
namespace app\common\behavior;
use app\content\model\Content as ContentModel;

// 初始化钩子信息
class AppInit{

    // 行为扩展的执行入口必须是run
    public function run(&$params){
    	// 注册AUTOLOAD方法
        spl_autoload_register('app\common\behavior\AppInit::autoload');
    }

    /**
     * 类库自动加载
     * @param string $class 对象类名
     * @return void
     */
    static public function autoload($class) {
        if (in_array($class, array('content_form', 'content_input', 'content_output', 'content_update', 'content_delete'))) {
            ContentModel::classGenerate();
            require RUNTIME_PATH . "{$class}.php";
            return;
        }
    }

}