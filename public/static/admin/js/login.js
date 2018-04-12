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

    var config = {
        vx: 4, //小球x轴速度,正为右，负为左
        vy: 4, //小球y轴速度
        height: 2, //小球高宽，其实为正方形，所以不宜太大
        width: 2,
        count: 200, //点个数
        color: "121, 162, 185", //点颜色
        stroke: "130,255,255", //线条颜色
        dist: 6000, //点吸附距离
        e_dist: 20000, //鼠标吸附加速距离
        max_conn: 10 //点到点最大连接数
    }
    //调用
    CanvasParticle(config);

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