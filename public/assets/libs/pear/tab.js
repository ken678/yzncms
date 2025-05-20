define(['jquery', 'layui'], function($, layui) {
	"use strict";

	var tabs = layui.tabs;

	var pearTab = function(opt) {
		this.option = opt;
	};

	var tabData = new Array();
	var tabDataCurrent = 0;
	var contextTabDOM;

	pearTab.render = function(opt) {
		var option = {
			container: opt.container,
			elem: opt.elem,
			data: opt.data,
			tool: opt.tool,
			roll: opt.roll,
			index: opt.index,
			width: opt.width,
			height: opt.height,
			tabMax: opt.tabMax,
			session: opt.session ? opt.session : false,
			preload: opt.preload ? opt.preload : false,
			closeEvent: opt.closeEvent,
			success: opt.success ? opt.success : function(id) {}
		}

		if (option.session) {
			if (sessionStorage.getItem(option.elem + "-pear-tab-data") != null) {
				tabData = JSON.parse(sessionStorage.getItem(option.elem + "-pear-tab-data"));
				option.data = JSON.parse(sessionStorage.getItem(option.elem + "-pear-tab-data"));
				tabDataCurrent = sessionStorage.getItem(option.elem + "-pear-tab-data-current");
				tabData.forEach(function(item, index) {
					if (item.id == tabDataCurrent) {
						option.index = index;
					}
				})
			} else {
				tabData = opt.data;
			}
		}

		var lastIndex;
		var tab = createTab(option);
		$("#" + opt.container).html(tab);

		tabs.render({
			elem: '#'+ option.elem,
			closable: true
		})
		toolEvent(option);
		$("#" + opt.container).width(opt.width);
		$("#" + opt.container).height(opt.height);
		$("#" + opt.container).css({
			position: "relative"
		});
		closeEvent(option);

		option.success(sessionStorage.getItem(option.elem + "-pear-tab-data-current"));

		return new pearTab(option);
	}

	pearTab.prototype.click = function(callback) {
		var elem = this.option.elem;
		var option = this.option;
		tabs.on('afterChange(' + this.option.elem + ')', function(data) {
			var id = $("#" + elem + " .layui-tabs-header .layui-this").attr("lay-id");
			sessionStorage.setItem(option.elem + "-pear-tab-data-current", id);
			if (!option.preload) {
				var $iframe = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-body").find(
					"iframe[id='" + id + "']");
				var iframeUrl = $iframe.attr("src");
				if (!iframeUrl || iframeUrl === "about:blank") {
					// 获取 url 并重载
					tabData.forEach(function(item, index) {
						if (item.id === id) {
							iframeUrl = item.url;
						}
					})
					tabIframeLoading(elem);
					$iframe.attr("src", iframeUrl);
				}
			}
			callback(id);
		});
	}

	pearTab.prototype.clear = function() {
		sessionStorage.removeItem(this.option.elem + "-pear-tab-data");
		sessionStorage.removeItem(this.option.elem + "-pear-tab-data-current");
	}

	pearTab.prototype.addTab = function(opt) {
		var title = '';
		if (opt.close) {
			title += '<span class="pear-tab-active"></span><span class="able-close title">' + opt.title +
				'</span><i class="layui-icon layui-unselect layui-tabs-close">ဆ</i>';
		} else {
			title += '<span class="pear-tab-active"></span><span class="disable-close title">' + opt.title +
				'</span><i class="layui-icon layui-unselect layui-tabs-close">ဆ</i>';
		}
		tabs.add(this.option.elem, {
			title: title,
			content: '<iframe id="' + opt.id + '" data-frameid="' + opt.id +
				'" scrolling="auto" frameborder="0" src="' +
				opt.url + '" style="width:100%;height:100%;" allowfullscreen="true"></>',
			id: opt.id
		});
		tabData.push(opt);
		sessionStorage.setItem(this.option.elem + "-pear-tab-data", JSON.stringify(tabData));
		sessionStorage.setItem(this.option.elem + "-pear-tab-data-current", opt.id);
		tabs.change(this.option.elem, opt.id);
	}

	var index = 0;
	// 根据过滤 fliter 标识, 重置选项卡标题
	pearTab.prototype.changeTabTitleById = function(elem, id, title) {
		var currentTab = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-header [lay-id='" + id +
			"'] .title");
		currentTab.html(title);
	}

	// 根据过滤 filter 标识, 删除指定选项卡
	pearTab.prototype.delTabByElem = function(elem, id, callback) {
		var currentTab = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-header [lay-id='" + id + "']");
		if (currentTab.find("span").is(".able-close")) {
			tabDelete(elem, id, callback);
		}
	}
	// 根据过滤 filter 标识, 删除其他选项卡
	pearTab.prototype.delOtherTabByElem = function(elem, callback) {
		var currentId = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-header .layui-this").attr(
			"lay-id");
		var tabtitle = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-header li");
		$.each(tabtitle, function(i) {
			if ($(this).attr("lay-id") != currentId) {
				if ($(this).find("span").is(".able-close")) {
					tabDelete(elem, $(this).attr("lay-id"), callback);
				}
			}
		})
	}

	// 根据过滤 filter 标识, 删除全部选项卡
	pearTab.prototype.delAllTabByElem = function(elem, callback) {
		var currentId = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-header .layui-this").attr(
			"lay-id");
		var tabtitle = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-header li");
		$.each(tabtitle, function(i) {
			if ($(this).find("span").is(".able-close")) {
				tabDelete(elem, $(this).attr("lay-id"), callback);
			}
		})
	}
	// 根据过滤 filter 标识, 删除当前选项卡
	pearTab.prototype.delCurrentTabByElem = function(elem, callback) {
		var currentTab = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-header .layui-this");
		if (currentTab.find("span").is(".able-close")) {
			var currentId = currentTab.attr("lay-id");
			tabDelete(elem, currentId, callback);
		}
	}

	/** 添 加 唯 一 选 项 卡 */
	pearTab.prototype.addTabOnly = function(opt, time) {
		var title = '';
		if (opt.close) {
			title += '<span class="pear-tab-active"></span><span class="able-close title">' + opt.title +
				'</span><i class="layui-icon layui-unselect layui-tabs-close">ဆ</i>';
		} else {
			title += '<span class="pear-tab-active"></span><span class="disable-close title">' + opt.title +
				'</span><i class="layui-icon layui-unselect layui-tabs-close">ဆ</i>';
		}
		if ($(".layui-tabs[lay-filter='" + this.option.elem + "'] .layui-tabs-header li[lay-id]").length <=
			0) {
			tabs.add(this.option.elem, {
				title: title,
				content: '<iframe id="' + opt.id + '" data-frameid="' + opt.id +
					'" scrolling="auto" frameborder="0" src="' +
					opt.url + '" style="width:100%;height:100%;" allowfullscreen="true"></iframe>',
				id: opt.id
			});
			if (time != false && time != 0) {
				tabIframeLoading(this.option.elem, opt.id);
			}
			tabData.push(opt);
			sessionStorage.setItem(this.option.elem + "-pear-tab-data", JSON.stringify(tabData));
			sessionStorage.setItem(this.option.elem + "-pear-tab-data-current", opt.id);
		} else {
			var isData = false;
			$.each($(".layui-tabs[lay-filter='" + this.option.elem + "'] .layui-tabs-header li[lay-id]"),
				function() {
					if ($(this).attr("lay-id") == opt.id) {
						isData = true;
					}
				})
			if (isData == false) {

				if (this.option.tabMax != false) {
					if ($(".layui-tabs[lay-filter='" + this.option.elem + "'] .layui-tabs-header li[lay-id]")
						.length >= this.option.tabMax) {
						layer.msg("最多打开" + this.option.tabMax + "个标签页", {
							icon: 2,
							time: 1000,
							shift: 6
						});
						return false;
					}
				}

				tabs.add(this.option.elem, {
					title: title,
					content: '<iframe id="' + opt.id + '" data-frameid="' + opt.id +
						'" scrolling="auto" frameborder="0" src="' +
						opt.url + '" style="width:100%;height:100%;" allowfullscreen="true"></iframe>',
					id: opt.id
				});
				if (time != false && time != 0) {
					tabIframeLoading(this.option.elem, opt.id);
				}
				tabData.push(opt);
				sessionStorage.setItem(this.option.elem + "-pear-tab-data", JSON.stringify(tabData));
				sessionStorage.setItem(this.option.elem + "-pear-tab-data-current", opt.id);
			}
		}
		tabs.change(this.option.elem, opt.id);
		sessionStorage.setItem(this.option.elem + "-pear-tab-data-current", opt.id);
	}

	// 刷 新 指 定 的 选 项 卡
	pearTab.prototype.refresh = function(time) {
		// 刷 新 指 定 的 选 项 卡
		var $iframe = $(".layui-tabs[lay-filter='" + this.option.elem + "'] .layui-tabs-body .layui-show")
			.find("iframe");
		if (time != false && time != 0) {
			tabIframeLoading(this.option.elem);
		}
		$iframe.attr("src", $iframe.attr("src"));
	}

	function tabIframeLoading(elem, id) {
		var load = '<div id="pear-tab-loading' + index + '" class="pear-tab-loading">' +
			'<div class="ball-loader">' +
			'<span></span><span></span><span></span><span></span>' +
			'</div>' +
			'</div>'
		var $iframe = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-body .layui-show").find("iframe");
		if (id) {
			$iframe = $(".layui-tabs[lay-filter='" + elem + "'] .layui-tabs-body").find("iframe[id='" + id +
				"']");
		}
		$iframe.parent().append(load);
		var pearLoad = $("#" + elem).find("#pear-tab-loading" + index);
		pearLoad.css({
			display: "block"
		});
		index++;
		$iframe.on("load", function() {
			pearLoad.fadeOut(1000, function() {
				pearLoad.remove();
			});
		})
	}

	function tabDelete(elem, id, callback, option) {
		//根据 elem id 来删除指定的 layui title li
		var tabTitle = $(".layui-tabs[lay-filter='" + elem + "']").find(".layui-tabs-header");

		// 删除指定 id 的 title
		var removeTab = tabTitle.find("li[lay-id='" + id + "']");
		var nextNode = removeTab.next("li");
		if (!removeTab.hasClass("layui-this")) {
			tabs.close(elem, id);

			tabData = JSON.parse(sessionStorage.getItem(elem + "-pear-tab-data"));
			tabDataCurrent = sessionStorage.getItem(elem + "-pear-tab-data-current");
			tabData = tabData.filter(function(item) {
				return item.id != id;
			})
			sessionStorage.setItem(elem + "-pear-tab-data", JSON.stringify(tabData));
			return false;
		}

		var currId;
		if (nextNode.length) {
			currId = nextNode.attr("lay-id");
		} else {
			var prevNode = removeTab.prev("li");
			currId = prevNode.attr("lay-id");
		}
		callback(currId);
		tabData = JSON.parse(sessionStorage.getItem(elem + "-pear-tab-data"));
		tabDataCurrent = sessionStorage.getItem(elem + "-pear-tab-data-current");
		tabData = tabData.filter(function(item) {
			return item.id != id;
		})
		sessionStorage.setItem(elem + "-pear-tab-data", JSON.stringify(tabData));
		sessionStorage.setItem(elem + "-pear-tab-data-current", currId);

		tabs.close(elem, id);
	}

	function createTab(option) {

		var type = "";
		if (option.roll == true) {
			type = "layui-tab-roll";
		}
		if (option.tool != false) {
			type = "layui-tab-tool";
		}
		if (option.roll == true && option.tool != false) {
			type = "layui-tab-rollTool";
		}
		var tab = '<div class="pear-tab ' + type + ' layui-tabs" lay-filter="' + option.elem +
		    '" id="' + option.elem +
			'" >';
		var title = '<ul class="layui-tabs-header">';
		var content = '<div class="layui-tabs-body">';
		var control =
			'<div class="layui-tab-control"><li class="layui-tab-tool layui-icon layui-icon-down"><ul class="layui-nav" lay-filter=""><li class="layui-nav-item"><a href="javascript:;"></a><dl class="layui-nav-child">';

		// 处 理 选 项 卡 头 部
		var index = 0;
		$.each(option.data, function(i, item) {
			var TitleItem = '';
			if (option.index == index) {
				TitleItem += '<li lay-id="' + item.id +
					'" class="layui-this"><span class="pear-tab-active"></span>';
			} else {
				TitleItem += '<li lay-id="' + item.id + '" ><span class="pear-tab-active"></span>';
			}

			if (item.close) {
				// 当 前 选 项 卡 可 以 关 闭
				TitleItem += '<span class="able-close title">' + item.title + '</span>';
			} else {
				// 当 前 选 项 卡 不 允 许 关 闭
				TitleItem += '<span class="disable-close title">' + item.title + '</span>';
			}
			TitleItem += '<i class="layui-icon layui-unselect layui-tabs-close">ဆ</i></li>';
			title += TitleItem;
			if (option.index == index) {

				// 处 理 显 示 内 容
				content += '<div class="layui-show layui-tabs-item"><iframe id="' + item.id +
					'" data-frameid="' + item.id +
					'"  src="' + item.url +
					'" frameborder="no" border="0" marginwidth="0" marginheight="0" style="width: 100%;height: 100%;" allowfullscreen="true"></iframe></div>'
			} else {
				if (!option.preload) {
					item.url = "about:blank";
				}
				// 处 理 显 示 内 容
				content += '<div class="layui-tabs-item"><iframe id="' + item.id + '" data-frameid="' +
					item.id + '"  src="' +
					item.url +
					'" frameborder="no" border="0" marginwidth="0" marginheight="0" style="width: 100%;height: 100%;" allowfullscreen="true"></iframe></div>'
			}
			index++;
		});

		title += '</ul>';
		content += '</div>';
		control += '<dd id="closeThis"><a href="#">关 闭 当 前</a></dd>'
		control += '<dd id="closeOther"><a href="#">关 闭 其 他</a></dd>'
		control += '<dd id="closeAll"><a href="#">关 闭 全 部</a></dd>'
		control += '</dl></li></ul></li></div>';

		tab += title;
		tab += control;
		tab += content;
		tab += '</div>';
		tab += ''
		return tab;
	}

	function closeEvent(option) {
		$(".layui-tabs[lay-filter='" + option.elem + "']").on("click", ".layui-tabs-close", function(event) {
			var layid = $(this).parent().attr("lay-id");
			tabDelete(option.elem, layid, option.closeEvent, option);
			return false //阻止冒泡
		})
	}

	function toolEvent(option) {
		$("body .layui-tabs[lay-filter='" + option.elem + "']").on("click", "#closeThis", function() {
			var currentTab = $(".layui-tabs[lay-filter='" + option.elem +
				"'] .layui-tabs-header .layui-this");
			if (currentTab.find("span").is(".able-close")) {
				var currentId = currentTab.attr("lay-id");
				tabDelete(option.elem, currentId, option.closeEvent, option);
			} else {
				layer.msg("当前页面不允许关闭", {
					icon: 3,
					time: 800
				})
			}
		})

		$("body .layui-tabs[lay-filter='" + option.elem + "']").on("click", "#closeOther", function() {
			var currentId = $(".layui-tabs[lay-filter='" + option.elem +
				"'] .layui-tabs-header .layui-this").attr("lay-id");
			var tabtitle = $(".layui-tabs[lay-filter='" + option.elem + "'] .layui-tabs-header li");
			$.each(tabtitle, function(i) {
				if ($(this).attr("lay-id") != currentId) {
					if ($(this).find("span").is(".able-close")) {
						tabDelete(option.elem, $(this).attr("lay-id"), option.closeEvent,
							option);
					}
				}
			})
		})

		$("body .layui-tabs[lay-filter='" + option.elem + "']").on("click", "#closeAll", function() {
			var currentId = $(".layui-tabs[lay-filter='" + option.elem +
				"'] .layui-tabs-header .layui-this").attr("lay-id");
			var tabtitle = $(".layui-tabs[lay-filter='" + option.elem + "'] .layui-tabs-header li");
			$.each(tabtitle, function(i) {
				if ($(this).find("span").is(".able-close")) {
					tabDelete(option.elem, $(this).attr("lay-id"), option.closeEvent, option);
				}
			})
		})
	}

	return pearTab;
})
