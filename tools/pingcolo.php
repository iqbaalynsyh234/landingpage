<?php
	$command = "ping -s 32 -c 5 119.235.20.251";
	exec($command, $output, $retval);

	if ($retval)
	{
		restart(0);
		exit;
	}

	$len = 0;
	for($i=0; $i < count($output); $i++)
	{
		$pos = strpos($output[$i], "time=");
		if ($pos === FALSE) continue;

		$s = substr($output[$i], $pos+5);
		$len = $s*1;

		if ($len >= 1000) continue;

		@unlink("/u/k9929492/sites/www.lacak-mobil.com/www/log/rto");
		printf("[%s] COLO OK %d ms\n", date("Ymd H:i:s", mktime()+7*3600), $len);
		exit;	
	}

	restart($len);

function restart($len)
{
	
	if (! function_exists("curl_init")) 
	{
		die("curl disabled");
	}	
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://119.235.20.251/tools/restart.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
	curl_exec($ch);		
	curl_close($ch);		

	if ($len)
	{
		$subject = "COLO ping ".$len." ms";
	}
	else
	{
		$subject = "COLO RTO";
		createfile();
	}

	printf("[%s] %s\n", date("Ymd H:i:s", mktime()+7*3600), $subject);
	mail("owner@adilahsoft.com, prastgtx@gmail.com, jaya@vilanishop.com", $subject, "Service restart automatically");
}

function createfile()
{
	$fout = fopen("/u/k9929492/sites/www.lacak-mobil.com/www/log/rto", "w");
	fwrite($fout, "RTO");
	fclose($fout);
}
