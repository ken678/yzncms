<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:50:"E:\yzncms/apps/admin\view\management\adminadd.html";i:1493024941;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1492155695;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1491898212;}*/ ?>
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
<link href="__STATIC__/admin/css/index.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/css/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="__STATIC__/admin/font/css/font-awesome.min.css" rel="stylesheet" />
<style type="text/css">html, body { overflow: visible;}</style>

<script type="text/javascript" src="__STATIC__/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="__STATIC__/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/admin.js"></script>
<script type="text/javascript" src="http://v5.33hao.com/admin/resource/js/flexigrid.js"></script>


</head>
<body style="background-color: #FFF; overflow: auto;">
<script type="text/javascript" src="__STATIC__/admin/js/jquery.picTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>管理员管理</h3>
        <h5>网站后台管理员索引及管理</h5>
      </div>
      <ul class="tab-base nc-row">
            <?php if(is_array($__GROUP_MENU__) || $__GROUP_MENU__ instanceof \think\Collection || $__GROUP_MENU__ instanceof \think\Paginator): $i = 0; $__LIST__ = $__GROUP_MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group_menu): $mod = ($i % 2 );++$i;?>
    <li><a <?php if($group_menu['action'] == \think\Request::instance()->action()): ?> class="current" <?php endif; ?> href="<?php echo url($group_menu['url'],$group_menu['parameter']); ?>" ><span><?php echo $group_menu['title']; ?></span></a></li>
<?php endforeach; endif; else: echo "" ;endif; ?>

      </ul>
  </div>
  </div>

</div>


<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>