<?php

include "config.php";
include "common.php";

$services = array(	  "webtracking_gprmc_13520_1"
					, "webtracking_gprmc_13502"
					, "webtracking_gtp_13521_1"
					, "webtracking_prpv_13420"
					, "webtracking_sms_13540"
					, "webtracking_farrasindo_13522"
					, "webtracking_gprmc_13503_T1_1"
					, "webtracking_gtp_andalas_13523"
					, "webtracking_gprmc_13504_T1_pln"
					, "webtracking_andalas_13505_T1"
					, "webtracking_agungputra_13524"
					, "webtracking_gtp_new_13525"
					, "webtracking_gprmc_13505_T1_2_UDP"
					, "webtracking_13506_T5"
					, "webtracking_T5_Pulse_13507"
				);

$filename = "log.txt";
$log = "";
foreach($services as $service)
{
	$start = "sc start ".$service;		
	exec($start);	
	
	$log .= appendlog($filename, $start);
}

if ($argv[1] == "mail")
{
	sendlocalhost($log, "service");
}
