<?php

/* $Id: GLTrialBalance.php 4630 2011-07-14 10:27:29Z daintree $*/

/*Through deviousness and cunning, this system allows trial balances for any date range that recalcuates the p & l balances
and shows the balance sheets as at the end of the period selected - so first off need to show the input of criteria screen
while the user is selecting the criteria the system is posting any unposted transactions */


include ('includes/session.inc');
$title = _('Trial Balance');
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.inc'); //this reads in the Accounts Sections array
include  ('includes/header.inc');

if ( $_POST['ToPeriod'] == " "){
	prnMsg(_('Please Select Period '),'error');
	$_POST['SelectADifferentPeriod']=_('Select A Different Period');
}

/*if (isset($_POST['FromPeriod']) and isset($_POST['ToPeriod']) and $_POST['FromPeriod'] > $_POST['ToPeriod']){
	prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'),'error');
	$_POST['SelectADifferentPeriod']=_('Select A Different Period');
}*/

if ( ! isset($_POST['ToPeriod']) OR ( $_POST['ToPeriod']==" ") OR isset($_POST['SelectADifferentPeriod'])){

	

	echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
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

	/*Show a form to allow input of criteria for TB to show */
	echo '<div class="breadcrumb">Home &raquo; <a href="GLTrialBalance.php">Trial Balance</a></div>
	<table cellspacing="1" cellpadding="2" style="border:none;">
<tr><td align="left" class="tdform-width" ><fieldset><legend>Trial Balance</legend>
 <table align="left" class="frmtbl">
			';
	$NextYear = date('Y-m-d',strtotime('+1 Year'));
	$sql = "SELECT periodno,
					lastdate_in_period
				FROM periods
				WHERE lastdate_in_period < '" . $NextYear . "' and periodno>='-55'
				ORDER BY periodno DESC";
	$Periods = DB_query($sql,$db);


	while ($myrow=DB_fetch_array($Periods,$db)){
		
	}

	echo '';
	if (!isset($_POST['ToPeriod']) OR $_POST['ToPeriod']==''){
		$lastDate = date('Y-m-d',mktime(0,0,0,Date('m')+1,0,Date('Y')));
		$sql = "SELECT periodno
				FROM periods
				WHERE lastdate_in_period = '" . $lastDate . "' ";
		$MaxPrd = DB_query($sql,$db);
		$MaxPrdrow = DB_fetch_row($MaxPrd);
		$DefaultToPeriod = (int) ($MaxPrdrow[0]);

	} else {
		$DefaultToPeriod = $_POST['ToPeriod'];
	}

echo '<tr><td><div class="divwrapper"><div class="maincol">' . _('Select Period :') .'</div>
			<div class="maincol"><select Name="ToPeriod"><option value=" ">--Select--</option>';

	$RetResult = DB_data_seek($Periods,0);

	while ($myrow=DB_fetch_array($Periods,$db)){

		if($myrow['periodno']==$_REQUEST['ToPeriod']){
			echo '<option selected value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		} else {
			echo '<option value ="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		}
	}
	echo '</select></div></div></td><td></td><td><div class="divwrapper"><div class="generatebtn">
		<input type=submit Name="ShowTB" Value="' . _('Generate') .'"></div>
		</div></td>
		</tr>
		
		</tr></table></filedset>
		</table>
		<br />';

	

/*Now do the posting while the user is thinking about the period to select */

	include ('includes/GLPostings.inc');

} else if (isset($_POST['PrintPDF'])) {

	include('includes/PDFStarter.php');

	$pdf->addInfo('Title', _('Trial Balance') );
	$pdf->addInfo('Subject', _('Trial Balance') );
	$PageNumber = 0;
	$FontSize = 10;
	$line_height = 12;

	$NumberOfMonths = $_POST['ToPeriod'] - $_POST['FromPeriod'] + 1;

	$sql = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['ToPeriod'] . "'";
	$PrdResult = DB_query($sql, $db);
	$myrow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($myrow[0]);

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];


	//include('includes/PDFTrialBalancePageHeader.inc');

		//include('includes/PDFStarter.php');


	$NumberOfMonths = $_POST['ToPeriod'] - $_POST['FromPeriod'] + 1;

	$sql = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['ToPeriod'] . "'";
	$PrdResult = DB_query($sql, $db);
	$myrow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($myrow[0]);


	$s="select * from accountgroups";
$q=DB_query($s,$db);
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();
//echo $TableHeader;
while($r=DB_fetch_array($q))
{
  /*$sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$r['groupname']."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period>='".$_POST['FromPeriod']."' and chartdetails.period<='".$_POST['ToPeriod']."' ";*/
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$r['groupname']."' and chartdetails.accountcode=chartmaster.accountcode  and chartdetails.period>='-127' and chartdetails.period<='".$_POST['ToPeriod']."' ";
 $qq=DB_query($sq,$db);
 if (DB_num_rows($qq)>=1){
 //echo "<tr><td colspan='4'><b>".$r['groupname']."</b></td></tr>";
 //echo "<b>".$r['groupname']."</b></br>";
 
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
  
}
	
foreach($acc as $acid)
{ 
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
  //echo $acid."--".$account[$acid]['name']."--".$account[$acid]['de']."--".$account[$acid]['cr']."--".$account[$acid]['group']."</br>";
 // echo "<tr><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
 
 if ($YPos < ($Bottom_Margin)){
			include('includes/PDFTrialBalancePageHeader.inc');
		}
 
        $LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,$acid);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,190,$FontSize,$account[$acid]['name']);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,number_format($account[$acid]['de']),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,number_format($account[$acid]['cr']),'right');
		//$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,number_format($AccountPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),'right');
		//$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,number_format($AccountPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$YPos -= $line_height;
		
		
 
 }
 $YPos -= (2 * $line_height);
		$pdf->line($Left_Margin+250, $YPos+$line_height,$Left_Margin+500, $YPos+$line_height);
		$pdf->setFont('','B');
		//$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,$acid);
		$LeftOvers = $pdf->addTextWrap($Left_Margin+60,$YPos,100,$FontSize,_(TOTAL));
		$LeftOvers = $pdf->addTextWrap($Left_Margin+250,$YPos,70,$FontSize,round(array_sum($totaldebit),2),'right');
		$LeftOvers = $pdf->addTextWrap($Left_Margin+310,$YPos,70,$FontSize,round(array_sum($totalcredit),2),'right');
		//$LeftOvers = $pdf->addTextWrap($Left_Margin+370,$YPos,70,$FontSize,number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		//$LeftOvers = $pdf->addTextWrap($Left_Margin+430,$YPos,70,$FontSize,number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),'right');
		$pdf->line($Left_Margin+250, $YPos,$Left_Margin+500, $YPos);  /*Draw the bottom line */
		$YPos -= (2 * $line_height);
		//$ParentGroups[$Level]='';
		//$GrpActual[$Level] =0;
		//$GrpBudget[$Level] =0;
		//$GrpPrdActual[$Level] =0;
	//	$GrpPrdBduget[$Level] =0;
		//$Level--;
 
 
