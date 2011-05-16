<?php
include "config/db_config.php";
include "class/mysql.php";

$DB=new DB_MySQL;
$DB->servername=$db_host;
$DB->dbname=$db_database;
$DB->dbusername=$db_user;
$DB->dbpassword=$db_password;

$DB->connect();
$DB->query("set names 'utf8'");
$DB->selectdb();

if (!$usepconnect) {
        // 在程序运行完毕时, 关闭数据库连接  
        register_shutdown_function(array(&$DB, 'close'));  //相当于注册析构函数
}
?>