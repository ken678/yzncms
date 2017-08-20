<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------
namespace util;
class Form {

    /**
     * 编辑器
     * @param int $textareaid 字段名
     * @param int $toolbar 标准型 full 简洁型 basic
     * @param string $module 模块名称
     * @param int $catid 栏目id
     * @param boole $allowupload  是否允许上传
     * @param boole $allowbrowser 是否允许浏览文件
     * @param string $alowuploadexts 允许上传类型
     * @param string $allowuploadnum 每次允许上传的文件数量
     * @param string $height 编辑器高度
     * @param string $disabled_page 是否禁用分页和子标题
     * 附件上传，要引入这两个JS content_addtop.js swf2ckeditor.js
     * 注意：使用这个，需另外单独增加编辑的实例化代码！
     */
    public static function editor($textareaid = 'content', $toolbar = 'basic', $module = '', $catid = '', $allowupload = 0, $allowbrowser = 1, $alowuploadexts = '', $allowuploadnum = '10', $height = 200, $disabled_page = 0)
    {
        $str = "";
        //加载编辑器所需JS，多编辑器字段防止重复加载
        if (!defined('EDITOR_INIT')) {
            $str .= '
                <script type="text/javascript">
                //编辑器路径定义
                var editorURL = "'.url('attachment/Ueditor/run').'";
                </script>
                <script type="text/javascript" charset="utf-8" src="__STATIC__/js/ueditor/ueditor.config.js"></script>
                <script type="text/javascript" charset="utf-8" src="__STATIC__/js/ueditor/ueditor.all.min.js"> </script>
                <script type="text/javascript" charset="utf-8" src="__STATIC__/js/ueditor/lang/zh-cn/zh-cn.js"></script>';
            define('EDITOR_INIT', 1);
        }
         //编辑器类型
        if ($toolbar == 'basic') {//简洁型
            $toolbar = "['FullScreen', 'Source', '|', 'Undo', 'Redo', '|','FontSize','Bold', 'forecolor', 'Italic', 'Underline', 'Link',  '|',  'InsertImage',
                 'ClearDoc',  'CheckImage','Emotion',  " . ($allowupload && $allowbrowser ? "'attachment'," : "") . " 'PageBreak','insertcode', 'WordImage','RemoveFormat', 'FormatMatch','AutoTypeSet']
                ";
        } elseif ($toolbar == 'full') {//标准型
            $toolbar = "['fullscreen', 'source', '|', 'undo', 'redo', '|','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|','rowspacingtop', 'rowspacingbottom', 'lineheight', '|','customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','directionalityltr', 'directionalityrtl', 'indent', '|','justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|','link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|','simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|','horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|','inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|','print', 'preview', 'searchreplace', 'drafts', 'help']";
        }
        $str .= "\r\n<script type=\"text/javascript\">\r\n";
        $str .= "var ue_{$textareaid} = UE.getEditor('{$textareaid}',{autoHeightEnabled: false,toolbars: [$toolbar]});";
        $str .= "ue_{$textareaid}.ready(function() {
                       ue_{$textareaid}.execCommand('serverparam', {
                          'catid': '{$catid}'
                       });
                       ue_{$textareaid}.setHeight($height);

                });";
        $str .= "\r\n</script>";
        return $str;
    }


  /*

        $sess_id = time();
        $isadmin = \Admin\Service\User::getInstance()->id ? 1 : 0;
        if ($isadmin) {
            $userid = \Admin\Service\User::getInstance()->id;
            $groupid = 0;
        } else {
            $userid = service('Passport')->userid;
            $groupid = service('Passport')->groupid ? service('Passport')->groupid : 8;
        }
        $authkey = md5(C("AUTHCODE") . $sess_id . $userid . $isadmin);
        $str .= "\r\n<script type=\"text/javascript\">\r\n";
        $str .= " var editor{$textareaid} = UE.getEditor('{$textareaid}',{
                            textarea:'" . ( in_array($module, array("Content", "content")) ? "info[$textareaid]" : "$textareaid" ) . "',
                            toolbars:[$toolbar],
                            autoHeightEnabled:false
                      });
                      editor{$textareaid}.ready(function(){
                            editor{$textareaid}.execCommand('serverparam', {
                                  'catid': '{$catid}',
                                  '_https':'" . CONFIG_SITEURL_MODEL . "',
                                  'isadmin':'{$isadmin}',
                                  'module':'{$module}',
                                  'uid':'{$userid}',
                                  'groupid':'{$groupid}',
                                  'sessid':'{$sess_id}',
                                  'authkey':'$authkey',
                                  'allowupload':'{$allowupload}',
                                  'allowbrowser':'{$allowbrowser}',
                                  'alowuploadexts':'{$alowuploadexts}'
                             });
                             editor{$textareaid}.setHeight($height);
                      });
                      ";
        $str .= "\r\n</script>";
        return $str;
    }*/

    /**
     * 单张图片上传
     * @param string $name 表单名称
     * @param int $id 表单id
     * @param string $value 表单默认值
     * @param string $moudle 模块名称
     * @param int $catid 栏目id
     * @param int $size 表单大小
     * @param string $class 表单风格
     * @param string $ext 表单扩展属性 如果 js事件等
     * @param string $alowexts 允许图片格式
     * @param array $thumb_setting 
     * @param int $watermark_setting  0或1
     */
    public static function images($name, $id = '', $value = '', $moudle = '', $catid = '', $size = 50, $class = 'input', $ext = '', $alowexts = '', $thumb_setting = array(), $watermark_setting = 0) {
        if (!$id)
            $id = $name;
        if (!$size)
            $size = 50;
        if (!empty($thumb_setting) && count($thumb_setting))
            $thumb_ext = $thumb_setting[0] . ',' . $thumb_setting[1];
        else
            $thumb_ext = ',';
        if (!$alowexts)
            $alowexts = 'jpg|jpeg|gif|bmp|png';
        //1, 允许上传的文件类型, 是否允许从已上传中选择, 图片高度, 图片宽度,是否添加水印1是
        $authkey = upload_key("1,$alowexts,1,$thumb_ext,$watermark_setting");
        return $str . "<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext ondblclick=\"image_priview(this.value);\"/> <input type=\"button\" class=\"button\" onclick=\"javascript:flashupload('{$id}_images', '附件上传','{$id}',submit_images,'1,{$alowexts},1,{$thumb_ext},{$watermark_setting}','{$moudle}','{$catid}','{$authkey}')\"/ value=\"上传图片\">";
    }

    /**
     * 
     * @param string $name 表单名称
     * @param int $id 表单id
     * @param string $value 表单默认值
     * @param string $moudle 模块名称
     * @param int $catid 栏目id
     * @param int $size 表单大小
     * @param string $class 表单风格
     * @param string $ext 表单扩展属性 如果 js事件等
     * @param string $alowexts 允许图片格式
     * @param array $file_setting 
     */
    public static function upfiles($name, $id = '', $value = '', $moudle = '', $catid = '', $size = 50, $class = '', $ext = '', $alowexts = '', $file_setting = array()) {
        if (!$id)
            $id = $name;
        if (!$size)
            $size = 50;
        //图片属性
        if (!empty($file_setting) && count($file_setting))
            $file_ext = $file_setting[0] . ',' . $file_setting[1];
        else
            $file_ext = ',';
        if (!$alowexts)
            $alowexts = 'jpg|gif';
        if (!defined('UPFILES_INIT')) {
            $str = '<script type="text/javascript" src="' . CONFIG_SITEURL_MODEL . 'statics/js/content_addtop.js"></script>';
            define('UPFILES_INIT', 1);
        }
        //1, 允许上传的文件类型, 是否允许从已上传中选择, 图片高度, 图片宽度,是否添加水印1是
        $authkey = upload_key("1,$alowexts,1,$file_ext");
        return $str . "<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/>  <input type=\"button\" class=\"button\" onclick=\"javascript:flashupload('{$id}_files', '附件上传','{$id}',submit_attachment,'1,{$alowexts},1,{$file_ext}','{$moudle}','{$catid}','{$authkey}')\"/ value=\"上传文件\">";
    }

    /**
     * 日期时间控件
     * @param $name 控件name，id
     * @param $value 选中值
     * @param $isdatetime 是否显示时间
     * @param $loadjs 是否重复加载js，防止页面程序加载不规则导致的控件无法显示
     * @param $showweek 是否显示周，使用，true | false
     */
    public static function date($name, $value = '', $isdatetime = 0, $loadjs = 0, $showweek = 'true', $timesystem = 1) {
        if ($value == '0000-00-00 00:00:00')
            $value = '';
        $id = preg_match("/\[(.*)\]/", $name, $m) ? $m[1] : $name;
        if ($isdatetime) {
            $size = 21;
            $format = '%Y-%m-%d %H:%M:%S';
            if ($timesystem) {
                $showsTime = 'true';
            } else {
                $showsTime = '12';
            }
        } else {
            $size = 10;
            $format = '%Y-%m-%d';
            $showsTime = 'false';
        }
        $str = '';
        $str .= '<input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" size="' . $size . '" class="input length_3 J_datetime ">';
        return $str;
    }

    /**
     * 栏目选择
     * @param string $file 栏目缓存文件名
     * @param intval/array $catid 别选中的ID，多选是可以是数组
     * @param string $str 属性
     * @param string $default_option 默认选项
     * @param intval $modelid 按所属模型筛选
     * @param intval $type 栏目类型
     * @param intval $onlysub 只可选择子栏目
     * @param intval $is_push 加载权限表模型 ,获取会员组ID值,以备下面投入判断用
     */
    public static function select_category($catid = 0, $str = '', $default_option = '', $modelid = 0, $type = -1, $onlysub = 0, $is_push = 0) {
        $tree = new \Tree();
        $result = cache('Category');
        $string = '<select ' . $str . '>';
        if ($default_option)
            $string .= "<option value='0'>{$default_option}</option>";
        //加载权限表模型 ,获取会员组ID值,以备下面投入判断用
        if ($is_push) {
            $priv = M('CategoryPriv');
            //用户组
            if (defined('IN_ADMIN') && IN_ADMIN) {
                //后台
                $user_groupid = \Admin\Service\User::getInstance()->role_id;
            } else {
                $user_groupid = service('Passport')->groupid ? : 8;
            }
        }

        if (is_array($result)) {
            foreach ($result as $r) {
                $r = getCategory($r['catid']);
                //检查当前会员组，在该栏目处是否允许投稿？
                if ($is_push == '1' and $r['child'] == '0') {
                    $where = array('catid' => $r['catid'], 'roleid' => $user_groupid, 'action' => 'add');
                    if (defined("IN_ADMIN") && IN_ADMIN) {
                        $where['is_admin'] = 1;
                    } else {
                        $where['is_admin'] = 0;
                    }
                    $array = $priv->where($where)->find();
                    if (!$array) {
                        continue;
                    }
                }

                $r['selected'] = '';
                if (is_array($catid)) {
                    $r['selected'] = in_array($r['catid'], $catid) ? 'selected' : '';
                } elseif (is_numeric($catid)) {
                    $r['selected'] = $catid == $r['catid'] ? 'selected' : '';
                }
                $r['html_disabled'] = "0";
                if (!empty($onlysub) && $r['child'] != 0) {
                    $r['html_disabled'] = "1";
                }
                $categorys[$r['catid']] = $r;
                if ($modelid && $r['modelid'] != $modelid)
                    unset($categorys[$r['catid']]);
            }
        }
        $str = "<option value='\$catid' \$selected>\$spacer \$catname</option>";
        $str2 = "<optgroup label='\$spacer \$catname'></optgroup>";

        $tree->init($categorys);
        $string .= $tree->get_tree_category(0, $str, $str2);
        $string .= '</select>';
        return $string;
    }

    /**
     * 下拉选择框
     * @param type $array 数据
     * @param type $id 默认选择
     * @param type $str 属性
     * @param type $default_option 默认选项
     * @return boolean|string 
     */
    public static function select($array = array(), $id = 0, $str = '', $default_option = '') {
        $string = '<select ' . $str . '>';
        $default_selected = (empty($id) && $default_option) ? 'selected' : '';
        if ($default_option)
            $string .= "<option value=\"\" $default_selected>$default_option</option>";
        if (!is_array($array) || count($array) == 0)
            return false;
        $ids = array();
        if (isset($id))
            $ids = explode(',', $id);
        foreach ($array as $key => $value) {
            $selected = in_array($key, $ids) ? 'selected' : '';
            $string .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
        $string .= '</select>';
        return $string;
    }

    /**
     * 复选框
     * @param $array 选项 二维数组
     * @param $id 默认选中值，多个用 '逗号'分割
     * @param $str 属性
     * @param $defaultvalue 是否增加默认值 默认值为 -99
     * @param $width 宽度
     */
    public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0, $field = '') {
        $string = '';
        $id = trim($id);
        if ($id != '')
            $id = strpos($id, ',') ? explode(',', $id) : array($id);
        if ($defaultvalue)
            $string .= '<input type="hidden" ' . $str . ' value="-99">';
        $i = 1;
        foreach ($array as $key => $value) {
            $key = trim($key);
            $checked = ($id && in_array($key, $id)) ? 'checked' : '';
            if ($width)
                $string .= '<label class="ib" style="width:' . $width . 'px">';
            $string .= '<input type="checkbox" ' . $str . ' id="' . $field . '_' . $i . '" ' . $checked . ' value="' . htmlspecialchars($key) . '"> ' . htmlspecialchars($value);
            if ($width)
                $string .= '</label>';
            $i++;
        }
        return $string;
    }

    /**
     * 单选框
     * @param $array 选项 二维数组
     * @param $id 默认选中值
     * @param $str 属性
     */
    public static function radio($array = array(), $id = 0, $str = '', $width = 0, $field = '') {
        $string = '';
        foreach ($array as $key => $value) {
            $checked = trim($id) == trim($key) ? 'checked' : '';
            if ($width)
                $string .= '<label class="ib" style="width:' . $width . 'px">';
            $string .= '<input type="radio" ' . $str . ' id="' . $field . '_' . htmlspecialchars($key) . '" ' . $checked . ' value="' . $key . '"> ' . $value;
            if ($width)
                $string .= '</label>';
        }
        return $string;
    }

    /**
     * 模板选择
     * @param $style  风格
     * @param $module 模块
     * @param $id 默认选中值
     * @param $str 属性
     * @param $pre 模板前缀
     */
    public static function select_template($style, $module, $id = '', $str = '', $pre = '') {
        $config = cache("Config");
        if (empty($config['theme'])) {
            $config['theme'] = "Default";
        }
        $filepath = TEMPLATE_PATH . $config['theme'] . "/Content" . DIRECTORY_SEPARATOR;
        $tp_show = str_replace($filepath . "Show" . DIRECTORY_SEPARATOR, "", glob($filepath . "Show" . DIRECTORY_SEPARATOR . 'show*'));
        foreach ($tp_show as $k => $v) {
            $tp_show[$v] = $v;
            unset($tp_show[$k]);
        }
        return self::select($tp_show, $id, $str, "请选择");
    }

    /**
     * url  规则调用
     * @param $module 模块
     * @param $file 文件名
     * @param $ishtml 是否为静态规则
     * @param $id 选中值
     * @param $str 表单属性
     * @param $default_option 默认选项
     */
    public static function urlrule($module, $file, $ishtml, $id, $str = '', $default_option = '') {
        if (!$module)
            $module = 'content';
        $urlrules = cache("Urlrules");
        $array = array();
        foreach ($urlrules as $roleid => $rules) {
            if ($rules['module'] == $module && $rules['file'] == $file && $rules['ishtml'] == $ishtml)
                $array[$roleid] = $rules['example'];
        }

        return self::select($array, $id, $str, $default_option);
    }

}
