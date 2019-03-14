<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		.table1{
			padding-top: 100px;
			margin: auto;
		}
		.table1 th{
			font-size: 32px;
		}
		.table1 td{
			font-size: 24px;
			padding-top: 20px;
		}
	</style>
</head>
<body>
</body>
</html>
<?php
	include "connectSQL.php";
	if(isset($_GET["ok"])){
		$KCM=$_GET["KCM"];
		$ZY=$_GET["ZY"];
		$nowPage=$_GET["nowPage"];
		echo "<form method='get'>";
		echo "<table class='table1'>";
		echo "<tr><th colspan=2>添加学生信息</th></tr>";
		echo "<tr><td>学号：</td><td><input type='text' name='XH'></td><input type='hidden' name=KCM value={$KCM}><input type='hidden' name=ZY value={$ZY}><input type='hidden' name='nowPage' value='{$nowPage}'></tr>";
		echo "<tr><td>课程号：</td><td><input type='text' name='stuKCH'></td></tr>";
		echo "<tr><td>成绩：</td><td><input type='text' name='CJ'></td></tr>";
		echo "<tr><td><input type='submit' name='add' value='添加'></td><td><input type='button' name='back' value='返回' onclick=window.location='addGrade.php'></td></tr>";
		echo "</table>";
		echo "</form>";
	}
	if(isset($_GET["add"])){
		$KCM=$_GET["KCM"];
		$ZY=$_GET["ZY"];
		$nowPage=$_GET["nowPage"];
		$stuKCH=$_GET["stuKCH"];
		$XH=$_GET["XH"];
		$CJ=$_GET["CJ"];
		$sql="insert into CJB (KCH,XH,CJ) values('{$stuKCH}','{$XH}','{$CJ}')";
		$result=$pdo->exec($sql);
		if($result){
			echo "<script>alert('添加成功');window.location='addGrade.php?ok=ok&KCM={$KCM}&ZY={$ZY}&nowPage={$nowPage}';</script>";
		}
		else{
			echo "<script>alert('添加失败');window.location='addGrade.php?ok=ok&KCM={$KCM}&ZY={$ZY}&nowPage={$nowPage}';</script>";
		}
	}
?>