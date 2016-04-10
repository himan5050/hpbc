<?php
//session_start();
//$_SESSION['emp_id'] = $q;
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET["q"];
$sql="SELECT tbl_joinings.current_officeid as current_officeid,tbl_joinings.current_designationid as current_designationid,tbl_joinings.current_Departmentid as current_Departmentid,tbl_joinings.employee_name as employee_name,tbl_apraisal.appraisal_year as appraisal_year,tbl_apraisal.appraisal_remark as appraisal_remark,tbl_apraisal.acr_of_appriasal as acr_of_appriasal FROM tbl_joinings LEFT JOIN tbl_apraisal ON(tbl_joinings.employee_id=tbl_apraisal.employee_id) WHERE tbl_joinings.employee_id = '".$q."' ORDER BY appraisal_year DESC";
$res= db_query($sql);
$rs=db_fetch_object($res);
$office=getCorporationName($rs->current_officeid);
$designation=getLookupName($rs->current_designationid);
$department=getLookupName($rs->current_Departmentid);




if($rs->appraisal_year){
	 $ysql ="select appraisal_year from tbl_apraisal where employee_id='".$q."' ORDER BY appraisal_year DESC";
	$yres= db_query($ysql);
	$yrs=db_fetch_object($yres);
	$appraisalyear = $yrs->appraisal_year;
   if($appraisalyear){
	   $year =$appraisalyear+1;
		$cyear = Date("Y");
		//$selectyear ='--Select--';
		while($year<=$cyear){
			$selectyear .= $year.','; 
				$year = $year +1;
			
		}
   }
 }
else{

     $selectyear = date("Y",time()).',';
}


echo $output = $rs->employee_name.'|'.$designation.'|'.$office.'|'.$department.'|'.$rs->appraisal_year.'|'.$rs->appraisal_remark.'|'.$rs->acr_of_appriasal.'|'.$selectyear;
?>


