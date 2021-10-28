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
// | dede数据转换管理
// +----------------------------------------------------------------------
namespace addons\dedetoyzn\Controller;

use app\addons\util\Adminaddon;
use think\Db;
use think\facade\Cache;
use think\facade\Log;

class Admin extends Adminaddon
{
    //dedecms固定相关表
    private $dedeModelTabList = array(
        'arctype',
        'channeltype',
    );
    //yzncms固定相关表
    private $modelTabList = array(
        'model',
        'model_field',
        'category',
        'page',
        'attachment',
    );
    private $flag = array(
        'h' => 2,
        'c' => 4,
        'f' => 6,
        'a' => 3,
    );
    private $dedefieldList = array(
        'id',
        'catid',
        'title',
        'thumb',
        'flag',
        'keywords',
        'description',
        'tags',
        'listorder',
        'uid',
        'username',
        'sysadd',
        'hits',
        'inputtime',
        'updatetime',
        'url',
        'status',
        'did',
        'content',
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
        Cache::set('db_config', $db_config, 600);
        $db_task = [
            ['fun' => 'step1', 'msg' => '数据表清空完毕!'],
            ['fun' => 'step2', 'msg' => '附件表转换完毕!'],
            ['fun' => 'step3', 'msg' => '模型表转换完毕!'],
            ['fun' => 'step4', 'msg' => '模型字段表转换完毕!'],
            ['fun' => 'step5', 'msg' => '栏目转换完毕!'],
            ['fun' => 'step6', 'msg' => '内容页转换完毕!'],
            ['fun' => 'step7', 'msg' => '单页转换完毕!'],
        ];
        if (!function_exists("finfo_open")) {
            $this->error('检测到环境未开启php_fileinfo拓展');
        }
        //检查dedecms表是否正常
        try {
            $db2 = Db::connect($db_config);
            foreach ($this->dedeModelTabList as $tablename) {
                $res = $db2->query("SHOW TABLES LIKE '{$db_config['prefix']}{$tablename}'");
                if ('channeltype' == $tablename) {
                    $modelTable = $db2->table($db_config['prefix'] . $tablename)->select();
                    foreach ($modelTable as $k => $v) {
                        $res = $db2->query("SHOW TABLES LIKE '{$db_config['prefix']}{$v['tablename']}'");
                    }
                }
            }
            //检查是否存在友情链接
            $res = $db2->query("SHOW TABLES LIKE '{$db_config['prefix']}flink'");
            if ($res && isModuleInstall('links')) {
                $db_task[] = ['fun' => 'step8', 'msg' => '友情链接转换完毕!'];
            }
            Cache::set('db_task', $db_task, 600);
            unset($db2);
            unset($res);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('dedecms数据表结构检查完毕！');
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
        if (!isModuleInstall('cms')) {
            $this->error('系统未安装cms模块，请先安装！');
        }
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
        $finfo     = finfo_open(FILEINFO_MIME_TYPE);
        try {
            Db::connect($db_config)->name('uploads')->chunk(100, function ($cursor) use ($finfo) {
                foreach ($cursor as $key => $value) {
                    $path     = ROOT_PATH . 'public' . str_replace("/", DS, $value['url']);
                    $is_exist = file_exists($path) ? true : false;
                    $data[]   = [
                        'id'          => $value['aid'],
                        'aid'         => (int) session('admin.id'),
                        'uid'         => $value['mid'],
                        'name'        => $value['title'],
                        'path'        => $value['url'],
                        'md5'         => $is_exist ? hash_file('md5', $path) : '',
                        'sha1'        => $is_exist ? hash_file('sha1', $path) : '',
                        'size'        => $value['filesize'],
                        'ext'         => strtolower(pathinfo($value['url'], PATHINFO_EXTENSION)),
                        'mime'        => $is_exist ? finfo_file($finfo, $path) : '',
                        'create_time' => $value['uptime'],
                        'update_time' => $value['uptime'],
                        'status'      => 1,
                    ];
                };
                Db::name('attachment')->insertAll($data);
            });
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function step3()
    {
        $db_config  = Cache::get('db_config');
        $res        = Db::connect($db_config)->name('channeltype')->select();
        $modelClass = new \app\cms\model\Models;
        $data       = $dede_models       = [];
        try {
            foreach ($res as $key => $value) {
                $tablename                              = str_replace($db_config['prefix'], '', $value['addtable']);
                $dede_models[$value['id']]['id']        = $data['id']        = $key + 1;
                $data['name']                           = $value['typename'];
                $dede_models[$value['id']]['name']      = $data['tablename']      = $tablename;
                $data['listorders']                     = $value['sort'];
                $dede_models[$value['id']]['type']      = $data['type']      = 2;
                $dede_models[$value['id']]['real_type'] = $value['issystem'] == -1 ? 1 : 2;
                $data['status']                         = 1;
                $data['listorders']                     = 100;
                $data['setting']                        = array(
                    'category_template' => '',
                    'list_template'     => '',
                    'show_template'     => '',
                );
                $result = $this->validate($data,
                    [
                        'name|模型名称'     => 'require|max:30|unique:model',
                        'tablename|表键名' => 'require|max:20|unique:model',
                        'type|模型类型'     => 'in:1,2',
                    ]);
                if (true !== $result) {
                    $this->error($result);
                }

                $modelClass->addModel($data);

            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        unset($res);
        Cache::set('dede_models', $dede_models, 600);
    }

    public function step4()
    {
        $db_config = Cache::get('db_config');
        //$dede_models = Cache::get('dede_models');
        $res         = Db::connect($db_config)->name('channeltype')->select();
        $modelClass  = new \app\cms\model\ModelField;
        $dede_fields = $data = [];
        foreach ($res as $key => $value) {
            preg_match_all("/<field:([a-z]+) itemname=\"(.*?)\" autofield=\"(.*?)\"(?:.*?)type=\"(.*?)\"(?:.*?)default=\"(.*?)\"/", $value['fieldset'], $matches, PREG_SET_ORDER);
            foreach ($matches as $k => $match) {
                if ($match[3] == "1" && in_array($match[4], ['htmltext', 'text', 'textdata', 'int', 'img'])) {
                    if (in_array($match[1], $this->dedefieldList)) {
                        $modelInfo = cache('Model')[$key + 1];
                        return $this->error('dedecms后台内容管理模型【' . $modelInfo['name'] . '】，其中自定义字段含保留字【' . $match[1] . '】，请重新命名');
                    }
                    $isadd = true;
                    switch ($match[4]) {
                        case 'int':
                            $define  = "int(10) UNSIGNED NOT NULL";
                            $type    = 'number';
                            $val     = $match[5];
                            $options = '';
                            break;
                        case 'text':
                        case 'textdata':
                            $define  = "varchar(255) NOT NULL";
                            $type    = 'text';
                            $val     = $match[5];
                            $options = '';
                            break;
                        case 'img':
                            $define  = "varchar(255) NOT NULL";
                            $type    = 'image';
                            $val     = '';
                            $options = '';
                            break;
                        case 'htmltext':
                            $define  = "text NOT NULL";
                            $type    = 'Ueditor';
                            $val     = $match[5];
                            $options = '';
                            break;
                    }
                    if ($isadd) {
                        $data = [
                            'modelid'   => $key + 1,
                            'name'      => $match[1],
                            'title'     => $match[2],
                            'remark'    => "",
                            'ifsystem'  => 0,
                            'listorder' => $value['sendrank'],
                            'isadd'     => 0,
                            'ifsearch'  => 0,
                            'ifcore'    => 0,
                            'type'      => $type,
                            'setting'   => ['define' => $define, 'value' => $val, 'options' => $options],
                            'status'    => 1,
                        ];
                        $dede_fields[$data['modelid']][] = [
                            'name'      => $data['name'],
                            'title'     => $data['title'],
                            'remark'    => $data['remark'],
                            'ifsystem'  => $data['ifsystem'],
                            'listorder' => $data['listorder'],
                            'isadd'     => $data['isadd'],
                            'ifsearch'  => $data['ifsearch'],
                            'ifcore'    => $data['ifcore'],
                            'type'      => $type,
                            'setting'   => ['define' => $define, 'value' => $val, 'options' => $options],
                            'status'    => 1,
                        ];
                        $result = $this->validate($data, [
                            'name|字段名称'           => 'require|regex:/^[a-zA-Z][A-Za-z0-9\-\_]+$/',
                            'title|字段标题'          => 'require',
                            'type|字段类型'           => 'require|alphaDash',
                            'setting.define|字段定义' => 'require',
                            'ifsystem|主表字段'       => 'in:0,1',
                            'ifrequire|是否必填格'     => 'in:0,1',
                            'ifsearch|是否显示搜索'     => 'in:0,1',
                            'status|字段状态'         => 'in:0,1',
                        ]);
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
            }
        }
        Cache::set('dede_fields', $dede_fields, 600);
    }

    public function step5()
    {
        cache('Category', null);
        $db_config   = Cache::get('db_config');
        $dede_models = Cache::get('dede_models');
        $pinyin      = new \Overtrue\Pinyin\Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
        $modelClass  = new \app\cms\model\Category;
        $cursor      = Db::connect($db_config)->name('arctype')->cursor();
        $catdir      = $data      = [];
        try {
            foreach ($cursor as $key => $value) {
                $data['id']          = $value['id'];
                $data['modelid']     = $value['ispart'] != 0 ? 0 : $dede_models[$value['channeltype']]['id'];
                $data['catname']     = $value['typename'];
                $data['parentid']    = $value['reid'];
                $data['description'] = "";
                $data['url']         = $value['ispart'] == 2 ? $value['typedir'] : '';
                $data['status']      = $value['ishidden'] == 0 ? 1 : 0;
                $data['listorder']   = $value['sortrank'];
                $data['setting']     = array(
                    'meta_title'        => isset($value['seotitle']) ? $value['seotitle'] : '',
                    'meta_keywords'     => isset($value['keywords']) ? $value['keywords'] : '',
                    'meta_description'  => isset($value['description']) ? mb_substr($value['description'], 0, 200) : '',

                    'category_template' => isset($value['tempindex']) ? $this->getTemplate($value['tempindex'], 'category') : 'category.html',
                    'list_template'     => isset($value['templist']) ? $this->getTemplate($value['templist'], 'list') : 'list.html',
                    'show_template'     => isset($value['temparticle']) ? $this->getTemplate($value['temparticle'], 'show') : 'show.html',
                    'page_template'     => isset($value['page_template']) ? $this->getTemplate($value['page_template'], 'page') : 'page.html',
                );
                if (is_numeric($value['typename'])) {
                    $data['catdir'] = $value['typename'];
                } else {
                    $data['catdir'] = $pinyin->permalink($value['typename'], '');
                    $data['catdir'] = substr($data['catdir'], 0, 10);
                }
                $data['catdir'] = in_array($data['catdir'], $catdir) ?: $data['catdir'] . genRandomString(3);
                $catdir[]       = $data['catdir'];
                $data['type']   = $value['ispart'] != 0 ? 1 : 2;
                $result         = $this->validate($data, 'app\cms\validate\Category.list');
                if (true !== $result) {
                    $this->error($result);
                }
                $modelClass->addCategory($data, ['id', 'parentid', 'catname', 'arrparentid', 'arrchildid', 'child', 'items', 'catdir', 'type', 'modelid', 'image', 'icon', 'description', 'url', 'setting', 'listorder', 'status']);
                unset($data);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        unset($cursor);
    }

    public function step6()
    {
        Log::close();
        set_time_limit(0);
        ini_set('memory_limit', '500M');
        $db_config   = Cache::get('db_config');
        $dede_models = Cache::get('dede_models');
        $dede_fields = Cache::get('dede_fields');
        $Cms_Model   = new \app\cms\model\Cms;
        $data        = [];

        foreach ($dede_models as $key => $value) {
            if ($value['real_type'] == 2) {
                try {
                    $cursor = Db::connect($db_config)
                        ->name('archives')->alias('a')
                        ->join([$db_config['prefix'] . $value['name'] => 'w'], 'a.id=w.aid and a.typeid=w.typeid')->field('a.*,w.*')->select();
                    foreach ($cursor as $key => $value) {
                        $modelid            = $dede_models[$value['channel']]['id'];
                        $data['modelField'] = [
                            'id'          => $value['id'],
                            'catid'       => $value['typeid'],
                            'thumb'       => $value['litpic'] ?? "",
                            'flag'        => $this->getFlag($value['flag']),
                            'tags'        => '',
                            'url'         => '',
                            'hits'        => $value['click'],
                            'modelid'     => $modelid,
                            'title'       => $value['title'],
                            'keywords'    => $value['keywords'],
                            'description' => mb_substr($value['description'], 0, 200),
                            'listorder'   => $value['weight'] > 65535 ? 65535 : $value['weight'],
                            'status'      => $value['arcrank'] == -2 ? -1 : 1,
                            'inputtime'   => date('Y-m-d h:i:s', $value['senddate']),
                            'updatetime'  => date('Y-m-d h:i:s', $value['senddate']),
                        ];
                        $data['modelFieldExt'] = [
                            'did'     => $value['id'],
                            'content' => $value['body'] ?: '',
                        ];
                        //是否有自定义字段
                        if (isset($dede_fields[$modelid])) {
                            foreach ($dede_fields[$modelid] as $k => $v) {
                                switch ($v['type']) {
                                    case 'image':
                                        if (strpos($value[$v['name']], 'dede:img') && preg_match("/'}(.*?){\/dede:img}/", $value[$v['name']], $matches)) {
                                            $value[$v['name']] = isset($matches[1]) ? trim($matches[1]) : '';
                                        }
                                        break;
                                }
                                if ($v['ifsystem']) {
                                    $data['modelField'][$v['name']] = $value[$v['name']] ?? "";
                                } else {
                                    $data['modelFieldExt'][$v['name']] = $value[$v['name']] ?? "";
                                }
                            }
                        }
                        $Cms_Model->addModelData($data['modelField'], $data['modelFieldExt']);
                        unset($data);
                    }
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            } elseif ($value['real_type'] == 1) {
                // 独立表
                try {
                    $cursor = Db::connect($db_config)->name($value['name'])->select();
                    foreach ($cursor as $key => $value) {
                        $modelid            = $dede_models[$value['channel']]['id'];
                        $data['modelField'] = [
                            'id'         => $value['id'],
                            'catid'      => $value['typeid'],
                            'thumb'      => $value['litpic'] ?? "",
                            'flag'       => $this->getFlag($value['flag']),
                            'title'      => $value['title'],
                            'tags'       => '',
                            'url'        => '',
                            'hits'       => $value['click'],
                            'modelid'    => $modelid,
                            'listorder'  => $value['weight'] > 65535 ? 65535 : $value['weight'],
                            'status'     => $value['arcrank'] == -2 ? -1 : 1,
                            'inputtime'  => date('Y-m-d h:i:s', $value['senddate']),
                            'updatetime' => date('Y-m-d h:i:s', $value['senddate']),
                        ];
                        //是否有自定义字段
                        if (isset($dede_fields[$modelid])) {
                            foreach ($dede_fields[$modelid] as $k => $v) {
                                if ($v['ifsystem']) {
                                    $data['modelField'][$v['name']] = $value[$v['name']] ?? "";
                                } else {
                                    $data['modelFieldExt'][$v['name']] = $value[$v['name']] ?? "";
                                }
                            }
                        }
                        $Cms_Model->addModelData($data['modelField'], $data['modelFieldExt']);
                        unset($data);
                    };
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            };
            unset($cursor);
        }
        unset($dede_models);
        unset($dede_fields);
    }

    public function step7()
    {
        $data      = [];
        $db_config = Cache::get('db_config');
        try {
            $cursor = Db::connect($db_config)->name('arctype')->where('ispart', 'in', [1, 2])->cursor();
            foreach ($cursor as $key => $value) {
                $data[] = [
                    'catid'       => $value['id'],
                    'title'       => $value['typename'],
                    'content'     => $value['content'],
                    'keywords'    => $value['keywords'],
                    'description' => mb_substr($value['description'], 0, 200),
                    'inputtime'   => time(),
                    'updatetime'  => time(),
                ];
            }
            if ($data) {
                Db::name('page')->insertAll($data);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        unset($cursor);
    }

    public function step8()
    {
        $db_config = Cache::get('db_config');
        $terms     = $links     = [];
        $cursor    = Db::connect($db_config)->name('flinktype')->cursor();
        foreach ($cursor as $key => $value) {
            $terms[] = [
                'id'       => $value['id'],
                'parentid' => 0,
                'name'     => $value['typename'],
                'module'   => 'links',
            ];
        }
        if ($terms) {
            Db::name('terms')->insertAll($terms);
        }
        $cursor = Db::connect($db_config)->name('flink')->cursor();
        foreach ($cursor as $key => $value) {
            $links[$key] = [
                'id'          => $value['id'],
                'termsid'     => $value['typeid'],
                'linktype'    => $value['logo'] ? 1 : 0,
                'name'        => $value['webname'],
                'url'         => $value['url'],
                'listorder'   => $value['sortrank'],
                'inputtime'   => $value['dtime'],
                'description' => $value['msg'],
                'status'      => 1,
            ];
        };
        if ($links) {
            Db::name('links')->insertAll($links);
        }
    }

    private function getFlag($flag = "")
    {
        if ($flag) {
            $flag = explode(',', $flag);
            $flag = array_map(function ($item) {
                return isset($this->flag[$item]) ? $this->flag[$item] : '';
            }, $flag);
            $flag = implode(',', array_filter($flag));
        }
        return $flag;
    }

    private function getTemplate($tem, $type)
    {
        $array = array_reverse(explode('/', trim($tem, '.htm')));
        if (strpos($array[0], $type)) {
            return $array[0] . '.html';
        } else {
            if (strpos($array[0], '_')) {
                $array = array_reverse(explode('_', $array[0]));
            }
            return $type . '_' . $array[0] . '.html';
        }

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
