<?php
	if (isset($_POST['prefix_KEY']))
	{
		$db_server = "localhost";
		$db_name = "nchu-tw-pinyin";
		$db_user = "root";
		$db_passwd = "speechlab723";
		
		$link = mysqli_connect($db_server,$db_user,$db_passwd,$db_name))
		
		mysqli_query($link,"SET NAMES 'UTF8'");
		mysqli_query($link,"SET CHARACTER SET UTF8");
		mysqli_query($link,"SET CHARACTER_SET_RESULTS=UTF8'");
		$associated = $_POST['prefix_KEY'];
		$word_len = strlen($associated) / 3;
		$sql = "SELECT DISTINCT `character` FROM `pinyin_formal` WHERE `character` LIKE '$associated%' AND char_length(`character`) > $word_len ORDER BY char_length(`character`) ASC, `score` DESC";
		$query = mysqli_query($link,$sql);
		$row = mysqli_fetch_row($query);

		$arr = array();
		$counter = 0;
		do{	
			if ($row[0] != "")
			{
				$arr[$counter] = $row[0];	
			}
			else
				break;
			$counter++;
		}while ($row = mysqli_fetch_row($query));

		if (count($arr) == 0){
			$empty = array();
			echo json_encode($empty);   
		}
		else{
			echo json_encode($arr);
		}
	}
	else{
		echo "Haven't got a post value!!";
	}
?>
