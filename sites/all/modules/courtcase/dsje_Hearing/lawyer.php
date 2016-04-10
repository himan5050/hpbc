<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

echo $q=$_GET['q']; exit;
$sql="SELECT * FROM tbl_lawyer WHERE lawyer_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);
echo $output = $rs->phone_no.'|'.$rs->email;
?>