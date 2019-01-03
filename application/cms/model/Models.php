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
// | 模型模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use app\common\model\Modelbase;
use think\Db;
use think\facade\Config;
use \think\Model;

/**
 * 模型
 */
class Models extends Modelbase
{

    protected $name = 'model';
    protected $ext_table = '_data';
    protected $autoWriteTimestamp = true;
    protected $insert = ['status' => 1];

    /**
     * 创建模型
     * @param type $data 提交数据
     * @return boolean
     */
    public function addModel($data)
    {
        if (empty($data)) {
            return false;
        }
        $data['ifsub'] = isset($data['ifsub']) ? $data['ifsub'] : 0;
        //创建模型表和模型附表
        if ($this->createTable($data)) {
            cache("Model", null);
            //添加模型记录
            if (self::allowField(true)->save($data)) {
                return $this->addFieldRecord($this->getAttr('id'), $data['type']);
            }
        }
        return false;
    }

    /**
     * 编辑模型
     * @param type $data 提交数据
     * @return boolean
     */
    public function editModel($data, $modelid = 0)
    {
        if (empty($data)) {
            return false;
        }
        //模型ID
        $modelid = $modelid ? $modelid : (int) $data['id'];
        if (!$modelid) {
            $this->error = '模型ID不能为空！';
            return false;
        }
        //查询模型数据
        $info = self::where(array("id" => $modelid))->find();
        if (empty($info)) {
            $this->error = '该模型不存在！';
            return false;
        }
        $data['modelid'] = $modelid;
        $data['ifsub'] = isset($data['ifsub']) ? $data['ifsub'] : 0;
        //模型添加验证
        /*$validate = Loader::validate('Models');
        if (!$validate->scene('edit')->check($data)) {
        $this->error = $validate->getError();
        return false;
        }*/
        //是否更改表名
        if ($info['tablename'] != $data['tablename'] && !empty($data['tablename'])) {
            //检查新表名是否存在
            if ($this->table_exists($data['tablename']) || $this->table_exists($data['tablename'] . '_data')) {
                $this->error = '该表名已经存在！';
                return false;
            }
            if (false !== $this->allowField(true)->save($data, array("modelid" => $modelid))) {
                //表前缀
                $dbPrefix = Config::get("database.prefix");
                //表名更改
                Db::execute("RENAME TABLE  `{$dbPrefix}{$info['tablename']}` TO  `{$dbPrefix}{$data['tablename']}` ;");
                //修改副表
                if ($info['type'] == 2) {
                    Db::execute("RENAME TABLE  `{$dbPrefix}{$info['tablename']}_data` TO  `{$dbPrefix}{$data['tablename']}_data` ;");
                }
                //更新缓存
                cache("Model", null);
                return true;
            } else {
                $this->error = '模型更新失败！';
                return false;
            }
        } else {
            if (false !== self::allowField(true)->save($data, array("modelid" => $modelid))) {
                //更新缓存
                cache("Model", null);
                return true;
            } else {
                $this->error = '模型更新失败！';
                return false;
            }
        }

    }

    /**
     * 根据模型ID删除模型
     * @param type $id 模型id
     * @return boolean
     */
    public function deleteModel($id)
    {
        if (empty($id)) {
            return false;
        }
        $modeldata = self::where(array("id" => $id))->find();
        if (!$modeldata) {
            return false;
        }
        //表名
        $model_table = $modeldata['tablename'];
        //删除模型数据
        self::destroy($id);
        //更新缓存
        cache("Model", null);
        //删除所有和这个模型相关的字段
        Db::name("ModelField")->where(array("modelid" => $id))->delete();
        //删除主表
        $this->deleteTable($model_table);
        if ((int) $modeldata['type'] == 2) {
            //删除副表
            $this->deleteTable($model_table . "_data");
        }
        return true;
    }

