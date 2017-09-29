# Yzncms内容管理系统V1.0.0测试版
![输入图片说明](https://img.shields.io/badge/php-%3E%3D5.4-000.svg "在这里输入图片标题")

>支持Yzncms的用户请给我们一个star

[项目介绍]
```
Yzncms(又名御宅男cms)是完全开源的项目，基于ThinkPHP5.011最新版,框架易于功能扩展，代码维护，方便二次开发  
帮助开发者简单高效降低二次开发成本，满足专注业务深度开发的需求。
```

[功能介绍]
```
核心版本的YZNCMS会完成如下功能 具体请下载体验
AUTH权限：用户权限
数据库管理：备份导入下载数据库等功能
网站设置：设置网站基本设置邮箱设置等 可用自定义设置几种字段
自定义模型：可用创建下载模型，文章模型里面字段都可以自定义 如编辑器，多图片，多附件...十几种字段选择
*模块安装：如友情链接，自定义表单，论坛，商城,会员模块等
*插件安装：如返回顶部，留言系统插件等
```

[使用帮助]
```
[使用手册](https://www.kancloud.cn/ken678/yzncms) 完成度5%
[开源中国](http://www.oschina.net/p/yzncms)
```

[环境要求]
```
ThinkPHP5.09的运行环境要求PHP5.4以上。（注意：PHP5.4dev版本和PHP6均不支持）
PDO拓展
MBstring拓展
CURL拓展
```

[安装教程]
```
第一步：修改数据库配置 apps/common/conf/database.php  
第二步：将根目录的yzncms.sql文件导入数据库即可  
第三步：后台入口 http://您的域名/admin 默认账号密码 admin    123456   

PS 几个注意点
1.如果已经安装过了 拷贝的时候记得数据库 换成新的 因为目前还是测试版 数据库跨度比较大 需要换成新的数据库SQL文件
2.本地测试请绑定虚拟域名 类似www.yzncms.com   不能使用localhost/yzncms这种带目录的域名 一般虚拟主机都带绑定域名的功能
```

[PS]
```
本系统持续更新 由于时间关系 更新时间较长
本系统从0开始发布  TP新手可以看看如何写一个cms
```
[有问必答]
```
本人非常乐意帮助新手，如果对本产品或者TP5.0有任何的疑问 都可以提交到issues 都会帮忙解决
工作原因 QQ请不要联系 谢谢！！！
```

[目录结构]
```
www  WEB部署目录（或者子目录）
├─apps                  应用目录
├─extend                扩展类库目录
├─static                前后台资源目录
├─runtime               应用的运行时目录（可写，可定制）
├─templates             前台模板目录
├─thinkphp              框架系统目录
├─uploads               文件上传目录
├─vendor                第三方类库目录（Composer依赖库）
├─composer.json         composer 定义文件
├─composer.lock         composer 锁文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
```

[后台登陆界面预览]  
![输入图片说明](https://git.oschina.net/uploads/images/2017/0929/171926_8898a2ab_555541.jpeg "QQ截图20170929171324.jpg")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0904/091039_830d8119_555541.png "添加内容.png")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0601/134316_df6a7b60_555541.jpeg "在这里输入图片标题")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0905/175949_bc011124_555541.png "Yzncms.png")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0613/152302_f2081fba_555541.png "在这里输入图片标题")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0601/134327_f760ee6b_555541.jpeg "在这里输入图片标题")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0601/134335_cd0f4d67_555541.jpeg "在这里输入图片标题")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0601/134344_ce09d530_555541.jpeg "在这里输入图片标题")
![输入图片说明](https://git.oschina.net/uploads/images/2017/0715/212221_2809862f_555541.png "Yzncms.png")