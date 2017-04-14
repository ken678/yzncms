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
namespace app\common\controller;
use think\Controller;
use app\admin\model\AuthRule;
use app\admin\model\AuthGroup;

class Adminbase extends Controller {
	/**
	 * 后台初始化
	 */
	protected function _initialize(){
        define('UID',is_login());
        //过滤不需要登陆的行为
        $allowUrl = ['admin/index/login',
                     'admin/index/logout',
                     'admin/index/getverify'
                    ];
        $request = request();
        $visit = strtolower($request->module()."/".$request->controller()."/".$request->action());
        if(in_array($visit,$allowUrl)){

        }else{
            if(!UID){
                $this->error('请先登陆',url('admin/index/login'));
            }else{
                /* 读取数据库中的配置 */
                $config = cache('DB_CONFIG_DATA');
                if(!$config){
                    $config =   api('Config/lists');
                    cache('DB_CONFIG_DATA', $config);
                }
                config($config);//添加配置
                define('IS_ROOT',   is_administrator());
            }
        }
	}


	/**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     */
    final protected function checkRule($rule, $type=AuthRule::rule_url, $mode='url'){
        static $Auth    =   null;
        if (!$Auth) {
            $Auth       =   new \com\Auth();
        }
        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
    }

	/**
     * 获取后台菜单数组
     */
    /*final public function getMenus(){
    	$menus  =   session('admin_menu_list');
        if(empty($menus)){
            $where['pid']   =   0;
            $where['status']  =   1;
            $main  =   \think\Db::name('Menu')->where($where)->order('listorder asc')->field('id,title')->select();
            foreach ($main as $key => $item) {
                $groups = \think\Db::name('Menu')->where(array('group'=>array('neq',''),'pid' =>$item['id']))->distinct(true)->column("group");
                foreach ($groups as $g) {
                    $map = array('group'=>$g);
                    $map['pid']     =   $item['id'];
                    $map['status']    =   1;
                    $data = \think\Db::name('Menu')->where($map)->field('id,pid,title,controller,action,tip')->order('listorder asc')->select();

                    foreach ($data as $key => $a) {
                            $id = $a['id'];
                            $controller = $a['controller'];
                            $action = $a['action'];
                            $array = array(
                                "id" => $id,
                                "title" => $a['title'],
                                "pid" => $a['pid'],
                                "url" => url("{$controller}/{$action}", array("menuid" => $id)),
                            );
                            $ret[$key] = $array;
                    }
                    $menus[$item['title']][$g]=$ret;
                }
            }
        }
    	return $menus;
    }*/

    /**
     * 获取后台分组菜单数组
     */
    final public function get_group_menu(){
        $getMenu = isset($Custom)?$Custom:model('Admin/Menu')->getMenu();
        return $getMenu;
    }









}
