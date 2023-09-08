<?php
$services = array(	  array("T1","13520", "webtracking_gprmc_13502", "webtracking_gps")
					, array("T1 (1)", "13502", "webtracking_gprmc_13520_1", "webtracking_gps")
					, array("T1_1 (<i>replace T1</i>)", "13503", "webtracking_gprmc_13503_T1_1", "webtracking_gps_t1_1")
					, array("T1 PLN", "13504", "webtracking_gprmc_13504_T1_pln", "webtracking_gps_pln")
					, array("T1 Andalas", "13505", "webtracking_andalas_13505_T1", "")
					, array("T3", "13540", "webtracking_sms_13540", "webtracking_gps_sms")
					, array("T4", "13521", "webtracking_gtp_13521_1", "webtracking_gps_gtp")					
					, array("T4 Farrasindo", "13522", "webtracking_farrasindo_13522", "webtracking_gps_farrasindo")
					, array("T4 Andalas", "13523", "webtracking_gtp_andalas_13523", "")
					, array("T4 Agung Putera", "13524", "webtracking_agungputra_13524", "webtracking_gps_agungputra")
					, array("INDOGPS", "13420", "webtracking_prpv_13420", "webtracking_gps_indogps")
				);

?>
<html>
	<head>
		<title>Daftar service</title>
	<head>
	<body bgcolor="#000000">		
		<table width="100%" cellpadding="10" cellspacing="0">
			<tr>
				<td colspan="4" align="center"><font color="#ffffff" size="+1">Aplikasi Webtracking Server</font></td>
			</tr>
			<tr>
				<td width="15%" style="border-top: 1px #ffffff solid; color: #000000; background: #ffffff;">&nbsp;GPS Type</td>
				<td width="10%" style="border-top: 1px #ffffff solid; color: #000000; background: #ffffff;" align="right">Port&nbsp;</td>
				<td width="20%" style="border-top: 1px #ffffff solid; color: #000000; background: #ffffff;">&nbsp;Nama Service</td>
				<td width="20%" style="border-top: 1px #ffffff solid; color: #000000; background: #ffffff;">&nbsp;Nama Table</td>
				<td>&nbsp;</td>
			</tr>
			<?php
		for($i=0; $i < count($services); $i++)
		{
		?>
			<tr>
				<td style="border-top: 1px #333333 solid; border-left: 1px #ffffff solid; color: #ffff00;">&nbsp;<?php echo $services[$i][0]; ?></td>
				<td style="border-top: 1px #333333 solid; color: #ff0000;" align="right"><?php echo $services[$i][1]; ?>&nbsp;</td>
				<td style="border-top: 1px #333333 solid; color: #333333;">&nbsp;<?php echo $services[$i][2]; ?></td>
				<td style="border-top: 1px #333333 solid; border-right: 1px #ffffff solid; color: #333333;">&nbsp;<?php echo $services[$i][3]; ?></td>
				<td>&nbsp;</td>
			</tr>
		
		<?php
		}
			?>
			<tr>
				<td colspan="4" style="border-top: 1px #ffffff solid; color: #333333;">&nbsp;</td>
			</tr>
		</table>
	</body>
</html>