    /**
     * 创建内容模型
     */
    protected function createTable($data)
    {
        $data['tablename'] = strtolower($data['tablename']);
        $table = Config::get("database.prefix") . $data['tablename'];
        if ($this->table_exists($data['tablename'])) {
            $this->error = '创建失败！' . $table . '表已经存在~';
            return false;
        }
        $sql = <<<EOF
				CREATE TABLE `{$table}` (
				`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
				`title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
				`keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
				`tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
				`description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
				`posid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
				`listorder` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
				`uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
                `hits` mediumint(8) UNSIGNED DEFAULT 0 COMMENT '点击量' ,
				`inputtime` int(11) unsigned NOT NULL DEFAULT '0'  COMMENT '创建时间',
				`updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
                `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='{$data['name']}模型表';
EOF;
        try {
            $res = Db::execute($sql);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        if ($data['type'] == 2) {
            // 新建附属表
            $sql = <<<EOF
				CREATE TABLE `{$table}{$this->ext_table}` (
				`did` mediumint(8) unsigned NOT NULL DEFAULT '0',
				`content` text COLLATE utf8_unicode_ci COMMENT '内容',
				PRIMARY KEY (`did`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='{$data['name']}模型表';
EOF;
            try {
                $res = Db::execute($sql);
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
                return false;
            }
        }
        return true;
    }

    /**
     * 添加默认字段
     */
    protected function addFieldRecord($modelid, $type)
    {
        $default = [
            'modelid' => $modelid,
            'create_time' => request()->time(),
            'update_time' => request()->time(),
            'ifsystem' => 1,
            'status' => 1,
            'listorder' => 100,
            'iffixed' => 1,
        ];
        $data = [
            [
                'name' => 'id',
                'title' => '文档id',
                'define' => 'mediumint(8) UNSIGNED',
                'type' => 'hidden',
                'ifeditable' => 1,
            ],
            [
                'name' => 'title',
                'title' => '标题',
                'define' => 'varchar(255)',
                'type' => 'text',
                'ifsearch' => 1,
                'ifeditable' => 1,
                'ifrequire' => 1,
                'iffixed' => 0,
            ],
            [
                'name' => 'keywords',
                'title' => 'SEO关键词',
                'define' => 'varchar(255)',
                'type' => 'tags',
                'jsonrule' => '{"string":{"table":"tag","key":"title","delimiter":",","where":"","limit":"6","order":"[rand]"}}',
                'ifeditable' => 1,
                'iffixed' => 0,
            ],
            [
                'name' => 'description',
                'title' => 'SEO摘要',
                'define' => 'varchar(255)',
                'type' => 'number',
                'ifeditable' => 0,
                'iffixed' => 0,
            ],
            [
                'name' => 'uid',
                'title' => '用户id',
                'define' => 'mediumint(8) UNSIGNED',
                'type' => 'number',
                'ifeditable' => 0,
                'value' => 1,
            ],
            [
                'name' => 'posid',
                'title' => '推荐位',
                'define' => 'tinyint(3) tinyint(3)',
                'type' => 'number',
                'ifeditable' => 0,
            ],
            [
                'name' => 'listorder',
                'title' => '排序',
                'define' => 'tinyint(3) UNSIGNED',
                'type' => 'number',
                'ifeditable' => 1,
                'value' => 100,
            ],
            [
                'name' => 'status',
                'title' => '状态',
                'define' => 'tinyint(1)',
                'type' => 'radio',
                'ifeditable' => 1,
                'value' => 1,
                'options' => '0:禁用
1:启用',
            ],
            [
                'name' => 'inputtime',
                'title' => '创建时间',
                'define' => 'int(11) UNSIGNED',
                'type' => 'datetime',
                'value' => 0,
                'ifeditable' => 1,
                'listorder' => 200,
            ],
            [
                'name' => 'updatetime',
                'title' => '更新时间',
                'define' => 'int(11) UNSIGNED',
                'type' => 'datetime',
                'ifeditable' => 0,
                'value' => 0,
                'listorder' => 200,
            ],
            [
                'name' => 'hits',
                'title' => '点击量',
                'define' => 'mediumint(8) UNSIGNED',
                'type' => 'number',
                'ifeditable' => 1,
                'value' => 0,
                'listorder' => 200,
            ],
        ];
        if ($type == 2) {
            array_push($data, [
                'name' => 'did',
                'title' => '附表文档id',
                'define' => 'mediumint(8) UNSIGNED',
                'type' => 'hidden',
                'ifeditable' => 0,
                'ifsystem' => 0,
            ],
                [
                    'name' => 'content',
                    'title' => '内容',
                    'define' => 'text',
                    'type' => 'Ueditor',
                    'ifsystem' => 0,
                    'ifeditable' => 1,
                    'iffixed' => 0,
                ]);

        }
        foreach ($data as $item) {
            $item = array_merge($default, $item);
            Db::name('model_field')->insert($item);
        }
        return true;

    }

    /**
     * 删除表
     * $table 不带表前缀
     */
    public function deleteTable($table)
    {
        if ($this->table_exists($table)) {
            $this->drop_table($table);
        }
        return true;
    }

    /**
     * 根据模型类型取得数据用于缓存
     * @param type $type
     * @return type
     */
    public function getModelAll($type = null)
    {
        $where = array('status' => 1);
        if (!is_null($type)) {
            $where['type'] = $type;
        }
        $data = Db::name('model')->where($where)->select();
        $Cache = array();
        foreach ($data as $v) {
            $Cache[$v['id']] = $v;
        }
        return $Cache;
    }

    /**
     * 生成模型缓存，以模型ID为下标的数组
     * @return boolean
     */
    public function model_cache()
    {
        $data = $this->getModelAll();
        cache('Model', $data);
        return $data;
    }

}
