<?php
	$lsleep = 2*60;

	while(1)
	{	
		$output = "";
		exec("c:\\xampp\\php\\php.exe c:\\www\\dmap\\index.php cron alert", &$output);
		print_r($output);			
		echo "[".date("Y-m-d H:i:s")."] SLEEPING...(".$lsleep.")\r\n";			
		sleep($lsleep);
	}
