<?php
include('includes/session.inc');
$title = _('Stock Register');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/*echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Stock Register</a></div>';


if (isset($_REQUEST['submit']) ){
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
  $qua=0;
 /*$sdate=$_REQUEST['element_1_2']."-".$_REQUEST['element_1_1']."-".$_REQUEST['element_1_3'];
$edate=$_REQUEST['element_2_2']."-".$_REQUEST['element_2_1']."-".$_REQUEST['element_2_3'];*/

$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];


if($_REQUEST['JournalProcessDate']=='')
   {
    // $InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($_REQUEST['JournalProcessDate1']=='' )
   {
     //$InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	
if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
     //$InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
    /* if($curdat<strtotime($edate))
 {
    // $InputError = 1;
     prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }*/
} 
?>
<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
<tr>	
	<td align="left" class="tdform-width">
    <fieldset><legend>Stock Register</legend>
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
            <td><div class="maincol"><!--<div id="li_1" style="width:125px;">
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
		</div>--><div id="date"><input type="text" id="JournalProcessDate" name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></td>
            <td><b>To Date: <span style="color:#FF0000">*</span></b></td>
            <td><div class="maincol"><!--<div id="li_2" style="width:125px;">
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
     
		</div>		
		</table>
     </fieldset>
     </td></tr>
		</table><br /></form>
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
			 
if (isset($_REQUEST['submit']) ){
  $InputError = 0;
  $qua=0;
 /*$sdate=$_REQUEST['element_1_2']."-".$_REQUEST['element_1_1']."-".$_REQUEST['element_1_3'];
$edate=$_REQUEST['element_2_2']."-".$_REQUEST['element_2_1']."-".$_REQUEST['element_2_3'];*/

$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];


if($_REQUEST['JournalProcessDate']=='')
   {
     $InputError = 1;
     //prnMsg(_('Select From Date'),'error');
	}
	if($_REQUEST['JournalProcessDate1']=='' )
   {
     $InputError = 1;
     //prnMsg(_('Select To Date'),'error');
	}
	
if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     //prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
     /*if($curdat<strtotime($edate))
 {
 $InputError = 1;
     //prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }*/
 if($InputError!=1)
	{
	  $cond='';
	  if(isset($_REQUEST['itemcode']) && $_REQUEST['itemcode']!='')
	  {
	    $cond.= "and im.code='".$_REQUEST['itemcode']."'";
		$opb="select openval from item_master where code='".$_REQUEST['itemcode']."'";
	  $opbq=DB_query($opb,$db);
	  $opbr=DB_fetch_array($opbq);
	  $op_bal=$opbr['openval'];
	  }
	  
	  
	  
	 $s="select at.code,at.date,at.quantity,im.name,im.itemrate,at.enteredby,im.openingval,at.checkedby,at.details,at.remarks,at.billno from item_details as at,item_master as im where 1=1 ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.code=im.code ";
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	
	$sa="select at.itemdetails,at.date,at.quantity,im.name,im.itemrate,at.enteredby,im.openingval,at.checkedby,at.remarks,at.office from assigned_item as at,item_master as im where 1=1 ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.itemdetails=im.code ";
	$qa=DB_query($sa,$db);
	$na=DB_num_rows($qa);
	$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	if($n || $na)
	{
	
  $rdata="<div class='report-hscroll'>
          <table  cellpadding='2' cellspacing='1'>
		  <tr class=oddrow><td colspan=9><h2>Stock Register</h2></td></tr>
		  <tr><td align='right' colspan='10'><a href='/".$u[1]."/generateinventorypdf.php?op=stock&sdate=".strtotime($sdate)."&edate=".strtotime($edate)."&item=".$_POST['itemcode']."&branch=".$corpbranch."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>";
		   if(isset($_REQUEST['itemcode']) && $_REQUEST['itemcode']!='')
	  {
		  $rdata.="<tr><td align='center' colspan='10'><b>Opening Balance:</b> ".$op_bal."</td></tr>";
		  }
$rdata.="<tr class='evenrow'><th>S. No.</th>
<th>Name of the article</th>
<th>Date</th>
<th>Particulars</th>
<th>Bill No.</th>
<th>Receive Quantity</th>
<th>Issue Quantity</th>
<th>Balance Quantity</th>
<th>Entered by</th>
</tr>
";
	
	$m=1;
	if($n)
	{
	  while($r=DB_fetch_array($q))
	  { $qua+=$r['quantity'];
	    if($m%2==0)
		{
		  $cl="even";
		}
		else
		{
		  $cl="odd";
		}
	    $rdata.="<tr class='".$cl."'><td>".$m."</td><td>".ucwords($r['name'])."</td><td align='center'>".date('d-m-Y',$r['date'])."</td><td>".ucwords($r['details'])."<br>".ucwords($r['remarks'])."</td><td>".$r['billno']."</td><td align='right'>".$r['quantity']."</td><td></td><td align='right'>".round(abs($qua))."</td><td>".ucwords($r['enteredby'])."</td></tr>";
		$m++;
	  }
	}
	
	
	if($na)
	{
	  while($ra=DB_fetch_array($qa))
	  {  if($m%2==0)
		{
		  $cl="even";
		}
		else
		{
		  $cl="odd";
		}
	     $sql = "SELECT loccode, locationname FROM locations where loccode='".$ra['office']."'";
             $resultStkLocs = DB_query($sql,$db);
			 $myrow=DB_fetch_array($resultStkLocs);
	      $qua-=$ra['quantity'];
	    $rdata.="<tr class='".$cl."'><td>".$m."</td><td>".ucwords($ra['name'])."</td><td align='center'>".date('d-m-Y',$ra['date'])."</td><td>Issued To: ".$myrow['locationname']."<br>".ucwords($ra['remarks'])."</td><td></td><td></td><td align='right'>".$ra['quantity']."</td><td align='right'>".round(abs($qua))."</td><td>".ucwords($ra['enteredby'])."</td></tr>";
		$m++;
	  }
	}
	  
	  $rdata.="<tr><td>&nbsp;</td></tr></table></div>";
	echo $rdata;
	}
	else
	{
	   echo "<div class='error'>No Result Found</div>";
	}
}
}
include('includes/footer.inc');
?>