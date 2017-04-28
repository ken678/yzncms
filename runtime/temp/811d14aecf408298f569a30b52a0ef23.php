<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"E:\yzncms/apps/admin\view\manager\add.html";i:1493355285;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1493111132;}*/ ?>
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
<script type="text/javascript" src="__STATIC__/admin/js/flexigrid.js"></script>

<script type="text/javascript" src="__STATIC__/js/jquery.validation.min.js"></script>
</head>
<body style="background-color: #FFF; overflow: auto;">
<script type="text/javascript" src="__STATIC__/admin/js/jquery.picTip.js"></script>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo url('manager/index'); ?>" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>权限设置 - 添加管理员</h3>
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
      <li>可添加一名后台管理员，设置其后台登录用户名及密码，但不可登录网站前台。</li>
      <li>管理员必须下属某个权限组，如无权限组选择请先完成“添加权限组”步骤。</li>
    </ul>
  </div>
  <form id="add_form" method="post">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="username"><em>*</em>用户名</label>
        </dt>
        <dd class="opt">
          <input type="text" id="username" name="username" class="input-txt">
          <span class="err"></span>
          <p class="notic">3-15位字符，可由字母和数字，下划线"_"及破折号"-"组成。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="password"><em>*</em>密码</label>
        </dt>
        <dd class="opt">
          <input type="password" id="password" name="password" class="input-txt">
          <span class="err"></span>
          <p class="notic">6-20位字符，可由英文、数字及标点符号组成。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="password_confirm"><em>*</em>确认密码</label>
        </dt>
        <dd class="opt">
          <input type="password" id="password_confirm" name="password_confirm" class="input-txt">
          <span class="err"></span>
          <p class="notic">请再次输入您的密码。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="email">E-mail</label>
        </dt>
        <dd class="opt">
          <input type="text" id="email" name="email" class="input-txt">
          <span class="err"></span>
          <p class="notic">填写完整邮箱，如 yzncms@163.com</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="nickname">真实姓名</label>
        </dt>
        <dd class="opt">
          <input type="text" id="nickname" name="nickname" class="input-txt">
          <span class="err"></span>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
  //按钮先执行验证再提交表单
  $("#submitBtn").click(function(){
      if($("#add_form").valid()){
        $("#add_form").submit();
      }

  });

  $("#add_form").validate({
      errorPlacement: function(error, element){
        error.appendTo(element.parent('dd').children('span.err'));
      },
      rules : {
          username : {
            required : true,
            minlength: 3,
            maxlength: 20,
          },
          password : {
            required : true,
            minlength: 6,
            maxlength: 20
          },
          password_confirm : {
            required : true,
            equalTo  : '#password'
          },
          email:{
            email:true
          }
      },
      messages : {
          username : {
              required : '<i class="fa fa-exclamation-circle"></i>用户名不能为空',
              minlength: '<i class="fa fa-exclamation-circle"></i>用户名长度不正确',
              maxlength: '<i class="fa fa-exclamation-circle"></i>用户名长度不正确'
          },
          password : {
              required : '<i class="fa fa-exclamation-circle"></i>密码不能为空',
              minlength: '<i class="fa fa-exclamation-circle"></i>密码长度不正确',
              maxlength: '<i class="fa fa-exclamation-circle"></i>密码长度不正确'
          },
          password_confirm: {
              required : '<i class="fa fa-exclamation-circle"></i>确认密码不能为空',
              equalTo  : '<i class="fa fa-exclamation-circle"></i>确认密码不一致'
          },
          email:{
              email    :'<i class="fa fa-exclamation-circle"></i>邮箱格式不正确'
          }
      }
  })
});
</script>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>