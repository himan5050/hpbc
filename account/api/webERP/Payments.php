<?php

/* $Id: Payments.php 4636 2011-07-24 00:14:27Z daintree $*/

include('includes/DefinePaymentClass.php');
include('includes/session.inc');

$title = _('Cash Payment Voucher');

include('includes/header.inc');

include('includes/SQL_CommonFunctions.inc');

if(isset($_GET['GLAmount']))
{
  $_POST['GLAmount']=$_GET['GLAmount'];
}

if(isset($_GET['GLCode']))
{
  $_POST['GLCode']=$_GET['GLCode'];
}
if(isset($_GET['GLManualCode']))
{
  $_POST['GLManualCode']=$_GET['GLManualCode'];
}
if(isset($_GET['clid']))
{
  $_SESSION['clid']=$_GET['clid'];
}
if(isset($_GET['type']))
{
  $_SESSION['type']=$_GET['type'];
}



if (isset($_POST['PaymentCancelled'])) {
	prnMsg(_('Payment Cancelled since cheque was not printed'), 'warning');
	include('includes/footer.inc');
	exit();
}

if (isset($_GET['NewPayment']) AND $_GET['NewPayment']=='Yes'){
	unset($_SESSION['PaymentDetail']->GLItems);
	unset($_SESSION['PaymentDetail']);
}

if (!isset($_SESSION['PaymentDetail'])){
	$_SESSION['PaymentDetail'] = new Payment;
	$_SESSION['PaymentDetail']->GLItemCounter = 1;
}

