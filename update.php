<?php
	$profillink = 'https://www.ebay-kleinanzeigen.de/s-bestandsliste.html?userId=EIGENE_USER-ID';
	$db_pw = "xxxx";
	$host = "xxx";
	$db_name = "xxx";
	$db_benutzer = "xxx";
	$mysql = mysqli_connect($host, $db_benutzer, $db_pw, $db_name);
	mysqli_query($mysql,"TRUNCATE TABLE kleinanzeigen");
	for($i=1; $i < 3; $i++) {
   		echo '<h1>Seite: ' . $i.'</h1><br /><br />';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $profillink . '&pageNum=' . $i);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		$html = curl_exec($ch);
		preg_match_all('/<article class="aditem" data-adid="(.*?)">(.*?)<\/article>/s',$html, $bodytag);	  
		foreach ($bodytag[0] as $key => $value) {
			$value = str_replace("<div class=\"imagebox\"  data-href=\"", "<a target=\"_blank\" href=\"https://www.ebay-kleinanzeigen.de", $value);
			$value = str_replace("</div>", "</a>", $value);
			$value = str_replace("article", "div", $value);
			preg_match_all('/alt="(.*?)"/s', $value, $alt);
			preg_match('/href="(.*?)"/', $value, $link);
			preg_match_all('/class="ellipsis"(.*?)">(.*?)<\/a>/s', $value, $titel);
			preg_match('/img src="(.*?)"/', $value, $bild);
			preg_match_all('/data-adid="(.*?)"/', $value, $dataadid);
			$exist_username = mysqli_num_rows(mysqli_query($mysql, "SELECT * FROM kleinanzeigen WHERE id = '".$dataadid[1][0]."'"));
			if ($exist_username == 0) {
				$contents= file_get_contents($bild[1]);
				setlocale(LC_TIME, 'de_DE');
				$savename = strftime("%Y-%m-%d_%H-%M-%S");
				$savefile = fopen('artikel/' .  $dataadid[1][0] . '.jpg', "w");
				fwrite($savefile, $contents);
				fclose($savefile);				
				echo '<h3>NEU</h3><br />';
				mysqli_query($mysql, "INSERT INTO kleinanzeigen VALUES('',
				    '".mysqli_real_escape_string($mysql, $dataadid[1][0])."',
				    '".mysqli_real_escape_string($mysql, 'artikel/' .  $dataadid[1][0] . '.jpg')."',
				    '".mysqli_real_escape_string($mysql, $link[1])."',
				    '".mysqli_real_escape_string($mysql, $titel[2][0])."'
				)") OR die("'".$sql."' : ".mysql_error());   
				echo $titel[2][0] . '<br /><img src="'.$bild[1].'" /><br /><hr />';
			}

		}  
	
	}
?>
