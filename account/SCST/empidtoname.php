<?php
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

 $q=$_REQUEST["m1"];
$sql="SELECT * FROM tbl_joinings WHERE employee_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);

echo $rs->employee_name;

?>