if ((isset($_POST['UpdateHeader']) 
	AND $_POST['BankAccount']=='') 
	OR (isset($_POST['Process']) AND $_POST['BankAccount']=='')) {
	prnMsg(_('A bank account must be selected to make this payment from'), 'warn');
	$BankAccountEmpty=TRUE;
} else {
	$BankAccountEmpty=FALSE;
}
if(isset($_POST['Process']) AND $_POST['GLAmount']=='') {
prnMsg(_('Enter Amount'), 'warn');
	$BankAccountEmpty=TRUE;
}
if (isset($_POST['Process']) AND ($_POST['GLAmount']!='') && (!eregi('^([0-9]+(\.[0-9]+)?$)' , $_POST['GLAmount'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Amount, Numeric Value And One . is Accepted'),'error');
	}
if($_POST['GLAmount']!='' AND $_POST['GLAmount']<=0) {
prnMsg(_('Enter Valid Amount'), 'warn');
	$BankAccountEmpty=TRUE;
}
if(isset($_POST['GLNarrative']) AND $_POST['GLNarrative']=='' AND !isset($_POST['Cancel']) AND isset($_POST['Process'])) {
prnMsg(_('Enter Narrative'), 'warn');
	$BankAccountEmpty=TRUE;
}
/*echo '<div class="page_help_text">' . _('Use this screen to enter payments FROM your bank account.  <br />Note: To enter a payment FROM a supplier, first select the Supplier, click Enter a Payment to, or Receipt from the Supplier, and use a negative Payment amount on this form.') . '</div>
		<br />
*/		
echo '<div class="centre">';

if (isset($_GET['SupplierID'])){
	/*The page was called with a supplierID check it is valid and default the inputs for Supplier Name and currency of payment */

	unset($_SESSION['PaymentDetail']->GLItems);
	unset($_SESSION['PaymentDetail']);
	$_SESSION['PaymentDetail'] = new Payment;
	$_SESSION['PaymentDetail']->GLItemCounter = 1;


	$SQL= "SELECT suppname,
				address1,
				address2,
				address3,
				address4,
				address5,
				address6,
				currcode,
				factorcompanyid
			FROM suppliers 
			WHERE supplierid='" . $_GET['SupplierID'] . "'";

	$Result = DB_query($SQL, $db);
	if (DB_num_rows($Result)==0){
		prnMsg( _('The supplier code that this payment page was called with is not a currently defined supplier code') . '. ' . _('If this page is called from the selectSupplier page then this assures that a valid supplier is selected'),'warn');
		include('includes/footer.inc');
		exit;
	} else {
		$myrow = DB_fetch_array($Result);
		if ($myrow['factorcompanyid'] == 1) {
			$_SESSION['PaymentDetail']->SuppName = $myrow['suppname'];
			$_SESSION['PaymentDetail']->Address1 = $myrow['address1'];
			$_SESSION['PaymentDetail']->Address2 = $myrow['address2'];
			$_SESSION['PaymentDetail']->Address3 = $myrow['address3'];
			$_SESSION['PaymentDetail']->Address4 = $myrow['address4'];
			$_SESSION['PaymentDetail']->Address5 = $myrow['address5'];
			$_SESSION['PaymentDetail']->Address6 = $myrow['address6'];
			$_SESSION['PaymentDetail']->SupplierID = $_GET['SupplierID'];
			$_SESSION['PaymentDetail']->Currency = $myrow['currcode'];
			
		} else {
			$factorsql= "SELECT coyname,
			 					address1,
			 					address2,
			 					address3,
			 					address4,
			 					address5,
			 					address6
							FROM factorcompanies
							WHERE id='" . $myrow['factorcompanyid'] . "'";

			$FactorResult = DB_query($factorsql, $db);
			$myfactorrow = DB_fetch_array($FactorResult);
			$_SESSION['PaymentDetail']->SuppName = $myrow['suppname'] . _(' care of ') . $myfactorrow['coyname'];
			$_SESSION['PaymentDetail']->Address1 = $myfactorrow['address1'];
			$_SESSION['PaymentDetail']->Address2 = $myfactorrow['address2'];
			$_SESSION['PaymentDetail']->Address3 = $myfactorrow['address3'];
			$_SESSION['PaymentDetail']->Address4 = $myfactorrow['address4'];
			$_SESSION['PaymentDetail']->Address5 = $myfactorrow['address5'];
			$_SESSION['PaymentDetail']->Address6 = $myfactorrow['address6'];
			$_SESSION['PaymentDetail']->SupplierID = $_GET['SupplierID'];
			$_SESSION['PaymentDetail']->Currency = $myrow['currcode'];
			$_POST['Currency'] = $_SESSION['PaymentDetail']->Currency;
		}
	}
}

if (isset($_POST['BankAccount']) AND $_POST['BankAccount']!=''){
	$_SESSION['PaymentDetail']->Account=$_POST['BankAccount'];
	/*Get the bank account currency and set that too */
	$ErrMsg = _('Could not get the currency of the bank account');
	$result = DB_query("SELECT currcode, decimalplaces 
						FROM bankaccounts INNER JOIN currencies
						ON bankaccounts.currcode=currencies.currabrev
						WHERE accountcode ='" . $_POST['BankAccount'] . "'",$db,$ErrMsg);
	$myrow = DB_fetch_array($result);
	$_SESSION['PaymentDetail']->AccountCurrency=$myrow['currcode'];
	$_SESSION['PaymentDetail']->CurrDecimalPlaces=$myrow['decimalplaces'];

} else {
	$_SESSION['PaymentDetail']->AccountCurrency =$_SESSION['CompanyRecord']['currencydefault'];
	$_SESSION['PaymentDetail']->CurrDecimalPlaces=$_SESSION['CompanyRecord']['decimalplaces'];
}
if (isset($_POST['DatePaid']) AND $_POST['DatePaid']!='' AND Is_Date($_POST['DatePaid'])){
	$_SESSION['PaymentDetail']->DatePaid=$_POST['DatePaid'];
}
if (isset($_POST['cheque']) AND $_POST['cheque']!='' ){
	$_SESSION['PaymentDetail']->cheque=$_POST['cheque'];
}

if (isset($_POST['ExRate']) AND $_POST['ExRate']!=''){
	$_SESSION['PaymentDetail']->ExRate=$_POST['ExRate']; //ex rate between payment currency and account currency
}
if (isset($_POST['FunctionalExRate']) AND $_POST['FunctionalExRate']!=''){
	$_SESSION['PaymentDetail']->FunctionalExRate=$_POST['FunctionalExRate']; //ex rate between payment currency and account currency
}
if (isset($_POST['Paymenttype']) AND $_POST['Paymenttype']!=''){
	$_SESSION['PaymentDetail']->Paymenttype = $_POST['Paymenttype'];
}

if (isset($_POST['Currency']) AND $_POST['Currency']!=''){
	$_SESSION['PaymentDetail']->Currency=$_POST['Currency']; //payment currency
	/*Get the exchange rate between the functional currency and the payment currency*/
	$result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail']->Currency . "'",$db);
	$myrow = DB_fetch_row($result);
	$tableExRate = $myrow[0]; //this is the rate of exchange between the functional currency and the payment currency

	if ($_POST['Currency']==$_SESSION['PaymentDetail']->AccountCurrency){
		$_POST['ExRate']=1;
		$_SESSION['PaymentDetail']->ExRate=$_POST['ExRate']; //ex rate between payment currency and account currency
		$SuggestedExRate=1;
	}
	if ($_SESSION['PaymentDetail']->AccountCurrency==$_SESSION['CompanyRecord']['currencydefault']){
		$_POST['FunctionalExRate']=1;
		$_SESSION['PaymentDetail']->FunctionalExRate=$_POST['FunctionalExRate'];
		$SuggestedFunctionalExRate =1;
		$SuggestedExRate = $tableExRate;

	} else {
		/*To illustrate the rates required
			Take an example functional currency NZD payment in USD from an AUD bank account
			1 NZD = 0.80 USD
			1 NZD = 0.90 AUD
			The FunctionalExRate = 0.90 - the rate between the functional currency and the bank account currency
			The payment ex rate is the rate at which one can purchase the payment currency in the bank account currency
			or 0.8/0.9 = 0.88889
		*/

		/*Get suggested FunctionalExRate */
		$result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail']->AccountCurrency . "'",$db);
		$myrow = DB_fetch_row($result);
		$SuggestedFunctionalExRate = $myrow[0];

		/*Get the exchange rate between the functional currency and the payment currency*/
		$result = DB_query("SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail']->Currency . "'",$db);
		$myrow = DB_fetch_row($result);
		$tableExRate = $myrow[0]; //this is the rate of exchange between the functional currency and the payment currency
		/*Calculate cross rate to suggest appropriate exchange rate between payment currency and account currency */
		$SuggestedExRate = $tableExRate/$SuggestedFunctionalExRate;

	}
}


if (isset($_POST['Narrative']) AND $_POST['Narrative']!=''){
	$_SESSION['PaymentDetail']->Narrative=$_POST['Narrative'];
}
if (isset($_POST['preparedby']) AND $_POST['preparedby']!=''){
	$_SESSION['preparedby']->preparedby=$_POST['preparedby'];
}
if (isset($_POST['approvedby']) AND $_POST['approvedby']!=''){
	$_SESSION['approvedby']->approvedby=$_POST['approvedby'];
}
if (isset($_POST['Amount']) AND $_POST['Amount']!=""){
	$_SESSION['PaymentDetail']->Amount=$_POST['Amount'];
} else {
	if (!isset($_SESSION['PaymentDetail']->Amount)) {
		$_SESSION['PaymentDetail']->Amount=0;
	}
}
if (isset($_POST['Discount']) AND $_POST['Discount']!=''){
	$_SESSION['PaymentDetail']->Discount=$_POST['Discount'];
} else {
	if (!isset($_SESSION['PaymentDetail']->Discount)) {
	  $_SESSION['PaymentDetail']->Discount=0;
  }
}


if (isset($_POST['CommitBatch'])){

  /* once the GL analysis of the payment is entered (if the Creditors_GLLink is active),
  process all the data in the session cookie into the DB creating a banktrans record for
  the payment in the batch and SuppTrans record for the supplier payment if a supplier was selected
  A GL entry is created for each GL entry (only one for a supplier entry) and one for the bank
  account credit.

  NB allocations against supplier payments are a separate exercice

  if GL integrated then
  first off run through the array of payment items $_SESSION['Payment']->GLItems and
  create GL Entries for the GL payment items
  */

	/*First off  check we have an amount entered as paid ?? */
	chmod("voucher.txt", 0777);
	$file=fopen("voucher.txt","r");
               $vn=fread($file,50);
	
	$TotalAmount =0;
	foreach ($_SESSION['PaymentDetail']->GLItems AS $PaymentItem) {
		$TotalAmount += $PaymentItem->Amount;
	}

	if ($TotalAmount==0 AND
		($_SESSION['PaymentDetail']->Discount + $_SESSION['PaymentDetail']->Amount)/$_SESSION['PaymentDetail']->ExRate ==0){
		prnMsg( _('This payment has no amounts entered and will not be processed'),'warn');
		include('includes/footer.inc');
		exit;
	}

	if ($_POST['BankAccount']=='') {
		prnMsg( _('No bank account has been selected so this payment cannot be processed'),'warn');
		include('includes/footer.inc');
		exit;
	}

	/*Make an array of the defined bank accounts */
	$SQL = "SELECT bankaccounts.accountcode
			FROM bankaccounts,
				chartmaster
			WHERE bankaccounts.accountcode=chartmaster.accountcode";
	$result = DB_query($SQL,$db);
	$BankAccounts = array();
	$i=0;

	while ($Act = DB_fetch_row($result)){
		$BankAccounts[$i]= $Act[0];
		$i++;
	}

	$PeriodNo = GetPeriod($_SESSION['PaymentDetail']->DatePaid,$db);

	$sql="SELECT usepreprintedstationery
			FROM paymentmethods
			WHERE paymentname='" . $_SESSION['PaymentDetail']->Paymenttype ."'";
	$result=DB_query($sql, $db);
	$myrow=DB_fetch_row($result);

	// first time through commit if supplier cheque then print it first
	if ((!isset($_POST['ChequePrinted']))
		AND (!isset($_POST['PaymentCancelled']))
		AND ($myrow[0] == 1)) {
	// it is a supplier payment by cheque and haven't printed yet so print cheque

		echo '<br />
			<a href="' . $rootpath . '/PrintCheque.php?ChequeNum=' . $_POST['cheque'] . '">' . _('Print Cheque using pre-printed stationery') . '</a>
			<br />
			<br />';

		echo '<form method=post action="' . $_SERVER['PHP_SELF'] . '">';
		echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
		echo _('Has the cheque been printed') . '?<br /><br />';
		echo '<input type="hidden" name="CommitBatch" value="' . $_POST['CommitBatch'] . '" />';
		echo '<input type="hidden" name="BankAccount" value="' . $_POST['BankAccount'] . '" />';
		echo '<input type="submit" name="ChequePrinted" value="' . _('Yes / Continue') . '" />&nbsp;&nbsp;';
		echo '<input type="submit" name="PaymentCancelled" value="' . _('No / Cancel Payment') . '" />';
	} else {

		//Start a transaction to do the whole lot inside
		
		$result = DB_Txn_Begin($db);


		if ($_SESSION['PaymentDetail']->SupplierID=='') {

		//its a nominal bank transaction type 1

			$TransNo = GetNextTransNo( 1, $db);
			$Transtype = 1;

			if ($_SESSION['CompanyRecord']['gllink_creditors']==1){ /* then enter GLTrans */
				$TotalAmount=0;
				
				
				foreach ($_SESSION['PaymentDetail']->GLItems as $PaymentItem) {

					 /*The functional currency amount will be the
					 payment currenct amount  / the bank account currency exchange rate  - to get to the bank account currency
					 then / the functional currency exchange rate to get to the functional currency */
					if ($PaymentItem->cheque=='') $PaymentItem->cheque=0;
					 $SQL = "INSERT INTO gltrans (type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount,
												chequeno,
												preparedby,
												approvedby,
												tag,
												voucher_no) ";
					 $SQL= $SQL . "VALUES (1,
						'" . $TransNo . "',
						'" . FormatDateForSQL($_SESSION['PaymentDetail']->DatePaid) . "',
						'" . $PeriodNo . "',
						'" . $PaymentItem->GLCode . "',
						'" . $PaymentItem->Narrative . "',
						'" . ($PaymentItem->Amount) . "',
						'". $_SESSION['PaymentDetail']->cheque ."',
						'".  $_SESSION['UsersRealName'] ."',
						'".  $_POST['approvedby'] ."',
						'" . $PaymentItem->tag . "',
						'".$vn."'
						)";
					$ErrMsg = _('Cannot insert a GL entry for the payment using the SQL');
					$result = DB_query($SQL,$db,$ErrMsg,_('The SQL that failed was'),true);

					$TotalAmount += $PaymentItem->Amount;
				}
				$_SESSION['PaymentDetail']->Amount = $TotalAmount;
				$_SESSION['PaymentDetail']->Discount=0;
				
				
			}

			//Run through the GL postings to check to see if there is a posting to another bank account (or the same one) if there is then a receipt needs to be created for this account too

			foreach ($_SESSION['PaymentDetail']->GLItems as $PaymentItem) {

				if (in_array($PaymentItem->GLCode, $BankAccounts)) {

					/*Need to deal with the case where the payment from one bank account could be to a bank account in another currency */

					/*Get the currency and rate of the bank account transferring to*/
					$SQL = "SELECT currcode, rate
							FROM bankaccounts INNER JOIN currencies
							ON bankaccounts.currcode = currencies.currabrev
							WHERE accountcode='" . $PaymentItem->GLCode . "'";
					$TrfToAccountResult = DB_query($SQL,$db);
					$TrfToBankRow = DB_fetch_array($TrfToAccountResult) ;
					$TrfToBankCurrCode = $TrfToBankRow['currcode'];
					$TrfToBankExRate = $TrfToBankRow['rate'];

					if ($_SESSION['PaymentDetail']->AccountCurrency == $TrfToBankCurrCode){
					/*Make sure to use the same rate if the transfer is between two bank accounts in the same currency */
						$TrfToBankExRate = $_SESSION['PaymentDetail']->FunctionalExRate;
					}

					/*Consider an example
					 functional currency NZD
					 bank account in AUD - 1 NZD = 0.90 AUD (FunctionalExRate)
					 paying USD - 1 AUD = 0.85 USD  (ExRate)
					 to a bank account in EUR - 1 NZD = 0.52 EUR

					 oh yeah - now we are getting tricky!
					 Lets say we pay USD 100 from the AUD bank account to the EUR bank account

					 To get the ExRate for the bank account we are transferring money to
					 we need to use the cross rate between the NZD-AUD/NZD-EUR
					 and apply this to the

					 the payment record will read
					 exrate = 0.85 (1 AUD = USD 0.85)
					 amount = 100 (USD)
					 functionalexrate = 0.90 (1 NZD = AUD 0.90)

					 the receipt record will read

					 amount 100 (USD)
					 exrate    (1 EUR =  (0.85 x 0.90)/0.52 USD)
					 					(ExRate x FunctionalExRate) / USD Functional ExRate
					 functionalexrate =     (1NZD = EUR 0.52)

				*/

					$ReceiptTransNo = GetNextTransNo( 2, $db);
					$SQL= "INSERT INTO banktrans (transno,
													type,
													bankact,
													ref,
													exrate,
													functionalexrate,
													transdate,
													banktranstype,
													amount,
													preparedby,
													approvedby,
													currcode,
													voucher_no)
						VALUES ('" . $ReceiptTransNo . "',
							2,
							'" . $PaymentItem->GLCode . "',
							'" . _('Act Transfer From ') . $_SESSION['PaymentDetail']->Account . ' - ' . $PaymentItem->Narrative . "',
							'" . (($_SESSION['PaymentDetail']->ExRate * $_SESSION['PaymentDetail']->FunctionalExRate)/$TrfToBankExRate). "',
							'" . $TrfToBankExRate . "',
							'" . FormatDateForSQL($_SESSION['PaymentDetail']->DatePaid) . "',
							'" . $_SESSION['paytype'] . "',
							'" . $PaymentItem->Amount . "',
							'" . $_SESSION['UsersRealName'] . "',
							'" . $_POST['approvedby'] . "',
							'" . $_SESSION['PaymentDetail']->Currency . "',
							'" . $vn . "'
						)";
					$ErrMsg = _('Cannot insert a bank transaction because');
					$DbgMsg =  _('Cannot insert a bank transaction with the SQL');
					$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

				}
			} 
			
			if( isset($_SESSION['clid']) && $_SESSION['type']=='tour')
	{
	   $vg="update tour_claim set voucher_generated='1' where id='".$_SESSION['clid']."'";
	  $vgq=DB_query($vg,$db);
	  
	}
	else if( isset($_SESSION['clid']) && $_SESSION['type']=='medical')
	{
	   $vg="update medical_claim set voucher_generated='1' where id='".$_SESSION['clid']."'";
	  $vgq=DB_query($vg,$db);
	  
	}
		} else {
	  /*Its a supplier payment type 22 */
			$CreditorTotal = ($_SESSION['PaymentDetail']->Amount);

			$TransNo = GetNextTransNo(22, $db);
			$Transtype = 22;

			/* Create a SuppTrans entry for the supplier payment */
			$SQL = "INSERT INTO supptrans (transno,
											type,
											supplierno,
											trandate,
											inputdate,
											suppreference,
											rate,
											ovamount,
											transtext) ";
			$SQL = $SQL . "valueS ('" . $TransNo . "',
					22,
					'" . $_SESSION['PaymentDetail']->SupplierID . "',
					'" . $_SESSION['PaymentDetail']->DatePaid . "',
					'" . date('Y-m-d H-i-s') . "',
					'" . $_SESSION['PaymentDetail']->Paymenttype . "',
					'" . ($_SESSION['PaymentDetail']->ExRate/$_SESSION['PaymentDetail']->FunctionalExRate) . "',
					'" . (-$_SESSION['PaymentDetail']->Amount-$_SESSION['PaymentDetail']->Discount) . "',
					'" . $_SESSION['PaymentDetail']->Narrative . "'
				)";

			$ErrMsg =  _('Cannot insert a payment transaction against the supplier because');
			$DbgMsg = _('Cannot insert a payment transaction against the supplier using the SQL');
			$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

			/*Update the supplier master with the date and amount of the last payment made */
			$SQL = "UPDATE suppliers 
					SET	lastpaiddate = '" . $_SESSION['PaymentDetail']->DatePaid . "',
						lastpaid='" . $_SESSION['PaymentDetail']->Amount ."'
					WHERE suppliers.supplierid='" . $_SESSION['PaymentDetail']->SupplierID . "'";



			$ErrMsg = _('Cannot update the supplier record for the date of the last payment made because');
			$DbgMsg = _('Cannot update the supplier record for the date of the last payment made using the SQL');
			$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

			$_SESSION['PaymentDetail']->Narrative = $_SESSION['PaymentDetail']->SupplierID . "-" . $_SESSION['PaymentDetail']->Narrative;

			if ($_SESSION['CompanyRecord']['gllink_creditors']==1){ /* then do the supplier control GLTrans */
			/* Now debit creditors account with payment + discount */

				 $SQL="INSERT INTO gltrans ( type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											amount) ";
				$SQL=$SQL . "VALUES (22,
								'" . $TransNo . "',
								'" . FormatDateForSQL($_SESSION['PaymentDetail']->DatePaid) . "',
								'" . $PeriodNo . "',
								'" . $_SESSION['CompanyRecord']['creditorsact'] . "',
								'" . $_SESSION['PaymentDetail']->Narrative . "',
								'" . $CreditorTotal . "')";
				$ErrMsg = _('Cannot insert a GL transaction for the creditors account debit because');
				$DbgMsg = _('Cannot insert a GL transaction for the creditors account debit using the SQL');
				$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

				if ($_SESSION['PaymentDetail']->Discount !=0){
					/* Now credit Discount received account with discounts */
					$SQL="INSERT INTO gltrans ( type,
												typeno,
												trandate,
												periodno,
												account,
												narrative,
												amount) ";
							$SQL=$SQL . "VALUES (22,
										'" . $TransNo . "',
										'" . FormatDateForSQL($_SESSION['PaymentDetail']->DatePaid) . "',
										'" . $PeriodNo . "',
										'" . $_SESSION['CompanyRecord']['pytdiscountact'] . "',
										'" . $_SESSION['PaymentDetail']->Narrative . "',
										'" . (-$_SESSION['PaymentDetail']->Discount/$_SESSION['PaymentDetail']->ExRate/$_SESSION['PaymentDetail']->FunctionalExRate) . "'
					  )";
					$ErrMsg = _('Cannot insert a GL transaction for the payment discount credit because');
					$DbgMsg = _('Cannot insert a GL transaction for the payment discount credit using the SQL');
					$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
				} // end if discount
			} // end if gl creditors
		} // end if supplier

		if ($_SESSION['CompanyRecord']['gllink_creditors']==1){ /* then do the common GLTrans */

			if ($_SESSION['PaymentDetail']->Amount !=0){
				/* Bank account entry first */
				$SQL = "INSERT INTO gltrans ( type,
											typeno,
											trandate,
											periodno,
											account,
											narrative,
											preparedby,
											approvedby,
											amount) ";
					$SQL = $SQL . "VALUES ('" . $Transtype . "',
							'" . $TransNo . "',
							'" . FormatDateForSQL($_SESSION['PaymentDetail']->DatePaid) . "',
							'" . $PeriodNo . "',
							'" . $_SESSION['PaymentDetail']->Account . "',
							'" . $_SESSION['PaymentDetail']->Narrative . "',
							'" . $_SESSION['UsersRealName'] . "',
							'" . $_POST['approvedby'] . "',
							'" . (-$_SESSION['PaymentDetail']->Amount) . "')";

				$ErrMsg =  _('Cannot insert a GL transaction for the bank account credit because');
				$DbgMsg =  _('Cannot insert a GL transaction for the bank account credit using the SQL');
				$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

			}
		}

		/*now enter the BankTrans entry */
		if ($Transtype==22) {
			$SQL="INSERT INTO banktrans (transno,
										type,
										bankact,
										ref,
										exrate,
										functionalexrate,
										transdate,
										banktranstype,
										amount,
										preparedby,
										approvedby,
										currcode) ";
						$SQL= $SQL . "VALUES ('" . $TransNo . "',
									'" . $Transtype . "',
									'" . $_SESSION['PaymentDetail']->Account . "',
									'" . $_SESSION['PaymentDetail']->Narrative . "',
									'" . $_SESSION['PaymentDetail']->ExRate . "',
									'" . $_SESSION['PaymentDetail']->FunctionalExRate . "',
									'" . FormatDateForSQL($_SESSION['PaymentDetail']->DatePaid) . "',
									'" . $_SESSION['paytype'] . "',
									'" . -$_SESSION['PaymentDetail']->Amount . "',
									'" . $_SESSION['UsersRealName']. "',
									'" . $_POST['approvedby'] . "',
									'" . $_SESSION['PaymentDetail']->Currency . "'
								)";

			$ErrMsg = _('Cannot insert a bank transaction because');
			$DbgMsg = _('Cannot insert a bank transaction using the SQL');
			$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
		} else {
			foreach ($_SESSION['PaymentDetail']->GLItems as $PaymentItem) {
				$SQL="INSERT INTO banktrans (transno,
											type,
											bankact,
											ref,
											exrate,
											functionalexrate,
											transdate,
											banktranstype,
											amount,
											preparedby,
											approvedby,
											currcode) ";
				$SQL= $SQL . "VALUES ('" . $TransNo . "',
					'" . $Transtype . "',
					'" . $_SESSION['PaymentDetail']->Account . "',
					'" . $_SESSION['PaymentDetail']->Narrative . "',
					'" . $_SESSION['PaymentDetail']->ExRate . "',
					'" . $_SESSION['PaymentDetail']->FunctionalExRate . "',
					'" . FormatDateForSQL($_SESSION['PaymentDetail']->DatePaid) . "',
					'" . $_SESSION['paytype'] . "',
					'" . -$PaymentItem->Amount . "',
					'" . $_SESSION['UsersRealName'] . "',
					'" . $_POST['approvedby'] . "',
					'" . $_SESSION['PaymentDetail']->Currency . "'
				)";

				$ErrMsg = _('Cannot insert a bank transaction because');
				$DbgMsg = _('Cannot insert a bank transaction using the SQL');
				$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
			}
		}

		DB_Txn_Commit($db);
		prnMsg(_('Payment') . ' ' . $TransNo . ' ' . _('has been successfully entered'),'success');

		$LastSupplier = ($_SESSION['PaymentDetail']->SupplierID);
       
	    if($_POST['BankAccount'])
	    { $bt="select type from bankaccounts where accountcode='".$_POST['BankAccount']."'";
	    $btq=DB_query($bt,$db);
		$btr=DB_fetch_array($btq);
		if($btr['type']=='Cash')
		{
	    include ('includes/GLPostings.inc');
		}
	  }
	   
		unset($_POST['BankAccount']);
		unset($_POST['DatePaid']);
		unset($_POST['ExRate']);
		unset($_POST['Paymenttype']);
		unset($_POST['Currency']);
		unset($_POST['Narrative']);
		unset($_POST['Amount']);
		unset($_POST['Discount']);
		unset($_POST['GLGroup']);
		unset($_SESSION['PaymentDetail']->GLItems);
		unset($_SESSION['PaymentDetail']);

		/*Set up a newy in case user wishes to enter another */
		if (isset($LastSupplier) and $LastSupplier!='') {
			$sql="SELECT suppname FROM suppliers 
					WHERE supplierid='".$LastSupplier."'";
			$result=DB_query($sql, $db);
			$myrow=DB_fetch_array($result);
			echo '<br /><a href="' . $rootpath . '/Payments.php?SupplierID=' . $LastSupplier . '">' .
				_('Enter another Payment for') . ' ' . $myrow['suppname'] . '</a>';
		} else {
			echo '<br /><a href="' . $_SERVER['PHP_SELF'] .'">' . _('Enter another General Ledger Payment') . '</a><br />';
		}
	}
