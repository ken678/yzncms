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
namespace app\content\validate;

use think\Validate;

/**
 * 模型验证
 */
class ModelField extends Validate
{
    protected $rule = [
        'fieldid' => 'require|number',
        'modelid' => 'require|number',
        'formtype' => 'require|alpha',
        'field' => 'require|unique:ModelField|alphaDash',
        'name' => 'require',
        'isbase' => 'in:0,1',

    ];

    protected $message = [
        'fieldid.require' => '请选择字段！',
        'fieldid.number' => '字段选择错误！',
        'modelid.require' => '请选择模型！',
        'modelid.number' => '模型格式错误',
        'formtype.require' => '字段类型不能为空！',
        'formtype.alpha' => '字段类型错误',
        'field.require' => '字段名不能为空！',
        'field.unique' => '字段名已存在！',
        'field.alphaDash' => '字段名格式错误！',
        'name.require' => '字段别名不能为空！',
        'isbase.in' => '是否作为基本信息设置错误！',
    ];

    protected $scene = [
        'add' => ['modelid', 'formtype', 'field', 'name', 'isbase'],
        'edit' => ['fieldid', 'modelid', 'name'],
    ];

}
