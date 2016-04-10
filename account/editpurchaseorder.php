<?php
include('includes/session.inc');
$title = _('Purchase Order');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/*'<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb">Home &raquo; <a href="purchase_order_detail.php">List of Requisition</a> &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Edit Requisition Form </a></div>';
$po="select od.itemcode,od.itemdetails,od.amountitem,od.remarks,od.referencenum,od.quantity_required,od.date,od.status,im.name from purchase_order_details as od,item_master as im where  im.code=od.itemcode and od.id='".$_REQUEST['id']."'";
$poq=DB_query($po,$db);
$por=DB_fetch_array($poq);

 
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
     prnMsg(_('Enter Item Code'),'error');
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
     prnMsg(_('Enter Date'),'error');
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
	  
	 $s="update purchase_order_details set itemdetails='".$_POST['itemdetails']."',
	                                             referencenum='".$_POST['referencenum']."',
												 quantity_required='".$_POST['quantity_required']."',
												 date='".strtotime($date)."',
												 amountitem='".$_POST['amountitem']."',
												 status='".$_POST['status']."',
												 remarks='".$_POST['remarks']."' 
												 where id='".$_POST['id']."'";
	 $q=DB_query($s,$db);
	 if($q)
	 { 
	  header("location:purchase_order_detail.php?msg=Purchase Order Updated");
	  echo "Purchase Order Updated";
	 }
	}
}
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $_REQUEST['id'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="448" height="148" border="0">
<tr class="oddrow">
    <td  colspan="2" align="center"><h2>Edit Requisition Form</h2></td>
	</tr>
  <tr class="evenrow">
   <td colspan="2"> <div class="left">Items/Equipment details: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="itemdetails"  size="45" maxlength="45"  value="<?php echo $por['itemdetails'];?>" onkeypress="return alphanumeric(event)"/><input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" onkeypress="return alphanumeric(event)"></div></td>
  </tr>
   <tr class="oddrow">
    <td colspan="2"> <div class="left">Items Code: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="itemcode"><option value="">--Select Item--</option>
    <?php
	$ic="select code,name from item_master";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{ 
	  if($icr['code']==$por['itemcode'])
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
    <td colspan="2"> <div class="left">Reference No. : <span style="color:#FF0000">*</span></div>
   <div class="right"><input type="text" name="referencenum"  size="45" maxlength="10"  value="<?php echo $por['referencenum'];?>" onkeypress="return alphanumeric(event)"/></div></td>
  </tr>
  
  <tr class="oddrow">
  <td colspan="2"> <div class="left">Quantity Required: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="quantity_required"  size="45" maxlength="10" value="<?php echo $por['quantity_required'];?>" onkeypress = "return fononlyn(event)"/></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"> <div class="left">Date: <span style="color:#FF0000">*</span></div>
    <div class="right"><!--<div id="li_1" style="width:355px;" >
		<span>
			<input id="element_1_2" name="element_1_2" class="element text" style="width:40px;" size="2" maxlength="2"  type="text" 
            value="<?php 
			if(substr(date('d',$por['date']),0,1)=='0')
			{
			  echo substr(date('d',$por['date']),1);
			}else
			{
			  echo date('d',$por['date']);
			}
			?>"> 
			<label for="element_1_2">DD</label>
		</span>
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" style="width:40px;" size="2" maxlength="2"  type="text" value="<?php echo date('m',$por['date']);?>"> /
			<label for="element_1_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" style="width:67px;" size="4" maxlength="4"  type="text" value="<?php echo date('Y',$por['date']);?>"> /
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
		</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo date('d-m-Y',$por['date']);?>"></div></div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"> <div class="left">Amount of the items: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="amountitem"  size="45" maxlength="10" value="<?php echo $por['amountitem'];?>" onkeypress = "return fononlyn(event)"/></div></td>
  </tr>
 
  <tr class="evenrow">
   <td colspan="2"> <div class="left">Status: <span style="color:#FF0000">*</span></div>
    <div class="right">
	<select name="status">
	<option value="pending" <?php if($por['status']=='pending'){ echo "selected"; }  ?>>Pending</option>
	<option value="po_generated" <?php if($por['status']=='po_generated'){ echo "selected"; }  ?>>PO Generated</option>
    <option value="supplied_from_stor" <?php if($por['status']=='supplied_from_stor'){ echo "selected"; }  ?>>Supplied From Store</option>
	</select></div></td>
  </tr>
  <tr class="oddrow">
  <td colspan="2"> <div class="left">Remarks :</div>
    <div class="right"><input type="text" name="remarks"  size="45" maxlength="200" value="<?php echo $por['remarks'];?>" onkeypress="return alphanumeric(event)"/></div></td>
  </tr>
  
  <tr class="evenrow">
    
    <td colspan="2" align="center"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table></form>
<?php include('includes/footer.inc');?>
