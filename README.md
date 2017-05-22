# Yzncms内容管理系统V1.0.0

##项目介绍
Yzncms是完全开源的项目，基于ThinkPHP5.09最新版,框架易于功能扩展，代码维护，方便二次开发，帮助开发者简单高效降低二次开发成本，满足专注业务深度开发的需求。

##环境要求
> ThinkPHP5.09的运行环境要求PHP5.4以上。（注意：PHP5.4dev版本和PHP6均不支持）

##安装教程
第一步：修改数据库配置 apps/common/conf/database.php  
第二步：将根目录的sql文件导入数据库即可  
第三步：进后台后缀加/admin即可  默认账号密码admin  123456  

##PS
本系统持续更新 由于时间关系 更新时间较长
本系统从0开始发布  TP新手可以看看如何写一个cms

##后台登陆界面预览
![登录界面](http://git.oschina.net/uploads/images/2017/0328/112350_1559e31d_555541.jpeg "登录界面")
![管理日志](https://git.oschina.net/uploads/images/2017/0509/154230_04191116_555541.jpeg "管理日志")


##目录结构
~~~
www  WEB部署目录（或者子目录）
├─apps          应用目录
│  ├─admin              后台模块目录
│  ├─home               前台模块目录
│  └─common             公共模块目录
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
├─index.php             入口文件
~~~