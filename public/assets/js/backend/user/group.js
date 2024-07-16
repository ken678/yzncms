define(['jquery', 'table', 'form'], function($, Table, Form) {
    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                add_url: "user.group/add",
                edit_url: "user.group/edit",
                delete_url: "user.group/del",
                multi_url: 'user.group/multi',
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'add', 'edit'],
                url: 'user.group/index',
                search: false,
                cols: [
                    [
                        { type: 'checkbox', fixed: 'left' },
                        { field: 'id', width: 80, title: 'ID' },
                        { field: 'listorder', width: 100, title: '排序', edit: 'text' },
                        { field: 'name', align: "left", title: '会员组名' },
                        { field: 'issystem', width: 100, title: '系统组', align: "center", templet: '#issystem' },
                        { field: 'count', width: 100, align: "center", title: '会员数' },
                        { field: 'starnum', width: 100, align: "center", title: '星星数' },
                        { field: 'point', width: 100, align: "center", title: '积分小于' },
                        { field: 'allowattachment', width: 120, align: "center", title: '允许上传附件', templet: '#allowattachment' },
                        { field: 'allowpost', width: 120, title: '投稿权限', align: "center", templet: '#allowpost' },
                        { field: 'allowpostverify', width: 120, title: '投稿不需审核', align: "center", templet: '#allowpostverify' },
                        { field: 'allowsearch', width: 120, title: '搜索权限', align: "center", templet: '#allowsearch' },
                        { field: 'allowsendmessage', width: 120, title: '发短消息', align: "center", templet: '#allowsendmessage' },
                        { field: 'status', width: 80, title: '状态', templet: Table.formatter.status, selectList: { 0: '禁用', 1: '正常' }, search: false },
                        {
                            fixed: 'right',
                            width: 85,
                            title: '操作',
                            templet: Table.formatter.tool,
                            operat: [
                                'edit',
                                [{
                                    class: 'layui-btn layui-btn-danger layui-btn-xs',
                                    hidden: function(row) {
                                        //为true隐藏按钮
                                        return row.issystem == 1;
                                    },
                                    icon:'iconfont icon-delete-bin-line',
                                    auth: 'delete',
                                    text: "",
                                    title: '',
                                    extend: "lay-event='btn-delone'",
                                }]
                            ]
                        }
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