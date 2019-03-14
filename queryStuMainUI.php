<!DOCTYPE html>
<html>
<head>
	<title>学生信息查询</title>
	<style type="text/css">
		.table1{
			width: 600px;
			margin: auto;
			margin-top: 12px;
		}
		.table1 td{
			border: 2px solid blue;
			width: 80px;
			height: 40px;
			text-align: center;
		}
	</style>
</head>
<body>
	<form method="get" style="text-align: center;">
		<table style="margin: auto;">
		<tr>
			<td>学号：</td>
			<td><input type="text" name="XH"></td>
			<td>姓名：</td>
			<td><input type="text" name="XM"></td>
			<td>专业：</td>
			<td>
			<select name="ZY">
			<?php
				include "connectSQL.php";
				$sql="select distinct ZY from XSB";
				$smt=$pdo->query($sql);
				$ZYArr=$smt->fetchAll();
				foreach ($ZYArr as $key => $value) {
					echo "<option>{$value["ZY"]}</option>";
				}
			?>	
			</select>
			</td>
			<td><input type="submit" name="ok" value="查询"></td>
		</tr>
		</table>
	</form>
</body>
</html>
<?php
	if(isset($_GET["ok"])){
		$XH=$_GET["XH"];
		$XM=$_GET["XM"];
		$ZY=$_GET["ZY"];
		$sql="select XH,XM,XB,CSSJ,ZY,ZXF,BZ,ZP from XSB ";
		$isFrtCondition=true;
		if($XH){
			$isFrtCondition=false;
			$sql=$sql."where XH like '%{$XH}%' "; 
		}
		if($XM){
			if($isFrtCondition){
				$isFrtCondition=false;
				$sql=$sql."where XM like '%{$XM}%' ";
			}
			else{
				$sql=$sql."and XM like '%{$XM}%' ";
			}
		}
		if($ZY){
			if($isFrtCondition){
				$isFrtCondition=false;
				$sql=$sql."where ZY like '%{$ZY}%' ";
			}
			else{
				$sql=$sql."and ZY like '%{$ZY}%' ";
			}
		}
		$countSql=substr_replace($sql,"select count(*) ",0,strpos($sql,'from'));
		$smt=$pdo->query($countSql);
		$totalCount=$smt->fetchColumn(0);//记录总行数
		$pageRow=10;//页规定数
		$totalPage=ceil($totalCount/$pageRow);//总页数
		$nowPage=(isset($_GET["nowPage"]))?$_GET["nowPage"]:1;//当前页
		$currentFstRow=(intval($nowPage)-1)*$pageRow;//当前页首行记录序数
		$sql=$sql."limit {$currentFstRow},{$pageRow}";//查找当前页所有记录
		$smt=$pdo->query($sql);
		$result=$smt->fetchAll();
		if($result){
			echo "<table class='table1' border=2px; bordercolor=blue>";
			echo "<tr><th>学号</th><th>姓名</th><th>性别</th><th>出生时间</th><th>专业</th><th>总学分</th></tr>";
			foreach ($result as $key => $value) {
				//页面框架中内部页面间传值刷新显示数据：①传值页面：链接<a>的地址设置为带参数的目的内部页面地址  ②目的页面：方式设置为目的内部页面的区域索引
				echo "<tr><td><a href='queryStuMinorUI.php?ok=ok?&XH={$value['XH']}' target='queryStuMinorFrame'>{$value['XH']}</a></td><td>{$value['XM']}</td><td>".($value['XB']==1?'男':'女')."</td><td>{$value['CSSJ']}</td><td>{$value['ZY']}</td><td>{$value['ZXF']}</td></tr>";
			}
			echo "</table>";
			//分页查询过程：①通过count()查询对应条件额的总记录条数（获取总页数） ②通过limit限定查询当前页的记录
			//分页查询刷新显示所需参数：①查询条件参数（若存在筛选条件） ②当前页数（无则表示首次查询，默认值为首页） 
			//分页导航条的实现：①get方式：通过各操作对应参数的链接/[也可通过表单（不常见）] ②post方式：各操作作为单独表单（对应参数存入隐藏域）  ③ajax方式：通过响应方法在JS函数中访问php文件
			$url="queryStuMainUI.php";
			$getStr="ok=ok&"."XH=".$XH."&XM=".$XM."&ZY=".$ZY;
			$Pagination="";
			$nowPage=intval($nowPage);
			if($nowPage>1){//当前页大于1页时存在上一页操作
				$tempStr=$getStr."&nowPage=".($nowPage-1);
				$Pagination.="<a href='{$url}?{$tempStr}'>上一页</a>";
			}
			for($i=1;$i<=$totalPage;$i++){//每页的链接
				if($nowPage==$i){
					$Pagination.="<b>{$i}</b>";
				}
				else{
					$tempStr=$getStr."&nowPage=".$i;
					$Pagination.=" <a href='{$url}?{$tempStr}'>{$i}</a> ";
				}
			}
			if($nowPage<$totalPage){//当前页不为尾页时
				$tempStr=$getStr."&nowPage=".($nowPage+1);
				$Pagination.="<a href='{$url}?$tempStr}'>下一页</a>";
			}
			echo "<div style='margin-top:20px;text-align:center;position:absolute;left:460px;top:550px'>".$Pagination."<div>";
		}
		else{
			echo "<script>alert('无记录')</script>";
		}
	}
?>