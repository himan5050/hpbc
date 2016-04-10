<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//case register tbl_courtcasehearing 
//hearing entry tbl_courtcase

$q=$_GET['q'];



$sql = "select max(courtcase_id) as maxid from  tbl_courtcase where case_no='".$q."'";
$res = db_query($sql);
$rs = db_fetch_object($res);
if(!(empty($rs->maxid)))
{
   $sqld = "select next_hearing_date from tbl_courtcase where courtcase_id='".$rs->maxid."'";
   $resd = db_query($sqld);
   $rsd = db_fetch_object($resd);
   
   echo  $yu= date('d-m-Y',strtotime($rsd->next_hearing_date));exit;
}else{
   $sql="SELECT * FROM tbl_courtcasehearing WHERE courtcase_id = '".$q."'";
  $res = db_query($sql);
  if($rs=db_fetch_object($res)){
     //echo $output = $rs->hearing_date;
	  echo $yu=date('d-m-Y',strtotime($rs->hearing_date));exit;
  }
} 


?>