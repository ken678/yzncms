define(['yzn'], function(Yzn) {
    var Frontend = {
        api: Yzn.api,
        init: function() {
            var si = {};
            //发送验证码
            $(document).on("click", ".btn-captcha", function (e) {
                var type = $(this).data("type") ? $(this).data("type") : 'mobile';
                var btn = this;
                Frontend.api.sendcaptcha = function (btn, type, data, callback) {
                    $(btn).addClass("disabled", true).text("发送中...");

                    Frontend.api.ajax({url: $(btn).data("url"), data: data}, function (data, ret) {
                        clearInterval(si[type]);
                        var seconds = 60;
                        si[type] = setInterval(function () {
                            seconds--;
                            if (seconds <= 0) {
                                clearInterval(si);
                                $(btn).removeClass("disabled").text("发送验证码");
                            } else {
                                $(btn).addClass("disabled").text(seconds + "秒后可再次发送");
                            }
                        }, 1000);
                        if (typeof callback == 'function') {
                            callback.call(this, data, ret);
                        }
                    }, function () {
                        $(btn).removeClass("disabled").text('发送验证码');
                    });
                };
                if (['mobile', 'email'].indexOf(type) > -1) {
                    var element = $(this).data("input-id") ? $("#" + $(this).data("input-id")) : $("input[name='" + type + "']", $(this).closest("form"));
                    var text = type === 'email' ? '邮箱' : '手机号码';
                    if (element.val() === "") {
                        Layer.msg(text + "不能为空！",{icon:2});
                        element.focus();
                        return false;
                    } else if (type === 'mobile' && !element.val().match(/^1[3-9]\d{9}$/)) {
                        Layer.msg("请输入正确的" + text + "！",{icon:2});
                        element.focus();
                        return false;
                    } else if (type === 'email' && !element.val().match(/^[\w\+\-]+(\.[\w\+\-]+)*@[a-z\d\-]+(\.[a-z\d\-]+)*\.([a-z]{2,4})$/)) {
                        Layer.msg("请输入正确的" + text + "！",{icon:2});
                        element.focus();
                        return false;
                    }

                    var data = {event: $(btn).data("event")};
                    data[type] = element.val();
                    Frontend.api.sendcaptcha(btn, type, data);

                } else {
                    var data = {event: $(btn).data("event")};
                    Frontend.api.sendcaptcha(btn, type, data, function (data, ret) {
                        Layer.open({title: false, area: ["400px", "430px"], content: "<img src='" + data.image + "' width='400' height='400' /><div class='text-center panel-title'>扫一扫关注公众号获取验证码</div>", type: 1});
                    });
                }
                return false;
            });

            //阻止IE7以下访问
            if (layui.device.ie && layui.device.ie < 10) {
                Layer.alert('如果您非得使用 IE 浏览器访问，那么请使用 IE10+');
            }


            $('.lay-tips').hover(function() {
                var title = $(this).attr('title');
                Layer.tips(title, $(this))
            }, function() {
                Layer.closeAll('tips')
            })


            //手机设备的简单适配
            var treeMobile = $('.site-tree-mobile'),
                shadeMobile = $('.site-mobile-shade')

            treeMobile.on('click', function() {
                $('body').addClass('site-mobile');
            });

            shadeMobile.on('click', function() {
                $('body').removeClass('site-mobile');
            });

        }
    };
    Frontend.api = $.extend(Yzn.api, Frontend.api);
    //将Frontend渲染至全局,以便于在子框架中调用
    window.Frontend = Frontend;

    Frontend.init();
    return Frontend;
});