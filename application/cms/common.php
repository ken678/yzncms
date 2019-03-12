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
// | cms函数文件
// +----------------------------------------------------------------------

use think\facade\Cache;

/**
 * 获取栏目相关信息
 * @param type $catid 栏目id
 * @param type $field 返回的字段，默认返回全部，数组
 * @param type $newCache 是否强制刷新
 * @return boolean
 */
function getCategory($catid, $field = '', $newCache = false)
{
    if (empty($catid)) {
        return false;
    }
    $key = 'getCategory_' . $catid;
    //强制刷新缓存
    if ($newCache) {
        Cache::rm($key, null);
    }
    $cache = Cache::get($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据
        $cache = db('category')->where(array('id' => $catid))->find();
        if (empty($cache)) {
            Cache::set($key, 'false', 60);
            return false;
        } else {
            //扩展配置
            $cache['setting'] = unserialize($cache['setting']);
            $cache['url'] = buildCatUrl($cache['type'], $catid);
            //栏目扩展字段
            //$cache['extend'] = $cache['setting']['extend'];
            Cache::set($key, $cache, 3600);
        }
    }
    if ($field) {
        //支持var.property，不过只支持一维数组
        if (false !== strpos($field, '.')) {
            $vars = explode('.', $field);
            return $cache[$vars[0]][$vars[1]];
        } else {
            return $cache[$field];
        }
    } else {
        return $cache;
    }
}

/**
 * 当前路径
 * 返回指定栏目路径层级
 * @param $catid 栏目id
 * @param $symbol 栏目间隔符
 */
function catpos($catid, $symbol = ' &gt; ')
{
    if (getCategory($catid) == false) {
        return '';
    }
    //获取当前栏目的 父栏目列表
    $arrparentid = array_filter(explode(',', getCategory($catid, 'arrparentid') . ',' . $catid));
    foreach ($arrparentid as $cid) {
        $url = buildCatUrl(getCategory($cid, 'type'), $cid, getCategory($cid, 'url'));
        $parsestr[] = '<a href="' . $url . '" >' . getCategory($cid, 'catname') . '</a>';
    }
    $parsestr = implode($symbol, $parsestr);
    return $parsestr;
}

/**
 * 获取模型数据
 * @param type $modelid 模型ID
 * @param type $name 返回的字段，默认返回全部，数组
 * @return boolean
 */
function getModel($modelid, $name = '')
{
    if (empty($modelid)) {
        return false;
    }
    $key = 'getModel_' . $modelid;
    $cache = Cache::get($key);
    if ($cache === 'false') {
        return false;
    }
    if (empty($cache)) {
        //读取数据
        $cache = db('Model')->where(array('id' => $modelid))->find();
        if (empty($cache)) {
            Cache::set($key, 'false', 60);
            return false;
        } else {
            Cache::set($key, $cache, 3600);
        }
    }
    if ($name) {
        return $cache[$name];
    } else {
        return $cache;
    }
}

/**
 * 字符截取
 * @param $string 需要截取的字符串
 * @param $length 长度
 * @param $dot
 */
