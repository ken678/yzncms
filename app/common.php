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
// | 全局函数文件
// +----------------------------------------------------------------------
use think\App;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;

!defined('DS') && define('DS', DIRECTORY_SEPARATOR);
// 运行目录
define('ROOT_URL', Request::rootUrl() . '/');
//模板目录
define('TEMPLATE_PATH', App::getRootPath() . 'templates' . DS);

if (!function_exists('cdnurl')) {
    /**
     * 获取上传资源的CDN的地址
     * @param string  $url    资源相对地址
     * @param string|bool $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl(string $url, string | bool $domain = false): string
    {
        $regex  = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
        $cdnurl = Config::get('upload.cdnurl');
        if (is_bool($domain) || stripos($cdnurl, '/') === 0) {
            $url = preg_match($regex, $url) || ($cdnurl && stripos($url, $cdnurl) === 0) ? $url : $cdnurl . $url;
        }
        if ($domain && !preg_match($regex, $url)) {
            $domain = is_bool($domain) ? Request::domain() : $domain;
            $url    = $domain . $url;
        }
        return $url;
    }
}

if (!function_exists('int_to_string')) {
    /**
     * select返回的数组进行整数映射转换
     *
     * @param array $map  映射关系二维数组  array(
     *                                          '字段名1'=>array(映射关系数组),
     *                                          '字段名2'=>array(映射关系数组),
     *                                           ......
     *                                       )
     * @author 朱亚杰 <zhuyajie@topthink.net>
     * @return array
     *
     *  array(
     *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
     *      ....
     *  )
     *
     */
    function int_to_string(&$data, $map = ['status' => [1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿']])
    {
        if ($data === false || $data === null) {
            return $data;
        }
        $data = (array) $data;
        foreach ($data as $key => $row) {
            foreach ($map as $col => $pair) {
                if (isset($row[$col]) && isset($pair[$row[$col]])) {
                    $data[$key][$col . '_text'] = $pair[$row[$col]];
                }
            }
        }
        return $data;
    }
}

if (!function_exists('str_cut')) {
    /**
     * 字符截取
     * @param $string 需要截取的字符串
     * @param $length 长度
     * @param $dot
     */
    function str_cut($sourcestr, $length, $dot = '...')
    {
        $returnstr  = '';
        $i          = 0;
        $n          = 0;
        $str_length = strlen($sourcestr); //字符串的字节数
        while (($n < $length) && ($i <= $str_length)) {
            $temp_str = substr($sourcestr, $i, 1);
            $ascnum   = Ord($temp_str); //得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224) {
                //如果ASCII位高与224，
                $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i         = $i + 3; //实际Byte计为3
                $n++; //字串长度计1
            } elseif ($ascnum >= 192) {
                //如果ASCII位高与192，
                $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i         = $i + 2; //实际Byte计为2
                $n++; //字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 90) {
                //如果是大写字母，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i         = $i + 1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
            } else {
                //其他情况下，包括小写字母和半角标点符号，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i         = $i + 1; //实际的Byte数计1个
                $n         = $n + 0.5; //小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ($str_length > strlen($returnstr)) {
            $returnstr = $returnstr . $dot; //超过长度时在尾处加上省略号
        }
        return $returnstr;
    }
}

if (!function_exists('list_sort_by')) {
    /**
     * 对查询结果集进行排序
     * @access public
     * @param array $list   查询结果
     * @param string $field 排序的字段名
     * @param array $sortby 排序类型
     * asc正向排序 desc逆向排序 nat自然排序
     * @return array
     */
    function list_sort_by($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = [];
            foreach ($list as $i => $data) {
                $refer[$i] = &$data[$field];
            }

            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc': // 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val) {
                $resultSet[] = &$list[$key];
            }

            return $resultSet;
        }
        return false;
    }
}

if (!function_exists('list_to_tree')) {
    /**
     * 把返回的数据集转换成Tree
     * @param array $list   要转换的数据集
     * @param string $pid   parent标记字段
     * @param string $level level标记字段
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    function list_to_tree($list, $pk = 'id', $pid = 'parentid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent           = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('parse_attr')) {
    /**
     * 解析配置
     * @param string $value 配置值
     * @return array|string
     */
    function parse_attr($value = '')
    {
        $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
        if (strpos($value, ':')) {
            $value = [];
            foreach ($array as $val) {
                [$k, $v] = explode(':', $val);
                $value[$k]   = $v;
            }
        } else {
            $value = $array;
        }
        return $value;
    }
}

if (!function_exists('time_format')) {
    /**
     * 时间戳格式化
     * @param int $timestamp
     * @return string 完整的时间显示
     */
    function time_format($timestamp = null, $type = 0)
    {
        if ($timestamp == 0) {
            return '';
        }
        $types     = ['Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d'];
        $timestamp = $timestamp === null ? $_SERVER['REQUEST_TIME'] : intval($timestamp);
        return date($types[$type], $timestamp);
    }
}

if (!function_exists('human_date')) {
    /**
     * 获取语义化时间
     * @param int $time  时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \util\Date::human($time, $local);
    }
}

if (!function_exists('format_bytes')) {
    /**
     * 格式化字节大小
     * @param  number $size      字节数
     * @param  string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }
}

if (!function_exists('to_guid_string')) {
    /**
     * 根据PHP各种类型变量生成唯一标识号
     * @param mixed $mix 变量
     * @return string
     */
    function to_guid_string($mix)
    {
        if (is_object($mix)) {
            return spl_object_hash($mix);
        } elseif (is_resource($mix)) {
            $mix = get_resource_type($mix) . strval($mix);
        } else {
            $mix = serialize($mix);
        }
        return md5($mix);
    }
}

if (!function_exists('encrypt_password')) {
    /**
     * 对用户的密码进行加密
     * @param $password
     * @param $encrypt 传入加密串，在修改密码时做认证
     * @param $fun 加密函数
     * @return array/password
     */
    function encrypt_password($password, $encrypt = '', $fun = 'md5')
    {
        $pwd             = [];
        $pwd['encrypt']  = $encrypt ? $encrypt : genRandomString();
        $pwd['password'] = $fun($fun($password) . $pwd['encrypt']);
        return $encrypt ? $pwd['password'] : $pwd;
    }
}

if (!function_exists('genRandomString')) {
    /**
     * 产生一个指定长度的随机字符串,并返回给用户
     * @param type $len 产生字符串的长度
     * @return string   随机字符串
     */
    function genRandomString($len = 6)
    {
        $chars = [
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9",
        ];
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
}

if (!function_exists('getModel')) {
    /**
     * 获取模型数据
     * @param type $modelid 模型ID
     * @param type $name 返回的字段，默认返回全部，数组
     * @return boolean
     */
    function getModel($modelid, $name = '')
    {
        if (empty($modelid) || !is_numeric($modelid)) {
            return false;
        }
        $key = 'getModel_' . $modelid;
        /* 读取缓存数据 */
        $cache = Cache::get($key);
        if ($cache === 'false') {
            return false;
        }
        if (empty($cache)) {
            //读取数据
            $cache = Db::name('Model')->find($modelid);
            if (empty($cache)) {
                Cache::set($key, 'false', 60);
                return false;
            } else {
                Cache::set($key, $cache, 3600);
            }
        }
        return is_null($name) ? $cache : $cache[$name];
    }
}

if (!function_exists('http_down')) {
    /**
     * 下载远程文件，默认保存在temp下
     * @param  string  $url      网址
     * @param  string  $filename 保存文件名
     * @param  integer $timeout  过期时间
     * @param  bool $repalce     是否覆盖已存在文件
     * @return string            本地文件名
     */
    function http_down($url, $filename = "", $timeout = 60)
    {
        if (empty($filename)) {
            $filename = public_path() . 'temp' . DS . pathinfo($url, PATHINFO_BASENAME);
        }
        $path = dirname($filename);
        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            return false;
        }
        $url = str_replace(" ", "%20", $url);
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
            // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            if ('https' == substr($url, 0, 5)) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }
            $temp = curl_exec($ch);
            if (file_put_contents($filename, $temp) && !curl_error($ch)) {
                return $filename;
            } else {
                return false;
            }
        } else {
            $opts = [
                "http" => [
                    "method"  => "GET",
                    "header"  => "",
                    "timeout" => $timeout,
                ],
            ];
            $context = stream_context_create($opts);
            if (@copy($url, $filename, $context)) {
                //$http_response_header
                return $filename;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('safe_replace')) {
    /**
     * 安全过滤函数
     * @param $string
     * @return string
     */
    function safe_replace($string)
    {
        $string = str_replace('%20', '', $string);
        $string = str_replace('%27', '', $string);
        $string = str_replace('%2527', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace("{", '', $string);
        $string = str_replace('}', '', $string);
        $string = str_replace('\\', '', $string);
        return $string;
    }
}

if (!function_exists('sys_auth')) {
    /**
     * 字符串加密、解密函数
     * @param    string    $txt        字符串
     * @param    string    $operation  ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
     * @param    string    $key        密钥：数字、字母、下划线
     * @param    string    $expiry     过期时间
     * @return    string
     */
    function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        $key         = md5($key != '' ? $key : Config::get('yzn.data_auth_key'));
        $keya        = md5(substr($key, 0, 16));
        $keyb        = md5(substr($key, 16, 16));
        $keyc        = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey   = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string        = $operation == 'DECODE' ? base64_decode(strtr(substr($string, $ckey_length), '-_', '+/')) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box    = range(0, 255);

        $rndkey = [];
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp     = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a       = ($a + 1) % 256;
            $j       = ($j + $box[$a]) % 256;
            $tmp     = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . rtrim(strtr(base64_encode($result), '+/', '-_'), '=');
        }
    }
}

if (!function_exists('hsv2rgb')) {
    function hsv2rgb($h, $s, $v)
    {
        $r = $g = $b = 0;

        $i = floor($h * 6);
        $f = $h * 6 - $i;
        $p = $v * (1 - $s);
        $q = $v * (1 - $f * $s);
        $t = $v * (1 - (1 - $f) * $s);

        switch ($i % 6) {
            case 0:
                $r = $v;
                $g = $t;
                $b = $p;
                break;
            case 1:
                $r = $q;
                $g = $v;
                $b = $p;
                break;
            case 2:
                $r = $p;
                $g = $v;
                $b = $t;
                break;
            case 3:
                $r = $p;
                $g = $q;
                $b = $v;
                break;
            case 4:
                $r = $t;
                $g = $p;
                $b = $v;
                break;
            case 5:
                $r = $v;
                $g = $p;
                $b = $q;
                break;
        }

        return [
            floor($r * 255),
            floor($g * 255),
            floor($b * 255),
        ];
    }
}

if (!function_exists('letter_avatar')) {
    /**
     * 首字母头像
     * @param $text
     * @return string
     */
    function letter_avatar($text)
    {
        $total           = unpack('L', hash('adler32', $text, true))[1];
        $hue             = $total % 360;
        [$r, $g, $b] = hsv2rgb($hue / 360, 0.3, 0.9);

        $bg    = "rgb({$r},{$g},{$b})";
        $color = "#ffffff";
        $first = mb_strtoupper(mb_substr($text, 0, 1));
        $src   = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><rect fill="' . $bg . '" x="0" y="0" width="100" height="100"></rect><text x="50" y="50" font-size="50" text-copy="fast" fill="' . $color . '" text-anchor="middle" text-rights="admin" alignment-baseline="central">' . $first . '</text></svg>');
        $value = 'data:image/svg+xml;base64,' . $src;
        return $value;
    }
}

if (!function_exists('build_suffix_image')) {
    /**
     * 生成文件后缀图片
     * @param string $suffix 后缀
     * @param null   $background
     * @return string
     */
    function build_suffix_image($suffix, $background = null)
    {
        $suffix          = mb_substr(strtoupper($suffix), 0, 4);
        $total           = unpack('L', hash('adler32', $suffix, true))[1];
        $hue             = $total % 360;
        [$r, $g, $b] = hsv2rgb($hue / 360, 0.3, 0.9);

        $background = $background ? $background : "rgb({$r},{$g},{$b})";

        $icon = <<<EOT
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
            <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
            <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
            <path style="fill:{$background};" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16 V416z"/>
            <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
            <g><text><tspan x="220" y="380" font-size="124" font-family="Verdana, Helvetica, Arial, sans-serif" fill="white" text-anchor="middle">{$suffix}</tspan></text></g>
        </svg>
EOT;
        return $icon;
    }
}

if (!function_exists('check_cors_request')) {
    /**
     * 跨域检测
     */
    function check_cors_request()
    {
        //跨域访问的时候才会存在此字段
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN']) {
            $info        = parse_url($_SERVER['HTTP_ORIGIN']);
            $domainArr   = explode(',', Config::get('yzn.cors_request_domain'));
            $domainArr[] = Request::host(true);
            if (in_array("*", $domainArr) || in_array($_SERVER['HTTP_ORIGIN'], $domainArr) || (isset($info['host']) && in_array($info['host'], $domainArr))) {
                header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            } else {
                header('HTTP/1.1 403 Forbidden');
                exit;
            }
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
                }
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                }
                exit;
            }
        }
    }
}

