@ECHO OFF

ECHO Emai Msg Cron Started @ %time% >> logfile/emailmsg_cron.txt

D:\wamp\bin\php\php5.2.5\php.exe emailmsg_cron.php >> logfile/emailmsg_cron.txt

ECHO Emai Msg Cron Completed @ %time% >> logfile/emailmsg_cron.txt