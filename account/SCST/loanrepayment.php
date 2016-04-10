<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET['q']; 
 $sql="SELECT * FROM tbl_loanee_detail WHERE account_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);
echo $output = $rs->fname.' '.$rs->lname.'|'.$rs->address1.' '.$rs->address2.' '.gettehsil(ucwords($rs->tehsil)).'  '.getdistrict(ucwords($rs->district));
//echo $output = $rs->phone_no.'|'.$rs->email.'|'.$rs->fee_charge;
?>