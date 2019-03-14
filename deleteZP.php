<?php
	include "connectSQL.php";
	if(isset($_POST["XH"])){//删除照片
		$XH=$_POST["XH"];
		$sql="update XSB set ZP=null where XH='$XH'";
		$result=$pdo->exec($sql);
		if($result){
			echo "true";
		}
		else{
			echo "false";
		}
	}
?>