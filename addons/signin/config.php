<?php

return array(
    'signinscore' => array(
        'title' => '签到积分',
        'type' => 'array',
        'value' => [
            's1' => '1',
            's2' => '2',
            's3' => '3',
            's4' => '4',
            's5' => '5',
            's6' => '6',
            's7' => '7',
            'sn' => '7',
        ],
        'tip' => '签到赠送的积分',
    ),
    'isfillup' => array(
        'title' => '是否开启补签',
        'type' => 'radio',
        'options' => [
            1 => '是',
            0 => '否',
        ],
        'value' => 1,
        'tip' => '是否开启补签',
    ),
    'fillupscore' => array(
        'title' => '补签消耗积分',
        'type' => 'text',
        'value' => 100,
        'tip' => '补签时消耗的积分',
    ),
    'fillupdays' => array(
        'name' => 'fillupdays',
        'title' => '补签天数内',
        'type' => 'text',
        'value' => 3,
        'tip' => '多少天数内漏签的可以补签',
    ),
    'fillupnumsinmonth' => array(
        'name' => 'fillupnumsinmonth',
        'title' => '每月可补签次数',
        'type' => 'text',
        'value' => 1,
        'tip' => '每月可补签次数',
    ),
);
