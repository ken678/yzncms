layui.use(['form', 'layer', 'jquery'], function() {
    var form = layui.form,
        $ = layui.jquery;
    //登录
    form.on('submit(*)', function(data) {
        var action = $(data.form).attr('action');
        $.post(action, $(data.form).serialize(), success, "json");
        return false;

        function success(data) {
            if (data.code) {
                //window.location.href = data.url;
            } else {
                //layer.msg(data.msg, { icon: 5 });
                //刷新验证码
                //$(".verifyimg img").click();
            }
        }
    });

    //表单输入效果
    $(".login-main .input-item").click(function(e) {
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    })
    $(".login-main .input-item .layui-input").focus(function() {
        $(this).parent().addClass("layui-input-focus");
    })
    $(".login-main .input-item .layui-input").blur(function() {
        $(this).parent().removeClass("layui-input-focus");
        if ($(this).val() != '') {
            $(this).parent().addClass("layui-input-active");
        } else {
            $(this).parent().removeClass("layui-input-active");
        }
    })
})