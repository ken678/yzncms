//封装表单操作 部分参考EasyAdmin
layui.define(['layer', 'form', 'yzn', 'table', 'notice', 'element','yznUpload'], function(exports) {
    var MOD_NAME = 'yznForm',
        $ = layui.$,
        layer = layui.layer,
        yzn = layui.yzn,
        table = layui.table,
        form = layui.form,
        element = layui.element,
        yznUpload = layui.yznUpload,
        notice = layui.notice;

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTable',
    };

    var yznForm = {
        api: {
            form: function(url, data, ok, no, refresh, type, pop) {
                var that = this;
                var submitBtn = $(".layer-footer button[lay-submit]");
                submitBtn.addClass("layui-btn-disabled").prop('disabled', true);
                if (refresh === undefined) {
                    refresh = true;
                }
                yzn.request.post({
                    url: url,
                    data: data,
                }, function(data,res) {
                    setTimeout(function() {
                        submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    }, 3000);
                    if (false === $('.layui-form').triggerHandler("success.form")) {
                        return false;
                    }
                    if (typeof ok === 'function') {
                        if (false === ok.call($(this), data,res)) {
                            return false;
                        }
                    }
                    if (type === 'layui-form') {
                        res.msg = res.msg || '';
                        yzn.msg.success(res.msg);
                        setTimeout(function() {
                            if (pop === true) {
                                if (refresh == true) {
                                    parent.location.reload();
                                } else if (typeof(res.url) != 'undefined' && res.url != null && res.url != '') {
                                    parent.location.href = res.url;
                                }
                                parent.layui.layer.closeAll();
                            } else if (refresh == true) {
                                if (typeof(res.url) != 'undefined' && res.url != null && res.url != '') {
                                    location.href = res.url;
                                } else {
                                    //history.back(-1);
                                    location.reload();
                                }
                            }
                        }, 3000);
                        return false;
                    } else {
                        res.msg = res.msg || '';
                        yzn.msg.success(res.msg, function() {
                            yznForm.api.closeCurrentOpen({
                                refreshTable: refresh
                            });
                        });
                        return false;
                    }
                }, function(data,res) {
                    submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    if (false === $('.layui-form').triggerHandler("error.form")) {
                        return false;
                    }
                    if (typeof no === 'function') {
                        if (false === no.call($(this), data,res)) {
                            return false;
                        }
                    }
                    var msg = res.msg == undefined ? '返回数据格式有误' : res.msg;
                    yzn.msg.error(msg);
                    return false;
                });
                return false;
            },
            closeCurrentOpen: function(option) {
                option = option || {};
                option.refreshTable = option.refreshTable || false;
                option.refreshFrame = option.refreshFrame || false;
                if (option.refreshTable === true) {
                    option.refreshTable = init.table_render_id;
                }
                var index = parent.layer.getFrameIndex(window.name);
                parent.layer.close(index);
                if (option.refreshTable !== false) {
                    parent.layui.table.reload(option.refreshTable);
                }
                if (option.refreshFrame) {
                    parent.location.reload();
                }
                return false;
            },
            refreshFrame: function() {
                parent.location.reload();
                return false;
            },
            refreshTable: function(tableName) {
                tableName = tableName | 'currentTable';
                table.reload(tableName);
            },
        },
        events: {
            formSubmit: function(layform,preposeCallback, ok, no) {
                var formList = $("[lay-submit]", layform);
                
                // 表单提交自动处理
                if (formList.length > 0) {
                    $.each(formList, function(i, v) {
                        var filter = $(this).attr('lay-filter'),
                            type = $(this).attr('data-type'),
                            refresh = $(this).attr('data-refresh'),
                            pop = $(this).attr('data-pop'),
                            url = $(this).attr('lay-submit');
                        // 表格搜索不做自动提交
                        if (type === 'tableSearch') {
                            return false;
                        }
                        // 判断是否需要刷新表格
                        if (refresh === 'false') {
                            refresh = false;
                        } else {
                            refresh = true;
                        }
                        if (pop === 'true') {
                            pop = true;
                        } else {
                            pop = false;
                        }
                        // 自动添加layui事件过滤器
                        if (filter === undefined || filter === '') {
                            filter = 'save_form_' + (i + 1);
                            $(this).attr('lay-filter', filter)
                        }
                        if (url === undefined || url === '' || url === null) {
                            url = window.location.href;
                        }
                        form.on('submit(' + filter + ')', function(data) {
                            var dataField = $(data.form).serializeArray();

                            if (typeof preposeCallback === 'function') {
                                dataField = preposeCallback(dataField);
                            }
                            yznForm.api.form(url, dataField, ok, no, refresh, type, pop);
                            return false;
                        });
                    });
                }

            },
            faselect: function (layform) {
                //绑定fachoose选择附件事件
                if ($(".fachoose", layform).size() > 0) {
                    $(".fachoose", layform).on('click', function () {
                        var that = this;
                        var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                        var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                        var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                        var user_id = $(this).data("user-id") ? $(this).data("user-id") : '';
                        var url = $(this).data("url") ? $(this).data("url") : GV.attachment_select_url;
                        yzn.open('选择',url + "?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype + "&admin_id=" + admin_id + "&user_id=" + user_id, 
                            '800','650',{
                            callback: function (data) {
                                var button = $("#" + $(that).attr("id"));
                                var maxcount = $(button).data("maxcount");
                                var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                                maxcount = typeof maxcount !== "undefined" ? maxcount : 0;
                                if (input_id && data.multiple) {
                                    var urlArr = [];
                                    var inputObj = $("#" + input_id);
                                    var value = $.trim(inputObj.val());
                                    if (value !== "") {
                                        urlArr.push(inputObj.val());
                                    }
                                    urlArr.push(data.url)
                                    var result = urlArr.join(",");
                                    if (maxcount > 0) {
                                        var nums = value === '' ? 0 : value.split(/\,/).length;
                                        var files = data.url !== "" ? data.url.split(/\,/) : [];
                                        var remains = maxcount - nums;
                                        if (files.length > remains) {
                                            layer.msg('你最多选择'+ remains +'个文件', { icon: 2 });
                                            return false;
                                        }
                                    }
                                    inputObj.val(result).trigger("change");
                                } else {
                                    $("#" + input_id).val(data.url).trigger("change");
                                }
                            }
                        });
                        return false;
                    });
                }
            },
            //favisible前端组件来源https://gitee.com/karson/fastadmin
            favisible: function(layform) {
                if ($("[data-favisible]", layform).length == 0) {
                    return;
                }
                var checkCondition = function(condition) {
                    var conditionArr = condition.split(/&&/);
                    var success = 0;
                    var baseregex = /^([a-z0-9\_]+)([>|<|=|\!]=?)(.*)$/i,
                        strregex = /^('|")(.*)('|")$/,
                        regregex = /^regex:(.*)$/;

                    var operator_result = {
                        '>': function(a, b) { return a > b; },
                        '>=': function(a, b) { return a >= b; },
                        '<': function(a, b) { return a < b; },
                        '<=': function(a, b) { return a <= b; },
                        '==': function(a, b) { return a == b; },
                        '!=': function(a, b) { return a != b; },
                        'in': function(a, b) { return b.split(/\,/).indexOf(a) > -1; },
                        'regex': function(a, b) {
                            var regParts = b.match(/^\/(.*?)\/([gim]*)$/);
                            var regexp = regParts ? new RegExp(regParts[1], regParts[2]) : new RegExp(b);
                            return regexp.test(a);
                        }
                    };

                    var dataArr = layform.serializeArray(),
                        dataObj = {},
                        fieldName, fieldValue;

                    var field = layform.attr('data-field') || 'row';
                    console.log(field);
                    $(dataArr).each(function(i, field) {
                        fieldName = field.name;
                        fieldValue = field.value;
                        fieldName = fieldName.substr(-2) === '[]' ? fieldName.substr(0, fieldName.length - 2) : fieldName;
                        dataObj[fieldName] = typeof dataObj[fieldName] !== 'undefined' ? [dataObj[fieldName], fieldValue].join(',') : fieldValue;
                    });

                    $.each(conditionArr, function(i, item) {
                        var basematches = baseregex.exec(item);
                        if (basematches) {
                            var name = basematches[1],
                                operator = basematches[2],
                                value = basematches[3].toString();
                            if (operator === '=') {
                                var strmatches = strregex.exec(value);
                                operator = strmatches ? '==' : 'in';
                                value = strmatches ? strmatches[2] : value;
                            }
                            var regmatches = regregex.exec(value);
                            if (regmatches) {
                                operator = 'regex';
                                value = regmatches[1];
                            }
                            var chkname = field+"[" + name + "]";
                            if (typeof dataObj[chkname] === 'undefined') {
                                return false;
                            }
                            var objvalue = dataObj[chkname];
                            if ($.isArray(objvalue)) {
                                objvalue = dataObj[chkname].join(",");
                            }
                            if (['>', '>=', '<', '<='].indexOf(operator) > -1) {
                                objvalue = parseFloat(objvalue);
                                value = parseFloat(value);
                            }
                            var result = operator_result[operator](objvalue, value);
                            success += (result ? 1 : 0);
                        }
                    });
                    return success === conditionArr.length;
                };
                var formEach = function(){
                    $("[data-favisible][data-favisible!='']", layform).each(function() {
                        var visible = $(this).data("favisible");
                        var groupArr = visible.split(/\|\|/);
                        var success = 0;
                        $.each(groupArr, function(i, j) {
                            if (checkCondition(j)) {
                                success++;
                            }
                        });
                        if (success > 0) {
                            $(this).removeClass("layui-hide");
                        } else {
                            $(this).addClass("layui-hide");
                        }
                    });
                }
                layform.on("keyup change click configchange", "input,select", function() {
                   formEach();
                });
                form.on('select', function(data){
                   formEach();
                });
                form.on('checkbox', function(data){
                   formEach();
                });
                form.on('switch', function(data){
                   formEach();
                });
                form.on('radio', function(data){
                   formEach();
                });
                //追加上忽略元素
                setTimeout(function() {
                    layform.find('.layui-hide,[data-favisible]').find('[lay-verify]').removeAttr('lay-verify');
                }, 0);

                $("input,select", layform).trigger("configchange");
            },
            //fieldlist前端组件来源https://gitee.com/karson/fastadmin
            fieldlist: function(layform) {
                // 绑定fieldlist组件
                if ($(".fieldlist",layform).size() > 0) {
                    layui.define(['laytpl','dragsort'], function(exports) {
                        var dragsort = layui.dragsort,
                            laytpl = layui.laytpl;

                        //刷新隐藏textarea的值
                        var refresh = function (container, obj) {
                            var data = {};
                            var name = container.data("name");
                            var textarea = $("textarea[name='" + name + "']",layform);
                            var template = container.data("template");
                            $.each($("input,select,textarea", container).serializeArray(), function (i, j) {
                                var reg = /\[(\w+)\]\[(\w+)\]$/g;
                                var match = reg.exec(j.name);
                                if (!match)
                                    return true;
                                match[1] = "x" + parseInt(match[1]);
                                if (typeof data[match[1]] == 'undefined') {
                                    data[match[1]] = {};
                                }

                                if (typeof obj != 'undefined' && $(obj.elem).attr('name') == j.name) {
                                    data[match[1]][match[2]] = obj.value;
                                } else {
                                    data[match[1]][match[2]] = j.value;
                                }
                                //data[match[1]][match[2]] = j.value;
                            });
                            var result = template ? [] : {};
                            $.each(data, function (i, j) {
                                if (j) {
                                    if (!template) {
                                        if (j.key != '') {
                                            result[j.key] = j.value;
                                        }
                                    } else {
                                        result.push(j);
                                    }
                                }
                            });
                            textarea.val(JSON.stringify(result));
                        };
                        //追加一行数据
                        var append = function (container, row, initial) {
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            var index = container.data("index");
                            var name = container.data("name");
                            var template = container.data("template");
                            var data = container.data();
                            var id = container.data("id");
                            index = index ? parseInt(index) : 0;
                            container.data("index", index + 1);
                            row = row ? row : {};
                            row = typeof row.key === 'undefined' || typeof row.value === 'undefined' ? {key: '', value: ''} : row;
                            var options = container.data("fieldlist-options") || {};
                            var vars = {
                                lists: [{ 'index': index, 'name': name, 'data': data,'options': options,'key': row.key,'value': row.value,'row': row.value }]
                            };
                            var html = laytpl($("#" + id + "Tpl").html()).render(vars);
                            var obj = $(html);
                            if ((options.deleteBtn === false || options.removeBtn === false) && initial)
                                obj.find(".btn-remove").remove();
                            if (options.dragsortBtn === false && initial)
                                obj.find(".btn-dragsort").remove();
                            if ((options.readonlyKey === true || options.disableKey === true) && initial) {
                                obj.find("input[name$='[key]']").prop("readonly", true);
                            }
                            obj.attr("fieldlist-item", true);
                            obj.insertAfter($(tagName + "[fieldlist-item]", container).length > 0 ? $(tagName + "[fieldlist-item]:last", container) : $(tagName + ":first", container));
                            form.render();
                            if ($(".btn-append,.append", container).length > 0) {
                                //兼容旧版本事件
                                $(".btn-append,.append", container).trigger("fa.event.appendfieldlist", obj);
                            } else {
                                //新版本事件
                                container.trigger("fa.event.appendfieldlist", obj);
                            }
                            return obj;
                        };
                        var fieldlist = $(".fieldlist", layform);
                        //监听文本框改变事件
                        $(document).on('change keyup changed', ".fieldlist input,.fieldlist textarea,.fieldlist select", function () {
                            var container = $(this).closest(".fieldlist");
                            refresh(container);
                        });
                        //追加控制(点击按钮)
                        fieldlist.on("click", ".btn-append,.append", function (e, row) {
                            var container = $(this).closest(".fieldlist");
                            append(container, row);
                            // refresh(container);
                        });
                        //移除控制(点击按钮)
                        fieldlist.on("click", ".btn-remove", function () {
                            var container = $(this).closest(".fieldlist");
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            $(this).closest(tagName).remove();
                            refresh(container);
                        });
                        //追加控制(通过事件)
                        fieldlist.on("fa.event.appendtofieldlist", function (e, row) {
                            var container = $(this);
                            append(container, row);
                            refresh(container);
                        });
                        form.on('radio(fieldlist)', function(data) {
                            var container = $(this).closest(".fieldlist");
                            refresh(container, data);
                        });
                        form.on('select(fieldlist)', function(data) {
                            var container = $(this).closest(".fieldlist");
                            refresh(container, data);
                        });
                        //根据textarea内容重新渲染
                        fieldlist.on("fa.event.refreshfieldlist", function () {
                            var container = $(this);
                            var textarea = $("textarea[name='" + container.data("name") + "']", layform);
                            //先清空已有的数据
                            $("[fieldlist-item]", container).remove();
                            var json = {};
                            try {
                                json = JSON.parse(textarea.val());
                            } catch (e) {
                            }
                            $.each(json, function (i, j) {
                                append(container, {key: i, value: j}, true);
                            });
                        });
                        //拖拽排序
                        fieldlist.each(function () {
                            var container = $(this);
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            container.dragsort({
                                itemSelector: tagName,
                                dragSelector: ".btn-dragsort",
                                dragEnd: function () {
                                    refresh(container);
                                },
                                placeHolderTemplate: $("<" + tagName + "/>")
                            });
                            if (typeof container.data("options") === 'object' && container.data("options").appendBtn === false) {
                                $(".btn-append,.append", container).hide();
                            }
                            $("textarea[name='" + container.data("name") + "']", layform).on("fa.event.refreshfieldlist", function () {
                                //兼容旧版本事件
                                $(this).closest(".fieldlist").trigger("fa.event.refreshfieldlist");
                            });
                        });
                        fieldlist.trigger("fa.event.refreshfieldlist");
                    })
                }
            },
            selectpage: function(layform) {
                // 绑定selectpage组件
                if ($(".selectpage",layform).size() > 0) {
                    layui.define('selectPage', function(exports) {
                        var selectPage = layui.selectPage;
                        $('.selectpage',layform).selectPage({
                            eAjaxSuccess: function(data) {
                                //console.log(data);
                                data.list = typeof data.data !== 'undefined' ? data.data : [];
                                data.totalRow = typeof data.count !== 'undefined' ? data.count : data.data.length;
                                return data;
                            }
                        })
                    })
                }
            },
            cxselect: function (layform) {
                //绑定cxselect元素事件
                if ($("[data-toggle='cxselect']", layform).length > 0) {
                    layui.define('cxselect', function (exports) {
                        $.cxSelect.defaults.jsonName = 'name';
                        $.cxSelect.defaults.jsonValue = 'value';
                        $.cxSelect.defaults.jsonSpace = 'data';
                        $("[data-toggle='cxselect']", layform).cxSelect();
                    });
                }
            },
            citypicker: function(layform) {
                // 绑定城市选择组件
                if ($("[data-toggle='city-picker']", layform).size() > 0) {
                    layui.define('citypicker', function(exports) {
                        var citypicker = layui.citypicker;
                    })
                }
            },
            datetimepicker: function(layform) {
                // 绑定时间组件
                if ($(".datetime",layform).size() > 0) {
                    layui.define('laydate', function(exports) {
                        var laydate = layui.laydate;
                        $(".datetime",layform).each(function() {
                            var format = $(this).attr('data-date'),
                                type = $(this).attr('data-date-type'),
                                range = $(this).attr('data-date-range');
                            if (type === undefined || type === '' || type === null) {
                                type = 'datetime';
                            }
                            var options = {
                                elem: this,
                                type: type,
                                trigger: 'click',
                            };
                            if (format !== undefined && format !== '' && format !== null) {
                                options['format'] = format;
                            }
                            if (range !== undefined) {
                                if (range === null || range === '') {
                                    range = '-';
                                }
                                options['range'] = range;
                            }
                            laydate.render(options);
                        });
                    })
                }
            },
            tagsinput: function(layform) {
                // 绑定tags标签组件
                if ($(".form-tags",layform).size() > 0) {
                    layui.define('tagsinput', function(exports) {
                        var tagsinput = layui.tagsinput;
                        $('.form-tags',layform).each(function() {
                            $(this).tagsInput({
                                width: 'auto',
                                defaultText: $(this).data('remark'),
                                height: '26px',
                            })
                        })
                    })
                }
            },
            colorpicker: function(layform) {
                // 绑定颜色组件
                if ($('.colorpicker',layform).length > 0) {
                    layui.define('colorpicker', function(exports) {
                        var colorpicker = layui.colorpicker;
                        $('.colorpicker',layform).each(function() {
                            var input_id = $(this).data("input-id");
                            var inputObj = $("#" + input_id);
                            colorpicker.render({
                                elem: $(this),
                                color: inputObj.val(),
                                done: function(color) {
                                    inputObj.val(color);
                                }
                            });
                        });
                    })
                }
            },
            ueditor: function(layform) {
                // ueditor编辑器集合
                var ueditors = {};
                // 绑定ueditor编辑器组件
                if ($(".js-ueditor",layform).size() > 0) {
                    layui.define('ueditor', function(exports) {
                        var ueditor = layui.ueditor;
                        $('.js-ueditor',layform).each(function() {
                            var ueditor_name = $(this).attr('id');
                            ueditors[ueditor_name] = UE.getEditor(ueditor_name, {
                                allowDivTransToP: false, //转换p标签
                                initialFrameWidth: '100%',
                                initialFrameHeight: 400, //初始化编辑器高度,默认320
                                autoHeightEnabled: false, //是否自动长高
                                maximumWords: 50000, //允许的最大字符数
                                serverUrl: GV.image_upload_url+'?from=ueditor',
                            });
                            $('#' + ueditor_name + 'grabimg',layform).click(function() {
                                var con = ueditors[ueditor_name].getContent();
                                $.post('/admin/Attachments/geturlfile', { 'content': con, 'type': 'images' },
                                    function(data) {
                                        ueditors[ueditor_name].setContent(data);
                                        layer.msg("图片本地化完成", { icon: 1 });
                                    }, 'html');
                            });
                            //打开图片管理
                            ueditors[ueditor_name].addListener("upload.online", function (e, editor, dialog) {
                                dialog.close(false);
                                yzn.open('选择',GV.attachment_select_url + "?element_id=&multiple=true&mimetype=image/*", '800','650',{
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        urlArr.forEach(function (url, index) {
                                            editor.execCommand('insertimage', {
                                                src: url
                                            });
                                        });
                                    }
                                });
                            });
                            //打开附件管理
                            ueditors[ueditor_name].addListener("file.online", function (e, editor, dialog) {
                                dialog.close(false);
                                yzn.open('选择',GV.attachment_select_url + "?element_id=&multiple=true&mimetype=application/*", '800','650',{
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        urlArr.forEach(function (url, index) {
                                            editor.execCommand('insertfile', {
                                                url: url
                                            });
                                        });
                                    }
                                });
                            });
                            //分词检测
                            if (ueditor_name == 'content') {
                                $('#getwords').click(function() {
                                    var con = ueditors['content'].getContentTxt();
                                    $.post(GV.ueditor_getwords_url, { 'content': con },
                                        function(data) {
                                            if (data.code == 0) {
                                                $(".tags-keywords").importTags(data.arr);
                                                //$(".tags-keywords").val(data.arr);
                                            } else {
                                                layer.msg(data.msg, { icon: 2 });
                                            }
                                        });
                                });
                            }
                            //过滤敏感字
                            $('#' + ueditor_name + 'filterword',layform).click(function() {
                                var con = ueditors[ueditor_name].getContent();
                                yzn.request.post({
                                    url: 'admin/ajax/filterWord',
                                    data: { 'content': con },
                                }, function(data,res) {
                                    if (res.code == 0) {
                                        if ($.isArray(res.data)) {
                                            layer.msg("违禁词：" + res.data.join(","), { icon: 2 });
                                        }
                                    } else {
                                        layer.msg("内容没有违禁词！", { icon: 1 });
                                    }
                                });
                            })
                        });
                    })
                }
            },
            cropper: function() {
                //裁剪图片
                $(document).on('click', '.cropper', function() {
                    var inputId = $(this).attr("data-input-id");
                    var image = $(this).parent('.file-panel').prev('img').data('original');
                    var index = layer.open({
                        type: 2,
                        shadeClose: true,
                        shade: false,
                        area: [$(window).width() > 880 ? '880px' : '95%', $(window).height() > 600 ? '600px' : '95%'],
                        title: '图片裁剪',
                        content: '/admin/Attachments/cropper?url=' + image,
                        success: function(layero, index) {
                            $(layero).data("arr", [inputId, image]);
                        }
                    });
                });
            },
            xmSelect: function() {
                // 绑定下拉框多选组件
                if ($('.layui-form .form-selects').length > 0) {
                    layui.define('xmSelect', function(exports) {
                        var xmselect = layui.xmSelect;
                        $('.layui-form .form-selects').each(function() {
                            var name = $(this).data("name");
                            var list = $(this).data("list");
                            var value = $(this).data("value");

                            var newArr = [];
                            $.each(list, function(i, j) {
                                var vote = {};
                                vote.value = i;
                                vote.name = j;
                                newArr.push(vote);
                            })
                            if (typeof value != 'object' && value) {
                                value = typeof value === "number" ? [value] : value.split(',')
                            }
                            xmSelect.render({
                                el: document.querySelector('.form-selects'),
                                initValue: value,
                                name: name,
                                data: newArr
                            })
                        })
                    })
                }
            },
            plupload: function (layform) {
                yznForm.events.faupload(layform);
            },
            faupload: function (layform) {
                //绑定上传元素事件
                if ($(".plupload,.faupload", layform).length > 0) {
                    yznUpload.api.upload($(".plupload,.faupload", layform));
                }
            },
        },
        bindevent: function(form,preposeCallback, ok, no) {
            form = typeof form === 'object' ? form : $(form);
            var events = yznForm.events;
            events.formSubmit(form,preposeCallback, ok, no);
            events.selectpage(form);
            events.faselect(form);
            events.fieldlist(form);
            events.cxselect(form);
            events.citypicker(form);
            events.datetimepicker(form);
            events.tagsinput(form);
            events.colorpicker(form);
            events.ueditor(form);
            events.favisible(form);
            events.cropper();
            events.xmSelect();
            events.faupload(form);
        }
    }
    exports(MOD_NAME, yznForm);
});