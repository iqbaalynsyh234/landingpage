#!/usr/bin/perl

`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron removeoldvehicle 6`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron clean`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron setrunhist 1`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron historical`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron setrunhist 0`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron movetotemp`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron movehistinfo`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron removeoldhist`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron movetemptohist`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php tools checkvtype`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron alertdata`;
`php -c /etc/ /var/www/html/lacak-mobil.com/desktop/index.php cron backupgpsdata`;
