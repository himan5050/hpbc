<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
session_start();

if(isset($_REQUEST['m']))
{
$workflow_id=$_REQUEST['m'];
$_SESSION['workflow_id']=$workflow_id;


$query = "select workflow_details_id from tbl_workflow_details where workflow_id=$workflow_id";
$result = db_query($query);
	
	$counter = 0;
	$data='<b>Workflow Detail Id:</b><select name="drop2" onchange="showvalue(this.value);">';
	$data .= '<option value="">--Select--</option>';
	while($row=db_fetch_object($result)) {
		//$workflowdtarray[$row->workflow_details_id] = $row->workflow_details_id;

 $data .='<option value="'.$row->workflow_details_id.'">'.$row->workflow_details_id.'</option>';
   
   
	}
  $data .=' </select>';
}
if(isset($_REQUEST['m1']))
{
  $data ="Dropdown Value=".$_REQUEST['m1'];
  $_SESSION['workflow_details']=$_REQUEST['m1'];


$sqlm ="SELECT max(LEVEL) as mlevel FROM `tbl_escalation` WHERE workflow_details_id ='".$_SESSION['workflow_details']."'";
$resm = db_query($sqlm);
$rsm = db_fetch_object($resm);

$_SESSION['mlevel'] = $rsm->mlevel;

$data ='<table><tr><th >Type</th> <th >Users/Roles</th><th>SLA (hours)</th><th>SMS</th><th>Email</th><th>Action</th></tr>';

$sqlw = "select * from tbl_escalation where workflow_details_id='".$_SESSION['workflow_details']."'";
$resw =	  db_query($sqlw);
	if($resw){

	  while($rsw = db_fetch_object($resw)){
		  $action = '<a href="delete/'.$rsw->eid.'">Delete</a>';
		  if($rsw->users){
			$type = 'Users';
			$usersroles = $rsw->users;
		  }else{
			$type = 'Roles'; 
			$usersroles = $rsw->roles;
		  }
		  $data .='<tr class="odd"> <td >'.$type.'</td> <td >'.$usersroles.'</td><td>'.$rsw->SLA.'</td><td>'.$rsw->SMS.'</td><td>'.$rsw->Email.'</td><td>'. $action.'</td></tr>';
	  }
	  $data .='</table>';
	}

}
echo $data;
?>