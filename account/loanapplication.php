<?php
include('includes/session.inc');
$title = _('Add Item');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/*echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="loanadvance_user.php">List of My Loan/Advance</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Loan/Advance Application</a></div>';
if(isset($_POST['emp_id']))
{
  $emp_id=$_POST['emp_id'];
}
else
{
$emp_id=$_SESSION['uid'];
}
 //$corp=getCorporationBranch($_SESSION['uid'],$db);
if (isset($_POST['submit']) ){
  $InputError = 0;
  
  if($_POST['emp_id']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Employee'),'error');
	}
  if($_POST['section']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Section'),'error');
	}
	/*if($_POST['advance']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Advance Needed'),'error');
	}
	if (($_POST['refnum']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['refnum'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Reference Number'),'error');
	}*/
   if($_POST['description']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Description'),'error');
	}
     if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Description, A-Z or 0-9 is Allowed'),'error');
	}
	
	 if($_POST['type']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Type'),'error');
	}
     /*if (($_POST['billno']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['billno'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Bill Number, A-Z or 0-9 is Allowed'),'error');
	}*/
	
	if($_POST['amount']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Amount'),'error');
	}
	if (($_POST['amount']!='') && (!is_numeric($_POST['amount'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Amount'),'error');
	}
	if($_POST['period']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Period'),'error');
	}
	/*if (($_POST['amountitem']!='') && (!is_numeric($_POST['amountitem'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Amount Item'),'error');
	}
	if($_POST['element_3_1']=='' || $_POST['element_3_2']=='' || $_POST['element_3_3']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Date'),'error');
	}
	if($_POST['checkedby']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Checkedby'),'error');
	}
	if (($_POST['checkedby']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['checkedby'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Name for Checked By, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['verifyphysically']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Name of person who has verified the item physically'),'error');
	}
	if (($_POST['verifyphysically']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['verifyphysically'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Name for Physically Verified By, A-Z or 0-9 is Allowed'),'error');
	}
	if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Remarks, A-Z or 0-9 is Allowed'),'error');
	}*/
	if($InputError!=1)
	{ 
	   
		$rt="select distinct(corporation_type) as cortype from tbl_corporations,tbl_joinings where tbl_corporations.corporation_id=tbl_joinings.current_officeid and tbl_joinings.program_uid='".$_SESSION['uid']."'";
		$rtq=DB_query($rt,$db);
		$rtr=DB_fetch_array($rtq);
		$offtype=$rtr['cortype'];
		
	    $ur="select rid from users_roles where uid='".$_SESSION['uid']."'";
		$urq=DB_query($ur,$db);
		$urr=DB_fetch_array($urq);
		$usrid=$urr['rid'];
		
		/*if($offtype==69 && ($usrid!=5 || $usrid!=19 || $usrid!=6))
		{
		  $reto="5";
		}
		if($offtype==69 && $usrid==5 )
		{
		  $reto="19";
		}
		if($offtype==69 && $usrid==19 )
		{
		  $reto="6";
		}
		if($offtype==70 && $usrid!=13)
		{
		  $reto="13";
		}
		if($offtype==70 && $usrid==13)
		{
		  $reto="5";
		}*/
	$corp=0;
	 $role = getRole($_SESSION['uid'],$db);
	if( $role != 13 && $role != 5 && $role != 19 )
		$corp=getCorporationBranch($_SESSION['uid'],$db);
	 //echo $role."==".$corp;exit;
	 $level = 1;
	 $reto="13";
	 if($role == 13)
	{
		 //$corp=getCorporationBranch($_SESSION['uid'],$db);
		 $level = 2;
		 $reto="5";
	}
	 if($role == 5)
	{
		 $level = 3;
		 $reto="19";
	}
	 if($role == 19)
	{
		 $level = 4;
		 $reto="6";
	}
	 
	 
	   $ado="insert into tbl_workflow_docket set workflow_id='13',time='".strtotime(date('d-m-Y'))."',status='pending',corp_branch=".$corp."";
	 $adoq=DB_query($ado,$db);
	 
	 $mwf="select max(doc_id) as doc_id from tbl_workflow_docket";
	 $mwfq=DB_query($mwf,$db);
	 $mwfr=DB_fetch_array($mwfq);
	 $doci=$mwfr['doc_id'];
	 
	 createTask($level,$doci,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
	 	
	 $mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];	
	 
	 $empof="select current_officeid from tbl_joinings where program_uid='".$_POST['emp_id']."'";
		$empofq=DB_query($empof,$db);
		$empofr=DB_fetch_array($empofq);
	 $sql="insert into loanadvance set empid='".$_POST['emp_id']."',
										section='".$_POST['section']."',
										office='".$empofr[0]."',
										description='".$_POST['description']."',
										type_loan='".$_POST['type']."',
										amount='".$_POST['amount']."',
										period='".$_POST['period']."',
										 date='".strtotime(date('d-m-Y'))."',
										  doc_id='".$doci."',
									    task_id='".$mtii."',
										reportedto='".$reto."',
										 intrate='5'";
	 $query=DB_query($sql,$db);		
	 if($query)	
	 {
	 unset($_POST);
	  unset($_REQUEST);
	  echo "<div class='success'>Loan Application Added Successfully</div>";
	  @header("location:loanadvance_user.php?msg=Loan Application Added Successfully");
	 }						
	}
}

?>

<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellspacing="1" cellpadding="2" border="0">
<tr class="oddrow"><td colspan="2" align="center"><h2>Loan/Advance Application</h2></td></tr>
 <tr class="evenrow"> 
    <td colspan="2"><div class="left">Employee: <span style="color:#FF0000">*</span></div>
      <div class="right"><select name="emp_id" id="emp_id">
      <option value="">--Select--</option>
                       <?php 
					     $emi="select employee_id, employee_name from tbl_joinings  INNER JOIN users on (tbl_joinings.program_uid = users.uid) where users.status=1 ORDER BY employee_name ASC";
					   // $emi="select * from tbl_joinings ORDER BY employee_name ASC";
						$emiq=DB_query($emi,$db);
						while($emir=DB_fetch_array($emiq))
						{ 
						 if($emp_id==$emir['program_uid'])
						   { 
					   ?> 
                       <option value="<?php echo $emir['program_uid'];?>" selected="selected"><?php echo ucwords($emir['employee_name']."(".$emir['employee_id'].")"."(Grade: ".getemployeegrade2($emir['employeegrade2'],$db).")")  ?></option>
                       <?php
					   }
					   else
					   {?>
                       <option value="<?php echo $emir['program_uid'];?>" ><?php echo ucwords($emir['employee_name']."(".$emir['employee_id'].")"."(Grade: ".getemployeegrade2($emir['employeegrade2'],$db).")") ?></option>
					   <?php
					   }
					   }
					   ?>  
                       </select></div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"><div class="left">Section Name: <span style="color:#FF0000">*</span></div>
      <div class="right"><select name="section" id="section">
                         <option value="">--Select Section--</option>
                         <?php 
						 $sec="select * from tbl_lookups where lookupType_id='19' order by lookup_name";
						 $secq=DB_query($sec,$db);
						 while($secr=DB_fetch_array($secq))
						 { 
						    if($_POST['section']==$secr['lookup_id'])
							{
						  ?>
                          <option value="<?php echo $secr['lookup_id'];?>" selected="selected"><?php echo ucwords($secr['lookup_name']);?> </option>
                          <?php
						  }
						  else
						  {
						  ?>
                           <option value="<?php echo $secr['lookup_id'];?>"><?php echo ucwords($secr['lookup_name']);?> </option>
                          <?php
						  }
						  }
						  ?>
                         </select></div></td>
  </tr>
<?php /*?> <tr class="evenrow">
    <td colspan="2"><div class="left">Advance Needed: <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="advance"  size="45" maxlength="11"  class="number" value="<?php echo $_POST['advance']?>" /></div></td>
  </tr><?php */?>
  <tr class="evenrow">
    <td><div class="left">Description: <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="description" id="description" size="45" maxlength="200" value="<?php echo $_POST['description']?>" onKeyPress="return alphanumeric(event)" /></div></td>
  </tr>
 
  <tr class="oddrow">
    <td><div class="left">Type of Employee loan: <span style="color:#FF0000">*</span></div>
      <div class="right"><select name="type" id="type">
      <option value="">--Select--</option>
      <option value="House And Building Advance" <?php if($_POST['type']=='House And Building Advance') { ?> selected="selected" <?php }?>>House & Building Advance</option>
      <option value="Vehicle Advance" <?php if($_POST['type']=='Vehicle Advance') { ?> selected="selected" <?php }?>>Vehicle Advance</option>
      <option value="Warm Clothing Advance" <?php if($_POST['type']=='Warm Clothing Advance') { ?> selected="selected" <?php }?>>Warm Clothing Advance</option>
      <option value="Festival Advance" <?php if($_POST['type']=='Festival Advance') { ?> selected="selected" <?php }?>>Festival Advance</option>
      </select></div></td>
  </tr>
 
  <tr class="evenrow">
    <td><div class="left">Amount: <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="amount" id="amount" size="45" maxlength="11"  class="number" value="<?php echo $_POST['amount']?>"  /></div></td>
  </tr>
  <tr class="oddrow">
    <td><div class="left">Period(months): <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="period"  size="45" id="period" maxlength="11" value="<?php echo $_POST['period']?>"  class="number" /></div></td>
  </tr>  
  <tr class="evenrow">
    <td colspan="2" align="center" class="back"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>
</form>
<?php include('includes/footer.inc'); ?>
<?php
if (isset($_POST['submit']) ){
 
  
  if($_POST['emp_id']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('emp_id').className='ercol';</script>";
	}
  if($_POST['section']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('section').className='ercol';</script>";
	}
	
   if($_POST['description']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
     if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	
	 if($_POST['type']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('type').className='ercol';</script>";
	}
     
	if($_POST['amount']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('amount').className='ercol';</script>";
	}
	if (($_POST['amount']!='') && (!is_numeric($_POST['amount'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('amount').className='ercol';</script>";
	}
	if($_POST['period']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('period').className='ercol';</script>";
	}
	}
?>