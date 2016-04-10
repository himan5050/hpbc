<?php
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET["q"];
$sql="SELECT * FROM tbl_joinings WHERE employee_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);


$office=getCorporationName($rs->current_officeid);
$designation=getLookupName($rs->current_designationid);
$department=getLookupName($rs->current_Departmentid);
echo $output = $rs->employee_name.'|'.$office.'|'.$designation.'|'.$department.'|'.$rs->phone.'|'.$rs->mobile.'|'.$rs->email;
?>