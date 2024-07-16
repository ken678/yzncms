define(['jquery'], function($) {
    "use strict";
    var defer = $.Deferred();
    var pearFullScreen = {
        func : null,
        onFullchange : function(func) {
            this.func = func;
            var evts = ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'];
            for (var i = 0; i < evts.length && func; i++) {
                window.addEventListener(evts[i], this.func);
            }
        },
        fullScreen : function(dom) {
            var docElm = dom && document.querySelector(dom) || document.documentElement;
            if (docElm.requestFullscreen) {
                docElm.requestFullscreen();
            } else if (docElm.mozRequestFullScreen) {
                docElm.mozRequestFullScreen();
            } else if (docElm.webkitRequestFullScreen) {
                docElm.webkitRequestFullScreen();
            } else if (docElm.msRequestFullscreen) {
                docElm.msRequestFullscreen();
            } else {
                defer.reject("");
            }
            defer.resolve("返回值");
            return defer.promise();
        },
        fullClose : function() {
            if (this.isFullscreen()) {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
            defer.resolve("返回值");
            return defer.promise();
        },
        isFullscreen : function() {
            return document.fullscreenElement ||
                document.msFullscreenElement ||
                document.mozFullScreenElement ||
                document.webkitFullscreenElement || false;
        },
    };
    return pearFullScreen;
})