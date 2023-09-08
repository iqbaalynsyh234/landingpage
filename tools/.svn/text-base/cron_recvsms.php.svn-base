<?php
	$path = "/home/smsserver/Public/lacakmobil";
	
	$start = microtime(true);
	
	set_time_limit(60);
	$sleep = 15;
	$n = 60/$sleep;
	for ($i=0; $i < $n; ++$i) 
	{
		$out = "";
		exec("php -c /etc/ ".$path."/index.php smsserver inbox >> /home/lacakmobil_".date("Ymd").".log", &$out);
		print_r($out);

		$start += $sleep;
		time_sleep_until($start);
	}
