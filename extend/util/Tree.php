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
// | 通用的树型类，可以生成任何树型结构
// +----------------------------------------------------------------------

namespace util;

class Tree
{
    /**
     * 生成树型结构所需要的2维数组
     * @var array
     */
    public $arr = array();
    protected static $instance;
    public $options = [];

    /**
     * 生成树型结构所需修饰符号，可以换成图片
     * @var array
     */
    public $icon    = array('│', '├', '└');
    public $nbsp    = "&nbsp;";
    public $id      = "id";
    public $pidname = 'parentid';
    public $child   = 'child';
    public $ret     = '';

    /**
     * 初始化.
     *
     * @param array $options 参数
     *
     * @return Tree
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    /**
     * 构造函数，初始化类
     * @param array 2维数组，例如：
     * array(
     *      1 => array('id'=>'1','parentid'=>0,'name'=>'一级栏目一'),
     *      2 => array('id'=>'2','parentid'=>0,'name'=>'一级栏目二'),
     *      3 => array('id'=>'3','parentid'=>1,'name'=>'二级栏目一'),
     *      4 => array('id'=>'4','parentid'=>1,'name'=>'二级栏目二'),
     *      5 => array('id'=>'5','parentid'=>2,'name'=>'二级栏目三'),
     *      6 => array('id'=>'6','parentid'=>3,'name'=>'三级栏目一'),
     *      7 => array('id'=>'7','parentid'=>3,'name'=>'三级栏目二')
     *      )
     */
    public function init($arr = [], $pidname = null, $nbsp = null)
    {
        $this->arr = $arr;
        if (!is_null($pidname)) {
            $this->pidname = $pidname;
        }
        if (!is_null($nbsp)) {
            $this->nbsp = $nbsp;
        }
        $this->ret = '';
        return $this;
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    public function getChild($myid)
    {
        $a = $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $id => $a) {
                if ($a[$this->pidname] == $myid) {
                    $newarr[$id] = $a;
                }

            }
        }
        return $newarr;
    }

    /**
     * 读取指定节点的所有孩子节点
     * @param int     $myid     节点ID
     * @param boolean $withself 是否包含自身
     * @return array
     */
    public function getChildren($myid, $withself = false)
    {
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value[$this->pidname] == $myid) {
                $newarr[] = $value;
                $newarr   = array_merge($newarr, $this->getChildren($value['id']));
            } elseif ($withself && $value['id'] == $myid) {
                $newarr[] = $value;
            }
        }
        return $newarr;
    }

    /**
     * 读取指定节点的所有孩子节点ID
     * @param int     $myid     节点ID
     * @param boolean $withself 是否包含自身
     * @return array
     */
    public function getChildrenIds($myid, $withself = false)
    {
        $childrenlist = $this->getChildren($myid, $withself);
        $childrenids  = [];
        foreach ($childrenlist as $k => $v) {
            $childrenids[] = $v['id'];
        }
        return $childrenids;
    }

    /**
     * 得到当前位置父辈数组
     * @param int
     * @return array
     */
    public function getParent($myid)
    {
        $pid    = 0;
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value['id'] == $myid) {
                $pid = $value[$this->pidname];
                break;
            }
        }
        if ($pid) {
            foreach ($this->arr as $value) {
                if ($value['id'] == $pid) {
                    $newarr[] = $value;
                    break;
                }
            }
        }
        return $newarr;
    }

    /**
     * 得到当前位置所有父辈数组
     * @param int
     * @param bool $withself 是否包含自己
     * @return array
     */
    public function getParents($myid, $withself = false)
    {
        $pid    = 0;
        $newarr = [];
        foreach ($this->arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value['id'] == $myid) {
                if ($withself) {
                    $newarr[] = $value;
                }
                $pid = $value[$this->pidname];
                break;
            }
        }
        if ($pid) {
            $arr    = $this->getParents($pid, true);
            $newarr = array_merge($arr, $newarr);
        }
        return $newarr;
    }

    /**
     * 读取指定节点所有父类节点ID
     * @param int     $myid
     * @param boolean $withself
     * @return array
     */
    public function getParentsIds($myid, $withself = false)
    {
        $parentlist = $this->getParents($myid, $withself);
        $parentsids = [];
        foreach ($parentlist as $k => $v) {
            $parentsids[] = $v['id'];
        }
        return $parentsids;
    }

    /**
     * 树型结构Option
     * @param int    $myid        表示获得这个ID下的所有子级
     * @param string $itemtpl     条目模板 如："<option value=@id @selected @disabled>@spacer@name</option>"
     * @param mixed  $selectedids 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param mixed  $disabledids 被禁用的ID，比如在做树型下拉框的时候需要用到
     * @param string $itemprefix  每一项前缀
     * @param string $toptpl      顶级栏目的模板
     * @return string
     */
    public function getTree($myid, $itemtpl = '', $selectedids = '', $disabledids = '', $itemprefix = '', $toptpl = '')
    {
        if (!$itemtpl) {
            $itemtpl = '<option value=@id @selected @disabled>@spacer @title</option>';
        }
        $ret    = '';
        $number = 1;
        $childs = $this->getChild($myid);
        if ($childs) {
            $total = count($childs);
            foreach ($childs as $value) {
                $id = $value['id'];
                $j  = $k  = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                    $k = $itemprefix ? $this->nbsp : '';
                } else {
                    $j .= $this->icon[1];
                    $k = $itemprefix ? $this->icon[0] : '';
                }
                $spacer = $itemprefix ? $itemprefix . $j : '';
                if ('' !== $selectedids) {
                    $selected = $selectedids && in_array($id, (is_array($selectedids) ? $selectedids : explode(',', $selectedids))) ? 'selected' : '';
                    $value    = array_merge($value, ['selected' => $selected]);
                }
                if ('' !== $disabledids) {
                    $disabled = $disabledids && in_array($id, (is_array($disabledids) ? $disabledids : explode(',', $disabledids))) ? 'disabled' : '';
                    $value    = array_merge($value, ['disabled' => $disabled]);
                }
                $value = array_merge($value, array('spacer' => $spacer));
                $value = array_combine(array_map(function ($k) {
                    return '@' . $k;
                }, array_keys($value)), $value);
                $nstr = strtr((($value["@{$this->pidname}"] == 0 || $this->getChild($id)) && $toptpl ? $toptpl : $itemtpl), $value);
                $ret .= $nstr;
                $ret .= $this->getTree($id, $itemtpl, $selectedids, $disabledids, $itemprefix . $k . $this->nbsp, $toptpl);
                $number++;
            }
        }
        return $ret;
    }

    /**
     * 树型结构UL
     * @param int    $myid        表示获得这个ID下的所有子级
     * @param string $itemtpl     条目模板 如："<li value=@id @selected @disabled>@name @childlist</li>"
     * @param string $selectedids 选中的ID
     * @param string $disabledids 禁用的ID
     * @param string $wraptag     子列表包裹标签
     * @param string $wrapattr    子列表包裹属性
     * @return string
     */
    public function getTreeUl($myid, $itemtpl, $selectedids = '', $disabledids = '', $wraptag = 'ul', $wrapattr = '')
    {
        $str    = '';
        $childs = $this->getChild($myid);
        if ($childs) {
            foreach ($childs as $value) {
                $id = $value['id'];
                unset($value['child']);
                $selected = $selectedids && in_array($id, (is_array($selectedids) ? $selectedids : explode(',', $selectedids))) ? 'selected' : '';
                $disabled = $disabledids && in_array($id, (is_array($disabledids) ? $disabledids : explode(',', $disabledids))) ? 'disabled' : '';
                $value    = array_merge($value, array('selected' => $selected, 'disabled' => $disabled));
                $value    = array_combine(array_map(function ($k) {
                    return '@' . $k;
                }, array_keys($value)), $value);
                $nstr      = strtr($itemtpl, $value);
                $childdata = $this->getTreeUl($id, $itemtpl, $selectedids, $disabledids, $wraptag, $wrapattr);
                $childlist = $childdata ? "<{$wraptag} {$wrapattr}>" . $childdata . "</{$wraptag}>" : "";
                $str .= strtr($nstr, array('@childlist' => $childlist));
            }
        }
        return $str;
    }

    /**
     * 将数据集格式化成层次结构
     * @param int $pid 父级id
     * @param int $max_level 最多返回多少层，0为不限制
     * @param int $curr_level 当前层数
     * @author 蔡伟明 <314013107@qq.com>
     * @return array
     */
    public function toLayer($pid = 0, $max_level = 0, $curr_level = 0)
    {
        $trees = [];
        $lists = $this->getChild($pid);
        foreach ($lists as $key => $value) {
            if ($value[$this->pidname] == $pid) {
                if ($max_level > 0 && $curr_level == $max_level) {
                    return $trees;
                }
                unset($lists[$key]);
                $child = $this->toLayer($value[$this->id], $max_level, $curr_level + 1);
                if (!empty($child)) {
                    $value[$this->child] = $child;
                }
                $trees[$key] = $value;
            }
        }
        return $trees;
    }

    /**
     *
     * 获取树状数组
     * @param string $myid 要查询的ID
     * @param string $nametpl 名称条目模板
     * @param string $itemprefix 前缀
     * @return string
     */
    public function getTreeArray($myid, $itemprefix = '')
    {
        $child  = $this->getChild($myid);
        $n      = 0;
        $data   = [];
        $number = 1;
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $value) {
                $j = $k = '';
                if ($number == $total) {
                    $j .= $this->icon[2];
                    $k = $itemprefix ? $this->nbsp : '';
                } else {
                    $j .= $this->icon[1];
                    $k = $itemprefix ? $this->icon[0] : '';
                }
                $spacer          = $itemprefix ? $itemprefix . $j : '';
                $value['spacer'] = $spacer;
                $data[$n]        = $value;

                $data[$n]['childlist'] = $this->getTreeArray($value[$this->id], $itemprefix . $k . $this->nbsp);
                $n++;
                $number++;
            }
        }
        return $data;
    }

    /**
     * 将getTreeArray的结果返回为二维数组
     * @param array $data
     * @return array
     */
    public function getTreeList($data = [], $field = 'name')
    {
        $arr = [];
        foreach ($data as $k => $v) {
            $childlist = isset($v['childlist']) ? $v['childlist'] : [];
            unset($v['childlist']);
            $v[$field]     = $v['spacer'] . ' ' . $v[$field];
            $v['haschild'] = $childlist ? 1 : 0;
            if ($v[$this->id]) {
                $arr[] = $v;
            }

            if ($childlist) {
                $arr = array_merge($arr, $this->getTreeList($childlist, $field));
            }
        }
        return $arr;
    }

    private function have($list, $item)
    {
        return (strpos(',,' . $list . ',', ',' . $item . ','));
    }

}
