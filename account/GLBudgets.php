<?php
/* $Id: GLBudgets.php 4624 2011-07-05 10:36:13Z daintree $*/

include('includes/session.inc');
include('includes/SQL_CommonFunctions.inc');

$title = _('Create GL Budgets');

include('includes/header.inc');

if (isset($_POST['SelectedAccount'])){
	$SelectedAccount = $_POST['SelectedAccount'];
} elseif (isset($_GET['SelectedAccount'])){
	$SelectedAccount = $_GET['SelectedAccount'];
}

if (isset($_POST['Previous'])) {
	$SelectedAccount = $_POST['PrevAccount'];
} elseif (isset($_POST['Next'])) {
	$SelectedAccount = $_POST['NextAccount'];
}

if (isset($_POST['update'])) {
	prnMsg(_('Budget updated successfully'), 'success');
}

//If an account has not been selected then select one here.

echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Gl Budgets</a></div><form action="' . $_SERVER['PHP_SELF'] . '" method="post" name="selectaccount">';
if( isset($_POST['Select']) && $SelectedAccount=='')
{
	prnMsg(_('Select Account'), 'error');
 }

echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<table cellpadding="2" cellspacing="1">';

echo '
<tr class="oddrow"><td colspan="2" align="center"><h2>GL Budgets</h2></td></tr>
<tr class="evenrow"><td colspan="2"> <div class="left">'.  _('Select GL Account').  ":</div><div class='right'><select name='SelectedAccount'
		onChange='ReloadForm(selectaccount.Select)'><option value=''>--Select Account--</option>";

$SQL = "SELECT accountcode,
				accountname
			FROM chartmaster
			ORDER BY accountcode";

$result=DB_query($SQL,$db);
if (DB_num_rows($result)==0){
	echo '</select></div></td></tr>';
	prnMsg(_('No General ledger accounts have been set up yet') . ' - ' . _('budgets cannot be allocated until the GL accounts are set up'),'warn');
} else {
	while ($myrow=DB_fetch_array($result)){
		$account = $myrow['accountcode'] . ' - ' . $myrow['accountname'];
		if (isset($SelectedAccount) and isset($LastCode) and $SelectedAccount==$myrow['accountcode']){
			echo '<option selected value=' . $myrow['accountcode'] . '>' . $account . '</option>';
			$PrevCode=$LastCode;
		} else {
			echo '<option value=' . $myrow['accountcode'] . '>' . $account . '</option>';
			if (isset($SelectedAccount) and isset($LastCode) and $SelectedAccount == $LastCode) {
				$NextCode=$myrow['accountcode'];
			}
		}
		$LastCode=$myrow['accountcode'];
	}
	echo '</select></td></tr>';
}

if (!isset($PrevCode)) {$PrevCode='';}
if (!isset($NextCode)) {$NextCode='';}

echo '<input type="hidden" name="PrevAccount" value='.$PrevCode.'>';
echo '<input type="hidden" name="NextAccount" value='.$NextCode.'>';

echo '<tr class="oddrow"><td colspan="2" align="center" ><input type="submit" name="Select" value="' . _('Select Account') . '">';
echo '&nbsp;&nbsp;<input type="submit" name="Next" value="' . _('Next Account') . '"></td></tr>';
echo '</table><br/>';
echo '</form>';

// End of account selection


if (isset($SelectedAccount) and $SelectedAccount != '') {

	$CurrentYearEndPeriod = GetPeriod(Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],0)),$db);

// If the update button has been hit, then update chartdetails with the budget figures
// for this year and next.
	if (isset($_POST['update'])) {
		$ErrMsg = _('Cannot update GL budgets');
		$DbgMsg = _('The SQL that failed to update the GL budgets was');
		for ($i=1; $i<=12; $i++) {
			$SQL="UPDATE chartdetails SET budget='".round($_POST[$i.'last']). "'
					WHERE period='" . ($CurrentYearEndPeriod-(24-$i)) ."'
					AND  accountcode = '" . $SelectedAccount."'";
			$result=DB_query($SQL,$db,$ErrMsg,$DbgMsg);
			$SQL="UPDATE chartdetails SET budget='".round($_POST[$i.'this']). "'
					WHERE period='" . ($CurrentYearEndPeriod-(12-$i)) ."'
					AND  accountcode = '" . $SelectedAccount."'";
			$result=DB_query($SQL,$db,$ErrMsg,$DbgMsg);
			$SQL="UPDATE chartdetails SET budget='".round($_POST[$i.'next'])."'
					WHERE period='" .  ($CurrentYearEndPeriod+$i) ."'
					AND  accountcode = '" . $SelectedAccount."'";
			$result=DB_query($SQL,$db,$ErrMsg,$DbgMsg);
		}
	}
