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

class Config extends Model{
	/**
     * 更新网站配置项
     * @param type $data 数据
     * @return boolean
     */
    public function saveConfig($data) {
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
            if ($this->where(["name" => $key])->update($saveData)=== false) {
                $this->error = "更新到{$key}项失败！";
                return false;
            }
        }
        cache('DB_CONFIG_DATA',null);//清除配置缓存
        return true;
    }

    /**
     * 更新扩展配置项
     * @param type $data 数据
     * @return boolean
     */
    public function saveExtendConfig($data) {
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
            if ($this->where(array("name" => $key, 'type' => 2))->update($saveData) === false) {
                $this->error = "更新到{$key}项时，更新失败！";
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
    public function extendAdd($data) {
        if (empty($data)) {
            $this->error = '数据不能为空！';
            return false;
        }
        if (empty($data['setting']['title'])) {
            $this->error = '名称不能为空！';
            return false;
        }
        $db = db('ConfigField');
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
        $data['setting'] = serialize($setting);//序列化
        $id = $db->insert($data);
        if ($id) {
            //增加配置项
            $this->insert(array(
                'name' => $data['fieldname'],
                'title' => $setting['title'],
                'type' => 2,
                'value' => '',
            ));
            return $id;
        } else {
            $this->error = '添加失败！';
            return false;
        }
    }

}
