<!DOCTYPE html>
<html>
<head>
	<title>成绩分布分析</title>
	<style type="text/css">
		.table1{
			margin: auto;
			width: 500px;
		}
		div{
			text-align: center;
			height: 100px;
		}
	</style>
</head>
<body>
	<form method="post">
		<table class="table1">
			<caption><font style="font-size: 40px;">成绩分布分析</font></caption>
			<tr><td>课程名：</td>
			<td><select name="KCM">
				<option>请选择</option>
				<?php 
					include "connectSQL.php";
					$sql="select * from KCB";
					$smt=$pdo->query($sql);
					$result=$smt->fetchAll();
					foreach ($result as $key => $value) {
						echo $value['KCM'];
						if($value['KCM']==@$_POST['KCM']){
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
					if(@$_POST["ZY"]=="所有专业"){
						echo "<option selected>所有专业</option>";
					}
					else{
						echo "<option>所有专业</option>";
					}
					$sql="select distinct ZY from XSB";
					$smt=$pdo->query($sql);
					$result=$smt->fetchAll();
					foreach ($result as $key => $value) {
						if($value['ZY']==@$_POST['ZY']){
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
	if(isset($_POST["ok"])){
		$KCM=$_POST["KCM"];
		$ZY=$_POST["ZY"];
		if(($KCM=="请选择")||($ZY=="请选择")){
			echo "<script>alert('未选择课程或专业');window.location='gradeStatistics.php'</script>";
		}
		$sql="select * from XS_KC_CJ where KCM='{$KCM}' ";
		if($ZY!='所有专业'){
			$sql.="and ZY='{$ZY}' ";
		}
		$smt=$pdo->query($sql);
		$result=$smt->fetchAll();
		if($result){
			$gradeArr=array('优秀'=>0,'良好'=>0,'中等'=>0,'不及格'=>0);
			$lastPoint=0;//饼状图中需使用上次终点线角度记录变量作为下次起点线角度值
			foreach ($result as $key => $value) {
				if($value['CJ']>=90){
					$gradeArr['优秀']+=1;
				}
				elseif($value['CJ']>=80){
					$gradeArr['良好']+=1;
				}
				elseif($value['CJ']>=60){
					$gradeArr['中等']+=1;
				}
				else{
					$gradeArr['不及格']+=1;
				}
			}

			$x=250;$y=250;//圆心坐标
			$w=400;$h=400;//宽/高
			$r=$w/2;//半径
			$sum=array_sum($gradeArr);//获取数组元素和
			$tempSum=0;//累加变量
			//设置白色背景
			$image=imagecreatetruecolor(500,500);
			$white=imagecolorallocate($image,255,255,255);
			$black=imagecolorallocate($image,0,0,0);
			imagefill($image,0,0,$white);

			foreach ($gradeArr as $key => $value) {
				$startPoint=$lastPoint;
				$tempSum+=$value;
				$endPoint=($tempSum/$sum)*360;
				$lastPoint=$endPoint;
				if(($endPoint-$startPoint)>0){
				$midPoint=(($endPoint-$startPoint)/2)+$startPoint;//中间线角度
				$midRadian=($midPoint/180)*pi();//中间线的弧度
				
				//保持字符串输出到图形对应部分的合适位置：①考虑字符串宽/高来设置其输出起点 ②根据字符串对应图形部分的角度设置字符串的旋转角度
				if(($midPoint>90)&&($midPoint<270)){//当终点线在左侧时，字符在中间线的3/4处开始输出
					$strX=cos($midRadian)*$r*(3/4)+$x;//求三角函数值：根据原角度终边线与x轴组成的最小正角度的三角函数与单位圆中三角函数在各相对象限的正负之积获得
					$strY=sin($midRadian)*$r*(3/4)+$y;
					$angle=180-$midPoint;//设置字符串旋转角度
				}
				else{//当终点线在右侧时，字符在中间线1/2处开始输出
					$strX=cos($midRadian)*$r*(1/3)+$x;
					$strY=sin($midRadian)*$r*(1/3)+$y;
					$angle=360-$midPoint;//设置字符串旋转角度
				}
				$color=imagecolorallocate($image,rand(0,255),rand(0,255),rand(0,255));//随机定制颜色
				imagefilledarc($image,$x,$y,$w,$h,$startPoint,$endPoint,$color,IMG_ARC_PIE);//绘制已填色弧
				$codeText=mb_convert_encoding($key,"UTF-8","auto");
				$font="C:\Windows\Fonts\simhei.ttf";
				imagettftext($image,14, $angle,$strX,$strY,$black,$font,$codeText.round($value/$sum*100,2)."%");//绘制字符串
			}
			}
			$file="tempImg.png";
			imagepng($image,$file);
			//PHP文件中代码执行顺序：PHP代码在后端的服务器中直接执行，HTML代码被解析后返回给前端（客户端）的浏览器执行（PHP代码的执行顺序优于HTML代码）
			//显示临时图片文件后进行删除的方法：<img>的src设置为输出临时图片文件数据流后并删除临时图片文件的页面
			echo "<div><img src='showGradeStatistics.php?ok=ok'&time=".time()."></div>";//避免显示绘制的图形后不能显示其他内容：保存图形为图片文件后再通过输出html标签的形式显示保存的图片
			imagedestroy($image);
		}
	}
?>