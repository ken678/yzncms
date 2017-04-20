<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:44:"E:\yzncms/apps/admin\view\config\extend.html";i:1492505940;s:44:"E:\yzncms/apps/admin\view\Public\layout.html";i:1492155695;s:41:"E:\yzncms/apps/admin\view\public\nav.html";i:1491898212;}*/ ?>
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
      <span id="explanationZoom" title="扩展配置 ，用法：模板调用标签：<?php echo cache('Config.键名'); ?>，PHP代码中调用：cache('Config.键名');"></span> </div>
    <ul>
      <li>扩展配置 ，用法：模板调用标签：{:cache('Config.键名')}，PHP代码中调用：cache('Config.键名');</li>
    </ul>
  </div>
  <form action="<?php echo url('config/extend'); ?>" method="post" class="form-horizontal" name="form1">
    <input type="hidden" name="action" value="add">
    <div class="ncap-form-default">
    <div class="title">
        <h3>添加扩展配置项</h3>
    </div>
    <dl class="row">
      <dt class="tit">键名</dt>
      <dd class="opt">
        <input id="fieldname" name="fieldname" value="" type="text" class="input-txt">
        <p class="notic">注意：只允许英文、数组、下划线</p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">名称</dt>
      <dd class="opt">
        <input id="setting[title]" name="setting[title]" value="" type="text" class="input-txt">
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">类型</dt>
      <dd class="opt">
        <select name="type" onchange="extend_type(this.value)">
              <option value="input">单行文本框</option>
              <option value="select">下拉框</option>
              <option value="textarea">多行文本框</option>
              <option value="radio">单选框</option>
              <option value="password">密码输入框</option>
        </select>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">提示</dt>
      <dd class="opt">
        <input id="setting[tips]" name="setting[tips]" value="" type="text" class="input-txt">
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">样式</dt>
      <dd class="opt">
        <input id="setting[style]" name="setting[style]" value="" type="text" class="input-txt">
      </dd>
    </dl>
    <dl class="row setting_radio" style="display:none">
      <dt class="tit">选项</dt>
      <dd class="opt">
        <textarea name="setting[option]" rows="6" class="tarea" id="setting[option]">选项名称1|选项值1</textarea>
        <p class="notic">注意：每行一个选项</p>
      </dd>
    </dl>
    <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()">确认提交</a></div>
    </div>
  </form>

  <form action="<?php echo url('config/extend'); ?>" method="post" class="form-horizontal" name="form2">
    <div class="ncap-form-default">
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle">操作</th>
          <th width="100" align="left">名称</th>
          <th width="120" align="center">键名</th>
          <th width="300" align="center">配置值</th>
          <th width="120" align="center">提示</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($extendList) || $extendList instanceof \think\Collection || $extendList instanceof \think\Paginator): $i = 0; $__LIST__ = $extendList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;$setting = unserialize($vo['setting']); ?>
        <tr class="hover">
          <td><i class="ico-check"></i></td>
          <td>
          <a class="btn red" href="<?php echo url('config/extend',array('fid'=>$vo['fid'],'action'=>'delete')); ?>" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
          </td>
          <td><?php echo $setting['title']; ?></td>
          <td><?php echo $vo['fieldname']; ?></td>
          <td>
          <?php switch($vo['type']): case "input": ?>
          <input type="text" class="input" style="<?php echo $setting['style']; ?>"  name="<?php echo $vo['fieldname']; ?>" value="<?php echo $Site[$vo['fieldname']]; ?>">
          <?php break; case "select": ?>
          <select name="<?php echo $vo['fieldname']; ?>">
          <?php if(is_array($setting['option']) || $setting['option'] instanceof \think\Collection || $setting['option'] instanceof \think\Paginator): $i = 0; $__LIST__ = $setting['option'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rs): $mod = ($i % 2 );++$i;?>
               <option value="<?php echo $rs['value']; ?>" <?php if($Site[$vo['fieldname']] == $rs['value']): ?>selected<?php endif; ?>><?php echo $rs['title']; ?></option>
          <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          <?php break; case "textarea": ?>
          <textarea name="<?php echo $vo['fieldname']; ?>" style="<?php echo $setting['style']; ?>"><?php echo $Site[$vo['fieldname']]; ?></textarea>
          <?php break; case "radio": if(is_array($setting['option']) || $setting['option'] instanceof \think\Collection || $setting['option'] instanceof \think\Paginator): $i = 0; $__LIST__ = $setting['option'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rs): $mod = ($i % 2 );++$i;?>
          <input name="<?php echo $vo['fieldname']; ?>" value="<?php echo $rs['value']; ?>" type="radio"  <?php if($Site[$vo['fieldname']] == $rs['value']): ?>checked<?php endif; ?>> <?php echo $rs['title']; endforeach; endif; else: echo "" ;endif; break; case "password": ?>
          <input type="password" class="input" style="<?php echo $setting['style']; ?>"  name="<?php echo $vo['fieldname']; ?>" value="<?php echo $Site[$vo['fieldname']]; ?>">
          <?php break; endswitch; ?>
          </td>
          <td><?php echo $setting['tips']; ?></td>
          <td></td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      </tbody>
    </table>
    <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form2.submit()">确认提交</a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
    $('.flex-table').flexigrid({
      height:'auto',// 高度自动
      usepager: false,// 不翻页
      striped: true,// 使用斑马线
      resizable: false,// 不调节大小
      reload: false,// 不使用刷新
      columnControl: false,// 不使用列控制
      title: '扩展配置列表'
    });
    function extend_type(type){
        if(type == 'radio' || type == 'select'){
            $('.setting_radio').show();
            $('.setting_radio textarea').attr('disabled',false);
        }else{
            $('.setting_radio').hide();
            $('.setting_radio textarea').attr('disabled',true);
        }
    }
</script>

<div id="goTop"> <a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
</body>
</html>