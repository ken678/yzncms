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
namespace app\admin\controller;
use think\Controller;
use app\admin\model\AuthRule;
use app\admin\model\AuthGroup;

class Base extends Controller {
	/**
	 * 后台初始化
	 */
	public function _initialize(){
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
    final public function getMenus(){
    	$model_name   = $this->request->module();
    	$controller   = $this->request->controller();
    	$action_name  = $this->request->action();
    	$menus  =   session('admin_menu_list');
        if(empty($menus)){
            $where['pid']   =   0;
            $where['hide']  =   0;
            $main  =   \think\Db::name('Menu')->where($where)->order('sort asc')->field('id,title')->select();
            foreach ($main as $key => $item) {
                $groups = \think\Db::name('Menu')->where(array('group'=>array('neq',''),'pid' =>$item['id']))->distinct(true)->column("group");
                foreach ($groups as $g) {
                    $map = array('group'=>$g);
                    $map['pid']     =   $item['id'];
                    $map['hide']    =   0;
                    $menus[$item['title']][$g] = \think\Db::name('Menu')->where($map)->field('id,pid,title,url,tip')->order('sort asc')->select();
                }
            }
        }
    	/*if(empty($menus)){
    		 $where['pid']   =   0;
             $where['hide']  =   0;
             $menus['main']  =   \think\Db::name('Menu')->where($where)->order('sort asc')->field('id,title,url')->select();
             $menus['child'] =   array(); //设置子节点
             foreach ($menus['main'] as $key => $item) {
                if ( !IS_ROOT && !$this->checkRule(strtolower($model_name.'/'.$item['url']),AuthRule::rule_main,null) ) {
                    unset($menus['main'][$key]);
                    continue;//继续循环
                }
                if(strtolower($controller.'/'.$action_name)  == strtolower($item['url'])){
                    $menus['main'][$key]['class']='active';//高亮
                }
            }
            foreach ($menus['main'] as $key => $item) {
            	//查找所有分组
            	$groups = \think\Db::name('Menu')->where(array('group'=>array('neq',''),'pid' =>$item['id']))->distinct(true)->column("group");
            	//获取二级分类的合法url
                $where          =   array();
                $where['pid']   =   $item['id'];
                $where['hide']  =   0;
                $second_urls = \think\Db::name('Menu')->where($where)->column('id,url');
            	if(!IS_ROOT){
	                // 检测菜单权限
	                $to_check_urls = array();
	                foreach ($second_urls as $key=>$to_check_url) {
	                    if( stripos($to_check_url,$model_name)!==0 ){
	                        $rule = $model_name.'/'.$to_check_url;
	                    }else{
	                        $rule = $to_check_url;
	                    }
	                    if($this->checkRule($rule, AuthRule::rule_url,null))
	                        $to_check_urls[] = $to_check_url;
	                }
                }
                // 按照分组生成子菜单树
                foreach ($groups as $g) {
                    $map = array('group'=>$g);
                    if(isset($to_check_urls)){
                        if(empty($to_check_urls)){
                            // 没有任何权限
                            continue;
                        }else{
                            $map['url'] = array('in', $to_check_urls);
                        }
                    }
                    $map['pid']     =   $item['id'];
                    $map['hide']    =   0;
                    $menus['child'][$g] = \think\Db::name('Menu')->where($map)->field('id,pid,title,url,tip')->order('sort asc')->select();
                }
            }

    	}*/

    	return $menus;
    }









}
