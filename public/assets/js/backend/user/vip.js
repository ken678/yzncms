define(['jquery', 'table', 'form'], function($, Table, Form) {
    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                add_url: "user.vip/add",
                edit_url: "user.vip/edit",
                delete_url: "user.vip/del",
                multi_url: 'user.vip/multi',
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'add'],
                url: 'user.vip/index?sort=listorder',
                search: false,
                cols: [
                    [
                        { field: 'id', width: 80, title: 'ID' },
                        { field: 'listorder', width: 100, title: '排序', edit: 'text' },
                        { field: 'level', width: 80, align: 'left', title: '等级' },
                        { field: 'title', align: 'left', title: '名称' },
                        { field: 'days', align: 'left', title: '天数' },
                        { field: 'amount', align: 'left', title: '价格' },
                        { field: 'create_time', width: 180, title: '创建时间', search: 'range' },
                        { field: 'status', width: 80, title: '状态', templet: Table.formatter.status, selectList: { 0: '禁用', 1: '正常' }, search: false },
                        { width: 85, title: '操作', templet: Table.formatter.tool, operat: ['edit', 'delete'] }
                    ]
                ],
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