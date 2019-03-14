<!DOCTYPE html>
<html>
<head>
	<title>学生成绩录入</title>
	<script type="text/javascript">

		function getXmlHttpObject(){//获取XMLHttpRequest
			var XMLHttp=null;
			if(window.XMLHttpRequest){
				XMLHttp=new XMLHttpRequest();
			}
			else if(window.ActiveXObject){
				try{
					XMLHttp=new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e){
					XMLHttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
			return XMLHttp;
		}
		
		function deleteZP(){//删除照片
		
			var XMLHttp=getXmlHttpObject();

			var XH=document.getElementById('num').value;
			var postStr="XH="+XH;

			var url="deleteZP.php";
			XMLHttp.open("post",url,true);

			XMLHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			XMLHttp.send(postStr);
			XMLHttp.onreadystatechange=function(){

				if((XMLHttp.readyState==4)&&(XMLHttp.status==200)){
					if(XMLHttp.responseText=="true"){//若删除照片成功
						document.getElementById('showZP').innerHTML="<div>暂无照片</div>";//显示暂无照片
					}
				} 
			}
		}

	</script>
</head>
<body style="background-color: pink">
	<div style="text-align: center;font-size: 30px;font-weight: bold;">学生信息录入</div>
		<form name="frm1" method="post" style="text-align:center;margin-top: 15px">根据学号查找学生信息：<input type="text" name="number"> <input type="submit" name="query" value="查找"></form>
</body>
</html>
<?php
	include 'connectSQL.php';
	//使用session/cookie变量作为同会话下的跨页面共享变量（作用类似于Java中的静态变量）
	session_start();
	$number=@$_POST["number"];
	$_SESSION['number']=$number;//将学号共享给输出图片二进制数据页面用于数据库获得图片二进制数据
	$sql="select * from XSB where XH='$number'";
	$smt=$pdo->query($sql);
	$row=$smt->fetch();//单行遍历结果集
	if(($number!==null)&&(!$row)){//提交的情况下无查询记录
		echo "<script>alert('没有该学生信息');</script>";
	}
?>

<form name="frm2" method="post" style="margin-top: 15px" enctype="multipart/form-data">
	<table style="margin: auto;">
		<tr>
			<td>学号：</td>
			<td>
				<input style="width:300px;" type="text" name="XH" value="<?php echo @$row['XH']; ?>">
				<!--表单核对提交前后的一致性：使用隐藏域保存禁止修改的附加值以传递-->
				<input type="hidden" name="num" id="num" value="<?php echo @$row['XH']; ?>">
			</td>
		</tr>
		<tr>
			<td>姓名：</td>
			<td><input style="width:300px;" type="text" name="XM" value="<?php echo @$row['XM']; ?>"></td>
		</tr>
		<tr>
			<td>性别：</td>
			<td>
				<?php 
					if(@$row['XB']==0){
						echo "<input type='radio' name='XB' value=1 >男";
						echo "<input type='radio' name='XB' value=0 checked>女";
					}
					else{
						echo "<input type='radio' name='XB' value=1 checked>男";
						echo "<input type='radio' name='XB' value=0 >女";
					}
				?>
			</td>
		</tr>
		<tr>
			<td>出生日期：</td>
			<td><input style="width:300px;" type="text" name="CSSJ" value="<?php echo @$row['CSSJ']; ?>"></td>
		</tr>
		<tr>
			<td>专业：</td>
			<td>
				<input style="width:300px;" type="text" name="ZY" value="<?php echo @$row["ZY"]; ?>">
			</td>
		</tr>
		<tr>
			<td>总学分：</td>
			<td><input style="width:300px;" type="text" name="ZXF" value="<?php echo @$row["ZXF"]; ?>"></td>
		</tr>
		<tr>
			<td>备注：</td>
			<!--文本域的值为其下属的内容-->
			<td><textarea rows="6" cols="40" type="text" name="BZ"><?php echo @$row["BZ"]; ?></textarea>
		</tr>
		<tr>
			<td style="height: 150px">学生照片：</td>
			<td name="showZP" id="showZP">
				<?php
					if(@$row["ZP"]){
						//浏览器为了提高访问相同页面的速度会自动缓存页面数据，当访问相同页面时浏览器可能不发送请求转而复用缓存数据进行返回
						//防止浏览器复用缓存：访问相同页面时在地址参数中添加随机数使浏览器视为访问不同页面
						echo "<img src='showPicture.php?time=".time()."'>";//显示二进制数据形式的图片：<img>的src设置为 以指定格式将图片二进制数据输出为结果图片的页面(原理为调用src的php文件获取其执行结果)
					}
					else{
						echo "<div>暂无照片</div>";
					}
				?>
			</td>
		</tr>
		<tr>
			<!--实现在html标签的点击事件中间接调用PHP函数：在标签响应事件调用的JS函数中使用AJAX与PHP页面交互-->
			<td><input type="button" value="删除照片" onclick="deleteZP()"></td><!--元素名与响应函数名不能重名，否则元素的onclick属性失效-->
			<td>
				<input style="margin-left: 80px" type="file" name="ZP">
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="submit" name="ok" value="修改" style="margin-top: 20px"><input type="submit" name="ok" value="添加" style="margin-left: 20px"> <input type="submit" name="ok" value="删除" style="margin-left: 20px"> <input type="submit" name="ok" value="退出" style="margin-left: 20px">
			</td>
		</tr>
	</table>
</form>
<?php
	$num=@$_POST["num"];
	$XH=@$_POST["XH"];
	$XM=@$_POST["XM"];
	$XB=@$_POST["XB"];
	$CSSJ=@$_POST["CSSJ"];
	$ZY=@$_POST["ZY"];
	$ZXF=@$_POST["ZXF"];
	$BZ=@$_POST["BZ"];
	$ZP=@$_POST["ZP"];
	$temp_file=@$_FILES["ZP"]["tmp_name"];//临时文件
	
	$checKCSSJ=preg_match('/^\d{4}-(0?\d|1?[012])-(0?\d|[12]\d|3[01]) $/',$CSSJ);//验证出生日期

	function checkInput($XH,$XM,$checkCSSJ,$temp_file){//检测输入值格式并上传缩放图片
		if($XH==""){
			echo "<script>alert('学号不能为空');window.location='addStu.php';</script>";
		}
		elseif($XM==""){
			echo "<script>alert('姓名不能为空');window.location='addStu.php';</script>";
		}
		elseif ($checkCSSJ) {
			echo "<script>alert('日期格式不正确');window.location='addStu.php';</script>";
		}
		else{
			if($temp_file){//若上传了照片
				$type=@$_FILES['ZP']['type'];//获取文件格式
				$size=@$_FILES['ZP']['size'];//获取文件大小
				if(($type!="image/jpeg")&&($type!="image/pjpeg")){
					echo "<script>aleet('图片文件格式不正确');window.location='addStu.php';</script>";
				}
				elseif ($size>100000) {
					echo "<script>aleet('图片太大，无法上传');window.location='addStu.php';</script>";
				}
				else{//缩放图片并存储
					move_uploaded_file($_FILES["ZP"]["tmp_name"],'upFile.jpeg');
					$image=imagecreatefromjpeg('upFile.jpeg');
					$bg_w=100;$bg_h=120;
					$src_w=imagesx($image);$src_h=imagesy($image);
					$bgImage=imagecreate($bg_w,$bg_h);
					imagecopyresized($bgImage,$image,0,0,0,0,$bg_w,$bg_h,$src_w,$src_h);
					imagejpeg($bgImage,'upFile.jpeg');
					imagedestroy($image);
					imagedestroy($bgImage);
				}
			}
		}
	}

	if(@$_POST["ok"]=="修改"){//多提交按钮的表单应根据提交按钮标签的值判断模式，不能根据键判断（同表单的所有元素都会被提交（包括其它提交标签））
		
		if(!$num){
			echo "<script>alert('未经查询选定记录');window.location='addStu.php';</script>";
		}

		checkInput($XH,$XM,$checkCSSJ,$temp_file);//检测输入值格式并上传缩放图片
		
		$handle=@fopen('upFile.jpeg','r');//获取图片的文件句柄
		//防止特殊字符串干扰sql语句：将字符串内容中的所有引号加上转义符：addslashes()，（取消转义符：stripslashes()） 
		$picture=@addslashes(fread($handle,filesize('upFile.jpeg')));//获取文件的二进制数据
		fclose($handle);
		unlink('upFile.jpeg');//获取图片二进制数据后删除图片
		
		if($XH!=$num){//禁止修改学号，防止成绩表记录随修改变动
			echo "<script>alert('不能修改学号');location.href('addStu.php');</script>";//JS方式跳转页面：window.location=页面地址/location.href(页面地址)
		}
		elseif($temp_file){//若上传了图片
			$update_sql="update XSB set XM='$XM',XB='$XB',CSSJ='$CSSJ',ZY='$ZY',ZXF='$ZXF',BZ='$BZ',ZP='$picture' where XH='$XH'";
		}
		else{//若未上传图片
			$update_sql="update XSB set XM='$XM',XB='$XB',CSSJ='$CSSJ',ZY='$ZY',ZXF='$ZXF',BZ='$BZ' where XH='$XH'";
		}
		$result=$pdo->exec($update_sql);
		if($result){
			echo "<script>alert('修改成功');window.location='addStu.php';</script>";
		}
		else{
			echo "<script>alert('修改失败');window.location='addStu.php';</script>";
		}	
	}
	if(@$_POST["ok"]=="添加"){
		checkInput($XH,$XM,$checkCSSJ,$temp_file);//检测输入值格式并上传缩放图片

		$handle=@fopen('upFile.jpeg','r');//获取图片的文件句柄
		//防止特殊字符串干扰sql语句：将字符串内容中的所有引号加上转义符：addslashes()，（取消转义符：stripslashes()） 
		$picture=@addslashes(fread($handle,filesize('upFile.jpeg')));//获取文件的二进制数据
		unlink('upFile.jpeg');

		fclose($handle);
		if($temp_file){//若上传了图片
			$add_sql="insert into XSB (XH,XM,XB,CSSJ,ZY,ZXF,BZ,ZP) values('$XH','$XM','$XB','$CSSJ','$ZY','$ZXF','$BZ'.'$ZP')";
		}
		else{
			$add_sql="insert into XSB (XH,XM,XB,CSSJ,ZY,ZXF,BZ) values('$XH','$XM','$XB','$CSSJ','$ZY','$ZXF','$BZ')";
		}
		$result=$pdo->exec($add_sql);
		if($result){
			echo "<script>alert('添加成功');window.location='addStu.php';</script>";
		}
		else{
			echo "<script>alert('添加失败');window.location='addStu.php';</script>";
		}	
	}
	if(@$_POST["ok"]=="删除"){
		//提高删除操作的用户体验性：删除表记录前判断是否存在指定记录，存在则执行删除否则提示不存在（若直接进行删除则失败原因不能直观提示）
		if(!$num){
			echo "<script>alert('未经查询选定记录');window.location='addStu.php';</script>";
		}
		else{
			$delete_sql="delete from XSB where XH='$XH'";
			$result=$pdo->exec($delete_sql);
			if($result){
				echo "<script>alert('删除成功');window.location='addStu.php';</script>";
			}
			else{
				echo "<script>alert('删除成功');window.location='addStu.php';</script>";
			}
		}
	}
	if(@$_POST["ok"]=="退出"){
		echo "<script>window.location='frameOperation.php';</script>";
	}	
?>