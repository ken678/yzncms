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
// | 表单生成类 https://github.com/LaravelCollective/html
// +----------------------------------------------------------------------

namespace form;

use think\facade\Request;
use think\helper\Arr;

class FormBuilder
{

    /**
     * 我们创建的标签名称数组
     *
     * @var array
     */
    protected $labels = [];

    /**
     * 输入类型
     *
     * @var null
     */
    protected $type = null;

    /**
     * 默认情况下不填充value的输入类型
     *
     * @var array
     */
    protected $skipValueTypes = ['file', 'password', 'checkbox', 'radio'];

    /**
     * 创建一个CSRF令牌生成隐藏字段
     *
     * @param  string  $name
     * @param  string  $type
     *
     * @return string
     */
    public function token($name = '__token__', $type = 'md5')
    {
        $token = Request::token($name, $type);

        return '<input type="hidden" name="' . $name . '" value="' . $token . '" />';
    }

    /**
     * 创建一个表单标签元素。
     *
     * @param         $name
     * @param  null   $value
     * @param  array  $options
     * @param  bool   $escape_html
     *
     * @return string
     */
    public function label($name, $value = null, $options = [], $escape_html = true)
    {
        $this->labels[] = $name;
        $options        = $this->attributes($options);
        $value          = $this->formatLabel($name, $value);
        if ($escape_html) {
            $value = $this->entities($value);
        }

        return '<label for="' . $name . '"' . $options . '>' . $value . '</label>';
    }

    /**
     * 创建一个表单输入字段
     *
     * @param         $type
     * @param         $name
     * @param  null   $value
     * @param  array  $options
     *
     * @return string
     */
    public function input($type, $name, $value = null, $options = [])
    {
        $this->type = $type;
        if (!isset($options['name'])) {
            $options['name'] = $name;
        }

        //我们将得到给定字段的适当值。我们将寻找
        //会话中的值查找旧输入数据中的值，然后我们将查找
        //在模型实例中（如果已设置）。否则我们只会使用空的
        $id = $this->getIdAttribute($name, $options);
        if (!in_array($type, $this->skipValueTypes)) {
            $value = $this->getValueAttribute($name, $value);
        }
        //一旦我们有了类型、值和ID，我们就可以将它们合并到
        //属性数组，以便将它们转换为HTML属性格式
        //创建HTML元素时。然后，我们将返回整个输入。
        $merge   = compact('type', 'value', 'id');
        $options = array_merge($options, $merge);

        return '<input' . $this->attributes($options) . '>';
    }

    /**
     * 创建一个文本输入字段
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function text($name, $value = null, $options = [])
    {
        return $this->input('text', $name, $value, $options);
    }

    /**
     * 创建一个密码输入字段
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return string
     */
    public function password(string $name, array $options = [])
    {
        return $this->input('password', $name, '', $options);
    }

    /**
     * 创建一个范围输入选择器
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function range($name, $value = null, $options = [])
    {
        return $this->input('range', $name, $value, $options);
    }

    /**
     * 创建一个隐藏的输入字段
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function hidden($name, $value = null, $options = [])
    {
        return $this->input('hidden', $name, $value, $options);
    }

    /**
     * 创建一个电子邮件输入字段
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function email($name, $value = null, $options = [])
    {
        return $this->input('email', $name, $value, $options);
    }

    /**
     * 创建一个tel输入字段
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function tel($name, $value = null, $options = [])
    {
        return $this->input('tel', $name, $value, $options);
    }

    /**
     * 创建一个数字输入字段
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function number($name, $value = null, $options = [])
    {
        return $this->input('number', $name, $value, $options);
    }

    /**
     * 创建一个url输入字段
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function url($name, $value = null, $options = [])
    {
        return $this->input('url', $name, $value, $options);
    }

    /**
     * 创建一个textarea输入字段
     *
     * @param  string  $name
     * @param  null    $value
     * @param  array   $options
     *
     * @return string
     */
    public function textarea($name, $value = null, $options = [])
    {
        $this->type = 'textarea';

        if (!isset($options['name'])) {
            $options['name'] = $name;
        }

        //接下来，我们将查找rows和cols属性，因为每个属性都是
        //在textarea元素定义上。如果他们不在场，我们就
        //为开发人员的这些属性假设一些合理的默认值。
        $options       = $this->setTextAreaSize($options);
        $options['id'] = $this->getIdAttribute($name, $options);
        $value         = (string) $this->getValueAttribute($name, $value);

        unset($options['size']);

        //接下来，我们将把属性转换成字符串形式。我们还移除了
        //“大小”属性，因为它只是一条通往行和列的捷径
        //元素。然后我们将为我们创建最终的textarea元素HTML。
        $options = $this->attributes($options);

        return '<textarea' . $options . '>' . $value . '</textarea>';
    }

