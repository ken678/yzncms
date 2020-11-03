layui.define(['layer', 'laytpl', 'form', 'element', 'upload', 'util','yznForm'], function(exports) {
    var $ = layui.jquery,
        layer = layui.layer,
        laytpl = layui.laytpl,
        form = layui.form,
        element = layui.element,
        upload = layui.upload,
        util = layui.util,
        yznForm=layui.yznForm,
        device = layui.device(),
        DISABLED = 'layui-btn-disabled';

    //阻止IE7以下访问
    if (device.ie && device.ie < 10) {
        layer.alert('如果您非得使用 IE 浏览器访问，那么请使用 IE10+');
    }

    $('.lay-tips').hover(function() {
        var title = $(this).attr('title');
        layer.tips(title, $(this))
    }, function() {
        layer.closeAll('tips')
    })


    layui.focusInsert = function(obj, str) {
        var result, val = obj.value;
        obj.focus();
        if (document.selection) { //ie
            result = document.selection.createRange();
            document.selection.empty();
            result.text = str;
        } else {
            result = [val.substring(0, obj.selectionStart), str, val.substr(obj.selectionEnd)];
            obj.focus();
            obj.value = result.join('');
        }
    };

    //显示当前tab
    if (location.hash) {
        element.tabChange('user', location.hash.replace(/^#/, ''));
    }
    element.on('tab(user)', function() {
        var othis = $(this),
            layid = othis.attr('lay-id');
        if (layid) {
            location.hash = layid;
        }
    });

    form.on('radio(type)', function(data) {
        var type = data.value;
        if (type == 'email') {
            $("input[name='mobile']").attr('lay-verify', '');
            $("input[name='email']").attr('lay-verify', 'email|required');

        }
        if (type == 'mobile') {
            $("input[name='email']").attr('lay-verify', '');
            $("input[name='mobile']").attr('lay-verify', 'phone|required');

        }
        $("div.layui-form-item[data-type]").addClass("layui-hide");
        $("div.layui-form-item[data-type='" + type + "']").removeClass("layui-hide");
        $(".btn-captcha").data("url", $(this).data("send-url")).data("type", type);
    });

    //发送验证码
    $(document).on("click", ".btn-captcha", function(e) {
        var that = this;
        var type = $(that).data("type") ? $(that).data("type") : 'mobile';
        var element = $("input[name='" + type + "']");
        var si = {};

        var data = { event: $(that).data("event") };
        data[type] = element.val();

        $(that).attr('disabled', true).text("发送中...");

        $.post($(that).data("url"), data, function(data) {
            if (data.code == 1) {
                var seconds = 120;
                si[type] = setInterval(function() {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(si[type]);
                        $(that).removeClass("layui-btn-disabled").text("获取验证码").attr("disabled", false);
                    } else {
                        $(that).addClass("layui-btn-disabled").text(seconds + "秒后可发送");
                    }
                }, 1000);
            } else {
                $(that).removeClass("layui-btn-disabled").text("获取验证码").attr("disabled", false);
                layer.msg(data.msg);
            }

        })
        return false;
    })

    //加载特定模块
    if (layui.cache.page && layui.cache.page !== 'index') {
        var extend = {};
        extend[layui.cache.page] = layui.cache.page;
        layui.extend(extend);
        layui.use(layui.cache.page);
    }

    //手机设备的简单适配
    var treeMobile = $('.site-tree-mobile'),
        shadeMobile = $('.site-mobile-shade')

    treeMobile.on('click', function() {
        $('body').addClass('site-mobile');
    });

    shadeMobile.on('click', function() {
        $('body').removeClass('site-mobile');
    });
    exports('fly', {});

});