if (!function_exists('xss_clean')) {
    /**
     * 清理XSS
     */
    function xss_clean($content, $is_image = false)
    {
        return \app\common\library\Security::instance()->xss_clean($content, $is_image);
    }
}

if (!function_exists('url_clean')) {
    /**
     * 清理URL
     */
    function url_clean($url)
    {
        if (!check_url_allowed($url)) {
            return '';
        }
        return xss_clean($url);
    }
}

if (!function_exists('check_url_allowed')) {
    /**
     * 检测URL是否允许范围内
     * @param  string  $url
     * @return bool
     */
    function check_url_allowed(string $url = ''): bool
    {
        //允许的主机列表
        $allowedHostArr = [
            strtolower(request()->host()),
        ];

        if (empty($url)) {
            return true;
        }

        //如果是站内相对链接则允许
        if (preg_match("/^[\/a-z][a-z0-9][a-z0-9\.\/]+((\?|#).*)?\$/i", $url) && substr($url, 0, 2) !== '//') {
            return true;
        }

        //如果是站外链接则需要判断HOST是否允许
        if (preg_match("/((http[s]?:\/\/)+(?>[a-z\-0-9]{2,}\.){1,}[a-z]{2,8})(?:\s|\/)/i", $url)) {
            $chkHost = parse_url(strtolower($url), PHP_URL_HOST);
            if ($chkHost && in_array($chkHost, $allowedHostArr)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('check_nav_active')) {
    /**
     * 检测会员中心导航是否高亮
     */
    function check_nav_active($url, $classname = 'layui-this')
    {
        $auth       = \app\common\library\Auth::instance();
        $requestUrl = $auth->getRequestUri();
        $url        = ltrim($url, '/');
        return $requestUrl === str_replace(".", "/", $url) ? $classname : '';
    }
}

if (!function_exists('var_export_short')) {
    /**
     * 使用短标签打印或返回数组结构
     * @param mixed   $data
     * @param boolean $return 是否返回数据
     * @return string
     */
    function var_export_short($data, $return = true)
    {
        return var_export($data, $return);
        $replaced = [];
        $count    = 0;

        //判断是否是对象
        if (is_resource($data) || is_object($data)) {
            return var_export($data, $return);
        }

        //判断是否有特殊的键名
        $specialKey = false;
        array_walk_recursive($data, function (&$value, &$key) use (&$specialKey) {
            if (is_string($key) && (stripos($key, "\n") !== false || stripos($key, "array (") !== false)) {
                $specialKey = true;
            }
        });
        if ($specialKey) {
            return var_export($data, $return);
        }
        array_walk_recursive($data, function (&$value, &$key) use (&$replaced, &$count, &$stringcheck) {
            if (is_object($value) || is_resource($value)) {
                $replaced[$count] = var_export($value, true);
                $value            = "##<{$count}>##";
            } else {
                if (is_string($value) && (stripos($value, "\n") !== false || stripos($value, "array (") !== false)) {
                    $index = array_search($value, $replaced);
                    if ($index === false) {
                        $replaced[$count] = var_export($value, true);
                        $value            = "##<{$count}>##";
                    } else {
                        $value = "##<{$index}>##";
                    }
                }
            }
            $count++;
        });

        $dump = var_export($data, true);

        $dump = preg_replace('#(?:\A|\n)([ ]*)array \(#i', '[', $dump); // Starts
        $dump = preg_replace('#\n([ ]*)\),#', "\n$1],", $dump); // Ends
        $dump = preg_replace('#=> \[\n\s+\],\n#', "=> [],\n", $dump); // Empties
        $dump = preg_replace('#\)$#', "]", $dump); //End

        if ($replaced) {
            $dump = preg_replace_callback("/'##<(\d+)>##'/", function ($matches) use ($replaced) {
                return isset($replaced[$matches[1]]) ? $replaced[$matches[1]] : "''";
            }, $dump);
        }

        if ($return === true) {
            return $dump;
        } else {
            echo $dump;
        }
    }
}
