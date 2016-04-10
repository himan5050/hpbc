<?php
include('includes/session.inc');
$title = _('Purchase Order');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
 /*'<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="purchase_order_detail.php">List of Purchase Order Details</a> &raquo; <a href="'.$_SERVER['SCRIPT_NAME'].'">Requisition Form</a></div>';
if (isset($_POST['submit']) ){
  $InputError = 0;

//$date=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
 $date=$_POST['JournalProcessDate'];
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
    if($_POST['itemcode']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Item Code'),'error');
	}
	
   
    if($_POST['referencenum']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Reference Number'),'error');
	}
	if (($_POST['referencenum']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['referencenum'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Reference Number'),'error');
	}
	
	if($_POST['quantity_required']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Quantity Required'),'error');
	}
	if (($_POST['quantity_required']!='') && (!is_numeric($_POST['quantity_required'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Quantity Required'),'error');
	}
	 if($date=='')
   {
     $InputError = 1;
     prnMsg(_('Select Date'),'error');
	}
	if($_POST['amountitem']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Amount of Item'),'error');
	}
	if (($_POST['amountitem']!='') && (!is_numeric($_POST['amountitem'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Amount Item'),'error');
	}
	if($_POST['status']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Status'),'error');
	}
   if($date=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Purchase Date'),'error');
	}
	if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Remarks, A-Z or 0-9 is Allowed'),'error');
	}
	
	if($InputError!=1)
	{
	  
	  $s="insert into purchase_order_details set itemdetails='".$_POST['itemdetails']."',
	                                             referencenum='".$_POST['referencenum']."',
												 itemcode='".$_POST['itemcode']."',
												 quantity_required='".$_POST['quantity_required']."',
												 date='".strtotime($date)."',
												 amountitem='".$_POST['amountitem']."',
												 status='".$_POST['status']."',
												 preparedby='".$_POST['preparedby']."',
												 remarks='".$_POST['remarks']."' ";
	 $q=DB_query($s,$db);
	 if($q)
	 { 
	   unset($_POST);
	  echo "<div class='success'>Requisition Form Submitted</div>";
	  @header("location:purchase_order_detail.php?msg=Requisition Form Submitted");
	 }
	}
}
?>

<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0">
  <tr class="oddrow"><td align="center" ><h2>Requisition Form</h2></td></tr>
   <tr class="evenrow">
    <td ><div class="left">Reference No.: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="referencenum"  id="referencenum" size="45" maxlength="10"  value="<?php echo $_POST['referencenum'];?>"onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
  
   <tr class="oddrow">
    <td  ><div class="left">Item Code: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="itemcode" id="itemcode"><option value="">--Select Item--</option>
    <?php
	$ic="select code,name from item_master order by code";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{ 
	if($_POST['itemcode']==$icr['code'])
	 {
	  echo '<option value="'.$icr['code'].'" selected>'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$icr['code'].'">'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
	  }
	}
	?>
	</select></div></td>
  </tr>
 <tr class="evenrow">
    <td ><div class="left"> Items/Fixed Assets details: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="itemdetails" id="itemdetails" size="45" maxlength="45"  value="<?php echo $_POST['itemdetails'];?>" onkeypress="return alphanumeric(event)"  /></div></td>
  </tr>
  
  <tr class="oddrow">
    <td ><div class="left">Quantity Required: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="quantity_required" id="quantity_required" size="45" maxlength="10" value="<?php echo $_POST['quantity_required'];?>" onkeypress = "return fononlyn(event)"/></div></td>
  </tr>
  <tr class="evenrow">
    <td ><div class="left">Date: <span style="color:#FF0000">*</span></div>
    <div class="right"><!--<div id="li_1" style="width:355px;">
		<span>
			<input id="element_1_2" name="element_1_2" class="element text" align="middle"  style="width:40px;" align="middle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_2'];?>"> 
			<label for="element_1_2">DD</label>
		</span>
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" align="middle"  style="width:40px; " align="middle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_1'];?>"> /
			<label for="element_1_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" align="middle"  style="width:67px;" align="middle" size="4" maxlength="4"  type="text" value="<?php echo $_POST['element_1_2'];?>"> /
			<label for="element_1_3">YYYY</label>
		</span>
	
		<span id="calendar_1">
			<img id="cal_img_1" class="datepicker" src="calendar.gif" alt="Pick a date.">		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_1_3",
			baseField    : "element_1",
			displayArea  : "calendar_1",
			button		 : "cal_img_1",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>" id="JournalProcessDate"></div></div></td>
  </tr>
  <tr class="oddrow">
    <td ><div class="left">Amount of the Item: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="amountitem" id="amountitem"  size="45" maxlength="10" class="number" value="<?php echo $_POST['amountitem'];?>"onkeypress = "return fononlyn(event)"/></div></td>
  </tr>
 
  <tr class="evenrow">
    <td ><div class="left">Status: <span style="color:#FF0000">*</span></div>
    <div class="right">
	<select name="status" id="status">
   
	<option value="pending"  <?php if($_POST['status']=='pending') { echo "selected"; }?>>Pending</option>
	
	</select></div></td>
  </tr>
  <tr class="oddrow">
    <td ><div class="left">Remarks:</div>
    <div class="right"><input type="text" name="remarks" id="remarks" size="45" maxlength="200" value="<?php echo $_POST['remarks'];?>"onkeypress="return alphanumeric(event)"  /></div></td>
  </tr>
   <tr class="evenrow">
    <td ><div class="left">Prepared By: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="addedby"  size="45" maxlength="45" value="<?php echo $_SESSION['UsersRealName'];?>"  readonly="readonly" /><input type="hidden" name="preparedby"  size="45" maxlength="45" value="<?php echo $_SESSION['uid'];?>"  readonly="readonly" /></div></td>
  </tr>
  
  <tr class="oddrow">
   
    <td  align="center"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table></form>
<?php
include('includes/footer.inc');
?>
<?php
if (isset($_POST['submit']) ){
 
   if($_POST['itemdetails']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('itemdetails').className='ercol';</script>";
	}
   if (($_POST['itemdetails']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemdetails'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('itemdetails').className='ercol';</script>";
	}
    if($_POST['itemcode']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('itemcode').className='ercol';</script>";
	}
	
   if($date=='')
   {  echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate').className='date';</script>";
	}
    if($_POST['referencenum']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('referencenum').className='ercol';</script>";
	}
	if (($_POST['referencenum']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['referencenum'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('referencenum').className='ercol';</script>";
	}
	
	if($_POST['quantity_required']=='')
   {echo "<script type='text/javascript'>document.getElementById('quantity_required').className='ercol';</script>";
	}
	if (($_POST['quantity_required']!='') && (!is_numeric($_POST['quantity_required'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('quantity_required').className='ercol';</script>";
	}
	
	if($_POST['amountitem']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('amountitem').className='ercol';</script>";
	}
	if (($_POST['amountitem']!='') && (!is_numeric($_POST['amountitem'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('amountitem').className='ercol';</script>";
	}
	if($_POST['status']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('status').className='ercol';</script>";
	}
  
	if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('remarks').className='ercol';</script>";
	}
	}
?>