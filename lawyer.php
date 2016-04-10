<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET['q']; 
$sql="SELECT * FROM tbl_lawyer WHERE lawyer_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);
echo $output = round($rs->fee_charge,2).'|'.$rs->phone_no.'|'.$rs->email;
//echo $output = $rs->phone_no.'|'.$rs->email.'|'.$rs->fee_charge;
?>