$vouchno=explode("-",$vn);
	$voucherno=$vouchno[1];
      $vounumber=$voucherno+1;
	  chmod("voucher.txt", 0777);
	  $file1=fopen("voucher.txt","w");
	  fwrite($file1,"sc-".$vounumber);
	  fclose($file1);
	  
	 
	//include('includes/footer.inc');
	//exit;

} elseif (isset($_GET['Delete'])){
  /* User hit delete the receipt entry from the batch */
	$_SESSION['PaymentDetail']->Remove_GLItem($_GET['Delete']);
} elseif (isset($_POST['Process']) AND !$BankAccountEmpty){ //user hit submit a new GL Analysis line into the payment

  

  /* if ( $_POST['cheque']==''){
		prnMsg( _('The Voucher number must be entered'),'error');
	}*/
	 if ($_POST['GLAmount'] == '') {
			prnMsg( _('Enter Amount') ,'warn');
	}  
	 $ChequeNoSQL="SELECT account FROM gltrans WHERE chequeno='" . $_POST['cheque'] ."'";
	$ChequeNoResult=DB_query($ChequeNoSQL, $db);
   
	if (is_numeric($_POST['GLManualCode'])){

		$SQL = "SELECT accountname
				FROM chartmaster
				WHERE accountcode='" . $_POST['GLManualCode'] . "'";

		$Result=DB_query($SQL,$db);

		if (DB_num_rows($Result)==0){
			prnMsg( _('The manual GL code entered does not exist in the database') . ' - ' . _('so this GL analysis item could not be added'),'warn');
			unset($_POST['GLManualCode']);
		} else if (DB_num_rows($ChequeNoResult)!=0 AND $_POST['cheque']!=''){
			prnMsg( _('The Cheque/Voucher number has already been used') . ' - ' . _('This GL analysis item could not be added'),'error');
		} else {
			$myrow = DB_fetch_array($Result);

			$_SESSION['PaymentDetail']->add_to_glanalysis($_POST['GLAmount'],
															$_POST['GLNarrative'],
															$_POST['GLManualCode'],
															$myrow['accountname'],
															$_POST['tag'],
															$_POST['cheque']);
			unset($_POST['GLManualCode']);
			unset($_POST['cheque']);
		}
	} else if (DB_num_rows($ChequeNoResult)!=0 AND $_POST['cheque']!=''){
		prnMsg( _('The cheque number has already been used') . ' - ' . _('This GL analysis item could not be added'),'error');
	} else if ($_POST['GLCode'] == '') {
			prnMsg( _('No General Ledger code has been chosen') . ' - ' . _('so this GL analysis item could not be added'),'warn');
	}
	else {
		$SQL = "SELECT accountname FROM chartmaster WHERE accountcode='" . $_POST['GLCode'] . "'";
		$Result=DB_query($SQL,$db);
		$myrow=DB_fetch_array($Result);
		$_SESSION['PaymentDetail']->add_to_glanalysis($_POST['GLAmount'],
														$_POST['GLNarrative'],
														$_POST['GLCode'],
														$myrow['accountname'],
														$_POST['tag'],
														$_POST['cheque']);
														
								unset($_POST['cheque']);						
														
	}

	/*Make sure the same receipt is not double processed by a page refresh */
	$_POST['Cancel'] = 1;
}

