<?php
	header("Content-type:image/png");//返回统计图片文件的数据流并删除临时图片文件
	if(isset($_GET["ok"])){
		$file="tempImg.png";
		$handle=fopen($file,"r");
		echo fread($handle,filesize($file));
		fclose($handle);
		unlink($file);
	}
?>