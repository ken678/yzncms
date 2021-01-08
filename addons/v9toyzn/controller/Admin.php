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
// | 数据转换管理
// +----------------------------------------------------------------------
namespace addons\v9toyzn\Controller;

use app\addons\util\Adminaddon;
use app\admin\service\User as admin_user;
use think\Db;
use think\facade\Cache;

class Admin extends Adminaddon
{
    //phpcms固定相关表
    private $v9modelTabList = array(
        'model',
        'model_field',
        'category',
        'page',
        'attachment',
    );
    private $v9fieldList = array(
        'catid',
        'typeid',
        'title',
        'thumb',
        'attachment',
        'keywords',
        'description',
        'updatetime',
        'content',
        'voteid',
        'pages',
        'inputtime',
        'posids',
        'url',
        'listorder',
        'status',
        'template',
        'groupids_view',
        'readpoint',
        'relation',
        'allow_comment',
        'copyfrom',
        'username',
        'downfiles',
        'downfile',
        'systems',
        'copytype',
        'language',
        'classtype',
        'version',
        'filesize',
        'islink',
        'stars',
        'pictureurls',
        'video_category',
        'video',
        'vision',
    );
    //yzncms固定相关表
    private $modelTabList = array(
        'model',
        'model_field',
        'category',
        'page',
        'attachment',
    );
    private $ext_table = '_data';

    //初始化
    public function init()
    {
        $data = $this->request->post();
        if (empty($data['hostname']) || empty($data['database']) || empty($data['username']) || empty($data['password']) || empty($data['prefix'])) {
            $this->error('数据库配置不得为空！');
        }
        $db_config = [
            'type'     => 'mysql',
            'hostname' => $data['hostname'],
            'database' => $data['database'],
            'username' => $data['username'],
            'password' => $data['password'],
            'charset'  => 'utf8',
            'prefix'   => $data['prefix'],
        ];
        Cache::set('db_config', $db_config, 3600);
        $db_task = [
            ['fun' => 'step1', 'msg' => '数据表清空完毕!'],
            ['fun' => 'step2', 'msg' => '附件表转换完毕!'],
            ['fun' => 'step3', 'msg' => '模型表转换完毕!'],
            ['fun' => 'step4', 'msg' => '模型字段表转换完毕!'],
            ['fun' => 'step5', 'msg' => '栏目转换完毕!'],
            ['fun' => 'step6', 'msg' => '内容页转换完毕!'],
            ['fun' => 'step7', 'msg' => '单页转换完毕!'],
        ];

        //检查phpcms表是否正常
        $db2 = Db::connect($db_config);
        foreach ($this->v9modelTabList as $tablename) {
            $res = $db2->query("SHOW TABLES LIKE '{$db_config['prefix']}{$tablename}'");
            if (!$res) {
                $this->error("表{$db_config['prefix']}{$tablename}不存在,请检查phpcms表结构是否正常！");
            }
            if ('model' == $tablename) {
                $modelTable = $db2->table($db_config['prefix'] . $tablename)->where('type', 0)->select();
                foreach ($modelTable as $k => $v) {
                    $res = $db2->query("SHOW TABLES LIKE '{$db_config['prefix']}{$v['tablename']}'");
                    if (!$res) {
                        $this->error("表{$db_config['prefix']}{$tablename}不存在,请检查phpcms表结构是否正常！");
                    }
                    $v9table[] = $v['tablename'];
                }
            }
        }
        //检查是否存在友情链接
        $res = $db2->query("SHOW TABLES LIKE '{$db_config['prefix']}link'");
        if ($res && isModuleInstall('links')) {
            $db_task[] = ['fun' => 'step8', 'msg' => '友情链接转换完毕!'];
        }
        Cache::set('db_task', $db_task, 3600);
        Cache::set('v9_table', $v9table, 3600);
        $this->success('phpcms数据表结构检查完毕！');
    }

    //任务执行
    public function start()
    {
        $page    = $this->request->param('page/d', 0);
        $db_task = Cache::get('db_task');
        if (isset($db_task[$page])) {
            $fun = $db_task[$page]['fun'];
            $this->$fun();
            $this->success($db_task[$page]['msg'], null, ['code' => 1, 'page' => $page + 1]);
        } else {
            $this->success('所有数据转换完毕', null, ['code' => 2]);
        }
    }

