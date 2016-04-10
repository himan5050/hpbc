<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//xmlhttp.open("GET",url+"process.php?corporation="+corporation+"&scheme="+scheme+"&account_number="+account_number+"&to_date="+to_date,true);
global $user;

//echo $_GET['corporation'].'<br />'.$_GET['scheme'].'<br />'.$_GET['account_number'].'<br />'.$_GET['to_date'];
//tbl_intbatch
/*
`intbatch_id` TINYINT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`Branch` VARCHAR( 20 ) NOT NULL ,
`Scheme` VARCHAR( 20 ) NOT NULL ,
`Account_Number` VARCHAR( 255 ) NOT NULL ,
`up_to_date` VARCHAR( 255 ) NOT NULL ,
`batch_status` TINYINT( 1 ) NOT NULL ,
`uid` TINYINT( 10 ) NOT NULL

*/

$corporation = $_GET['corporation'];
$scheme = $_GET['scheme'];
$account_number = $_GET['account_number'];
$to_date = $_GET['to_date'];

$sqlc = "select uid  from tbl_intbatch where Branch='".$corporation."' AND Scheme='".$scheme."' AND up_to_date='".$to_date."'";
$res = db_query($sqlc);
if($rs = db_fetch_object($res)){
  $uc= user_load($rs->uid);
  $message ='Dear '.$user->name.' ,<br />';
  $message .= 'We can not Process Your request, Because this process is done by '.$uc->name .' for selected Branch , Scheme and Time.';
}else{
   db_query("INSERT INTO {tbl_intbatch} (`Branch`,`Scheme`,`Account_Number`,`up_to_date`,`uid`)  VALUES('".$corporation."','".$scheme."','".$account_number."','".$to_date."','".$user->uid."')");

  $message ='Dear '.$user->name.' ,<br />';
 // $message .='Interest Calculation Process is in Queue, After Complition we will mail you.<br />Your Batch Id : '.db_last_insert_id('tbl_intbatch','intbatch_id');
  $message .= 'The Interest Calculation of the selected criteria is under process. You will shortly receive a confirmation email.<br />';
  $message .='Your Batch Id : '.db_last_insert_id('tbl_intbatch','intbatch_id');
  $message .='<br /> Thanks';

}
 echo $message;

?>
