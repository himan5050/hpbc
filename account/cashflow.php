<?php

include ('includes/session.inc');
$title = _('Cash Flow');
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.inc'); //this reads in the Accounts Sections array


if (isset($_POST['FromPeriod']) and isset($_POST['ToPeriod']) and $_POST['FromPeriod'] > $_POST['ToPeriod']){
	//prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'),'error');
	$_POST['SelectADifferentPeriod']=_('Select A Different Period');
}



	include  ('includes/header.inc');
	//echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Trial Balance') . '" alt="" />' . ' ' . $title . '</p>';
	echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'.$_SERVER['SCRIPT_NAME'].'">Cash Flow</a></div><form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">';
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
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Cash Flow</legend>
<table align="left" class="frmtbl">
		<tr><td><div class="divwrapper"><div class="maincol">' . _('Select Period From :') . '</div>
				<div class="maincol"><select Name="FromPeriod"><option value=" " selected>--Select--</option>';
	$NextYear = date('Y-m-d',strtotime('+1 Year'));
	$sql = "SELECT periodno,
					lastdate_in_period
				FROM periods
				WHERE lastdate_in_period < '" . $NextYear . "'
				ORDER BY periodno DESC";
	$Periods = DB_query($sql,$db);


	while ($myrow=DB_fetch_array($Periods,$db)){
		if(isset($_POST['FromPeriod']) AND $_POST['FromPeriod']!='' ){
			if( $_POST['FromPeriod'] ==  $myrow['periodno']){
				 echo '<option selected value="'.$myrow['periodno'] .'">' .MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="'.$myrow['periodno'].'">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		} else {
			if($myrow['lastdate_in_period']==$DefaultFromDate){
				//echo '<option selected value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		}
	}

	echo '</select></div></div></td>';
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

	echo '<td><div class="divwrapper"><div class="maincol">' . _('Select Period To :') .'</div>
			<div class="maincol"><select Name="ToPeriod"><option value=" ">--Select--</option>';

	$RetResult = DB_data_seek($Periods,0);

	while ($myrow=DB_fetch_array($Periods,$db)){

		if($myrow['periodno']==$_REQUEST['ToPeriod']){
			echo '<option selected value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		} else {
			echo '<option value ="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		}
	}
	echo '</select></div></div></div></td><td><div class="divwrapper"><div class="generatebtn"><input type=submit Name="ShowTB" Value="' . _('Generate') .'"></div></div></td>
		</tr>
		
		</tr></table></filedset>
		</table>
		';

	//echo '<div class="centre"><input type=submit Name="ShowTB" Value="' . _('Show Report') .'">';
	

/*Now do the posting while the user is thinking about the period to select */

	include ('includes/GLPostings.inc');
	
	
	
	if(isset($_POST['ShowTB']))
	{
	 
//$data="<table><tr><td colspan='4'>Income</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";

if($_POST['ToPeriod']==" " && $_POST['FromPeriod']==" ")
{
 echo '<font color="red"><b>Please Select Period From and Period To</b></font>'; 
}
else if($_POST['FromPeriod']==" ")
{
 echo '<font color="red"><b>Please Select Period from</b></font>'; 
}
else if($_POST['ToPeriod']==" ")
{
 echo '<font color="red"><b>Please Select Period To</b></font>'; 
}
 else if($_POST['ToPeriod']<$_POST['FromPeriod'])
{
 echo '<font color="red"><b>Period To Should Be Greater Than Period From</b></font>'; 
}
else if($_POST['FromPeriod']!=" " && $_POST['ToPeriod']!=" "){



global $opening;

$acgc=array();
$debtotalc=0;
$cretotalc=0;
$totaldebitc=array();
$totalcreditc=array();
$accc=array();
$accountc=array();

$da=0;
$gda="select lastdate_in_period from periods where periodno='".$_POST['FromPeriod']."' OR periodno='".$_POST['ToPeriod']."' order by periodno";
$gdaq=DB_query($gda,$db);
while($gdar=DB_fetch_array($gdaq))
{
if($da==0)
{
  $dafr=$gdar['lastdate_in_period'];
  }
  else
  {
    $dato=$gdar['lastdate_in_period'];
  }
  $da++;
}

$s="select * from accountgroups where sectioninaccounts='1'";
$q=DB_query($s,$db);
while($r=DB_fetch_array($q))
{
 $acgc[]=$r['groupname'];
 
 $ss="select * from accountgroups where parentgroupname='".$r['groupname']."' ";
 $ssq=DB_query($ss,$db);
  while($ssr=DB_fetch_array($ssq))
  {
    $acgc[]=$ssr['groupname'];
  }
}
//print_r($acg);
//echo "Income <br>";
foreach($acgc as $accoc)
{
  
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoc."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period<'".$_POST['FromPeriod']."' ";
 $qq=DB_query($sq,$db);

 while($qr=DB_fetch_array($qq))
 { 
  $accocde=$qr['accountcode'];

  
  if(in_array($accocde,$accc))
    {
      if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $accountc[$accocde]['cr'] =$accountc[$accocde]['cr']+$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $accountc[$accocde]['de'] =$accountc[$accocde]['de']+$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $accc[]=$qr['accountcode'];
	$accountc[$accocde]['name']=$qr['accountname'];
	$accountc[$accocde]['group']=$r['groupname'];
	  if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $accountc[$accocde]['cr'] =$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $accountc[$accocde]['de']=$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$f=1;
foreach($accc as $acid)
{ if($f%2==0)
{
$cl="even";
} 
else
{
$cl="odd";
}

   $totaldebitc[]=$accountc[$acid]['de'];
   $totalcreditc[]=$accountc[$acid]['cr'];
    if($accountc[$acid]['de']=='')
     {
      $accountc[$acid]['de']=0;
     }
	 if($accountc[$acid]['cr']=='')
     {
      $accountc[$acid]['cr']=0;
     }
	 
  //$data.= "<tr><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
 
		  $f++;
}
 


$acgce=array();
$debtotalce=0;
$cretotalce=0;
$totaldebitce=array();
$totalcreditce=array();
$accce=array();
$accountce=array();
$se="select * from accountgroups where sectioninaccounts='3'";
$qe=DB_query($se,$db);
//$datae="<table><tr><td colspan='4'>Expenditure</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";
$data.="<table>";
$data .="<thead><tr><td align=center colspan=3><b>Cash Out (".$mo.")</b></td></tr></thead>";
while($re=DB_fetch_array($qe))
{
 $acgce[]=$re['groupname'];
 
 $sse="select * from accountgroups where parentgroupname='".$re['groupname']."' ";
 $ssqe=DB_query($sse,$db);
  while($ssre=DB_fetch_array($ssqe))
  {
    $acgce[]=$ssre['groupname'];
  }
}
//print_r($acge);
//echo "Expenditure <br>";
foreach($acgce as $accoce)
{
  
 $sqe="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoce."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period<'".$_POST['FromPeriod']."' ";
 $qqe=DB_query($sqe,$db);

 while($qre=DB_fetch_array($qqe))
 { 
  $accocde=$qre['accountcode'];

  
  if(in_array($accocde,$accce))
    {
      if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accountce[$accocde]['cr'] =$accountce[$accocde]['cr']+$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accountce[$accocde]['de'] =$accountce[$accocde]['de']+$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $accce[]=$qre['accountcode'];
	$accountce[$accocde]['name']=$qre['accountname'];
	$accountce[$accocde]['group']=$re['groupname'];
	  if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accountce[$accocde]['cr'] =$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accountce[$accocde]['de']=$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$z=1;
foreach($accce as $acide)
{ if($z%2==0)
{
$cl="even";
}
else
{
$cl="odd";
}

   $totaldebitce[]=$accountce[$acide]['de'];
   $totalcreditce[]=$accountce[$acide]['cr'];
    if($accountce[$acide]['de']=='')
     {
      $accountce[$acide]['de']=0;
     }
	 if($accountce[$acide]['cr']=='')
     {
      $accountce[$acide]['cr']=0;
     }
 // $datae.= "<tr><td>".$acide."</td><td>".$accounte[$acide]['name']."</td><td>".$accounte[$acide]['de']."</td><td>".$accounte[$acide]['cr']."</td></tr>";

		$z++;
}




$carry=round((array_sum($totaldebitce))-(array_sum($totalcreditce)));

$opening=$carry;


for($g=$_POST['FromPeriod'];$g<=$_POST['ToPeriod'];$g++)
{
  $mon[]=$g;
 }
 
//$mon=array('16');
$mm=1;
foreach($mon as $mont)
{

$pe="select lastdate_in_period from periods where periodno='".$mont."'";
$peq=DB_query($pe,$db);
$per=DB_fetch_array($peq);
$daa=explode('-',$per['lastdate_in_period']);

if($daa[1]=='01')
{
 $mo="January";
}
if($daa[1]=='02')
{
 $mo="February";
}
if($daa[1]=='03')
{
 $mo="March";
}
if($daa[1]=='04')
{
 $mo="April";
}
if($daa[1]=='05')
{
 $mo="May";
}
if($daa[1]=='06')
{
 $mo="June";
}
if($daa[1]=='07')
{
 $mo="July";
}
if($daa[1]=='08')
{
 $mo="August";
}
if($daa[1]=='09')
{
 $mo="September";
}
if($daa[1]=='10')
{
 $mo="October";
}
if($daa[1]=='11')
{
 $mo="November";
}
if($daa[1]=='12')
{
 $mo="December";
}
 $acg=array();
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();



$data="<table>";
 if($mm==1)
			   {
$data .="<tr><th  align=center><div style='width:130px'><b>Cash In</b></th><th align='center'> ".$mo."</b></div></th></tr>";
}
else
{
$data .="<tr><th align='center'> ".$mo."</b></div></th></tr>";
}
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
foreach($acg as $acco)
{
  
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$acco."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period='".$mont."' ";
 $qq=DB_query($sq,$db);

 while($qr=DB_fetch_array($qq))
 { 
  $accode=$qr['accountcode'];

  
  if(in_array($accode,$acc))
    {
      if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $account[$accode]['cr'] =$account[$accode]['cr']+$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $account[$accode]['de'] =$account[$accode]['de']+$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $acc[]=$qr['accountcode'];
	$account[$accode]['name']=$qr['accountname'];
	$account[$accode]['group']=$r['groupname'];
	  if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $account[$accode]['cr'] =$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $account[$accode]['de']=$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$f=1;
foreach($acc as $acid)
{ if($f%2==0)
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
  $data.= "<tr class='".$cl."'>";
               if($mm==1)
			   {
  				$data.= "<td align='left'><div style='width:230px;'>".ucwords($account[$acid]['name'])."</div></td>";
				}
				$data.= "<td align='right'>".round($account[$acid]['de'])."</td>
		  </tr>";
		  $f++;
}
 
         $data.= "<tr class='odd'>";
            if($mm==1)
			   {
				$data .="<td style='border-top:1px solid; border-top-color:#ccc;' ><b>Total Cash In</b></td>";
				}
				$data .="<td style='border-top:1px solid; border-top-color:#ccc;' align='right'><b>".round(array_sum($totaldebit))."</td></tr>";
				
				 $data.= "<tr class='even'>";
            if($mm==1)
			   {
				$data .="<td style='border-top:1px solid; border-top-color:#ccc;' ><b>Total Cash Available</b></td>";
				}
				$data .="<td style='border-top:1px solid; border-top-color:#ccc;' align='right'><div style='width:100px;'><b>".round((array_sum($totaldebit)+$opening))."</div></td></tr>";
				
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
$data.="<table>";
if($mm==1)
		{
$data .="<tr class='evenrow'><th align=center ><b>Cash Out</b></th><th align='center'>".$mo."</th></tr>";
}
else
{
 $data .="<tr><th align='center'>".$mo."</th></tr>";
}
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
foreach($acge as $accoe)
{
  
 $sqe="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoe."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period='".$mont."' ";
 $qqe=DB_query($sqe,$db);

 while($qre=DB_fetch_array($qqe))
 { 
  $accode=$qre['accountcode'];

  
  if(in_array($accode,$acce))
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
   $totalcredite[]=abs($accounte[$acide]['cr']);
    if($accounte[$acide]['de']=='')
     {
      $accounte[$acide]['de']=0;
     }
	 if($accounte[$acide]['cr']=='')
     {
      $accounte[$acide]['cr']=0;
     }
 // $datae.= "<tr><td>".$acide."</td><td>".$accounte[$acide]['name']."</td><td>".$accounte[$acide]['de']."</td><td>".$accounte[$acide]['cr']."</td></tr>";
$data.= "<tr class='".$cl."'>";
               if($mm==1)
			   {
				$data.= "<td><div style='width:233px;'>".ucwords($accounte[$acide]['name'])."</div></td>";
				}
				$data.= "<td align='right'>".round(abs($accounte[$acide]['cr']))."</td>
		</tr>";
		$z++;
}



$data.= "<tr class='odd'>";
 if($mm==1)
			   {
				$data.= "<td style='border-top:1px solid; border-top-color:#ccc;' ><b>Total Cash Out</b></td>";
				}
				$data.= "<td style='border-top:1px solid; border-top-color:#ccc;' align='right'><b>".round(array_sum($totalcredite))."</td></tr>";
				
				$cico=round((array_sum($totaldebit))-(array_sum($totalcredite)));
				$data.= "<tr class='even'>";
if($mm==1)
{
$data .="<td style='border-top:1px solid; border-top-color:#ccc;' ><b>Monthly Cash Flow (Cash In - Cash Out)</b></td>";
}
$data .="<td  align='right'><b>".round((array_sum($totaldebit))-(array_sum($totalcredite)))."</b></td></tr>";

$data.= "<tr class='odd'>";
if($mm==1)
{
$data .="<td style='border-top:1px solid; border-top-color:#ccc;' ><b>beginning Cash Balance</b></td>";
}
$data .="<td  align='right'><b>".round($opening)."</b></td></tr>";

$data.= "<tr class='even'>";
if($mm==1)
{
$data .="<td style='border-top:1px solid; border-top-color:#ccc;'><b>Ending Cash Balance</b></td>";
}
$data .="<td  align='right'><div style='width:100px;'><b>".round(($cico+$opening))."</b></div></td></tr>";
				
$data.="</table>";
$dat .="<td>".$data."</td>";
 
 $mm++;
 
 $opening=round(($cico+$opening));
 }
 $record="<br/><table><tr class='oddrow'><td><h2>Cash Flow From ".MonthAndYearFromSQLDate($dafr)." To ".MonthAndYearFromSQLDate($dato)."</h2></td></tr><tr><td align='right'><a href='/".$u[1]."/generatecashflowpdf.php?sdate=".$_POST['FromPeriod']."&edate=".$_POST['ToPeriod']."&op=cashflow' target='blank'><img src='images/pdf_icon.gif'></a></td></tr></table><div class='listingpage_scrolltable1'><table><tr>".$dat."</tr></table></div>";
 
 echo $record;
	}
	
}
include('includes/footer.inc');
?>
