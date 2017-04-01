<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title>欢迎您登录Yzncms后台管理系统</title>
<link href="__STATIC__/admin/css/login.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="login-layout">
    <div class="top">
        <h2>Yzncms系统管理中心</h2>
        <h6>一套简单，易用，面向开发者的内容管理框,采用TP5.0框架开发</h6>
    </div>
    <form action="{:url('admin/index/login')}" method="post" id="form_login">
        <div class="lock-holder">
            <div class="form-group pull-left input-username">
                <label>账号</label>
                <input name="username" id="username" type="text" class="input-text" value="" autocomplete="off" >
            </div>
            <div class="form-group pull-right input-password-box">
                <label>密码</label>
                <input name="password" id="password" class="input-text" autocomplete="off" type="password" autocomplete="off">
            </div>
        </div>
        <div class="avatar"><img src="__STATIC__/admin/images/login/admin.png" alt=""></div>
        <div class="submit"> <span>
            <div class="code">
              <div class="arrow"></div>
              <div class="verifycode"><img src="{:url('admin/index/getVerify')}" name="codeimage" id="codeimage" border="0"/></div>
              <a href="JavaScript:void(0);" id="hide" class="close" title="关闭"><i></i></a><a href="JavaScript:void(0);"  class="reloadverify" title="看不清,点击更换验证码"><i></i></a>
            </div>
            <input name="captcha" type="text" required class="input-code" id="captcha" placeholder="输入验证" title="验证码为4个字符" value="" >
            </span>
            <span><input name="" class="input-button btn-submit" type="button" value="登录"></span>
        </div>
        <div class="submit2"></div>
    </form>
</div>
<!--[if lt IE 9]>
<script type="text/javascript" src="__STATIC__/js/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="__STATIC__/js/jquery-2.0.3.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="__STATIC__/admin/js/common.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/jquery.progressBar.js"></script>
<script type="text/javascript" src="__STATIC__/admin/js/canvas-particle.js"></script>
<script type="text/javascript">
$(function(){
    //初始化选中用户名输入框
    $("#user_name").focus();
    //刷新验证码
    var verifyimg = $(".verifycode img").attr("src");
    $(".reloadverify").click(function(){
        if( verifyimg.indexOf('?')>0){
            $(".verifycode img").attr("src", verifyimg+'&random='+Math.random());
        }else{
            $(".verifycode img").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
        }
    });
    $(".verifycode img").click(function(){
        $(".reloadverify").click();
    });
    //显示隐藏验证码
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });

    $("#captcha").nc_placeholder();//placeholder兼容
    //动画登录
    $('.btn-submit').click(function(e){
            $('.input-username,dot-left').addClass('animated fadeOutRight')
            $('.input-password-box,dot-right').addClass('animated fadeOutLeft')
            $('.btn-submit').addClass('animated fadeOutUp')
            setTimeout(function () {
                      $('.avatar').addClass('avatar-top');
                      $('.submit').hide();
                      $('.submit2').html('<div class="progress"><div class="progress-bar progress-bar-success" aria-valuetransitiongoal="100"></div></div>');
                      $('.progress .progress-bar').progressbar({
                          done : function() {$('#form_login').submit();}
                      });
              },
          300);
    });
    //背景特效
    var config = {vx: 4, vy:  4, height: 2, width: 2, count: 100, color: "121, 162, 185", stroke: "100,200,180", dist: 6000, e_dist: 20000, max_conn: 10 }
    CanvasParticle(config);
});
</script>
</body>
</html>
