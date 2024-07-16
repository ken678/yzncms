define(['jquery', 'table', 'form', 'jstree'], function($, Table, Form, undefined) {

    //读取选中的条目
    $.jstree.core.prototype.get_all_checked = function(full) {
        var obj = this.get_selected(),
            i, j;
        for (i = 0, j = obj.length; i < j; i++) {
            obj = obj.concat(this.get_node(obj[i]).parents);
        }
        obj = $.grep(obj, function(v, i, a) {
            return v != '#';
        });
        obj = obj.filter(function(itm, i, a) {
            return i == a.indexOf(itm);
        });
        return full ? $.map(obj, $.proxy(function(i) {
            return this.get_node(i);
        }, this)) : obj;
    };

    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                add_url: "auth.group/add",
                edit_url: "auth.group/edit",
                delete_url: "auth.group/del",
            };

            Table.render({
                init: Table.init,
                toolbar: ['refresh', 'add'],
                url: 'auth.group/index',
                search: false,
                cols: [
                    [
                        { field: 'id', width: 80, title: 'ID' },
                        { field: 'title', width: 200, align: 'left', title: '权限组' },
                        { field: 'description', title: '描述' },
                        { field: 'status', width: 80, title: '状态', templet: Table.formatter.status, selectList: { 0: '禁用', 1: '正常' }, search: false },
                        {
                            width: 100,
                            title: '操作',
                            templet: function(d) {
                                if (d.id == Config.admin.roleid) {
                                    return '<a class="layui-btn layui-btn-xs layui-btn-danger layui-btn-disabled">不可操作</a>';
                                } else {
                                    return Table.formatter.tool.call(this, d, this);
                                }
                            },
                            operat: ['edit', 'delete']
                        }
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
                Form.api.bindevent($("form.layui-form"), null, null, function() {
                    if ($("#treeview").length > 0) {
                        var r = $("#treeview").jstree("get_all_checked");
                        $("input[name='row[rules]']").val(r.join(','));
                    }
                    return true;
                });
                //渲染权限节点树
                //变更级别后需要重建节点树
                layui.form.on('select(parentid)', function(data){
                    var parentid = $(data.elem).data("parentid");
                    var id = $(data.elem).data("id");
                    var val = data.value;

                    if (val == id) {
                        $("option[value='" + parentid + "']", this).prop("selected", true).change();
                        Backend.api.toastr.error('父级不能是它自己');
                        return false;
                    }
                    $.ajax({
                        url: "auth.group/roletree",
                        type: 'post',
                        dataType: 'json',
                        data: { id: id, parentid: val },
                        success: function(ret) {
                            if (ret.hasOwnProperty("code")) {
                                var data = ret.hasOwnProperty("data") && ret.data != "" ? ret.data : "";
                                if (ret.code === 1) {
                                    //销毁已有的节点树
                                    $("#treeview").jstree("destroy");
                                    Controller.api.rendertree(data);
                                } else {
                                    Backend.api.toastr.error(ret.msg);
                                }
                            }
                        },
                        error: function(e) {
                            Backend.api.toastr.error(e.message);
                        }
                    });
                });

                //全选和展开
                layui.form.on('checkbox(checkall)', function(data){
                    $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
                });
                layui.form.on('checkbox(expandall)', function(data){
                    $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
                });
                $("select[name='row[parentid]']").siblings("div.layui-form-select").find("dd.layui-this").click();
            },
            rendertree: function(content) {
                $("#treeview")
                    .on('redraw.jstree', function(e) {
                        $(".layer-footer").attr("domrefresh", Math.random());
                    })
                    .jstree({
                        "themes": { "stripes": true },
                        "checkbox": {
                            "keep_selected_style": false,
                        },
                        "types": {
                            "root": {
                                "icon": "fa fa-folder-open",
                            },
                            "menu": {
                                "icon": "fa fa-folder-open",
                            },
                            "file": {
                                "icon": "fa fa-file-o",
                            }
                        },
                        "plugins": ["checkbox", "types"],
                        "core": {
                            'check_callback': true,
                            "data": content
                        }
                    });
            }
        }
    };
    return Controller;
});