<?php
include('includes/session.inc');
$title = _('Add Item');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
 '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';

if (isset($_POST['submit']) ){
  $InputError = 0;
  
   //$date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
   $date=$_POST['JournalProcessDate'];
  if($_POST['category']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Category'),'error');
	}
  if($_POST['itemcode']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Item Code'),'error');
	}
	if($_POST['refnum']=='')
   { 
    /* echo '<script type="text/javascript">$("#refnum").innerHTML="ercol";</script>';*/
     $InputError = 1;
	/* echo "<script type='text/javascript'>document.getElementById('refnum').value='dsfds';</script>";*/
     prnMsg(_('Enter Reference Number'),'error');
	 
	}
	if (($_POST['refnum']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['refnum'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Reference Number'),'error');
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
	
	 if($_POST['billno']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Bill Number'),'error');
	}
     if (($_POST['billno']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['billno'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Bill Number, A-Z or 0-9 is Allowed'),'error');
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
	/*if($_POST['element_3_1']=='' || $_POST['element_3_2']=='' || $_POST['element_3_3']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Date'),'error');
	}*/
	if($_POST['JournalProcessDate']=='')
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
	}
	if($InputError!=1)
	{
	  $it="select * from item_master where code='".$_POST['itemcode']."'";
	  $itq=DB_query($it,$db);
	  $itn=DB_num_rows($itq);
	  $itr=DB_fetch_array($itq);
	  $qu=$itr['openingval'];
	  if($itn)
	  { 
	    $tqu=$qu+$_POST['quantity'];
	    $itu="update item_master set openingval='".$tqu."' where code='".$_POST['itemcode']."'";
		$ituq=DB_query($itu,$db);
	  }
	 $sql="insert into item_details set details='".$_POST['itemdetails']."',
										quantity='".$_POST['quantity']."',
										amountitem='".$_POST['amountitem']."',
										code='".$_POST['itemcode']."',
										refnum='".$_POST['refnum']."',
										remarks='".$_POST['remarks']."',
										category='".$_POST['category']."',
										 date='".strtotime($date)."',
										  billno='".$_POST['billno']."',
										 enteredby='".$_POST['enteredby']."',
										 checkedby='".$_POST['checkedby']."',
										verifyphysically='".$_POST['verifyphysically']."'";
	 $query=DB_query($sql,$db);		
	 if($query)	
	 {
	 unset($_POST);
	  unset($_REQUEST);
	  echo "<div class='success'>Item has been added succesfully</div>";
	 }						
	}
}

?>
<style>
.right .ercol{
border: 1px solid #FF0000;
 }
.left .ercol{
border: 1px solid #FF0000;
 }
</style>
<script>
function changeitem(a)
{  
  window.location.href="add_item.php?category="+a;
  
}

function validate()
{
//document.getElementById('refnum').value='ercol';
 //document.getElementById('refnum').className='ercol';
 //alert(document.getElementById('refnum').className);
 //$("#refnum").addClass("ercol");
}
</script>

<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Receive Item</a></div>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="form" >
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellspacing="1" cellpadding="2" border="0">
<tr class="oddrow"><td colspan="2" align="center"><h2>Receive Items</h2></td></tr>
 <tr class="evenrow"> 
    <td colspan="2"><div class="left">Reference No.: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" id="refnum" name="refnum"  size="45" maxlength="10"  value="<?php echo $_POST['refnum']?>" onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"><div class="left">Category: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="category" onchange="changeitem(this.value);" id="category"><option value="">--Select Category--</option>
    <?php
	$ic="SELECT categoryid, categorydescription FROM stockcategory";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{
	  if($_REQUEST['category']==$icr['categoryid'])
	 {
	  echo '<option value="'.$icr['categoryid'].'" selected>'.$icr['categorydescription'].'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$icr['categoryid'].'">'.$icr['categorydescription'].'</option>';
	  }
	}
	?>
	</select></div></td>
  </tr>
 <tr class="evenrow">
    <td colspan="2"><div class="left">Item Code: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="itemcode" id="itemcode"><option value="">--Select Item--</option>
    <?php
	$cate=$_REQUEST['category'];
	$ic="select code,name from item_master where category='".$cate."' order by code";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{
	  if($_REQUEST['itemcode']==$icr['code'])
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
  <tr class="oddrow">
    <td colspan="2"><div class="left">Item details: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="itemdetails" id="itemdetails" size="45" maxlength="45" value="<?php echo $_POST['itemdetails']?>" onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
 
  <tr class="evenrow">
    <td colspan="2"><div class="left">Quantity: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="quantity" id="quantity" size="45" maxlength="10" value="<?php echo $_POST['quantity']?>" onkeypress = "return fononlyn(event)" /></div></td>
  </tr>
 
  <tr class="oddrow">
    <td colspan="2"><div class="left">Bill No: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="billno" id="billno" size="45" maxlength="15" value="<?php echo $_POST['billno']?>" onkeypress="return alphanumeric(event)"  /></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"><div class="left">Amount per Items: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="amountitem" id="amountitem" size="45" maxlength="11" value="<?php echo $_POST['amountitem']?>" onkeypress = "return fononlyn(event)" /></div></td>
  </tr>
 <tr class="oddrow">
    <td colspan="2"><div class="left">Date: <span style="color:#FF0000">*</span></div>
    <div class="right"><!--<div id="li" style="width:353px;">
			<span>
			<input id="element_3_2" name="element_3_2" class="element text" size="2" style="width:40px;" maxlength="2" value="<?php echo $_POST['element_3_2'];?>" type="text" readonly="readonly"> 
			<label for="element_3_2">DD</label>
		</span>
            <span><input id="element_3_1" name="element_3_1" class="element text" style="width:40px;" size="2" maxlength="2" value="<?php echo $_POST['element_3_1'];?>" type="text" readonly="readonly"> /
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
		</script>	</div>--><div  id="date"><input type="text" id="JournalProcessDate" name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></td>
  </tr>
    <tr class="evenrow">
    <td colspan="2"><div class="left">Name of the person entered by: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="enteredby"  size="45" maxlength="45" value="<?php echo $_SESSION['UsersRealName'] ?>" readonly="readonly" onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"> <div class="left">Checked by: <span style="color:#FF0000">*</span></div>
   <div class="right"><input type="text" name="checkedby" id="checkedby" size="45" maxlength="45" value="<?php echo $_POST['checkedby'] ?>"onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"> <div class="left">Name of person who has verified the item physically: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="verifyphysically" id="verifyphysically" size="45" maxlength="45" value="<?php echo $_POST['verifyphysically'] ?>"onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
  <tr class="oddrow">
 <td colspan="2"> <div class="left">Remarks: </div>
    <div class="right"><input type="text" name="remarks"  size="45" maxlength="200" value="<?php echo $_POST['remarks'] ?>"onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
  
  <tr class="evenrow">
    <td colspan="2" align="center" ><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>
</form>
<?php include('includes/footer.inc'); ?>
<?php
if(isset($_POST['submit']))
{
  $date=$_POST['JournalProcessDate'];
  if($_POST['category']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('category').className='ercol';</script>";
	}
  if($_POST['itemcode']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('itemcode').className='ercol';</script>";
	}
	if($_POST['refnum']=='')
   { 
    /* echo '<script type="text/javascript">$("#refnum").innerHTML="ercol";</script>';*/
    // $InputError = 1;
	 echo "<script type='text/javascript'>document.getElementById('refnum').className='ercol';</script>";
     //prnMsg(_('Enter Reference Number'),'error');
	 
	}
	if (($_POST['refnum']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['refnum'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('refnum').className='ercol';</script>";
	}
   if($_POST['itemdetails']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('itemdetails').className='ercol';</script>";
	}
     if (($_POST['itemdetails']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemdetails'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('itemdetails').className='ercol';</script>";
	}
	
	 if($_POST['billno']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('billno').className='ercol';</script>";
	}
     if (($_POST['billno']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['billno'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('billno').className='ercol';</script>";
	}
	
	if($_POST['quantity']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('quantity').className='ercol';</script>";
	}
	if (($_POST['quantity']!='') && (!is_numeric($_POST['quantity'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('quantity').className='ercol';</script>";
	}
	if($_POST['amountitem']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('amountitem').className='ercol';</script>";
	}
	if (($_POST['amountitem']!='') && (!is_numeric($_POST['amountitem'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('amountitem').className='ercol';</script>";
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

	
	}
?>