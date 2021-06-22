/**
 * Layui图标选择器
 * @author wujiawei0926@yeah.net
 * @version 1.1
 */

layui.define(['laypage', 'form'], function(exports) {
    "use strict";

    var IconPicker = function() {
            this.v = '1.1';
        },
        _MOD = 'iconPicker',
        _this = this,
        $ = layui.jquery,
        laypage = layui.laypage,
        form = layui.form,
        BODY = 'body',
        TIPS = '请选择图标';

    /**
     * 渲染组件
     */
    IconPicker.prototype.render = function(options) {
        var opts = options,
            // DOM选择器
            elem = opts.elem,
            // 数据类型：fontClass/unicode
            type = opts.type == null ? 'fontClass' : opts.type,
            // 是否分页：true/false
            page = opts.page == null ? true : opts.page,
            // 每页显示数量
            limit = opts.limit == null ? 12 : opts.limit,
            // 是否开启搜索：true/false
            search = opts.search == null ? true : opts.search,
            // 每个图标格子的宽度：'43px'或'20%'
            cellWidth = opts.cellWidth,
            // 点击回调
            click = opts.click,
            // 渲染成功后的回调
            success = opts.success,
            // json数据
            data = {},
            // 唯一标识
            tmp = new Date().getTime(),
            // 是否使用的class数据
            isFontClass = opts.type === 'fontClass',
            // 初始化时input的值
            ORIGINAL_ELEM_VALUE = $(elem).val(),
            TITLE = 'layui-select-title',
            TITLE_ID = 'layui-select-title-' + tmp,
            ICON_BODY = 'layui-iconpicker-' + tmp,
            PICKER_BODY = 'layui-iconpicker-body-' + tmp,
            PAGE_ID = 'layui-iconpicker-page-' + tmp,
            LIST_BOX = 'layui-iconpicker-list-box',
            selected = 'layui-form-selected',
            unselect = 'layui-unselect';

        var a = {
            init: function() {
                data = common.getData[type]();

                a.hideElem().createSelect().createBody().toggleSelect();
                a.preventEvent().inputListen();
                common.loadCss();

                if (success) {
                    success(this.successHandle());
                }

                return a;
            },
            successHandle: function() {
                var d = {
                    options: opts,
                    data: data,
                    id: tmp,
                    elem: $('#' + ICON_BODY)
                };
                return d;
            },
            /**
             * 隐藏elem
             */
            hideElem: function() {
                $(elem).hide();
                return a;
            },
            /**
             * 绘制select下拉选择框
             */
            createSelect: function() {
                var oriIcon = '<i class="iconfont">';

                // 默认图标
                if (ORIGINAL_ELEM_VALUE === '') {
                    if (isFontClass) {
                        ORIGINAL_ELEM_VALUE = '';//默认值
                    } else {
                        ORIGINAL_ELEM_VALUE = '&#xe617;';
                    }
                }

                if (isFontClass) {
                    oriIcon = '<i class="iconfont ' + ORIGINAL_ELEM_VALUE + '">';
                } else {
                    oriIcon += ORIGINAL_ELEM_VALUE;
                }
                oriIcon += '</i>';

                var selectHtml = '<div class="layui-iconpicker layui-unselect layui-form-select" id="' + ICON_BODY + '">' +
                    '<div class="' + TITLE + '" id="' + TITLE_ID + '">' +
                    '<div class="layui-iconpicker-item">' +
                    '<span class="layui-iconpicker-icon layui-unselect">' +
                    oriIcon +
                    '</span>' +
                    '<i class="layui-edge"></i>' +
                    '</div>' +
                    '</div>' +
                    '<div class="layui-anim layui-anim-upbit" style="">' +
                    '123' +
                    '</div>';
                $(elem).after(selectHtml);
                return a;
            },
            /**
             * 展开/折叠下拉框
             */
            toggleSelect: function() {
                var item = '#' + TITLE_ID + ' .layui-iconpicker-item,#' + TITLE_ID + ' .layui-iconpicker-item .layui-edge';
                a.event('click', item, function(e) {
                    var $icon = $('#' + ICON_BODY);
                    if ($icon.hasClass(selected)) {
                        $icon.removeClass(selected).addClass(unselect);
                    } else {
                        // 隐藏其他picker
                        $('.layui-form-select').removeClass(selected);
                        // 显示当前picker
                        $icon.addClass(selected).removeClass(unselect);
                    }
                    e.stopPropagation();
                });
                return a;
            },
            /**
             * 绘制主体部分
             */
            createBody: function() {
                // 获取数据
                var searchHtml = '';

                if (search) {
                    searchHtml = '<div class="layui-iconpicker-search">' +
                        '<input class="layui-input">' +
                        '<i class="iconfont icon-search"></i>' +
                        '</div>';
                }

                // 组合dom
                var bodyHtml = '<div class="layui-iconpicker-body" id="' + PICKER_BODY + '">' +
                    searchHtml +
                    '<div class="' + LIST_BOX + '"></div> ' +
                    '</div>';
                $('#' + ICON_BODY).find('.layui-anim').eq(0).html(bodyHtml);
                a.search().createList().check().page();

                return a;
            },
            /**
             * 绘制图标列表
             * @param text 模糊查询关键字
             * @returns {string}
             */
            createList: function(text) {
                var d = data,
                    l = d.length,
                    pageHtml = '',
                    listHtml = $('<div class="layui-iconpicker-list">') //'<div class="layui-iconpicker-list">';

                // 计算分页数据
                var _limit = limit, // 每页显示数量
                    _pages = l % _limit === 0 ? l / _limit : parseInt(l / _limit + 1), // 总计多少页
                    _id = PAGE_ID;

                // 图标列表
                var icons = [];

                for (var i = 0; i < l; i++) {
                    var obj = d[i];

                    // 判断是否模糊查询
                    if (text && obj.indexOf(text) === -1) {
                        continue;
                    }

                    // 是否自定义格子宽度
                    var style = '';
                    if (cellWidth !== null) {
                        style += ' style="width:' + cellWidth + '"';
                    }

                    // 每个图标dom
                    var icon = '<div class="layui-iconpicker-icon-item" title="' + obj + '" ' + style + '>';
                    if (isFontClass) {
                        icon += '<i class="iconfont ' + obj + '"></i>';
                    } else {
                        icon += '<i class="iconfont">' + obj.replace('amp;', '') + '</i>';
                    }
                    icon += '</div>';

                    icons.push(icon);
                }

                // 查询出图标后再分页
                l = icons.length;
                _pages = l % _limit === 0 ? l / _limit : parseInt(l / _limit + 1);
                for (var i = 0; i < _pages; i++) {
                    // 按limit分块
                    var lm = $('<div class="layui-iconpicker-icon-limit" id="layui-iconpicker-icon-limit-' + tmp + (i + 1) + '">');

                    for (var j = i * _limit; j < (i + 1) * _limit && j < l; j++) {
                        lm.append(icons[j]);
                    }

                    listHtml.append(lm);
                }

                // 无数据
                if (l === 0) {
                    listHtml.append('<p class="layui-iconpicker-tips">无数据</p>');
                }

                // 判断是否分页
                if (page) {
                    $('#' + PICKER_BODY).addClass('layui-iconpicker-body-page');
                    pageHtml = '<div class="layui-iconpicker-page" id="' + PAGE_ID + '">' +
                        '<div class="layui-iconpicker-page-count">' +
                        '<span id="' + PAGE_ID + '-current">1</span>/' +
                        '<span id="' + PAGE_ID + '-pages">' + _pages + '</span>' +
                        ' (<span id="' + PAGE_ID + '-length">' + l + '</span>)' +
                        '</div>' +
                        '<div class="layui-iconpicker-page-operate">' +
                        '<i class="iconfont icon-icon-test" id="' + PAGE_ID + '-prev" data-index="0" prev></i> ' +
                        '<i class="iconfont icon-icon-test1" id="' + PAGE_ID + '-next" data-index="2" next></i> ' +
                        '</div>' +
                        '</div>';
                }


                $('#' + ICON_BODY).find('.layui-anim').find('.' + LIST_BOX).html('').append(listHtml).append(pageHtml);
                return a;
            },
            // 阻止Layui的一些默认事件
            preventEvent: function() {
                var item = '#' + ICON_BODY + ' .layui-anim';
                a.event('click', item, function(e) {
                    e.stopPropagation();
                });
                return a;
            },
            // 分页
            page: function() {
                var icon = '#' + PAGE_ID + ' .layui-iconpicker-page-operate .iconfont';

                $(icon).unbind('click');
                a.event('click', icon, function(e) {
                    var elem = e.currentTarget,
                        total = parseInt($('#' + PAGE_ID + '-pages').html()),
                        isPrev = $(elem).attr('prev') !== undefined,
                        // 按钮上标的页码
                        index = parseInt($(elem).attr('data-index')),
                        $cur = $('#' + PAGE_ID + '-current'),
                        // 点击时正在显示的页码
                        current = parseInt($cur.html());

                    // 分页数据
                    if (isPrev && current > 1) {
                        current = current - 1;
                        $(icon + '[prev]').attr('data-index', current);
                    } else if (!isPrev && current < total) {
                        current = current + 1;
                        $(icon + '[next]').attr('data-index', current);
                    }
                    $cur.html(current);

                    // 图标数据
                    $('#' + ICON_BODY + ' .layui-iconpicker-icon-limit').hide();
                    $('#layui-iconpicker-icon-limit-' + tmp + current).show();
                    e.stopPropagation();
                });
                return a;
            },
            /**
             * 搜索
             */
            search: function() {
                var item = '#' + PICKER_BODY + ' .layui-iconpicker-search .layui-input';
                a.event('input propertychange', item, function(e) {
                    var elem = e.target,
                        t = $(elem).val();
                    a.createList(t);
                });
                return a;
            },
            /**
             * 点击选中图标
             */
            check: function() {
                var item = '#' + PICKER_BODY + ' .layui-iconpicker-icon-item';
                a.event('click', item, function(e) {
                    var el = $(e.currentTarget).find('.iconfont'),
                        icon = '';
                    if (isFontClass) {
                        var clsArr = el.attr('class').split(/[\s\n]/),
                            cls = clsArr[1],
                            icon = cls;
                        $('#' + TITLE_ID).find('.layui-iconpicker-item .iconfont').html('').attr('class', clsArr.join(' '));
                    } else {
                        var cls = el.html(),
                            icon = cls;
                        $('#' + TITLE_ID).find('.layui-iconpicker-item .iconfont').html(icon);
                    }

                    $('#' + ICON_BODY).removeClass(selected).addClass(unselect);
                    $(elem).val(icon).attr('value', icon);
                    // 回调
                    if (click) {
                        click({
                            icon: icon
                        });
                    }

                });
                return a;
            },
            // 监听原始input数值改变
            inputListen: function() {
                var el = $(elem);
                a.event('change', elem, function() {
                    var value = el.val();
                })
                // el.change(function(){

                // });
                return a;
            },
            event: function(evt, el, fn) {
                $(BODY).on(evt, el, fn);
            }
        };

        var common = {
            /**
             * 加载样式表
             */
            loadCss: function() {
                var css = '.layui-iconpicker {max-width: 280px;}.layui-iconpicker .layui-anim{display:none;position:absolute;left:0;top:42px;padding:5px 0;z-index:899;min-width:100%;border:1px solid #d2d2d2;max-height:300px;overflow-y:auto;background-color:#fff;border-radius:2px;box-shadow:0 2px 4px rgba(0,0,0,.12);box-sizing:border-box;}.layui-iconpicker-item{border:1px solid #e6e6e6;width:90px;height:38px;border-radius:4px;cursor:pointer;position:relative;}.layui-iconpicker-icon{border-right:1px solid #e6e6e6;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;display:block;width:60px;height:100%;float:left;text-align:center;background:#fff;transition:all .3s;}.layui-iconpicker-icon i{line-height:38px;font-size:18px;}.layui-iconpicker-item > .layui-edge{left:70px;}.layui-iconpicker-item:hover{border-color:#D2D2D2!important;}.layui-iconpicker-item:hover .layui-iconpicker-icon{border-color:#D2D2D2!important;}.layui-iconpicker.layui-form-selected .layui-anim{display:block;}.layui-iconpicker-body{padding:6px;}.layui-iconpicker .layui-iconpicker-list{background-color:#fff;border:1px solid #ccc;border-radius:4px;}.layui-iconpicker .layui-iconpicker-icon-item{display:inline-block;width:21.1%;line-height:36px;text-align:center;cursor:pointer;vertical-align:top;height:36px;margin:4px;border:1px solid #ddd;border-radius:2px;transition:300ms;}.layui-iconpicker .layui-iconpicker-icon-item i.iconfont{font-size:17px;}.layui-iconpicker .layui-iconpicker-icon-item:hover{background-color:#eee;border-color:#ccc;-webkit-box-shadow:0 0 2px #aaa,0 0 2px #fff inset;-moz-box-shadow:0 0 2px #aaa,0 0 2px #fff inset;box-shadow:0 0 2px #aaa,0 0 2px #fff inset;text-shadow:0 0 1px #fff;}.layui-iconpicker-search{position:relative;margin:0 0 6px 0;border:1px solid #e6e6e6;border-radius:2px;transition:300ms;}.layui-iconpicker-search:hover{border-color:#D2D2D2!important;}.layui-iconpicker-search .layui-input{cursor:text;display:inline-block;width:86%;border:none;padding-right:0;margin-top:1px;}.layui-iconpicker-search .iconfont{position:absolute;top:11px;right:4%;}.layui-iconpicker-tips{text-align:center;padding:8px 0;cursor:not-allowed;}.layui-iconpicker-page{margin-top:6px;margin-bottom:-6px;font-size:12px;padding:0 2px;}.layui-iconpicker-page-count{display:inline-block;}.layui-iconpicker-page-operate{display:inline-block;float:right;cursor:default;}.layui-iconpicker-page-operate .iconfont{font-size:12px;cursor:pointer;}.layui-iconpicker-body-page .layui-iconpicker-icon-limit{display:none;}.layui-iconpicker-body-page .layui-iconpicker-icon-limit:first-child{display:block;}';
                var $style = $('head').find('style[iconpicker]');
                if ($style.length === 0) {
                    $('head').append('<style rel="stylesheet" iconpicker>' + css + '</style>');
                }
            },
            /**
             * 获取数据
             */
            getData: {
                fontClass: function() {
                    var arr = ["icon-money","icon-upload2-fill","icon-upload2-line","icon-upload-fill","icon-upload-line","icon-tools-fill","icon-tools-line","icon-database-2-line","icon-database-2-fill","icon-file-fill","icon-file-line","icon-file-list-3-fill","icon-file-list-3-line","icon-file-paper-2-fill","icon-file-paper-2-line","icon-file-shield-2-fill","icon-file-shield-2-line","icon-gift-fill","icon-money-cny-box-line","icon-gift-line","icon-money-cny-box-fill","icon-history","icon-history-filling","icon-file-settings-fill","icon-file-settings-line","icon-user-fill","icon-user-line","icon-shield-user-fill","icon-shield-user-line","icon-user-unfollow-fill","icon-user-unfollow-line","icon-shuaxin","icon-user-add-fill","icon-user-add-line","icon-user-heart-line","icon-user-heart-fill","icon-user-shared-2-fill","icon-user-shared-2-line","icon-user-star-fill","icon-user-star-line","icon-mail-add-line","icon-mail-check-fill","icon-mail-check-line","icon-mail-add-fill","icon-mail-close-line","icon-mail-download-line","icon-mail-close-fill","icon-mail-fill","icon-mail-forbid-fill","icon-mail-line","icon-mail-forbid-line","icon-mail-lock-fill","icon-mail-download-fill","icon-mail-lock-line","icon-mail-open-fill","icon-mail-open-line","icon-mail-send-line","icon-mail-send-fill","icon-mail-settings-line","icon-mail-star-line","icon-mail-star-fill","icon-mail-unread-line","icon-mail-unread-fill","icon-mail-volume-line","icon-mail-settings-fill","icon-mail-volume-fill","icon-edit-2-fill","icon-edit-2-line","icon-file-code-fill","icon-file-code-line","icon-reactjs-line","icon-reactjs-fill","icon-logout-box-line","icon-logout-box-r-line","icon-group-fill","icon-group-line","icon-user-settings-line","icon-user-settings-fill","icon-palette-fill","icon-palette-line","icon-git-branch-fill","icon-git-branch-line","icon-book-2-fill","icon-book-2-line","icon-book-open-fill","icon-book-open-line","icon-vip-fill","icon-vip-line","icon-equalizer-fill","icon-equalizer-line","icon-shujutu","icon-draft-fill","icon-draft-line","icon-apps-fill","icon-apps-line","icon-alarm-fill","icon-alarm-line","icon-circle-fill","icon-circle-line","icon-oschina","icon-cry","icon-meh","icon-smile","icon-smile-filling","icon-meh-filling","icon-cry-filling","icon-add","icon-close","icon-min","icon-fa-arrows-alt-v","icon-arrows-alt-h","icon-redo-alt","icon-undo-alt","icon-falling","icon-leftarrow","icon-rising","icon-Rightarrow","icon-vip1","icon-shuaxin1","icon-renwu","icon-icon-test","icon-icon-test1","icon-taobao","icon-zhifubao","icon-baidu","icon-sina","icon-weixin","icon-qq","icon-tongji","icon-shenhe","icon-tubiaoqiehuan","icon-tiyanjiankong","icon-yonghu","icon-male","icon-female","icon-global","icon-liebiaosousuo","icon-dibu","icon-dingbu","icon-right","icon-caigou-xianxing","icon-caigou","icon-shiyongwendang","icon-peoplefill","icon-people","icon-hotfill","icon-hot","icon-cloud-upload","icon-cloud","icon-cloud-download","icon-cloud-sync","icon-apartment","icon-bug-fill","icon-bug","icon-rankfill","icon-rank","icon-other","icon-send","icon-tailor","icon-warning_fill","icon-warning","icon-workbench_fill","icon-workbench","icon-search","icon-searchfill","icon-accessory","icon-addition_fill","icon-addition","icon-browse_fill","icon-browse","icon-brush","icon-brush_fill","icon-collection_fill","icon-collection","icon-coordinates_fill","icon-coordinates","icon-createtask_fill","icon-createtask","icon-delete_fill","icon-delete","icon-emoji_fill","icon-emoji","icon-enterinto","icon-enterinto_fill","icon-feedback_fill","icon-feedback","icon-flag_fill","icon-flag","icon-flashlight","icon-flashlight_fill","icon-fullscreen","icon-homepage_fill","icon-homepage","icon-interactive_fill","icon-interactive","icon-label","icon-label_fill","icon-like_fill","icon-like","icon-lock_fill","icon-lock","icon-manage_fill","icon-manage","icon-more","icon-narrow","icon-offline_fill","icon-offline","icon-picture_fill","icon-picture","icon-playon_fill","icon-playon","icon-praise_fill","icon-praise","icon-prompt_fill","icon-prompt","icon-qrcode_fill","icon-qrcode","icon-select_fill","icon-select","icon-setup_fill","icon-setup","icon-share_fill","icon-share","icon-stealth_fill","icon-stealth","icon-success_fill","icon-success","icon-switch","icon-systemprompt_fill","icon-systemprompt","icon-time_fill","icon-time","icon-trash","icon-trash_fill","icon-undo","icon-unlock_fill","icon-unlock","icon-packup","icon-unfold","icon-yidong","icon-beifenruanjian","icon-zidongxiufu","icon-lianjie"];
                    return arr;
                }
            }
        };

        a.init();
        return new IconPicker();
    };

    /**
     * 选中图标
     * @param filter lay-filter
     * @param iconName 图标名称，自动识别fontClass/unicode
     */
    IconPicker.prototype.checkIcon = function(filter, iconName) {
        var el = $('*[lay-filter=' + filter + ']'),
            p = el.next().find('.layui-iconpicker-item .iconfont'),
            c = iconName;

        if (c.indexOf('#xe') > 0) {
            p.html(c);
        } else {
            p.html('').attr('class', 'iconfont ' + c);
        }
        el.attr('value', c).val(c);
    };

    var iconPicker = new IconPicker();
    exports(_MOD, iconPicker);
});