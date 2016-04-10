<?php
include ('includes/session.inc');
$title = _('Income Expenditure');
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.inc'); //this reads in the Accounts Sections array


if (isset($_POST['FromPeriod']) and isset($_POST['ToPeriod']) and $_POST['FromPeriod'] > $_POST['ToPeriod']){
	//prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'),'error');
	$_POST['SelectADifferentPeriod']=_('Select A Different Period');
}



	include  ('includes/header.inc');
	//echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Trial Balance') . '" alt="" />' . ' ' . $title . '</p>';
	echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Income Expenditure</a></div><form method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
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
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Income Expenditure</legend>
<table align="left" class="frmtbl">
		<tr><td>
		<td><b>' . _('Select Period From :') . '</b></td>		
		<td><input type="text" style="width:110px;" name="JournalProcessDate1" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="'.$_POST['JournalProcessDate1'].'" ></td>';
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

	echo '<td>
	<td><b>' . _('Select Period To :') .'</b></td>
	<td>
	
			<input type="text" style="width:110px;" name="JournalProcessDate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="'.$_POST['JournalProcessDate'].'"></td><td><div class="divwrapper"><div class="generatebtn"><input type=submit Name="ShowTB" Value="' . _('Generate') .'"></div></div></td>
		</tr>
		
		</tr></table></filedset>
		</table>
		';

	//echo '<div class="centre"><input type=submit Name="ShowTB" Value="' . _('Show Report') .'">';
	

