<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'medicine',          // 数据库名
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  't_' ,          //表前缀
    'TMPL_L_DELIM'          =>  '~@',            // 模板引擎普通标签开始标记
    'TMPL_R_DELIM'          =>  '@~',            // 模板引擎普通标签结束标记 
    'TMPL_CACHE_ON'         =>  false,        // 是否开启模板编译缓存,设为false则每次都会重新编译
);