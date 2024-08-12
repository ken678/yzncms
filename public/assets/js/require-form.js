/**
 * http://www.apache.org/licenses/LICENSE-2.0
 * Copyright (c) 2018 http://yzncms.com All rights reserved.
 * Author: 御宅男 <530765310@qq.com>
 * Original reference: https://gitee.com/karson/fastadmin
 */
define(['jquery', 'layui', 'upload'], function($, layui, Upload) {
    var form = layui.form;

    var Form = {
        config: {
            fieldlisttpl:'<dd class="layui-form-item rules-item">{{# layui.each(d.lists, function(index, item) { }}<input type="text" class="layui-input" name="{{item.name}}[{{item.index}}][key]" placeholder="键" value="{{item.key|| \'\'}}" /> <input type="text" class="layui-input" name="{{item.name}}[{{item.index}}][value]" placeholder="值" value="{{item.value|| \'\'}}" /> <button type="button" class="layui-btn layui-btn-danger btn-remove layui-btn-xs"><i class="iconfont icon-close-fill"></i></button><button type="button" class="layui-btn btn-dragsort layui-btn-xs"><i class="iconfont icon-drag-move-2-fill"></i></button>{{# }); }}</dd>'
        },
        events: {
            bindevent: function(layform) {

            },
            validator: function(layform, success, error, submit) {
                if (!layform.is("form"))
                    return;
                var submitBtn = $("[lay-submit]", layform),
                    filter = submitBtn.attr('lay-filter');

                $(".layer-footer [lay-submit],.fixed-footer [lay-submit],.normal-footer [lay-submit]", layform).removeClass("disabled");

                //验证通过提交表单
                form.on('submit(' + filter + ')', function(data) {
                    submitBtn.addClass("disabled");

                    var submitResult = Form.api.submit(layform, function(data, ret) {
                        //that.holdSubmit(false);
                        submitBtn.removeClass("disabled");
                        if (false === $(this).triggerHandler("success.form", [data, ret])) {
                            return false;
                        }
                        if (typeof success === 'function') {
                            if (false === success.call($(this), data, ret)) {
                                return false;
                            }
                        }
                        //提示及关闭当前窗口
                        var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : '操作成功!';
                        parent.Toastr.success(msg);
                        parent.$(".btn-refresh").trigger("click");
                        if (window.name) {
                            var index = parent.Layer.getFrameIndex(window.name);
                            parent.Layer.close(index);
                        }
                        return false;
                    }, function(data, ret) {
                        if (false === $(this).triggerHandler("error.form", [data, ret])) {
                            return false;
                        }
                        submitBtn.removeClass("disabled");
                        if (typeof error === 'function') {
                            if (false === error.call($(this), data, ret)) {
                                return false;
                            }
                        }
                    }, submit);
                    //如果提交失败则释放锁定
                    if (!submitResult) {
                        submitBtn.removeClass("disabled");
                    }
                    return false;
                })
            },
            faselect: function(layform) {
                //绑定fachoose选择附件事件
                if ($(".faselect,.fachoose", layform).length > 0) {
                    $(".faselect,.fachoose", layform).off('click').on('click', function() {
                        var that = this;
                        var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                        var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                        var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                        var user_id = $(this).data("user-id") ? $(this).data("user-id") : '';
                        mimetype = mimetype.replace(/\/\*/ig, '/');
                        var url = $(this).data("url") ? $(this).data("url") : (typeof Backend !== 'undefined' ? "general.attachments/select" : "user/attachment");
                        parent.Yzn.api.open(url + "?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype + "&admin_id=" + admin_id + "&user_id=" + user_id, '选择', {
                            callback: function(data) {
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
                                    var nums = value === '' ? 0 : value.split(/\,/).length;
                                    var files = data.url !== "" ? data.url.split(/\,/) : [];
                                    $.each(files, function(i, j) {
                                        var url = Config.upload.fullmode ? Fast.api.cdnurl(j) : j;
                                        urlArr.push(url);
                                    });
                                    if (maxcount > 0) {
                                        var remains = maxcount - nums;
                                        if (files.length > remains) {
                                            Toastr.error('你最多选择' + remains + '个文件');
                                            return false;
                                        }
                                    }
                                    var result = urlArr.join(",");
                                    inputObj.val(result).trigger("change");
                                } else if (input_id) {
                                    var url = Config.upload.fullmode ? Yzn.api.cdnurl(data.url) : data.url;
                                    $("#" + input_id).val(url).trigger("change").trigger("validate");
                                }
                            }
                        });
                        return false;
                    });
                }
            },
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
                            var chkname = field + "[" + name + "]";
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
                var formEach = function() {
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
                form.on('select', function(data) {
                    formEach();
                });
                form.on('checkbox', function(data) {
                    formEach();
                });
                form.on('switch', function(data) {
                    formEach();
                });
                form.on('radio', function(data) {
                    formEach();
                });
                //追加上忽略元素
                setTimeout(function() {
                    layform.find('.layui-hide,[data-favisible]').find('[lay-verify]').removeAttr('lay-verify');
                }, 0);

                $("input,select", layform).trigger("configchange");
            },
            fieldlist: function(layform) {
                // 绑定fieldlist组件
                if ($(".fieldlist", layform).length > 0) {
                    require(['dragsort'], function(undefined) {

                        //刷新隐藏textarea的值
                        var refresh = function(container, obj) {
                            var data = {};
                            var name = container.data("name");
                            var textarea = $("textarea[name='" + name + "']", layform);
                            var template = container.data("template");
                            $.each($("input,select,textarea", container).serializeArray(), function(i, j) {
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
                                    var keys = Object.keys(j);
                                    if (keys.indexOf("value") > -1 && (keys.length === 1 || (keys.length === 2 && keys.indexOf("key") > -1))) {
                                        if (keys.length === 2) {
                                            if (j.key != '') {
                                                result['__PLACEHOLDKEY__' + j.key] = j.value;
                                            }
                                        } else {
                                            result.push(j.value);
                                        }
                                    } else {
                                        result.push(j);
                                    }
                                }
                            });
                            textarea.val(JSON.stringify(result).replace(/__PLACEHOLDKEY__/g, ''));
                        };
                        //追加一行数据
                        var append = function(container, row, initial) {
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            var index = container.data("index");
                            var name = container.data("name");
                            var template = container.data("template");
                            var data = container.data();
                            index = index ? parseInt(index) : 0;
                            container.data("index", index + 1);
                            row = row ? row : {};
                            row = typeof row.key === 'undefined' || typeof row.value === 'undefined' ? {key: '', value: ''} : row;
                            var options = container.data("fieldlist-options") || {};
                            var vars = {
                                lists: [{ 'index': index, 'name': name, 'data': data, 'options': options, 'key': row.key, 'value': row.value, 'row': row.value }]
                            };
                            var tpl = template ? $("#" + template).html() : Form.config.fieldlisttpl;
                            var html = layui.laytpl(tpl).render(vars);
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
                        $(document).on('change keyup changed', ".fieldlist input,.fieldlist textarea,.fieldlist select", function() {
                            var container = $(this).closest(".fieldlist");
                            refresh(container);
                        });
                        //追加控制(点击按钮)
                        fieldlist.on("click", ".btn-append,.append", function(e, row) {
                            var container = $(this).closest(".fieldlist");
                            append(container, row);
                            refresh(container);
                        });
                        //移除控制(点击按钮)
                        fieldlist.on("click", ".btn-remove", function() {
                            var container = $(this).closest(".fieldlist");
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            $(this).closest(tagName).remove();
                            refresh(container);
                        });
                        //追加控制(通过事件)
                        fieldlist.on("fa.event.appendtofieldlist", function(e, row) {
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
                        fieldlist.on("fa.event.refreshfieldlist", function() {
                            var container = $(this);
                            var textarea = $("textarea[name='" + container.data("name") + "']", layform);
                            //先清空已有的数据
                            $("[fieldlist-item]", container).remove();
                            var json = {};
                            try {
                                var val = textarea.val().replace(/"(\d+)"\:/g, "\"__PLACEHOLDERKEY__$1\":");
                                json = JSON.parse(val);
                            } catch (e) {}
                            $.each(json, function(i, j) {
                                append(container, {key: i.toString().replace("__PLACEHOLDERKEY__", ""), value: j}, true);
                            });
                        });
                        //拖拽排序
                        fieldlist.each(function() {
                            var container = $(this);
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            container.dragsort({
                                itemSelector: tagName,
                                dragSelector: ".btn-dragsort",
                                dragEnd: function() {
                                    refresh(container);
                                },
                                placeHolderTemplate: $("<" + tagName + "/>")
                            });
                            if (typeof container.data("options") === 'object' && container.data("options").appendBtn === false) {
                                $(".btn-append,.append", container).hide();
                            }
                            $("textarea[name='" + container.data("name") + "']", layform).on("fa.event.refreshfieldlist", function() {
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
                if ($(".selectpage", layform).length > 0) {
                    require(['selectpage'], function() {
                        $('.selectpage', layform).selectPage({
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
            cxselect: function(layform) {
                //绑定cxselect元素事件
                if ($("[data-toggle='cxselect']", layform).length > 0) {
                    require(['cxselect'], function() {
                        $.cxSelect.defaults.jsonName = 'name';
                        $.cxSelect.defaults.jsonValue = 'value';
                        $.cxSelect.defaults.jsonSpace = 'data';
                        $("[data-toggle='cxselect']", layform).cxSelect();
                    });
                }
            },
            citypicker: function(layform) {
                // 绑定城市选择组件
                if ($("[data-toggle='city-picker']", layform).length > 0) {
                    require(['citypicker'], function() {
                        $(layform).on("reset", function() {
                            setTimeout(function() {
                                $("[data-toggle='city-picker']").citypicker('refresh');
                            }, 1);
                        });
                    })
                }
            },
            datetimepicker: function(layform) {
                // 绑定时间组件
                if ($(".datetime", layform).length > 0) {
                    $(".datetime", layform).each(function() {
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
                            options['shortcuts'] = [{
                                    text: "今天",
                                    value: function() {
                                        var today = new Date();
                                        return [
                                            new Date(today.getFullYear(), today.getMonth(), today.getDate()),
                                            new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59)
                                        ];
                                    }
                                },
                                {
                                    text: "昨天",
                                    value: function() {
                                        var yesterday = new Date();
                                        yesterday.setDate(yesterday.getDate() - 1);
                                        return [
                                            new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate()),
                                            new Date(yesterday.getFullYear(), yesterday.getMonth(), yesterday.getDate(), 23, 59, 59)
                                        ];
                                    }
                                },
                                {
                                    text: "7天前",
                                    value: function() {
                                        var today = new Date();
                                        var sevenDaysAgo = new Date();
                                        sevenDaysAgo.setDate(today.getDate() - 7);
                                        return [
                                            new Date(sevenDaysAgo.getFullYear(), sevenDaysAgo.getMonth(), sevenDaysAgo.getDate()),
                                            new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59)
                                        ];
                                    }
                                },
                                {
                                    text: "本月",
                                    value: function() {
                                        var date = new Date();
                                        var year = date.getFullYear();
                                        var month = date.getMonth();
                                        return [
                                            new Date(year, month, 1),
                                            new Date(year, month + 1, 0, 23, 59, 59)
                                        ];
                                    }
                                },
                                {
                                    text: "上个月",
                                    value: function() {
                                        var date = new Date();
                                        var year = date.getFullYear();
                                        var month = date.getMonth();
                                        return [
                                            new Date(year, month - 1, 1),
                                            new Date(year, month, 0, 23, 59, 59)
                                        ];
                                    }
                                }
                            ]
                        }
                        layui.laydate.render(options);
                    })
                }
            },
            tagsinput: function(layform) {
                if ($(".form-tags", layform).length > 0) {
                    require(['tagsinput'], function() {
                        $('.form-tags', layform).each(function() {
                            $(this).tagsInput({
                                width: 'auto',
                                defaultText: $(this).data('remark') || '关键词回车确认',
                                height: '26px',
                            })
                        })
                    });
                }
            },
            colorpicker: function(layform) {
                // 绑定颜色组件
                if ($('.colorpicker', layform).length > 0) {
                    $('.colorpicker', layform).each(function() {
                        var input_id = $(this).data("input-id");
                        var inputObj = $("#" + input_id);
                        layui.colorpicker.render({
                            elem: $(this),
                            color: inputObj.val(),
                            done: function(color) {
                                inputObj.val(color);
                            }
                        });
                    });
                }
            },
            cropper: function() {
                //裁剪图片
                $(document).off('click', '.btn-cropper').on('click', '.btn-cropper', function() {
                    var input = $("#" + $(this).data("input-id"));
                    var image = $(this).parent('.file-panel').prev('img').data('original');
                    var url = image;
                    var parentWin = (parent ? parent : window);

                    parentWin.Yzn.api.open('general.attachments/cropper?url=' + image, '裁剪', {
                        callback: function(data) {
                            if (typeof data !== 'undefined') {
                                //data URI 转换为一个 File 对象
                                var arr = data.dataURI.split(','),
                                    mime = arr[0].match(/:(.*?);/)[1],
                                    bstr = atob(arr[1]),
                                    n = bstr.length,
                                    u8arr = new Uint8Array(n);
                                while (n--) {
                                    u8arr[n] = bstr.charCodeAt(n);
                                }
                                var urlArr = url.split('.');
                                var suffix = 'png';
                                url = urlArr.join('');
                                var filename = url.substr(url.lastIndexOf('/') + 1);
                                var exp = new RegExp("\\." + suffix + "$", "i");
                                filename = exp.test(filename) ? filename : filename + "." + suffix;
                                var file = new File([u8arr], filename, { type: mime });

                                Upload.api.send(file, function(data) {
                                    input.val(input.val().replace(image, data.url)).trigger("change");
                                }, function(data) {});
                            }
                        },
                        area: ["800px", "600px"],
                    })

                });
            },
            xmSelect: function() {
                // 绑定下拉框多选组件
                if ($('.layui-form .form-selects').length > 0) {
                    require(['xm-select'], function(xmSelect) {
                        $('.layui-form .form-selects').each(function() {
                            var name = $(this).data("name");
                            var list = Array.isArray($(this).data("list")) || typeof $(this).data("list") === "object" ? $(this).data("list") : [];
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
            plupload: function(layform) {
                Form.events.faupload(layform);
            },
            faupload: function(layform) {
                //绑定上传元素事件
                if ($(".plupload,.faupload", layform).length > 0) {
                    Upload.api.upload($(".plupload,.faupload", layform));
                }
            },
        },
        api: {
            submit: function(form, success, error, submit) {
                if (form.length === 0) {
                    Toastr.error("表单未初始化完成,无法提交");
                    return false;
                }
                if (typeof submit === 'function') {
                    if (false === submit.call(form, success, error)) {
                        return false;
                    }
                }
                var type = form.attr("method") ? form.attr("method").toUpperCase() : 'POST';
                type = type && (type === 'GET' || type === 'POST') ? type : 'POST';
                url = form.attr("action");
                url = url ? url : location.href;
                //修复当存在多选项元素时提交的BUG
                var params = {};
                var multipleList = $("[name$='[]']", form);
                if (multipleList.length > 0) {
                    var postFields = form.serializeArray().map(function(obj) {
                        return $(obj).prop("name");
                    });
                    $.each(multipleList, function(i, j) {
                        if (postFields.indexOf($(this).prop("name")) < 0) {
                            params[$(this).prop("name")] = '';
                        }
                    });
                }
                //调用Ajax请求方法
                Yzn.api.ajax({
                    type: type,
                    url: url,
                    data: form.serialize() + (Object.keys(params).length > 0 ? '&' + $.param(params) : ''),
                    dataType: 'json',
                    complete: function(xhr) {
                        var token = xhr.getResponseHeader('__token__');
                        if (token) {
                            $("input[name='__token__']").val(token);
                        }
                    }
                }, function(data, ret) {
                    //$('.form-group', form).removeClass('has-feedback has-success has-error');
                    if (data && typeof data === 'object') {
                        //刷新客户端token
                        if (typeof data.token !== 'undefined') {
                            $("input[name='__token__']").val(data.token);
                        }
                        //调用客户端事件
                        if (typeof data.callback !== 'undefined' && typeof data.callback === 'function') {
                            data.callback.call(form, data);
                        }
                    }
                    if (typeof success === 'function') {
                        if (false === success.call(form, data, ret)) {
                            return false;
                        }
                    }
                }, function(data, ret) {
                    if (data && typeof data === 'object' && typeof data.token !== 'undefined') {
                        $("input[name='__token__']").val(data.token);
                    }
                    if (typeof error === 'function') {
                        if (false === error.call(form, data, ret)) {
                            return false;
                        }
                    }
                });
                return true;
            },
            bindevent: function(form, success, error, submit) {
                form = typeof form === 'object' ? form : $(form);
                var events = Form.events;
                events.bindevent(form);
                events.validator(form, success, error, submit);
                events.selectpage(form);
                events.faupload(form);
                events.faselect(form);
                events.fieldlist(form);
                events.cxselect(form);
                events.citypicker(form);
                events.datetimepicker(form);
                events.colorpicker(form);
                events.cropper();
                events.xmSelect();
                events.tagsinput(form);
                events.favisible(form);
            }
        },
    }
    return Form;
});