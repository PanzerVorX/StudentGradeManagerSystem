<?php
	//返回学生照片
	include "connectSQL.php";
	session_start();
	header("Content-type:image/png");//设置显示图片为png格式
	//获取学生对应的照片数据
	$number=$_SESSION["number"];
	$sql="select ZP from XSB where XH='$number'";
	$smt=$pdo->query($sql);
	$row=$smt->fetch();
	echo $row["ZP"];//输出照片
?>