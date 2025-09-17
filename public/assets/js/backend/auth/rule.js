define(['jquery', 'table', 'form', 'iconPicker'], function($, Table, Form, iconPicker) {

    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                multi_url: 'auth.rule/multi',
                edit_url: 'auth.rule/edit',
                add_url: 'auth.rule/add',
                delete_url: 'auth.rule/del',
            };

            Table.render({
                init: Table.init,
                elem: Table.init.table_elem,
                search:false,
                toolbar: ['refresh', 'add', 'edit', 'delete', {
                    text: '更多',
                    auth: 'multi',
                    icon: 'iconfont icon-more-line',
                    class: 'layui-btn layui-btn-sm layui-btn-primary layui-border btn-disabled layui-btn-disabled',
                    extend: 'id="dropdown-more"',
                },
                {
                    text: '展开或折叠全部',
                    icon: 'iconfont icon-add-fill',
                    class: 'layui-btn layui-btn-sm',
                    extend: 'id="openAll"',
                }],
                url: "auth.rule/index",
                pk:'id',
                tree: {
                    customName: {
                        children: 'childlist',
                        pid: 'parentid',
                        name: 'title'
                    },
                    view: {
                        showIcon: false
                    }
                },
                cols: [
                    [
                        { type: 'checkbox', fixed: 'left' },
                        { field: 'listorder', width: 60, title: '排序', edit: 'text' },
                        { field: 'id', width: 60, title: 'ID' },
                        { field: 'title', minWidth: 150, align: 'left', title: '菜单名称', },
                        { width: 80, title: '图标', align: 'center', templet: "<div><i class='iconfont {{d.icon}}'></i></div>" },
                        { field: 'name', width: 200, title: '规则' },
                        { field: 'status', align: 'center', width: 120, title: '状态', unresize: true, templet: Table.formatter.switch, tips: "显示|隐藏" },
                        {
                            fixed: 'right', 
                            width: 140,
                            title: '操作',
                            templet: Table.formatter.tool, 
                            operat: [
                                {
                                    text: '添加',
                                    url: Table.init.add_url+'?parentid={id}',
                                    auth:'add',
                                    class: 'layui-btn layui-btn-xs layui-btn-normal btn-dialog',
                                },
                                'edit', 'delete']
                        }
                    ]
                ],
                done: function(res, curr, count) {
                    $('.layui-btn[lay-event="btn-delone"]').data("success", function (data, ret) {
                        Yzn.api.refreshmenu();
                        layui.table.reload(Table.init.table_render_id);
                        return false;
                    });

                    //更多操作
                    layui.dropdown.render({
                        elem: '#dropdown-more',
                        data: [{
                            title: '设置显示',
                            templet: '<i class="iconfont icon-eye-line"></i>&nbsp;设置显示',
                            params:'status=1'
                        }, {
                            title: '设置隐藏',
                            templet: '<i class="iconfont icon-eye-close-line"></i>&nbsp;设置隐藏',
                            params:'status=0'
                        }],
                        click: function(obj) {
                            tableId = Table.init.table_render_id;
                            var ids = Table.api.selectedids(tableId);
                            if (ids.length <= 0) {
                                Toastr.error('请勾选需要操作的数据');
                                return false;
                            }
                            Yzn.api.ajax({ 
                                url: Table.init.multi_url, 
                                data: { id: ids, param: obj.params } 
                            }, function(data, ret) {
                                layui.table.reload(tableId);
                            });
                        }
                    });
                }
            });

            $('body').on('click', '#openAll', function() {
                var that = this;
                var show = $("i", that).hasClass("icon-add-fill");
                layui.treeTable.expandAll(Table.init.table_render_id, show);
                $("i", that).toggleClass("icon-add-fill", !show);
                $("i", that).toggleClass("icon-subtract-fill", show);
            })

            Table.api.bindevent();
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                iconPicker.render({
                    // 选择器，推荐使用input
                    elem: '#iconPicker',
                    // 数据类型：fontClass/unicode，推荐使用fontClass
                    type: 'fontClass',
                    // 是否开启搜索：true/false，默认true
                    search: true,
                    // 是否开启分页：true/false，默认true
                    page: true,
                    // 每页显示数量，默认12
                    limit: 12,
                    // 点击回调
                    click: function(data) {
                        //console.log(data);
                    },
                    // 渲染成功后的回调
                    success: function(d) {
                        //console.log(d);
                    }
                });

                Form.api.bindevent($("form.layui-form"), function (data) {
                    Yzn.api.refreshmenu();
                });

                layui.form.on('radio(ismenu)', function(data) {
                    var name = $("input[name='row[name]']");
                    var ismenu = data.value == 1;
                    name.prop("placeholder", ismenu ? name.data("placeholder-menu") : name.data("placeholder-node"));
                    $('div[data-type="menu"]').toggleClass("layui-hide", !ismenu);
                    
                });

                $("input[name='row[ismenu]']:checked").next().trigger("click");


            }
        }
    };
    return Controller;
});