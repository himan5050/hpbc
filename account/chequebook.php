<?php
include ('includes/session.inc');
$title = _('Cheque Book');
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.inc'); //this reads in the Accounts Sections array

	include  ('includes/header.inc');
	
	
	if (isset($_POST['FromPeriod']) and isset($_POST['ToPeriod']) and $_POST['FromPeriod'] > $_POST['ToPeriod']){
	//prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'),'error');
	//$_POST['SelectADifferentPeriod']=_('Select A Different Period');
    }
	//echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Trial Balance') . '" alt="" />' . ' ' . $title . '</p>';
	echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'.$_SERVER['SCRIPT_NAME'].'">Cheque Book</a></div><form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">';
	if(isset($_POST['ShowTB']))
	{
	if($_POST['JournalProcessDate1']=="" && $_POST['JournalProcessDate']=="")
{
prnMsg(_('Please Select Period From and Period To'),'error'); 
}
else if($_POST['JournalProcessDate1']=="")
{
 prnMsg(_('Please Select Period from'),'error'); 
}
else if($_POST['JournalProcessDate']=="")
{
 prnMsg(_('Please Select Period To'),'error'); 
}
else if(strtotime($_POST['JournalProcessDate1']) > strtotime($_POST['JournalProcessDate']))
{
 prnMsg(_('From Date Can Not Be Greater Than To Date'),'error'); 
}
	}
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (Date('m') > $_SESSION['YearEnd']){
		/*Dates in SQL format */
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')));
	} else {
		$DefaultFromDate = Date ('Y-m-d', Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')-1));
		$FromDate = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,$_SESSION['YearEnd'] + 2,0,Date('Y')-1));
	}
	$period=GetPeriod($FromDate, $db);
    // print_r($_REQUEST);exit;
	/*Show a form to allow input of criteria for TB to show */
	echo '<table cellspacing="1" cellpadding="2" style="border:none;">
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Cheque Book</legend>
<table align="left" class="frmtbl">
		<tr><td><div class="divwrapper"><div class="maincol2"><b>' . _('Select Period From :') . ' <span style="color:#FF0000">*</span></b></div>
				<div class="maincol"><input type="text" style="width:110px;" name="JournalProcessDate1" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="'.$_POST['JournalProcessDate1'].'" ></div></div></td>';
	if (!isset($_POST['ToPeriod']) OR $_POST['ToPeriod']==''){
		$lastDate = date('Y-m-d',mktime(0,0,0,Date('m')+1,0,Date('Y')));
		$sql = "SELECT periodno
				FROM periods
				WHERE lastdate_in_period = '" . $lastDate . "'";
		$MaxPrd = DB_query($sql,$db);
		$MaxPrdrow = DB_fetch_row($MaxPrd);
		$DefaultToPeriod = (int) ($MaxPrdrow[0]);

	} else {
		$DefaultToPeriod = $_POST['ToPeriod'];
	}

	echo '<td><div class="divwrapper"><div class="maincol"><b>' . _('Select Period To :') .' <span style="color:#FF0000">*</span></b></div>
			<div class="maincol" id="date"><input type="text" style="width:110px;" name="JournalProcessDate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="'.$_POST['JournalProcessDate'].'"></div></div></div></td><td><div class="divwrapper"><div class="generatebtn"><input type=submit Name="ShowTB" Value="' . _('Generate') .'"></div></div></td>
		</tr>
		
		</tr></table></filedset>
		</table>
		';
	
	
	if(isset($_POST['ShowTB']))
	{
	/*if($_POST['JournalProcessDate1']=="" && $_POST['JournalProcessDate']=="")
{
 echo '<font color="red"><b>Please Select Period From and Period To</b></font>'; 
}
else if($_POST['JournalProcessDate1']=="")
{
 echo '<font color="red"><b>Please Select Period from</b></font>'; 
}
else if($_POST['JournalProcessDate']=="")
{
 echo '<font color="red"><b>Please Select Period To</b></font>'; 
}
else if(strtotime($_POST['JournalProcessDate1']) > strtotime($_POST['JournalProcessDate']))
{
 echo '<font color="red"><b>From Date Can Not Be Greater Than To Date</b></font>'; 
}*/
 
 if($_POST['JournalProcessDate1']!="" && $_POST['JournalProcessDate']!="" && (strtotime($_POST['JournalProcessDate1']) <= strtotime($_POST['JournalProcessDate']))){

$fr=explode('-',$_POST['JournalProcessDate1']);
$from=$fr[2].'-'.$fr[1].'-'.$fr[0];
$tr=explode('-',$_POST['JournalProcessDate']);
$to=$tr[2].'-'.$tr[1].'-'.$tr[0];
$corpbranch=getCorporationBranch($_SESSION['uid'],$db);

$data='<table><tr><td colspan="6" align="right"><a href="/'.$u[1].'/generateaccountpdf.php?sdate='.$from.'&edate='.$to.'&op=chequebook&branch='.$corpbranch.'" target="blank"><img src="images/pdf_icon.gif"/></a></td></tr><tr><th>S.No.</th><th>Date</th><th>Cheque No</th><th>Cheque Type</th><th>Amount</th><th>Purpose</th></tr>';

    $s="select * from gltrans where transtype='Cheque' and trandate>='".$from."' and trandate<='".$to."' and (chequeno!=0)";
 //$s="select banktrans.transdate,banktrans.amount,banktrans.ref,banktrans.type from banktrans where transdate>='".$from."' and transdate<='".$to."' and banktranstype='Cheque' AND bankact = '1030' ";
 //echo $s;
 $q=DB_query($s,$db);
$ssrn=DB_num_rows($q);
 $n=1;
 while($r=DB_fetch_array($q))
 {  if($n%2==0)
	 {
	   $cla="even";
	 }
	 else
	 {
	   $cla="odd";
	 }
    /*$ss="select * from gltrans where type='".$r['type']."' and trandate='".$r['transdate']."' and (chequeno!=0)";
	$ssq=DB_query($ss,$db);
	$ssr=DB_fetch_array($ssq);
	$ssrn=DB_num_rows($ssq);*/
 if($r['amount']>0)
   {
     $typ="Paid";
   }
   else
   {
     $typ="Received";
   }

   $datt=explode('-',$r['trandate']);

	if(($r['type'] == 12 && $r['amount']<0) || ($r['type'] == 1 && $r['amount']>0))
	{
		$data .='<tr class="'.$cla.'" align="center"><td>'.$n.'</td><td align="center">'.$datt[2].'-'.$datt[1].'-'.$datt[0].'</td><td align="right">'.$r['chequeno'].'</td><td align="left">'.$typ.'</td><td align="right">'.round(abs($r['amount'])).'</td><td align="left">'.$r['narrative'].'</td></tr>';
		$n++;
   }
 }

$data .='</table>';
if($ssrn>0)
  {
echo $data;
  }
  else
  {
	 echo '<span style="color:#FF0000"><b>No Record(s) Found</b></span>';  
  }
}

	}
	
include('includes/footer.inc');

?>