//}	
	$pdf->OutputD($_SESSION['DatabaseName'] . '_GL_Trial_Balance_' . Date('Y-m-d') . '.pdf');
	$pdf->__destruct();
	exit;
} else {//
//
//	include('includes/header.inc');
//	echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '?' . SID . '">';
//	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
//	echo '<input type=hidden name="FromPeriod" value="' . $_POST['FromPeriod'] . '" />
//			<input type=hidden name="ToPeriod" value="' . $_POST['ToPeriod'] . '" />';
//
//	$NumberOfMonths = $_POST['ToPeriod'] - $_POST['FromPeriod'] + 1;
//
//	$sql = "SELECT lastdate_in_period
//			FROM periods
//			WHERE periodno='" . $_POST['ToPeriod'] . "'";
//	$PrdResult = DB_query($sql, $db);
//	$myrow = DB_fetch_row($PrdResult);
//	$PeriodToDate = MonthAndYearFromSQLDate($myrow[0]);
//
//	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];
//
//	 $SQL = "SELECT accountgroups.groupname,
//			accountgroups.parentgroupname,
//			accountgroups.pandl,
//			chartdetails.accountcode ,
//			chartmaster.accountname,
//			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
//			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
//			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
//			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
//			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
//			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
//		FROM chartmaster INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
//			INNER JOIN chartdetails ON chartmaster.accountcode= chartdetails.accountcode
//		GROUP BY accountgroups.groupname,
//				accountgroups.pandl,
//				accountgroups.sequenceintb,
//				accountgroups.parentgroupname,
//				chartdetails.accountcode,
//				chartmaster.accountname
//		ORDER BY accountgroups.pandl desc,
//			accountgroups.sequenceintb,
//			accountgroups.groupname,
//			chartdetails.accountcode";
//
//
//	$AccountsResult = DB_query($SQL,
//				$db,
//				 _('No general ledger accounts were returned by the SQL because'),
//				 _('The SQL that failed was:'));
//
//	echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' .
//		_('Trial Balance') . '" alt="" />' . ' ' . _('Trial Balance Report') . '</p>';
//
//	/*show a table of the accounts info returned by the SQL
//	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */
//
//	echo '<table cellpadding="2" class="selection">';
//	echo '<tr><th colspan=6><font size=3 color=blue><b>'. _('Trial Balance for the month of ') . $PeriodToDate .
//		_(' and for the ') . $NumberOfMonths . _(' months to ') . $PeriodToDate .'</b></font></th></tr>';
//	$TableHeader = '<tr>
//					<th>' . _('Account') . '</th>
//					<th>' . _('Account Name') . '</th>
//					<th>' . _('Month Actual') . '</th>
//					<th>' . _('Month Budget') . '</th>
//					<th>' . _('Period Actual') . '</th>
//					<th>' . _('Period Budget') .'</th>
//					</tr>';
//
//	$j = 1;
//	$k=0; //row colour counter
//	$ActGrp ='';
//	$ParentGroups = array();
//	$Level =1; //level of nested sub-groups
//	$ParentGroups[$Level]='';
//	$GrpActual =array(0);
//	$GrpBudget =array(0);
//	$GrpPrdActual =array(0);
//	$GrpPrdBudget =array(0);
//
//	$PeriodProfitLoss = 0;
//	$PeriodBudgetProfitLoss = 0;
//	$MonthProfitLoss = 0;
//	$MonthBudgetProfitLoss = 0;
//	$BFwdProfitLoss = 0;
//	$CheckMonth = 0;
//	$CheckBudgetMonth = 0;
//	$CheckPeriodActual = 0;
//	$CheckPeriodBudget = 0;
//
//	while ($myrow=DB_fetch_array($AccountsResult)) {
//
//		if ($myrow['groupname']!= $ActGrp ){
//			if ($ActGrp !=''){ //so its not the first account group of the first account displayed
//				if ($myrow['parentgroupname']==$ActGrp){
//					$Level++;
//					$ParentGroups[$Level]=$myrow['groupname'];
//					$GrpActual[$Level] =0;
//					$GrpBudget[$Level] =0;
//					$GrpPrdActual[$Level] =0;
//					$GrpPrdBudget[$Level] =0;
//					$ParentGroups[$Level]='';
//				} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
//					printf('<tr>
//						<td colspan=2><font size=2><I>%s ' . _('Total') . ' </I></font></td>
//						<td class=number><I>%s</I></td>
//						<td class=number><I>%s</I></td>
//						<td class=number><I>%s</I></td>
//						<td class=number><I>%s</I></td>
//						</tr>',
//						$ParentGroups[$Level],
//						number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//						number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//						number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//						number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));
//
//					$GrpActual[$Level] =0;
//					$GrpBudget[$Level] =0;
//					$GrpPrdActual[$Level] =0;
//					$GrpPrdBudget[$Level] =0;
//					$ParentGroups[$Level]=$myrow['groupname'];
//				} else {
//					do {
//						printf('<tr>
//							<td colspan=2><font size=2><I>%s ' . _('Total') . ' </I></font></td>
//							<td class=number><I>%s</I></td>
//							<td class=number><I>%s</I></td>
//							<td class=number><I>%s</I></td>
//							<td class=number><I>%s</I></td>
//							</tr>',
//							$ParentGroups[$Level],
//							number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//							number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//							number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//							number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));
//
//						$GrpActual[$Level] =0;
//						$GrpBudget[$Level] =0;
//						$GrpPrdActual[$Level] =0;
//						$GrpPrdBudget[$Level] =0;
//						$ParentGroups[$Level]='';
//						$Level--;
//
//						$j++;
//					} while ($Level>0 and $myrow['groupname']!=$ParentGroups[$Level]);
//
//					if ($Level>0){
//						printf('<tr>
//						<td colspan=2><font size=2><I>%s ' . _('Total') . ' </I></font></td>
//						<td class=number><I>%s</I></td>
//						<td class=number><I>%s</I></td>
//						<td class=number><I>%s</I></td>
//						<td class=number><I>%s</I></td>
//						</tr>',
//						$ParentGroups[$Level],
//						number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//						number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//						number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//						number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));
//
//						$GrpActual[$Level] =0;
//						$GrpBudget[$Level] =0;
//						$GrpPrdActual[$Level] =0;
//						$GrpPrdBudget[$Level] =0;
//						$ParentGroups[$Level]='';
//					} else {
//						$Level=1;
//					}
//				}
//			}
//			$ParentGroups[$Level]=$myrow['groupname'];
//			$ActGrp = $myrow['groupname'];
//			printf('<tr>
//				<td colspan=6><font size=4 color=blue><b>%s</b></font></td>
//				</tr>',
//				$myrow['groupname']);
//			echo $TableHeader;
//			$j++;
//		}
//
//		if ($k==1){
//			echo '<tr class="EvenTableRows">';
//			$k=0;
//		} else {
//			echo '<tr class="OddTableRows">';
//			$k++;
//		}
//		/*MonthActual, MonthBudget, FirstPrdBFwd, FirstPrdBudgetBFwd, LastPrdBudgetCFwd, LastPrdCFwd */
//
//
//		if ($myrow['pandl']==1){
//
//			$AccountPeriodActual = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
//			$AccountPeriodBudget = $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
//
//			$PeriodProfitLoss += $AccountPeriodActual;
//			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
//			$MonthProfitLoss += $myrow['monthactual'];
//			$MonthBudgetProfitLoss += $myrow['monthbudget'];
//			$BFwdProfitLoss += $myrow['firstprdbfwd'];
//		} else { /*PandL ==0 its a balance sheet account */
//			if ($myrow['accountcode']==$RetainedEarningsAct){
//				$AccountPeriodActual = $BFwdProfitLoss + $myrow['lastprdcfwd'];
//				$AccountPeriodBudget = $BFwdProfitLoss + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
//			} else {
//				$AccountPeriodActual = $myrow['lastprdcfwd'];
//				$AccountPeriodBudget = $myrow['firstprdbfwd'] + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
//			}
//
//		}
//
//		if (!isset($GrpActual[$Level])) {
//			$GrpActual[$Level]=0;
//		}
//		if (!isset($GrpBudget[$Level])) {
//			$GrpBudget[$Level]=0;
//		}
//		if (!isset($GrpPrdActual[$Level])) {
//			$GrpPrdActual[$Level]=0;
//		}
//		if (!isset($GrpPrdBudget[$Level])) {
//			$GrpPrdBudget[$Level]=0;
//		}
//		$GrpActual[$Level] +=$myrow['monthactual'];
//		$GrpBudget[$Level] +=$myrow['monthbudget'];
//		$GrpPrdActual[$Level] +=$AccountPeriodActual;
//		$GrpPrdBudget[$Level] +=$AccountPeriodBudget;
//
//		$CheckMonth += $myrow['monthactual'];
//		$CheckBudgetMonth += $myrow['monthbudget'];
//		$CheckPeriodActual += $AccountPeriodActual;
//		$CheckPeriodBudget += $AccountPeriodBudget;
//
//		$ActEnquiryURL = '<a href="'. $rootpath . '/GLAccountInquiry.php?Period=' . $_POST['ToPeriod'] . '&Account=' . $myrow['accountcode'] . '&Show=Yes">' . $myrow['accountcode'] . '<a>';
//
//		printf('<td>%s</td>
//			<td>%s</td>
//			<td class=number>%s</td>
//			<td class=number>%s</td>
//			<td class=number>%s</td>
//			<td class=number>%s</td>
//			</tr>',
//			$ActEnquiryURL,
//			$myrow['accountname'],
//			number_format($myrow['monthactual'],$_SESSION['CompanyRecord']['decimalplaces']),
//			number_format($myrow['monthbudget'],$_SESSION['CompanyRecord']['decimalplaces']),
//			number_format($AccountPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),
//			number_format($AccountPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']));
//
//		$j++;
//	}
//	//end of while loop
//
//
//	if ($ActGrp !=''){ //so its not the first account group of the first account displayed
//		if ($myrow['parentgroupname']==$ActGrp){
//			$Level++;
//			$ParentGroups[$Level]=$myrow['groupname'];
//		} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
//			printf('<tr>
//				<td colspan=2><font size=2><I>%s ' . _('Total') . ' </I></font></td>
//				<td class=number><I>%s</I></td>
//				<td class=number><I>%s</I></td>
//				<td class=number><I>%s</I></td>
//				<td class=number><I>%s</I></td>
//				</tr>',
//				$ParentGroups[$Level],
//				number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//				number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//				number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//				number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));
//
//			$GrpActual[$Level] =0;
//			$GrpBudget[$Level] =0;
//			$GrpPrdActual[$Level] =0;
//			$GrpPrdBudget[$Level] =0;
//			$ParentGroups[$Level]=$myrow['groupname'];
//		} else {
//			do {
//				printf('<tr>
//					<td colspan=2><font size=2><I>%s ' . _('Total') . ' </I></font></td>
//					<td class=number><I>%s</I></td>
//					<td class=number><I>%s</I></td>
//					<td class=number><I>%s</I></td>
//					<td class=number><I>%s</I></td>
//					</tr>',
//					$ParentGroups[$Level],
//					number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//					number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//					number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//					number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));
//
//				$GrpActual[$Level] =0;
//				$GrpBudget[$Level] =0;
//				$GrpPrdActual[$Level] =0;
//				$GrpPrdBudget[$Level] =0;
//				$ParentGroups[$Level]='';
//				$Level--;
//
//				$j++;
//			} while (isset($ParentGroups[$Level]) and ($myrow['groupname']!=$ParentGroups[$Level] and $Level>0));
//
//			if ($Level >0){
//				printf('<tr>
//				<td colspan=2><font size=2><I>%s ' . _('Total') . ' </I></font></td>
//				<td class=number><I>%s</I></td>
//				<td class=number><I>%s</I></td>
//				<td class=number><I>%s</I></td>
//				<td class=number><I>%s</I></td>
//				</tr>',
//				$ParentGroups[$Level],
//				number_format($GrpActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//				number_format($GrpBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//				number_format($GrpPrdActual[$Level],$_SESSION['CompanyRecord']['decimalplaces']),
//				number_format($GrpPrdBudget[$Level],$_SESSION['CompanyRecord']['decimalplaces']));
//
//				$GrpActual[$Level] =0;
//				$GrpBudget[$Level] =0;
//				$GrpPrdActual[$Level] =0;
//				$GrpPrdBudget[$Level] =0;
//				$ParentGroups[$Level]='';
//			} else {
//				$Level =1;
//			}
//		}
//	}
//
//
//
//	printf('<tr bgcolor="#ffffff">
//			<td colspan=2><font color=BLUE><b>' . _('Check Totals') . '</b></font></td>
//			<td class=number>%s</td>
//			<td class=number>%s</td>
//			<td class=number>%s</td>
//			<td class=number>%s</td>
//		</tr>',
//		number_format($CheckMonth,$_SESSION['CompanyRecord']['decimalplaces']),
//		number_format($CheckBudgetMonth,$_SESSION['CompanyRecord']['decimalplaces']),
//		number_format($CheckPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),
//		number_format($CheckPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']));
//
//	echo '</table><br />';
//	echo '<div class="centre"><input type=submit Name="SelectADifferentPeriod" value="' . _('Select A Different Period') . '" /></div>';

//include('includes/header.inc');
	echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '?' . SID . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<input type=hidden name="FromPeriod" value="' . $_POST['FromPeriod'] . '" />
			<input type=hidden name="ToPeriod" value="' . $_POST['ToPeriod'] . '" />';

	$NumberOfMonths = $_POST['ToPeriod'] - $_POST['FromPeriod'] + 1;

	$sql = "SELECT lastdate_in_period
			FROM periods
			WHERE periodno='" . $_POST['ToPeriod'] . "'";
	$PrdResult = DB_query($sql, $db);
	$myrow = DB_fetch_row($PrdResult);
	$PeriodToDate = MonthAndYearFromSQLDate($myrow[0]);


  

	/*show a table of the accounts info returned by the SQL
	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */
	
	
	
	
if(isset($_REQUEST['ShowTB']))
{ 
		if($_POST['ToPeriod']!=" ")
		{
	echo '<table cellpadding="2" cellspacing="1">';
	echo '';
	$TableHeader = '<thead><tr><td colspan="4" align="right"><a href="/'.$u[1].'/generateaccountpdf.php?sdate='.$_POST['FromPeriod'].'&edate='.$_POST['ToPeriod'].'&op=trial_balance" target="blank"><img src="images/pdf_icon.gif"/></a></td></tr><tr class=evenrow><td colspan=6 align=center><b>'. _('Trial Balance for the month of ') . $PeriodToDate .
		'</b></td></tr>
					<th>' . _('Account') . '</th>
					<th>' . _('Account Name') . '</th>
					
					<th>' . _('Debit') . '</th>
					<th>' . _('Credit') .'</th>
					</thead>';




$s="select * from accountgroups";
$q=DB_query($s,$db);
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();
echo $TableHeader;
while($r=DB_fetch_array($q))
{
  /* $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails,gltrans where chartmaster.group_='".$r['groupname']."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period>='".$_POST['FromPeriod']."' and chartdetails.period<='".$_POST['ToPeriod']."' ";*/
   $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails,gltrans where chartmaster.group_='".$r['groupname']."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period>='-55' and chartdetails.period<='".$_POST['ToPeriod']."' ";
 $qq=DB_query($sq,$db);
 if (DB_num_rows($qq)>=1){
 //echo "<tr><td colspan='4'><b>".$r['groupname']."</b></td></tr>";
 //echo "<b>".$r['groupname']."</b></br>";
 
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
  
  
 
  
  
  
  
  
 /*  if($qr['actual']<0)
   {
     $cred=$qr['actual'];
	// $account[$accode]['credit'] +=$qr['actual'];
	 $account[$accode]['cred']=$cred;
   }
   else
   {
     $cred=0;
	// $account[$accode]['credit'] +=0;
	$account[$accode]['cred']=0;
   }
   if($qr['actual']>0)
   {
     $debt=$qr['actual'];
	 $account[$accode]['debt']=$debt;
	//$account[$accode]['debit'] +=$qr['actual'];
   }
   else
   {
     $debt=0;
	 $account[$accode]['debt']=0;
	  //$account[$accode]['debit'] +=0;
   }
   
   $debtotal=$debtotal+$debt;
   $cretotal=$cretotal+$cred;
  // echo $qr['accountname']."--".round($debt,2)."--".round($cred,2)."</br>";
   
   
   $i++;*/
 }
 }
  
}
foreach($acc as $acid)
{ 
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
  //echo $acid."--".$account[$acid]['name']."--".$account[$acid]['de']."--".$account[$acid]['cr']."--".$account[$acid]['group']."</br>";
  echo "<tr class='even'><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
}
 echo "<tr><td style='border-top-color:#ccc; border-top:1px solid;'></td><td style='border-top-color:#ccc; border-top:1px solid;'><b>Total:</b></td><td style='border-top-color:#ccc; border-top:1px solid;'><b><i>".round(array_sum($totaldebit),2)."</i></b></td><td style='border-top-color:#ccc; border-top:1px solid;'><b><i>".round(array_sum($totalcredit),2)."</i></b></td></tr>";
//echo "Total:      ".$debtotal."--".$cretotal;
	


	//printf('<tr bgcolor="#ffffff">
		//	<td colspan=2><font color=BLUE><b>' . _('Check Totals') . '</b></font></td>
		//	<td class=number>%s</td>
		//	<td class=number>%s</td>
		//	<td class=number>%s</td>
		//	<td class=number>%s</td>
		//</tr>',
		//number_format($CheckMonth,$_SESSION['CompanyRecord']['decimalplaces']),
	//	number_format($CheckBudgetMonth,$_SESSION['CompanyRecord']['decimalplaces']),
	//	number_format($CheckPeriodActual,$_SESSION['CompanyRecord']['decimalplaces']),
	//	number_format($CheckPeriodBudget,$_SESSION['CompanyRecord']['decimalplaces']));

	echo '<tr><td colspan="10" align="center"><input type=submit Name="SelectADifferentPeriod" value="' . _('Back') . '" /></td></tr></table><br />';
	echo '<div class="centre"></div>';
}



 /*if($_REQUEST['FromPeriod']==" ")
{
 echo '<div class="error">Select Period from</div>'; 
}*/
 if($_REQUEST['ToPeriod']==" ")
{
 echo '<div class="error"><b>Select Period To</div>'; 
}
}
}

echo '</form>';
include('includes/footer.inc');

?>