    /**
     * 创建百度富文本编辑器字段
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    public function ueditor($name, $value = null, $options = [])
    {
        $domname = str_replace(['[', ']', '.'], '', $name);
        if (!isset($options['name'])) {
            $options['name'] = $name;
        }
        if (isset($options['class'])) {
            $options['class'][] = 'js-ueditor';
        } else {
            $options['class'] = 'js-ueditor';
        }
        $value = (string) $this->getValueAttribute($name, $value);

        $options = $this->attributes(array_merge(['id' => "c-{$domname}"], $options));
        return '<script type="text/plain" ' . $options . '>' . $value . '</script>';
    }

    /**
     * 创建一个选择框字段
     *
     * @param         $name
     * @param  array  $list
     * @param  null   $selected
     * @param  array  $selectAttributes
     * @param  array  $optionsAttributes
     * @param  array  $optgroupsAttributes
     *
     * @return string
     */
    public function select(
        $name,
        $list = [],
        $selected = null,
        array $selectAttributes = [],
        array $optionsAttributes = [],
        array $optgroupsAttributes = []
    ) {
        $this->type = 'select';
        //在构建选择框时，“值”属性实际上就是所选的
        //因此，我们将在检查模型或会话时使用该值
        //应该提供一种方便的方法来重新填充post上的表格。
        $selected               = $this->getValueAttribute($name, $selected);
        $selectAttributes['id'] = $this->getIdAttribute($name, $selectAttributes);
        if (!isset($selectAttributes['name'])) {
            $selectAttributes['name'] = $name;
        }
        //我们将简单地遍历这些选项，并为每个选项构建一个HTML值
        //直到我们有一个HTML声明数组。然后我们会加入他们
        //所有这些都整合到一个可以放在表单上的HTML元素中。
        $html = [];

        if (isset($selectAttributes['placeholder'])) {
            $html[] = $this->placeholderOption($selectAttributes['placeholder'], $selected);
            unset($selectAttributes['placeholder']);
        }
        foreach ($list as $value => $display) {
            $optionAttributes   = $optionsAttributes[$value] ?? [];
            $optgroupAttributes = $optgroupsAttributes[$value] ?? [];
            $html[]             = $this->getSelectOption($display, $value, $selected, $optionAttributes, $optgroupAttributes);
        }
        //一旦我们拥有了所有这些HTML，我们就可以在之后将其加入到单个元素中
        //将属性格式化为HTML“attributes”字符串，然后
        //构建一个最终的select语句，它将包含所有的值。
        $selectAttributes = $this->attributes($selectAttributes);
        $list             = implode('', $html);

        return "<select{$selectAttributes}>{$list}</select>";
    }

    /**
     * 创建一个按钮字段
     *
     * @param  string $value
     * @param  array  $options
     *
     * @return string
     */
    public function button($value = null, $options = [])
    {
        if (!array_key_exists('type', $options)) {
            $options['type'] = 'button';
        }

        return '<button' . $this->attributes($options) . '>' . $value . '</button>';
    }

