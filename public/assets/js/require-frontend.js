require.config({
    urlArgs: "v=" + requirejs.s.contexts._.config.config.site.version,
    //在打包压缩时将会把include中的模块合并到主文件中
    include: ['css', 'toastr', 'yzn', 'frontend', 'frontend-init', 'table', 'form', 'dragsort', 'selectpage'],
    paths: {
        'form': 'require-form',
        'table': 'require-table',
        'upload': 'require-upload',
        'dropzone': 'dropzone.min',
        // 以下的包从bower的libs目录加载
        'jquery': '../libs/jquery/jquery.min',
        'layui': '../libs/layui/layui',
        'dragsort': '../libs/dragsort/jquery.dragsort',
        'sortable': '../libs/Sortable/Sortable.min',
        'toastr': '../libs/toastr/toastr',
        'selectpage': '../libs/selectpage/selectpage',
        'citypicker': '../libs/citypicker/js/city-picker.min',
        'citypicker-data': '../libs/citypicker/js/city-picker.data.min',
        'tagsinput': '../libs/tagsinput/tagsinput.min',
    },
    // shim依赖配置
    shim: {
        'layui': {
            exports: 'layui'
        },
        'addons': ['frontend'],
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

require(['jquery'], function ($, undefined) {
    //初始配置
    var Config = requirejs.s.contexts._.config.config;
    //将Config渲染到全局
    window.Config = Config;
    // 配置语言包的路径
    var paths = {};
    // 避免目录冲突
    paths['frontend/'] = 'frontend/';
    require.config({paths: paths});

    // 初始化
    $(function () {
        require(['yzn'], function (Fast) {
            require(['frontend', 'frontend-init', 'addons'], function (Frontend, Addons) {
                //加载相应模块
                if (Config.jsname) {
                    require([Config.jsname], function (Controller) {
                        Controller[Config.actionname] != undefined && Controller[Config.actionname]();
                    }, function (e) {
                        console.error(e);
                        // 这里可捕获模块加载的错误
                    });
                }
            });
        });
    });
});
