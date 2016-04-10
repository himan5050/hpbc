@ECHO OFF

ECHO Drupal Main Cron Started @ %time% >> logfile/maincron.txt

D:\wamp\bin\php\php5.2.5\php.exe cron.php >> logfile/maincron.txt

ECHO Drupal Main Cron Completed @ %time% >> logfile/maincron.txt