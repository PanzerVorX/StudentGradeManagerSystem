<!DOCTYPE html>
<html>
<head>
	<title>课程信息查询</title>
	<style type="text/css">
		.table1{
			padding-top: 40px;
			margin: auto;
			font-size: 18px;
			font-weight: bold;
		}
		.table2{
			width: 600px;
			padding-top: 80px;
			margin: auto;
			font-size: 15px;
			font-weight: bold;
		}
		.table2 td{
			border: 2px solid blue;
			width: 100px;
			height: 50px;
			text-align: center;
		}
	</style>
</head>
<body>
	<form method="get">
		<table class="table1">
			<tr><td>课程号：</td><td><input type="text" name="KCH"></td><td style="padding-left: 40px">课程名：</td><td><input type="text" name="KCM"></td><td><input type="submit" name="ok" value="查询"></td></tr>
		</table>
	</form>
</body>
</html>
<?php
	include "connectSQL.php";
	if(isset($_GET["ok"])){
		$KCH=$_GET["KCH"];
		$KCM=$_GET["KCM"];
		$sql="select * from KCB ";
		$isFstCondition=true;
		if($KCH){
			$isFstCondition=false;
			$sql.="where KCH like '%{$KCH}%' ";
		}
		if($KCM){
			if($isFstCondition){
				$sql.="where KCM like '%{$KCM}%' ";
			}
			else{
				$sql.="and KCM like '%{$KCM}%' ";
			}
		}
		$countSql=substr_replace($sql,"select count(*) ",0,strpos($sql,'from'));
		$smt=$pdo->query($countSql);
		$totalCount=$smt->fetchColumn(0);//总记录条数
		$pageRow=5;//页规定数
		$totalPage=ceil($totalCount/$pageRow);//总页数
		$nowPage=(isset($_GET["nowPage"])?$_GET["nowPage"]:1);//当前页数
		$nowPageFstRow=($nowPage-1)*$pageRow;//当前页首行记录数
		$sql.="limit {$nowPageFstRow},{$pageRow}";
		$smt=$pdo->query($sql);
		$result=$smt->fetchAll();
		if($result){
			echo "<table class='table2'>";
			echo "<tr><th>课程号</th><th>课程名</th><th>开课学期</th><th>学时</th><th>学分</th></tr>";
			foreach ($result as $key => $value) {
				echo "<tr><td>{$value["KCH"]}</td><td>{$value["KCM"]}</td><td>{$value["KKXQ"]}</td><td>{$value["XS"]}</td><td>{$value["XF"]}</td></tr>";
			}
			echo "<table>";
			$Pagination="";
			$url="queryCourse.php?ok=ok&KCH={$KCH}&KCM={$KCM}";
			if($nowPage>1){
				$tempStr=$url."&nowPage=".($nowPage-1);
				$Pagination.=" <a href='{$tempStr}'>上一页</a> ";
			}
			for($i=1;$i<=$totalPage;$i++){
				if($nowPage==$i){
					$Pagination.=" <b>$i</b> ";
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
			echo "<div style='text-align:center;position:absolute;left:660px;top:500px'>".$Pagination."<div>";
		}
		else{
			echo "<script>alert('无信息');window.location='queryCourse.php';</script>";
		}
	}
?>