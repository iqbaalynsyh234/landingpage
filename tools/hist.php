<?php

$d = date("Ymd");

if ($d%2) 
{
	system("C:\\xampp\\php\\php.exe C:\\www\\dmap\\index.php cron noticedelay yes no yes");
}
else
{
	system("C:\\xampp\\php\\php.exe C:\\www\\dmap\\index.php cron noticedelay yes yes yes");
}
