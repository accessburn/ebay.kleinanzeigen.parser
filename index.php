<?php
	$db_pw = "xxx";
	$host = "xxx";
	$sb_name = "xxx";
	$db_benutzer = "xxx";
	$mysql = mysqli_connect($host, $db_benutzer, $db_pw, $sb_name);
?>
<html>
  <head>
    <title>ebay Kleinanzeigen</title>
    <link rel="stylesheet" href="https://showmeyourpc.de/css/style.css?rnd=1596531168" />
	<style type="text/css">
		.aditem-image {border: 1px solid #ff0000; }
		table { width: 50%; border: 0; }
		td { border: 0.1em solid; text-align: center; width: 10em; padding: 1em; background-color: #d8ffc4; border: 0.1em solid #c0c0c0; }
		th { border: 0.1em solid #c0c0c0; text-align: center; width: 10em; padding: 1em; background-color: #ededed; }
	</style>
  </head>
  <body>
	<table width="100%" border="0">
    	<tr>
    		<th>Nr.</th>
    		<th>Bild</th>
    		<th>Artikel</th> 
  		</tr>
		<?php
		$a=1;
		$result = mysqli_query($mysql, "SELECT * FROM kleinanzeigen");
	    	while($row = mysqli_fetch_object($result))                                    
	    	{
			echo '
			<tr>
				<td>
					'.$a++.'
				</td>
				<td>
					<a href="'.$row->href.'" target="_blank"><img src="'.$row->img.'" /></a>
				</td>
				<td>
					<a href="'.$row->href.'" target="_blank">'.$row->alt.'</a>
				</td>
			</tr>';
		}  
		?>
	</table>
  </body>
</html>