if (isset($_POST['Cancel'])){
	unset($_POST['GLAmount']);
	unset($_POST['GLNarrative']);
	unset($_POST['GLCode']);
	unset($_POST['AccountName']);
	unset($_POST['GLManualCode']);
	unset($_POST['GLGroup']);
}

/*set up the form whatever */
if (!isset($_POST['DatePaid'])) {
	$_POST['DatePaid'] = '';
}

if (isset($_POST['DatePaid']) AND ($_POST['DatePaid']!='' )){
	$_POST['DatePaid']= Date($_SESSION['DefaultDateFormat']);
	$_SESSION['PaymentDetail']->DatePaid = $_POST['DatePaid'];
}

if ($_SESSION['PaymentDetail']->Currency=='' AND $_SESSION['PaymentDetail']->SupplierID==''){
	$_SESSION['PaymentDetail']->Currency=$_SESSION['CompanyRecord']['currencydefault'];
}


if (isset($_POST['BankAccount']) AND $_POST['BankAccount']!='') {
  $bt="select type from bankaccounts where accountcode='".$_POST['BankAccount']."'";
$btq=DB_query($bt,$db);
$btr=DB_fetch_array($btq);
   if($btr['type']=='Cash')
   {
     $_SESSION['paytype']='Cash';
   }
   else if($btr['type']=='Cheque')
   {
     $_SESSION['paytype']='Cheque';
   }
	$SQL = "SELECT bankaccountname
			FROM bankaccounts,
				chartmaster
			WHERE bankaccounts.accountcode= chartmaster.accountcode
			AND chartmaster.accountcode='" . $_POST['BankAccount'] . "'";

	$ErrMsg = _('The bank account name cannot be retrieved because');
	$DbgMsg = _('SQL used to retrieve the bank account name was');

	$result= DB_query($SQL,$db,$ErrMsg,$DbgMsg);

	if (DB_num_rows($result)==1){
		$myrow = DB_fetch_row($result);
		$_SESSION['PaymentDetail']->BankAccountName = $myrow[0];
		unset($result);
	} elseif (DB_num_rows($result)==0){
		prnMsg( _('The bank account number') . ' ' . $_POST['BankAccount'] . ' ' . _('is not set up as a bank account with a valid general ledger account'),'error');
	}
}


echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Payment Entry</a></div><form action="' . $_SERVER['PHP_SELF'] . '" method=post>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<p><table " cellspacing="1" cellpadding="2">';

echo '<tr class="oddrow"><td colspan="4" align="center"><h2>' . _('Payment');

if ($_SESSION['PaymentDetail']->SupplierID!=""){
	echo ' ' . _('to') . ' ' . $_SESSION['PaymentDetail']->SuppName;
}

if ($_SESSION['PaymentDetail']->BankAccountName!=""){
	//echo ' ' . _('from the') . ' ' . $_SESSION['PaymentDetail']->BankAccountName;
}

echo '</td></tr>';

$SQL = "SELECT bankaccountname,
				bankaccounts.accountcode,
				bankaccounts.currcode
		FROM bankaccounts,
		chartmaster
		WHERE bankaccounts.accountcode=chartmaster.accountcode";

$ErrMsg = _('The bank accounts could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve the bank accounts was');
$AccountsResults = DB_query($SQL,$db,$ErrMsg,$DbgMsg);

echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Cash/Bank Account') . ': <span style="color:#FF0000">*</span></div>
		<div class="right"><select name="BankAccount" onChange="chequeno(this.value)">';

if (DB_num_rows($AccountsResults)==0){
	echo '</select></div></td></tr><p>';
	prnMsg( _('Bank Accounts have not yet been defined. You must first') . ' <a href="' . $rootpath . '/BankAccounts.php">' . _('define the bank accounts') . '</a> ' . _('and general ledger accounts to be affected'),'warn');
	include('includes/footer.inc');
	exit;
} else {
	echo '<option value="">--Select--</option>';
	while ($myrow=DB_fetch_array($AccountsResults)){
	/*list the bank account names */
		if (isset($_POST['BankAccount']) AND $_POST['BankAccount']==$myrow['accountcode']){
			echo '<option selected value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . '</option>';
		} else {
			echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['bankaccountname'] . '</option>';
		}
	}
	echo '</select></td></tr>';
}

