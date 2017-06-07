<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:42:"E:\yzncms/apps/admin\view\index\index.html";i:1491034880;s:50:"E:\yzncms/apps/admin\view\public\index_layout.html";i:1496825325;s:46:"E:\yzncms/apps/admin\view\public\left_nav.html";i:1493108177;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title>Yzncms</title>
<link href="__STATIC__/admin/css/index.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/css/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="__STATIC__/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<script type="text/javascript">
var SITEURL = '';
var ADMIN_TEMPLATES_URL = '__STATIC__/admin';
</script>
<script type="text/javascript" src="__STATIC__/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/common.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="__STATIC__/js/jquery.cookie.js"></script>
<script type="text/javascript" src="__STATIC__/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/jquery.bgColorSelector.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/admincp.js"></script>
</head>
<body>
<div class="admincp-map ui-widget-content" nctype="map_nav" style="display:none;" id="draggable" >
  <div class="title ui-widget-header" >
    <h3>管理中心全部菜单</h3>
    <h5>切换显示全部管理菜单，通过点击勾选可添加菜单为管理常用操作项，最多添加10个</h5>
    <span><a nctype="map_off" href="JavaScript:void(0);">X</a></span> </div>
  <div class="content">内容</div>
</div>
<!--顶部导航 START-->
<div class="admincp-header">
  <div class="bgSelector"></div>
  <div id="foldSidebar"><i class="fa fa-outdent " title="展开/收起侧边导航"></i></div>
  <div class="admincp-name">
    <h2>Yzncms v1.0<br>平台系统管理中心</h2>
  </div>
  <div class="nc-module-menu">
    <ul class="nc-row">
      <?php if(is_array($__MENU__) || $__MENU__ instanceof \think\Collection || $__MENU__ instanceof \think\Paginator): $i = 0; $__LIST__ = $__MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?>
          <li data-param="<?php echo $key; ?>" class=""><a href="javascript:void(0);"><?php echo $menu['title']; ?></a></li>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
  </div>
  <div class="admincp-header-r">
    <div class="manager">
      <dl>
        <dt class="name"><?php echo session('user_auth.username'); ?></dt>
        <dd class="group"><?php echo $role_name; ?></dd>
      </dl>
      <span class="avatar">
      <input name="_pic" type="file" class="admin-avatar-file" id="_pic" title="设置管理员头像"/>
      <img alt="" nctype="admin_avatar" src=""> </span><i class="arrow" id="admin-manager-btn" title="显示快捷管理菜单"></i>
      <div class="manager-menu">
        <div class="title">
          <h4>上次登录</h4>
          <a href="javascript:void(0);" class="edit-password">修改密码</a> </div>
        <div class="login-date">
        <?php if(session('last_login_time') > 0) { echo date('Y-m-d H:i:s', session('last_login_time'));} else { echo '--';}?>
          <span>(IP:
        <?php if (!empty(session('last_login_ip'))) { echo long2ip(session('last_login_ip'));} else { echo '--';}?>
                )</span> </div>
        <div class="title">
          <h4>常用操作</h4>
          <a href="javascript:void(0)" class="add-menu">添加菜单</a> </div>
      </div>
    </div>
    <ul class="operate nc-row">
      <li style="display: none !important;" nctype="pending_matters"><a class="toast show-option" href="javascript:void(0);"  title="查看待处理事项">&nbsp;<em>0</em></a></li>
      <li><a class="sitemap show-option" nctype="map_on" href="javascript:void(0);" title="查看全部管理菜单">&nbsp;</a></li>
      <li><a class="style-color show-option" id="trace_show" href="javascript:void(0);" title="给管理中心换个颜色">&nbsp;</a></li>
      <li><a class="homepage show-option" target="_blank" href="" title="新窗口打开商城首页">&nbsp;</a></li>
      <li><a class="login-out show-option" href="<?php echo url('index/logout'); ?>" title="安全退出管理中心">&nbsp;</a></li>
    </ul>
  </div>
  <div class="clear"></div>
</div>
<!--顶部导航 END-->
<!--左侧菜单 及 右侧主内容 START-->
<div class="admincp-container unfold">
  <div class="admincp-container-left">
    <div class="top-border"><span class="nav-side"></span><span class="sub-side"></span></div>
    <?php if(is_array($__MENU__) || $__MENU__ instanceof \think\Collection || $__MENU__ instanceof \think\Paginator): $i = 0; $__LIST__ = $__MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu_top): $mod = ($i % 2 );++$i;?>
<div id="admincpNavTabs_<?php echo $key; ?>" class="nav-tabs">
<?php if(!(empty($menu_top['items']) || (($menu_top['items'] instanceof \think\Collection || $menu_top['items'] instanceof \think\Paginator ) && $menu_top['items']->isEmpty()))): if(is_array($menu_top['items']) || $menu_top['items'] instanceof \think\Collection || $menu_top['items'] instanceof \think\Paginator): $k1 = 0; $__LIST__ = $menu_top['items'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu_center): $mod = ($k1 % 2 );++$k1;?>
<dl>
<dt><a href="javascript:void(0);"><span class="ico-system-<?php echo $k1; ?>"></span><h3><?php echo $menu_center['title']; ?></h3></a></dt>
<dd class="sub-menu">
<ul>
<?php if(!(empty($menu_center['items']) || (($menu_center['items'] instanceof \think\Collection || $menu_center['items'] instanceof \think\Paginator ) && $menu_center['items']->isEmpty()))): if(is_array($menu_center['items']) || $menu_center['items'] instanceof \think\Collection || $menu_center['items'] instanceof \think\Paginator): if( count($menu_center['items'])==0 ) : echo "" ;else: foreach($menu_center['items'] as $key=>$menu_bottom): ?>
<li ><a href="javascript:void(0);" data-param="<?php echo $menu_bottom['url']; ?>" data-id="<?php echo $menu_bottom['id']; ?>"><?php echo $menu_bottom['title']; ?></a></li>
<?php endforeach; endif; else: echo "" ;endif; endif; ?>
</ul>
</dd>
</dl>
<?php endforeach; endif; else: echo "" ;endif; endif; ?>
</div>
<?php endforeach; endif; else: echo "" ;endif; ?>
    <div class="about" title="关于系统" onclick="ajax_form('about', '', '<?php echo url("admin/aboutus/index"); ?>', 640);"><i class="fa fa-copyright"></i><span>Yzncms v1.0</span></div>
  </div>
  <div class="admincp-container-right">
    <div class="top-border"></div>
    <iframe src="" id="workspace" name="workspace" style="overflow: visible;" frameborder="0" width="100%" height="94%" scrolling="yes" onload="window.parent"></iframe>
  </div>
</div>
<!--左侧菜单 及 右侧主内容 END-->
</body>
</html>