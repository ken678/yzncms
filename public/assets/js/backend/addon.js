define(['jquery', 'backend', 'table', 'form', 'layui', 'upload'], function($, Backend, Table, Form, layui, Upload) {
    var table = layui.table;

    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                layFilter: 'currentTable_LayFilter',
            };

            var area = Yzn.config.openArea != undefined ? Yzn.config.openArea : [$(window).width() > 800 ? '800px' : '95%', $(window).height() > 600 ? '600px' : '95%'];

            var switch_local = function() {
                Layer.confirm('插件市场暂不可用，是否切换到本地插件？', {
                    title: '温馨提示',
                    btn: ['切换到本地插件', '重新尝试加载']
                }, function(index) {
                    Layer.close(index);
                    $(".btn-switch[data-type='local']")[0].click();
                }, function(index) {
                    Layer.close(index);
                    table.reload(Table.init.table_render_id);
                });
                return false;
            };

            var button_online = Config.addon.type == 'online' ? "layui-btn-normal" : "layui-btn-primary layui-border-blue";
            var button_local = Config.addon.type == 'local' ? "layui-btn-normal" : "layui-btn-primary layui-border-blue";

            Table.render({
                init: Table.init,
                toolbar: ['refresh',
                    [{
                        text: '本地安装',
                        url:"",
                        icon: 'iconfont icon-upload-fill',
                        class: 'layui-btn layui-btn-sm faupload',
                        extend: 'id="faupload-addon" data-type="file" data-url="addon/local" data-chunking="false" data-mimetype="zip" data-multiple="false"',
                    },{
                        text: '全部',
                        url:"addon/index",
                        icon: 'iconfont icon-list-unordered',
                        class: 'layui-btn layui-btn-sm btn-switch ' + button_online,
                        extend: 'data-type="all" style="margin-left: 10px !important;"',
                    },{
                        text: '本地插件',
                        url:"addon/index?type=local",
                        icon: 'iconfont icon-apps-line',
                        class: 'layui-btn layui-btn-sm btn-switch ' + button_local,
                        extend: 'data-type="local"',
                    },{
                        text: '会员信息',
                        icon: 'iconfont icon-user-line',
                        class: 'layui-btn layui-bg-black layui-btn-sm btn-userinfo',
                        extend: 'style="margin-left: 10px !important;"',
                    }],
                ],
                url: 'addon/index' + (Config.addon.type == "local" ? "?type=local" : ""),
                css: '.layui-table-tool-temp{padding-right: 85px;}',
                showSearch: false,
                cols: [
                    [
                        { field: 'title', width: 200, title: '名称', templet: '#titleTpl' },
                        { field: 'name', width: 150, title: '标识' },
                        { field: 'description', title: '描述' },
                        { field: 'author', width: 90, title: '作者' },

                        {
                            field: 'price',
                            width: 95,
                            title: '价格',
                            hide: (Config.addon.type == "local" ? true : false),
                            templet: function(d) {
                                if (isNaN(d.price)) {
                                    return d.price;
                                }
                                return parseFloat(d.price) == 0 ? '<span class="text-success">' + '免费' + '</span>' : '<span class="text-danger">￥' + d.price + '</span>';
                            }
                        },
                        { field: 'downloads', width: 90, title: '下载', hide: (Config.addon.type == "local" ? true : false) },

                        {
                            field: 'version',
                            width: 80,
                            title: '版本',
                            templet: function(d) {
                                return d.addon && d.addon.version != d.version ? '<a href="javascript:;"><span class="releasetips text-primary" lay-tips="发现新版本:' + d.version + '">' + d.addon.version + '<i></i></span></a>' : d.version;
                            }
                        },
                        { field: 'status', width: 90, title: '状态', templet: '#statusTpl' },
                        { fixed: 'right', width: 180, title: '操作', templet: '#operateTpl' }
                    ]
                ],
                page: { limit: 20 },
                before() {
                    let userinfo = Yzn.cache.getStorage('yzncms_userinfo');
                    this.where.uid = userinfo ? userinfo.id : '';
                    this.where.token = userinfo ? userinfo.token : '';
                    this.where.category_id = $('input[name="category_id"]').val();
                },
                done: function(res, curr, count) {
                    if (res && typeof res.category != 'undefined' && $(".nav-category li").length == 1) {
                        $.each(res.category, function(i, j) {
                            $("<li data-id='" + j.id + "'>" + j.title + "</li>").insertAfter($(".nav-category li:first"));
                        });
                    }
                    if (count == -1) {
                        switch_local();
                    }
                    Upload.api.upload("#faupload-addon", function(data, ret) {
                        //上传完毕回调
                        var addon = data.addon;
                        var testdata = data.addon.testdata;
                        operate(addon.name, 'enable', false, function(data, ret) {
                            Layer.alert('安装成功！清除浏览器缓存和框架缓存后生效！' + (testdata ? '<br>你还可以继续导入测试数据！' : ""), {
                                btn: testdata ? ['导入测试数据', '暂不导入'] : ['确定'],
                                title: '温馨提示',
                                yes: function(index) {
                                    if (testdata) {
                                        Yzn.api.ajax({
                                            url: 'addon/testdata',
                                            data: {
                                                name: addon.name,
                                            }
                                        }, function(data, res) {
                                            Layer.close(index);
                                        });
                                    } else {
                                        Layer.close(index);
                                    }
                                },
                                icon: 1
                            });
                        });
                        return false;
                    }, function(data, ret) {
                        if (ret.msg && ret.msg.match(/(login|登录)/g)) {
                            return Layer.alert(ret.msg, {
                                title: '温馨提示',
                                btn: ['立即登录'],
                                yes: function(index, layero) {
                                    $(".btn-userinfo").trigger("click");
                                }
                            });
                        }
                    })
                }
            });
            Table.api.bindevent();
            // 检测是否登录
            $(document).on("mousedown", "#faupload-addon", function(e) {
                if (!isLogin()) return
            });

            table.on('tool(' + Table.init.layFilter + ')', function(obj) {
                var that = this;
                jsondata = $(this).data('value');
                var name = $(this).data("name");
                var title = $(this).data("title");
                const arr = jsondata.map(({ id, version }) => ({ id, title: version }))

                layui.dropdown.render({
                    elem: that,
                    show: true, // 外部事件触发即显示
                    data: arr,
                    click: function(data, othis) {

                        if (!isLogin()) return

                        if (obj.event === 'install') {
                            Layer.confirm(title, function(index) {
                                install(name, data.title, false);
                            });
                        }
                        if (obj.event === 'upgrade') {
                            Layer.confirm('确认升级<b>《' + title + '》</b>？<p class="text-danger">1、请务必做好代码和数据库备份！备份！备份！<br>2、升级后如出现冗余数据，请根据需要移除即可!<br>3、不建议在生产环境升级，请在本地完成升级测试</p>如有重要数据请备份后再操作！', function(index, layero) {
                                upgrade(name, data.title);
                            });
                        }
                    }
                })
            })

            layui.element.on('tab(tabswitch)', function(data) {
                var value = $(this).data("id");
                $('input[name="category_id"]').val(value);
                table.reload(Table.init.table_render_id);
            });

            // 会员信息
            $(document).on("click", ".btn-userinfo", function(e, name, version) {
                var that = this;
                var area = [$(window).width() > 800 ? '500px' : '95%', $(window).height() > 600 ? '400px' : '95%'];
                var userinfo = Yzn.cache.getStorage('yzncms_userinfo');
                if (!userinfo) {
                    Layer.open({
                        content: layui.laytpl($("#logintpl").html()).render({}),
                        zIndex: 99,
                        area: area,
                        title: '登录',
                        resize: false,
                        btn: ['登录', '注册'],
                        yes: function(index, layero) {
                            Yzn.api.ajax({
                                url: Config.addon.api_url + '/member/login',
                                type: 'post',
                                data: {
                                    account: $("#inputAccount", layero).val(),
                                    password: $("#inputPassword", layero).val(),
                                }
                            }, function(data, ret) {
                                Yzn.cache.setStorage('yzncms_userinfo', data.userinfo);
                                Layer.closeAll();
                                Layer.alert(ret.msg, { title: '温馨提示', icon: 1 });
                                return false;
                            }, function(data, ret) {

                            });
                        },
                        btn2: function() {
                            return false;
                        },
                        success: function(layero, index) {
                            this.checkEnterKey = function(event) {
                                if (event.keyCode === 13) {
                                    $(".layui-layer-btn0").trigger("click");
                                    return false;
                                }
                            };
                            $(document).on('keydown', this.checkEnterKey);
                            $(".layui-layer-btn1", layero).prop("href", "https://www.yzncms.com/member/index/register.html").prop("target", "_blank");
                        },
                        end: function() {
                            $(document).off('keydown', this.checkEnterKey);
                        }
                    });
                } else {
                    Yzn.api.ajax({
                        url: Config.addon.api_url + '/member/index',
                        data: {
                            uid: userinfo.id,
                            token: userinfo.token,
                        }
                    }, function(data) {
                        Layer.open({
                            content: layui.laytpl($("#userinfotpl").html()).render(userinfo),
                            area: area,
                            title: '会员信息',
                            resize: false,
                            btn: ['退出登录', '关闭'],
                            yes: function() {
                                Yzn.api.ajax({
                                    url: Config.addon.api_url + '/member/logout',
                                    data: { uid: userinfo.id, token: userinfo.token }
                                }, function(data, ret) {
                                    Yzn.cache.setStorage('yzncms_userinfo', '');
                                    Layer.closeAll();
                                    Layer.alert(ret.msg, { title: '温馨提示', icon: 0 });
                                }, function(data, ret) {
                                    Yzn.cache.setStorage('yzncms_userinfo', '');
                                    Layer.closeAll();
                                    Layer.alert(ret.msg, { title: '温馨提示', icon: 0 });
                                });
                            }
                        });
                        return false;
                    }, function(data) {
                        Yzn.cache.setStorage('yzncms_userinfo', '');
                        $(that).trigger('click');
                        return false;
                    });

                }
            });

            var install = function(name, version, force) {
                var userinfo = Yzn.cache.getStorage('yzncms_userinfo');
                var uid = userinfo ? userinfo.id : 0;
                var token = userinfo ? userinfo.token : '';
                Yzn.api.ajax({
                    url: 'addon/install',
                    data: {
                        name: name,
                        force: force ? 1 : 0,
                        uid: uid,
                        token: token,
                        version: version
                    },
                }, function(data, res) {
                    Layer.closeAll();
                    Layer.alert('安装成功！清除浏览器缓存和框架缓存后生效！' + (data.addon.testdata ? '<br>你还可以继续导入测试数据！' : ""), {
                        btn: data.addon.testdata ? ['导入测试数据', '暂不导入'] : ['确定'],
                        title: '温馨提示',
                        yes: function(index) {
                            if (data.addon.testdata) {
                                Yzn.api.ajax({
                                    url: 'addon/testdata',
                                    data: {
                                        name: name,
                                    }
                                }, function(data, res) {
                                    Layer.close(index);
                                });
                            } else {
                                Layer.close(index);
                            }
                            table.reload(Table.init.table_render_id);
                        },
                        icon: 1
                    });
                }, function(data, res) {
                    var area = [$(window).width() > 650 ? '650px' : '95%', $(window).height() > 710 ? '710px' : '95%'];
                    if (res && res.code === -2) {
                        Layer.closeAll();
                        top.Yzn.api.open('立即支付', res.data.payurl, '', '', {
                            area: area,
                            end: function() {
                                Yzn.api.ajax({
                                    url: 'addon/isbuy',
                                    data: {
                                        name: name,
                                        force: force ? 1 : 0,
                                        uid: uid,
                                        token: token,
                                        version: version
                                    }
                                }, function() {
                                    top.Layer.alert('购买成功！请点击继续安装按钮完成安装！', {
                                        btn: ['继续安装'],
                                        title: '温馨提示',
                                        icon: 1,
                                        yes: function(index) {
                                            top.Layer.close(index);
                                            install(name, version);
                                        }
                                    });
                                    return false;
                                }, function() {
                                    console.log('已取消');
                                    return false;
                                });
                            }
                        });
                    } else if (res && res.code === -3) {
                        //插件目录发现影响全局的文件
                        Layer.open({
                            content: layui.laytpl($("#conflicttpl").html()).render({ list: res.data }),
                            shade: 0.8,
                            area: area,
                            title: '温馨提示',
                            btn: ['继续操作', '取消'],
                            end: function() {

                            },
                            yes: function() {
                                install(name, version, true);
                            }
                        });
                    } else if (res && res.code === 401) {
                        Yzn.cache.setStorage('yzncms_userinfo', '');
                        Layer.alert('登录已经失效，请重新登录后操作！', {
                            title: '温馨提示',
                            btn: ['立即登录'],
                            yes: function(index, layero) {
                                $(".btn-userinfo").trigger("click");
                            },
                        });
                    } else {
                        Layer.alert(res.msg, { title: '温馨提示', icon: 0 });
                    }
                    return false;
                })
            }

            var uninstall = function(name, force, droptables) {
                Yzn.api.ajax({
                    url: 'addon/uninstall',
                    data: { name: name, force: force ? 1 : 0, droptables: droptables ? 1 : 0 }
                }, function(data, res) {
                    Layer.closeAll();
                    table.reload(Table.init.table_render_id);
                }, function(data, res) {
                    if (res && res.code === -3) {
                        //插件目录发现影响全局的文件
                        Layer.open({
                            content: layui.laytpl($("#conflicttpl").html()).render({ list: res.data }),
                            shade: 0.8,
                            area: area,
                            title: '温馨提示',
                            btn: ['继续操作', '取消'],
                            end: function() {

                            },
                            yes: function() {
                                uninstall(name, true, droptables);
                            }
                        });
                    } else {
                        Layer.alert(res.msg, { title: '温馨提示', icon: 0 });
                    }
                    return false;
                });
            };

            // 点击升级
            $(document).on("click", ".btn-upgrade", function() {
                var name = $(this).data('name');
                var version = $(this).data("version");
                var title = $(this).data("title");

                if (!isLogin()) return

                Layer.confirm('确认升级<b>《' + title + '》</b>？<p class="text-danger">1、请务必做好代码和数据库备份！备份！备份！<br>2、升级后如出现冗余数据，请根据需要移除即可!<br>3、不建议在生产环境升级，请在本地完成升级测试</p>如有重要数据请备份后再操作！', function(index, layero) {
                    upgrade(name, version);
                });
            });

            var upgrade = function(name, version) {
                var userinfo = Yzn.cache.getStorage('yzncms_userinfo');
                var uid = userinfo ? userinfo.id : 0;
                var token = userinfo ? userinfo.token : '';

                Yzn.api.ajax({
                    url: 'addon/upgrade',
                    data: {
                        name: name,
                        uid: uid,
                        token: token,
                        version: version
                    }
                }, function(data, ret) {
                    table.reload(Table.init.table_render_id);
                    Layer.closeAll();
                }, function(data, ret) {
                    Layer.alert(ret.msg, { title: '温馨提示' });
                    return false;
                });
            };

            var operate = function(name, action, force, success) {
                Yzn.api.ajax({
                    url: 'addon/state',
                    data: { name: name, action: action, force: force ? 1 : 0 }
                }, function(data, res) {
                    Layer.closeAll();
                    if (typeof success === 'function') {
                        success(res);
                    }
                    table.reload(Table.init.table_render_id);
                    return false;
                }, function(data, res) {
                    if (res && res.code === -3) {
                        //插件目录发现影响全局的文件
                        Layer.open({
                            content: layui.laytpl($("#conflicttpl").html()).render({ list: res.data }),
                            shade: 0.8,
                            area: area,
                            title: '温馨提示',
                            btn: ['继续操作', '取消'],
                            end: function() {

                            },
                            yes: function() {
                                operate(name, action, true, success);
                            }
                        });
                    } else {
                        Layer.alert(res.msg, { title: '温馨提示', icon: 0 });
                    }
                })
            };

            var isLogin = function() {
                var userinfo = Yzn.cache.getStorage('yzncms_userinfo');
                var uid = userinfo ? userinfo.id : 0;

                if (parseInt(uid) === 0) {
                    Layer.alert('你当前未登录Yzncms，请登录后操作！', {
                        title: '温馨提示',
                        btn: ['立即登录'],
                        yes: function(index, layero) {
                            $(".btn-userinfo").trigger("click");
                        },
                    });
                    return false;
                }
                return true;
            }

            var tables = [];
            $(document).on("click", "#droptables", function() {
                if ($(this).prop("checked")) {
                    Yzn.api.ajax({
                        url: 'addon/get_table_list',
                        async: false,
                        data: { name: $(this).data("name") }
                    }, function(res) {
                        tables = res.tables;
                        return false;
                    });
                    var html;
                    html = tables.length > 0 ? '<div class="alert alert-warning-light droptablestips" style="max-width:480px;max-height:300px;overflow-y: auto;">以下插件数据表将会被删除：<br>' + tables.join("<br>") + '<br>注意：部分插件还同时会删除演示数据和关联表等</div>' :
                        '<div class="alert alert-warning-light droptablestips">注意：部分插件还同时会删除演示数据和关联表等</div>';
                    $(html).insertAfter($(this).closest("p"));
                } else {
                    $(".droptablestips").remove();
                }
                $(window).resize();
            });

            // 点击安装
            $(document).on("click", ".btn-install", function() {
                var name = $(this).data("name");
                var title = $(this).data("title");
                var version = $(this).data("version");

                if (!isLogin()) return

                Layer.confirm(title, function(index) {
                    install(name, version, false);
                });
            });

            // 点击启用/禁用
            layui.form.on('switch(templet-status)', function(obj) {
                var name = $(this).data("name");
                var action = $(this).data("action");
                $(this).trigger('click');
                layui.form.render('checkbox');
                operate(name, action, false);
            })

            // 点击卸载
            $(document).on("click", ".btn-uninstall", function() {
                var name = $(this).data('name');
                var title = $(this).data('title');
                if ($(this).data('status') == 1) {
                    Layer.alert('请先禁用插件再进行卸载', { icon: 7 });
                    return false;
                }
                Layer.confirm(layui.laytpl($("#uninstalltpl").html()).render({ name: name, title: title }), function(index, layero) {
                    uninstall(name, false, $("input[name='droptables']", layero).prop("checked"));
                });
            });

        },
        config: function() {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form.layui-form"));
            }
        }
    };
    return Controller;
});