/* 显示Ajax表单 */
function ajax_form(type, title, url, width,height){
    if (!width) width = '480px';
    if (!height) height = '300px';
    if (!type) type = 1;
    $.post(url, {}, function(str){
        layer.open({
          title:title,
          area: [width,height],
          type: type,
          content: str
        });
    });
}

$(document).ready(function () {
    //鼠标移动tips提示
    $(".itip").each(function(i,n){
        $(n).hover(function(){itip($(n).attr("alt"),$(n),undefined,$(n).hasClass("ifixed"));},function(){layer.closeAll('tips');});
    });
})
function itip(msg, selector, ipoint, ifixed){ipoint=ipoint==undefined?1:ipoint;layer.tips(msg,selector,{tips:[ipoint,'#333'],fixed:ifixed,time:6000});}

/*
 * 为低版本IE添加placeholder效果
 *
 * 使用范例：
 * [html]
 * <input id="captcha" name="captcha" type="text" placeholder="验证码" value="" >
 * [javascrpt]
 * $("#captcha").nc_placeholder();
 *
 * 生效后提交表单时，placeholder的内容会被提交到服务器，提交前需要把值清空
 * 范例：
 * $('[data-placeholder="placeholder"]').val("");
 * $("#form").submit();
 *
 */
(function($) {
    $.fn.nc_placeholder = function() {
        var isPlaceholder = 'placeholder' in document.createElement('input');
        return this.each(function() {
            if(!isPlaceholder) {
                $el = $(this);
                $el.focus(function() {
                    if($el.attr("placeholder") === $el.val()) {
                        $el.val("");
                        $el.attr("data-placeholder", "");
                    }
                }).blur(function() {
                    if($el.val() === "") {
                        $el.val($el.attr("placeholder"));
                        $el.attr("data-placeholder", "placeholder");
                    }
                }).blur();
            }
        });
    };
})(jQuery);