<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$year = $_REQUEST['m1'];
$emp_id = $_REQUEST['m2'];

$sql ="select appraisal_remark,acr_of_appriasal,status,acr_no from tbl_apraisal where appraisal_year='".$year."' AND employee_id='".$emp_id."'";
$res = db_query($sql);
$rs = db_fetch_object($res);

$data = $rs->acr_of_appriasal.'|'.$rs->acr_no;


echo $data;
?>