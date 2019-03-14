<!DOCTYPE html>
<html>
<head>
	<title>课程信息录入</title>
	<style type="text/css">
		body{
			background-color: gray;
		}
		.table1{
			padding-top: 40px;
			margin: auto;
			font-size: 18px;
			font-weight: bold;
		}
		.table2{
			padding-top: 30px;
			margin: auto;
			font-size: 18px;
			font-weight: bold;
		}
		.table2 td{
			height: 50px;
		}
	</style>
</head>
<body>
	<form name="fm1" method="post">
		<table class="table1">
			<tr><td>课程号：</td><td><input type="text" name="num"></td><td><input type="submit" name="ok" value="查询"></td></tr>
		</table>
	</form>
	<?php
		include "connectSQL.php";
		$num=@$_POST["num"];
		$sql="select * from KCB where KCH='{$num}'";
		$smt=$pdo->query($sql);
		$row=$smt->fetch();
		//复用界面：根据页面接收参数改变原有（固定不变）的界面控件的显示数据（显示数据的关联参数接收变量需使用错误控制符防止首次开启页面时未传参数）
		//复用界面中根据接收参数变量判断是否提交：未提交时接收参数变量值为null（未被初始化），提交时接收参数的变量值为非null（已被初始化）
		if(($num!==null)&&(!$row)){
			echo "<script>alert('无该课程号');window.location='addCourse.php';</script>";
		}
	?>
	<form name="fm2" method="post">
		<table class="table2" cellpadding="0px" cellspacing="0px">
			<tr><td>课程号：</td><td style="width: 350px;text-align: center;"><input style="width: 300px" type="text" name="KCH" value='<?php echo $row["KCH"]; ?>'><input type="hidden" name="checkKCH" value='<?php echo $row["KCH"]; ?>'></td></tr>
			<tr><td>课程名：</td><td style="width: 350px;text-align: center;"><input style="width: 300px" type="text" name="KCM" value='<?php echo $row["KCM"]; ?>'></td></tr>
			<tr><td>开课学期：</td><td style="width: 350px;text-align: center;"><input style="width: 300px" type="text" name="KKXQ" value=<?php echo $row["KKXQ"]; ?>></td></tr>
			<tr><td>学时：</td><td style="width: 350px;text-align: center;"><input style="width: 300px" type="text" name="XS" value=<?php echo $row["XS"]; ?>></td></tr>
			<tr><td>学分：</td><td style="width: 350px;text-align: center;"><input style="width: 300px" type="text" name="XF" value=<?php echo $row["XF"]; ?>></td></tr>
			<tr><td colspan="2"><input style="margin-left: 50px;margin-top: 40px" type="submit" name="ok" value="修改"><input style="margin-left: 40px" type="submit" name="ok" value="添加"><input style="margin-left: 40px" type="submit" name="ok" value="删除"><input style="margin-left: 40px" type="submit" name="ok" value="退出"></td></tr>
		</table>
	</form>
</body>
</html>
<?php
	$KCH=@$_POST["KCH"];
	$checkKCH=@$_POST["checkKCH"];
	$KCM=@$_POST["KCM"];
	$KKXQ=@$_POST["KKXQ"];
	$XS=@$_POST["XS"];
	$XF=@$_POST["XF"];

	function checkInput($KCH,$KCM,$KKXQ,$XS,$XF){
		if(!$KCH){
			echo "<script>alert('课程号不能为空');window.location='addCourse.php';</script>";
		}
		elseif (!$KCM){
			echo "<script>alert('课程名不能为空');window.location='addCourse.php';</script>";
		}
		elseif ((!$KKXQ)||(!is_numeric($KKXQ))){
			echo "<script>alert('开课学期必须为数字');window.location='addCourse.php';</script>";
		}
		elseif((!$XS)||(!is_numeric($XS))){
			echo "<script>alert('学时必须为数字');window.location='addCourse.php';</script>";
		}
		elseif((!$XF)||(!is_numeric($XF))){
			echo "<script>alert('学分必须为数字');window.location='addCourse.php';</script>";
		}
	}
	if(@$_POST["ok"]=="修改") {
		if(!$checkKCH){
			echo "<script>alert('未经查询选定记录');window.location='addCourse.php';</script>";
		}
		checkInput($KCH,$KCM,$KKXQ,$XS,$XF);
		if($KCH!=$checkKCH){
			echo "<script>alert('不能修改课程号');window.location='addCourse.php';</script>";
		}
		$sql="update KCB set KCM='$KCM',KKXQ='$KKXQ',XS='$XS',XF='$XF' where KCH='$checkKCH'";
		$result=$pdo->exec($sql);
		if($result){
			echo "<script>alert('修改成功');window.location='addCourse.php';</script>";
		}
		else{
			echo "<script>alert('修改失败');window.location='addCourse.php';</script>";
		}
	}
	if(@$_POST["ok"]=="添加"){
		checkInput($KCH,$KCM,$KKXQ,$XS,$XF);
		$sql="select * from KCB where KCH='{$KCH}'";
		$smt=$pdo->query($sql);
		$row=$smt->fetch();
		if($row){
			echo "<script>alert('不能添加重复的课程号');window.location='addCourse.php';</script>";
		}
		$sql="insert into KCB (KCH,KCM,KKXQ,XS,XF) values('$KCH','$KCM','$KKXQ','$XS','$XF')";
		$result=$pdo->exec($sql);
		if($result){
			echo "<script>alert('添加成功');window.location='addCourse.php';</script>";
		}
		else{
			echo "<script>alert('添加失败');window.location='addCourse.php';</script>";
		}
	}
	if(@$_POST['ok']=="删除"){
		if(!$checkKCH){
			echo "<script>alert('未经查询选定记录');window.location='addCourse.php';</script>";
		}
		$sql="delete from KCB where KCH='{$KCH}'";
		$result=$pdo->exec($sql);
		if($result){
			echo "<script>alert('删除成功');window.location='addCourse.php';</script>";
		}
		else{
			echo "<script>alert('删除失败');window.location='addCourse.php';</script>";
		}
	}
	if(@$_POST["ok"]=="退出"){
		echo "<script>window.location='frameOperation.php';</script>";
	}	
?>