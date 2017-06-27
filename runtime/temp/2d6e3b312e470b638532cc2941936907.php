<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:53:"E:\yzncms/apps/admin\view\auth_manager\editgroup.html";i:1496734636;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;}*/ ?>
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
    <div class="item-title"><a class="back" href="<?php echo url('AuthManager/index'); ?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>权限设置 - <?php echo !empty($auth_group['id'])?'编辑':'新增'; ?>权限组</h3>
        <h5>管理中心操作权限及分组设置</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
      <ul>
        <li>添加权限组，方便添加管理员时使用。</li>
      </ul>
  </div>
  <form action="<?php echo url('AuthManager/writeGroup'); ?>" name="adminForm" id="add_form" enctype="application/x-www-form-urlencoded" method="POST" class="form-horizontal auth-form">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="admin_name"><em>*</em>权限组</label>
        </dt>
        <dd class="opt">
          <input type="text"  value="<?php echo $auth_group['title']; ?>" maxlength="40"  name="title" class="input-txt">
          <p class="notic">权限组名称，用于添加管理员时选择使用。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="admin_name">描述</label>
        </dt>
        <dd class="opt">
          <textarea name="description" rows="6" class="tarea" ><?php echo $auth_group['description']; ?></textarea>
          <p class="notic">权限的相关描述</p>
        </dd>
      </dl>
      <?php if(isset($auth_group['id'])): ?><input type="hidden" name="id" value="<?php echo $auth_group['id']; ?>" /><?php endif; ?>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.adminForm.submit()">确认提交</a></div>
    </div>
  </form>
</div>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>