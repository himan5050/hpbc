<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $user;
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
     db_query("INSERT INTO {tbl_intbatch} (`Branch`,`Scheme`,`Account_Number`,`up_to_date`,`uid`)  
	                  VALUES('".$corporation."','".$scheme."','".$account_number."','".$to_date."','".$user->uid."')");

     $message ='Dear '.$user->name.' ,<br />';
     $message .= 'The Interest Calculation of the selected criteria is under process. You will shortly receive a confirmation email.<br />';
     $message .='Your Batch Id : '.db_last_insert_id('tbl_intbatch','intbatch_id');
     $message .='<br /> Thanks';
}
echo $message;
?>
