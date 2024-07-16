define(['jquery', 'table', 'form'], function($, Table, Form) {
    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                add_url: "user.user/add",
                edit_url: "user.user/edit",
                delete_url: "user.user/del",
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'add', 'delete'],
                url: 'user.user/index',
                lineStyle: 'height: 45px;',
                cols: [
                    [
                        { type: 'checkbox', fixed: 'left' },
                        { field: 'id', width: 90, title: '用户ID' },
                        { field: 'group.name', width: 110, title: '用户组' },
                        { field: 'username', width: 150, title: '用户名', templet: '<div>{{#  if(d.vip>0){ }} <span class="text-danger">[VIP]</span> {{#  } }}{{ d.username }}</div>', searchOp: 'like' },
                        { field: 'nickname', width: 150, title: '昵称', searchOp: 'like' },
                        { field: 'avatar', width: 70, align: "center", title: '头像', search: false, templet: Table.formatter.image },
                        { field: 'mobile', width: 120, title: '手机', searchOp: 'like' },
                        { field: 'email',  width: 180,title: '邮箱', searchOp: 'like' },
                        { field: 'reg_ip',  width: 120,title: '注册IP' },
                        { field: 'last_login_time', width: 170, title: '最后登录', search: 'range', templet: Table.formatter.datetime },
                        { field: 'amount', width: 90, title: '金钱总数' },
                        { field: 'point', width: 90, title: '积分总数' },
                        { field: 'login', width: 90, title: '登录次数' },
                        { field: 'status', width: 80, title: '状态', templet: Table.formatter.status, selectList: { 0: '禁用', 1: '正常' }, search: false },
                        { fixed: 'right', width: 90, title: '操作', templet: Table.formatter.tool, operat: ['edit', 'delete'] }
                    ]
                ],
                page: {}
            });

            Table.api.bindevent();
        },
        userverify: function() {

            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                edit_url: "user.user/edit",
                delete_url: "user.user/del",
                multi_url: 'user.user/multi',
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'delete',
                    [{
                        text: '审核通过',
                        auth: 'pass',
                        icon: 'iconfont icon-check-line',
                        class: 'layui-btn layui-btn-sm layui-btn-disabled btn-disabled',
                        extend: 'data-params="status=1" lay-event="btn-multi"',
                    }],
                ],
                url: 'user.user/userverify',
                lineStyle: 'height: 45px;',
                cols: [
                    [
                        { type: 'checkbox', fixed: 'left' },
                        { field: 'id', width: 100, title: '用户ID' },
                        { field: 'username', width: 150, title: '用户名', searchOp: 'like' },
                        { field: 'nickname', width: 150, title: '昵称', searchOp: 'like' },
                        { field: 'avatar', width: 70, align: "center", title: '头像', search: false, templet: Table.formatter.image },
                        { field: 'mobile', width: 120, title: '手机', searchOp: 'like' },
                        { field: 'email', title: '邮箱', searchOp: 'like' },
                        { field: 'reg_ip', title: '注册IP' },
                        { field: 'last_login_time', title: '最后登录', search: 'range', templet: Table.formatter.datetime },
                        { field: 'login', width: 100, title: '登录次数' },
                        { fixed: 'right', width: 90, title: '操作', templet: Table.formatter.tool, operat: ['edit', 'delete'] }
                    ]
                ]
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