<?php
include('includes/session.inc');
$title = _('Add Item');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
 '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'.$_SERVER['SCRIPT_NAME'].'">Issue Item</a></div>';
if (isset($_POST['submit']) ){
  $InputError = 0;

 // $date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
  $date=$_POST['JournalProcessDate'];
  //echo $date;
  if($date == ''){
    $InputError = 1;
     prnMsg(_('Please Select Date'),'error');
  }
   if($_POST['itemdetails']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Item Details'),'error');
	}
	 if (($_POST['itemdetails']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemdetails'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Item Details, A-Z or 0-9 is Allowed'),'error');
	}
	
	 if($_POST['office']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Office'),'error');
	}
	if($_POST['quantity']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Quantity'),'error');
	}
	if (($_POST['quantity']!='') && (!is_numeric($_POST['quantity'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Quantity'),'error');
	}
	if($date=='')
   {
     $InputError = 1;
     prnMsg(_('Select Date'),'error');
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
	/*
	if($_POST['element_3_2'] == '' && $_POST['element_3_1'] == '' && $_POST['element_3_3']){
	   $InputError = 1;
       prnMsg(_('Please Select Date'),'error');
	}*/
		/*if($_POST['JournalProcessDate']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Date'),'error');
	}*/
	if (($_POST['verifyphysically']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['verifyphysically'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Name for Physically Verified By, A-Z or 0-9 is Allowed'),'error');
	}
	
	if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Remarks, A-Z or 0-9 is Allowed'),'error');
	}
	 $it="select * from item_master where code='".$_POST['itemdetails']."'";
	  $itq=DB_query($it,$db);
	  $itn=DB_num_rows($itq);
	  $itr=DB_fetch_array($itq);
	  $qu=$itr['openingval'];
	  if($qu>=$_POST['quantity'])
	  {
	  
	  }
	  else
	  {
	    
	     $InputError = 1;
     prnMsg(_('This Quantity is not awailable in stock'),'error');
	  }
	if($InputError!=1)
	{
	
	   $it="select * from item_master where code='".$_POST['itemdetails']."'";
	  $itq=DB_query($it,$db);
	  $itn=DB_num_rows($itq);
	  $itr=DB_fetch_array($itq);
	  $qu=$itr['openingval'];
	  if($qu>=$_POST['quantity'])
	  {
	   $tqu=$qu-$_POST['quantity'];
	   
	    $itu="update item_master set openingval='".$tqu."' where code='".$_POST['itemdetails']."'";
		$ituq=DB_query($itu,$db);
		
	  $s="insert into assigned_item set 	itemdetails='".$_POST['itemdetails']."',
	                                     quantity='".$_POST['quantity']."',
										
										 office='".$_POST['office']."',
										 date='".strtotime($date)."',
										 enteredby='".$_POST['enteredby']."',
										 checkedby='".$_POST['checkedby']."',
										 verifyphysically='".$_POST['verifyphysically']."',
										 remarks='".$_POST['remarks']."'";
	  $s=DB_query($s,$db);
	  unset($_POST);
	  echo "<div class='success'>Item has been issued successfully</div>";
	  }
	  else
	  {
	    
	     prnMsg(_('This Quantity is not awailable in stock'),'error');
	  }
	}
}

?>

<form action="" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellspacing="1" cellpadding="2" border="0">
 <tr class="oddrow"><td colspan="2" align="center"><h2>Issue Item</h2></td></tr>
  <tr class="evenrow">
    <td colspan="2"> <div class="left">Items/Equipment details: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="itemdetails" id="itemdetails"><option value="">--Select Item--</option>
    <?php
	$ic="select code,name from item_master order by code";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{
	  if($_POST['itemdetails']==$icr['code'])
	 {
	  echo '<option value="'.$icr['code'].'" selected>'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$icr['code'].'">'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
	  }
	}
	?>
	</select></div>
  </tr>
 
  <tr class="oddrow">
  <td colspan="2"> <div class="left">Quantity: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="quantity" id="quantity" size="45" maxlength="10" value="<?php echo $_POST['quantity'];?>" onkeypress = "return fononlyn(event)"/></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"> <div class="left">Date: <span style="color:#FF0000">*</span></div>
    <div class="right"><!--<div id="li" style="width:353px;">
		<span>
			<input id="element_3_2" name="element_3_2" class="element text" size="2" style="width:40px;" maxlength="2" value="<?php echo $_POST['element_3_2'];?>" type="text" readonly="readonly"> 
			<label for="element_3_2">DD</label>
		</span>
        <span>	<input id="element_3_1" name="element_3_1" class="element text" size="2" style="width:40px;" maxlength="2" value="<?php echo $_POST['element_3_1'];?>" type="text" readonly="readonly"> /
			<label for="element_3_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_3_3" name="element_3_3" class="element text" size="4" style="width:67px;" maxlength="4" value="<?php echo $_POST['element_3_3'];?>" type="text" readonly="readonly">/
			<label for="element_3_3">YYYY</label>
		</span>
	
		<span id="calendar_3">
			<img id="cal_img_3" class="datepicker" src="calender/calendar.gif" alt="Pick a date.">		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_3_3",
			baseField    : "element_3",
			displayArea  : "calendar_3",
			button		 : "cal_img_3",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
	</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></td>
  </tr>
  <tr class="oddrow">
   <td colspan="2"><div class="left">Department: <span style="color:#FF0000">*</span></div>
    <div class="right"><select  name="office" id="office"><option Value="">--Select Office--</option>
	<?php $sql = "SELECT loccode, locationname FROM locations";
$resultStkLocs = DB_query($sql,$db);
while ($myrow=DB_fetch_array($resultStkLocs)){
if($_POST['office']==$myrow['loccode'])
      {
	   echo '<option Value="'.$myrow['loccode'].'" selected >'.$myrow['locationname'].'</option>';
		}
		else
		{
		 echo '<option Value="'.$myrow['loccode'].'">'.$myrow['locationname'].'</option>';
		}
		 
	}
?>

</select>
	</div></td>
  </tr>
  <tr class="evenrow">
   <td colspan="2"> <div class="left">Name of the person entered by: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="enteredby" id="enteredby" size="45" maxlength="45" value="<?php echo $_SESSION['UsersRealName'] ?>" readonly="readonly"/></div></td>
  </tr>
  <tr class="oddrow">
   <td colspan="2"> <div class="left">Checked by: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="checkedby" id="checkedby" size="45" maxlength="45" value="<?php echo $_POST['checkedby'];?>" onkeypress="return alphanumeric(event)"  /></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"><div class="left">Name of person who has verified the item physically: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="verifyphysically" id="verifyphysically" size="45" maxlength="45" value="<?php echo $_POST['verifyphysically'];?>" onkeypress="return alphanumeric(event)"  /></div></td>
  </tr>
  <tr class="oddrow">
  <td colspan="2"><div class="left">Remarks: </div>
    <div class="right"><input type="text" name="remarks" id="remarks" size="45" maxlength="200" value="<?php echo $_POST['remarks'];?>"onkeypress="return alphanumeric(event)"  /></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2" align="center"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
  
</table></form>
<?php include('includes/footer.inc'); ?>
<?php
if (isset($_POST['submit']) ){
 
 // $date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
  $date=$_POST['JournalProcessDate'];
  //echo $date;
 
   if($_POST['itemdetails']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('itemdetails').className='ercol';</script>";
	}
	 if (($_POST['itemdetails']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemdetails'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('itemdetails').className='ercol';</script>";
	}
	
	 if($_POST['office']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('office').className='ercol';</script>";
	}
	if($_POST['quantity']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('quantity').className='ercol';</script>";
	}
	if (($_POST['quantity']!='') && (!is_numeric($_POST['quantity'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('quantity').className='ercol';</script>";
	}
	
	if($_POST['checkedby']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('checkedby').className='ercol';</script>";
	}
	if (($_POST['checkedby']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['checkedby'])))
	{
	   echo "<script type='text/javascript'>document.getElementById('checkedby').className='ercol';</script>";
	}
	
	if($_POST['verifyphysically']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('verifyphysically').className='ercol';</script>";
	}
	
	if (($_POST['verifyphysically']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['verifyphysically'])))
	{
	   echo "<script type='text/javascript'>document.getElementById('verifyphysically').className='ercol';</script>";
	}
	
	if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('remarks').className='ercol';</script>";
	}
	if($date=='')
   {  echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate').className='date';</script>";
	}
	
	 $it="select * from item_master where code='".$_POST['itemdetails']."'";
	  $itq=DB_query($it,$db);
	  $itn=DB_num_rows($itq);
	  $itr=DB_fetch_array($itq);
	  $qu=$itr['openingval'];
	  if($qu>=$_POST['quantity'])
	  {
	  
	  }
	  else
	  {
	    
	     echo "<script type='text/javascript'>document.getElementById('quantity').className='ercol';</script>";
	  }
	
	
	}
	?>