if(!isset($_SESSION['PaymentDetail']->DatePaid))
{
$_SESSION['PaymentDetail']->DatePaid=date('d-m-Y');
}
echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Date') . ': <span style="color:#FF0000">*</span></div>
	<div class="right"><input type="text" name="DatePaid" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" value="' . $_SESSION['PaymentDetail']->DatePaid . '"></div><input type="hidden" name="Currency" value="INR"></td>
	</tr>';


if ($_SESSION['PaymentDetail']->SupplierID==''){
	/*echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Currency of Payment') . ':</div>
			<div class="right" style="display:none;"><select name="Currency" onChange="ReloadForm(UpdateHeader)">';
	$SQL = "SELECT currency, currabrev, rate FROM currencies where currabrev='INR'";
	$result=DB_query($SQL,$db);

	if (DB_num_rows($result)==0){
		echo '</select></td></tr>';
		prnMsg( _('No currencies are defined yet. Payments cannot be entered until a currency is defined'),'error');
	} else {
		while ($myrow=DB_fetch_array($result)){
			if ($_SESSION['PaymentDetail']->Currency==$myrow['currabrev']){
				echo '<option selected value=' . $myrow['currabrev'] . '>' . $myrow['currency'] . '</option>';
			} else {
				echo '<option value=' . $myrow['currabrev'] . '>' . $myrow['currency'] . '</option>';
			}
		}
		echo '</select></div><i>' . _('The transaction currency does not need to be the same as the bank account currency') . '</i></td></tr>';
	}*/
} else { /*its a supplier payment so it must be in the suppliers currency */
	echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Supplier Currency') . ': <span style="color:#FF0000">*</span></div><div class="right">' . $_SESSION['PaymentDetail']->Currency . '</div></td></tr>';
	echo '<input type="hidden" name="Currency" value="' . $_SESSION['PaymentDetail']->Currency . '">';
	/*get the default rate from the currency table if it has not been set */
	if (!isset($_POST['ExRate']) OR $_POST['ExRate']==''){
		$SQL = "SELECT rate FROM currencies WHERE currabrev='" . $_SESSION['PaymentDetail']->Currency ."'";
		$Result=DB_query($SQL,$db);
		$myrow=DB_fetch_row($Result);
		$_POST['ExRate']=$myrow[0];
	}

}

if (!isset($_POST['ExRate'])){
	$_POST['ExRate']=1;
}

if (!isset($_POST['FunctionalExRate'])){
	$_POST['FunctionalExRate']=1;
}
if ($_SESSION['PaymentDetail']->AccountCurrency!=$_SESSION['PaymentDetail']->Currency AND isset($_SESSION['PaymentDetail']->AccountCurrency)){
	if (isset($SuggestedExRate)){
		$SuggestedExRateText = '<b>' . _('Suggested rate:') . ' ' . number_format($SuggestedExRate,4) . '</b>';
	} else {
		$SuggestedExRateText ='';
	}
	if ($_POST['ExRate']==1 AND isset($SuggestedExRate)){
		$_POST['ExRate'] = $SuggestedExRate;
	}
	echo '<input class=number type="hidden" name="ExRate" maxlength=10 size=12 value="1"><input type="hidden" name="FunctionalExRate" maxlength=10 size=12 value="' . $_POST['FunctionalExRate'] . '" />';
}

