<?php
include('includes/session.inc');
$title = _('Condemned items');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/* '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Bill Detail Reports</a></div>';
if (isset($_REQUEST['submit']) ){
  $InputError = 0;
//$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
//$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];

$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];
if($sdate=='')
   {
    // $InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($edate=='' )
   {
     //$InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
     //$InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
}
?>

<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
 <tr>	<td align="left" class="tdform-width"><fieldset><legend>Bill Detail Reports</legend>
 <table align="left" class="frmtbl">
  	<tr> 	<td><div class="divwrapper"><div class="maincol"><b>From Date:</b> <span style="color:#FF0000">*</span></div>
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
		</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></div></td>
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
		</div>--><div  id="date"><input type="text"  name="JournalProcessDate1" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate1'];?>" width="125"></div></div></div></td>
 <td> <div class="divwrapper"><div class="generatebtn"><input  type="submit" name="submit" value="Generate" />
		</div></td>
		</tr>
		
		</tr></table></filedset>
		</table></form>
<?php 
if (isset($_REQUEST['submit']) ){
  $InputError = 0;
//$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
//$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];

$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];
if($sdate=='')
   {
     $InputError = 1;
     //prnMsg(_('Select From Date'),'error');
	}
	if($edate=='' )
   {
     $InputError = 1;
    // prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     //prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}

 if($InputError!=1)
	{  
	
	 $rec_limit = 10;
	 $count_query = "select count(id) from billsubmit where (date>='".strtotime($sdate)."' and date<='".strtotime($edate)."')";
$retval =DB_query( $count_query, $db );
$row = DB_fetch_array($retval);
 $rec_count = $row[0];

$topage=ceil($rec_count/$rec_limit);
if( isset($_GET{'page'} ) )
{
   $page = $_GET{'page'} + 1;
   $offset = $rec_limit * $page ;
}
else
{
   $page = 0;
   $offset = 0;
}
 $rec_count;
 $left_rec = $rec_count - ($page * $rec_limit);
 
 
	  $s="select * from billsubmit where (date>='".strtotime($sdate)."' and date<='".strtotime($edate)."') LIMIT $offset, $rec_limit";
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	if($n)
	{
  $rdata="
          <table>
		  <tr class=oddrow><td colspan=6><h2>Bill Detail Reports</h2></td></tr>
		  <tr><td colspan=6 align='right'><a href='/".$u[1]."/generateadvancepdf.php?op=billsubmit&sdate=".strtotime($sdate)."&edate=".strtotime($edate)."&branch=".$corpbranch."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
<tr><th><b>S. No.</b></th>
<th><b>Vendor Name</b></th>
<th><b>Work Order</b></th>
<th><b>Bill Date</b></th>
<th><b>Amount</b></th>
<th><b>Bill Paid</b></th>


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
	  {  
	    $ss="select * from tbl_pendingvouchers where transactionid='".$r['id']."' and entrytype='billsubmit'";
		$ssq=DB_query($ss,$db);
		$ssr=DB_fetch_array($ssq);
		
		if($ssr['voucher_number']!='')
		{
		  $sta="Yes";
		}
		else
		{
		 $sta="No";
		}
	   
	  if($i%2==0)
     {
	   $cl="even";
	 }
	 else
	 {
	   $cl="odd";
	 }
	    $rdata.="<tr class='".$cl."' align='center'><td>".$nn."</td><td align='left'>".ucwords($r['name'])."</td><td align='right'><div style='text-transform:uppercase'>".ucwords($r['refnum'])."</div></td><td align='center'>".date('d-m-Y',$r['date'])."</td><td align='right'>".round(abs($r['approveamount']))."</td><td align='left'>".$sta."</td></tr>";
		$i++;
		$nn++;
	  }
	
	  
	  $rdata.="</table><div class='paging'>";
	  
	echo $rdata;
	 if(isset($_GET['page']) && $_GET['page'] >3){
   $nn = $_GET['page']-3;
   for($nn;$nn<=($_GET['page']+3);$nn++){
      
	   if($_GET['page']==($nn-2))
		   {
			 $pg="<strong>".$nn."</strong>";
			}
			else
			{
			  $pg=$nn;
			}
	      //$pg = $nn;
		
		  $datap .="<a href=\"$_PHP_SELF?page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	
   }
     if(($_GET['page']+ 2) != $topage){
		    $datap .= '..';
		  }
 }else{
    if($topage > 7){
	   $tp = 7;
	}else if($topage < 7 && $topage > 1){
	   $tp = $topage;
	}
     for($nn=1;$nn<=$tp;$nn++){
	  if(isset($_GET['page']))
	   {
		   if($_GET['page']==($nn-2))
		   {
			 $pg="<strong>".$nn."</strong>";
			}
			else
			{
			  $pg=$nn;
			}
		}
		else
		{
		  if($nn==1)
		  {
		   $pg="<strong>".$nn."</strong>";
		  }
		  else
		  {
		    $pg=$nn;
		  }
		}	
      $datap .="<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	 } 
 }
	
if($left_rec <= $rec_limit && $page!=0)
{   
   $last = $page-2;
   echo "<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous</a>";
}

	else if( $page > 0)
{  
   $last = $page - 2;
      echo "<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous </a>&nbsp;  &nbsp;";
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}
 
else if( $page == 0 && $left_rec > $rec_limit)
{   
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}

	echo '</div>';
	}
	else
	{
	  echo "<div class='error'>No Records Found</div>";
	}
}
}
?>
<?php include('includes/footer.inc');?>