    /**
     * 创建单选按钮输入字段
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $options
     *
     * @return string
     */
    public function radio($name, $value = null, $checked = null, $options = [])
    {
        if (is_null($value)) {
            $value = $name;
        }
        if ($checked) {
            $options['checked'] = 'checked';
        }
        return $this->input('radio', $name, $value, $options);
    }

    /**
     * 创建一组单选框字段
     *
     * @param string $name
     * @param array  $list
     * @param mixed  $checked
     * @param array  $title
     * @param array  $options
     *
     * @return string
     */
    public function radios($name, $list, $checked = null, $title = [], $options = [])
    {
        if (is_array($list)) {
            $html    = [];
            $checked = is_null($checked) ? key($list) : $checked;
            $checked = is_array($checked) ? $checked : explode(',', $checked);
            foreach ($list as $k => $v) {
                $options['id']    = "{$name}-{$k}";
                $options['title'] = $title[$k] ?? $v;
                $html[]           = Form::radio($name, $k, in_array($k, $checked), $options);
            }
            return '<div class="radio-group">' . implode(' ', $html) . '</div>';
        }
        return '';
    }

    /**
     * 创建复选按钮字段
     *
     * @param string $name
     * @param mixed  $value
     * @param bool   $checked
     * @param array  $options
     *
     * @return string
     */
    public function checkbox($name, $value = 1, $checked = null, $options = [])
    {
        if ($checked) {
            $options['checked'] = 'checked';
        }

        return $this->input('checkbox', $name, $value, $options);
    }

    /**
     * 创建一组复选按钮框字段
     *
     * @param string $name
     * @param array  $list
     * @param mixed  $checked
     * @param array  $title
     * @param array  $options
     *
     * @return string
     */
    public function checkboxs($name, $list, $checked, $title = [], $options = [])
    {
        if (is_array($list)) {
            $html    = [];
            $checked = is_null($checked) ? [] : $checked;
            $checked = is_array($checked) ? $checked : explode(',', $checked);
            foreach ($list as $k => $v) {
                $options['id']    = "{$name}-{$k}";
                $options['title'] = $title[$k] ?? $v;
                $html[]           = Form::checkbox("{$name}[{$k}]", $k, in_array($k, $checked), $options);
            }
            return '<div class="checkbox">' . implode(' ', $html) . '</div>';
        }
        return '';
    }

    /**
     * 创建一个上传图片组件(单图)字段
     *
     * @param string $name
     * @param string $value
     * @param array  $inputAttr
     * @param array  $uploadAttr
     * @param array  $chooseAttr
     * @param array  $previewAttr
     *
     * @return string
     */
    public function image($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = [])
    {
        $default = [
            'data-type'     => "image",
            'data-mimetype' => 'image/gif,image/jpeg,image/png,image/jpg,image/bmp',
        ];
        $uploadAttr = is_array($uploadAttr) ? array_merge($default, $uploadAttr) : $uploadAttr;
        $chooseAttr = is_array($chooseAttr) ? array_merge($default, $chooseAttr) : $chooseAttr;
        return $this->uploader($name, $value, $inputAttr, $uploadAttr, $chooseAttr, $previewAttr);
    }

    /**
     * 创建一个上传图片组件(多图)字段
     *
     * @param string $name
     * @param string $value
     * @param array  $inputAttr
     * @param array  $uploadAttr
     * @param array  $chooseAttr
     * @param array  $previewAttr
     *
     * @return string
     */
    public function images($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = [])
    {
        $default = [
            'data-type'     => "image",
            'data-multiple' => 'true',
            'data-mimetype' => 'image/gif,image/jpeg,image/png,image/jpg,image/bmp',
        ];
        $uploadAttr = is_array($uploadAttr) ? array_merge($default, $uploadAttr) : $uploadAttr;
        $chooseAttr = is_array($chooseAttr) ? array_merge($default, $chooseAttr) : $chooseAttr;
        return $this->uploader($name, $value, $inputAttr, $uploadAttr, $chooseAttr, $previewAttr);
    }

