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
namespace app\common\model;
use think\Model;
use think\Validate;

class Config extends Model
{

	/**
     * 更新网站配置项
     * @param type $data 数据
     * @param type $type 配置类型 1：系统配置  2：扩展配置
     * @return boolean
     */
    public function saveConfig($data,$type=1)
    {
        if (empty($data) || !is_array($data)) {
            $this->error = '配置数据不能为空！';
            return false;
        }
        foreach ($data as $key => $value) {
            if (empty($key)) {
                continue;
            }
            $saveData = array();
            $saveData["value"] = trim($value);
            if ($this->where(array("name" => $key, 'type' => $type))->update($saveData)=== false) {
                $this->error = "更新到{$key}项失败！";
                return false;
            }
        }
        cache('DB_CONFIG_DATA',null);//清除配置缓存
        return true;
    }

    /**
     * 增加扩展配置项
     * @param type $data
     * @return boolean
     */
    public function extendAdd($data)
    {
        if (empty($data)) {
            $this->error = '数据不能为空！';
            return false;
        }
        if (empty($data['setting']['title'])) {
            $this->error = '名称不能为空！';
            return false;
        }
        $db = db('ConfigField');
        //验证器
        $rule = [
            'fieldname'  => 'require|alphaDash',
            'type' => 'require'
        ];
        $msg = [
            'fieldname.require' => '键名不能为空',
            'fieldname.alphaDash'     => '键名只支持英文、数字、下划线',
            'type'        => '类型不能为空！'
        ];
        $validate = new Validate($rule,$msg);
        if (!$validate->check($data)) {
            $this->error = $validate->getError();
            return false;
        }
        $data['fieldname'] = strtolower($data['fieldname']);
        $data['createtime'] = time();
        if ($this->where(array('name' => $data['fieldname']))->count()) {
                $this->error = '该键名已经存在！';
                return false;
        }
        $setting = $data['setting'];
        if ($data['type'] == 'radio' || $data['type'] == 'select') {
            $option = array();
            $optionList = explode("\n", $setting['option']);
            if (is_array($optionList)) {
                foreach ($optionList as $rs) {
                    $rs = explode('|', $rs);
                    if (!empty($rs)) {
                        $option[] = array(
                            'title' => $rs[0],
                            'value' => $rs[1],
                        );
                    }
                }
                $setting['option'] = $option;
            }
        }
        $data['setting'] = trim(serialize($setting));//序列化或者用array2string函数 数组转字符串
        $id = $db->insert($data);
        if ($id) {
            //增加配置项
            $this->insert(array(
                'name' => $data['fieldname'],
                'title' => $setting['title'],
                'type' => 2,
                'value' => '',
            ));
            cache('DB_CONFIG_DATA',null);//清除配置缓存
            return $id;
        } else {
            $this->error = '添加失败！';
            return false;
        }
    }

    /**
     * 删除扩展配置项
     * @param type $fid 配置项ID
     * @return boolean
     */
    public function extendDel($fid)
    {
        if (empty($fid)) {
            $this->error = '请指定需要删除的扩展配置项！';
            return false;
        }
        $db = db('ConfigField');
        //扩展字段详情
        $info = $db->where(array('fid' => $fid))->find();
        if (empty($info)) {
            $this->error = '该扩展配置项不存在！';
            return false;
        }
        //删除
        if ($this->where(array('name' => $info['fieldname'], 'type' => 2))->delete() !== false) {
            $db->where(array('fid' => $fid))->delete();//删除配置主表和从表
            cache('DB_CONFIG_DATA',null);//清除配置缓存
            return true;
        } else {
            $this->error = '删除失败！';
            return false;
        }
    }

}
