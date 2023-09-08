<?php

include "common.php";

$services = array(	  "webtracking_gprmc_13520_1"
					, "webtracking_gprmc_13502"
					, "webtracking_gtp_13521_1"
					, "webtracking_prpv_13420"
					, "webtracking_sms_13540"
					, "webtracking_farrasindo_13522"
					, "webtracking_gprmc_13503_T1_1"
					, "webtracking_gtp_andalas_13523"
					, "webtracking_13506_T5"
				);

$log = "";
$sleep = 3600;

while(1)
{
	$filename = date("Ymd")."_notice.txt";
	appendlog($filename, "srvice notice running");
	
	foreach($services as $service)
	{
		$status = getpid($service);
		if ($status["PID"])  continue;
	
		$start = "sc start ".$service;
		exec($start);
			
		$log = "[".date("Ymd His")."] ".$start."\r\n";	
		sendlocalhost($log, "notice: ".$service." is down");
	}
	
	sleep($sleep);
}

function getpid($service)
{
	$res = array();
	exec("sc queryex ".$service, $lines);

	for($i=0; $i < count($lines); $i++)
	{
		$line = trim($lines[$i]);
		$pos = strpos($line, ":");
		
		$key = trim(substr($line, 0, $pos-1));
		$val = trim(substr($line, $pos+1));
		
		$res[$key] = $val;
	}
	
	return $res;
}