// End of update

	$YearEndYear=Date('Y', YearEndDate($_SESSION['YearEnd'],0));

/* If the periods dont exist then create them */
	for ($i=1; $i <=36; $i++) {
		$MonthEnd=mktime(0,0,0,$_SESSION['YearEnd']+1+$i,0,$YearEndYear-2);
		$period=GetPeriod(Date($_SESSION['DefaultDateFormat'],$MonthEnd),$db, false);
		$PeriodEnd[$period]=Date('M Y',$MonthEnd);
	}
	include('includes/GLPostings.inc'); //creates chartdetails with correct values
// End of create periods

	$SQL="SELECT period,
					budget,
					actual
				FROM chartdetails
				WHERE accountcode='" . $SelectedAccount . "'";

	$result=DB_query($SQL,$db);
	while ($myrow=DB_fetch_array($result)) {
		$budget[$myrow['period']]=$myrow['budget'];
		$actual[$myrow['period']]=$myrow['actual'];
	}

	
	if (isset($_POST['apportion'])) {
		for ($i=1; $i<=12; $i++) {
			if ($_POST['AnnualAmountLY'] != '0' AND is_numeric($_POST['AnnualAmountLY'])){
				$budget[$CurrentYearEndPeriod+$i-24]	=round( $_POST['AnnualAmountLY']/12);
			}
			if ($_POST['AnnualAmountTY'] != '0' AND is_numeric($_POST['AnnualAmountTY'])){
				$budget[$CurrentYearEndPeriod+$i-12]	= round($_POST['AnnualAmountTY']/12);
			}
			if ($_POST['AnnualAmount'] != '0' AND is_numeric($_POST['AnnualAmount'])){
				$budget[$CurrentYearEndPeriod+$i]	= round($_POST['AnnualAmount']/12);
			}
		}
	}

	$LastYearActual=0;
	$LastYearBudget=0;
	$ThisYearActual=0;
	$ThisYearBudget=0;
	$NextYearActual=0;
	$NextYearBudget=0;

// Table Headers

	echo '<form name="form" action="' . $_SERVER['PHP_SELF'] . '" method=post>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br /><div class="listingpage_scrolltable1"><table  width="100%">';
	echo '<tr><th colspan=3>'. _('Last Financial Year') .'</th>';
	echo '<th colspan=3>'. _('This Financial Year') .'</th>';
	echo '<th colspan=3>'. _('Next Financial Year') .'</th></tr>';

	echo '<thead><th colspan=3>'. _('Year ended').' - '.
		Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],-1)) .'</th>';
	echo '<th colspan=3>'. _('Year ended').' - '.
		Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],0)) .'</th>';
	echo '<th colspan=3>'. _('Year ended').' - '.
		Date($_SESSION['DefaultDateFormat'],YearEndDate($_SESSION['YearEnd'],1)) .'</th></thead>';

	echo '<thead>';
	for ($i=0; $i<3; $i++) {
		echo '<th>'. _('Period'). '</th>
				<th>'. _('Actual') . '</th>
				<th>'. _('Budget') . '</th>';
	}
	echo '</thead>';

