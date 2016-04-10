<?php 

//echo "<br>".$_SERVER['REQUEST_URI'];
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');


function filecheck($path)
{
$fn=explode("/",$path);
  $mn=count($fn);
 return $fn[$mn-1];
  
}
$sr=filecheck($_SERVER['HTTP_REFERER']);
$sel=filecheck($_SERVER['PHP_SELF']);
if($sr==$sel)
{
 
}
else
{
        unset($_SESSION['date']);
		unset($_SESSION['officername']);
		unset($_SESSION['office']);
		unset($_SESSION['own_authority']);
		$tr="truncate table condem_item_group";
		$trq=DB_query($tr,$db);	
}
echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';

if (isset($_POST['submit']) ){
  $InputError = 0;

      $disdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
      $purdate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];

  if(!isset($_SESSION['date']) || $_SESSION['date']=='')
		{
		$_SESSION['date']=strtotime($disdate);
		}
		if($_SESSION['officername']=='')
		{
		$_SESSION['officername']=$_POST['officername'];
		}
		if($_SESSION['office']=='')
		{
		$_SESSION['office']=$_POST['office'];
		}
		if($_SESSION['own_authority']=='')
		{
		$_SESSION['own_authority']=$_POST['own_authority'];
		}

   if(($_POST['element_1_2']=='' || $_POST['element_1_1']=='' || $_POST['element_1_3']=='') && $_SESSION['date']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Date'),'error');
	}
   
    if($_POST['officername']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Officer Name'),'error');
	}
	 if (($_POST['officername']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['officername'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Officer Name A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['office']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Office'),'error');
	}
	
	if($_POST['own_authority']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Owning authority'),'error');
	}
	 if (($_POST['own_authority']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['own_authority'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Owning Authority, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['sr_no']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter S. No.'),'error');
	}
	if (($_POST['sr_no']!='') && (!is_numeric($_POST['sr_no'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For S. No'),'error');
	}
	
	if($_POST['code']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Item Code'),'error');
	}
   if($_POST['particulars']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Particulars'),'error');
	}
	 if (($_POST['particulars']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['particulars'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Particulars, A-Z or 0-9 is Allowed'),'error');
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
	if($_POST['weight']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Weight'),'error');
	}
	if (($_POST['weight']!='') && (!is_numeric($_POST['weight'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Weight'),'error');
	}
	if( $_POST['element_2_2']=='' || $_POST['element_2_1']=='' || $_POST['element_2_3']=='' )
   {
     $InputError = 1;
     prnMsg(_('Enter Purchase Date'),'error');
	}
  
	if($_POST['purchase_value']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Purchase value'),'error');
	}
	if (($_POST['purchase_value']!='') && (!is_numeric($_POST['purchase_value'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Purchase value'),'error');
	}
	if($_POST['present_condition']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Present condition'),'error');
	}
	 if (($_POST['present_condition']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['present_condition'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Present Condition, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['disposal_head']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Head of account to which disposal proceeds to be credited'),'error');
	}
	if($_POST['debit_head']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Head of account which the price of the article was debited at the time of purchase'),'error');
	}
	if($_POST['why_store']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Why such store indented'),'error');
	}
	 if (($_POST['why_store']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['why_store'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Why Store Reason, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['remark_page']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Remarks ledger page no '),'error');
	}
	if (($_POST['remark_page']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remark_page'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Remarks ledger page no, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['written_value']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Written down value '),'error');
	}
	if (($_POST['written_value']!='') && (!is_numeric($_POST['written_value'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value Written down value'),'error');
	}
	if($_POST['reserve_price']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Reserve price fixed by the committee '),'error');
	}
	if (($_POST['reserve_price']!='') && (!is_numeric($_POST['reserve_price'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value for Reserve Price'),'error');
	}
	if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Remarks, A-Z or 0-9 is Allowed'),'error');
	}

  if($InputError!=1)
	{ 
	  

	   $it="select * from item_master where code='".$_POST['code']."'";
	  $itq=DB_query($it,$db);
	  $itn=DB_num_rows($itq);
	  $itr=DB_fetch_array($itq);
	  $qu=$itr['openingval'];
	  if($qu>=$_POST['quantity'])
	  {
	   $tqu=$qu-$_POST['quantity'];
	   
	    $itu="update item_master set openingval='".$tqu."' where code='".$_POST['code']."'";
		$ituq=DB_query($itu,$db);
	   
		
		$s="insert into condem_item_group set 
									  sr_no='".$_POST['sr_no']."',
									  code='".$_POST['code']."',
									  particulars='".$_POST['particulars']."',
									  quantity='".$_POST['quantity']."',
									  weight='".$_POST['weight']."',
									  purchase_date='".strtotime($purdate)."',
									  purchase_value='".$_POST['purchase_value']."',
									  present_condition='".$_POST['present_condition']."',
									  disposal_head='".$_POST['disposal_head']."',
									  debit_head='".$_POST['debit_head']."',
									  why_store='".$_POST['why_store']."',
									  remark_page='".$_POST['remark_page']."',
									  written_value='".$_POST['written_value']."',
									  reserve_price='".$_POST['reserve_price']."',
									  remarks='".$_POST['remarks']."'";
		$q=DB_query($s,$db);
		if($q)
		{
		unset($_POST);	
		echo "<div class='success'>Condem Item has been added successfully</div>";
		}	  
	  }
	  else
	  {
	     
	     prnMsg(_('This Quantity Is Not Available In Stock'),'error');
	  }
	
    }
	
}
if(isset($_POST['save']))
{  
    $con="select * from condem_item_group";
	$conq=DB_query($con,$db);
	while($conr=DB_fetch_array($conq))
	{

    $s1="insert into condem_item set date='".$_SESSION['date']."',
	                                  officername='".$_SESSION['officername']."',
									  office='".$_SESSION['office']."',
									  own_authority='".$_SESSION['own_authority']."',
									  sr_no='".$conr['sr_no']."',
									  code='".$conr['code']."',
									  particulars='".$conr['particulars']."',
									  quantity='".$conr['quantity']."',
									  weight='".$conr['weight']."',
									  purchase_date='".$conr['purchase_date']."',
									  purchase_value='".$conr['purchase_value']."',
									  present_condition='".$conr['present_condition']."',
									  disposal_head='".$conr['disposal_head']."',
									  debit_head='".$conr['debit_head']."',
									  why_store='".$conr['why_store']."',
									  remark_page='".$conr['remark_page']."',
									  written_value='".$conr['written_value']."',
									  reserve_price='".$conr['reserve_price']."',
									  remarks='".$conr['remarks']."'";
		$q1=DB_query($s1,$db);
		
		}	
		
		$tr="truncate table condem_item_group";
		$trq=DB_query($tr,$db);	
		unset($_SESSION['date']);
		unset($_SESSION['officername']);
		unset($_SESSION['office']);
		unset($_SESSION['own_authority']);
		//neshat khan 
		//unset($_SESSION['officername']);
		//unset($_SESSION['office']);
		//unset($_SESSION['own_authority']);
}

?>
<link href="images/style.css" rel="stylesheet" type="text/css" />
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Condemnation</a></div>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0">
<tr class="oddrow"><td colspan="2" align="center"><h2>Condemnation</h2></td></tr>
 <tr class="evenrow">
   <td colspan="2"> <div class="left">Date: <span style="color:#FF0000">*</span></div>
    <div class="right"><div id="li_1" style="width:353px;" >
		<span>
			<input id="element_1_2" name="element_1_2" class="element text" style="width:40px;" align="middle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_2'];?>" readonly="readonly"> 
			<label for="element_1_2">DD</label>
		</span>
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" style="width:40px;" align="absmiddle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_1'];?>" readonly="readonly"> /
			<label for="element_1_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" style="width:67px;" align="middle" size="4" maxlength="4"  type="text" value="<?php echo $_POST['element_1_2'];?>" readonly="readonly">/
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
		</div></div></td>
  </tr>
  
  <tr class="oddrow">
    <td colspan="2"> <div class="left">Name of officer to be contacted: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="officername"  size="45" maxlength="45" value="<?php echo $_POST['officername'];?>"onkeypress="return alphanumeric(event)"  ></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"> <div class="left">Location of stores: <span style="color:#FF0000">*</span></div>
    <div class="right"><select  name="office"><option Value="">---Select Office-</option>
	<?php
	$sql = "SELECT loccode, locationname FROM locations";
$resultStkLocs = DB_query($sql,$db);
while ($myrow=DB_fetch_array($resultStkLocs)){ 
 
      if($_POST['office']==$myrow['loccode'])
	  {
	    echo'<option Value="'.$myrow['loccode'].'" selected>'.$myrow['locationname'].'</option>';
	  }
	  else
	  {
	   echo'<option Value="'.$myrow['loccode'].'">'.$myrow['locationname'].'</option>';
		}
		 
	}
?>

</select>
	</div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"> <div class="left">Owning authority: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="own_authority"  size="45" maxlength="45"  value="<?php echo $_POST['own_authority'];?>" onkeypress="return alphanumeric(event)"  ></div></td>
  </tr>


    <tr class="evenrow"> <td colspan="2"> <div class="left">S. No.: <span style="color:#FF0000">*</span></div> <div class="right"><input type="text" name="sr_no"  size="45" maxlength="10" value="<?php echo $_POST['sr_no']?>" onkeypress = "return fononlyn(event)" /></div></td></tr>
    <tr class="oddrow"><td colspan="2"> <div class="left">Item Code: <span style="color:#FF0000">*</span></div><div class="right"><select name="code"><option value="">--Select Item--</option>
    <?php
	$ic="select code,name from item_master";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{ if($_POST['code']==$icr['code'])
	{
	echo '<option value="'.$icr['code'].'" selected>'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
	}
	else
	{
	  echo '<option value="'.$icr['code'].'">'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
	}
	}
	?>
	</select></div></td></tr>
	<tr class="evenrow"><td colspan="2"> <div class="left">Particulars of store items: <span style="color:#FF0000">*</span></div><div class="right"><input type="text" name="particulars"  size="45" maxlength="45" value="<?php echo $_POST['particulars']?>"onkeypress="return alphanumeric(event)" /></div></td></tr>
	<tr class="oddrow"><td colspan="2"> <div class="left">Quantity: <span style="color:#FF0000">*</span></div><div class="right"><input type="text" name="quantity"  size="45" maxlength="10" value="<?php echo $_POST['quantity']?>" onkeypress = "return fononlyn(event)" /></div></td></tr>
	<tr class="evenrow"><td colspan="2"> <div class="left">Weight: <span style="color:#FF0000">*</span></div> <div class="right"><input type="text" name="weight"  size="45" maxlength="11" value="<?php echo $_POST['weight']?>" onkeypress = "return fononlyn(event)" /></div></tr>
	<tr class="oddrow"><td colspan="2"> <div class="left">Date of Purchase: <span style="color:#FF0000">*</span></div><div class="right"><div id="li_2" style="width:353px;">
	<span>
			<input id="element_2_2" name="element_2_2" class="element text" style="width:40px;" align="middle"  size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_2_2'];?>" readonly="readonly"> 
			<label for="element_2_2">DD</label>
		</span>
    
		<span>
			<input id="element_2_1" name="element_2_1" class="element text" style="width:40px;" align="middle"  size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_2_1'];?>" readonly="readonly"> /
			<label for="element_2_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_2_3" name="element_2_3" class="element text" style="width:67px;" align="middle"  size="4" maxlength="4"  type="text" value="<?php echo $_POST['element_2_3'];?>" readonly="readonly">/
			<label for="element_2_3">YYYY</label>
		</span>
	
		<span id="calendar_2">
			<img id="cal_img_2" class="datepicker" src="calendar.gif" alt="Pick a date.">		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_2_3",
			baseField    : "element_2",
			displayArea  : "calendar_2",
			button		 : "cal_img_2",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		</div></div></td></tr>
	<tr class="evenrow"> <td colspan="2"> <div class="left">Purchase Value: <span style="color:#FF0000">*</span></div> <div class="right"><input type="text" name="purchase_value"  size="45" maxlength="11" value="<?php echo $_POST['purchase_value']?>" onkeypress = "return fononlyn(event)" /></div></td></tr>
	<tr class="oddrow"><td colspan="2"> <div class="left">Present Condition: <span style="color:#FF0000">*</span></div> <div class="right"><input type="text" name="present_condition"  size="45" maxlength="45" value="<?php echo $_POST['present_condition']?>" onkeypress="return alphanumeric(event)"  /></div></td></tr>
	<tr class="evenrow"><td colspan="2"> <div class="left">Head of account to which disposal proceeds to be credited: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="disposal_head" ><option value="">--Select--</option>
	<?php
      $ac="select * from chartmaster ORDER BY accountname ASC";
	  $acq=DB_query($ac,$db);
	  while($acr=DB_fetch_array($acq))
	  {	
	  if($_POST['disposal_head']==$acr['accountcode']) {?>
      <option value="<?php echo ucwords($acr['accountcode']);?>"  selected="selected"><?php echo ucwords ($acr['accountname']);?></option>
      <?php
	  }
	  else {
	  ?>
       <option value="<?php echo ucwords( $acr['accountcode']);?>" ><?php echo ucwords ($acr['accountname']);?></option>
      <?php
	  } }?>
      </select></div></td></tr>
	<tr class="oddrow"> <td colspan="2"> <div class="left">Head of account which the price of the article was debited at the time of purchase: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="debit_head" ><option value="">--Select--</option>
	<?php
      $acd="select * from chartmaster ORDER BY accountname ASC";
	  $acdq=DB_query($acd,$db);
	  while($acrd=DB_fetch_array($acdq))
	  {	
	   if($_POST['debit_head']==$acrd['accountcode']) {?>
      <option value="<?php echo ucwords($acrd['accountcode']);?>" selected="selected"><?php echo ucwords($acrd['accountname']);?></option>
      <?php
	  }
	  else {
	  ?>
       <option value="<?php echo ucwords ($acrd['accountcode']);?>"><?php echo ucwords($acrd['accountname']);?></option>
       <?php
	  } }?>
      </select></div></td></tr> 
	<tr class="evenrow"><td colspan="2"> <div class="left">Why such store indented: <span style="color:#FF0000">*</span></div> <div class="right"><input type="text"  name="why_store"  size="45" value="<?php echo $_POST['why_store']?>" maxlength="200" onkeypress="return alphanumeric(event)" /></div></td></tr>
	<tr class="oddrow"><td colspan="2"> <div class="left">Remarks ledger page no: <span style="color:#FF0000">*</span></div><div class="right"><input type="text" name="remark_page"  size="45" maxlength="10" value="<?php echo $_POST['remark_page']?>" onkeypress="return alphanumeric(event)" /></div></td></tr>
	<tr class="evenrow"><td colspan="2"> <div class="left">Written down value: <span style="color:#FF0000">*</span></div> <div class="right"><input type="text" name="written_value"  size="45" maxlength="11" value="<?php echo $_POST['written_value']?>" onkeypress = "return fononlyn(event)"  /></div></td></tr>
	<tr class="oddrow"><td colspan="2"> <div class="left">Reserve price fixed by the committee: <span style="color:#FF0000">*</span></div> <div class="right"><input type="text"  name="reserve_price"  size="45" maxlength="11" value="<?php echo $_POST['reserve_price']?>" onkeypress = "return fononlyn(event)"  /></div></td></tr>
	<tr class="evenrow"><td colspan="2"> <div class="left">Remarks: </div> <div class="right"><input type="text"  name="remarks"  size="45"  maxlength="200" value="<?php echo $_POST['remarks']?>" onkeypress="return alphanumeric(event)" /></div></td></tr>
   
      
    <tr class="oddrow"><td colspan="2" align="center"><input  type="submit" name="submit" value="Submit" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table></form>
<?php
$sg="select * from condem_item_group";
$sgq=DB_query($sg,$db);
$sgn=Db_num_rows($sgq);
if($sgn)
{
  $data="<form action='' method='post' name='form'>
  <input type='hidden' name='FormID' value='". $_SESSION['FormID'] ."' />
  <br/><table><tr><th>S. No.</th><th>Item Code</th><th>Quantity</th><th>Purchase Value</th><th>Present condition</th></tr>";
  while($sgr=DB_fetch_array($sgq))
  {
    $data.="<tr class='even'><td>".$sgr['sr_no']."</td><td>".$sgr['code']."</td><td>".$sgr['quantity']."</td><td>".$sgr['purchase_value']."</td><td>".$sgr['present_condition']."</td></tr>";
  }
  
  $data.="<tr class='odd'><td colspan='6' align='center'><input  type='submit' name='save' value='Save' /></td></tr></table></form>";
  echo $data;
}
?>
<?php include('includes/footer.inc'); ?>
