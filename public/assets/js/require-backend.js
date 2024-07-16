require.config({
    urlArgs: "v=" + requirejs.s.contexts._.config.config.site.version,
    //在打包压缩时将会把include中的模块合并到主文件中
    include: ['css','toastr','yzn', 'backend', 'backend-init','table','form', 'selectpage', 'dragsort'],
    paths: {
        'form': 'require-form',
        'table': 'require-table',
        'upload': 'require-upload',
        'dropzone': 'dropzone.min',
        'echarts': 'echarts.min',
        'echarts-theme': 'echarts-theme',
        // 以下的包从bower的libs目录加载
        'jquery': '../libs/jquery/jquery.min',
        'layui': '../libs/layui/layui',
        'toastr': '../libs/toastr/toastr',
        'pearAdmin': '../libs/pear/admin',
        'pearFrame': '../libs/pear/frame',
        'pearFullscreen': '../libs/pear/fullscreen',
        'pearMenu': '../libs/pear/menu',
        'pearTab': '../libs/pear/tab',
        'pearTheme': '../libs/pear/theme',
        'clipboard': '../libs/clipboard/clipboard.min',
        'selectpage': '../libs/selectpage/selectpage',
        'dragsort': '../libs/dragsort/jquery.dragsort',
        'sortable': '../libs/Sortable/Sortable.min',
        'jstree': '../libs/jstree/jstree.min',
        'iconPicker': '../libs/iconPicker/iconPicker',
        'citypicker': '../libs/citypicker/js/city-picker.min',
        'citypicker-data': '../libs/citypicker/js/city-picker.data.min',
        'tagsinput': '../libs/tagsinput/tagsinput.min',
        'cxselect': '../libs/cxselect/jquery.cxselect',
    },
    // shim依赖配置
    shim: {
        'layui': {
            exports: 'layui'
        },
        'addons': ['backend'],
        'jstree': ['css!../libs/jstree/themes/default/style.css'],
        'citypicker': ['citypicker-data', 'css!../libs/citypicker/css/city-picker.css'],
        'tagsinput': ['css!../libs/tagsinput/tagsinput.min.css'],
    },
    baseUrl: requirejs.s.contexts._.config.config.site.cdnurl + '/assets/js/', //资源基础路径
    map: {
        '*': {
            'css': '../libs/require-css/css.min'
        }
    },
    waitSeconds: 60,
    charset: 'utf-8' // 文件编码
});

require(['jquery'], function($, undefined) {
    //初始配置
    var Config = requirejs.s.contexts._.config.config;
    //将Config渲染到全局
    window.Config = Config;
    var paths = {};
    // 避免目录冲突
    paths['backend/'] = 'backend/';
    require.config({ paths: paths });

    // 初始化
    $(function() {
        require(['yzn'], function(Yzn) {
            require(['backend', 'backend-init', 'addons'], function(Backend, undefined, Addons) {
                //加载相应模块
                if (Config.jsname) {
                    require([Config.jsname], function(Controller) {
                        if (Controller.hasOwnProperty(Config.actionname)) {
                            Controller[Config.actionname]();
                        } else {
                            if (Controller.hasOwnProperty("_empty")) {
                                Controller._empty();
                            }
                        }
                    }, function(e) {
                        console.error(e);
                        // 这里可捕获模块加载的错误
                    });
                }
            });
        });
    });
});