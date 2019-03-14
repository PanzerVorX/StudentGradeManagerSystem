<!DOCTYPE html>
<html>
<head>
	<title>成绩信息录入</title>
	<style type="text/css">
		.table1{
			padding-top: 20px;
			margin: auto;
		}
		.table1 td{
			padding-top: 20px;
		}
		.table2{
			padding-top: 20px;
			margin: auto;
			width: 600px;
			text-align: center;
		}
		.table2 td{
			border: 2px solid blue;
		}

	</style>
</head>
<body>
	<form method="get">
		<table class="table1">
			<tr><th colspan="5" style="font-size: 30px">成绩信息录入</th></tr>
			<tr><td>课程名：</td>
				<td><select name="KCM">
					<option>请选择</option>
					<?php
						include "connectSQL.php";
						$sql="select KCM from KCB";
						$smt=$pdo->query($sql);
						$result=$smt->fetchAll();
						if (@$_GET["KCM"]=="所有课程") {
							echo "<option selected>所有课程</option>";
						}
						else{
							echo "<option>所有课程</option>";
						}
						foreach ($result as $key => $value) {
							if (@$_GET["KCM"]==$value["KCM"]) {
								echo "<option selected>{$value['KCM']}</option>";
							}
							else{
								echo "<option>{$value['KCM']}</option>";
							}
						}
					?>
				</select></td>
				<td>专业：</td>
				<td><select name="ZY">
					<option>请选择</option>
					<?php
						$sql="select distinct ZY from XSB";
						$smt=$pdo->query($sql);
						$result=$smt->fetchAll();
						if (@$_GET["ZY"]=="所有专业") {
							echo "<option selected>所有专业</option>";
						}
						else{
							echo "<option>所有专业</option>";
						}
						foreach ($result as $key => $value) {
							if (@$_GET["ZY"]==$value["ZY"]) {
								echo "<option selected>{$value['ZY']}</option>";
							}
							else{
								echo "<option>{$value['ZY']}</option>";
							}
						}
					?>
				</select></td>
				<td><input type="submit" name="ok" value="查询"></td>
			</tr>
		</table>
	</form>
</body>
</html>
<?php
	if(isset($_GET["ok"])){
		$KCM=$_GET["KCM"];
		$ZY=$_GET["ZY"];
		if(($KCM=="请选择")||($ZY=="请选择")){
			echo "<script>alert('未选择');window.location='addGrade.php';</script>";
		}
		$sql="select * from XS_KC_CJ ";
		$ifFstCondition=true;
		if($KCM!="所有课程"){
			$ifFstCondition=false;
			$sql.="where KCM='{$KCM}' ";
		}
		if($ZY!="所有专业"){
			if($ifFstCondition){
				$sql.="where ZY='{$ZY}' ";
			}
			else{
				$sql.="and ZY='{$ZY}' ";
			}
		}
		$countSql=substr_replace($sql,"select count(*) ",0,strpos($sql,'from'));
		$smt=$pdo->query($countSql);
		$totalCount=$smt->fetchColumn(0);//总页数
		$pageCount=10;//页规定数
		$totalPage=ceil($totalCount/$pageCount);//总页数
		$nowPage=(isset($_GET["nowPage"]))?$_GET["nowPage"]:1;
		$nowPageFstRow=($nowPage-1)*$pageCount;//当前页首行记录序数
		$sql.="limit {$nowPageFstRow},{$pageCount}";//限定查询
		$smt=$pdo->query($sql);
		$result=$smt->fetchAll();
		if($result){
			echo "<table class=table2>";
			echo "<tr><th>课程名</th><th>学号</th><th>姓名</th><th>成绩</th><th colspan=2>操作</th><th></th></tr>";
			foreach ($result as $key => $value) {
				echo "<tr><td>{$value["KCM"]}</td><td>{$value["XH"]}</td><td>{$value["XM"]}</td><td>{$value['CJ']}</td><td><a href='addGradeUpdate.php?ok=ok&KCM={$KCM}&ZY={$ZY}&nowPage={$nowPage}&stuKCM={$value["KCM"]}&XH={$value["XH"]}&XM={$value["XM"]}&CJ={$value["CJ"]}'>修改</a></td><td><a href='addGradeDelete.php?ok=ok&KCM={$KCM}&ZY={$ZY}&nowPage={$nowPage}&stuKCM={$value["KCM"]}&XH={$value["XH"]}'>删除</a></td></tr>";
			}
			echo "<table>";
			$url="addGrade.php?ok=ok&KCM={$KCM}&ZY={$ZY}";
			$Pagination="";
			if($nowPage>1){
				$tempStr=$url."&nowPage=".($nowPage-1);
				$Pagination.=" <a href='{$tempStr}'>上一页</a> ";
			}
			for($i=1;$i<=$totalPage;$i++){
				if($nowPage==$i){
					$Pagination.=" <b>{$i}</b> ";
				}
				else{
					$tempStr=$url."&nowPage={$i}";
					$Pagination.=" <a href='{$tempStr}'>{$i}</a> ";
				}
			}
			if($nowPage<$totalPage){
				$tempStr=$url."&nowPage=".($nowPage+1);
				$Pagination.=" <a href='{$tempStr}'>下一页</a> ";
			}
			$Pagination.=" <a href='addGradeAdd.php?ok=ok&KCM={$KCM}&ZY={$ZY}&nowPage={$nowPage}'>添加</a> ";
			echo "<div style='text-align:center;position:absolute;left:660px;top:500px'>".$Pagination."<div>";
		}
		else{
			echo "<script>alert('无成绩');window.location='addGrade.php';</script>";
		}
	}
	else{
		$addStr=" <a href='addGradeAdd.php?ok=ok&KCM=请选择&ZY=请选择&nowPage=1'>添加</a> ";
		echo "<div style='text-align:center;position:absolute;left:660px;top:500px'>".$addStr."<div>";
	} 
?>
