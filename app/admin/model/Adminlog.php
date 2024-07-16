<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 后台用户管理
// +----------------------------------------------------------------------
namespace app\admin\model;

use app\admin\library\Auth;
use think\Model;

class Adminlog extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    //自定义日志标题
    protected static $title = '';
    //自定义日志内容
    protected static $content = '';
    //忽略的链接正则列表
    protected static $ignoreRegex = [
        '/^(.*)\/(selectpage|index|logout)$/i',
    ];

    public static function setTitle($title)
    {
        self::$title = $title;
    }

    public static function setContent($content)
    {
        self::$content = $content;
    }

    public static function setIgnoreRegex($regex = [])
    {
        $regex             = is_array($regex) ? $regex : [$regex];
        self::$ignoreRegex = array_merge(self::$ignoreRegex, $regex);
    }

    /**
     * 记录日志
     * @param string $title
     * @param string $content
     */
    public static function record($title = '', $content = '')
    {
        $auth     = Auth::instance();
        $admin_id = $auth->isLogin() ? $auth->id : 0;
        $username = $auth->isLogin() ? $auth->username : '未知';

        // 设置过滤函数
        request()->filter('trim,strip_tags,htmlspecialchars');

        $controllername = parse_name(request()->controller(true));
        $actionname     = strtolower(request()->action());
        $path           = $controllername . '/' . $actionname;
        if (self::$ignoreRegex) {
            foreach (self::$ignoreRegex as $index => $item) {
                if (preg_match($item, $path)) {
                    return;
                }
            }
        }
        $content = $content ?: self::$content;
        if (!$content) {
            $content = request()->param('', null);
            $content = self::getPureContent($content);
        }
        $title = $title ?: self::$title;
        if (!$title) {
            $controllerTitle = AuthRule::where('name', $controllername)->value('title');
            $title           = AuthRule::where('name', $path)->value('title');
            $title           = $title ?: '未知' . '(' . $actionname . ')';
            $title           = $controllerTitle ? ($controllerTitle . '-' . $title) : $title;
        }
        self::create([
            'title'     => $title,
            'content'   => !is_scalar($content) ? json_encode($content, JSON_UNESCAPED_UNICODE) : $content,
            'url'       => substr(xss_clean(strip_tags(request()->url())), 0, 1500),
            'admin_id'  => $admin_id,
            'username'  => $username,
            'useragent' => substr(request()->server('HTTP_USER_AGENT'), 0, 255),
            'ip'        => xss_clean(strip_tags(request()->ip())),
        ]);
    }

    /**
     * 获取已屏蔽关键信息的数据
     * @param $content
     * @return false|string
     */
    protected static function getPureContent($content)
    {
        if (!is_array($content)) {
            return $content;
        }
        foreach ($content as $index => &$item) {
            if (preg_match("/(password|salt|token)/i", $index)) {
                $item = "***";
            } else {
                if (is_array($item)) {
                    $item = self::getPureContent($item);
                }
            }
        }
        return $content;
    }

}