if ($_SESSION['PaymentDetail']->AccountCurrency!=$_SESSION['CompanyRecord']['currencydefault']
										AND isset($_SESSION['PaymentDetail']->AccountCurrency)){
	if (isset($SuggestedFunctionalExRate)){
		$SuggestedFunctionalExRateText = '<b>' . _('Suggested rate:') . ' ' . number_format($SuggestedFunctionalExRate,4) . '</b>';
	} else {
		$SuggestedFunctionalExRateText ='';
	}
	if ($_POST['FunctionalExRate']==1 AND isset($SuggestedFunctionalExRate)){
		$_POST['FunctionalExRate'] = $SuggestedFunctionalExRate;
	}
	/*echo '<tr><td>' . _('Functional Exchange Rate') . ': <span style="color:#FF0000">*</span></td>
			<td><input type="text" name="FunctionalExRate" maxlength=10 size=12 value="' . $_POST['FunctionalExRate'] . '" /></td>
			<td>' . ' ' . $SuggestedFunctionalExRateText . ' <i>' . _('The exchange rate between the currency of the business (the functional currency) and the currency of the bank account') .  '. 1 ' . $_SESSION['CompanyRecord']['currencydefault'] . ' = ? ' . $_SESSION['PaymentDetail']->AccountCurrency . '</i></td></tr>';*/
}
//echo '<tr><td>' . _('Payment type') . ':</td>
	//	<td><select name="Paymenttype">';

include('includes/GetPaymentMethods.php');
/* The array Payttypes is set up in includes/GetPaymentMethods.php
payment methods can be modified from the setup tab of the main menu under payment methods*/

//foreach ($PaytTypes as $PaytType) {

	//if (isset($_POST['Paymenttype']) AND $_POST['Paymenttype']==$PaytType){
		//echo '<option selected value="' . $PaytType . '">' . $PaytType . '</option>';
	//} else {
		//echo '<option value="' . $PaytType . '">' . $PaytType . '</option>';
//	}
//} //end foreach
//echo '</select></td></tr>';

if (!isset($_POST['cheque'])) {
	$_POST['cheque']='';
}

echo '<tr class="evenrow" id="cheq"'; if($_POST['BankAccount']!=1030) { echo'style="display:none;"';} echo '><td colspan="2">  <div class="left">' . _('Cheque Number') . ': <span style="color:#FF0000">*</span></div>
<div class="right"><input type="text" class="number" name="cheque" maxlength="6" size="10" value="' . $_POST['cheque'] . '" /> </div>' . _('') . '</td>
	</tr>';

if (!isset($_POST['Narrative'])) {
	$_POST['Narrative']='';
}

echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Reference / Narrative') . ': </div>
		<div class="right"><input type="text" name="Narrative" maxlength=200 size=82 value="' . $_POST['Narrative'] . '"onkeypress="return alphanumeric(event)" /></div></td>
		</tr>';
		
		echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Prepared By') . ': <span style="color:#FF0000">*</span></div>
		<div class="right"><input type="text" name="preparedby" maxlength=45 size=82 value="' . $_SESSION['UsersRealName'] . '" readonly />  </div></td>
		</tr>';
		echo '<tr class="evenrow"><td colspan="2"> <div class="left">'. _('Approved By').': <span style="color:#FF0000">*</span></div><div class="right"><select name="approvedby">';
					
					if (isset($_POST['approvedby'])){
					
					if( $_POST['approvedby']=='accountant')
					{
					
			echo '<option value="none">None</option>
			<option value="accountant" selected>Accountant</option>
			<option value="cao">C.A.O.</option>
			<option value="gm">G.M.</option>
			<option value="md">M.D.</option>';
			   
		} else if($_POST['approvedby']=='cao') {
			echo '<option value="none">None</option>
			<option value="accountant" >Accountant</option>
			<option value="cao" selected>C.A.O.</option>
			<option value="gm">G.M.</option>
			<option value="md">M.D.</option>';
		}
		else if($_POST['approvedby']=='gm') {
			echo '<option value="none">None</option>
			<option value="accountant" >Accountant</option>
			<option value="cao" >C.A.O.</option>
			<option value="gm" selected>G.M.</option>
			<option value="md">M.D.</option>';
		}
		else if($_POST['approvedby']=='md') {
			echo '<option value="none">None</option>
			<option value="accountant" >Accountant</option>
			<option value="cao" >C.A.O.</option>
			<option value="gm" >G.M.</option>
			<option value="md" selected>M.D.</option>';
		}
		else if($_POST['approvedby']=='none') {
			echo '<option value="none" selected>None</option>
			<option value="accountant" >Accountant</option>
			<option value="cao" >C.A.O.</option>
			<option value="gm" >G.M.</option>
			<option value="md" >M.D.</option>';
		}
			}	else {
			echo '<option value="none">None</option>
			<option value="accountant" >Accountant</option>
			<option value="cao">C.A.O.</option>
			<option value="gm">G.M.</option>
			<option value="md">M.D.</option>';
			}
				  echo '</select></div></td></tr>';
/*echo '<tr class="evenrow"><td align="center"  colspan="2"><input type="submit" name="UpdateHeader" value="' . _('Update'). '" /></td></tr>';*/


echo '</table><br />';


