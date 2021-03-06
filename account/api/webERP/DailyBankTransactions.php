<?php

/* $Id: DailyBankTransactions.php 4556 2011-04-26 11:03:36Z daintree $ */

include ('includes/session.inc');
$title = _('Bank Transactions Inquiry');
include('includes/header.inc');

if (!isset($_POST['Show'])) {
	echo '<form action="' . $_SERVER['PHP_SELF'] . '" method=post>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	echo '<table cellspacing="1" cellpadding="2">';

	$SQL = "SELECT 	bankaccountname,
					bankaccounts.accountcode,
					bankaccounts.currcode
			FROM bankaccounts,
				chartmaster
			WHERE bankaccounts.accountcode=chartmaster.accountcode";

	$ErrMsg = _('The bank accounts could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve the bank accounts was');
	$AccountsResults = DB_query($SQL,$db,$ErrMsg,$DbgMsg);

	echo '
	<tr class="oddrow"><td colspan="2" align="center"><font size="3">Bank Transactions Inquiry</td></tr>
	<tr class="evenrow"><td>' . _('Bank Account') . ':</td>
			<td><select name="BankAccount">';

	if (DB_num_rows($AccountsResults)==0){
		echo '</select></td>
				</tr></table>';
		prnMsg( _('Bank Accounts have not yet been defined. You must first') . ' <a href="' . $rootpath . '/BankAccounts.php">' . _('define the bank accounts') . '</a> ' . _('and general ledger accounts to be affected'),'warn');
		include('includes/footer.inc');
		exit;
	} else {
		while ($myrow=DB_fetch_array($AccountsResults)){
		/*list the bank account names */
			if (!isset($_POST['BankAccount']) AND $myrow['currcode']==$_SESSION['CompanyRecord']['currencydefault']){
				$_POST['BankAccount']=$myrow['accountcode'];
			}
			if ($_POST['BankAccount']==$myrow['accountcode']){
				echo '<option selected value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . ' - ' . $myrow['currcode'] . '</option>';
			} else {
				echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . ' - ' . $myrow['currcode'] . '</option>';
			}
		}
		echo '</select></td></tr>';
	}
	echo '<tr class="oddrow"><td>' . _('Transactions Dated From') . ':</td>
		<td><input type="text" name="FromTransDate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' .
				date($_SESSION['DefaultDateFormat']) . '"></td></tr>
		<tr class="evenrow"><td>' . _('Transactions Dated To') . ':</td>
		<td><input type="text" name="ToTransDate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11
			onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' .
				date($_SESSION['DefaultDateFormat']) . '"></td>
		</tr>
		<tr class="oddrow"><td colspan="2" align="center"><input type="submit" name="Show" value="' . _('Show transactions'). '"></td></tr>
		';

	echo '</table>';
	echo '</form>';
} else {
	$SQL = "SELECT 	bankaccountname,
					bankaccounts.currcode,
					currencies.decimalplaces
			FROM bankaccounts 
			INNER JOIN currencies
				ON bankaccounts.currcode = currencies.currabrev
			WHERE bankaccounts.accountcode='" . $_POST['BankAccount'] . "'";
	$BankResult = DB_query($SQL,$db,_('Could not retrieve the bank account details'));
	
	
	$sql="SELECT 	banktrans.currcode,
					banktrans.amount,
					banktrans.functionalexrate,
					banktrans.exrate,
					banktrans.banktranstype,
					banktrans.transdate,
					bankaccounts.bankaccountname,
					systypes.typename,
					systypes.typeid
				FROM banktrans
				INNER JOIN bankaccounts
				ON banktrans.bankact=bankaccounts.accountcode
				INNER JOIN systypes
				ON banktrans.type=systypes.typeid
				WHERE bankact='".$_POST['BankAccount']."'
					AND transdate>='" . FormatDateForSQL($_POST['FromTransDate']) . "'
					AND transdate<='" . FormatDateForSQL($_POST['ToTransDate']) . "' 
				ORDER BY banktrans.transdate";
	$result = DB_query($sql, $db);
	if (DB_num_rows($result)==0) {
		prnMsg(_('There are no transactions for this account in the date range selected'), 'info');
	} else {
		$BankDetailRow = DB_fetch_array($BankResult);
		echo '<table>
				<tr class="oddrow"><td width="80px"><img src="images/report_logo.png"/></td><td align="center">HIMACHAL PRADESH SCHEDULED CASTES DEVELOPMENT CORPORATION</td></tr></table><table>
				<tr>
					<td>&nbsp;<strong>Report Name : </strong>' . _('Account Transactions').'</td><td><strong>Bank Name : </strong>'.$BankDetailRow['bankaccountname'].' '._('Between').'</td></tr><tr ><td>&nbsp;<strong>From Date : </strong>'.$_POST['FromTransDate'] . '</td><td> <strong>' . _('To Date :') . '</strong> ' . $_POST['ToTransDate'] . '</td>
				</tr><tr></tr></table>';
		echo '<table><thead><tr>
				<th>' . ('Date') . '</th>
				<th>'._('Transaction type').'</th>
				<th>'._('Type').'</th>
				<th>'._('Reference').'</th>
				<th>'._('Amount in').' '.$BankDetailRow['currcode'].'</th>
				<th>'._('Running Total').' '.$BankDetailRow['currcode'].'</th>
				<th>'._('Amount in').' '.$_SESSION['CompanyRecord']['currencydefault'].'</th>
				<th>'._('Running Total').' '.$_SESSION['CompanyRecord']['currencydefault'].'</th>
			</tr></thead>';
		
		$AccountCurrTotal=0;
		$LocalCurrTotal =0;
		
		while ($myrow = DB_fetch_array($result)){
			
			$AccountCurrTotal += $myrow['amount'];
			$LocalCurrTotal += $myrow['amount']/$myrow['functionalexrate']/$myrow['exrate'];
			
			echo '<tr class="odd">
					<td>'. ConvertSQLDate($myrow['transdate']) . '</td>
					<td>'.$myrow['typename'].'</td>
					<td>'.$myrow['banktranstype'].'</td>
					<td>'.$myrow['ref'].'</td>
					<td class=number>'.number_format($myrow['amount'],$BankDetailRow['decimalplaces']).'</td>
					<td class=number>'.number_format($AccountCurrTotal,$BankDetailRow['decimalplaces']).'</td>
					<td class=number>'.number_format($myrow['amount']/$myrow['functionalexrate']/$myrow['exrate'],$_SESSION['CompanyRecord']['decimalplaces']).'</td>
					<td class=number>'.number_format($LocalCurrTotal,$_SESSION['CompanyRecord']['decimalplaces']).'</td>
				</tr>';
		}
		echo '<tr><td colspan="10" align="right" style="font-size:10px;">*This is a Computer Generated Report. Signature Not Required*</td></tr></table>';
	} //end if no bank trans in the range to show
	
	echo '<form action="' . $_SERVER['PHP_SELF'] . '" method=post>';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<br /><div class="centre"><input type="submit" name="Return" value="' . _('Select Another Date'). '"></div>';
	echo '</form>';
}
include('includes/footer.inc');

?>