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
// | 测试队列,仅供参考
// +----------------------------------------------------------------------
namespace addons\queue\jobs;

use think\facade\Log;
use think\queue\Job;

class DemoJob
{

    /**
     * fire方法是消息队列默认调用的方法
     * @param Job            $job      当前的任务对象
     * @param array|mixed    $data     发布任务时自定义的数据
     */
    public function fire(Job $job, $data)
    {
        $isJobDone = $this->doHelloJob($data);
        if ($isJobDone) {
            // 如果任务执行成功， 记得删除任务
            $job->delete();
            print("<info>Hello Job has been done and deleted" . "</info>\n");
        } else {
            //超过3次删除任务 如果不删除 并且命令行附带--tries参数，则会执行failed方法
            if ($job->attempts() > 3) {
                $job->delete();
                print("<warn>Hello Job has been retried more than 3 times!" . "</warn>\n");
            } else {
                //5秒后从新放入队列
                $job->release(5);
            }
        }
    }

    //多任务A 在发布任务时，需要用 push('任务的类名@方法名')
    public function taskA(Job $job, $data)
    {
        $isJobDone = $this->_doTaskA($data);
        if ($isJobDone) {
            $job->delete();
            print("Info: TaskA of Job MultiTask has been done and deleted" . "\n");
        } else {
            if ($job->attempts() > 3) {
                $job->delete();
            }
        }
    }

    //多任务B 在发布任务时，需要用 push('任务的类名@方法名')
    public function taskB(Job $job, $data)
    {
        $isJobDone = $this->_doTaskA($data);
        if ($isJobDone) {
            $job->delete();
            print("Info: TaskB of Job MultiTask has been done and deleted" . "\n");
        } else {
            if ($job->attempts() > 2) {
                $job->release();
            }
        }
    }

    /**
     * 根据消息中的数据进行实际的业务处理...
     */
    private function doHelloJob($data)
    {
        print("<info>Hello Job Started. job Data is: " . var_export($data, true) . "</info> \n");
        print("<info>Hello Job is Fired at " . date('Y-m-d H:i:s') . "</info> \n");
        print("<info>Hello Job is Done!" . "</info> \n");
        return false;
    }

    private function _doTaskA($data)
    {
        print("Info: doing TaskA of Job MultiTask " . "\n");
        return true;
    }

    private function _doTaskB($data)
    {
        print("Info: doing TaskB of Job MultiTask " . "\n");
        return true;
    }

    //此函数可以省略 通常用于定义通知管理员队列失败
    //任务最终失败回调 命令行的 --tries 参数的值大于0生效
    public function failed($jobData)
    {
        Log::error("Warning: Job failed after max retries. job data is :" . var_export($jobData, true));
    }
}