    /**
     * 创建上传文件组件(单文件)字段
     *
     * @param string $name
     * @param string $value
     * @param array  $inputAttr
     * @param array  $uploadAttr
     * @param array  $chooseAttr
     * @param array  $previewAttr
     *
     * @return string
     */
    public function upload($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = [])
    {
        $default = [
            'data-type' => "file",
        ];
        $uploadAttr = is_array($uploadAttr) ? array_merge($default, $uploadAttr) : $uploadAttr;
        $chooseAttr = is_array($chooseAttr) ? array_merge($default, $chooseAttr) : $chooseAttr;
        return $this->uploader($name, $value, $inputAttr, $uploadAttr, $chooseAttr, $previewAttr);
    }

    /**
     * 创建上传文件组件(多文件)字段
     *
     * @param string $name
     * @param string $value
     * @param array  $inputAttr
     * @param array  $uploadAttr
     * @param array  $chooseAttr
     * @param array  $previewAttr
     *
     * @return string
     */
    public function uploads($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = [])
    {
        $default = [
            'data-type'     => "file",
            'data-multiple' => 'true',
        ];
        $uploadAttr = is_array($uploadAttr) ? array_merge($default, $uploadAttr) : $uploadAttr;
        $chooseAttr = is_array($chooseAttr) ? array_merge($default, $chooseAttr) : $chooseAttr;
        return $this->uploader($name, $value, $inputAttr, $uploadAttr, $chooseAttr, $previewAttr);
    }

    /**
     * 创建颜色选择字段
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    public function color($name = null, $value = null, $options = [])
    {
        $domname = str_replace(['[', ']', '.'], '', $name);
        $input   = $this->text($name, $value, array_merge(['id' => "c-{$domname}", 'placeholder' => '请选择颜色'], $options));
        $html    = <<<EOD
<div class="layui-input-inline" style="width: 120px;">
    {$input}
</div>
<div class="layui-inline" style="left: -11px;">
    <div class="colorpicker" data-input-id="c-{$domname}"></div>
</div>
EOD;
        return $html;
    }

    /**
     * 创建日期时间选择器字段
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    public function datetime($name = null, $value = null, $options = [])
    {
        $value = is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
        if (isset($options['class'])) {
            $options['class'][] = 'datetime';
        } else {
            $options['class'] = 'datetime';
        }
        return $this->text($name, $value, $options);
    }

    /**
     * 创建动态下拉列表字段
     *
     * @param string $name       名称
     * @param mixed  $value
     * @param string $url        数据源地址
     * @param string $field      显示的字段名称,默认为name
     * @param string $primaryKey 主键,数据库中保存的值,默认为id
     * @param array  $options
     *
     * @return string
     */
    public function selectpage($name, $value, $url, $field = null, $primaryKey = null, $options = [])
    {
        $options = array_merge($options, ['data-source' => $url, 'data-field' => $field ? $field : 'name', 'data-primary-key' => $primaryKey ? $primaryKey : 'id']);
        if (isset($options['class'])) {
            $options['class'][] = 'selectpage';
        } else {
            $options['class'] = 'selectpage';
        }
        return $this->text($name, $value, $options);
    }

    /**
     * 创建动态下拉列表(复选)字段
     *
     * @param string $name       名称
     * @param mixed  $value
     * @param string $url        数据源地址
     * @param string $field      显示的字段名称,默认为name
     * @param string $primaryKey 主键,数据库中保存的值,默认为id
     * @param array  $options
     *
     * @return string
     */
    public function selectpages($name, $value, $url, $field = null, $primaryKey = null, $options = [])
    {
        $options['data-multiple'] = "true";
        return $this->selectpage($name, $value, $url, $field, $primaryKey, $options);
    }

