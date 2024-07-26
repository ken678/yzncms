/**
 * http://www.apache.org/licenses/LICENSE-2.0
 * Copyright (c) 2018 http://yzncms.com All rights reserved.
 * Author: 御宅男 <530765310@qq.com>
 * Original reference: https://gitee.com/karson/fastadmin
 */
define(['jquery', 'dropzone', 'layui'], function ($, Dropzone, layui) {

    var Upload = {
            list: {},
            options: {},
            config: {
                container: document.body,
                classname: '.plupload:not([initialized]),.faupload:not([initialized])',
                previewtpl: '<li class="file-item thumbnail"><a href="javascript:;"><img data-image onerror="this.src=\'' + Yzn.api.fixurl("admin/ajax/icon") + '?suffix={{d.suffix}}\';this.onerror=null;"  data-original="{{d.url}}" src="{{d.url}}"><div class="file-panel">{{- d.data.multiple==true ? "<i class=\'iconfont icon-drag-move-2-fill move-picture\'></i>" : "" }} {{# if(Config.modulename === \'admin\'){ }}<i class="iconfont icon-crop-line btn-cropper" data-input-id="{{d.data.inputId}}"></i>{{#  } }} <i class="iconfont icon-delete-bin-line remove-picture"></i></div></a></li>',     
            },
            events: {
                //初始化
                onInit: function () {

                },
                //上传成功的回调
                onUploadSuccess: function (up, ret, file) {
                    var button = up.element;
                    var onUploadSuccess = up.options.onUploadSuccess;
                    var data = typeof ret.data !== 'undefined' ? ret.data : ret;
                    //上传成功后回调
                    if (button) {
                        //如果有文本框则填充
                        var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                        if (input_id) {
                            var urlArr = [];
                            var inputObj = $("#" + input_id);
                            if ($(button).data("multiple") && inputObj.val() !== "") {
                                urlArr.push(inputObj.val());
                            }
                            var url = Config.upload.fullmode ? Yzn.api.cdnurl(data.url) : data.url;
                            var url = data.url;
                            urlArr.push(url);
                            inputObj.val(urlArr.join(",")).trigger("change").trigger("validate");
                        }
                        //如果有回调函数
                        var onDomUploadSuccess = $(button).data("upload-success");
                        if (onDomUploadSuccess) {
                            if (typeof onDomUploadSuccess !== 'function' && typeof Upload.api.custom[onDomUploadSuccess] === 'function') {
                                onDomUploadSuccess = Upload.api.custom[onDomUploadSuccess];
                            }
                            if (typeof onDomUploadSuccess === 'function') {
                                var result = onDomUploadSuccess.call(button, data, ret);
                                if (result === false)
                                    return;
                            }
                        }
                    }

                    if (typeof onUploadSuccess === 'function') {
                        var result = onUploadSuccess.call(button, data, ret);
                        if (result === false)
                            return;
                    }
                },
                //上传错误的回调
                onUploadError: function (up, ret, file) {
                    var button = up.element;
                    var onUploadError = up.options.onUploadError;
                    var data = typeof ret.data !== 'undefined' ? ret.data : ret;
                    if (button) {
                        var onDomUploadError = $(button).data("upload-error");
                        if (onDomUploadError) {
                            if (typeof onDomUploadError !== 'function' && typeof Upload.api.custom[onDomUploadError] === 'function') {
                                onDomUploadError = Upload.api.custom[onDomUploadError];
                            }
                            if (typeof onDomUploadError === 'function') {
                                var result = onDomUploadError.call(button, data, ret);
                                if (result === false)
                                    return;
                            }
                        }
                    }

                    if (typeof onUploadError === 'function') {
                        var result = onUploadError.call(button, data, ret);
                        if (result === false) {
                            return;
                        }
                    }
                    Toastr.error(ret.msg.toString().replace(/(<([^>]+)>)/gi, "") + "(code:" + ret.code + ")");
                },
                //服务器响应数据后
                onUploadResponse: function (response, up, file) {
                    try {
                        var ret = typeof response === 'object' ? response : JSON.parse(response);
                        if (!ret.hasOwnProperty('code')) {
                            $.extend(ret, {code: -2, msg: response, data: null});
                        }
                    } catch (e) {
                        var ret = {code: -1, msg: e.message, data: null};
                    }
                    return ret;
                },
                //上传全部结束后
                onUploadComplete: function (up, files) {
                    var button = up.element;
                    var onUploadComplete = up.options.onUploadComplete;
                    if (button) {
                        var onDomUploadComplete = $(button).data("upload-complete");
                        if (onDomUploadComplete) {
                            if (typeof onDomUploadComplete !== 'function' && typeof Upload.api.custom[onDomUploadComplete] === 'function') {
                                onDomUploadComplete = Upload.api.custom[onDomUploadComplete];
                            }
                            if (typeof onDomUploadComplete === 'function') {
                                var result = onDomUploadComplete.call(button, files);
                                if (result === false)
                                    return;
                            }
                        }
                    }

                    if (typeof onUploadComplete === 'function') {
                        var result = onUploadComplete.call(button, files);
                        if (result === false) {
                            return;
                        }
                    }
                }
            },
            api: {
                //上传接口
                upload: function (element, onUploadSuccess, onUploadError, onUploadComplete) {
                    element = typeof element === 'undefined' ? Upload.config.classname : element;
                    $(element, Upload.config.container).each(function () {
                        if ($(this).attr("initialized")) {
                            return true;
                        }
                        $(this).attr("initialized", true);
                        var that = this;
                        var id = $(this).prop("id") || $(this).prop("name") || Dropzone.uuidv4();
                        var url = $(this).data("url");
                        var maxsize = $(this).data("maxsize");
                        var maxcount = $(this).data("maxcount");
                        var mimetype = $(this).data("mimetype");
                        var multipart = $(this).data("multipart");
                        var multiple = $(this).data("multiple");
                        var type = $(this).data("type");
                        type = typeof type !== "undefined" ? (type.indexOf('image') > -1 ? 'image':'file'): 'image';

                        //填充ID
                        var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
                        //预览ID
                        var preview_id = $(that).data("preview-id") ? $(that).data("preview-id") : "";

                        //上传URL
                        url = url ? url : Config.upload.uploadurl;
                        url = Yzn.api.fixurl(url) +'?dir='+ type;
                        var chunking = false, chunkSize = Config.upload.chunksize || 2097152, timeout = Config.upload.timeout || 600000;

                        //最大可上传文件大小
                        maxsize = typeof maxsize !== "undefined" ? maxsize : Config.upload.maxsize;
                        //文件类型
                        mimetype = typeof mimetype !== "undefined" ? mimetype : Config.upload.mimetype;
                        //请求的表单参数
                        multipart = typeof multipart !== "undefined" ? multipart : Config.upload.multipart;
                        //是否支持批量上传
                        multiple = typeof multiple !== "undefined" ? multiple : Config.upload.multiple;
                        //后缀特殊处理
                        mimetype = mimetype.split(",").map(function (k) {
                            return k.indexOf("/") > -1 ? k : (!k || k === "*" || k.charAt(0) === "." ? k : "." + k);
                        }).join(",");
                        mimetype = mimetype === '*' ? null : mimetype;

                        //最大文件限制转换成mb
                        var maxFilesize = (function (maxsize) {
                            var matches = maxsize.toString().match(/^([0-9\.]+)(\w+)$/);
                            var size = matches ? parseFloat(matches[1]) : parseFloat(maxsize),
                                unit = matches ? matches[2].toLowerCase() : 'b';
                            var unitDict = {'b': 0, 'k': 1, 'kb': 1, 'm': 2, 'mb': 2, 'gb': 3, 'g': 3, 'tb': 4, 't': 4};
                            var y = typeof unitDict[unit] !== 'undefined' ? unitDict[unit] : 0;
                            var bytes = size * Math.pow(1024, y);
                            return bytes / Math.pow(1024, 2);
                        }(maxsize));

                        var options = $(this).data() || {};
                        options = $.extend(true, {}, options, $(this).data("upload-options") || {});
                        delete options.success;
                        delete options.url;
                        multipart = $.isArray(multipart) ? {} : multipart;
                        var params = $(this).data("params") || {};
                        var category = typeof params.category !== 'undefined' ? params.category : ($(this).data("category") || '');
                        if (category) {
                            // multipart.category = category;
                        }

                        Upload.list[id] = new Dropzone(this, $.extend({
                            url: url,
                            params: function (files, xhr, chunk) {
                                var params = multipart;
                                if (chunk) {
                                    return $.extend({}, params, {
                                        filesize: chunk.file.size,
                                        filename: chunk.file.name,
                                        chunkid: chunk.file.upload.uuid,
                                        chunkindex: chunk.index,
                                        chunkcount: chunk.file.upload.totalChunkCount,
                                        chunksize: this.options.chunkSize,
                                        chunkfilesize: chunk.dataBlock.data.size,
                                        width: chunk.file.width || 0,
                                        height: chunk.file.height || 0,
                                        type: chunk.file.type,
                                    });
                                }
                                return params;
                            },
                            chunking: chunking,
                            chunkSize: chunkSize,
                            maxFilesize: maxFilesize,
                            acceptedFiles: mimetype,
                            maxFiles: (maxcount && parseInt(maxcount) > 1 ? maxcount : (multiple ? null : 1)),
                            timeout: timeout,
                            parallelUploads: 1,
                            previewsContainer: false,
                            dictDefaultMessage: "将文件拖到此处上载",
                            dictFallbackMessage: "您的浏览器不支持拖放文件上传",
                            dictFallbackText: "请使用下面的备用表单像以前一样上传您的文件",
                            dictFileTooBig: "当前上传({{filesize}}M)，最大允许上传文件大小:{{maxFilesize}}M",
                            dictInvalidFileType: "不允许上传的文件类型",
                            dictResponseError: "服务端响应(Code:{{statusCode}})",
                            dictCancelUpload: "取消上传",
                            dictUploadCanceled: "上传已取消",
                            dictCancelUploadConfirmation: "确定取消上传？",
                            dictRemoveFile: "移除文件",
                            dictMaxFilesExceeded: "你最多允许上传 {{maxFiles}} 个文件",
                            init: function () {
                                Upload.events.onInit.call(this);
                                //必须添加dz-message，否则点击icon无法唤起上传窗口
                                $(">i", this.element).addClass("dz-message");
                                this.options.elementHtml = $(this.element).html();
                            },
                            sending: function (file, xhr, formData) {
                                if (typeof file.category !== 'undefined') {
                                    formData.append('category', file.category);
                                }
                            },
                            addedfile: function (file) {
                                var params = $(this.element).data("params") || {};
                                var category = typeof params.category !== 'undefined' ? params.category : ($(this.element).data("category") || '');
                                file.category = typeof category === 'function' ? category.call(this, file) : category;
                            },
                            addedfiles: function (files) {
                                if (this.options.maxFiles && (!this.options.maxFiles || this.options.maxFiles > 1) && this.options.inputId) {
                                    var inputObj = $("#" + this.options.inputId);
                                    if (inputObj.length > 0) {
                                        var value = $.trim(inputObj.val());
                                        var nums = value === '' ? 0 : value.split(/\,/).length;
                                        var remain = this.options.maxFiles - nums;
                                        if (remain === 0 || files.length > remain) {
                                            files = Array.prototype.slice.call(files, remain);
                                            for (var i = 0; i < files.length; i++) {
                                                this.removeFile(files[i]);
                                            }
                                            notice.error({ message: "你最多允许上传 "+this.options.maxFiles+" 个文件" });
                                        }
                                    }
                                }
                            },
                            success: function (file, response) {
                                var ret = Upload.events.onUploadResponse(response, this, file);
                                file.ret = ret;
                                if (ret.code === 1) {
                                    Upload.events.onUploadSuccess(this, ret, file);
                                } else {
                                    Upload.events.onUploadError(this, ret, file);
                                }
                            },
                            error: function (file, response, xhr) {
                                var responseObj = $("<div>" + (xhr && typeof xhr.responseText !== 'undefined' ? xhr.responseText : response) + "</div>");
                                responseObj.find("style, title, script").remove();
                                var msg = responseObj.text() || '网络错误!';
                                var ret = {code: 0, data: null, msg: msg};
                                Upload.events.onUploadError(this, ret, file);
                            },
                            uploadprogress: function (file, progress, bytesSent) {
                                if (file.upload.chunked) {
                                    $(this.element).prop("disabled", true).html("<i class='iconfont icon-upload-line'></i> " + '上传' + Math.floor((file.upload.bytesSent / file.size) * 100) + "%");
                                }
                            },
                            totaluploadprogress: function (progress, bytesSent) {
                                if (this.getActiveFiles().length > 0 && !this.options.chunking) {
                                    $(this.element).prop("disabled", true).html("<i class='iconfont icon-upload-line'></i> " + '上传' + Math.floor(progress) + "%");
                                }
                            },
                            queuecomplete: function () {
                                Upload.events.onUploadComplete(this, this.files);
                                this.removeAllFiles(true);
                                $(this.element).prop("disabled", false).html(this.options.elementHtml);
                            },
                            chunkSuccess: function (chunk, file, response) {
                            },
                            chunksUploaded: function (file, done) {
                                var that = this;
                                Yzn.api.ajax({
                                    url: this.options.url,
                                    data: $.extend({}, multipart, {
                                        action: 'merge',
                                        filesize: file.size,
                                        filename: file.name,
                                        chunkid: file.upload.uuid,
                                        chunkcount: file.upload.totalChunkCount,
                                    })
                                }, function (data, ret) {
                                    done(JSON.stringify(ret));
                                    return false;
                                }, function (data, ret) {
                                    file.accepted = false;
                                    that._errorProcessing([file], ret.msg);
                                });
                            },
                            onUploadSuccess: onUploadSuccess,
                            onUploadError: onUploadError,
                            onUploadComplete: onUploadComplete,
                        }, Upload.options, options));

                        //拖动排序
                        if (preview_id && multiple) {
                            require(['dragsort'], function () {
                                $("#" + preview_id).dragsort({
                                    dragSelector: ".move-picture",
                                    dragEnd: function () {
                                        $("#" + preview_id).trigger("fa.preview.change");
                                    },
                                    placeHolderTemplate: '<li class="file-item thumbnail" style="border:1px #009688 dashed;"></li>'
                                });
                            });
                        }
                        //刷新隐藏textarea的值
                        var refresh = function (name) {
                            var data = {};
                            var textarea = $("textarea[name='" + name + "']");
                            var container = textarea.prev("ul");
                            $.each($("input,select,textarea", container).serializeArray(), function (i, j) {
                                var reg = /\[?(\w+)\]?\[(\w+)\]$/g;
                                var match = reg.exec(j.name);
                                if (!match)
                                    return true;
                                if (!isNaN(match[2])) {
                                    data[i] = j.value;
                                } else {
                                    match[1] = "x" + parseInt(match[1]);
                                    if (typeof data[match[1]] === 'undefined') {
                                        data[match[1]] = {};
                                    }
                                    data[match[1]][match[2]] = j.value;
                                }
                            });
                            var result = [];
                            $.each(data, function (i, j) {
                                result.push(j);
                            });
                            textarea.val(JSON.stringify(result));
                        };
                        if (preview_id && input_id) {
                            $(document.body).on("keyup change", "#" + input_id, function (e) {
                                var inputStr = $("#" + input_id).val();
                                var inputArr = inputStr.split(/\,/);

                                var previewObj = $("#" + preview_id);
                                previewObj.empty();
                                var tpl = previewObj.data("template") ? previewObj.data("template") : "";
                                var extend = previewObj.next().is("textarea") ? previewObj.next("textarea").val() : "{}";
                                var json = {};
                                try {
                                    json = JSON.parse(extend);
                                } catch (e) {
                                }
                                $.each(inputArr, function (i, j) {
                                    if (!j) {
                                        return true;
                                    }
                                    var suffix = /[\.]?([a-zA-Z0-9]+)$/.exec(j);
                                    suffix = suffix ? suffix[1] : 'file';
                                    j = Config.upload.fullmode ? Yzn.api.cdnurl(j) : j;
                                    var value = (json && typeof json[i] !== 'undefined' ? json[i] : null);
                                    var data = {url: j, fullurl: Yzn.api.cdnurl(j), data: $(that).data(), key: i, index: i, value: value, row: value, suffix: suffix};
                                    var data = {url: j, data: $(that).data(), key: i, index: i, value: value, row: value, suffix: suffix};
                                    layui.laytpl(tpl ? $("#" + tpl).html() : Upload.config.previewtpl).render(data, function(html) {
                                        previewObj.append(html);
                                    });
                                });
                                refresh(previewObj.data("name"));
                            });
                            $("#" + input_id).trigger("change");
                        }
                        if (preview_id) {
                            //监听文本框改变事件
                            $("#" + preview_id).on('change keyup', "input,textarea,select", function () {
                                refresh($(this).closest("ul").data("name"));
                            });
                            // 监听事件
                            $(document.body).on("fa.preview.change", "#" + preview_id, function () {
                                var urlArr = [];
                                $("#" + preview_id + " [data-original]").each(function (i, j) {
                                    urlArr.push($(this).data("original"));
                                });
                                if (input_id) {
                                    $("#" + input_id).val(urlArr.join(","));
                                }
                                refresh($("#" + preview_id).data("name"));
                            });
                            // 移除按钮事件
                            $(document.body).on("click", "#" + preview_id + " .remove-picture", function () {
                                $(this).closest("li").remove();
                                $("#" + preview_id).trigger("fa.preview.change");
                            });
                        }
                        if (input_id) {
                            $("#" + input_id).closest("form").on("reset", function () {
                                setTimeout($.proxy(function () {
                                    $("#" + input_id, this).trigger("change");
                                }, this), 0);
                            });
                            //粘贴上传、拖拽上传
                            $("body").on('paste drop', "#" + input_id, function (event) {
                                var originEvent = event.originalEvent;
                                var button = $(".plupload[data-input-id='" + $(this).attr("id") + "'],.faupload[data-input-id='" + $(this).attr("id") + "']");
                                if (event.type === 'paste' && originEvent.clipboardData && originEvent.clipboardData.items) {
                                    var items = originEvent.clipboardData.items;
                                    if ((items.length === 1 && items[0].type.indexOf("text") > -1) || (items.length === 2 && items[1].type.indexOf("text") > -1)) {

                                    } else {
                                        Upload.list[button.attr("id")].paste(originEvent);
                                        return false;
                                    }
                                }
                                if (event.type === 'drop' && originEvent.dataTransfer && originEvent.dataTransfer.files) {
                                    Upload.list[button.attr("id")].drop(originEvent);
                                    return false;
                                }
                            });
                        }
                    });
                },
                /**
                 * @deprecated Use upload instead.
                 */
                plupload: function (element, onUploadSuccess, onUploadError, onUploadComplete) {
                    return Upload.api.upload(element, onUploadSuccess, onUploadError, onUploadComplete);
                },
                /**
                 * @deprecated Use upload instead.
                 */
                faupload: function (element, onUploadSuccess, onUploadError, onUploadComplete) {
                    return Upload.api.upload(element, onUploadSuccess, onUploadError, onUploadComplete);
                },
                // AJAX异步上传
                send: function (file, onUploadSuccess, onUploadError, onUploadComplete) {
                    var index = layer.msg('上传中', {offset: 't', time: 0, icon: 0});
                    var id = "dropzone-" + Dropzone.uuidv4();
                    $('<button type="button" id="' + id + '" class="btn btn-danger hidden faupload" />').appendTo("body");
                    $("#" + id).data("upload-complete", function (files) {
                        layer.close(index);
                        Upload.list[id].removeAllFiles(true);
                    });
                    Upload.api.upload("#" + id, onUploadSuccess, onUploadError, onUploadComplete);
                    setTimeout(function () {
                        Upload.list[id].addFile(file);
                    }, 1);
                },
                custom: {
                    //自定义上传完成回调
                    afteruploadcallback: function (response) {
                        console.log(this, response);
                        alert("Custom Callback,Response URL:" + response.url);
                    },
                }
            }
        };

   return Upload;
});
