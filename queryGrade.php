<!DOCTYPE html>
<html>
<head>
	<title>学生成绩查询</title>
	<style type="text/css">
		.table1{
			margin:auto;
		}
		.table2{
			margin: auto;
			margin-top: 50px;
			float: left;
			margin-left: 400px;
		}
		.table2 td{
			width: 150px;
			border:2px solid blue;
			text-align: center;
		}
		.table3{
			width: 400px;
			border:2px solid blue;
			text-align: center;
			float: right;
			margin-right:50px;
			margin-top:75px;
		}
		.table3 td{
			width: 150px;
			border:2px solid blue;
			text-align: center;
		}

	</style>
</head>
<body>
	<form method="post">
		<table class='table1'>
			<caption><font style="font-size: 30px;font-weight: bold;">学生成绩查询</font></caption>
			<tr><td>学号：</td><td><input type="input" name="XH"></td><td><input type="submit" name="ok" value="查询"></td></tr>
		</table>
	</form>	
</body>
</html>
<?php
	include "connectSQL.php";
	if(isset($_POST["ok"])){
		$XH=$_POST["XH"];
		if($XH){
			$sql="select * from XS_KC_CJ where XH='{$XH}'";
			$smt=$pdo->query($sql);
			$result=$smt->fetchAll();
			if($result){
				session_start();
				$_SESSION['number']=$XH;
				echo "<table class='table2'>";
				echo "<tr><th>课程号</th><th>课程名</th><th>成绩</th></tr>";
				foreach ($result as $key => $value) {
					echo "<tr><td>{$value['KCH']}</td><td>{$value['KCM']}</td><td>{$value['CJ']}</td></tr>";
				}
				echo "</table>";
				$sql="select * from XSB where XH='{$XH}'";
				$smt=$pdo->query($sql);
				$result=$smt->fetch();
				echo "<table class='table3'>";
				echo "<tr><td style='background-color:gray'>学号：</td></tr>";
				echo "<tr><td>{$result['XH']}</td></tr>";
				echo "<tr><td style='background-color:gray'>姓名：</td></tr>";
				echo "<tr><td>{$result['XM']}</td></tr>";
				echo "<tr><td style='background-color:gray'>总学分：</td></tr>";
				echo "<tr><td>{$result['ZXF']}</td></tr>";
				echo "<tr><td style='background-color:gray'>照片</td></tr>";
				if($result["ZP"])
					echo "<tr><td><img src='showPicture.php'></td></tr>";
				else
					ECHO "<tr><td>暂无</td></tr>";
				echo "</table>";
			}
			else{
				echo "<script>alert('该学号不存在');</script>";
			}
		}
	}
?>