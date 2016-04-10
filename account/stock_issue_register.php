<?php
include('includes/session.inc');
$title = _('Stock Issue Register');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/*echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
*/
//$u=explode('/',$_SERVER['REQUEST_URI']);
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Stock Issue register</a></div>';
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

$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];

if($_POST['JournalProcessDate']=='' )
   {
     //$InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($_POST['JournalProcessDate1']=='' )
   {
     //$InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
   //  $InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}

   /*  if($curdat<strtotime($edate))
 {
 //$InputError = 1;
     prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }*/
} 
?>

<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table  border="0" cellpadding="2" cellspacing="1" style="border:none;">
 <tr>	<td align="left" class="tdform-width"><fieldset><legend>Stock Issue Register</legend>
 <table align="left" class="frmtbl"> 	
		<tr>
        	<td><b>Item Code:</b></td>
        	<td><div class="maincol"><select name="itemcode"><option value="">--Select Item--</option>
    <?php
	$ic="select code,name from item_master order by code";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{  if($_POST['itemcode']==$icr['code'])
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
        	<td><b>From Date: <span style="color:#FF0000">*</span></b></td>
        	<td><div class="maincol"><!--<div id="li_1" style="width:170px;">
		<span>
			<input id="element_1_2" name="element_1_2" class="element text" style="width:15px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_1_2'];?>" type="text"> /
			<label for="element_1_2"></label>
		</span>
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" style="width:15px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_1_1'];?>" type="text"> /
			<label for="element_1_1"></label>
		</span>
		
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" style="width:30px;" align="middle" size="4" maxlength="4" value="<?php echo $_POST['element_1_3'];?>" type="text"> /
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
		</div>--><div id="date"><input type="text" id="JournalProcessDate" name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></td>
        	<td><b>To Date: <span style="color:#FF0000">*</span></b></td>
        	<td><div class="maincol"><!--<div id="li_2" style="width:170px;" >
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
		</div>--><div  id="date"><input type="text" id="JournalProcessDate1" name="JournalProcessDate1" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat1'];?>')" value="<?php echo $_POST['JournalProcessDate1'];?>"></div></div></td>                                                            
        <td><input  type="submit" name="submit" value="Generate" /></td>       

</table></fieldset>
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

$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];

if($_POST['JournalProcessDate']=='' )
   {
     $InputError = 1;
     //prnMsg(_('Select From Date'),'error');
	}
	if($_POST['JournalProcessDate1']=='' )
   {
     $InputError = 1;
     //prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     //prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
/*
     if($curdat<strtotime($edate))
 {
 $InputError = 1;
    // prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }*/
 if($InputError!=1)
	{  $cond='';
	  if(isset($_POST['itemcode']) && $_POST['itemcode']!='')
	  {
	    $cond.= "and im.code='".$_POST['itemcode']."'";
	  }
	 
	 $s="select at.itemdetails,at.date,at.quantity,im.name,im.itemrate,at.enteredby,im.openingval,at.checkedby,at.verifyphysically from assigned_item as at,item_master as im where 1=1 ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.itemdetails=im.code ";
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	 if($n)
	{
  $rdata="
          <table  cellpadding='2' cellspacing='1' >
		  <tr class=oddrow><td colspan='11'><h2>Stock Issue Register</h2></td></tr>
		  <tr><td colspan='11' align='right'><a href='/".$u[1]."/generateinventorypdf.php?op=issue&sdate=".strtotime($sdate)."&edate=".strtotime($edate)."&itemcode=".$_POST['itemcode']."&branch=".$corpbranch."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
<tr><th align='center'>S. No.</th>
<th>Name of the article</th>
<th>Date</th>
<th>Quantity</th>
<th>Rate</th>
<th>Amount</th>
<th>Balance Quantity</th>
<th>Balance Amount</th>
<th>Entered by</th>
<th>Checked by</th>
<th width=150>Name of person who has verified the item physically</th>
</tr>
";
	
	$i=1;
	
	  while($r=DB_fetch_array($q))
	  { 
	     if($i%2==0)
     {
	   $cl="even";
	 }
	 else
	 {
	   $cl="odd";
	 }
	    $rdata.="<tr class='".$cl."'><td align='center'>".$i."</td><td align='left'>".ucwords($r['name'])."</td><td width='80'>".date('d-m-Y',$r['date'])."</td><td align='right'>".$r['quantity']."</td><td align='right'>".$r['itemrate']."</td><td align='right'>".($r['quantity']*$r['itemrate'])."</td><td align='right'>".$r['openingval']."</td><td align='right'>".($r['openingval']*$r['itemrate'])."</td><td align='left'>".ucwords($r['enteredby'])."</td><td align='left'>".ucwords($r['checkedby'])."</td><td align='left'>".ucwords($r['verifyphysically'])."</td></tr>";
		$i++;
	  }
	
	  
	  $rdata.="<tr><td>&nbsp;</td></tr></table>";
	echo $rdata;
	}
	else
	{
	  echo "No Result Found";
	}
}
}
include('includes/footer.inc');
?>