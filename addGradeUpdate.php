<!DOCTYPE html>
<html>
<head>
	<title>修改学生成绩</title>
	<style type="text/css">
		.table1{
			margin: auto;
			margin-top: 100px;
			font-size: 20px;
			font-weight: solid;
		}
		.table1 td{
			padding: 10px;
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
		$stuKCM=$_GET["stuKCM"];
		$nowPage=$_GET["nowPage"];
		$XH=$_GET["XH"];
		$XM=$_GET["XM"];
		$CJ=$_GET["CJ"];
		echo "<form method='get'>";
		echo "<table class='table1'>";
		echo "<tr><td>学号：</td><td><input type='text' name='XH' value={$XH} readonly><input type='hidden' name='nowPage' value={$nowPage}><input type='hidden' name='KCM' value={$KCM}><input type='hidden' name='ZY' value={$ZY}></td></tr>";
		echo "<tr><td>姓名：</td><td><input type='text' name='XM' value={$XM} readonly></td></tr>";
		echo "<tr><td>课程名：</td><td><input type='text' name='stuKCM' value={$stuKCM} readonly></td></tr>";
		echo "<tr><td>成绩：</td><td><input type='text' name='CJ' value={$CJ} ></td></tr>";
		echo "<tr><td><input type='submit' name='update' value='提交'></td><td><input type='button' name='back' value='返回' onclick=window.location='addGrade.php'></td></tr>";
	}
	if(isset($_GET["update"])){
		$KCM=$_GET["KCM"];
		$ZY=$_GET["ZY"];
		$nowPage=$_GET["nowPage"];
		$stuKCM=$_GET["stuKCM"];
		$XH=$_GET["XH"];
		$CJ=$_GET["CJ"];
		$sql="select KCH from KCB where KCM='{$stuKCM}'";
		$smt=$pdo->query($sql);
		$stuKCH=$smt->fetchColumn(0);
		$sql="update CJB set CJ='{$CJ}' where KCH='{$stuKCH}' and XH='{$XH}'";
		echo $KCM;
		echo $ZY;
		$result=$pdo->exec($sql);
		if($result){
			echo "<script>alert('更新成功');window.location='addGrade.php?ok=ok&KCM={$KCM}&ZY={$ZY}&$nowPage={$nowPage}';</script>";
		}
		else{
			echo "<script>alert('更新失败');window.location='addGrade.php?ok=ok&KCM={$KCM}&ZY={$ZY}&$nowPage={$nowPage}';</script>";
		}
	}
?>