/*Now do the posting while the user is thinking about the period to select */

	include ('includes/GLPostings.inc');
	
	
	
	if(isset($_POST['ShowTB']))
	{  
	  $acg=array();
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();
//$data="<table><tr><td colspan='4'>Income</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";
$data="<div style='width:460px;'><table>

<thead><tr><td colspan=3 align=center><b>Income</b></td><tr><th>Code</th><th>Account</th><th>Income</th></thead>";

if($_POST['JournalProcessDate1']=="" && $_POST['JournalProcessDate']=="")
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
 
else if($_POST['JournalProcessDate1']!="" && $_POST['JournalProcessDate']!=""){

$fr=explode('-',$_POST['JournalProcessDate1']);
$from=$fr[2].'-'.$fr[1].'-'.$fr[0];
$tr=explode('-',$_POST['JournalProcessDate']);
$to=$tr[2].'-'.$tr[1].'-'.$tr[0];
$s="select * from accountgroups where sectioninaccounts='1'";
$q=DB_query($s,$db);
while($r=DB_fetch_array($q))
{
 $acg[]=$r['groupname'];
 
 $ss="select * from accountgroups where parentgroupname='".$r['groupname']."' ";
 $ssq=DB_query($ss,$db);
  while($ssr=DB_fetch_array($ssq))
  {
    $acg[]=$ssr['groupname'];
  }
}
//print_r($acg);
//echo "Income <br>";
$f=1;
foreach($acg as $acco)
{
  
 $sq="select chartmaster.accountcode as accountcode,chartmaster.accountname,gltrans.amount,gltrans.trandate from chartmaster,gltrans where chartmaster.group_='".$acco."' and gltrans.account=chartmaster.accountcode and gltrans.trandate>='".$from."' and gltrans.trandate<='".$to."' ";
 $qq=DB_query($sq,$db);

 while($qr=DB_fetch_array($qq))
 { 
  $accode=$qr['accountcode'];
if($qr['amount']>0)
{
  $debtotal=$debtotal+$qr['amount'];
  if($f%2==0)
{
$cl="even";
} 
else
{
$cl="odd";
}
/*  if(in_array($accode,$acc))
    {
      if($qr['amount']<0)
        {
          $cred=$qr['amount'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $account[$accode]['cr'] =$account[$accode]['cr']+$cred;
        }
		elseif($qr['amount']>0)
        {
          $debt=$qr['amount'];
	      $account[$accode]['de'] =$account[$accode]['de']+$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $acc[]=$qr['accountcode'];
	$account[$accode]['name']=$qr['accountname'];
	$account[$accode]['group']=$r['groupname'];
	  if($qr['amount']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $account[$accode]['cr'] =$cred;
        }
		elseif($qr['amount']>0)
        {
          $debt=$qr['amount'];
	      $account[$accode]['de']=$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }
	}*/
	$data.= "<tr class='".$cl."'>
  				<td align='right'>".$qr['accountcode']."</td>
				<td align='left'>".ucwords($qr['accountname'])."</td>
				<td align='right'>".$qr['amount']."</td>
		  </tr>";
		  $f++;
   }
   
   }
}
/*$f1=1;
foreach($acc as $acid)
{ if($f1%2==0)
{
$cl="even";
} 
else
{
$cl="odd";
}

   $totaldebit[]=$account[$acid]['de'];
   $totalcredit[]=$account[$acid]['cr'];
    if($account[$acid]['de']=='')
     {
      $account[$acid]['de']=0;
     }
	 if($account[$acid]['cr']=='')
     {
      $account[$acid]['cr']=0;
     }
	 
  //$data.= "<tr><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
  $data.= "<tr class='".$cl."'>
  				<td align='right'>".$acid."</td>
				<td align='left'>".ucwords($account[$acid]['name'])."</td>
				<td align='right'>".$account[$acid]['de']."</td>
		  </tr>";
		  $f1++;
}*/
$data.= "<tr class='odd'>
				<td style='border-top:1px solid; border-top-color:#ccc;' colspan='2'><b>Total</b></td>
				<td style='border-top:1px solid; border-top-color:#ccc;' align='right'><b>".round($debtotal,2)."</td>			        </tr>";
$data.="</table>";
 $data;


$acge=array();
$debtotale=0;
$cretotale=0;
$totaldebite=array();
$totalcredite=array();
$acce=array();
$accounte=array();
$se="select * from accountgroups where sectioninaccounts='3'";
$qe=DB_query($se,$db);
//$datae="<table><tr><td colspan='4'>Expenditure</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";
$datae="<div style='width:460px;'><table><thead><tr><td align=center colspan=3><b>Expenditure</b></td></tr><th>Code</th><th>Account</th><th>Expenditure</th></thead>";
while($re=DB_fetch_array($qe))
{
 $acge[]=$re['groupname'];
 
 $sse="select * from accountgroups where parentgroupname='".$re['groupname']."' ";
 $ssqe=DB_query($sse,$db);
  while($ssre=DB_fetch_array($ssqe))
  {
    $acge[]=$ssre['groupname'];
  }
}
//print_r($acge);
//echo "Expenditure <br>";
$z=1;
foreach($acge as $accoe)
{
  
 $sqe="select chartmaster.accountcode as accountcode,chartmaster.accountname,gltrans.amount,gltrans.trandate from chartmaster,gltrans where chartmaster.group_='".$accoe."' and gltrans.account=chartmaster.accountcode and gltrans.trandate>='".$from."' and gltrans.trandate<='".$to."' ";
 $qqe=DB_query($sqe,$db);

 while($qre=DB_fetch_array($qqe))
 { 
  $accode=$qre['accountcode'];
if($qre['amount']<0)
{
  $cretotale=$cretotale+$qre['amount'];
  if($z%2==0)
{
$cl="even";
}
else
{
$cl="odd";
}
/*  if(in_array($accode,$acce))
    {
      if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accounte[$accode]['cr'] =$accounte[$accode]['cr']+$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accounte[$accode]['de'] =$accounte[$accode]['de']+$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $acce[]=$qre['accountcode'];
	$accounte[$accode]['name']=$qre['accountname'];
	$accounte[$accode]['group']=$re['groupname'];
	  if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accounte[$accode]['cr'] =$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accounte[$accode]['de']=$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }
	}*/
	$datae.= "<tr class='".$cl."'>
  				<td align='right'>".$qre['accountcode']."</td>
				<td align='left'>".ucwords($qre['accountname'])."</td>
				<td align='right'>".$qre['amount']."</td>
		  </tr>";
		  $z++;
		}
		
   }
}
$z=1;
foreach($acce as $acide)
{ if($z%2==0)
{
$cl="even";
}
else
{
$cl="odd";
}

   $totaldebite[]=$accounte[$acide]['de'];
   $totalcredite[]=$accounte[$acide]['cr'];
    if($accounte[$acide]['de']=='')
     {
      $accounte[$acide]['de']=0;
     }
	 if($accounte[$acide]['cr']=='')
     {
      $accounte[$acide]['cr']=0;
     }
 // $datae.= "<tr><td>".$acide."</td><td>".$accounte[$acide]['name']."</td><td>".$accounte[$acide]['de']."</td><td>".$accounte[$acide]['cr']."</td></tr>";
/*$datae.= "<tr class='".$cl."'>
				<td align='right'>".$acide."</td>
				<td>".$accounte[$acide]['name']."</td>
				<td align='right'>".$accounte[$acide]['cr']."</td>
		</tr>";*/
		$z++;
}
$datae.= "<tr class='odd'>
				<td style='border-top:1px solid; border-top-color:#ccc;' colspan='2'><b>Total</b></td>
				<td style='border-top:1px solid; border-top-color:#ccc;' align='right'><b>".round($cretotale,2)."</td>
		</tr>";
$datae.="</table></div>";
 $datae;
 
 $record="<br/><br/><table><tr class=oddrow><td colspan=2><h2>Income Expenditure</h2></td></tr><tr><td colspan='2' align='right'><a href='/".$u[1]."/generateaccountpdf.php?sdate=".$from."&edate=".$to."&op=incexp' target='blank'><img src='images/pdf_icon.gif'/></a></td></tr><tr><td valign='top'>".$data."</td><td valign='top'>".$datae."</td></tr><table>";
 
 echo $record;
	}
	else{
  echo '<div class="error"><b>No Record(s) found</b></div>';

}	
}
	echo"<br/>";
include('includes/footer.inc');

?>