<?php
include('includes/session.inc');
$title = _('Stock Issue Register');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
 '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
$u=explode('/',$_SERVER['REQUEST_URI']);
?>
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Audit Report</a></div>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table  border="0" cellpadding="2" cellspacing="1" style="border:none;">
 <tr>	<td align="left" class="tdform-width"><fieldset><legend>Audit Report</legend>
 <table align="left" class="frmtbl">
		<tr>
    <td align="left" ><div class="divwrapper"><div class="maincol"><b>From Date: </b><span style="color:#FF0000">*</span></div>
<div class="maincol"><!--<div id="li_1" style="width:170px;">
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
		</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></td>
<td> <div class="divwrapper"><div class="maincol"><b>To Date: </b><span style="color:#FF0000">*</span></div>
    <div class="maincol"><!--<div id="li_2" style="width:170px;" >
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
        </tr>
        <tr><td colspan="4"><div class="divwrapper"><div class="maincol"><b>Office: </b><span style="color:#FF0000">*</span></div>
  <div class="maincolcel">  <select name="auditoffice"><option value="">--Select --</option>
    <?php
	$ic="select * from tbl_corporations";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{
	  if($_POST['auditoffice']==$icr['corporation_id'])
	 {
	  echo '<option value="'.$icr['corporation_id'].'" selected >'.ucwords($icr['corporation_name']).'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$icr['corporation_id'].'">'.ucwords($icr['corporation_name']).'</option>';
	  }
	}
	?>
	</select></div></div></td></tr>
        <tr><td>&nbsp;</td></tr>
<tr> <td colspan="4"><div class="generatebtn" style="margin-right:30px;"><input  type="submit" name="submit" value="Generate" /></div></td>
  </tr>
  <tr>

 <td> </td>
  </tr>
</table></filedset>
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
//$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
$sdate=$_POST['JournalProcessDate'];
//$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];
$edate=$_POST['JournalProcessDate1'];
if($_POST['auditoffice']=='' )
   {
     $InputError = 1;
     prnMsg(_('Select Office'),'error');
	}

if($sdate=='')
   {
     $InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($edate=='' )
   {
     $InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	if($sdate!='--' && $edate!='--' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}

     if($curdat<strtotime($edate))
 {
 $InputError = 1;
     prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }
 if($InputError!=1)
	{  
	  $output="<table><tr class=oddrow><td colspan=10><h2>Audit Report</h2></td></tr><tr><td colspan='10' align='right'><a href='/".$u[1]."/generateaccountpdf.php?sdate=".strtotime($sdate)."&edate=".strtotime($edate)."&office=".$_REQUEST['auditoffice']."&op=audit' target='blank'><img src='images/pdf_icon.gif'/></a></td></tr>
	  <tr><th width='10%'><b>Audit Date</b></th><th width='10%'><b>Office Name</b></th><th width='10%'><b>Section Name</b></th><th width='10%'><b>Auditor</b></th><th width='12%'><b>NCs</b></th><th width='8%'><b>Description</b></th><th width='10%'><b>Severity</b></th><th width='10%'><b>Clause</b></th><th width='10%'><b>Action Taken</b></th><th width='10%'><b>Remark</b></th></tr>";
	  $s="select * from audit_detail where auditdate>='".strtotime($sdate)."' and auditdate<='".strtotime($edate)."' and auditoffice='".$_REQUEST['auditoffice']."' order by auditdate";
	  $q=DB_query($s,$db);
	  $k=1;
	  while($r=DB_fetch_array($q))
	  {  if($k%2==0)
	        {
		      $cl="even";
	        }
			else
			{
			  $cl="odd";	
			}
	    $s1="select name from users where uid='".$r['auditor']."'"; 
		$q1=DB_query($s1,$db);
		$r1=DB_fetch_array($q1);
		
		$s2="select * from tbl_corporations where corporation_id='".$r['auditoffice']."'"; 
		$q2=DB_query($s2,$db);
		$r2=DB_fetch_array($q2);
	  
	   //nc goes here
	   $nc="select * from nsc_detail where audit_id='".$r['audit_id']."'";	
	   $ncq=DB_query($nc,$db);
	  $nctd ="<table style='border:none;'>";
	  
	   while($ncr=DB_fetch_array($ncq))
	   {
	     
	     $nctd .="<tr ><td width='12%' style='border:none;'><div style='word-wrap:break-word; width:60px;'>".ucwords($ncr['nsc'])."</div></td><td width='12%' style='border:none;'><div style='word-wrap:break-word; width:60px;'>".ucwords($ncr['description'])."</div></td><td width='10%' style='border:none;'><div style='word-wrap:break-word; width:60px;'>".ucwords($ncr['sevirity'])."</div></td><td width='10%' style='border:none;'><div style='word-wrap:break-word; width:60px;'>".ucwords($ncr['clause'])."</div></td><td width='8%' style='border:none;'><div style='word-wrap:break-word; width:60px;'>".ucwords($ncr['corrective_report'])."</div></td></tr>";
		 
	   }
	   $nctd.="</table>";
	   
	   
	   $output .="<tr class='".$cl."'><td valign='top' width='10%'>".date('d-m-Y',$r['auditdate'])."</td><td valign='top' width='10%'>".ucwords($r2['corporation_name'])."</td><td valign='top' width='10%'>".ucwords($r['section'])."</td><td valign='top' width='10%'>".ucwords($r1['name'])."</td><td colspan='5' width='10%'>".$nctd."</td><td valign='top'>".ucwords($r['remark'])."</td></tr>";
	   	$k++;	   
	  }
	$output .="</table>";
	echo $output;
	}
}
include('includes/footer.inc');
?>