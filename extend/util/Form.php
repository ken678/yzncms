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
/**
 * 表单构建器
 */
class Form {
    /**
     * 百度编辑器
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
         //编辑器类型 图标工具栏标准和简洁
        if ($toolbar == 'basic') {
            $toolbar = "['FullScreen', 'Source', '|', 'Undo', 'Redo', '|','FontSize','Bold', 'forecolor', 'Italic', 'Underline', 'Link',  '|',  'InsertImage',
                 'ClearDoc',  'CheckImage','Emotion',  " . ($allowupload && $allowbrowser ? "'attachment'," : "") . " 'PageBreak','insertcode', 'WordImage','RemoveFormat', 'FormatMatch','AutoTypeSet']
                ";
        } elseif ($toolbar == 'full') {
            $toolbar = "['fullscreen', 'source', '|', 'undo', 'redo', '|','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|','rowspacingtop', 'rowspacingbottom', 'lineheight', '|','customstyle', 'paragraph', 'fontfamily', 'fontsize', '|','directionalityltr', 'directionalityrtl', 'indent', '|','justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|','link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|','simpleupload', 'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|','horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|','inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|','print', 'preview', 'searchreplace', 'drafts', 'help']";
        } else{
            $toolbar = "[]";
        }
        $str .= "\r\n<script type=\"text/javascript\">\r\n";
        $str .= "var ue_{$textareaid} = UE.getEditor('{$textareaid}',{initialFrameHeight:$height,autoHeightEnabled:false,toolbars:[$toolbar]});";
        $str .= "ue_{$textareaid}.ready(function() {
                       ue_{$textareaid}.execCommand('serverparam', {
                          'catid': '{$catid}'
                       });
                });";
        $str .= "\r\n</script>";
        return $str;
    }
}