// Main Table

	for ($i=1; $i<=12; $i++) {
		echo '<tr class="even">';
		echo '<th>'. $PeriodEnd[$CurrentYearEndPeriod-(24-$i)] .'</th>';
		echo '<td bgcolor="d2e5e8" class="number">'.round($actual[$CurrentYearEndPeriod-(24-$i)]).'</td>';
		echo '<td><input type="text" class="number" size=14 name="'.$i.'last" value="'.$budget[$CurrentYearEndPeriod-(24-$i)] .'"></td>';
		echo '<th>'. $PeriodEnd[$CurrentYearEndPeriod-(12-$i)] .'</th>';
		echo '<td bgcolor="d2e5e8" class="number">'.round($actual[$CurrentYearEndPeriod-(12-$i)]).'</td>';
		echo '<td><input type="text" class="number" size=14 name="'.$i.'this" value="'. $budget[$CurrentYearEndPeriod-(12-$i)] .'"></td>';
		echo '<th>'. $PeriodEnd[$CurrentYearEndPeriod+($i)] .'</th>';
		echo '<td bgcolor="d2e5e8" class="number">'.round($actual[$CurrentYearEndPeriod+$i]).'</td>';
		echo '<td><input type="text" class="number" size=14 name="'.$i.'next" value='. $budget[$CurrentYearEndPeriod+$i] .'></td>';
		echo '</tr>';
		$LastYearActual=$LastYearActual+$actual[$CurrentYearEndPeriod-(24-$i)];
		$LastYearBudget=$LastYearBudget+$budget[$CurrentYearEndPeriod-(24-$i)];
		$ThisYearActual=$ThisYearActual+$actual[$CurrentYearEndPeriod-(12-$i)];
		$ThisYearBudget=$ThisYearBudget+$budget[$CurrentYearEndPeriod-(12-$i)];
		$NextYearActual=$NextYearActual+$actual[$CurrentYearEndPeriod+($i)];
		$NextYearBudget=$NextYearBudget+$budget[$CurrentYearEndPeriod+($i)];
	}

// Total Line

	echo '<tr><th>'. _('Total') .'</th>';
	echo '<th align="right">'.round($LastYearActual). '</th>';
	echo '<th align="right">'.round($LastYearBudget). '</th>';
	echo '<th align="right"></th>';
	echo '<th align="right">'.round($ThisYearActual). '</th>';
	echo '<th align="right">'.round($ThisYearBudget). '</th>';
	echo '<th align="right"></th>';
	echo '<th align="right">'.round($NextYearActual). '</th>';
	echo '<th align="right">'.round($NextYearBudget). '</th></tr>';
	echo '<tr><td colspan=2>'._('Annual Budget').'</td>
				<td><input class=number type="text" size="14" name="AnnualAmountLY" value="0"></td>
				</td><td><td></td>
				<td><input class=number type="text" size=14 name="AnnualAmountTY" value="0"></td>
				<td></td><td></td>
				<td><input onChange="numberFormat(this,2)" class="number" type="text" size="14" name="AnnualAmount" value="0"></td>';
	echo '';
	echo '</tr>';
	echo '</table>';
	echo '<script>defaultControl(document.form.1next);</script>';
	echo '<br /><div align="center"><input type="submit" name="apportion" value="' . _('Apportion Budget') . '"><input type="hidden" name="SelectedAccount" value='.$SelectedAccount.'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name=update value="' . _('Update') . '"></div></div></div></form>';

	$SQL="SELECT MIN(periodno) FROM periods";
	$result=DB_query($SQL,$db);
	$MyRow=DB_fetch_array($result);
	$FirstPeriod=$MyRow[0];

	$SQL="SELECT MAX(periodno) FROM periods";
	$result=DB_query($SQL,$db);
	$MyRow=DB_fetch_array($result);
	$LastPeriod=$MyRow[0];

	for ($i=$FirstPeriod;$i<=$LastPeriod;$i++) {
		$sql="SELECT accountcode,
							period,
							budget,
							actual,
							bfwd,
							bfwdbudget
						FROM chartdetails
						WHERE period ='". $i . "' AND  accountcode = '" . $SelectedAccount . "'";

		$ErrMsg = _('Could not retrieve the ChartDetail records because');
		$result = DB_query($sql,$db,$ErrMsg);

		while ($myrow=DB_fetch_array($result)){

			$CFwdBudget = $myrow['bfwdbudget'] + $myrow['budget'];
			$sql = "UPDATE chartdetails
				SET bfwdbudget='" . $CFwdBudget . "'
				WHERE period='" . ($myrow['period'] +1) . "'
				AND  accountcode = '" . $SelectedAccount . "'";

			$ErrMsg =_('Could not update the chartdetails record because');
			$updresult = DB_query($sql,$db,$ErrMsg);
		}
	} /* end of for loop */
}

include('includes/footer.inc');

?>