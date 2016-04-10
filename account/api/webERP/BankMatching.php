<?php

/* $Id: BankMatching.php 4618 2011-07-02 23:04:59Z daintree $*/

include('includes/session.inc');
$title = _('Bank Account Matching');
include('includes/header.inc');

if ((isset($_GET['Type']) AND $_GET['Type']=='Receipts') OR
	(isset($_POST['Type']) and $_POST['Type']=='Receipts')){

	$Type = 'Receipts';
	$TypeName =_('Receipts');

} elseif ((isset($_GET['Type']) AND $_GET['Type']=='Payments') OR
			(isset($_POST['Type']) and $_POST['Type']=='Payments')) {

	$Type = 'Payments';
	$TypeName =_('Payments');

} else {

	prnMsg(_('This page must be called with a bank transaction type') . '. ' . _('It should not be called directly'),'error');
	include ('includes/footer.inc');
	exit;
}

if (isset($_GET['Account'])) {
	$_POST['BankAccount']=$_GET['Account'];
	$_POST['ShowTransactions']=true;
	$_POST['Ostg_or_All']='Ostg';
	$_POST['First20_or_All']='All';
}

if (isset($_POST['Update']) AND $_POST['RowCounter']>1){
	for ($Counter=1;$Counter <= $_POST['RowCounter']; $Counter++){
		if (isset($_POST['Clear_' . $Counter]) AND $_POST['Clear_' . $Counter]==True){
			/*Get amount to be cleared */
			$sql = "SELECT amount,
						exrate
					FROM banktrans
					WHERE banktransid='" . $_POST['BankTrans_' . $Counter]."'";
			$ErrMsg =  _('Could not retrieve transaction information');
			$result = DB_query($sql,$db,$ErrMsg);
			$myrow=DB_fetch_array($result);
			$AmountCleared = round($myrow[0] / $myrow[1],2);
			/*Update the banktrans recoord to match it off */
			$sql = "UPDATE banktrans SET amountcleared= ". $AmountCleared .
					" WHERE banktransid='" . $_POST['BankTrans_' . $Counter] . "'";
			$ErrMsg =  _('Could not match off this payment because');
			$result = DB_query($sql,$db,$ErrMsg);

		} elseif (isset($_POST['AmtClear_' . $Counter]) AND
					is_numeric((float) $_POST["AmtClear_" . $Counter]) AND
			((isset($_POST['AmtClear_' . $Counter]) AND $_POST['AmtClear_' . $Counter]<0 AND $Type=='Payments') OR
			($Type=='Receipts' AND (isset($_POST['AmtClear_' . $Counter]) and $_POST['AmtClear_' . $Counter]>0)))){
			/*if the amount entered was numeric and negative for a payment or positive for a receipt */
			
			$sql = "UPDATE banktrans SET amountcleared=" .  $_POST['AmtClear_' . $Counter] . "
					 WHERE banktransid='" . $_POST['BankTrans_' . $Counter]."'";

			$ErrMsg = _('Could not update the amount matched off this bank transaction because');
			$result = DB_query($sql,$db,$ErrMsg);

		} elseif (isset($_POST['Unclear_' . $Counter]) AND $_POST['Unclear_' . $Counter]==True){
			$sql = "UPDATE banktrans SET amountcleared = 0
					 WHERE banktransid='" . $_POST['BankTrans_' . $Counter]."'";
			$ErrMsg =  _('Could not unclear this bank transaction because');
			$result = DB_query($sql,$db,$ErrMsg);
		}
	}
	/*Show the updated position with the same criteria as previously entered*/
	$_POST['ShowTransactions'] = True;
}

echo '<form action="'. $_SERVER['PHP_SELF'] . '" method=post>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<input type="hidden" name="Type" value="' . $Type . '">';

if($_REQUEST['Type']=='Payments')
{
echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'?Type=Payments">Bank Payment Reconcillation</a></div>';
}
else if($_REQUEST['Type']=='Receipts')
{
echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'?Type=Receipts">Bank Receipt Reconcillation</a></div>';
}

echo '<table cellspacing="1" cellpadding="2">
<tr class="oddrow"><td colspan="2" align="center"><h2>';
if($_REQUEST['Type']=='Payments')
{
  echo "Bank Payment Reconcillation";
}
else if($_REQUEST['Type']=='Receipts')
{
echo "Bank Receipt Reconcillation";
}
echo '</h2></td></tr>
		<tr class="evenrow">
			<td ><div class="left">' . _('Bank Account') . ':</div>
			<div class="right"><select tabindex="1" name="BankAccount">';

