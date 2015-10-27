<?php
return array(

    //Database config
    'DB_TYPE' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'test',
    'DB_USER' => 'root',
    'DB_PWD' => '',
    'DB_PREFIX' => '',

    'LOAD_EXT_CONFIG' => 'base,route', // 扩展配置文件名称

    'URL_HTML_SUFFIX' => '', // 伪静态后缀名设置
    'TMPL_VAR_IDENTIFY' => 'array', // 点语法的解析
    'URL_MODEL' => 2,   // url展示的形式

    'EACH_PAGE' => 20, // 每一页显示的数目
    'PAGE_NUM' => 10, // 最多显示的页数

    'SHOW_PAGE_TRACE' => true,
    'URL_CASE_INSENSITIVE' => true, //访问的url大小写是否敏感
    'URL_ROUTER_ON' => true, // 开启路由

    'MODULE_ALLOW_LIST' => array('Home', 'Zadmin', 'Exam', 'Teacher'),

    'ISDEBUG' => true,

);