<?php
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET["q"];
$sql="SELECT * FROM tbl_joinings WHERE employee_id = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);



echo "<table cellspacing='2' cellpadding='1' border='0' id='form-container'>";
 
  echo "<tr class='evenrow'><td>Employee Name:</td><td>" . $rs->employee_name . "</td></tr>";
  echo "<tr class='oddrow'><td>Date of Birth:</td><td>" . date("d-m-Y",strtotime($rs->dob)). "</td></tr>";
  echo "<tr class='evenrow'><td>Date of Joining:</td><td>" . date("d-m-Y",strtotime($rs->doj)). "</td></tr>";
  echo "<tr class='oddrow'><td>Department:</td><td>" . getLookupName($rs->Departmentid). "</td></tr>";
echo "<tr class='evenrow'><td>Current Office:</td><td>" . getCorporationName($rs->current_officeid). "</td></tr>";
echo "<tr class='oddrow'><td>Email ID:</td><td>" . $rs->email. "</td></tr>";
echo "</table>";


?>