define(['jquery', 'table', 'form'], function($, Table, Form) {
    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                add_url: 'auth.manager/add',
                edit_url: 'auth.manager/edit',
                delete_url: 'auth.manager/del',
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'add'],
                url: 'auth.manager/index',
                cols: [
                    [
                        { field: 'id', width: 80, title: 'ID' },
                        { field: 'username', width: 120, title: '登录名', searchOp: 'like' },
                        { field: 'groups', width: 120, title: '所属角色', search: false },
                        { field: 'last_login_ip', title: '最后登录IP', searchOp: 'like' },
                        { field: 'last_login_time', width: 200, title: '最后登录时间', search: 'range' },
                        { field: 'email', width: 200, title: '邮箱', searchOp: 'like' },
                        { field: 'mobile', width: 200, title: '手机', searchOp: 'like' },
                        { field: 'nickname', title: '真实姓名', searchOp: 'like' },
                        {
                            width: 100,
                            title: '操作',
                            templet: function(d) {
                                if (d.id == Config.admin.id) {
                                    return '<a class="layui-btn layui-btn-xs layui-btn-danger layui-btn-disabled">不可操作</a>';
                                } else {
                                    return Table.formatter.tool.call(this, d, this);
                                }
                            },
                            operat: ['edit', 'delete']
                        }
                    ]
                ],
                page: {}
            });

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
                Form.api.bindevent($("form.layui-form"));
            }
        }
    };
    return Controller;
});