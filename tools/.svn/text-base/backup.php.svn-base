<?php
if (! isset($_GET['f']))
{
	die("access denied");
	exit;
}

if ($_GET['f'] == "on")
{
	$fout = fopen("C:\\www\\dmap\\backup.log", "w");
	fwrite($fout, date("Ymd H:i:s"));
	fclose($fout);
	
	echo "backup on";
	exit;
}

unlink("C:\\www\\dmap\\backup.log");
echo "backup off";
