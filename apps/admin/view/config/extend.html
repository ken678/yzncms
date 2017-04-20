{extend name="Public:layout" /}
{block name="content"}
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>网站设置</h3>
        <h5>网站全局内容基本选项设置</h5>
      </div>
      <ul class="tab-base nc-row">
            {include file="public/nav" /}
      </ul>
  </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="扩展配置 ，用法：模板调用标签：{:cache('Config.键名')}，PHP代码中调用：cache('Config.键名');"></span> </div>
    <ul>
      <li>扩展配置 ，用法：模板调用标签：{literal}{:cache('Config.键名')}{/literal}，PHP代码中调用：cache('Config.键名');</li>
    </ul>
  </div>
  <form action="{:url('config/extend')}" method="post" class="form-horizontal" name="form1">
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

  <form action="{:url('config/extend')}" method="post" class="form-horizontal" name="form2">
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
        {volist name="extendList" id="vo"}
        {php}$setting = unserialize($vo['setting']);{/php}
        <tr class="hover">
          <td><i class="ico-check"></i></td>
          <td>
          <a class="btn red" href="{:url('config/extend',array('fid'=>$vo['fid'],'action'=>'delete'))}" onclick="if(confirm('删除后将不能恢复，确认删除这  1 项吗？')){return true;} else {return false;}"><i class="fa fa-trash-o"></i>删除</a>
          </td>
          <td>{$setting.title}</td>
          <td>{$vo.fieldname}</td>
          <td>
          {switch name="vo.type" }
          {case value="input"}
          <input type="text" class="input" style="{$setting.style}"  name="{$vo.fieldname}" value="{$Site[$vo['fieldname']]}">
          {/case}

          {case value="select"}
          <select name="{$vo.fieldname}">
          {volist name="setting['option']" id="rs"}
               <option value="{$rs.value}" {if condition=" $Site[$vo['fieldname']] == $rs['value'] "}selected{/if}>{$rs.title}</option>
          {/volist}
          </select>
          {/case}

          {case value="textarea"}
          <textarea name="{$vo.fieldname}" style="{$setting.style}">{$Site[$vo['fieldname']]}</textarea>
          {/case}

          {case value="radio"}
          {volist name="setting['option']" id="rs"}
          <input name="{$vo.fieldname}" value="{$rs.value}" type="radio"  {if condition=" $Site[$vo['fieldname']] == $rs['value'] "}checked{/if}> {$rs.title}
          {/volist}
          {/case}

          {case value="password"}
          <input type="password" class="input" style="{$setting.style}"  name="{$vo.fieldname}" value="{$Site[$vo['fieldname']]}">
          {/case}
          {/switch}
          </td>
          <td>{$setting.tips}</td>
          <td></td>
        </tr>
        {/volist}
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
{/block}