if ($_SESSION['CompanyRecord']['gllink_creditors']==1 AND $_SESSION['PaymentDetail']->SupplierID==''){
/* Set upthe form for the transaction entry for a GL Payment Analysis item */

	echo '<table>';
	echo '<tr class="oddrow"><td colspan="2" align="center"><h2>' . _('General Ledger Payment Analysis Entry') . '</h2></td></tr>';

	//Select the tag
	// End select tag

	/*now set up a GLCode field to select from avaialble GL accounts */
	if (isset($_POST['GLManualCode'])) {
		echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Enter GL Account Manually') . ': <span style="color:#FF0000">*</span></div>
			<div class="right"><input type=Text class="number" Name="GLManualCode" maxlength=12 size=12 onChange="return assignComboToInput(this,'.'GLCode'.')" value='. $_POST['GLManualCode'] .'  ></div></td></tr>';
	} else {
		echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Enter GL Account Manually') . ': <span style="color:#FF0000">*</span></div>
			<div class="right"><input type=Text class="number" Name="GLManualCode" Maxlength=12 size=12 onChange="return assignComboToInput(this,'.'GLCode'.')"></div></tr>';
	}
	
	echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Select GL Group') . ': </div>
		<div class="right"><select name="GLGroup" >';

	$SQL = "SELECT groupname
			FROM accountgroups
			ORDER BY sequenceintb";

	$result=DB_query($SQL,$db);
	if (DB_num_rows($result)==0){
		echo '</select></td></tr>';
		prnMsg(_('No General ledger account groups have been set up yet') . ' - ' . _('payments cannot be analysed against GL accounts until the GL accounts are set up'),'error');
	} else {
		echo '<option value="">--Select--</option>';
		while ($myrow=DB_fetch_array($result)){
			if (isset($_POST['GLGroup']) AND ($_POST['GLGroup']==$myrow['groupname'])){
				echo '<option selected value="' . $myrow['groupname'] . '">' . $myrow['groupname'] . '</option>';
			} else {
				echo '<option value="' . $myrow['groupname'] . '">' . $myrow['groupname'] . '</option>';
			}
		}
		echo '</select></div><input type="submit" name="UpdateCodes" value="Select" /></td></tr>';
	}

	if (isset($_POST['GLGroup']) AND $_POST['GLGroup']!='') {
		$SQL = "SELECT accountcode,
						accountname
				FROM chartmaster
				WHERE group_='".$_POST['GLGroup']."'
				ORDER BY accountcode";
	} else {
		$SQL = "SELECT accountcode,
						accountname
				FROM chartmaster
				ORDER BY accountcode";
	}
	
	
	echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Select GL Account') . ': <span style="color:#FF0000">*</span></div>
		<div class="right"><select name="GLCode" onChange="return assignComboToInput(this,'.'GLManualCode'.')">';

	$result=DB_query($SQL,$db);
	if (DB_num_rows($result)==0){
		echo '</select></div></td></tr>';
		prnMsg(_('No General ledger accounts have been set up yet') . ' - ' . _('payments cannot be analysed against GL accounts until the GL accounts are set up'),'error');
	} else {
		echo '<option value="">--Select--</option>';
		while ($myrow=DB_fetch_array($result)){
			if (isset($_POST['GLCode']) AND $_POST['GLCode']==$myrow['accountcode']){
				echo '<option selected value=' . $myrow['accountcode'] . '>' . $myrow['accountcode'] . ' - ' . $myrow['accountname'] . '</option>';
			} else {
				echo '<option value=' . $myrow['accountcode'] . '>' . $myrow['accountcode'] . ' - ' . $myrow['accountname'] . '</option>';
			}
		}
		echo '</select></div></td></tr>';
	}

	/*echo '<tr class="evenrow"><td>'. _('Cheque/Voucher Number') .'</td>
			<td><input type="text" name="cheque" maxlength="12" size="12"></td>
		</tr>';*/
	

	if (isset($_POST['GLNarrative'])) {
		echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('GL Narrative') . ': <span style="color:#FF0000">*</span></div>
				<div class="right"><input type="text" name="GLNarrative" maxlength="200" size="52" value="' . $_POST['GLNarrative'] . '" onkeypress="return alphanumeric(event)"/>
				</div></td>
			</tr>';
	} else {
		echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('GL Narrative') . ': <span style="color:#FF0000">*</span></div>
				<div class="right"><input type="text" name="GLNarrative" maxlength="200" size="52" onkeypress="return alphanumeric(event)"/></div></td>
			</tr>';
	}

	if (isset($_POST['GLAmount'])) {
		//echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Amount') . ' (' . $_SESSION['PaymentDetail']->Currency . '):</div>';
		
		 echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Amount') . ' ( INR ): <span style="color:#FF0000">*</span></div>
				<div class="right"><input type="text" name="GLAmount" maxlength="11" size="12" class="number" value=' . $_POST['GLAmount'] . '></div></td>
			</tr>';
	} else {
		//echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Amount') . ' (' . $_SESSION['PaymentDetail']->Currency . '):</div>
		echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Amount') . ' (INR): <span style="color:#FF0000">*</span></div>
				<div class="right"><input type="text" name="GLAmount" Maxlength="12" size="12" class="number"></div>
			</tr>';
	}
	

	echo '
	<tr class="oddrow"><td colspan="2" align="center">
	<input type=submit name="Process" value="' . _('Accept') . '" />
			<input type=submit name="Cancel" value="' . _('Cancel') . '" />
	</td></tr>
	
	</table><br />';

	if (sizeOf($_SESSION['PaymentDetail']->GLItems)>0) {
		echo '<br />
			<table class="selection">
			<tr>
				<th>' . _('Cheque No').'</th>
				<th>' . _('Amount') . ' (' . $_SESSION['PaymentDetail']->Currency . ')</th>
				<th>' . _('GL Account') . '</th>
				<th>' . _('Narrative') . '</th>
				
				<th>' . _('Action') . '</th>
			</tr>';

		$PaymentTotal = 0;
		foreach ($_SESSION['PaymentDetail']->GLItems as $PaymentItem) {
			$tagsql="SELECT tagdescription from tags where tagref='" . $PaymentItem->tag . "'";
			$TagResult=DB_query($tagsql, $db);
			$TagMyrow=DB_fetch_row($TagResult);
			if ($PaymentItem->tag==0) {
				$TagName='None';
			} else {
				$TagName=$TagMyrow[0];
			}
			//print_r($_SESSION['PaymentDetail']->GLItems);
			echo '<tr class="odd">
				<td align=left>' . $PaymentItem->cheque . '</td>
				<td class=number>' . number_format($PaymentItem->Amount,$_SESSION['PaymentDetail']->CurrDecimalPlaces) . '</td>
				<td>' . $PaymentItem->GLCode . ' - ' . $PaymentItem->GLActName . '</td>
				<td>' . stripslashes($PaymentItem->Narrative)  . '</td>
				
				<td><a href="' . $_SERVER['PHP_SELF'] . '?Delete=' . $PaymentItem->ID . '" onclick="return confirm(\'' . _('Are you sure you wish to delete this payment analysis item?') . '\');">' . _('Delete') . '</a></td>
				</tr>';
			$PaymentTotal += $PaymentItem->Amount;
		}
		echo '<tr class="total"><td style="border:none;"></td><td class="number" colspan="4"><b>' . number_format($PaymentTotal,$_SESSION['PaymentDetail']->CurrDecimalPlaces) . '</b></td></tr></table><br />';
		echo '<div class="center" align="center"><input type=submit name="CommitBatch" value="' . _('Accept and Process Payment') . '"></div>';
	}

} else {
/*a supplier is selected or the GL link is not active then set out
the fields for entry of receipt amt and disc */

	echo '<table class=selection><tr><td>' . _('Amount of Payment') . ' ' . $_SESSION['PaymentDetail']->Currency . ': <span style="color:#FF0000">*</span></td>
				<td><input class=number type="text" name="Amount" maxlength=12 size=13 value=' . $_SESSION['PaymentDetail']->Amount . '></td></tr>';

	if (isset($_SESSION['PaymentDetail']->SupplierID)){ /*So it is a supplier payment so show the discount entry item */
		echo '<tr><td>' . _('Amount of Discount') . ': <span style="color:#FF0000">*</span></td>
					<td><input class=number type="text" name="Discount" maxlength=12 size=13 value="' . $_SESSION['PaymentDetail']->Discount . '" /></td></tr>';
		echo '<input type="hidden" name="SuppName" value="' . $_SESSION['PaymentDetail']->SuppName . '" />';
	} else {
		echo '<input type="hidden" name="discount" Value="0" />';
	}
	echo '</table><br />';
	echo '<div class="center" align="center"><input type=submit name="CommitBatch" value="' . _('Accept and Process Payment') . '" /></div>';
}
echo '</form>';

include('includes/footer.inc');
?>
<script>
function chequeno(a)
{ 
 //alert(a);
   if(a==1030)
   {
    document.getElementById('cheq').style.display='';
	}

else if(a!=1030)
{
  document.getElementById('cheq').style.display='none';
}
}
</script>