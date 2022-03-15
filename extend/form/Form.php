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
// | 表单生成门面类 https://github.com/LaravelCollective/html
// +----------------------------------------------------------------------

namespace form;

use think\Facade;

/**
 * @see \form\FormBuilder
 * @method string token($name = '__token__', $type = 'md5') static 创建一个CSRF令牌生成隐藏字段
 * @method string label($name, $value = null, $options = [], $escape_html = true) static 创建一个表单标签元素
 * @method string input($type, $name, $value = null, $options = []) static 创建一个表单输入字段
 * @method string text($name, $value = null, $options = []) static 创建一个文本输入字段
 * @method string password(string $name, array $options = []) static 创建一个密码输入字段
 * @method string range($name, $value = null, $options = []) static 创建一个范围输入选择器
 * @method string hidden($name, $value = null, $options = []) static 创建一个隐藏的输入字段
 * @method string email($name, $value = null, $options = []) static 创建一个电子邮件输入字段
 * @method string tel($name, $value = null, $options = []) static 创建一个tel输入字段
 * @method string number($name, $value = null, $options = []) static 创建一个数字输入字段
 * @method string url($name, $value = null, $options = []) static 创建一个url输入字段
 * @method string textarea($name, $value = null, $options = []) static 创建一个textarea输入字段
 * @method string select($name,$list = [],$selected = null,array $selectAttributes = [],array $optionsAttributes = [],array $optgroupsAttributes = []) static 创建一个选择框字段
 * @method string button($value = null, $options = []) static 创建一个按钮字段
 * @method string image($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = []) static 创建一个上传图片组件(单图)字段
 * @method string images($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = []) static 创建一个上传图片组件(多图)字段
 * @method string radio($name, $value = null, $checked = null, $options = []) static 创建单选按钮输入字段
 * @method string radios($name, $list, $checked = null, $title = [], $options = []) static 创建一组单选框字段
 * @method string checkbox($name, $value = 1, $checked = null, $options = []) static 创建复选按钮字段
 * @method string checkboxs($name, $list, $checked, $title = [], $options = []) static 创建一组复选按钮框字段
 * @method string upload($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = []) static 创建上传文件组件(单文件)字段
 * @method string uploads($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = []) static 创建上传文件组件(多文件)字段
 * @method string color($name = null, $value = null, $options = []) static 创建颜色选择字段
 * @method string datetime($name = null, $value = null, $options = []) static 创建日期时间选择器字段
 * @method string ueditor($name, $value = null, $options = []) static 创建百度富文本编辑器字段
 * @method string selectpage($name, $value, $url, $field = null, $primaryKey = null, $options = []) static 创建动态下拉列表字段
 * @method string selectpages($name, $value, $url, $field = null, $primaryKey = null, $options = []) static 创建动态下拉列表(复选)字段
 */
class Form extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'form\FormBuilder';
    }
}