$sql = "SELECT accountcode, bankaccountname,type FROM bankaccounts where type!='Cash'";
$resultBankActs = DB_query($sql,$db);
while ($myrow=DB_fetch_array($resultBankActs)){
	if (isset($_POST['BankAccount']) and $myrow['accountcode']==$_POST['BankAccount']){
		echo '<option selected value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . '</option>';
	}
}

echo '</select></div></td></tr>';

if (!isset($_POST['BeforeDate']) OR !Is_Date($_POST['BeforeDate'])){
	$_POST['BeforeDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['AfterDate']) OR !Is_Date($_POST['AfterDate'])){
	$_POST['AfterDate'] = Date($_SESSION['DefaultDateFormat'], Mktime(0,0,0,Date('m')-3,Date('d'),Date('y')));
}

// Change to allow input of FROM DATE and then TO DATE, instead of previous back-to-front method, add datepicker
echo '<tr class="oddrow"><td ><div class="left">' . _('Show') . ' ' . $TypeName . ' ' . _('from') . ':</div>
		<div class="right"><input tabindex="3" type="text" name="AfterDate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" size="12" maxlength="10" onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' . $_POST['AfterDate'] . '"></div></td></tr>';

echo '<tr class="evenrow"><td><div class="left">' . _('To') . ':</div>
	<div class="right"><input tabindex="2" type="text" name="BeforeDate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" size="12" maxlength="10" onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" Value="' . $_POST['BeforeDate'] . '"></div></td></tr>';
echo '<tr class="oddrow"><td ><div class="left">' . _('Choose outstanding') . ' ' . $TypeName . ' ' . _('only or all') . ' ' . $TypeName . ' ' . _('in the date range') . ':</div>
	<div class="right"><select tabindex="4" name="Ostg_or_All">';

if ($_POST['Ostg_or_All']=='All'){
	echo '<option selected value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option value="Ostdg">' . _('Show unmatched') . ' ' . $TypeName . ' ' . _('only') . '</option>';
} else {
	echo '<option Value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option selected value="Ostdg">' . _('Show unmatched') . ' ' . $TypeName . ' ' . _('only') . '</option>';
}
echo '</select></div></td></tr>';

echo '<tr class="evenrow"><td><div class="left">' . _('Choose to display only the first 20 matching') . ' ' . $TypeName . ' ' .
	_('or all') . ' ' . $TypeName . ' ' . _('meeting the criteria') . ':</div><div class="right"><select tabindex="5" name="First20_or_All">';
if ($_POST['First20_or_All']=='All'){
	echo '<option selected value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option value="First20">' . _('Show only the first 20') . ' ' . $TypeName . '</option>';
} else {
	echo '<option value="All">' . _('Show all') . ' ' . $TypeName . ' ' . _('in the date range') . '</option>';
	echo '<option selected value="First20">' . _('Show only the first 20') . ' ' . $TypeName . '</option>';
}
echo '</select></div></td></tr>

<tr class="oddrow"><td colspan="2" align="center">
<input tabindex="6" type=submit name="ShowTransactions" value="' . _('Show selected') . ' ' . $TypeName . '">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $rootpath . '/BankReconciliation.php"><input type="button" value="Show Reconcillation" /></a></td></tr>

';


echo '</table><br /><div class="centre">';
echo '<p></div>';

$InputError=0;
if (!Is_Date($_POST['BeforeDate'])){
	$InputError =1;
	prnMsg(_('The date entered for the field to show') . ' ' . $TypeName . ' ' . _('before') . ', ' .
		_('is not entered in a recognised date format') . '. ' . _('Entry is expected in the format') . ' ' .
		$_SESSION['DefaultDateFormat'],'error');
}
if (!Is_Date($_POST['AfterDate'])){
	$InputError =1;
	prnMsg( _('The date entered for the field to show') . ' ' . $Type . ' ' . _('after') . ', ' .
		_('is not entered in a recognised date format') . '. ' . _('Entry is expected in the format') . ' ' .
		$_SESSION['DefaultDateFormat'],'error');
}

if ($InputError !=1 AND isset($_POST['BankAccount']) AND $_POST['BankAccount']!='' AND isset($_POST['ShowTransactions'])){

	$SQLBeforeDate = FormatDateForSQL($_POST['BeforeDate']);
	$SQLAfterDate = FormatDateForSQL($_POST['AfterDate']);
//echo 'aa'.$SQLBeforeDate;

	if ($_POST['Ostg_or_All']=='All'){
		if ($Type=='Payments'){
			$sql = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount as amt,
							banktranstype
					FROM banktrans
					WHERE amount < 0
					AND transdate >= '". $SQLAfterDate . "'
					AND transdate <= '" . $SQLBeforeDate . "'
					AND bankact='" .$_POST['BankAccount'] . "'
					ORDER BY transdate";

		} else { /* Type must == Receipts */
			$sql = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount as amt,
							banktranstype
						FROM banktrans
						WHERE amount >0
						AND transdate >= '". $SQLAfterDate . "'
						AND transdate <= '" . $SQLBeforeDate . "'
						AND bankact='" .$_POST['BankAccount'] . "'
						ORDER BY transdate";
		}
	} else { /*it must be only the outstanding bank trans required */
		if ($Type=='Payments'){
			$sql = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount as amt,
							banktranstype
						FROM banktrans
						WHERE amount <0
						AND transdate >= '". $SQLAfterDate . "'
						AND transdate <= '" . $SQLBeforeDate . "'
						AND bankact=" .$_POST['BankAccount'] . "
						AND  ABS(amountcleared - (amount )) > 0.009
						ORDER BY transdate";
		} else { /* Type must == Receipts */
			$sql = "SELECT banktransid,
							ref,
							amountcleared,
							transdate,
							amount as amt,
							banktranstype
						FROM banktrans
						WHERE amount >0
						AND transdate >= '". $SQLAfterDate . "'
						AND transdate <= '" . $SQLBeforeDate . "'
						AND bankact='" .$_POST['BankAccount'] . "'
						AND  ABS(amountcleared - (amount )) > 0.009
						ORDER BY transdate";
		}
	}
	if ($_POST['First20_or_All']!='All'){
		$sql = $sql . " LIMIT 20";
	}

	$ErrMsg = _('The payments with the selected criteria could not be retrieved because');
	$PaymentsResult = DB_query($sql, $db, $ErrMsg);

	$TableHeader = '<tr><th>'. _('Refrence/Narrative'). '</th>
						<th>' . $TypeName . '</th>
						<th width="100">' . _('Date') . '</th>
						<th>' . _('Amount') . '</th>
						<th>' . _('Outstanding') . '</th>
						<th colspan="4">' . _('Clear') . ' / ' . _('Unclear') . '</th>
					</tr>';
	echo '<table cellpadding=2 cellspacing=1>' . $TableHeader;


	$j = 1;  //page length counter
	$k=0; //row colour counter
	$i = 1; //no of rows counter
    $d=1;
	while ($myrow=DB_fetch_array($PaymentsResult)) {
    if($d%2==0)
	{
	  $cla="odd";
	}
	else
	{
	  $cla="even";
	}
		$DisplayTranDat = explode('/',ConvertSQLDate($myrow['transdate']));
		$DisplayTranDate=$DisplayTranDat[0]."-".$DisplayTranDat[1]."-".$DisplayTranDat[2];
		$Outstanding = $myrow['amt']- $myrow['amountcleared'];
		if (ABS($Outstanding)<0.009){ /*the payment is cleared dont show the check box*/

			printf('<tr class="'.$cla.'">
					<td>%s</td>
					<td>%s</td>
					<td  align="center">%s</td>
					<td align="right">%s</td>
					<td  align="right">%s</td>
					<td colspan=4><input type="checkbox" name="Unclear_%s"><input type="hidden" name="BankTrans_%s" value=%s></td>
					</tr>',
					$myrow['ref'],
					$myrow['banktranstype'],
					$DisplayTranDate,
					number_format($myrow['amt'],2),
					number_format($Outstanding,2),
					_('Unclear'),
					$i,
					$i,
					$myrow['banktransid']);

		} else{
			if ($d%2==0){
				echo '<tr class="odd">';
				$k=0;
			} else {
				echo '<tr class="even">';
				$k=1;
			}
		printf('<td width="150">%s</td>
				<td align="left">%s</td>
				<td align="center">%s</td>
				<td align="right">%s</td>
				<td align="right">%s</td>
				<td width="50" align="center"><input type="checkbox" style="margin-right:10px; name="Clear_%s"><input type=hidden name="BankTrans_%s" value=%s></td>
				<td colspan=3 align="center"><input type="text" maxlength=15 size=15 class="number" name="AmtClear_%s"></td>
				</tr>',
				$myrow['ref'],
				$myrow['banktranstype'],
				$DisplayTranDate,
				number_format($myrow['amt'],2),
				number_format($Outstanding,2),
				$i,
				$i,
				$myrow['banktransid'],
				$i
			);
		}

		$j++;
		If ($j == 12){
			$j=1;
			echo $TableHeader;
		}
	//end of page full new headings if
		$i++;
		$d++;
	}
	//end of while loop

	echo '<tr class="odd"><td colspan="9" align="center"><input type=hidden name="RowCounter" value=' . $i . '>
					<input type=submit name="Update" value="' . _('Update Matching') . '"></td></tr></table>
			<br />
		</div>';
}
echo '</form>';
include('includes/footer.inc');
?>