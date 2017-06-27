<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"E:\yzncms/apps/admin\view\menu\add.html";i:1498458327;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1495508374;}*/ ?>
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
    <div class="item-title"><a class="back" href="<?php echo url("menu/index"); ?>" title="返回商品分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>后台菜单管理 - 新增</h3>
        <h5>所有后台菜单索引及管理</h5>
      </div>
    </div>
  </div>
  <form id="menu_form" method="post" enctype="multipart/form-data">
      <div class="ncap-form-default">
<dl class="row">
        <dt class="tit">
          <label for="pid">上级菜单</label>
        </dt>
        <dd class="opt">
          <select name="pid" id="pid" class="valid">
            <option value="0">作为一级菜单</option>
                <?php echo $select_categorys; ?>
          </select>
          <span class="err"></span>
          <p class="notic">如果选择上级分类，那么新增的分类则为被选择上级分类的子分类</p>
        </dd>
      </dl>
        <dl class="row">
          <dt class="tit">
            <label for="title">名称</label>
          </dt>
          <dd class="opt">
            <input type="text" class="input-txt" name="title" id="title" />
            <span class="err"></span>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="app">模块</label>
          </dt>
          <dd class="opt">
            <input type="text" class="input-txt" name="app" id="app" value="Admin" />
            <span class="err"></span>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="controller">控制器</label>
          </dt>
          <dd class="opt">
            <input type="text" class="input-txt" name="controller" id="controller" />
            <span class="err"></span>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="action">方法</label>
          </dt>
          <dd class="opt">
            <input type="text" class="input-txt" name="action" id="action" value="" />
            <span class="err"></span>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label for="parameter">附加参数</label>
          </dt>
          <dd class="opt">
            <input type="text" class="input-txt" name="parameter" id="parameter" value="" />
            <span class="err"></span>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">是否隐藏</dt>
          <dd class="opt">
            <div class="onoff">
              <label for="status1" class="cb-enable">是</label>
              <label for="status0" class="cb-disable selected">否</label>
              <input id="status1" name="status" value="1" type="radio">
              <input id="status0" name="status" value="0" type="radio">
            </div>
            <p class="notic">默认为隐藏，显示请点击【是】</p>
          </dd>
        </dl>
        <div class="bot"><a id="submitBtn" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);">确认提交</a></div>
      </div>
  </form>
</div>
<script>
$(function(){
//按钮先执行验证再提交表单
  $("#submitBtn").click(function(){
    if($("#menu_form").valid()){
      $("#menu_form").submit();
    }
  });
  //表单验证
  $('#menu_form').validate({
        errorPlacement: function(error, element){
      var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            pid : {
              required :true,
            },
            title : {
              required :true,
            },
            app : {
              required :true,
            },
            controller : {
              required :true,
            },
            action : {
              required :true,
            }
        },
        messages : {
            pid : {
                required : '<i class="fa fa-exclamation-circle"></i>上级菜单不能为空',
            },
            title : {
                required : '<i class="fa fa-exclamation-circle"></i>菜单名称不能为空',
            },
            app : {
                required : '<i class="fa fa-exclamation-circle"></i>模块不能为空',
            },
            controller : {
                required : '<i class="fa fa-exclamation-circle"></i>控制器不能为空',
            },
            action : {
                required : '<i class="fa fa-exclamation-circle"></i>方法不能为空',
            }
        }
    });
});
</script>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>