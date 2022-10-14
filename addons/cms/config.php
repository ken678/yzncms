<?php
return array(
    [
        'name'    => 'web_site_status',
        'title'   => '站点开关',
        'type'    => 'radio',
        'options' => [
            1 => '开启',
            0 => '关闭',
        ],
        'tip'     => '站点关闭后前台将不能访问',
        'value'   => 1,
    ],
    [
        'name'    => 'site_url_mode',
        'title'   => 'URL模式',
        'type'    => 'radio',
        'options' => [
            1 => '数字',
            2 => '标识',
        ],
        'value'   => 1,
    ],
    [
        'name'  => 'site_name',
        'title' => '站点名称',
        'type'  => 'text',
        'value' => '御宅男工作室',
    ],
    [
        'name'  => 'site_title',
        'title' => '站点标题',
        'type'  => 'text',
        'value' => '御宅男工作室',
    ],
    [
        'name'  => 'site_keyword',
        'title' => '站点关键词',
        'type'  => 'text',
        'value' => '御宅男CMS,CMS,YZNCMS,内容管理系统,CMS系统',
    ],
    [
        'name'  => 'site_description',
        'title' => '站点描述',
        'type'  => 'textarea',
        'value' => 'Yzncms(又名御宅男CMS)是基于最新TP5.1框架的CMS内容管理系统。是一款完全免费开源的项目，他将是您轻松建站的首选利器。框架易于功能扩展，代码维护，方便二次开发，帮助开发者简单高效降低二次开发成本，满足专注业务深度开发的需求。',
    ],
    [
        'name'  => 'site_cache_time',
        'title' => '缓存时间',
        'type'  => 'text',
        'value' => 3600,
        'tip'   => '单页和详情页有效',
    ],
    [
        'name'    => 'web_site_recycle',
        'title'   => '回收站',
        'type'    => 'radio',
        'options' => [
            1 => '开启',
            0 => '关闭',
        ],
        'tip'     => '开启后，误删的文章可以恢复,反之不可还原',
        'value'   => 0,
    ],
    [
        'name'    => 'web_site_searchtype',
        'title'   => '搜索方式',
        'type'    => 'radio',
        'options' => [
            'local'     => '本地搜索',
            'xunsearch' => '迅搜全文搜索',
        ],
        'tip'     => '使用迅搜(xunsearch)需要安装搜索插件，支持百万以上数据检索',
        'value'   => 'local',
    ],
    [
        'name'    => 'site_category_auth',
        'title'   => '栏目授权',
        'type'    => 'radio',
        'options' => [
            1 => '开启',
            0 => '关闭',
        ],
        'tip'     => '开启后，可在【栏目授权】单独给管理员分配可管理的内容栏目',
        'value'   => 0,
    ],
    [
        'name'    => 'web_site_baidupush',
        'title'   => '站长推送',
        'type'    => 'radio',
        'options' => [
            1 => '开启',
            0 => '关闭',
        ],
        'tip'     => '如果开启百度熊掌+百度站长推送，将在文章发布时自动进行推送(需要安装推送插件)',
        'value'   => 0,
    ],
    [
        'name'    => 'web_site_getwords',
        'title'   => '中文分词',
        'type'    => 'radio',
        'options' => [
            1 => '开启',
            0 => '关闭',
        ],
        'tip'     => '开启后，会将内容分词提取到关键词中(需要安装中文分词插件)',
        'value'   => 0,
    ],
    [
        'name'  => 'autolinks',
        'title' => '关键字链接',
        'type'  => 'textarea',
        'value' => '百度|https://www.baidu.com/
腾讯|https://www.qq.com/',
        'tip'   => '每行1组以"关键词<b class="text-danger">|</b>(半角竖线)链接"形式填写, 可用第2个竖线追加参数:<b class="text-danger">n</b>代表nofollow标记, <b class="text-danger">e</b>代表external nofollow标记, <b class="text-danger">b</b>代表本窗口打开.</br> 例: google<strong>|</strong>http://www.google.com<strong>|</strong>n 即此链接带nofollow(默认新窗口打开)',
    ],
);
