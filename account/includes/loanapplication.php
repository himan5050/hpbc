<?php
include('includes/session.inc');
$title = _('Add Item');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
if(isset($_POST['emp_id']))
{
  $emp_id=$_POST['emp_id'];
}
else
{
$emp_id=$_SESSION['uid'];
}
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
	if($_POST['advance']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Advance Needed'),'error');
	}
	if (($_POST['refnum']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['refnum'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Reference Number'),'error');
	}
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
	  
	 $sql="insert into loanadvance set empid='".$_POST['emp_id']."',
										section='".$_POST['section']."',
										advance='".$_POST['advance']."',
										description='".$_POST['description']."',
										type_loan='".$_POST['type']."',
										amount='".$_POST['amount']."',
										period='".$_POST['period']."',
										 date='".strtotime(date('d-m-Y'))."',
										  intrate='5'";
	 $query=DB_query($sql,$db);		
	 if($query)	
	 {
	 unset($_POST);
	  unset($_REQUEST);
	  echo "<div class='success'>Loan Application Added Successfully</div>";
	  @header("location:loanadvance_user.php");
	 }						
	}
}

?>
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Loan Application</a></div>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellspacing="1" cellpadding="2" border="0">
<tr class="oddrow"><td colspan="2" align="center"><h2>Loan/Advance Application</h2></td></tr>
 <tr class="evenrow"> 
    <td colspan="2"><div class="left">Employee: <span style="color:#FF0000">*</span></div>
      <div class="right"><select name="emp_id" >
      <option value="">--Select--</option>
                       <?php 
					    $emi="select * from tbl_joinings ORDER BY employee_name ASC";
						$emiq=DB_query($emi,$db);
						while($emir=DB_fetch_array($emiq))
						{ 
						 if($emp_id==$emir['program_uid'])
						   {
					   ?>
                       <option value="<?php echo $emir['program_uid'];?>" selected="selected"><?php echo ucwords($emir['employee_name']."(".$emir['employee_id'].")")  ?></option>
                       <?php
					   }
					   else
					   {?>
                       <option value="<?php echo $emir['program_uid'];?>" ><?php echo ucwords($emir['employee_name']."(".$emir['employee_id'].")") ?></option>
					   <?php
					   }
					   }
					   ?>  
                       </select></div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"><div class="left">Section Name: <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="section"  size="45" maxlength="45" value="<?php echo $_POST['section']?>" onkeypress = "return alphanumeric(event)" /></div></td>
  </tr>
 <tr class="evenrow">
    <td colspan="2"><div class="left">Advance Needed: <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="advance"  size="45" maxlength="11"  class="number" value="<?php echo $_POST['advance']?>" /></div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"><div class="left">Description: <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="description"  size="45" maxlength="200" value="<?php echo $_POST['description']?>" onKeyPress="return alphanumeric(event)" /></div></td>
  </tr>
 
  <tr class="evenrow">
    <td colspan="2"><div class="left">Type of Employee loan: <span style="color:#FF0000">*</span></div>
      <div class="right"><select name="type">
      <option value="">--Select--</option>
      <option value="House & Building Advance">House & Building Advance</option>
      <option value="Vehicle Advance">Vehicle Advance</option>
      <option value="Warm Clothing Advance">Warm Clothing Advance</option>
      <option value="Festival Advance">Festival Advance</option>
      </select></div></td>
  </tr>
 
  <tr class="oddrow">
    <td colspan="2"><div class="left">Amount: <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="amount"  size="45" maxlength="11"  class="number" value="<?php echo $_POST['amount']?>"  /></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"><div class="left">Period(months): <span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="period"  size="45" maxlength="11" value="<?php echo $_POST['period']?>" class="number" onkeypress = "return alphanumeric(event)" /></div></td>
  </tr>
 <tr class="oddrow">
    <td colspan="2">&nbsp;</td>
 </tr>
   
  
  <tr class="evenrow">
    <td colspan="2" align="center" ><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>
</form>
<?php include('includes/footer.inc'); ?>
