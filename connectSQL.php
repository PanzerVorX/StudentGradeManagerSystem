<?php
	//连接数据库
	$pdo=new PDO('mysql:host=localhost;dbname=pxscj2','root','');
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
	$pdo->exec("set names utf8");
?>