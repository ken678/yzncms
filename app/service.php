<?php

// 定义服务
return array_merge([
    '\think\AddonsService',
    '\app\AppService',
], config('addons.service'));
