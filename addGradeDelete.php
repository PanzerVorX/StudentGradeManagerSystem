<?php
	include "connectSQL.php";
		if(isset($_GET["ok"])){
		$KCM=$_GET["KCM"];
		$ZY=$_GET["ZY"];
		$nowPage=$_GET["nowPage"];
		echo $nowPage;
		$stuKCM=$_GET["stuKCM"];
		$XH=$_GET["XH"];
		$sql="select KCH from KCB where KCM='{$stuKCM}'";
		$smt=$pdo->query($sql);
		$stuKCH=$smt->fetchColumn(0);
		$sql="delete from CJB where KCH={$stuKCH} and XH={$XH}";
		$result=$pdo->exec($sql);
		if($result){
			$sql="select count(*) from XS_KC_CJ";
			$isFstCondition=true;
			if($KCM!="所有课程"){
				$isFstCondition=false;
				$sql.="where KCM='{$KCM}' ";
			}
			if($ZY!="所有专业"){
				if($isFstCondition)
					$sql.="where ZY='{$ZY}' ";
				else
					$sql.="and ZY='{$ZY}'" ;
			}
			$smt=$pdo->query($sql);
			$totalCount=$smt->fetchColumn(0);//总记录数
			$pageCount=10;//页规定数
			$totalPage=ceil($totalCount/$pageCount);//总页数
			$totalPage=($totalPage==0)?1:$totalPage;
			if($nowPage>$totalPage){//分页管理中防止删除操作影响当前页数的判断：①判断当前页是否为尾页且只含单条记录  ②删除记录后判断当前页是否大于总页数
				$nowPage=$nowPage-1;
			}
			echo "<script>alert('删除成功');window.location='addGrade.php?ok=ok&KCM={$KCM}&ZY={$ZY}&nowPage={$nowPage}';</script>";
		}
		else{
			echo "<script>alert('删除失败');window.location='addGrade.php?ok=ok&KCM={$KCM}&ZY={$ZY}&nowPage={$nowPage}';</script>";
		}
	}
?>