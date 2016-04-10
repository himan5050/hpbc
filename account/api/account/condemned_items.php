<?php
include('includes/session.inc');
$title = _('Condemned items');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';


?>
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Condemned Items</a></div>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
 <tr>	<td align="left" class="tdform-width"><fieldset><legend>Condemned Items</legend>
 <table align="left" class="frmtbl">
  	<tr> 	<td><div class="divwrapper"><div class="maincol">From Date:</div>
    <div class="maincol"><div id="li_1" style="width:125px;">
		<span>
			<input id="element_1_2" name="element_1_2" class="element text" style="width:15px;" align="middle"  size="2" maxlength="2" value="<?php echo $_POST['element_1_2'];?>" type="text"> /
			<label for="element_1_2"></label>
		</span>
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" style="width:15px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_1_1'];?>" type="text"> /
			<label for="element_1_1"></label>
		</span>
		
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" style="width:30px;" align="middle"  size="4" maxlength="4" value="<?php echo $_POST['element_1_3'];?>" type="text"> /
			<label for="element_1_3"></label>
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
		</div></div></div></td>
  <td><div class="divwrapper"><div class="maincol">To Date:</div>
    <div class="maincol"><div id="li_2" style="width:125px;">
	<span>
			<input id="element_2_2" name="element_2_2" class="element text" style="width:15px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_2_2'];?>" type="text"> /
			<label for="element_2_2"></label>
		</span>
		<span>
			<input id="element_2_1" name="element_2_1" class="element text" style="width:15px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_2_1'];?>" type="text"> /
			<label for="element_2_1"></label>
		</span>
		
		<span>
	 		<input id="element_2_3" name="element_2_3" class="element text" style="width:30px;" align="middle" size="4" maxlength="4" value="<?php echo $_POST['element_2_3'];?>" type="text"> /
			<label for="element_2_3"></label>
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
		</div></div></div></td>
 <td> <div class="divwrapper"><div class="generatebtn"><input  type="submit" name="submit" value="Generate" />
		</div></td>
		</tr>
		
		</tr></table></filedset>
		</table></form>
<?php 
if (isset($_POST['submit']) ){
  $InputError = 0;
$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];

if($_POST['element_1_2']=='' || $_POST['element_1_1']=='' || $_POST['element_1_3']=='')
   {
     $InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($_POST['element_2_2']=='' || $_POST['element_2_1']=='' || $_POST['element_2_3']=='' )
   {
     $InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='--' && $edate!='--' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}

 if($InputError!=1)
	{  
	  $s="select at.code,at.date,at.quantity,im.name,im.itemrate,at.particulars,im.openingval,at.written_value from condem_item as at,item_master as im where (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.code=im.code";
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	if($n)
	{
  $rdata="
          <table>
		  <tr class=oddrow><td colspan=6><h2>Condemned Items</h2></td></tr>
		  <tr><td colspan=6 align='right'><a href='/".$u[1]."/generateinventorypdf.php?op=condemned&sdate=".strtotime($sdate)."&edate=".strtotime($edate)."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
<tr><th width='91'><b>Name of the article</b></th>
<th width='50'><b>Date of condemn</b></th>
<th width='50'><b>Quantity</b></th>
<th width='39'><b>Particulars</b></th>
<th width='82'><b>Depreciation value</b></th>
<th width='114'><b>Amount received</b></th>

</tr>
";
	 $i=1;
	
	
	  while($r=DB_fetch_array($q))
	  {  if($i%2==0)
     {
	   $cl="even";
	 }
	 else
	 {
	   $cl="odd";
	 }
	    $rdata.="<tr class='".$cl."'><td>".ucwords($r['name'])."</td><td align='center'>".date('d-m-Y',$r['date'])."</td><td align='right'>".$r['quantity']."</td><td>".ucwords($r['particulars'])."</td><td align='right'>".($r['quantity']*$r['itemrate'])."</td><td align='right'>".$r['written_value']."</td></tr>";
		$i++;
	  }
	
	  
	  $rdata.="<tr><td>&nbsp;</td></tr></table>";
	echo $rdata;
	}
	else
	{
	  echo "<div class='error'>No Result Found</div>";
	}
}
}
?>
<?php include('includes/footer.inc');?>