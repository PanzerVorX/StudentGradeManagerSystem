<!DOCTYPE html>
<html>
<head>
	<title>学生学分排序</title>
	<style type="text/css">
		.table1{
			margin: auto;
			font-size: 18px;
			font-weight: bold;
		}
		.table2{
			width: 600px;
			margin: auto;
			margin-top: 12px;
		}
		.table2 td{
			border: 2px solid blue;
			width: 70px;
			height: 38px;
			text-align: center;
		}
	</style>
</head>
<body>
	<form method="get">
		<table class="table1">
			<tr>
				<td>专业：</td>
				<td>
					<select name="ZY">
					<option>所有专业</option>
					<?php
						include "connectSQL.php";
						$sql="select distinct ZY from XSB";
						$smt=$pdo->query($sql);
						$result=$smt->fetchAll();
						foreach ($result as $key => $value) {
							echo "<option>{$value['ZY']}</option>";
						}
					?>
					</select>
				</td>
				<td style="padding-left: 30px;">排序方式：</td>
				<td>
					<select name="sortType">
					<option>升序</option>
					<option>降序</option>
					</select>
				</td>
				<td><input type="submit" name="ok" value="排序"></td>
			</tr>
		</table>
	</form>
</body>
</html>
<?php
	include "connectSQL.php";
	if(isset($_GET["ok"])){
		$sql="select XH,XM,XB,CSSJ,ZY,ZXF from XSB ";
		$ZY=$_GET["ZY"];
		$sortType=$_GET["sortType"];
		if($ZY!="所有专业"){
			$sql.="where ZY='{$ZY}' ";
		}
		if($sortType=="升序"){
			$sql.="order by ZXF asc,XH asc ";
		}
		elseif ($sortType=="降序") {
			$sql.="order by ZXF desc,XH asc ";
		}
		$countSql=substr_replace($sql,"select count(*) ",0,strpos($sql,'from'));
		$smt=$pdo->query($countSql);
		$pageRow=10;//页规定数
		$totalCount=$smt->fetchColumn(0);//总记录数
		$pageCount=ceil($totalCount/$pageRow);//总页数
		$nowPage=(isset($_GET["nowPage"]))?$_GET["nowPage"]:1;//当前页
		$nowPageFstRow=($nowPage-1)*10;//当前页首行数
		$sql.="limit {$nowPageFstRow},{$pageRow}";
		$smt=$pdo->query($sql);
		$result=$smt->fetchAll();
		if($result){
			echo "<table class='table2'>";
			echo "<tr><td>学号</td><td>姓名</td><td>性别</td><td>出生时间</td><td>专业</td><td>总学分</td></tr>";
			foreach ($result as $key => $value) {
				echo "<tr><td><a href='creditSortMinorUI.php?ok=ok&XH={$value['XH']}' target='creditSortMinorFrame'>{$value["XH"]}</a><td>{$value['XM']}</td><td>".($value['XB']==1?'男':'女')."</td><td>{$value['CSSJ']}</td><td>{$value['ZY']}</td><td>{$value['ZXF']}</td></tr>";
			}
			echo "<table>";
			$url="creditSortMainUI.php";
			$getStr="?ok=ok"."&ZY=".$ZY."&sortType=".$sortType;
			$Pagination="";
			if ($nowPage>1) {
				$tempStr=$getStr."&nowPage=".($nowPage-1);
				$Pagination.="<a href='{$url}{$tempStr}'>上一页</a>";
			}
			for($i=1;$i<=$pageCount;$i++){
				if($nowPage==$i){
					$Pagination.="<b>{$i}</b>";
				}
				else{
					$tempStr=$getStr."&nowPage=".$i;
					$Pagination.=" <a href='{$url}{$tempStr}'>$i</a> ";
				}
			}
			if($nowPage<$pageCount){
				$tempStr=$getStr."&nowPage=".($nowPage+1);
				$Pagination.=" <a href='{$url}{$tempStr}'>下一页</a> ";
			}

			echo "<div style='margin-top:10px;text-align:center;position:absolute;left:460px;top:550px'>{$Pagination}</div>";
		}
	}
?>