    protected function uploader($name = null, $value = null, $inputAttr = [], $uploadAttr = [], $chooseAttr = [], $previewAttr = [])
    {
        $domname = str_replace(['[', ']', '.'], '', $name);
        $upload  = $uploadAttr === false ? false : true;
        $choose  = $chooseAttr === false ? false : true;
        $preview = $previewAttr === false ? false : true;

        $options = [
            'id'            => "picker_{$domname}",
            'class'         => "webUpload",
            'data-input-id' => "c-{$domname}",
        ];
        if ($preview) {
            $options['data-preview-id'] = "p-{$domname}";
        }
        $uploadBtn = $upload ? $this->button('<i class="layui-icon layui-icon-upload"></i> ' . '上传', array_merge($options, $uploadAttr)) : '';

        $options = [
            'id'            => "fachoose-{$domname}",
            'class'         => "layui-btn fachoose",
            'data-input-id' => "c-{$domname}",
        ];
        if ($preview) {
            $options['data-preview-id'] = "p-{$domname}";
        }
        $chooseBtn = $choose ? $this->button('<i class="iconfont icon-other"></i> ' . '选择', array_merge($options, $chooseAttr)) : '';

        $previewAttrHtml = $this->attributes($previewAttr);
        $previewArea     = $preview ? '<ul class="layui-row list-inline plupload-preview" id="p-' . $domname . '" ' . $previewAttrHtml . '></ul>' : '';
        $input           = $this->text($name, $value, array_merge(['id' => "c-{$domname}", 'class' => 'layui-input'], $inputAttr));
        $html            = <<<EOD
<div class="layui-col-xs4">{$input}</div>
{$uploadBtn}
{$chooseBtn}
{$previewArea}
EOD;
        return $html;
    }

    /**
     * 获取给定值的选择选项
     *
     * @param  string  $display
     * @param  string  $value
     * @param  string  $selected
     * @param  array   $attributes
     * @param  array   $optgroupAttributes
     *
     * @return mixed
     */
    protected function getSelectOption($display, $value, $selected, array $attributes = [], array $optgroupAttributes = [])
    {
        if (is_array($display)) {
            //if (is_iterable($display)) {
            return $this->optionGroup($display, $value, $selected, $optgroupAttributes, $attributes);
        }

        return $this->option($display, $value, $selected, $attributes);
    }

    /**
     * 创建选项组表单元素
     *
     * @param  array    $list
     * @param  string   $label
     * @param  string   $selected
     * @param  array    $attributes
     * @param  array    $optionsAttributes
     * @param  integer  $level
     *
     * @return string
     */
    protected function optionGroup($list, $label, $selected, array $attributes = [], array $optionsAttributes = [], $level = 0)
    {
        $html  = [];
        $space = str_repeat("&nbsp;", $level);
        foreach ($list as $value => $display) {
            $optionAttributes = $optionsAttributes[$value] ?? [];
            if (is_array($display)) {
                //if (is_iterable($display)) {
                $html[] = $this->optionGroup($display, $value, $selected, $attributes, $optionAttributes, $level + 5);
            } else {
                $html[] = $this->option($space . $display, $value, $selected, $optionAttributes);
            }
        }

        return '<optgroup label="' . e($space . $label, false) . '"' . $this->attributes($attributes) . '>' . implode('', $html) . '</optgroup>';
    }

    /**
     * 创建一个选择元素选项
     *
     * @param  string  $display
     * @param  string  $value
     * @param  string  $selected
     * @param  array   $attributes
     *
     * @return mixed
     */
    protected function option($display, $value, $selected, array $attributes = [])
    {
        $selected = $this->getSelectedValue($value, $selected);

        $options = array_merge(['value' => $value, 'selected' => $selected], $attributes);

        $string = '<option' . $this->attributes($options) . '>';
        if ($display !== null) {
            $string .= e($display, false) . '</option>';
        }

        return $string;
    }

