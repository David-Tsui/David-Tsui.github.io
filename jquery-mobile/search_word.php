<?php
	if (isset($_POST['search_KEY']) && isset($_POST['sel_MODE']))
	{
		$db_server = "localhost";
		$db_name = "nchu-tw-pinyin";
		$db_user = "root";
		$db_passwd = "speechlab723";
		
		$link = mysqli_connect($db_server,$db_user,$db_passwd,$db_name);
		mysqli_query($link,"SET NAMES 'UTF8'");
		mysqli_query($link,"SET CHARACTER SET UTF8");
		mysqli_query($link,"SET CHARACTER_SET_RESULTS=UTF8'");
		$key = trim($_POST['search_KEY']);
		$mode = $_POST['sel_MODE'];
			
		$key_super = $key ." ";
		if ($mode == 0)
			$sql = "SELECT DISTINCT `character` FROM `pinyin_formal` WHERE `sound` LIKE '$key' OR `sound` LIKE '$key_super%' ORDER BY char_length(`character`) ASC, `score` DESC";
		if ($mode == 1)
		    $sql = "SELECT `character` FROM `pinyin_formal` WHERE `sound` = '$key' ORDER BY CHAR_LENGTH(`character`) ASC, `score` DESC LIMIT 0,1";
		$query = mysqli_query($link,$sql);
		$row = mysqli_fetch_row($query);
		$arr = array();
		
		if ($mode == 0){
			$i = 0;
			do{	
				if ($row[0] != "")
				{
					$arr[$i]["character"] = $row[0];
					//$arr[$i]["score"] = $row[1];	
				}
				else
					break;
				$i++;
			}while ($row = mysqli_fetch_row($query));
		}
		if ($mode == 1){
			if ($row[0] != ""){
				$arr[0]["character"] = $row[0];
				//$arr[0]["score"] = $row[1];	
			}
		}
		
		if (count($arr) == 0){
			$blanks = 0;
			for($i = 0 ; $i < strlen($key) ; $i++){
				if ($key[$i] == " ")
					$blanks++;
			}
			$temp = $blanks + 1;
			$sql = "SELECT SUBSTRING_INDEX(`sound`,' ',$temp) FROM `pinyin_formal` WHERE `sound` LIKE '$key%' GROUP BY SUBSTRING_INDEX(`sound`,' ',$temp)";
			$query = mysqli_query($link,$sql);
			$row = mysqli_fetch_row($query);
			$i = 1;
			if ($row[0] == ""){
				$arr = array();
			}
			else{			
				$arr = array();
				$arr[0] = "related pinyin";
				do{	
					if ($row[0] != "")
					{
						$arr[$i] = $row[0];	
					}
					else
						break;
					$i++;
				}while ($row = mysqli_fetch_row($query));
			}
			echo json_encode($arr);   
		}
		else{
			echo json_encode($arr);
		}
	}
	else{
		echo "Haven't got a post value!!";
	}
?>
