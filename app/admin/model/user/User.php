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
// | 会员模型
// +----------------------------------------------------------------------
namespace app\admin\model\user;

use think\facade\Config;
use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;
    protected $createTime         = 'reg_time';

    // 追加属性
    protected $append = [
        'overduedate_text',
    ];

    protected function setRegIpAttr()
    {
        return request()->ip();
    }

    protected function setLastLoginIpAttr()
    {
        return request()->ip();
    }

    protected function setLastLoginTimeAttr()
    {
        return time();
    }

    protected function setBirthdayAttr($value)
    {
        return $value ? $value : null;
    }

    protected function getBirthdayAttr($value)
    {
        return $value ? $value : '';
    }

    protected function setOverduedateAttr($value)
    {
        return $value ? strtotime($value) : null;
    }

    public function getOverduedateTextAttr($value, $data)
    {
        $value = $value ?: ($data['overduedate'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    public static function onbeforeUpdate($row)
    {
        $changed = $row->getChangedData();
        //如果有修改密码
        if (isset($changed['password'])) {
            if ($changed['password']) {
                $encrypt       = \util\Random::alnum();
                $row->password = encrypt_password($changed['password'], $encrypt);
                $row->encrypt  = $encrypt;
            } else {
                unset($row->password);
            }
        }
    }

    /**
     * 获取头像
     * @param $value
     * @param $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            switch (Config::get('yzn.user_avatar_mode')) {
                case 'letter':
                    $value = letter_avatar($data['nickname']);
                    break;
                case 'gravatar':
                    $value = "https://cravatar.cn/avatar/" . md5(strtolower(trim($data['email']))) . "?s=80";
                    break;
                default:
                    $value = config('upload.cdnurl') . '/assets/img/avatar.png';
                    break;
            }
        }
        return cdnurl($value, true);
    }

    public function group()
    {
        return $this->belongsTo('UserGroup', 'group_id', 'id');
    }
}
