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
// | 后台配置模型
// +----------------------------------------------------------------------
namespace app\common\model;

use think\Model;

class Config extends Model
{
    // 追加属性
    protected $append = [
        'extend_html',
    ];

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function getExtendHtmlAttr($value, $data)
    {
        $result = preg_replace_callback("/\{([a-zA-Z]+)\}/", function ($matches) use ($data) {
            if (isset($data[$matches[1]])) {
                return $data[$matches[1]];
            }
        }, $data['extend']);
        return $result;
    }

    /**
     * 刷新配置文件
     */
    public static function refreshFile()
    {
        $configs    = self::where('status', 1)->column('value,type,options', 'name');
        $newConfigs = [];
        foreach ($configs as $key => $value) {
            if ($value['options'] != '') {
                $value['options'] = parse_attr($value['options']);
            }
            switch ($value['type']) {
                case 'array':
                    $newConfigs[$key] = (array) json_decode($value['value'], true);
                    break;
                case 'select':
                case 'radio':
                    $newConfigs[$key] = $value['value'];
                    if (isset($value['options'][$value['value']])) {
                        $newConfigs[$key . '_text'] = $value['options'][$value['value']];
                    } else {
                        $newConfigs[$key . '_text'] = $value['value'];
                    }
                    break;
                case 'selects':
                case 'checkbox':
                    if (empty($value['value'])) {
                        $newConfigs[$key] = [];
                    } else {
                        $valueArr = explode(',', $value['value']);
                        foreach ($valueArr as $v) {
                            if (isset($value['options'][$v])) {
                                $newConfigs[$key][$v] = $value['options'][$v];
                            } elseif ($v) {
                                $newConfigs[$key][$v] = $v;
                            }
                        }
                    }
                    break;
                case 'files':
                case 'images':
                    $newConfigs[$key] = empty($value['value']) ? [] : explode(',', $value['value']);
                    break;
                case 'Ueditor':
                    $newConfigs[$key] = htmlspecialchars_decode($value['value']);
                    break;
                default:
                    $newConfigs[$key] = $value['value'];
                    break;
            }
        }
        file_put_contents(
            app()->getConfigPath() . 'site.php',
            '<?php' . "\n\nreturn " . var_export_short($newConfigs) . ";\n"
        );
        return true;
    }

    /**
     * 本地上传配置信息
     * @return array
     */
    public static function upload()
    {
        $uploadcfg = config('upload');

        $uploadurl = app()->http->getName() ? $uploadcfg['uploadurl'] : ($uploadcfg['uploadurl'] === 'ajax/upload' ? 'index/' . $uploadcfg['uploadurl'] : $uploadcfg['uploadurl']);

        if (!preg_match("/^((?:[a-z]+:)?\/\/)(.*)/i", $uploadurl) && substr($uploadurl, 0, 1) !== '/') {
            $uploadurl = (string) url($uploadurl, [], false);
        }
        $uploadcfg['fullmode']   = isset($uploadcfg['fullmode']) && $uploadcfg['fullmode'];
        $uploadcfg['thumbstyle'] = $uploadcfg['thumbstyle'] ?? '';

        $upload = [
            'cdnurl'     => $uploadcfg['cdnurl'],
            'uploadurl'  => $uploadurl,
            'bucket'     => 'local',
            'maxsize'    => $uploadcfg['maxsize'],
            'mimetype'   => $uploadcfg['mimetype'],
            'chunking'   => $uploadcfg['chunking'],
            'chunksize'  => $uploadcfg['chunksize'],
            'savekey'    => $uploadcfg['savekey'],
            'multipart'  => [],
            'multiple'   => $uploadcfg['multiple'],
            'fullmode'   => $uploadcfg['fullmode'],
            'thumbstyle' => $uploadcfg['thumbstyle'],
            'storage'    => 'local',
        ];
        return $upload;
    }

}
