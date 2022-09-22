//封装表单操作 部分参考EasyAdmin和fastadmin
layui.define(['layer', 'form', 'yzn', 'table', 'notice', 'element', 'dragsort'], function(exports) {
    var MOD_NAME = 'yznForm',
        $ = layui.$,
        layer = layui.layer,
        yzn = layui.yzn,
        table = layui.table,
        form = layui.form,
        dragsort = layui.dragsort,
        element = layui.element,
        notice = layui.notice;

    // 文件上传集合
    var webuploaders = [];
    // 当前上传对象
    var curr_uploader = {};

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTable',
    };

    var yznForm = {
        listen: function(preposeCallback, ok, no, ex) {
            // 监听表单提交事件
            yznForm.api.formSubmit(preposeCallback, ok, no, ex);
        },
        api: {
            form: function(url, data, ok, no, ex, refresh, type, pop) {
                var that = this;
                var submitBtn = $(".layer-footer button[lay-submit]");
                submitBtn.addClass("layui-btn-disabled").prop('disabled', true);
                if (refresh === undefined) {
                    refresh = true;
                }
                yzn.request.post({
                    url: url,
                    data: data,
                }, function(res) {
                    setTimeout(function() {
                        submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    }, 3000);
                    if (false === $('.layui-form').triggerHandler("success.form")) {
                        return false;
                    }
                    if (typeof ok === 'function') {
                        if (false === ok.call($(this), res)) {
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
                }, function(res) {
                    submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    if (false === $('.layui-form').triggerHandler("error.form")) {
                        return false;
                    }
                    if (typeof no === 'function') {
                        if (false === no.call($(this), res)) {
                            return false;
                        }
                    }
                    var msg = res.msg == undefined ? '返回数据格式有误' : res.msg;
                    yzn.msg.error(msg);
                    return false;
                }, function(res) {
                    submitBtn.removeClass("layui-btn-disabled").prop('disabled', false);
                    if (typeof ex === 'function') {
                        if (false === ex.call($(this), res)) {
                            return false;
                        }
                    }
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
            formSubmit: function(preposeCallback, ok, no, ex) {
                var formList = document.querySelectorAll("[lay-submit]");

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
                            //var dataField = data.field;
                            var dataField = $(data.form).serializeArray();

                            // 富文本数据处理
                            /*var editorList = document.querySelectorAll(".editor");
                            if (editorList.length > 0) {
                                $.each(editorList, function(i, v) {
                                    var name = $(this).attr("name");
                                    dataField[name] = CKEDITOR.instances[name].getData();
                                });
                            }*/

                            if (typeof preposeCallback === 'function') {
                                dataField = preposeCallback(dataField);
                            }
                            yznForm.api.form(url, dataField, ok, no, ex, refresh, type, pop);
                            return false;
                        });
                    });
                }

            },
        },
        events: {
            init: function() {
                // 放大图片
                $('body').on('click', '[data-image]', function() {
                    var title = $(this).attr('data-image'),
                        src = $(this).attr('src'),
                        alt = $(this).attr('alt');
                    var photos = {
                        "title": title,
                        "id": Math.random(),
                        "data": [{
                            "alt": alt,
                            "pid": Math.random(),
                            "src": src,
                            "thumb": src
                        }]
                    };
                    layer.photos({
                        photos: photos,
                        anim: 5
                    });
                    return false;
                });
                
                /**
                 * 普通按钮点击iframe弹窗
                 * @href 弹窗地址
                 * @title 弹窗标题
                 * @lay-data {width: '弹窗宽度', height: '弹窗高度', idSync: '是否同步ID', table: '数据表ID(同步ID时必须)', type: '弹窗类型'}
                 */
                $(document).on('click', '.layui-iframe', function() {
                    var that = $(this),
                        query = '';
                    var def = { width: '750px', height: '500px', idSync: false, table: 'dataTable', type: 2, url: !that.attr('data-href') ? that.attr('href') : that.attr('data-href'), title: that.attr('title') };
                    var opt = new Function('return ' + that.attr('lay-data'))() || {};

                    opt.url = opt.url || def.url;
                    opt.title = opt.title || def.title;
                    opt.width = opt.width || def.width;
                    opt.height = opt.height || def.height;
                    opt.type = opt.type || def.type;
                    opt.table = opt.table || def.table;
                    opt.idSync = opt.idSync || def.idSync;

                    if (!opt.url) {
                        notice.info({ message: '请设置data-href参数' });
                        return false;
                    }

                    if (opt.idSync) { // ID 同步
                        if ($('.checkbox-ids:checked').length <= 0) {
                            var checkStatus = table.checkStatus(opt.table);
                            if (checkStatus.data.length <= 0) {
                                notice.info({ message: '请选择要操作的数据' });
                                return false;
                            }

                            for (var i in checkStatus.data) {
                                query += '&id[]=' + checkStatus.data[i].id;
                            }
                        } else {
                            $('.checkbox-ids:checked').each(function() {
                                query += '&id[]=' + $(this).val();
                            })
                        }
                    }

                    if (opt.url.indexOf('?') >= 0) {
                        opt.url += '&iframe=yes' + query;
                    } else {
                        opt.url += '?iframe=yes' + query;
                    }

                    layer.open({ type: opt.type, title: opt.title, content: opt.url, area: [opt.width, opt.height] });
                    return false;
                });
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
            fieldlist: function(layform) {
                // 绑定fieldlist组件
                if ($(".fieldlist",layform).size() > 0) {
                    layui.define('laytpl', function(exports) {
                        var laytpl = layui.laytpl;
                        //刷新隐藏textarea的值
                        var refresh = function(name, obj) {
                            var data = {};
                            var textarea = $("textarea[name='" + name + "']",layform);
                            var container = $(".fieldlist[data-name='" + name + "']");
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
                            });
                            var result = template ? [] : {};
                            $.each(data, function(i, j) {
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
                        //监听文本框改变事件
                        $(document).on('change keyup changed', ".fieldlist input,.fieldlist textarea,.fieldlist select", function() {
                            refresh($(this).closest(".fieldlist").data("name"));
                        });
                        form.on('radio(fieldlist)', function(data) {
                            refresh($(this).closest(".fieldlist").data("name"), data);
                        });
                        form.on('select(fieldlist)', function(data) {
                            refresh($(this).closest(".fieldlist").data("name"), data);
                        });
                        //追加控制
                        $(".fieldlist",layform).on("click", ".btn-append,.append", function(e, row) {
                            var container = $(this).closest(".fieldlist");
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            var index = container.data("index");
                            var name = container.data("name");
                            var id = container.data("id");
                            var template = container.data("template");
                            var data = container.data();
                            index = index ? parseInt(index) : 0;
                            container.data("index", index + 1);
                            row = row ? row : {};
                            var vars = {
                                lists: [{ 'index': index, 'name': name, 'data': data, 'row': row }]
                            };
                            laytpl($("#" + id + "Tpl").html()).render(vars, function(html) {
                                $(html).attr("fieldlist-item", true).insertBefore($(tagName + ":last", container));
                            });
                            form.render();
                            $(this).trigger("fa.event.appendfieldlist", $(this).closest(tagName).prev());
                        });
                        //移除控制
                        $(".fieldlist",layform).on("click", ".btn-remove", function() {
                            var container = $(this).closest(".fieldlist");
                            var tagName = container.data("tag") || (container.is("table") ? "tr" : "dd");
                            $(this).closest(tagName).remove();
                            refresh(container.data("name"));
                        });
                        //渲染数据&拖拽排序
                        $(".fieldlist",layform).each(function() {
                            var container = this;
                            var tagName = $(this).data("tag") || ($(this).is("table") ? "tr" : "dd");
                            $(this).dragsort({
                                itemSelector: tagName,
                                dragSelector: ".btn-dragsort",
                                dragEnd: function() {
                                    refresh($(this).closest(".fieldlist").data("name"));
                                },
                                //placeHolderTemplate: '<div style="border:1px #009688 dashed;"></div>'
                            });
                            var textarea = $("textarea[name='" + $(this).data("name") + "']",layform);
                            var template = $(this).data("template");
                            textarea.on("fa.event.refreshfieldlist", function() {
                                $("[fieldlist-item]", container).remove();
                                var json = {};
                                try {
                                    json = JSON.parse($(this).val());
                                } catch (e) {}
                                $.each(json, function(i, j) {
                                    $(".btn-append,.append", container).trigger('click', template ? j : {
                                        key: i,
                                        value: j
                                    });
                                });
                            });
                            textarea.trigger("fa.event.refreshfieldlist");
                        });
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
                                serverUrl: GV.ueditor_upload_url,
                            });
                            $('#' + ueditor_name + 'grabimg',layform).click(function() {
                                var con = ueditors[ueditor_name].getContent();
                                $.post('/attachment/Attachments/geturlfile', { 'content': con, 'type': 'images' },
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
                                }, function(res) {
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
                        content: '/attachment/Attachments/cropper?url=' + image,
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
                            xmSelect.render({
                                el: document.querySelector('.form-selects'),
                                initValue: value !== "" ? value.split(',') : [],
                                name: name,
                                data: newArr
                            })
                        })
                    })
                }
            },
            upload_image: function(elements, onUploadSuccess, onUploadError) {
                elements = typeof elements === 'undefined' ? document.body : elements;
                // 绑定图片上传组件
                if ($(elements).length > 0) {
                    layui.link(layui.cache.base + 'webuploader/webuploader.css?v=0.1.8');
                    layui.define('webuploader', function(exports) {
                        var webuploader = layui.webuploader;
                        //分片
                        var chunking = typeof GV.site.chunking !== "undefined" ? GV.site.chunking : false,
                            chunkSize = typeof GV.site.chunksize !== "undefined" ? GV.site.chunksize : 5242880;
                        $(elements).each(function() {
                            var GUID = WebUploader.Base.guid();
                            if ($(this).attr("initialized")) {
                                return true;
                            }
                            $(this).attr("initialized", true);
                            var that = this;
                            var id = $(this).prop("id") || $(this).prop("name");
                            // 是否多图片上传
                            var multiple = $(that).data('multiple');
                            var type = $(that).data('type');
                            if (type == 'image') {
                                var formData = { thumb: GV.site.upload_thumb_water, watermark: GV.site.upload_thumb_water_pic };
                            } else {
                                var formData = chunking ? { chunkid: GUID } : {};
                            }
                            //填充ID
                            var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
                            //预览ID
                            var preview_id = $(that).data("preview-id") ? $(that).data("preview-id") : "";
                            var previewtpl = '<li class="file-item thumbnail"><img data-image data-original="{{d.url}}" src="{{d.url}}"><div class="file-panel">' + (multiple ? '<i class="iconfont icon-yidong move-picture"></i>' : '') + '<i class="iconfont icon-tailor cropper" data-input-id="' + input_id + '"></i> <i class="iconfont icon-trash remove-picture"></i></div></li>';
                            // 允许上传的后缀
                            var $ext = type == 'image' ? GV.site.upload_image_ext : GV.site.upload_file_ext;
                            // 图片限制大小
                            var $size = type == 'image' ? GV.site.upload_image_size * 1024 : GV.site.upload_file_size * 1024;
                            // 优化retina, 在retina下这个值是2
                            /*var ratio = window.devicePixelRatio || 1;
                            // 缩略图大小
                            var thumbnailWidth = 100 * ratio;
                            var thumbnailHeight = 100 * ratio;*/

                            var uploader = WebUploader.create({
                                // 选完图片后，是否自动上传。
                                auto: true,
                                // 去重
                                duplicate: true,
                                // 不压缩图片
                                resize: false,
                                compress: false,
                                pick: {
                                    id: '#' + id,
                                    multiple: multiple
                                },
                                chunked: chunking,
                                chunkSize: chunkSize,
                                server: type == 'image' ? GV.image_upload_url : GV.file_upload_url,
                                // 图片限制大小
                                fileSingleSizeLimit: $size,
                                // 只允许选择图片文件。
                                accept: {
                                    title: type == 'image' ? 'Images' : 'Files',
                                    extensions: $ext,
                                    mimeTypes: type == 'image' ? 'image/jpg,image/jpeg,image/bmp,image/png,image/gif' : '',
                                },
                                // 自定义参数
                                formData: formData,
                            })

                            element.on('tab', function(data) {
                                uploader.refresh();
                            });

                            // 文件上传过程中创建进度条实时显示。
                            uploader.on('uploadProgress', function(file, percentage) {
                                $(that).find('.webuploader-pick').html("<i class='layui-icon layui-icon-upload'></i> 上传" + Math.floor(percentage * 100) + "%");
                            });

                            // 文件上传成功
                            uploader.on('uploadSuccess', function(file, response) {
                                var ok = function(file, response) {
                                    if (response.code == 1) {
                                        var button = $('#' + file.id);
                                        if (button) {
                                            //如果有文本框则填充
                                            if (input_id) {
                                                var urlArr = [];
                                                var inputObj = $("#" + input_id);
                                                if (multiple && inputObj.val() !== "") {
                                                    urlArr.push(inputObj.val());
                                                }
                                                urlArr.push(response.url);
                                                inputObj.val(urlArr.join(",")).trigger("change");
                                            }
                                        }
                                    }else{
                                        yzn.msg.error(response.info);
                                    }
                                }
                                if (type == 'file' && chunking) {
                                    //合并
                                    $.ajax({
                                        url: GV.file_upload_url,
                                        dataType: "json",
                                        type: "POST",
                                        data: {
                                            chunkid: GUID,
                                            action: 'merge',
                                            filesize: file.size,
                                            filename: file.name,
                                            id: file.id,
                                            chunks: Math.floor(file.size / chunkSize + (file.size % chunkSize > 1 ? 1 : 0)),
                                        },
                                        success: function(res) {
                                            ok(file, res);
                                        },
                                    })
                                } else {
                                    ok(file, response);
                                }
                                if (typeof onUploadSuccess === 'function') {
                                    var result = onUploadSuccess.call(file, response);
                                    if (result === false)
                                        return;
                                }
                            });
                            // 文件上传失败，显示上传出错。
                            /*uploader.on('uploadError', function(file) {
                                var $li = $('#' + file.id);
                                $li.find('.file-state').html('<div class="layui-bg-red">服务器错误</div>');
                            });*/
                            // 完成上传完了，成功或者失败，先删除进度条。
                            uploader.on('uploadComplete', function(file) {
                                setTimeout(function() {
                                    $(that).find('.webuploader-pick').html("<i class='layui-icon layui-icon-upload'></i> 上传");
                                    uploader.refresh();
                                }, 500);
                            });
                            // 文件验证不通过
                            uploader.on('error', function(type) {
                                switch (type) {
                                    case 'Q_TYPE_DENIED':
                                        layer.alert('类型不正确，只允许上传后缀名为：' + $ext + '，请重新上传！', { icon: 5 })
                                        break;
                                    case 'F_EXCEED_SIZE':
                                        layer.alert('不得超过' + ($size / 1024) + 'kb，请重新上传！', { icon: 5 })
                                        break;
                                }
                            });
                            // 如果是多图上传，则实例化拖拽
                            if (preview_id && multiple) {
                                $("#" + preview_id).dragsort({
                                    dragSelector: ".move-picture",
                                    dragEnd: function() {
                                        $("#" + preview_id).trigger("fa.preview.change");
                                    },
                                    placeHolderTemplate: '<li class="file-item thumbnail" style="border:1px #009688 dashed;"></li>'
                                })
                            }
                            //刷新隐藏textarea的值
                            var refresh = function(name) {

                            }
                            if (preview_id && input_id) {
                                layui.define('laytpl', function(exports) {
                                    var laytpl = layui.laytpl;
                                    $(document.body).on("keyup change", "#" + input_id, function(e) {
                                        var inputStr = $("#" + input_id).val();
                                        var inputArr = inputStr.split(/\,/);
                                        $("#" + preview_id).empty();
                                        var tpl = $("#" + preview_id).data("template") ? $("#" + preview_id).data("template") : "";
                                        var extend = $("#" + preview_id).next().is("textarea") ? $("#" + preview_id).next("textarea").val() : "{}";
                                        var json = {};
                                        try {
                                            json = JSON.parse(extend);
                                        } catch (e) {}
                                        $.each(inputArr, function(i, j) {
                                            if (!j) {
                                                return true;
                                            }
                                            var suffix = /[\.]?([a-zA-Z0-9]+)$/.exec(j);
                                            suffix = suffix ? suffix[1] : 'file';
                                            var value = (json && typeof json[i] !== 'undefined' ? json[i] : null);
                                            var data = { url: j, data: $(that).data(), key: i, index: i, value: value, row: value, suffix: suffix };
                                            laytpl(previewtpl).render(data, function(html) {
                                                $("#" + preview_id).append(html);
                                            });
                                        });
                                        refresh($("#" + preview_id).data("name"));
                                    });
                                    $("#" + input_id).trigger("change");
                                })
                            }
                            if (preview_id) {
                                //监听文本框改变事件
                                $("#" + preview_id).on('change keyup', "input,textarea,select", function() {
                                    refresh($(this).closest("ul").data("name"));
                                });
                                // 监听事件
                                $(document.body).on("fa.preview.change", "#" + preview_id, function() {
                                    var urlArr = [];
                                    $("#" + preview_id + " [data-original]").each(function(i, j) {
                                        urlArr.push($(this).data("original"));
                                    });
                                    if (input_id) {
                                        $("#" + input_id).val(urlArr.join(","));
                                    }
                                    refresh($("#" + preview_id).data("name"));
                                });
                                // 移除按钮事件
                                $(document.body).on("click", "#" + preview_id + " .remove-picture", function() {
                                    $(this).closest("li").remove();
                                    $("#" + preview_id).trigger("fa.preview.change");
                                });
                            }
                            // 将上传实例存起来
                            webuploaders.push(uploader);
                        });
                    })
                }
            },
        },
        bindevent: function(form) {
            form = typeof form === 'object' ? form : $(form);
            var events = yznForm.events;
            events.init();
            events.selectpage(form);
            events.fieldlist(form);
            events.faselect(form);
            events.citypicker(form);
            events.datetimepicker(form);
            events.tagsinput(form);
            events.colorpicker(form);
            events.ueditor(form);
            events.cropper();
            events.xmSelect();
            events.upload_image('.webUpload');
        }
    }
    yznForm.bindevent($(".layui-form"));

    //修复含有fixed-footer类的body边距
    if ($(".fixed-footer").size() > 0) {
        $(document.body).css("padding-bottom", $(".fixed-footer").outerHeight());
    }
    //修复不在iframe时layer-footer隐藏的问题
    if ($(".layer-footer").size() > 0 && self === top) {
        $(".layer-footer").show();
    }

    /**
     * 监听表单提交
     * @attr action 请求地址
     * @attr data-form 表单DOM
     */
    form.on('submit(layui-Submit)', function(data) {
        var _form = '',
            that = $(this),
            text = that.text(),
            opt = {},
            def = { pop: false, refresh: true, jump: false, callback: null, time: 3000 };
        if ($(this).attr('data-form')) {
            _form = $(that.attr('data-form'));
        } else {
            _form = that.parents('form');
        }
        if (that.attr('lay-data')) {
            opt = new Function('return ' + that.attr('lay-data'))();
        }
        opt = Object.assign({}, def, opt);

        that.prop('disabled', true);
        yzn.request.post({
            url: _form.attr('action'),
            data: _form.serialize(),
        }, function(res) {
            if (opt.callback) {
                opt.callback(that, res);
            } else {
                layer.msg(res.msg, { icon: 1 });
                //that.text(res.msg);
                setTimeout(function() {
                    that.text(text).prop('disabled', false);
                    if (opt.pop == true) {
                        if (opt.refresh == true) {
                            parent.location.reload();
                        } else if (opt.jump == true && res.url != '') {
                            parent.location.href = res.url;
                        }
                        parent.layui.layer.closeAll();
                    } else if (opt.refresh == true) {
                        if (res.url != '') {
                            location.href = res.url;
                        } else {
                            history.back(-1);
                        }
                    }
                }, opt.time);
            }
        }, function(res) {
            layer.msg(res.msg, { icon: 2 });
            setTimeout(function() {
                that.prop('disabled', false);
            }, opt.time);
        });
        return false;
    });

    exports(MOD_NAME, yznForm);
});