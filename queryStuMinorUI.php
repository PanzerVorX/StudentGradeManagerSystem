<?php
	include "connectSQL.php";
	session_start();
	if(isset($_GET["ok"])){
		$XH=$_GET["XH"];
		$_SESSION['number']=$XH;
		$sql="select BZ,ZP from XSB where XH={$XH}";
		$smt=$pdo->query($sql);
		$row=$smt->fetch();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>备注与照片信息</title>
	<style type="text/css">
	table{
		width: 320px;
	}
	td{
		font-size: 20px;
		text-align: center;
		border:2px solid blue;
	}
	</style>
</head>
<body>
	<table>
		<tr><td>附加信息</td></tr>
		<tr><td>备注</td></tr>
		<tr><td><textarea rows="6" cols="40" readonly><?php echo @$row["BZ"]; ?></textarea></td></tr>
		<tr><td>照片</td></tr>
		<tr><td style="height: 150PX">
		<?php
			if(@$row["ZP"]){
				echo "<img src='showPicture.php?time=".time()."'>";
			}
			else{
				echo "<div>暂无照片</div>";
			}
		?>
		</td></tr>
	</table>
</body>
</html>