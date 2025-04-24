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

            layui.treeTable.render({
                init: Table.init,
                elem: Table.init.table_elem,
                toolbar: '#toolbarDemo',
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
                // @todo 不直接使用yznTable.render(); 进行表格初始化, 需要使用 Table.formatCols(); 方法格式化`cols`列数据
                cols: Table.formatCols([
                    [
                        { field: 'listorder', width: 60, title: '排序', edit: 'text' },
                        { field: 'id', width: 60, title: 'ID' },
                        { field: 'title', align: 'left', title: '菜单名称', },
                        { width: 80, title: '图标', align: 'center', templet: "<div><i class='iconfont {{d.icon}}'></i></div>" },
                        { field: 'name', width: 200, title: '规则' },
                        { field: 'status', align: 'center', width: 120, title: '状态', unresize: true, templet: Table.formatter.switch, tips: "显示|隐藏" },
                        {
                            fixed: 'right', 
                            width: 140,
                            title: '操作',
                            templet: Table.formatter.tool, 
                            operat: [
                                [{
                                    text: '添加',
                                    url: Table.init.add_url+'?parentid={id}',
                                    auth:'add',
                                    class: 'layui-btn layui-btn-xs layui-btn-normal btn-dialog',
                                }],
                                'edit', 'delete']
                        }
                    ]
                ], Table.init),
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

                Form.api.bindevent($("form.layui-form"));

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