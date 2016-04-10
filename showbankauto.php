<?php
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET["q"];
$sql="SELECT bank FROM tbl_loan_detail WHERE loan_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);

$office='';
$bankname=getBankName($rs->bank);
echo $output = $bankname.'|'.$office;
?>