<?php
    //连接数据库
    error_reporting(E_ALL ^ E_DEPRECATED);              //错误控制
    header("Content-type:text/html;charset=utf-8");     //客户端发送原始的 HTTP 报头
	
	$conn=mysql_connect('localhost','root','');         //连接MySQL服务器
	mysql_select_db('stu_db',$conn);                    //选择数据库
	
	mysql_query("SET NAMES utf8");                    //设置字符集为utf8
	session_start();                                    //启动session会话
?>