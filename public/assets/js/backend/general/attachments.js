define(['jquery', 'table', 'form', 'upload'], function($, Table, Form, Upload) {
    var form = layui.form,
        table = layui.table;

    var Controller = {
        index: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
                delete_url: "general.attachments/del",
            };

            Table.render({
                init: Table.init,
                elem: Table.init.table_elem,
                toolbar: ['refresh', 'delete'],
                url: 'general.attachments/index',
                lineStyle: 'height: 50px;',
                cols: [
                    [
                        { type: 'checkbox', fixed: 'left' },
                        { field: 'id', width: 80, title: 'ID', sort: true },
                        { field: 'admin_id', width: 80, title: '用户', hide: true, addClass: "selectpage", extend: "data-source='auth.manager/index' data-field='username'" },
                        { field: 'name', title: '名称', searchOp: 'like' },
                        { field: 'path', width: 70, align: "center", title: '图片', search: false, templet: Controller.api.formatter.thumb },
                        { field: 'path', width: 450, align: "center", title: '物理路径', templet: '<div><a class="layui-btn layui-btn layui-btn-xs" href="{{d.path}}" target="_blank">{{d.path}}</a></div>', searchOp: 'like' },
                        { field: 'size', width: 100, title: '大小', sort: true },
                        { field: 'ext', width: 100, title: '类型', searchOp: 'like' },
                        { field: 'mime', title: 'Mime类型', selectList: { 'image/*': '图片', 'audio/*': '音频', 'video/*': '视频', 'text/*': '文档', 'application/*': '应用', 'zip,rar,7z,tar': '压缩包' }, extend: "lay-search lay-creatable" },
                        { field: 'driver', width: 90, title: '存储引擎', searchOp: 'like' },
                        { field: 'create_time', width: 170, title: '上传时间', search: 'range' },
                        { width: 60, title: '操作', templet: Table.formatter.tool, operat: ['delete'] }
                    ]
                ],
                page: {}
            });

            Table.api.bindevent();
        },
        select: function() {
            Table.init = {
                table_elem: '#currentTable',
                table_render_id: 'currentTable',
            };

            var multiple = Backend.api.query('multiple');
            multiple = multiple == 'true' ? true : false;

            var toolbar = ['refresh', [{
                html: '<button type="button" class="layui-btn layui-btn-sm faupload" data-multiple="true" data-mimetype="' + Config.mimetype + '"><i class="iconfont icon-upload-line"></i> 上传</button>'
            }]];
            if (multiple) {
                toolbar.push([{
                    html: '&nbsp;<button class="layui-btn layui-btn-danger layui-btn-sm btn-choose-multi"><i class="iconfont icon-check-line"></i> 选择</button>'
                }]);
            }

            Table.render({
                init: Table.init,
                elem: Table.init.table_elem,
                toolbar: toolbar,
                url: 'general.attachments/index',
                lineStyle: 'height: 50px;',
                cols: [
                    [
                        { type: 'checkbox', fixed: 'left', hide: (multiple ? false : true) },
                        { field: 'id', width: 60, title: 'ID', sort: true },
                        { field: 'name', title: '名称' },
                        { field: 'path', width: 70, align: "center", title: '图片', search: false, templet: Controller.api.formatter.thumb },
                        { field: 'size', width: 100, title: '大小', sort: true },
                        { field: 'mime', width: 120, title: 'Mime类型' },
                        { field: 'create_time', width: 160, title: '上传时间', search: 'range' },
                        { fixed: 'right', width: 85, title: '操作', toolbar: '#barTool' }
                    ]
                ],
                page: {},
                done: function(res, curr, count) {
                    Upload.api.upload('.faupload', function() {
                        $(".btn-refresh").trigger("click");
                    });
                }
            });
            var urlArr = [];

            table.on('checkbox', function(obj) {
                var checkStatus = table.checkStatus('currentTable').data;
                for (var i = 0; i < checkStatus.length; i++) {
                    urlArr.push(checkStatus[i]['path']);
                }
            })
            
            //选择单个
            $(document).on('click', '.btn-chooseone', function() {
                var that = $(this),
                    index = that.parents('tr').eq(0).data('index'),
                    tr = $('.layui-table-body').find('tr[data-index="' + index + '"]'),
                    href = !that.attr('data-href') ? that.attr('href') : that.attr('data-href');
                Yzn.api.close({ url: href, multiple: multiple });
            });

            // 选中多个 todo翻页失效
            $(document).on("click", ".btn-choose-multi", function() {
                Yzn.api.close({ url: urlArr.join(","), multiple: multiple });
            });

            Table.api.bindevent();
        },
        cropper: function() {
            require(['../libs/cropper/cropper.min'], function() {
                var URL = window.URL || window.webkitURL;
                var $image = $('#image');
                var $download = $('#download');
                /*var $dataX = $('#dataX');
                var $dataY = $('#dataY');*/
                var $dataHeight = $('#dataHeight');
                var $dataWidth = $('#dataWidth');
                /*var $dataRotate = $('#dataRotate');
                var $dataScaleX = $('#dataScaleX');
                var $dataScaleY = $('#dataScaleY');*/
                var options = {
                    aspectRatio: 16 / 9,
                    preview: '.img-preview',
                    crop: function(e) {
                        /*$dataX.val(Math.round(e.detail.x));
                        $dataY.val(Math.round(e.detail.y));*/
                        $dataHeight.val(Math.round(e.detail.height));
                        $dataWidth.val(Math.round(e.detail.width));
                        /*$dataRotate.val(e.detail.rotate);
                        $dataScaleX.val(e.detail.scaleX);
                        $dataScaleY.val(e.detail.scaleY);*/
                    }
                };
                var originalImageURL = $image.attr('src');
                var uploadedImageName = 'cropped.jpg';
                var uploadedImageType = 'image/jpeg';
                var uploadedImageURL;

                // Cropper
                $image.cropper(options);

                $(document).on("click", ".getCroppedCanvas", function() {
                    layer.open({
                        type: 1,
                        title: false,
                        area: '340px',
                        content: $('#getCroppedCanvasModal')
                    });
                })

                //确认事件
                $(document).on("click", ".btn-submit", function() {
                    var data = $image.cropper('getData');
                    var dataURI = $image.cropper('getCroppedCanvas').toDataURL('image/png');
                    data.dataURI = dataURI;
                    Yzn.api.close(data);
                });


                //取消事件
                $(document).on("click", ".btn-cancel", function() {
                    var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
                    parent.layer.close(index);
                });

                // Buttons
                if (!$.isFunction(document.createElement('canvas').getContext)) {
                    $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
                }

                if (typeof document.createElement('cropper').style.transition === 'undefined') {
                    $('button[data-method="rotate"]').prop('disabled', true);
                    $('button[data-method="scale"]').prop('disabled', true);
                }

                // 选择比例
                $('.docs-toggles').on('click', 'button', function() {
                    var $this = $(this);
                    var name = $this.data('name');
                    var type = $this.prop('type');
                    var cropBoxData;
                    var canvasData;
                    if (!$image.data('cropper')) {
                        return;
                    }
                    options[name] = $this.val();
                    $image.cropper('destroy').cropper(options);
                });

                // 按钮组操作
                $('.docs-buttons').on('click', '[data-method]', function() {
                    var $this = $(this);
                    var data = $this.data();
                    var cropper = $image.data('cropper');
                    var cropped;
                    var $target;
                    var result;

                    if ($this.prop('disabled') || $this.hasClass('disabled')) {
                        return;
                    }

                    if (cropper && data.method) {
                        data = $.extend({}, data); // Clone a new one
                        if (typeof data.target !== 'undefined') {
                            $target = $(data.target);
                            if (typeof data.option === 'undefined') {
                                try {
                                    data.option = JSON.parse($target.val());
                                } catch (e) {
                                    console.log(e.message);
                                }
                            }
                        }
                        cropped = cropper.cropped;
                        switch (data.method) {
                            case 'rotate':
                                if (cropped && options.viewMode > 0) {
                                    $image.cropper('clear');
                                }
                                break;
                            case 'getCroppedCanvas':
                                if (uploadedImageType === 'image/jpeg') {
                                    if (!data.option) {
                                        data.option = {};
                                    }
                                    data.option.fillColor = '#fff';
                                }
                                break;
                        }
                        result = $image.cropper(data.method, data.option, data.secondOption);
                        switch (data.method) {
                            case 'rotate':
                                if (cropped && options.viewMode > 0) {
                                    $image.cropper('crop');
                                }
                                break;
                            case 'scaleX':
                            case 'scaleY':
                                $(this).data('option', -data.option);
                                break;
                            case 'getCroppedCanvas':
                                if (result) {
                                    // Bootstrap's Modal
                                    $('#getCroppedCanvasModal').find('.modal-body').html(result);
                                    if (!$download.hasClass('disabled')) {
                                        download.download = uploadedImageName;
                                        $download.attr('href', result.toDataURL(uploadedImageType));
                                    }
                                }
                                break;
                            case 'destroy':
                                if (uploadedImageURL) {
                                    URL.revokeObjectURL(uploadedImageURL);
                                    uploadedImageURL = '';
                                    $image.attr('src', originalImageURL);
                                }
                                break;
                        }
                        if ($.isPlainObject(result) && $target) {
                            try {
                                $target.val(JSON.stringify(result));
                            } catch (e) {
                                console.log(e.message);
                            }
                        }
                    }
                });

                // 键盘支持
                $(document.body).on('keydown', function(e) {
                    if (e.target !== this || !$image.data('cropper') || this.scrollTop > 300) {
                        return;
                    }
                    switch (e.which) {
                        case 37:
                            e.preventDefault();
                            $image.cropper('move', -1, 0);
                            break;
                        case 38:
                            e.preventDefault();
                            $image.cropper('move', 0, -1);
                            break;
                        case 39:
                            e.preventDefault();
                            $image.cropper('move', 1, 0);
                            break;
                        case 40:
                            e.preventDefault();
                            $image.cropper('move', 0, 1);
                            break;
                    }
                });

                // 上传图片
                var $inputImage = $('#inputImage');

                if (URL) {
                    $inputImage.change(function() {
                        var files = this.files;
                        var file;

                        if (!$image.data('cropper')) {
                            return;
                        }

                        if (files && files.length) {
                            file = files[0];

                            if (/^image\/\w+$/.test(file.type)) {
                                uploadedImageName = file.name;
                                uploadedImageType = file.type;

                                if (uploadedImageURL) {
                                    URL.revokeObjectURL(uploadedImageURL);
                                }

                                uploadedImageURL = URL.createObjectURL(file);
                                $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
                                $inputImage.val('');
                            } else {
                                window.alert('Please choose an image file.');
                            }
                        }
                    });
                } else {
                    $inputImage.prop('disabled', true).parent().addClass('disabled');
                }



            })

        },
        api: {
            bindevent: function() {
                Form.api.bindevent($("form.layui-form"));
            },
            formatter: {
                thumb: function(row) {
                    var html = '';
                    if (row.mime.indexOf("image") > -1) {
                        html = '<img src="' + row.path + '" alt="" style="max-height:30px;max-width:40px" data-image="' + row.name + '">';
                    } else {
                        html = '<a href="' + row.path + '" target="_blank"><img src="' + Yzn.api.fixurl("ajax/icon") + "?suffix=" + row.ext + '" alt="" style="max-height:30px;max-width:40px"></a>';
                    }
                    return '<div>' + html + '</div>';
                }
            }
        }
    };
    return Controller;
});