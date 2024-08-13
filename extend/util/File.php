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
// | 文件及文件夹处理类
// +----------------------------------------------------------------------
namespace util;

class File
{
    /**
     * 创建目录
     * @param string $dir 目录名
     * @return bool true 成功， false 失败
     */
    public static function mk_dir(string $dir): bool
    {
        $dir = rtrim($dir, '/') . '/';
        if (!is_dir($dir)) {
            if (!mkdir($dir, 0700, true)) {
                return false;
            }
            return true;
        }
        return true;
    }

    /**
     * 读取文件内容
     * @param string $filename 文件名
     * @return false|string
     */
    public static function read_file(string $filename): false|string
    {
        $content = '';
        if (function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if (@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        return $content;
    }

    /**
     * 写文件
     * @param string $filename 文件名
     * @param string $writetext 要写入的字符串
     * @param string $openmod 打开方式
     * @return bool
     */
    public static function write_file(string $filename, string $writetext, string $openmod = 'w'): bool
    {
        if (@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 删除目录
     * @param string $dirName 原目录
     * @return bool true 成功, false 失败
     */
    public static function del_dir(string $dirName): bool
    {
        if (!file_exists($dirName)) {
            return false;
        }

        $dir = opendir($dirName);
        while ($fileName = readdir($dir)) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::del_dir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }

    /**
     * 复制目录
     * @param string $surDir 原目录
     * @param string $toDir 目标目录
     * @return bool true 成功, false 失败
     */
    public static function copy_dir(string $surDir, string $toDir): bool
    {
        $surDir = rtrim($surDir, '/') . '/';
        $toDir  = rtrim($toDir, '/') . '/';
        if (!file_exists($surDir)) {
            return false;
        }

        if (!file_exists($toDir)) {
            self::mk_dir($toDir);
        }
        $file = opendir($surDir);
        while ($fileName = readdir($file)) {
            $file1 = $surDir . '/' . $fileName;
            $file2 = $toDir . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    self::copy_dir($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
    }

    /**
     * 列出目录
     * @param string $dir 目录名
     * @return array  目录数组。列出文件夹下内容，返回数组 $dirArray['dir']:存文件夹；$dirArray['file']：存文件
     */
    public static function get_dirs(string $dir): array
    {
        $dir          = rtrim($dir, '/') . '/';
        $dirArray[][] = null;
        if (($handle = opendir($dir))) {
            $i = 0;
            $j = 0;
            while (false !== ($file = readdir($handle))) {
                if (is_dir($dir . $file)) {
                    //判断是否文件夹
                    $dirArray['dir'][$i] = $file;
                    $i++;
                } else {
                    $dirArray['file'][$j] = $file;
                    $j++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }

    /**
     * 取得目录下面的文件信息
     * @param string $pathname 路径
     * @param string $pattern
     * @return array
     */
    public static function listFile(string $pathname, string $pattern = '*'): array
    {
        if (str_contains($pattern, '|')) {
            $patterns = explode('|', $pattern);
        } else {
            $patterns[0] = $pattern;
        }
        $i   = 0;
        $dir = [];
        foreach ($patterns as $pattern) {
            $list = glob($pathname . $pattern);
            if ($list !== false) {
                foreach ($list as $file) {
                    //$dir[$i]['filename']    = basename($file);
                    //basename取中文名出问题.改用此方法
                    //编码转换.把中文的调整一下.
                    $dir[$i]['filename'] = preg_replace('/^.+[\\\\\\/]/', '', $file);
                    $dir[$i]['pathname'] = realpath($file);
                    $dir[$i]['owner']    = fileowner($file);
                    $dir[$i]['perms']    = fileperms($file);
                    $dir[$i]['inode']    = fileinode($file);
                    $dir[$i]['group']    = filegroup($file);
                    $dir[$i]['path']     = dirname($file);
                    $dir[$i]['atime']    = fileatime($file);
                    $dir[$i]['ctime']    = filectime($file);
                    $dir[$i]['size']     = filesize($file);
                    $dir[$i]['type']     = filetype($file);
                    $dir[$i]['ext']      = is_file($file) ? strtolower(substr(strrchr(basename($file), '.'), 1)) : '';
                    $dir[$i]['mtime']    = filemtime($file);
                    $dir[$i]['isDir']    = is_dir($file);
                    $dir[$i]['isFile']   = is_file($file);
                    $dir[$i]['isLink']   = is_link($file);
                    //$dir[$i]['isExecutable']= function_exists('is_executable')?is_executable($file):'';
                    $dir[$i]['isReadable'] = is_readable($file);
                    $dir[$i]['isWritable'] = is_writable($file);
                    $i++;
                }
            }
        }
        // 对结果排序 保证目录在前面
        usort($dir, function ($a, $b) {
            if (($a["isDir"] && $b["isDir"]) || (!$a["isDir"] && !$b["isDir"])) {
                return $a["filename"] > $b["filename"] ? 1 : -1;
            } else {
                if ($a["isDir"]) {
                    return -1;
                } elseif ($b["isDir"]) {
                    return 1;
                }
                if ($a["filename"] == $b["filename"]) {
                    return 0;
                }
                return $a["filename"] > $b["filename"] ? -1 : 1;
            }
        });
        return $dir;
    }

    /**
     * 统计文件夹大小
     * @param string $dir 目录名
     * @return int 文件夹大小(单位 B)
     */
    public static function get_size(string $dir): int
    {
        $dirlist = opendir($dir);
        $dirsize = 0;
        while (false !== ($folderorfile = readdir($dirlist))) {
            if ($folderorfile != "." && $folderorfile != "..") {
                if (is_dir("$dir/$folderorfile")) {
                    $dirsize += self::get_size("$dir/$folderorfile");
                } else {
                    $dirsize += filesize("$dir/$folderorfile");
                }
            }
        }
        closedir($dirlist);
        return $dirsize;
    }

    /**
     * 检测是否为空文件夹
     * @param string $dir 目录名
     * @return bool true 空， fasle 不为空
     */
    public static function empty_dir(string $dir): bool
    {
        return (($files = @scandir($dir)) && count($files) <= 2);
    }

    /**
     * 移除空目录
     * @param string $dir 目录
     * @return void
     */
    public static function remove_empty_folder(string $dir): void
    {
        try {
            $isDirEmpty = !(new \FilesystemIterator($dir))->valid();
            if ($isDirEmpty) {
                @rmdir($dir);
                self::remove_empty_folder(dirname($dir));
            }
        } catch (\UnexpectedValueException $e) {
        } catch (\Exception $e) {
        }
    }

    /**
     * 判断文件或文件夹是否可写.
     * @param string $file 文件或目录
     * @return bool
     */
    public static function is_really_writable(string $file): bool
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);

            return true;
        } elseif (!is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }
        fclose($fp);
        return true;
    }

}
