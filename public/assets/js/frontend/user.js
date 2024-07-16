define(['jquery', 'frontend', 'form'], function($, Frontend, Form) {

    var Controller = {
        login: function() {
            //为表单绑定事件
            Form.api.bindevent($("form.layui-form"), function(data, ret) {
                setTimeout(function() {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            }, function(res) {
                //刷新验证码
                $("#verify").click();
                //layer.msg(res.msg, { icon: 5 });
            });

            //刷新验证码
            $("#verify").click(function() {
                var verifyimg = $("#verify").attr("src");
                $("#verify").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
            });

        },
        register: function() {
            //为表单绑定事件
            Form.api.bindevent($("form.layui-form"), function(data, ret) {
                setTimeout(function() {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            }, function(res) {
                //刷新验证码
                $("#verify").click();
                //layer.msg(res.msg, { icon: 5 });
            });

            //刷新验证码
            $("#verify").click(function() {
                var verifyimg = $("#verify").attr("src");
                $("#verify").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
            });
        },
        forget: function() {
            Form.api.bindevent($("form.layui-form"), function(data, ret) {
                Layer.msg(res.msg, {
                    offset: '15px',
                    icon: 1,
                    time: 1000
                }, function() {
                    window.location.href = res.url;
                });
                return false;
            });

            layui.form.on('radio(type)', function(data) {
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
        },
        index: function() {

        },
        upgrade: function() {
            $(document).on("click", ".layui-btn", function(e) {
                Yzn.api.ajax('user/upgrade?id=' + $(this).data("id"), function(data) {
                    Layer.msg("开通成功!", {
                        icon: 1,
                        time: 1500
                    }, function() {
                        location.reload();
                    });
                })
                return false;
            })
        },
        changepwd: function() {
            //为表单绑定事件
            Form.api.bindevent($("#changepwd-form"), function(data, ret) {
                setTimeout(function() {
                    location.href = ret.url ? ret.url : "/";
                }, 1000);
            });
        },
        profile: function() {
            // 给上传按钮添加上传成功事件
            $("#faupload-avatar").data("upload-success", function(data) {
                var url = Yzn.api.cdnurl(data.url);
                $(".profile-user-img,.fly-avatar").prop("src", url);
                Toastr.success('上传成功');
            });
            Form.api.bindevent($("#profile-form"));


            $(document).on("click", ".btn-change", function() {
                var that = this;
                var id = $(this).data("type") + "Tpl";
                var content = layui.laytpl($("#" + id).html()).render()
                Layer.open({
                    type: 1,
                    title: $(this).data("title"),
                    area: [$(window).width() < 450 ? ($(window).width() - 10) + "px" : "450px", "320px"],
                    content: content,
                    success: function(layero) {
                        var form = $("form", layero);
                        Form.api.bindevent(form, function(data) {
                            location.reload();
                            Layer.closeAll();
                        });
                    }
                });
            });
        },
        attachment: function() {
            require(['table'], function(Table) {
                var multiple = Yzn.api.query('multiple');
                multiple = multiple == 'true' ? true : false;

                var toolbar = ['refresh', [{
                    html: '<button type="button" class="layui-btn layui-btn-sm faupload" data-multiple="true" data-mimetype="' + Config.mimetype + '"><i class="iconfont icon-upload-line"></i> 上传</button>'
                }]];
                if (multiple) {
                    toolbar.push([{
                        html: '<button class="layui-btn layui-btn-danger layui-btn-sm btn-choose-multi"><i class="iconfont icon-check-line"></i> 选择</button>'
                    }]);
                }

                Table.init = {
                    table_elem: '#currentTable',
                    table_render_id: 'currentTable',
                };

                Table.render({
                    init: Table.init,
                    toolbar: toolbar,
                    elem: '#currentTable',
                    url: 'user/attachment',
                    lineStyle: 'height: 60px;',
                    cols: [
                        [
                            { type: 'checkbox', fixed: 'left', hide: (multiple ? false : true) },
                            { field: 'id', width: 60, title: 'ID', sort: true },
                            { field: 'name', title: '名称' },
                            {
                                field: 'path',
                                width: 100,
                                align: "center",
                                title: '图片',
                                search: false,
                                templet: function(d) {
                                    var html = '';
                                    if (d.mime.indexOf("image") > -1) {
                                        html = '<a href="' + d.path + '" target="_blank"><img src="' + d.path + '" alt="" style="max-height:40px;max-width:70px"></a>';
                                    } else {
                                        html = '<a href="' + d.path + '" target="_blank"><img src="' + Yzn.api.fixurl("ajax/icon") + "?suffix=" + d.ext + '" alt="" style="max-height:40px;max-width:70px"></a>';
                                    }
                                    return '<div style="width:70px;margin:0 auto;text-align:center;overflow:hidden;white-space: nowrap;text-overflow: ellipsis;">' + html + '</div>';
                                }
                            },
                            { field: 'size', width: 100, title: '大小', sort: true },
                            { field: 'mime', width: 100, title: 'Mime类型' },
                            { field: 'create_time', width: 160, title: '上传时间', search: 'range' },
                            { fixed: 'right', width: 70, title: '操作', toolbar: '#barTool' }
                        ]
                    ],
                    page: {},
                    done: function(res, curr, count) {
                        require(['upload'], function(Upload) {
                            Upload.api.upload('.faupload', function() {
                                $(".btn-refresh").trigger("click");
                            });
                        });
                    }
                });

                Table.api.bindevent();

                layui.table.on('checkbox', function(obj) {
                    urlArr = [];
                    var checkStatus = layui.table.checkStatus('currentTable').data;
                    for (var i = 0; i < checkStatus.length; i++) {
                        urlArr.push(checkStatus[i]['path']);
                    }
                });


                //选择单个
                $(document).on('click', '.btn-chooseone', function() {
                    var that = $(this),
                        index = that.parents('tr').eq(0).data('index'),
                        tr = $('.layui-table-body').find('tr[data-index="' + index + '"]'),
                        href = !that.attr('data-href') ? that.attr('href') : that.attr('data-href');
                    Yzn.api.close({ url: href, multiple: multiple });
                });


                // 选中多个 todo翻页失效
                $(document).on("click", ".btn-choose-multi", function() {
                    Yzn.api.close({ url: urlArr.join(","), multiple: multiple });
                });
            })

        }
    };
    return Controller;
});