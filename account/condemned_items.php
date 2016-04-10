<?php
include('includes/session.inc');
$title = _('Condemned items');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/*echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'>">Condemned Items</a></div>';
if (isset($_POST['submit']) ){
if(substr(date('d'),0,1)=='0')
			 {
			  $d= substr(date('d'),1);
			  $m=date('m');
			  $y= date('Y');
			 }
			 else
			 {
			  $d= date('d');
			  $m=date('m');
			  $y= date('Y');
			 }
			 $curdat=strtotime($d."-".$m."-".$y);
  $InputError = 0;
/*$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];*/
$sdate=$_POST['JournalProcessDate'];
$edate=$_POST['JournalProcessDate1'];

if($_POST['JournalProcessDate']=='' )
   {
    // $InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($_POST['JournalProcessDate1']==''  )
   {
     //$InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
     //$InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
	 /*if($curdat<strtotime($edate))
 {
 //$InputError = 1;
     prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }*/
}	

?>

<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
 <tr>	<td align="left" class="tdform-width"><fieldset><legend>Condemned Items</legend>
 <table align="left" class="frmtbl">
  	<tr> 	<td><div class="divwrapper"><div class="maincol"><b>From Date: </b><span style="color:#FF0000">*</span></div>
    <div class="maincol"><!--<div id="li_1" style="width:125px;">
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
		</div>--><div  id="date"><input type="text" id="JournalProcessDate" name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></div></td>
  <td><div class="divwrapper"><div class="maincol"><b>To Date:</b> <span style="color:#FF0000">*</span></div>
    <div class="maincol"><!--<div id="li_2" style="width:125px;">
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
		</div>--><div  id="date"><input type="text" id="JournalProcessDate1" name="JournalProcessDate1" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat1'];?>')" value="<?php echo $_POST['JournalProcessDate1'];?>"></div></div></div></td>
 <td> <div class="divwrapper"><div class="generatebtn"><input  type="submit" name="submit" value="Generate" />
		</div></td>
		</tr>
		
		</tr></table></filedset>
		</table></form>
<?php 
if(substr(date('d'),0,1)=='0')
			 {
			  $d= substr(date('d'),1);
			  $m=date('m');
			  $y= date('Y');
			 }
			 else
			 {
			  $d= date('d');
			  $m=date('m');
			  $y= date('Y');
			 }
			 $curdat=strtotime($d."-".$m."-".$y);
if (isset($_POST['submit']) ){
  $InputError = 0;
/*$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];*/
$sdate=$_POST['JournalProcessDate'];
$edate=$_POST['JournalProcessDate1'];

if($_POST['JournalProcessDate']=='' )
   {
     $InputError = 1;
     //prnMsg(_('Select From Date'),'error');
	}
	if($_POST['JournalProcessDate1']==''  )
   {
     $InputError = 1;
     //prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     //prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
	/* if($curdat<strtotime($edate))
 {
    $InputError = 1;
    // prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }*/

 if($InputError!=1)
	{  
	  $s="select at.code,at.date,at.quantity,im.name,im.itemrate,at.particulars,im.openingval,at.written_value from condem_item as at,item_master as im where (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.code=im.code";
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	if($n)
	{
  $rdata="
          <table>
		  <tr class=oddrow><td colspan=7><h2>Condemned Items</h2></td></tr>
		  <tr><td colspan=7 align='right'><a href='/".$u[1]."/generateinventorypdf.php?op=condemned&sdate=".strtotime($sdate)."&edate=".strtotime($edate)."&branch=".$corpbranch."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
<tr><th width='91' align='center'><b>S. No.</b></th>
<th width='91'><b>Name of the article</b></th>
<th width='50'><b>Date of condemn</b></th>
<th width='50'><b>Quantity</b></th>
<th width='39'><b>Particulars</b></th>
<th width='82'><b>Depreciation value</b></th>
<th width='114'><b>Amount received</b></th>

</tr>
";
	 $i=1;
	
	if(isset($_GET['page']) && $_GET['page']>1)
	{
	  $pp=($_GET['page']*10)+11;
	}
	else if(isset($_GET['page']) && $_GET['page']==0)
	{
	  $pp=11;
	}
	else if(isset($_GET['page']) && $_GET['page']==1)
	{
	  $pp=21;
	}
	else
	{
	  $pp=1;
	}
    $nn=1*($pp);
	  while($r=DB_fetch_array($q))
	  {  if($i%2==0)
     {
	   $cl="even";
	 }
	 else
	 {
	   $cl="odd";
	 }
	    $rdata.="<tr class='".$cl."'><td align='center'>".$nn."</td><td>".ucwords($r['name'])."</td><td align='center'>".date('d-m-Y',$r['date'])."</td><td align='right'>".abs($r['quantity'])."</td><td>".ucwords($r['particulars'])."</td><td align='right'>".abs(($r['quantity']*$r['itemrate']))."</td><td align='right'>".$r['written_value']."</td></tr>";
		$i++;
		$nn++;
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