<?php

/* $Id: GLTrialBalance.php 4630 2011-07-14 10:27:29Z daintree $*/

/*Through deviousness and cunning, this system allows trial balances for any date range that recalcuates the p & l balances
and shows the balance sheets as at the end of the period selected - so first off need to show the input of criteria screen
while the user is selecting the criteria the system is posting any unposted transactions */


include ('includes/session.inc');
$title = _('Trial Balance');
include('includes/SQL_CommonFunctions.inc');
include('includes/AccountSectionsDef.inc'); //this reads in the Accounts Sections array


/*if (isset($_POST['FromPeriod']) and isset($_POST['ToPeriod']) and $_POST['FromPeriod'] > $_POST['ToPeriod']){
	prnMsg(_('The selected period from is actually after the period to! Please re-select the reporting period'),'error');
	$_POST['SelectADifferentPeriod']=_('Select A Different Period');
}*/

if ((! isset($_POST['FromPeriod']) AND ! isset($_POST['ToPeriod'])) OR isset($_POST['SelectADifferentPeriod'])){

	include  ('includes/header.inc');
	echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Trial Balance</a></div>';
	echo '<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">';
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
	echo '<table style="border:none;">
			<tr>	<td align="left" class="tdform-width"><fieldset><legend>Trial Balance</legend>
	
    <table align="left" class="frmtbl">
			<tr>
				<td><div class="maincol">' . _('Select Period From:') . '</div>
				<div class="maincol"><select Name="FromPeriod">';
	$NextYear = date('Y-m-d',strtotime('+1 Year'));
	$sql = "SELECT periodno,
					lastdate_in_period
				FROM periods
				WHERE lastdate_in_period < '" . $NextYear . "'
				ORDER BY periodno DESC";
	$Periods = DB_query($sql,$db);


	while ($myrow=DB_fetch_array($Periods,$db)){
		if(isset($_POST['FromPeriod']) AND $_POST['FromPeriod']!=''){
			if( $_POST['FromPeriod']== $myrow['periodno']){
				echo '<option selected value="' . $myrow['periodno'] . '">' .MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		} else {
			if($myrow['lastdate_in_period']==$DefaultFromDate){
				echo '<option selected value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		}
	}

	echo '</select></div></td>';
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

	echo '<td><div class="maincol">' . _('Select Period To:') .'</div>
			<div class="maincol"><select Name="ToPeriod">';

	$RetResult = DB_data_seek($Periods,0);

	while ($myrow=DB_fetch_array($Periods,$db)){

		if($myrow['periodno']==$DefaultToPeriod){
			echo '<option selected value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		} else {
			echo '<option value ="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		}
	}
	echo '</select></div></td><td><input type=submit Name="ShowTB" Value="' . _('Generate') .'"></td>
		</tr><tr><td>&nbsp;</td></tr>
		</table></fieldset>
		<br />';

	echo '<div class="centre">';
	

/*Now do the posting while the user is thinking about the period to select */

	include ('includes/GLPostings.inc');

}  
else if(isset($_POST['FromPeriod']) and isset($_POST['ToPeriod']) and $_POST['FromPeriod'] > $_POST['ToPeriod'])
{
	include  ('includes/header.inc');
	
	
	echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Trial Balance</a></div>';
	
	prnMsg(_('From Period Can Not Be Greater Than To Period'),'error');
	$_POST['SelectADifferentPeriod']=_('Select A Different Period');
	echo '<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">';
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
	echo '<table style="border:none;">
			<tr>	<td align="left" class="tdform-width"><fieldset><legend>Trial Balance</legend>
	
    <table align="left" class="frmtbl">
			<tr>
				<td><div class="maincol">' . _('Select Period From:') . '</div>
				<div class="maincol"><select Name="FromPeriod">';
	$NextYear = date('Y-m-d',strtotime('+1 Year'));
	$sql = "SELECT periodno,
					lastdate_in_period
				FROM periods
				WHERE lastdate_in_period < '" . $NextYear . "'
				ORDER BY periodno DESC";
	$Periods = DB_query($sql,$db);


	while ($myrow=DB_fetch_array($Periods,$db)){
		if(isset($_POST['FromPeriod']) AND $_POST['FromPeriod']!=''){
			if( $_POST['FromPeriod']== $myrow['periodno']){
				echo '<option selected value="' . $myrow['periodno'] . '">' .MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		} else {
			if($myrow['lastdate_in_period']==$DefaultFromDate){
				echo '<option selected value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			} else {
				echo '<option value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
			}
		}
	}

	echo '</select></div></td>';
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

	echo '<td><div class="maincol">' . _('Select Period To:') .'</div>
			<div class="maincol"><select Name="ToPeriod">';

	$RetResult = DB_data_seek($Periods,0);

	while ($myrow=DB_fetch_array($Periods,$db)){

		if($myrow['periodno']==$DefaultToPeriod){
			echo '<option selected value="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		} else {
			echo '<option value ="' . $myrow['periodno'] . '">' . MonthAndYearFromSQLDate($myrow['lastdate_in_period']) . '</option>';
		}
	}
	echo '</select></div></td><td><input type=submit Name="ShowTB" Value="' . _('Generate') .'"></td>
		</tr><tr><td>&nbsp;</td></tr>
		</table></fieldset>
		<br />';

	echo '<div class="centre">';
	

/*Now do the posting while the user is thinking about the period to select */

	include ('includes/GLPostings.inc');
}
else {

	include('includes/header.inc');
	echo '<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '?' . SID . '">';
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

	$RetainedEarningsAct = $_SESSION['CompanyRecord']['retainedearnings'];

	$SQL = "SELECT accountgroups.groupname,
			accountgroups.parentgroupname,
			accountgroups.pandl,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['FromPeriod'] . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
			Sum(CASE WHEN chartdetails.period='" . $_POST['ToPeriod'] . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
		FROM chartmaster INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails ON chartmaster.accountcode= chartdetails.accountcode
		GROUP BY accountgroups.groupname,
				accountgroups.pandl,
				accountgroups.sequenceintb,
				accountgroups.parentgroupname,
				chartdetails.accountcode,
				chartmaster.accountname
		ORDER BY accountgroups.pandl desc,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";


	$AccountsResult = DB_query($SQL,
				$db,
				 _('No general ledger accounts were returned by the SQL because'),
				 _('The SQL that failed was:'));

	echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['SCRIPT_NAME'].'">Trial Balance</a></div>';

	/*show a table of the accounts info returned by the SQL
	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */
$corpbranch=getCorporationBranch($_SESSION['uid'],$db);

	echo '<table cellpadding="2" class="selection"><tr class="oddrow"><td colspan="4"><h2>Trial Balance</h2></td></tr><tr><td colspan="4" align="right"><a href="/'.$u[1].'/generatevoucherpdf.php?sdate='.$_POST['FromPeriod'].'&edate='.$_POST['ToPeriod'].'&op=trial_balance&branch='.$corpbranch.'" target="blank"><img src="images/pdf_icon.gif"/></a></td></tr>';
	echo '<tr><th colspan=6><b>'. _('Trial Balance for the month of ') . $PeriodToDate .
		_(' and for the ') . $NumberOfMonths . _(' months to ') . $PeriodToDate .'</b></th></tr>';
	$TableHeader = '<tr>
					<th>' . _('Account') . '</th>
					<th>' . _('Account Name') . '</th>
					
					
					<th >' . _('Debit') . '</th><th >' . _('Credit') . '</th>
					
					</tr>';

	$j = 1;
	$k=0; //row colour counter
	$ActGrp ='';
	$ParentGroups = array();
	$Level =1; //level of nested sub-groups
	$ParentGroups[$Level]='';
	$GrpActual =array(0);
	$GrpBudget =array(0);
	$GrpPrdActual =array(0);
	$GrpPrdBudget =array(0);

	$PeriodProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;
	$MonthProfitLoss = 0;
	$MonthBudgetProfitLoss = 0;
	$BFwdProfitLoss = 0;
	$CheckMonth = 0;
	$CheckBudgetMonth = 0;
	$CheckPeriodActuald = 0;
	$CheckPeriodActualc = 0;
	$CheckPeriodBudget = 0;

	while ($myrow=DB_fetch_array($AccountsResult)) {

		if ($myrow['groupname']!= $ActGrp ){
			if ($ActGrp !=''){ //so its not the first account group of the first account displayed
				if ($myrow['parentgroupname']==$ActGrp){
					$Level++;
					$ParentGroups[$Level]=$myrow['groupname'];
					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]='';
				} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
					printf('<tr>
						<td colspan=2><b>%s ' . _('Total') . ' </b></td>
						
						
						<td class=numberalign="right"><b>%s</b></td><td></td>
						
						</tr>',
						$ParentGroups[$Level],
						
						
						round(abs($GrpPrdActual[$Level])));

					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]=$myrow['groupname'];
				} else {
					do {
						printf('<tr>
							<td colspan=2><font size=2><b>%s ' . _('Total') . '</b></font></td>
							
							
							<td class=number align="right"><b>%s</b></td><td></td>
							
							</tr>',
							$ParentGroups[$Level],
						
							
							round(abs($GrpPrdActual[$Level])));

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
						$Level--;

						$j++;
					} while ($Level>0 and $myrow['groupname']!=$ParentGroups[$Level]);

					if ($Level>0){
						printf('<tr>
						<td colspan=2><font size=2><b>%s ' . _('Total') . ' </b></font></td>
						
						
						<td class=numberalign="right"><b>%s</b></td><td></td>
						
						</tr>',
						$ParentGroups[$Level],
						
						
						round(abs($GrpPrdActual[$Level])));

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
					} else {
						$Level=1;
					}
				}
			}
			$ParentGroups[$Level]=$myrow['groupname'];
			$ActGrp = $myrow['groupname'];
			printf('<tr><td>&nbsp;</td></tr><tr class="oddrow">
				<td colspan=6 align="center">%s</td>
				</tr>',
				$myrow['groupname']);
			echo $TableHeader;
			$j++;
		}

		if ($k==1){
			echo '<tr class="even">';
			$k=0;
		} else {
			echo '<tr class="odd">';
			$k++;
		}
		/*MonthActual, MonthBudget, FirstPrdBFwd, FirstPrdBudgetBFwd, LastPrdBudgetCFwd, LastPrdCFwd */


		if ($myrow['pandl']==1){

			$AccountPeriodActual = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
			$AccountPeriodBudget = $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];

			$PeriodProfitLoss += $AccountPeriodActual;
			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
			$MonthProfitLoss += $myrow['monthactual'];
			$MonthBudgetProfitLoss += $myrow['monthbudget'];
			$BFwdProfitLoss += $myrow['firstprdbfwd'];
		} else { /*PandL ==0 its a balance sheet account */
			if ($myrow['accountcode']==$RetainedEarningsAct){
				$AccountPeriodActual = $BFwdProfitLoss + $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $BFwdProfitLoss + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			} else {
				$AccountPeriodActual = $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $myrow['firstprdbfwd'] + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			}

		}

		if (!isset($GrpActual[$Level])) {
			$GrpActual[$Level]=0;
		}
		if (!isset($GrpBudget[$Level])) {
			$GrpBudget[$Level]=0;
		}
		if (!isset($GrpPrdActual[$Level])) {
			$GrpPrdActual[$Level]=0;
		}
		if (!isset($GrpPrdBudget[$Level])) {
			$GrpPrdBudget[$Level]=0;
		}
		$GrpActual[$Level] +=$myrow['monthactual'];
		$GrpBudget[$Level] +=$myrow['monthbudget'];
		$GrpPrdActual[$Level] +=$AccountPeriodActual;
		$GrpPrdBudget[$Level] +=$AccountPeriodBudget;

		$CheckMonth += $myrow['monthactual'];
		$CheckBudgetMonth += $myrow['monthbudget'];
		//echo $AccountPeriodActual."<br>";
		if($AccountPeriodActual>0)
		{
		$CheckPeriodActuald += $AccountPeriodActual;
		}
		if($AccountPeriodActual<0)
		{
		 $CheckPeriodActualc += $AccountPeriodActual;
        }
		$CheckPeriodBudget += $AccountPeriodBudget;
		$ActEnquiryURL = '<a href="'. $rootpath . '/GLAccountInquiry.php?Period=' . $_POST['ToPeriod'] . '&Account=' . $myrow['accountcode'] . '&Show=Yes">' . $myrow['accountcode'] . '<a>';
		
		if((round($AccountPeriodActual))>0)
		   { 
		     $actd=round(abs($AccountPeriodActual)) ;
			 $actc="0" ;
			} 
		else if ((round($AccountPeriodActual))<0)
		   { 
		     $actc=round(abs($AccountPeriodActual)) ;
			 $actd="0";
		   }
		   else
		   {
		     $actd=round(abs($AccountPeriodActual)) ;
			  $actc=round(abs($AccountPeriodActual)) ;
		   }

		echo '<td>'.$ActEnquiryURL.'</td>
			<td>'.$myrow['accountname'].'</td>
			
			
			<td class=number align="right">'. $actd.'</td><td class=number align="right">'. $actc.'</td>
			
			</tr>';
			

		$j++;
	}
	//end of while loop


	if ($ActGrp !=''){ //so its not the first account group of the first account displayed
		if ($myrow['parentgroupname']==$ActGrp){
			$Level++;
			$ParentGroups[$Level]=$myrow['groupname'];
		} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
			printf('<tr>
				<td colspan=2><font size=2><b>%s ' . _('Total') . '</b></font></td>
				
				
				<td class=number align="right"><b>%s</b></td><td></td>
				
				</tr>',
				$ParentGroups[$Level],
				
				
				round(abs($GrpPrdActual[$Level])));

			$GrpActual[$Level] =0;
			$GrpBudget[$Level] =0;
			$GrpPrdActual[$Level] =0;
			$GrpPrdBudget[$Level] =0;
			$ParentGroups[$Level]=$myrow['groupname'];
		} else {
			do {
				printf('<tr>
					<td colspan=2><font size=2><b>%s ' . _('Total') . '</b></font></td>
					
					
					<td class=number align="right"><b>%s</b></td><td></td>
					
					</tr>',
					$ParentGroups[$Level],
					
					
					round(abs($GrpPrdActual[$Level])));

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
				$Level--;

				$j++;
			} while (isset($ParentGroups[$Level]) and ($myrow['groupname']!=$ParentGroups[$Level] and $Level>0));

			if ($Level >0){
				printf('<tr>
				<td colspan=2><font size=2><b>%s ' . _('Total') . '</b></font></td>
				
				
				<td class=number align="right"><b>%s</b></td><td></td>
				
				</tr>',
				$ParentGroups[$Level],
				
				
				round(abs($GrpPrdActual[$Level])));

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
			} else {
				$Level =1;
			}
		}
	}



	printf('<tr bgcolor="#ffffff">
			<td colspan=2><font color=BLACK><b>' . _('Check Totals') . '</b></font></td>
			
			<td class=number align="right"><b>%s</b></td>
			<td class=number align="right"><b>%s</b></td>
			
		</tr>',
		
		
		round(abs($CheckPeriodActuald)),
		round(abs($CheckPeriodActualc)));

	echo '</table><br />';
	echo '<div class="centre" align="center"><input type=submit Name="SelectADifferentPeriod" value="' . _('Select A Different Period') . '" /></div>';
}

echo '</form></td></tr></table>';
include('includes/footer.inc');

?>