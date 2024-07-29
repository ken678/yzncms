<?php

// 容器Provider定义文件
use app\api\library\ApiExceptionHandle;

return [
    'think\exception\Handle' => ApiExceptionHandle::class,
];
