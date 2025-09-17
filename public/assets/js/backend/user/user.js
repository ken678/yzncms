define(['jquery', 'table', 'form'], function($, Table, Form) {
    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                add_url: "user.user/add",
                edit_url: "user.user/edit",
                delete_url: "user.user/del",
                multi_url: 'user.user/multi',
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'add', 'delete', {
                    text: '更多',
                    auth: 'multi',
                    icon: 'iconfont icon-more-line',
                    class: 'layui-btn layui-btn-sm layui-btn-primary layui-border btn-disabled layui-btn-disabled',
                    extend: 'id="dropdown-more"',
                }],
                url: 'user.user/index',
                lineStyle: 'height: 45px;',
                cols: [
                    [
                        { type: 'checkbox', fixed: 'left' },
                        { field: 'id', width: 90, title: '用户ID' },
                        { field: 'group.name', width: 110, title: '用户组' },
                        { field: 'username', width: 150, title: '用户名', templet: '<div>{{#  if(d.vip>0){ }} <span class="text-danger">[VIP]</span> {{#  } }}{{ d.username }}</div>', searchOp: 'like' },
                        { field: 'nickname', title: '昵称', searchOp: 'like' },
                        { field: 'avatar', width: 70, align: "center", title: '头像', search: false, templet: Table.formatter.image },
                        { field: 'mobile', width: 120, title: '手机', searchOp: 'like' },
                        { field: 'email',  width: 180,title: '邮箱', searchOp: 'like' },
                        { field: 'reg_ip',  width: 120,title: '注册IP' },
                        { field: 'last_login_time', width: 160, title: '最后登录', search: 'range', templet: Table.formatter.datetime },
                        { field: 'amount', width: 90, title: '金钱总数' },
                        { field: 'point', width: 90, title: '积分总数' },
                        { field: 'status', width: 80, title: '状态', templet: Table.formatter.status, selectList: { 0: '禁用', 1: '正常' }, search: false },
                        { fixed: 'right', width: 90, title: '操作', templet: Table.formatter.tool, operat: ['edit', 'delete'] }
                    ]
                ],
                page: {},
                done: function() {
                    //更多操作
                    layui.dropdown.render({
                        elem: '#dropdown-more',
                        data: [{
                            title: '设置正常',
                            templet: '<i class="iconfont icon-eye-line"></i>&nbsp;设置正常',
                            params:'status=1'
                        }, {
                            title: '设置禁用',
                            templet: '<i class="iconfont icon-eye-close-line"></i>&nbsp;设置禁用',
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