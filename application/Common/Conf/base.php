<?php
/**
 * drunk , fix later
 * Created by PhpStorm.
 * User: jiaying
 * Datetime: 15/10/19 01:12
 */

// OJ系统主要字段配置文件
return array(

    'OJ_VIP_CONTEST' => false, // 是否是正式比赛

    'judge_color' => array("gray", "gray", "orange", "orange", "green", "red", "red", "red", "red", "red", "red", "#004488", "#004488"),

    'judge_result' => array('Pending', 'Pending Rejudging', 'Compiling', 'Running & Judging',
        'Accepted', 'Presentation Error', 'Wrong Answer', 'Time Limit Exceed', 'Memory Limit Exceed',
        'Output Limit Exceed', 'Runtime Error', 'Compile Error', 'Compile OK'),

    'language_ext' => array("c", "cc", "pas", "java"),

    'OJ_APPENDCODE' => true,

    'OJ_DATA' => "/home/judge/data",
);