    public function step1()
    {
        //清空yzncms的表
        $yznprefix  = config('database.prefix');
        $table_list = Db::name('model')->where('module', 'cms')->field('tablename,type,id')->select();
        if ($table_list) {
            foreach ($table_list as $val) {
                $tablename = $yznprefix . $val['tablename'];
                Db::execute("DROP TABLE IF EXISTS `{$tablename}`;");
                if ($val['type'] == 2) {
                    Db::execute("DROP TABLE IF EXISTS `{$tablename}{$this->ext_table}`;");
                }
                Db::name('model_field')->where(['modelid' => $val['id']])->delete();
            }
        }
        if (isModuleInstall('links')) {
            Db::execute("TRUNCATE `{$yznprefix}terms`;");
            Db::execute("TRUNCATE `{$yznprefix}links`;");
        }
        //删除模型中的表
        Db::name('model')->where('module', 'cms')->delete();
        foreach ($this->modelTabList as $tablename) {
            if (!empty($tablename)) {
                $tablename = $yznprefix . $tablename;
                Db::execute("TRUNCATE `{$tablename}`;");
            }
        }
        Db::execute("TRUNCATE `{$yznprefix}attachment`;");
        cache('Model', null);
    }

    public function step2()
    {
        $db_config = Cache::get('db_config');
        $admin_id  = admin_user::instance()->id;
        $finfo     = finfo_open(FILEINFO_MIME_TYPE);
        Db::connect($db_config)->name('attachment')->chunk(100, function ($cursor) use ($admin_id, $finfo) {
            foreach ($cursor as $key => $value) {
                $path     = ROOT_PATH . 'public' . str_replace("/", DS, '/uploads/images/' . $value['filepath']);
                $is_exist = file_exists($path) ? true : false;
                $data[]   = [
                    'id'          => $value['aid'],
                    'aid'         => $admin_id,
                    'uid'         => 0,
                    'name'        => $value['filename'],
                    'path'        => '/uploads/images/' . $value['filepath'],
                    'module'      => 'cms',
                    'md5'         => $is_exist ? hash_file('md5', $path) : '',
                    'sha1'        => $is_exist ? hash_file('sha1', $path) : '',
                    'size'        => $value['filesize'],
                    'ext'         => $value['fileext'],
                    'mime'        => $is_exist ? finfo_file($finfo, $path) : '',
                    'create_time' => $value['uploadtime'],
                    'update_time' => $value['uploadtime'],
                    'status'      => 1,
                ];
            };
            Db::name('attachment')->insertAll($data);
        });
    }

