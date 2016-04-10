<?php
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET["q"];
$sql="SELECT * FROM tbl_joinings WHERE employee_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);



echo "<table cellspacing='2' cellpadding='1' border='0' id='wrapper2' width='100%' >";
 
  echo "<tr class='evenrow'><td width='50%'>Employee Name:</td><td width='50%' class='normal'>" . $rs->employee_name . "</td></tr>";
  echo "<tr class='oddrow'><td width='50%'>Date of Birth:</td><td width='50%' class='normal'>" . date("d-m-Y",strtotime($rs->dob)). "</td></tr>";
  echo "<tr class='evenrow'><td width='50%'>Date of Joining:</td><td width='50%' class='normal'>" . date("d-m-Y",strtotime($rs->doj)). "</td></tr>";
  echo "<tr class='oddrow'><td width='50%'>Department:</td><td width='50%' class='normal'>" . getLookupName($rs->current_Departmentid). "</td></tr>";
echo "<tr class='evenrow'><td width='50%'>Current Office:</td><td width='50%' class='normal'>" . getCorporationName($rs->current_officeid). "</td></tr>";
echo "<tr class='oddrow'><td width='50%'>Email ID:</td><td width='50%' class='normal'>" . $rs->email. "</td></tr>";
echo "</table>";


?>