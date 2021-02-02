<?php
return array(
    array(
        'name'    => 'mode',
        'title'   => '模式',
        'type'    => 'radio',
        'options' => array(
            'random' => '每次随机',
            'daily'  => '每日切换',
        ),
        'value'   => 'daily',
        'tip'     => '随机切换只支持七天内图片',
    ),
);
