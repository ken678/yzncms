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

namespace app\common\logic;

use think\Db;
use think\Model;
use think\Request;
use think\Validate;

/**
 * 文档基础模型
 */
class Base extends Model
{
    protected $catid = 0;
    protected $modelid = 0;
    protected $FormData = array();

    public function __construct($name = '')
    {
        if (!empty($name) && !empty($name['table_name']) && count($name) == 1) {
            $this->name = $name['table_name'];
            parent::__construct();
        } else {
            parent::__construct($name);
        }
    }

    public function initialize()
    {
        $data = Request::instance()->param();
        $data = $data['info'];
        if (empty($data['id'])) {
            unset($data['id']);
        }

        $this->FormData = $data;
        parent::initialize();
    }

    public function add($data = null)
    {
        //自动验证及自动完成
        /*if(!$check = $this->checkModelAttr()){
        return false;
        };*/
        //表单数据
        $data = $this->FormData;
        //栏目ID
        $this->catid = (int) $data['catid'];
        //模型ID
        $this->modelid = getCategory($this->catid, 'modelid');
        //自动提取摘要，如果有设置自动提取，且description为空，且有内容字段才执行
        $this->description($data);
        $data['status'] = 99;
        //保存一份旧数据
        $oldata = $data;
        //对数据进行入库前的处理 STAT
        $content_input = new \content_input($this->modelid);
        $inputinfo = $content_input->get($data, 1);
        //对数据进行入库前的处理 END
        $systeminfo = $inputinfo['system']; //主表
        $modelinfo = $inputinfo['model']; //附表

        //检查真实发表时间，如果有时间转换为时间戳
        if ($data['inputtime'] && !is_numeric($data['inputtime'])) {
            $systeminfo['inputtime'] = strtotime($data['inputtime']);
        } elseif (!$data['inputtime']) {
            $systeminfo['inputtime'] = time();
        } else {
            $systeminfo['inputtime'] = $data['inputtime'];
        }

        //更新时间处理
        if ($data['updatetime'] && !is_numeric($data['updatetime'])) {
            $systeminfo['updatetime'] = strtotime($data['updatetime']);
        } elseif (!$data['updatetime']) {
            $systeminfo['updatetime'] = time();
        } else {
            $systeminfo['updatetime'] = $data['updatetime'];
        }
        if (!defined('IN_ADMIN') || (defined('IN_ADMIN') && IN_ADMIN == false)) {
            $systeminfo['sysadd'] = 0;
        } else {
            $systeminfo['sysadd'] = 1;
        }
        if ($rs = $this->data($systeminfo)->allowField(true)->save()) {
            $modelinfo['id'] = $data['id'] = $this->id;
            if (false === Db::name($this->name . '_data')->insert($modelinfo)) {
                //删除已添加的主表内容
                $this->delete($systeminfo['id']);
                $this->error = '新增附表内容出错';
                return false;
            }
            //调用回调更新
            $content_update = new \content_update($this->modelid);
            $content_update->update($oldata);
            //添加统计
            $hitsid = 'c-' . $this->modelid . '-' . $this->id;
            Db::name('Hits')->insert(array('hitsid' => $hitsid, 'catid' => $this->catid, 'updatetime' => time()));
            $urls = array();
            if ($data['islink'] == 1) {
                $urls['url'] = $data['linkurl'];
            } else {
                //生成该篇地址
                $urls = $this->generateUrl($data);
            }
            $data['url'] = $urls['url'];
            //更新url
            $this->save(array('url' => $data['url']), ['id' => $modelinfo['id']]);
            return true;
        } else {
            $this->error = '新增基础内容出错';
            return false;
        }
    }