    public function step3()
    {
        $db_config  = Cache::get('db_config');
        $res        = Db::connect($db_config)->name('model')->where('type', 0)->select();
        $modelClass = new \app\cms\model\Models;
        $data       = [];
        foreach ($res as $key => $value) {
            $data['id']          = $value['modelid'];
            $data['name']        = $value['name'];
            $data['description'] = $value['description'];
            $data['tablename']   = $value['tablename'];
            $data['listorders']  = $value['sort'];
            $data['type']        = 2;
            $data['status']      = $value['disabled'] ? 0 : 1;
            $data['setting']     = array(
                'category_template' => $value['category_template'] . '.html',
                'list_template]'    => $value['list_template'] . '.html',
                'show_template'     => $value['show_template'] . '.html',
            );
            $result = $this->validate($data, 'app\cms\validate\Models');
            if (true !== $result) {
                $this->error($result);
            }
            try {
                $modelClass->addModel($data);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
        unset($res);
    }

    public function step4()
    {
        $db_config  = Cache::get('db_config');
        $cursor     = Db::connect($db_config)->name('model_field')->cursor();
        $ids        = Db::connect($db_config)->name('model')->where('type', 0)->column('modelid');
        $modelClass = new \app\cms\model\ModelField;
        $v9fields   = $data   = [];
        foreach ($cursor as $key => $value) {
            if (!in_array($value['field'], $this->v9fieldList) && in_array($value['modelid'], $ids) && in_array($value['formtype'], ['text', 'textarea', 'image', 'editor'])) {
                switch ($value['formtype']) {
                    case 'text':
                    case 'textarea':
                        $define = "varchar(255) NOT NULL";
                        $type   = 'text';
                        $val    = "";
                        break;
                    case 'image':
                        $define = "int(5) UNSIGNED NOT NULL";
                        $type   = 'image';
                        $val    = "";
                        break;
                    case 'editor':
                        $define = "text NOT NULL";
                        $type   = 'Ueditor';
                        $val    = "";
                        break;
                }
                $data = [
                    'modelid'    => $value['modelid'],
                    'name'       => $value['field'],
                    'title'      => $value['name'],
                    'ifsystem'   => $value['issystem'],
                    'listordert' => $value['listorder'],
                    'isadd'      => $value['isadd'],
                    'ifsearch'   => $value['issearch'],
                    'ifcore'     => $value['iscore'],
                    'type'       => $type,
                    'setting'    => ['define' => $define, 'value' => $val],
                    'status'     => 1,
                ];
                $v9fields[$value['modelid']][] = [
                    'name'       => $value['field'],
                    'title'      => $value['name'],
                    'ifsystem'   => $value['issystem'],
                    'listordert' => $value['listorder'],
                    'isadd'      => $value['isadd'],
                    'ifsearch'   => $value['issearch'],
                    'ifcore'     => $value['iscore'],
                    'type'       => $type,
                    'setting'    => ['define' => $define, 'value' => $val],
                    'status'     => 1,
                ];
                $result = $this->validate($data, 'app\cms\validate\ModelField');
                if (true !== $result) {
                    return $this->error($result);
                }
                try {
                    $res = $modelClass->addField($data);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
        }
        unset($cursor);
        Cache::set('v9_fields', $v9fields, 3600);

    }

    public function step5()
    {
        cache('Category', null);
        $db_config  = Cache::get('db_config');
        $pinyin     = new \Overtrue\Pinyin\Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
        $modelClass = new \app\cms\model\Category;
        $cursor     = Db::connect($db_config)->name('category')->where('type', 'in', '0,1')->cursor();
        foreach ($cursor as $key => $value) {
            $value['id']      = $value['catid'];
            $value['url']     = '';
            $value['status']  = $value['ismenu'] ? 1 : 0;
            $setting          = $this->string2array($value['setting']);
            $value['setting'] = array(
                'meta_title'        => $setting['meta_title'],
                'meta_keywords'     => $setting['meta_keywords'],
                'meta_description'  => $setting['category_template'] . '.html',
                'category_template' => $setting['meta_description'],
                'list_template]'    => $setting['list_template'] . '.html',
                'show_template'     => $setting['show_template'] . '.html',
            );
            $value['catdir'] = $pinyin->permalink($value['catname'], '');
            $value['catdir'] = substr($value['catdir'], 0, 10);
            $value['type']   = $value['type'] == 0 ? 2 : 1;
            if ($value['image']) {
                $value['image'] = strrchr($value['image'], '/');
                $image_id       = Db::name('attachment')->where('path', 'like', '%' . $value['image'])->value('id');
                $value['image'] = $image_id ? $image_id : 0;
            } else {
                $value['image'] = 0;
            }
            $result = $this->validate($value, 'app\cms\validate\Category.list');
            if (true !== $result) {
                $this->error($result);
            }
            $modelClass->addCategory($value, ['id', 'parentid', 'catname', 'arrparentid', 'arrchildid', 'child', 'items', 'catdir', 'type', 'modelid', 'image', 'icon', 'description', 'url', 'setting', 'listorder', 'status']);
            unset($value);
        }
        unset($cursor);
    }

    public function step6()
    {
        $v9_table  = Cache::get('v9_table');
        $db_config = Cache::get('db_config');
        $v9_fields = Cache::get('v9_fields');
        $Cms_Model = new \app\cms\model\Cms;
        $data      = [];
        foreach ($v9_table as $key => $value) {
            $cursor = Db::connect($db_config)->name($value)->alias('a')->join(['v9_' . $value . '_data' => 'w'], 'w.id=a.id')->join(['v9_category' => 'c'], 'c.catid=a.catid')->field('a.*,w.*,c.modelid')->cursor();
            foreach ($cursor as $key => $value) {
                try {
                    $data['modelField'] = [
                        'id'          => $value['id'],
                        'catid'       => $value['catid'],
                        'title'       => $value['title'],
                        'keywords'    => $value['keywords'] ? explode(" ", $value['keywords']) : '',
                        'description' => $value['description'],
                        'listorder'   => $value['listorder'],
                        'status'      => $value['status'] === 99 ? 1 : 0,
                        'inputtime'   => date('Y-m-d h:i:s', $value['inputtime']),
                        'updatetime'  => date('Y-m-d h:i:s', $value['updatetime']),
                    ];
                    if ($value['thumb']) {
                        $value['thumb']              = strrchr($value['thumb'], '/');
                        $thumb_id                    = Db::name('attachment')->where('path', 'like', '%' . $value['thumb'])->value('id');
                        $data['modelField']['thumb'] = $thumb_id ? $thumb_id : 0;
                    } else {
                        $data['modelField']['thumb'] = 0;
                    }
                    $data['modelFieldExt'] = [
                        'did'     => $value['id'],
                        'content' => $value['content'],
                    ];
                    //是否有自定义字段
                    if (isset($v9_fields[$value['modelid']])) {
                        foreach ($v9_fields[$value['modelid']] as $k => $v) {
                            switch ($v['type']) {
                                case 'image':
                                    if ($value[$v['name']]) {
                                        $value[$v['name']] = strrchr($value[$v['name']], '/');
                                        $image_id          = Db::name('attachment')->where('path', 'like', '%' . $value[$v['name']])->value('id');
                                        $value[$v['name']] = $image_id ? $image_id : 0;
                                    } else {
                                        $value[$v['name']] = 0;
                                    }
                                    break;
                            }
                            if ($v['ifsystem']) {
                                $data['modelField'][$v['name']] = $value[$v['name']];
                            } else {
                                $data['modelFieldExt'][$v['name']] = $value[$v['name']];
                            }
                        }
                    }
                    $Cms_Model->addModelData($data['modelField'], $data['modelFieldExt']);
                    unset($data);
                } catch (\Exception $ex) {
                    //$this->error($ex->getMessage());
                }
            }
        };
        unset($cursor);
    }

    public function step7()
    {
        $db_config = Cache::get('db_config');
        $cursor    = Db::connect($db_config)->name('page')->cursor();
        foreach ($cursor as $key => $value) {
            $data[] = [
                'catid'       => $value['catid'],
                'title'       => $value['title'],
                'content'     => $value['content'],
                'keywords'    => $value['keywords'] ? explode(" ", $value['keywords']) : '',
                'description' => $value['description'] ? $value['description'] : '',
                'updatetime'  => $value['updatetime'],
            ];
        }
        Db::name('page')->insertAll($data);
    }

    public function step8()
    {
        $db_config = Cache::get('db_config');
        $terms     = $links     = [];
        $cursor    = Db::connect($db_config)->name('type')->where('module', 'link')->cursor();
        foreach ($cursor as $key => $value) {
            $terms[] = [
                'id'       => $value['id'],
                'parentid' => $value['parentid'],
                'name'     => $value['name'],
                'module'   => 'links',
            ];
        }
        Db::name('terms')->insertAll($terms);
        $cursor = Db::connect($db_config)->name('link')->cursor();
        foreach ($cursor as $key => $value) {
            $links[$key] = [
                'id'          => $value['linkid'],
                'termsid'     => $value['typeid'],
                'linktype'    => $value['linktype'],
                'name'        => $value['name'],
                'url'         => $value['url'],
                'listorder'   => $value['listorder'],
                'inputtime'   => $value['addtime'],
                'description' => $value['introduce'],
                'status'      => $value['passed'],
            ];
            if ($value['logo']) {
                $value['logo']        = strrchr($value['logo'], '/');
                $logo_id              = Db::name('attachment')->where('path', 'like', '%' . $value['logo'])->value('id');
                $links[$key]['image'] = $logo_id ? $logo_id : 0;
            } else {
                $links[$key]['image'] = 0;
            };
        };
        Db::name('links')->insertAll($links);
    }

    private function string2array($data)
    {
        $data = trim($data);
        if ($data == '') {
            return array();
        }
        if (strpos($data, 'array') === 0) {
            @eval("\$array = $data;");
        } else {
            if (strpos($data, '{\\') === 0) {
                $data = stripslashes($data);
            }
            $array = json_decode($data, true);
        }
        return $array;
    }
}
