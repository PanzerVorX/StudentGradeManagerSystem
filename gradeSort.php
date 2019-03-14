<!DOCTYPE html>
<html>
<head>
	<title>课程成绩排序</title>
	<style type="text/css">
		.table1{
			margin: auto;
			width: 500px;
		}
		.table2{
			width: 600px;
			padding-top: 40px;
			margin: auto;
			font-size: 15px;
			font-weight: bold;
		}
		.table2 td{
			border: 2px solid blue;
			width: 100px;
			height: 40px;
			text-align: center;
		}
	</style>
</head>
<body>
	<form method="get">
		<table class="table1">
			<caption><font style="font-size: 40px;">课程成绩排序</font></caption>
			<tr><td>课程名：</td>
			<td><select name="KCM">
				<option>请选择</option>
				<?php 
					//根据接收表单参数刷新查询页面时保持条件输入/选择控件显示对应条件：根据表单参数中的条件参数设置条件输入/选择控件的被输入/被选中条件（AJAX方式更新页面显示内容则无需考虑）
					include "connectSQL.php";
					$sql="select * from KCB";
					$smt=$pdo->query($sql);
					$result=$smt->fetchAll();
					foreach ($result as $key => $value) {
						echo $value['KCM'];
						if($value['KCM']==@$_GET['KCM']){
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
					if(@$_GET["ZY"]=="所有专业"){
						echo "<option selected>所有专业</option>";
					}
					else{
						echo "<option>所有专业</option>";
					}
					$sql="select distinct ZY from XSB";
					$smt=$pdo->query($sql);
					$result=$smt->fetchAll();
					foreach ($result as $key => $value) {
						if($value['ZY']==@$_GET['ZY']){
							echo "<option selected>{$value['ZY']}</option>";
						}
						else{
							echo "<option>{$value['ZY']}</option>";
						}
					}
				?>	
			</select></td>
			<td>排序：</td>
			<td><select name="sortType">
				<option>升序</option>
				<option>降序</option>
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
		$sortType=$_GET["sortType"];
		if(($KCM=="请选择")||($ZY=="请选择")){
			//跳转本体页面刷新可用于排除无效（无法进行）的操作条件
			echo "<script>alert('未选择课程或专业');window.location='gradeSort.php'</script>";
		}
		$sql="select * from XS_KC_CJ where KCM='{$KCM}' ";
		if($ZY!='所有专业'){
			$sql.="and ZY='{$ZY}' ";
		}
		if($sortType=="升序"){
			$sql.="order by CJ asc,XH asc ";
		}
		else{
			$sql.="order by CJ desc,XH asc ";
		}
		$countSql=substr_replace($sql,"select count(*) ",0,strpos($sql,'from'));
		$smt=$pdo->query($countSql);
		$totalCount=$smt->fetchColumn(0);//总记录数
		$pageCount=8;//页规定数
		$totalPage=ceil($totalCount/$pageCount);//页数
		$nowPage=(isset($_GET["nowPage"]))?$_GET["nowPage"]:1;//当前页数
		$nowPageFstRow=($nowPage-1)*$pageCount;//当前页首行记录
		$sql.="limit {$nowPageFstRow},{$pageCount}";
		$smt=$pdo->query($sql);
		$result=$smt->fetchAll();
		if($result){
			echo "<table class='table2'>";
			echo "<tr><th>课程号</th><th>课程名</th><th>学号</th><th>姓名</th><th>专业</th><th>成绩</th></tr>";
			foreach ($result as $key => $value) {
				echo "<tr><td>{$value['KCH']}</td><td>{$value['KCM']}</td><td>{$value['XH']}</td><td>{$value['XM']}</td><td>{$value['ZY']}</td><td>{$value['CJ']}</td></tr>";
			}
			echo "</table>";
			$url="gradeSort.php?ok=ok&KCM={$KCM}&ZY={$ZY}&sortType={$sortType}";
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
					$Pagination.=" <a href='$tempStr'>{$i}</a> ";
				}
			}
			if($nowPage<$totalPage){
				$tempStr=$url."&nowPage=".($nowPage+1);
				$Pagination.=" <a href='{$tempStr}'>下一页</a> ";
			}
			echo "<div style='text-align:center;position:absolute;left:660px;top:550px'>".$Pagination."<div>";
		}
	}
?>