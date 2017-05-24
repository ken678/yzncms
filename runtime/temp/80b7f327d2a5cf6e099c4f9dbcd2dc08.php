<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:43:"E:\yzncms/apps/admin\view\config\index.html";i:1492154421;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1491898212;}*/ ?>
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
<script type="text/javascript">
var SITEURL = '';
</script>
<script type="text/javascript" src="__STATIC__/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="__STATIC__/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/admin.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="__STATIC__/admin/js/flexigrid.js"></script>

<script type="text/javascript" src="__STATIC__/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/jquery.mousewheel.js"></script>
</head>
<body style="background-color: #FFF; overflow: auto;">
<script type="text/javascript" src="__STATIC__/admin/js/jquery.picTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>网站设置</h3>
        <h5>网站全局内容基本选项设置</h5>
      </div>
      <ul class="tab-base nc-row">
            <?php if(is_array($__GROUP_MENU__) || $__GROUP_MENU__ instanceof \think\Collection || $__GROUP_MENU__ instanceof \think\Paginator): $i = 0; $__LIST__ = $__GROUP_MENU__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group_menu): $mod = ($i % 2 );++$i;?>
    <li><a <?php if($group_menu['action'] == \think\Request::instance()->action()): ?> class="current" <?php endif; ?> href="<?php echo url($group_menu['url'],$group_menu['parameter']); ?>" ><span><?php echo $group_menu['title']; ?></span></a></li>
<?php endforeach; endif; else: echo "" ;endif; ?>

      </ul>
  </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="网站全局基本设置，网站及其他模块相关内容在其各自栏目设置项内进行操作"></span> </div>
    <ul>
      <li>网站全局基本设置，网站及其他模块相关内容在其各自栏目设置项内进行操作。</li>
    </ul>
  </div>
  <form action="<?php echo url('config/index'); ?>" method="post" class="form-horizontal" name="form1">
    <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">
        <label for="site_title">网站标题</label>
      </dt>
      <dd class="opt">
        <input id="site_title" name="site_title" value="<?php echo $Site['site_title']; ?>" class="input-txt" type="text">
        <p class="notic">网站标题，将显示在前台顶部欢迎信息等位置</p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label for="site_name">网站名称</label>
      </dt>
      <dd class="opt">
        <input id="site_name" name="site_name" value="<?php echo $Site['site_name']; ?>" class="input-txt" type="text">
        <p class="notic">网站名称，将显示在前台顶部欢迎信息等位置</p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label for="site_keyword">网站关键词</label>
      </dt>
      <dd class="opt">
        <input id="site_keyword" name="site_keyword" value="<?php echo $Site['site_keyword']; ?>" class="input-txt" type="text">
        <p class="notic">网站搜索引擎关键字</p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label for="site_description">网站描述</label>
      </dt>
      <dd class="opt">
        <input id="site_description" name="site_description" value="<?php echo $Site['site_description']; ?>" class="input-txt" type="text">
        <p class="notic">网站搜索引擎描述</p>
      </dd>
    </dl>
    <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()">确认提交</a></div>
    </div>
  </form>
</div>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>