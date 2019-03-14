<!DOCTYPE html>
<html>
<head>
	<title>主菜单</title>
	<style type="text/css">
		li{
			margin-left: 25px;
		}
		.title-li{//列表标题
			font-weight:bold;
			font-size: 30px;
			list-style-type:none;
			margin: 0px
		}
		a{
			text-decoration: none;
			color: blue;
		}
	</style>
</head>
<body bgcolor="D9DFAA">
	<ul style="list-style-type:circle;padding-left:20px;font-size:25px">
	<li class="title-li">学生信息管理</li>
	<li><a href="addStu.php" target="frameOperation">学生信息录入</a></li>
	<li><a href="queryStu.php" target="frameOperation">学生信息查询</a></li>
	<li><a href="creditSort.php" target="frameOperation">学生学分排序</a></li>
	</ul>

	<ul style="list-style-type:circle;padding-left:20px;font-size:25px">
	<li class="title-li">课程信息管理</li>
	<li><a href="addCourse.php" target="frameOperation">课程信息录入</a></li>
	<li><a href="queryCourse.php" target="frameOperation">课程信息查询</a></li>
	</ul>
	
	<ul style="list-style-type:circle;padding-left:20px;font-size:25px">
	<li class="title-li">成绩信息管理</li>
	<li><a href="addGrade.php" target="frameOperation">成绩信息录入</a></li>
	<li><a href="queryGrade.php" target="frameOperation">学生成绩查询</a></li>
	<li><a href="gradeSort.php" target="frameOperation">课程成绩排序</a></li>
	<li><a href="gradeStatistics.php" target="frameOperation">成绩分布分析</a></li>
	</ul>
</body>
</html>