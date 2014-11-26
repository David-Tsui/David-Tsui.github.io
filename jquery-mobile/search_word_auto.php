<?php
	if (isset($_POST['auto_KEY']))
	{
		$db_server = "localhost";
		$db_name = "nchu-tw-pinyin";
		$db_user = "root";
		$db_passwd = "speechlab723";
		
		$link = mysqli_connect($db_server,$db_user,$db_passwd,$db_name);
		mysqli_query($link,"SET NAMES 'UTF8'");
		mysqli_query($link,"SET CHARACTER SET UTF8");
		mysqli_query($link,"SET CHARACTER_SET_RESULTS=UTF8'");
		$key = $_POST['auto_KEY'];
		
		$sql = "SELECT `character`,`score` FROM `pinyin_formal` WHERE `sound` = '$key' ORDER BY `score` DESC LIMIT 0,1";
		$query = mysqli_query($link,$sql);
		$row = mysqli_fetch_row($query);
		$nothing_behind = false;
		
		$sql_behind = "SELECT `character`,`score` FROM `pinyin_formal` WHERE `sound` LIKE '$key%' ORDER BY `score` DESC LIMIT 0,1";
		$query_behind = mysqli_query($link,$sql_behind);
		$row_behind = mysqli_fetch_row($query_behind);
		if ($row_behind[0] == "")
			$nothing_behind = true;
		
		$arr = array();
		if ($row[0] != ""){
			if ($nothing_behind == true)
				$arr[0] = "fail";
			else
				$arr[0] = "next";
			$arr[1]["character"] = $row[0];
			$arr[1]["score"] = $row[1];	
			echo json_encode($arr);
		}
		else{
			if ($nothing_behind == true)
				$arr = array("fail");
			else
				$arr = array("next");
			echo json_encode($arr);
		}
	}
	else{
		echo "Haven't got a post value!!";
	}
?>