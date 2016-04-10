@ECHO OFF

ECHO MailCronScheduler Cron Started @ %time% >> logfile/MailCronScheduler.txt

D:\wamp\bin\php\php5.2.5\php.exe ccron.php >> logfile/MailCronScheduler.txt

ECHO MailCronScheduler Cron Completed @ %time% >> logfile/MailCronScheduler.txt