    public function edit($data = null)
    {
        //表单数据
        $data = $this->FormData;
        if (empty($data)) {
            if (!empty($this->data)) {
                $data = $this->data;
                // 重置数据
                $this->data = array();
            } else {
                $this->error = '数据不存在';
                return false;
            }
        }
        $this->id = (int) $data['id'];
        $this->catid = (int) $data['catid'];
        $this->modelid = getCategory($this->catid, 'modelid');
        //栏目数据
        $catidinfo = getCategory($this->catid);
        if (empty($catidinfo)) {
            $this->error = '获取不到栏目数据！';
            return false;
        }
        //setting配置
        $catidsetting = $catidinfo['setting'];
        //真实发布时间
        $data['inputtime'] = $inputtime = $this->where(array("id" => $this->id))->column('inputtime');
        //更新时间处理
        if ($data['updatetime'] && !is_numeric($data['updatetime'])) {
            $data['updatetime'] = strtotime($data['updatetime']);
        } elseif (!$data['updatetime']) {
            $data['updatetime'] = time();
        }
        //自动提取摘要，如果有设置自动提取，且description为空，且有内容字段才执行
        $this->description($data);
        //转向地址
        if ($data['islink'] == 1) {
            $urls["url"] = $data['linkurl'];
        } else {
            //生成该篇地址
            $urls = $this->generateUrl($data);
        }
        $data['url'] = $urls["url"];
        //保存一份旧数据
        $oldata = $data;
        $content_input = new \content_input($this->modelid);
        $inputinfo = $content_input->get($data, 2);
        //对数据进行入库前的处理 END
        $systeminfo = $inputinfo['system']; //主表
        $modelinfo = $inputinfo['model']; //附表
        if ($rs = $this->allowField(true)->save($systeminfo, ['id' => $this->id])) {
            if (false === Db::name($this->name . '_data')->update($modelinfo)) {
                //删除已添加的主表内容
                $this->delete($data['id']);
                $this->error = '新增附表内容出错';
                return false;
            }
            //调用回调更新
            $content_update = new \content_update($this->modelid);
            $content_update->update($oldata);
            return true;

        } else {
            $this->error = $this->getError();
            return false;
        }

    }

    /**
     * 删除文档
     * @param  [type] $id 文档ID
     * @param  [type] $catid 栏目ID
     * @return
     */
    public function rmove($id, $catid)
    {
        if (empty($id) || empty($catid)) {
            $this->error = '参数错误！';
            return false;
        }
        if ($rs = $this->where(['id' => $id])->delete()) {
            if (false === Db::name($this->name . '_data')->delete($id)) {
                $this->error = '删除附表内容出错';
                return false;
            }
        } else {
            $this->error = '删除基础内容出错';
            return false;
        }

    }

    /**
     * 检测属性的自动验证和自动完成属性 并进行验证
     * 验证场景  insert和update二个个场景，可以分别在新增和编辑
     * @return boolean
     */
    public function checkModelAttr($model_id = false, $data = false)
    {
        if (!$data) {
            $data = $this->FormData; //获取数据
        }

        $scene = 'insert'; //验证场景
        $validate_module = Validate::make([['keywords', 'require|date']]);
        $validate_module->scene($scene);
        if (!$validate_module->check($data)) {
            $this->error = $validate_module->getError();
            return false;
        }
        return true;
    }

    /**
     * 获取URL规则处理后的
     * @param type $data
     * @return type
     */
    protected function generateUrl($data)
    {
        $this->Url = new \util\Url;
        return $this->Url->show($data);
    }

    /**
     * 自动获取简介
     * @param type $data
     */
    protected function description(&$data)
    {
        //自动提取摘要，如果有设置自动提取，且description为空，且有内容字段才执行
        if (isset($_POST['add_introduce']) && $data['description'] == '' && isset($data['content'])) {
            $content = $data['content'];
            $introcude_length = intval($_POST['introcude_length']);
            $data['description'] = str_cut(str_replace(array("\r\n", "\t", '[page]', '[/page]', '&ldquo;', '&rdquo;', '&nbsp;'), '', strip_tags($content)), $introcude_length);
        }
    }

}