    /**
     * 创建占位符选择元素选项
     *
     * @param $display
     * @param $selected
     *
     * @return mixed
     */
    protected function placeholderOption($display, $selected)
    {
        $selected = $this->getSelectedValue(null, $selected);

        $options = [
            'selected' => $selected,
            'value'    => '',
        ];

        return '<option' . $this->attributes($options) . '>' . e($display, false) . '</option>';
    }

    /**
     * 确定是否选择了该值
     *
     * @param  string  $value
     * @param  string  $selected
     *
     * @return null|string
     */
    protected function getSelectedValue($value, $selected)
    {
        if (is_array($selected)) {
            return in_array($value, $selected, true) || in_array((string) $value, $selected, true) ? 'selected' : null;
        }
        if (is_int($value) && is_bool($selected)) {
            return (bool) $value === $selected;
        }

        return ((string) $value === (string) $selected) ? 'selected' : null;
    }

    /**
     * 在属性上设置文本区域大小
     *
     * @param  array  $options
     *
     * @return array
     */
    protected function setTextAreaSize($options)
    {
        if (isset($options['size'])) {
            return $this->setQuickTextAreaSize($options);
        }

        //如果没有指定“size”属性，我们将只查找常规
        //列和行属性，如果这些属性在
        //属性数组。然后我们将返回整个选项数组。
        $cols = Arr::get($options, 'cols', 50);
        $rows = Arr::get($options, 'rows', 10);

        return array_merge($options, compact('cols', 'rows'));
    }

    /**
     * 使用快速“大小”属性设置文本区域大小
     *
     * @param  array  $options
     *
     * @return array
     */
    protected function setQuickTextAreaSize($options)
    {
        $segments = explode('x', $options['size']);

        return array_merge($options, ['cols' => $segments[0], 'rows' => $segments[1]]);
    }

    /**
     * 获取应分配给字段的值
     *
     * @param  string  $name
     * @param  string  $value
     *
     * @return mixed
     */
    protected function getValueAttribute($name, $value = null)
    {
        if (is_null($name)) {
            return $value;
        }

        if (!is_null($value)) {
            return $value;
        }
    }

    /**
     * 将HTML字符串转换为实体
     *
     * @param  string  $value
     *
     * @return string
     */
    protected function entities($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * 获取字段名的ID属性
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return string
     */
    protected function getIdAttribute($name, $attributes)
    {
        if (array_key_exists('id', $attributes)) {
            return $attributes['id'];
        }

        if (in_array($name, $this->labels)) {
            return $name;
        }
    }

    /**
     * 设置标签值的格式。
     *
     * @param  string       $name
     * @param  string|null  $value
     *
     * @return string
     */
    protected function formatLabel($name, $value)
    {
        return $value ?: ucwords(str_replace('_', ' ', $name));
    }

    /**
     * 从数组生成HTML属性字符串
     *
     * @param  array  $attributes
     *
     * @return string
     */
    protected function attributes($attributes)
    {
        $html = [];
        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if (!is_null($element)) {
                $html[] = $element;
            }
        }

        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }

    /**
     * 构建单个属性元素
     *
     * @param  string  $key
     * @param  string  $value
     *
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        //[required]之类的HTML属性转换为正确的形式，而不是使用错误的数字。
        if (is_numeric($key)) {
            return $value;
        }
        // 将布尔属性视为HTML属性
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }
        //多个class属性
        if (is_array($value) && $key === 'class') {
            return 'class="' . implode(' ', $value) . '"';
        }
        if (!is_null($value)) {
            return $key . '="' . e($value, false) . '"';
        }
    }
}

if (!function_exists('e')) {
    /**
     * 将HTML特殊字符编码为字符串
     *
     * @param  string  $value
     * @param  bool    $doubleEncode
     *
     * @return string
     */
    function e($value, $doubleEncode = true)
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}
