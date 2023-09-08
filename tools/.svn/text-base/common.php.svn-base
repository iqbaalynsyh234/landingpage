<?php
if (! function_exists("appendlog"))
{
	function appendlog($filename, $log)
	{
		$log = "[".date("Ymd His")."] ".$log."\r\n";
		
		$fout = fopen($filename, "a");
		fputs($fout, $log);
		fclose($fout);
		
		return $log;
	}
}

if (! function_exists("sendlocalhost"))
{
	function sendlocalhost($body, $subject)
	{
		$ch = curl_init();
		
		$post_data = "subject=".$subject;
		$post_data .= "&body=".nl2br(urlencode($body));
			
		curl_setopt($ch, CURLOPT_URL, "http://services.adilahsoft.com/lacakmobil/sendmail.php");
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		curl_exec($ch);	
		curl_close($ch);	
	}
}
