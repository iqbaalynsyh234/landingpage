<?php
	if ($argc < 2)
	{
		$argv[1] = "Wavecomm";
	}

	$out = false;
	exec("gammu --identify", &$out);
	
	$filename = sprintf("/home/abahadilah/Public/lacak-mobil/log/gammu/identify_%s_%d.log", date("Ymd"), date("G"));
	
	for($i=0; $i < count($out); $i++)
	{
		append($filename, $out[$i]);
	}
	
	$found = false;
	for($i=0; $i < count($out); $i++)
	{
		if (strpos(strtoupper($out[$i]), strtoupper($argv[1])) === FALSE) continue;
		
		return;
	}

	// kill gammu-smsd
	
	$out = false;
	exec("ps aux | grep gammu-smsd", &$out);	
	for($i=0; $i < count($out); $i++)
	{
		printf("Killing.... %s\r\n", $out[$i]);
		$process = preg_split("/\s+/", $out[$i]);		
		echo system(sprintf("kill -9 %d", $process[1]));		
	}
	
	$mail['subject'] = sprintf("sms modem is down");
	$mail['message'] = sprintf("restart gammu service automatically.\r\n");
	$mail['dest'] = "jaya@vilanishop.com,jayatriyadi@hotmail.com,prastgtx@gmail.com,owner@adilahsoft.com"; 
	
	lacakmobilmail($mail);
	
	exec("gammu-smsd -c /etc/smsdrc &");

	function lacakmobilmail($post)
	{
		$ch = curl_init();
		
		$post_data = "";
		$post_data .= "dest=".$post['dest'];
		$post_data .= "&subject=".$post['subject'];
		$post_data .= "&message=".nl2br(urlencode($post['message']));		
		
		curl_setopt($ch, CURLOPT_URL, "http://www.lacak-mobil.com/mail.php");
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		curl_exec($ch);	
		curl_close($ch);	
	}
	
	function append($filename, $log)
	{
		$fout = fopen($filename, "a");
		fwrite($fout, sprintf("[%s] %s\r\n", date("Ymd His"), $log));
		fclose($fout);
	}
	