function str_cut($sourcestr, $length, $dot = '...')
{
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr); //字符串的字节数
    while (($n < $length) && ($i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
        if ($ascnum >= 224) { //如果ASCII位高与224，
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = $i + 3; //实际Byte计为3
            $n++; //字串长度计1
        } elseif ($ascnum >= 192) { //如果ASCII位高与192，
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = $i + 2; //实际Byte计为2
            $n++; //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) {
            //如果是大写字母，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        } else {
//其他情况下，包括小写字母和半角标点符号，
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1; //实际的Byte数计1个
            $n = $n + 0.5; //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > strlen($returnstr)) {
        $returnstr = $returnstr . $dot; //超过长度时在尾处加上省略号
    }
    return $returnstr;
}

//创建内容链接
function buildContentUrl($catid, $id)
{
    return url('cms/index/shows', ['catid' => $catid, 'id' => $id]);
}

/**
 * 生成栏目URL
 */
function buildCatUrl($type, $id, $url = '')
{
    switch ($type) {
        case 3: //自定义链接
            $url = empty($url) ? '' : ((strpos($url, '://') !== false) ? $url : url($url));
            break;
        default:
            $url = url('cms/index/lists', ['catid' => $id]);
            break;
    }
    return $url;
}

/**
 * 生成分类信息中的筛选菜单
 */
function filters($modelid)
{
    $param = input();
    $options = cache('ModelField')[$modelid];
    $data = [];
    foreach ($options as $_k => $_v) {
        if (isset($_v['filtertype']) && $_v['filtertype']) {
            $_v['options'] = parse_attr($_v['options']);
        } else {
            continue;
        }
        $data[$_v['name']] = $_v;
    }
    foreach ($data as $name => $rs) {
        $url = structure_filters_url($name, $modelid, $data);
        $_ar = [];
        foreach ($rs['options'] as $k => $v) {
            $_ar[$k] = [
                'active' => false,
                'key' => $k,
                'title' => $v,
                'url' => $url . $name . '=' . $k,
            ];
        }
        if (!empty($param[$name])) {
            $_ar[$param[$name]]['active'] = true;
        }
        $rs['opt'] = $_ar;
        $rs['opt_url'] = $url;
        $data[$name] = $rs;
    }
    return $data;
}

/**
 * 生成其他筛选地址
 */
function structure_filters_url($fieldname, $modelid, $array = [])
{
    $param = input();
    foreach ($array as $name => $rs) {
        if ($fieldname == $name) {
            //排除自身
            continue;
        }
        if (isset($rs['filtertype']) && $rs['filtertype']) {
            if ($param[$name] !== '' && $param[$name] !== null) {
                $url .= $name . '=' . $param[$name] . '&';
            }
        }
    }
    return $url;
}

function paramdecode($str)
{
    $arr = [];
    $arr1 = explode('&', $str);
    foreach ($arr1 as $vo) {
        if (!empty($vo)) {
            $arr2 = explode('=', $vo);
            if (!empty($arr2[1])) {
                $arr[$arr2[0]] = $arr2[1];
            }
        }
    }
    return $arr;
}

function paramencode($arr)
{
    $str = '';
    if (!empty($arr)) {
        foreach ($arr as $key => $vo) {
            if (!empty($vo)) {
                $str .= $key . '=' . $vo . '&';
            }
        }
        $str = substr($str, 0, -1);
    }
    return $str;
}

/**
 * 生成SEO
 * @param $catid        栏目ID
 * @param $title        标题
 * @param $description  描述
 * @param $keyword      关键词
 */
function seo($catid = '', $title = '', $description = '', $keyword = '')
{
    if (!empty($title)) {
        $title = strip_tags($title);
    }
    if (!empty($description)) {
        $description = strip_tags($description);
    }
    if (!empty($keyword)) {
        $keyword = str_replace(' ', ',', strip_tags($keyword));
    }
    $site = cache("Cms_Config");
    if (!empty($catid)) {
        $cat = getCategory($catid);
    }
    $seo['site_title'] = $site['site_name'];
    $titleKeywords = "";

    $seo['keyword'] = !empty($keyword) ? $keyword : $site['site_keyword'];

    $seo['description'] = isset($description) && !empty($description) ? $description : (isset($cat['setting']['meta_description']) && !empty($cat['setting']['meta_description']) ? $cat['setting']['meta_description'] : (isset($site['site_description']) && !empty($site['site_description']) ? $site['site_description'] : ''));

    $seo['title'] = (isset($title) && !empty($title) ? $title . ' - ' : '') . (isset($cat['setting']['meta_title']) && !empty($cat['setting']['meta_title']) ? $cat['setting']['meta_title'] . ' - ' : (isset($cat['catname']) && !empty($cat['catname']) ? $cat['catname'] . ' - ' : ''));
    foreach ($seo as $k => $v) {
        $seo[$k] = str_replace(array("\n", "\r"), '', $v);
    }
    return $seo;
}
