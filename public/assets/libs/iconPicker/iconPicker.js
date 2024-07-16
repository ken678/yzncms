/**
 * Layui图标选择器
 * @author wujiawei0926@yeah.net
 * @version 1.1
 */
define(['jquery', 'layui'], function($, layui) {
    "use strict";

    var iconPicker = function() {
            this.v = '1.1';
        },
        _MOD = 'iconPicker',
        _this = this,
        laypage = layui.laypage,
        form = layui.form,
        BODY = 'body',
        TIPS = '请选择图标';

    /**
     * 渲染组件
     */
    iconPicker.render = function(options) {
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
                        '<i class="layui-icon layui-icon-search"></i>' +
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
                        '<i class="iconfont icon-arrow-left-s-line" id="' + PAGE_ID + '-prev" data-index="0" prev></i> ' +
                        '<i class="iconfont icon-arrow-right-s-line" id="' + PAGE_ID + '-next" data-index="2" next></i> ' +
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
                    var arr = ["icon-search-fill","icon-search-line","icon-question-fill","icon-question-line","icon-alert-fill","icon-alert-line","icon-battery-2-charge-fill","icon-battery-2-charge-line","icon-computer-fill","icon-computer-line","icon-cpu-fill","icon-cpu-line","icon-dashboard-3-fill","icon-dashboard-3-line","icon-instance-line","icon-instance-fill","icon-server-fill","icon-server-line","icon-bookmark-fill","icon-bookmark-line","icon-price-tag-3-fill","icon-price-tag-3-line","icon-bus-fill","icon-bus-line","icon-car-fill","icon-car-line","icon-e-bike-2-fill","icon-e-bike-2-line","icon-gas-station-fill","icon-gas-station-line","icon-train-fill","icon-train-line","icon-plane-fill","icon-plane-line","icon-hourglass-fill","icon-hourglass-line","icon-menu-add-fill","icon-menu-add-line","icon-menu-fill","icon-menu-line","icon-lightbulb-flash-line","icon-lightbulb-line","icon-plug-fill","icon-plug-line","icon-scales-3-line","icon-scales-3-fill","icon-collapse-diagonal-2-fill","icon-book-shelf-line","icon-collapse-diagonal-2-line","icon-collapse-horizontal-fill","icon-collapse-horizontal-line","icon-collapse-vertical-fill","icon-collapse-vertical-line","icon-expand-diagonal-2-line","icon-expand-diagonal-2-fill","icon-expand-horizontal-fill","icon-expand-horizontal-line","icon-expand-vertical-fill","icon-book-shelf-fill","icon-expand-vertical-line","icon-chat-1-fill","icon-chat-1-line","icon-chat-thread-fill","icon-chat-thread-line","icon-javascript-fill","icon-javascript-line","icon-links-fill","icon-links-line","icon-link-m","icon-link-unlink-m","icon-external-link-fill","icon-external-link-line","icon-loop-left-fill","icon-loop-left-line","icon-shut-down-fill","icon-shut-down-line","icon-pushpin-fill","icon-pushpin-line","icon-id-card-fill","icon-id-card-line","icon-contacts-book-fill","icon-contacts-book-line","icon-bank-card-fill","icon-bank-card-line","icon-lock-unlock-fill","icon-lock-unlock-line","icon-collapse-diagonal-fill","icon-collapse-diagonal-line","icon-expand-diagonal-line","icon-expand-diagonal-fill","icon-robot-2-fill","icon-robot-2-line","icon-bluetooth-fill","icon-bluetooth-line","icon-seedling-fill","icon-seedling-line","icon-sun-foggy-fill","icon-sun-foggy-line","icon-thumb-down-fill","icon-thumb-down-line","icon-thumb-up-fill","icon-thumb-up-line","icon-walk-fill","icon-walk-line","icon-wifi-line","icon-wifi-fill","icon-windows-fill","icon-windows-line","icon-edit-box-fill","icon-edit-box-line","icon-file-copy-fill","icon-file-copy-line","icon-folder-2-fill","icon-folder-2-line","icon-hotel-bed-fill","icon-hotel-bed-line","icon-nurse-fill","icon-nurse-line","icon-pantone-fill","icon-pantone-line","icon-restaurant-fill","icon-restaurant-line","icon-rss-line","icon-rss-fill","icon-bill-fill","icon-bill-line","icon-subtract-fill","icon-subtract-line","icon-team-fill","icon-team-line","icon-wallet-fill","icon-wallet-line","icon-list-check","icon-list-unordered","icon-list-ordered","icon-reply-fill","icon-reply-line","icon-send-plane-fill","icon-send-plane-line","icon-delete-bin-fill","icon-delete-bin-line","icon-list-settings-line","icon-list-settings-fill","icon-terminal-box-fill","icon-terminal-box-line","icon-more-2-line","icon-more-2-fill","icon-more-line","icon-more-fill","icon-emotion-happy-fill","icon-emotion-happy-line","icon-article-fill","icon-article-line","icon-profile-fill","icon-profile-line","icon-arrow-go-forward-fill","icon-arrow-go-forward-line","icon-image-fill","icon-image-line","icon-bug-line","icon-bug-fill","icon-attachment-fill","icon-attachment-line","icon-check-fill","icon-check-line","icon-checkbox-circle-fill","icon-checkbox-circle-line","icon-close-circle-fill","icon-close-circle-line","icon-indeterminate-circle-fill","icon-indeterminate-circle-line","icon-information-fill","icon-information-line","icon-key-2-line","icon-key-2-fill","icon-message-3-fill","icon-message-3-line","icon-play-fill","icon-play-line","icon-question-answer-fill","icon-question-answer-line","icon-save-fill","icon-save-line","icon-arrow-down-fill","icon-arrow-down-line","icon-arrow-left-fill","icon-arrow-left-line","icon-arrow-right-fill","icon-arrow-right-line","icon-arrow-up-fill","icon-arrow-up-line","icon-crop-fill","icon-crop-line","icon-heart-fill","icon-heart-line","icon-history-fill","icon-history-line","icon-download-cloud-2-line","icon-download-cloud-2-fill","icon-upload-cloud-2-fill","icon-upload-cloud-2-line","icon-video-fill","icon-video-line","icon-arrow-left-s-fill","icon-arrow-left-s-line","icon-arrow-right-s-fill","icon-arrow-right-s-line","icon-map-pin-fill","icon-map-pin-line","icon-settings-4-fill","icon-settings-4-line","icon-share-fill","icon-share-line","icon-arrow-go-back-line","icon-arrow-go-back-fill","icon-lock-fill","icon-lock-line","icon-vip-crown-2-fill","icon-vip-crown-2-line","icon-close-fill","icon-close-line","icon-men-fill","icon-men-line","icon-flag-2-fill","icon-flag-2-line","icon-time-line","icon-time-fill","icon-volume-up-fill","icon-volume-up-line","icon-women-fill","icon-women-line","icon-add-fill","icon-add-line","icon-at-line","icon-at-fill","icon-medal-fill","icon-medal-line","icon-vidicon-fill","icon-vidicon-line","icon-donut-chart-fill","icon-donut-chart-line","icon-line-chart-fill","icon-line-chart-line","icon-pie-chart-2-fill","icon-pie-chart-2-line","icon-printer-fill","icon-printer-line","icon-stack-fill","icon-stack-line","icon-window-fill","icon-window-line","icon-barcode-line","icon-barcode-fill","icon-code-s-slash-fill","icon-code-s-slash-line","icon-coupon-line","icon-coupon-fill","icon-download-2-line","icon-download-2-fill","icon-drag-move-2-fill","icon-drag-move-2-line","icon-eye-close-fill","icon-eye-fill","icon-eye-close-line","icon-eye-line","icon-eye-off-fill","icon-eye-off-line","icon-fingerprint-line","icon-fingerprint-fill","icon-map-fill","icon-map-2-line","icon-mini-program-line","icon-mini-program-fill","icon-movie-2-fill","icon-movie-2-line","icon-music-2-fill","icon-music-2-line","icon-notification-3-line","icon-notification-3-fill","icon-notification-4-fill","icon-notification-4-line","icon-qr-code-fill","icon-qr-code-line","icon-qr-scan-2-fill","icon-qr-scan-2-line","icon-red-packet-fill","icon-red-packet-line","icon-riding-fill","icon-riding-line","icon-rocket-2-fill","icon-rocket-2-line","icon-shape-2-fill","icon-shape-2-line","icon-shape-fill","icon-shape-line","icon-shield-keyhole-line","icon-shield-keyhole-fill","icon-shopping-bag-fill","icon-shopping-bag-line","icon-sound-module-fill","icon-sound-module-line","icon-stock-fill","icon-stock-line","icon-t-shirt-fill","icon-t-shirt-line","icon-table-fill","icon-table-line","icon-takeaway-fill","icon-takeaway-line","icon-taxi-fill","icon-taxi-line","icon-truck-fill","icon-truck-line","icon-zoom-in-line","icon-zoom-out-fill","icon-zoom-out-line","icon-zoom-in-fill","icon-hospital-fill","icon-hospital-line","icon-store-line","icon-store-fill","icon-advertisement-line","icon-advertisement-fill","icon-archive-line","icon-archive-fill","icon-bar-chart-fill","icon-bar-chart-grouped-line","icon-bar-chart-2-line","icon-bar-chart-grouped-fill","icon-bar-chart-horizontal-fill","icon-bar-chart-horizontal-line","icon-bubble-chart-fill","icon-bubble-chart-line","icon-qq-fill","icon-qq-line","icon-alipay-fill","icon-alipay-line","icon-translate-2","icon-translate","icon-baidu-fill","icon-baidu-line","icon-dingding-fill","icon-dingding-line","icon-github-fill","icon-github-line","icon-taobao-fill","icon-taobao-line","icon-wechat-2-fill","icon-wechat-2-line","icon-wechat-pay-fill","icon-wechat-fill","icon-wechat-line","icon-wechat-pay-line","icon-sina-fill","icon-sina-line","icon-calendar-2-fill","icon-calendar-2-line","icon-calendar-check-fill","icon-calendar-check-line","icon-recycle-fill","icon-recycle-line","icon-money","icon-upload2-fill","icon-upload2-line","icon-upload-fill","icon-upload-line","icon-tools-fill","icon-tools-line","icon-database-2-line","icon-database-2-fill","icon-file-fill","icon-file-line","icon-file-list-3-fill","icon-file-list-3-line","icon-file-paper-2-fill","icon-file-paper-2-line","icon-file-shield-2-fill","icon-file-shield-2-line","icon-gift-fill","icon-money-cny-box-line","icon-gift-line","icon-money-cny-box-fill","icon-history","icon-history-filling","icon-file-settings-fill","icon-file-settings-line","icon-user-fill","icon-user-line","icon-shield-user-fill","icon-shield-user-line","icon-user-unfollow-fill","icon-user-unfollow-line","icon-user-add-fill","icon-user-add-line","icon-user-heart-line","icon-user-heart-fill","icon-user-shared-2-fill","icon-user-shared-2-line","icon-user-star-fill","icon-user-star-line","icon-mail-add-line","icon-mail-check-fill","icon-mail-check-line","icon-mail-add-fill","icon-mail-close-line","icon-mail-download-line","icon-mail-close-fill","icon-mail-fill","icon-mail-forbid-fill","icon-mail-line","icon-mail-forbid-line","icon-mail-lock-fill","icon-mail-download-fill","icon-mail-lock-line","icon-mail-open-fill","icon-mail-open-line","icon-mail-send-line","icon-mail-send-fill","icon-mail-settings-line","icon-mail-star-line","icon-mail-star-fill","icon-mail-unread-line","icon-mail-unread-fill","icon-mail-volume-line","icon-mail-settings-fill","icon-mail-volume-fill","icon-edit-2-fill","icon-edit-2-line","icon-file-code-fill","icon-file-code-line","icon-reactjs-line","icon-reactjs-fill","icon-logout-box-line","icon-logout-box-r-line","icon-group-fill","icon-group-line","icon-user-settings-line","icon-user-settings-fill","icon-palette-fill","icon-palette-line","icon-git-branch-fill","icon-git-branch-line","icon-book-2-fill","icon-book-2-line","icon-book-open-fill","icon-book-open-line","icon-vip-fill","icon-vip-line","icon-equalizer-fill","icon-equalizer-line","icon-draft-fill","icon-draft-line","icon-apps-fill","icon-apps-line","icon-alarm-fill","icon-alarm-line","icon-circle-fill","icon-circle-line","icon-vip1","icon-lianjie"];
                    return arr;
                }
            }
        };

        a.init();
        return new iconPicker();
    };

    /**
     * 选中图标
     * @param filter lay-filter
     * @param iconName 图标名称，自动识别fontClass/unicode
     */
    iconPicker.prototype.checkIcon = function(filter, iconName) {
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
    return iconPicker;
});