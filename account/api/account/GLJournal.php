<?php

/* $Id: GLJournal.php 4565 2011-05-13 10:50:42Z daintree $*/

include('includes/DefineJournalClass.php');

include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');


if(isset($_GET['Debit']))
{
  $_POST['Debit']=$_GET['Debit'];
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

if (isset($_GET['NewJournal']) and $_GET['NewJournal'] == 'Yes' AND isset($_SESSION['JournalDetail'])){
	unset($_SESSION['JournalDetail']->GLEntries);
	unset($_SESSION['JournalDetail']);
}

if (!isset($_SESSION['JournalDetail'])){
	$_SESSION['JournalDetail'] = new Journal;

	/* Make an array of the defined bank accounts - better to make it now than do it each time a line is added
	Journals cannot be entered against bank accounts GL postings involving bank accounts must be done using
	a receipt or a payment transaction to ensure a bank trans is available for matching off vs statements */

	$SQL = "SELECT accountcode FROM bankaccounts";
	$result = DB_query($SQL,$db);
	$i=0;
	while ($Act = DB_fetch_row($result)){
		$_SESSION['JournalDetail']->BankAccounts[$i]= $Act[0];
		$i++;
	}

}




if (isset($_POST['JournalProcessDate'])){
	$_SESSION['JournalDetail']->JnlDate=$_POST['JournalProcessDate'];
//echo $_POST['JournalProcessDate'];
	if (!Is_Date($_POST['JournalProcessDate'])){
		prnMsg(_('The date entered was not valid please enter the date to process the journal in the format '). $_SESSION['DefaultDateFormat'],'warn');
		$_POST['CommitBatch']='Do not do it the date is wrong';
	}
}
if (isset($_POST['JournalType'])){
	$_SESSION['JournalDetail']->JournalType = $_POST['JournalType'];
}

if (isset($_POST['CommitBatch']) and $_POST['CommitBatch']==_('Accept and Process Journal')){

 /* once the GL analysis of the journal is entered
  process all the data in the session cookie into the DB
  A GL entry is created for each GL entry
*/
 $_SESSION['clid'];
 chmod("voucher.txt", 0777);
$file=fopen("voucher.txt","r");
$vn=fread($file,50);


	$PeriodNo = GetPeriod($_SESSION['JournalDetail']->JnlDate,$db);

     /*Start a transaction to do the whole lot inside */
	$result = DB_Txn_Begin($db);

	$TransNo = GetNextTransNo( 0, $db);

	foreach ($_SESSION['JournalDetail']->GLEntries as $JournalItem) {
		$SQL = "INSERT INTO gltrans (type,
						typeno,
						trandate,
						periodno,
						account,
						narrative,
						amount,
						tag,
						preparedby,
						approvedby,
						voucher_no)
				VALUES ('0',
					'" . $TransNo . "',
					'" . FormatDateForSQL($_SESSION['JournalDetail']->JnlDate) . "',
					'" . $PeriodNo . "',
					'" . $JournalItem->GLCode . "',
					'" . ucwords($JournalItem->Narrative) . "',
					'" . $JournalItem->Amount . "',
					'" . $JournalItem->tag."',
					'" . $JournalItem->preparedby."',
					'" . $JournalItem->approvedby."',
					'" . $vn."'
					)";
		$ErrMsg = _('Cannot insert a GL entry for the journal line because');
		$DbgMsg = _('The SQL that failed to insert the GL Trans record was');
		$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);

		if ($_POST['JournalType']=='Reversing'){
			$SQL = "INSERT INTO gltrans (type,
							typeno,
							trandate,
							periodno,
							account,
							narrative,
							amount,
							tag,
						preparedby,
						approvedby,
						voucher_no) 
					VALUES ('0',
						'" . $TransNo . "',
						'" . FormatDateForSQL($_SESSION['JournalDetail']->JnlDate) . "',
						'" . ($PeriodNo + 1) . "',
						'" . $JournalItem->GLCode . "',
						'Reversal - " . $JournalItem->Narrative . "',
						'" . -($JournalItem->Amount) ."',
						'".$JournalItem->tag."',
						'" . $JournalItem->preparedby."',
					    '" . $JournalItem->approvedby."',
						'" . $vn."'
						)";

			$ErrMsg =_('Cannot insert a GL entry for the reversing journal because');
			$DbgMsg = _('The SQL that failed to insert the GL Trans record was');
			$result = DB_query($SQL,$db,$ErrMsg,$DbgMsg,true);
		}
	}


	$ErrMsg = _('Cannot commit the changes');
	$result= DB_Txn_Begin($db);

	//prnMsg(_('Journal').' ' . $TransNo . ' '._('has been successfully entered'),'success');
	
	$vouchno=explode("-",$vn);
	$voucherno=$vouchno[1];
      $vounumber=$voucherno+1;
	   chmod("voucher.txt", 0777);
	  $file1=fopen("voucher.txt","w");
	  fwrite($file1,"sc-".$vounumber);
	  fclose($file1);
	  
	prnMsg(_('Voucher').' ' . "sc-".($vounumber-1) . ' '._('has been Added'),'success');
	echo '<div align="right"><a href="/'.$u[1].'/generatevoucherpdf.php?voucher=sc-'.$voucherno.'&op=journal" target="_blank"><img src="images/pdf_icon.gif"/></a></div>';
	if($_SESSION['type'] == 'loan')
	{
			echo '<br /><div align="center"><a href="/'.$u[1].'/loan/pendingvouchers">' . _('') . '<input type="button" value="Back To Pending Vouchers"/></a></div><br />';
	}
	if( isset($_SESSION['clid']) && $_SESSION['type']=='tour')
	{
	   $vg="update tour_claim set voucher_generated='1',voucher='".$vn."' where id='".$_SESSION['clid']."'";
	  $vgq=DB_query($vg,$db);
	  
	}
	else if( isset($_SESSION['clid']) && $_SESSION['type']=='medical')
	{
	   $vg="update medical_claim set voucher_generated='1',voucher='".$vn."' where id='".$_SESSION['clid']."'";
	  $vgq=DB_query($vg,$db);
	  
	}
	else if( isset($_SESSION['clid']) && $_SESSION['type']=='billsubmit')
	{
	   $vg="update billsubmit set voucher_generated='1' where id='".$_SESSION['clid']."'";
	  $vgq=DB_query($vg,$db);
	  
	}
	else if( isset($_SESSION['clid']) && $_SESSION['type']=='loanadvance')
	{
	   $vg="update loanadvance set voucher_generated='1' where id='".$_SESSION['clid']."'";
	  $vgq=DB_query($vg,$db);
	  
	}
	else if(isset($_SESSION['clid']) && $_SESSION['type']=='loan'){
		$vg ="UPDATE tbl_pendingvouchers SET pendingstatus='1',voucher_number = 'sc-".($vounumber-1)."' WHERE id='".$_SESSION['clid']."'";
		$vgq=DB_query($vg,$db);
		$vg ="SELECT transactionid,entrytype FROM tbl_pendingvouchers WHERE id='".$_SESSION['clid']."'";
		$res=DB_query($vg,$db);
		$trid = DB_fetch_row($res);
		if($trid[1] == 'Disbursement')
			$tbl = 'tbl_loan_disbursement';
		if($trid[1] == 'Repayment')
			$tbl = 'tbl_loan_repayment';
		$vg ="UPDATE $tbl SET voucher_posted = '1' WHERE id='".$trid[0]."'";
		$vgq=DB_query($vg,$db);
		
		unset($_POST['JournalProcessDate']);
		unset($_POST['JournalType']);
		unset($_SESSION['JournalDetail']->GLEntries);
		unset($_SESSION['JournalDetail']);
		unset($_SESSION['clid']);
		unset($_SESSION['type']);
	}
	
	
	unset($_POST['JournalProcessDate']);
	unset($_POST['JournalType']);
	unset($_SESSION['JournalDetail']->GLEntries);
	unset($_SESSION['JournalDetail']);
	unset($_SESSION['clid']);
	unset($_SESSION['type']);

	/*Set up a newy in case user wishes to enter another */
	echo '<br /><div align="center" class="created"><a href="' . $_SERVER['PHP_SELF'] . '?NewJournal=Yes">'._('Add New Journal').'</a></div><br />';
	/*And post the journal too */
	include ('includes/GLPostings.inc');
	include ('includes/footer.inc');
	exit;

} elseif (isset($_GET['Delete'])){

	/* User hit delete the line from the journal */
	$_SESSION['JournalDetail']->Remove_GLEntry($_GET['Delete']);

} elseif (isset($_POST['Process']) and $_POST['Process']==_('Accept')){ //user hit submit a new GL Analysis line into the journal
//echo $_POST['GLCode'];
$InputError =0;
   if (($_POST['Debit'] == '') && ($_POST['Credit'] == '' )) {
			prnMsg(_('Enter Debit or Credit Amount'),'info');
			$InputError = 1;
			$AllowThisPosting = false;
		}
		if (($_POST['Debit'] != '' && !eregi('^([0-9]+(\.[0-9]+)?$)' , $_POST['Debit'])) || ($_POST['Credit'] != '' && !eregi('^([0-9]+(\.[0-9]+)?$)' , $_POST['Credit']))) {
			prnMsg(_('Enter Valid Debit or Credit Amount'),'info');
			$InputError = 1;
			$AllowThisPosting = false;
		}
		if ($_POST['GLCode'] == '' ) {
			prnMsg(_('Select a GL account code'),'info');
			$InputError = 1;
			$AllowThisPosting = false;
		}
		
		if ($_POST['GLNarrative'] == '' and $_POST['GLNarrative'] == '') {
			prnMsg(_('Enter Narrative'),'info');
			$InputError = 1;
			$AllowThisPosting = false;
		}
		if (($_POST['GLNarrative']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['GLNarrative'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Narrative, A-Z or 0-9 is Allowed'),'error');
	}
		if ($_POST['preparedby'] == '' and $_POST['preparedby'] == '') {
			prnMsg(_('Enter Prepared By'),'info');
			$AllowThisPosting = false;
		}
		/*if ($_POST['voucherno'] == '' and $_POST['voucherno'] == '') {
			prnMsg(_('You must Enter Voucher No'),'info');
			$AllowThisPosting = false;
		}
		else if (($_POST['voucherno']!='') && (!eregi('^[a-zA-Z0-9-]+$' , $_POST['voucherno'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid voucher no, A-Z or 0-9 and "-" is Allowed'),'error');
	}
		else
		{
		  $_POST['voucherno']=$_POST['voucherno'];
		}*/

	if ($_POST['GLCode']!='') {
		$extract = explode(' - ',$_POST['GLCode']);
		$_POST['GLCode'] = $extract[0];
	}
	if ($_POST['Debit']>0) {
		$_POST['GLAmount'] = $_POST['Debit'];
	} elseif ($_POST['Credit']>0) {
		$_POST['GLAmount'] = '-' . $_POST['Credit'];
	}
	if ($_POST['GLManualCode'] != '' AND is_numeric($_POST['GLManualCode'] AND $_POST['GLNarrative']!='')){
		// If a manual code was entered need to check it exists and isnt a bank account
		//$AllowThisPosting = true; //by default
		if ($_SESSION['ProhibitJournalsToControlAccounts'] == 1){
			if ($_SESSION['CompanyRecord']['gllink_debtors'] == '1' AND $_POST['GLManualCode'] == $_SESSION['CompanyRecord']['debtorsact']){
				prnMsg(_('GL Journals involving the debtors control account cannot be entered. The general ledger debtors ledger (AR) integration is enabled so control accounts are automatically maintained by webERP. This setting can be disabled in System Configuration'),'warn');
				$AllowThisPosting = false;
			}
			if ($_SESSION['CompanyRecord']['gllink_creditors'] == '1' AND $_POST['GLManualCode'] == $_SESSION['CompanyRecord']['creditorsact']){
				prnMsg(_('GL Journals involving the creditors control account cannot be entered. The general ledger creditors ledger (AP) integration is enabled so control accounts are automatically maintained by webERP. This setting can be disabled in System Configuration'),'warn');
				$AllowThisPosting = false;
			}
		}
		if (in_array($_POST['GLManualCode'], $_SESSION['JournalDetail']->BankAccounts)) {
			prnMsg(_('GL Journals involving a bank account cannot be entered') . '. ' . _('Bank account general ledger entries must be entered by either a bank account receipt or a bank account payment'),'info');
			$AllowThisPosting = false;
		}
     if ($_POST['GLNarrative'] == '' and $_POST['GLNarrative'] == '') {
			prnMsg(_('You must Enter Narrative'),'info');
			$InputError = 1;
			$AllowThisPosting = false;
		}
		if (($_POST['GLNarrative']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['GLNarrative'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Narrative, A-Z or 0-9 is Allowed'),'error');
	 $AllowThisPosting = false;
	}
		if ($AllowThisPosting ) {
			$SQL = "SELECT accountname
				FROM chartmaster
				WHERE accountcode='" . $_POST['GLManualCode'] . "'";
			$Result=DB_query($SQL,$db);

			if (DB_num_rows($Result)==0){
				prnMsg(_('The manual GL code entered does not exist in the database') . ' - ' . _('so this GL analysis item could not be added'),'warn');
				unset($_POST['GLManualCode']);
			} else {
				$myrow = DB_fetch_array($Result);
				$_SESSION['JournalDetail']->add_to_glanalysis($_POST['GLAmount'], ucwords($_POST['GLNarrative']), $_POST['GLManualCode'], $myrow['accountname'], $_POST['tag'],$_POST['preparedby'],$_POST['approvedby'],$_POST['voucherno']);
			}
		}
	} else {
		$AllowThisPosting =true; //by default
		if ($_SESSION['ProhibitJournalsToControlAccounts'] == 1){
			if ($_SESSION['CompanyRecord']['gllink_debtors'] == '1' AND $_POST['GLCode'] == $_SESSION['CompanyRecord']['debtorsact']){
				prnMsg(_('GL Journals involving the debtors control account cannot be entered. The general ledger debtors ledger (AR) integration is enabled so control accounts are automatically maintained by webERP. This setting can be disabled in System Configuration'),'warn');
				$AllowThisPosting = false;
			}
			if ($_SESSION['CompanyRecord']['gllink_creditors'] == '1' AND $_POST['GLCode'] == $_SESSION['CompanyRecord']['creditorsact']){
				prnMsg(_('GL Journals involving the creditors control account cannot be entered. The general ledger creditors ledger (AP) integration is enabled so control accounts are automatically maintained by webERP. This setting can be disabled in System Configuration'),'warn');
				$AllowThisPosting = false;
			}
		}
		

		if (in_array($_POST['GLCode'], $_SESSION['JournalDetail']->BankAccounts)) {
			prnMsg(_('GL Journals involving a bank account cannot be entered') . '. ' . _('Bank account general ledger entries must be entered by either a bank account receipt or a bank account payment'),'warn');
			$AllowThisPosting = false;
		}
if ($_POST['GLNarrative'] == '' and $_POST['GLNarrative'] == '') {
			//prnMsg(_('You must Enter Narrative'),'info');
			$InputError = 1;
			$AllowThisPosting = false;
		}
		if (($_POST['GLNarrative']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['GLNarrative'])))
	{
	  $InputError = 1;
     //prnMsg(_('Enter valid Narrative, A-Z or 0-9 is Allowed'),'error');
	 $AllowThisPosting = false;
	}
		if ($AllowThisPosting ){
			if (!isset($_POST['GLAmount'])) {
				$_POST['GLAmount']=0;
			}
			$SQL = "SELECT accountname FROM chartmaster WHERE accountcode='" . $_POST['GLCode'] . "'";
			$Result=DB_query($SQL,$db);
			$myrow=DB_fetch_array($Result);
			$_SESSION['JournalDetail']->add_to_glanalysis($_POST['GLAmount'], ucwords($_POST['GLNarrative']), $_POST['GLCode'], $myrow['accountname'], $_POST['tag'], $_POST['preparedby'], $_POST['approvedby'],$_POST['voucherno']);
		}
	}

	/*Make sure the same receipt is not double processed by a page refresh */
	$Cancel = 1;
	unset($_POST['Credit']);
	unset($_POST['Debit']);
	//unset($_POST['tag']);
	//unset($_POST['GLManualCode']);
	//unset($_POST['GLNarrative']);
}

if (isset($_POST['cancel'])){
	unset($_POST['Credit']);
	unset($_POST['Debit']);
	unset($_POST['GLAmount']);
	unset($_POST['GLCode']);
	unset($_POST['tag']);
	unset($_POST['GLManualCode']);
	unset($_POST['GLNarrative']);
}


echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Journal Entry</a></div><form action="' . $_SERVER['PHP_SELF'] . '" method="post" name="form">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

//echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') . '" alt="" />' . ' ' . $title.'</p>';

// A new table in the first column of the main table

if (!Is_Date($_SESSION['JournalDetail']->JnlDate)){
	// Default the date to the last day of the previous month
	$_SESSION['JournalDetail']->JnlDate = Date($_SESSION['DefaultDateFormat'],mktime(0,0,0,date('m'),date('d'),date('Y')));
}

echo '<table><tr class="oddrow"><td align="center"><h2>Journal Entry</h2></td></tr>
		<tr class="evenrow"><td ><div class="left">'._('Date to Process Journal') . ': <span style="color:#FF0000">*</span></div>
		<div class="right"><input type="text" class="date" alt="' . $_SESSION['DefaultDateFormat'] . '" name="JournalProcessDate" maxlength="10" size="11" value="' . $_SESSION['JournalDetail']->JnlDate . '" /></div></td></tr>';
/*echo '<td>' . _('Voucher No.') . '</td>
		<td><input type="text" name="voucherno" maxlength="10" size="100" value="' .$_POST['voucherno']. '" /></td>
		</tr>';*/
		/*echo '		<tr><td><input type="hidden" name="voucherno" maxlength="10" size="100" value="' .$_POST['voucherno']. '" /></td>
		</tr>';*/
		echo '<tr class="oddrow">';

echo '<td><div class="left">' . _('Prepared By:') . '<span style="color:#FF0000"> *</span></div><div class="right"><input type="text" name="preparedby" maxlength="45" size="100" value="' . $_SESSION['UsersRealName'] . '" readonly="readonly" onkeypress="return alphanumeric(event)"/></div></td></tr>';

echo '<tr class="evenrow">';

echo '<td><div class="left">' . _('Approved By') . ': <span style="color:#FF0000">*</span></div><div class="right"><select name="approvedby">';
					
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
	echo '</table>';
/* close off the table in the first column  */

echo '<br />';
echo '<table >';
/* Set upthe form for the transaction entry for a GL Payment Analysis item */

echo '<tr class="oddrow"><td align="center"><h2>' . _('Journal Line Entry') . '</h2></th></tr>';

/*now set up a GLCode field to select from avaialble GL accounts */
echo '<tr class="evenrow">
		<td><div class="left">' . _('Account Code') . ': <span style="color:#FF0000">*</span></div>';
		
		if (!isset($_POST['GLManualCode'])) {
	$_POST['GLManualCode']='';
}

echo '<div class="right"><input class="number" type="text" Name="GLManualCode" Maxlength="12" size="12" )"' .
		' value="'. $_POST['GLManualCode'] .'" onblur="return assignComboToInput(this,'.'GLCode'.')" onkeypress="return alphanumeric(event)"/></div></td>';

	echo	'<tr class="oddrow"><td><div class="left">' . _('Select Account') . ': <span style="color:#FF0000">*</span></div><div class="right">';
	
	$sql="SELECT accountcode,
		accountname
	FROM chartmaster
	ORDER BY accountcode";

$result=DB_query($sql, $db);
echo '<select name="GLCode" onChange="return assignComboToInput(this,'.'GLManualCode'.')">';
echo '<option value="">' . _('Select a general ledger account code') . '</option>';
while ($myrow=DB_fetch_array($result)){
	if (isset($_POST['GLCode']) and $_POST['GLCode']==$myrow['accountcode']){
		echo '<option "selected" value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'].' - ' .htmlentities($myrow['accountname'], ENT_QUOTES,'UTF-8') . '</option>';
	} else {
		echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'].' - ' .htmlentities($myrow['accountname'], ENT_QUOTES,'UTF-8') .'</option>';
	}
}
echo '</select></div></td></div></tr>';

/* Set upthe form for the transaction entry for a GL Payment Analysis item */

//Select the tag
// End select tag



if (!isset($_POST['GLNarrative'])) {
	$_POST['GLNarrative'] = '';
}
if (!isset($_POST['Credit'])) {
	$_POST['Credit'] = '';
}
if (!isset($_POST['Debit'])) {
	$_POST['Debit'] = '';
}

 if(isset($_REQUEST['Debit']) && $_REQUEST['type']!='')
  {
  echo '<tr class="evenrow"><td><div class="left">' . _('Debit') . ': <span style="color:#0F4465">*</span></div>
				<div class="right"><input type="text" class="number" name = "Debit" onChange="eitherOr(this, '.'Credit'.')" maxlength="11" size="10" value="' . $_POST['Debit'] . '" readonly="readonly" /></div></td></tr>';
 }
 else
 {
   echo '<tr class="evenrow"><td><div class="left">' . _('Debit') . ': <span style="color:#0F4465">*</span></div>
				<div class="right"><input type="text" class="number" name = "Debit" onChange="eitherOr(this, '.'Credit'.')" maxlength="11" size="10" value="' . $_POST['Debit'] . '" /></div></td></tr>';
 }
echo '<tr class="oddrow"><td><div class="left">' . _('Credit') . ': <span style="color:#0F4465">*</span></div>
				<div class="right"><input type="text" class="number" Name = "Credit" onChange="eitherOr(this, '.'Debit'.')" maxlength="11" size="10" value="' . $_POST['Credit'] . '" /></div></td>';
echo '</tr><tr class="evenrow"><td><div class="left">' . _('Narrative') . ': <span style="color:#FF0000">*</span></div>';

echo '<div class="right"><input type="text" name="GLNarrative" maxlength="200" size="100" value="'.$_POST['GLNarrative'].'" onkeypress="return alphanumeric(event)"/></div></td></tr>';






echo '<tr class="oddrow"><td align="center"><input type="submit" name="Process" value="' . _('Accept') . '" />&nbsp;&nbsp;<input type="submit" name="cancel" value="' . _('Cancel') . '" /></td></tr></table><br />'; /*Close the main table */
echo '';


echo '<table>';

echo '<tr class="oddrow"><td colspan="7" align="center"><h2>' . _('Journal Summary') . '</h2></td></tr>';
echo '<tr>
	
	<th>'._('Account').'</th>
	<th>'._('Debit').'</th>
	<th>'._('Credit').'</th>
	<th>'._('Narrative').'</th>
	<th>'._('Prepared By').'</th>
	<th>'._('Approved By').'</th>
	<th>'._('Action').'</th>
	</tr>';

$DebitTotal=0;
$CreditTotal=0;
$j=0;

foreach ($_SESSION['JournalDetail']->GLEntries as $JournalItem) {
		if ($j==1) {
			echo '<tr class="odd">';
			$j=0;
		} else {
			echo '<tr class="odd">';
			$j++;
		}
	$sql="SELECT tagdescription
			FROM tags
			WHERE tagref='".$JournalItem->tag . "'";
	$result=DB_query($sql, $db);
	$myrow=DB_fetch_row($result);
	if ($JournalItem->tag==0) {
		$TagDescription=_('None');
	} else {
		$TagDescription=$myrow[0];
	}
	echo '<td>' . $JournalItem->GLCode . ' - ' . $JournalItem->GLActName . '</td>';
	if ($JournalItem->Amount>0) {
		echo '<td class="number">' . number_format($JournalItem->Amount,$_SESSION['CompanyRecord']['decimalplaces']) . '</td><td></td>';
		$DebitTotal=$DebitTotal+$JournalItem->Amount;
	} elseif($JournalItem->Amount<0) {
		$Credit=(-1 * $JournalItem->Amount);
		echo '<td></td>
			<td class="number">' . number_format($Credit,$_SESSION['CompanyRecord']['decimalplaces']) . '</td>';
		$CreditTotal=$CreditTotal+$Credit;
	}

	echo '<td>' . ucwords($JournalItem->Narrative)  . '</td><td>' . $JournalItem->preparedby  . '</td><td>' . $JournalItem->approvedby  . '</td>
		<td><a href="' . $_SERVER['PHP_SELF'] . '?Delete=' . $JournalItem->ID . '">' . _('Delete').'</a></td>
	</tr>';
}

echo '<tr class="even">
	<td class="number" align="center"><b>' . _('Total') .  '</b></td>
	<td class="number"><b>' . number_format($DebitTotal,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></td>
	<td class="number"><b>' . number_format($CreditTotal,$_SESSION['CompanyRecord']['decimalplaces']) . '</b></td>';
	
	if ($DebitTotal!=$CreditTotal) {
	echo '<td colspan="4" align="center" style="background-color: #fddbdb"><b>' . _('Required to balance') .' - </b>' .
		number_format(abs($DebitTotal-$CreditTotal),$_SESSION['CompanyRecord']['decimalplaces']);
}
if ($DebitTotal>$CreditTotal) {
	echo ' ' . _('Credit') . '</td></tr>';
} else if ($DebitTotal<$CreditTotal) {
	echo ' ' . _('Debit') . '</td></tr>';
}
echo '</table></td></tr>';


if (ABS($_SESSION['JournalDetail']->JournalTotal)<0.001 AND $_SESSION['JournalDetail']->GLItemCounter > 0){
	echo '<br /><br /><div class="centre" align="center"><input type="submit" name="CommitBatch" value="' ._('Accept and Process Journal').'" /></div>';
} elseif(count($_SESSION['JournalDetail']->GLEntries)>0) {
	echo '<br /><br />';
	prnMsg(_('The journal must balance ie debits equal to credits before it can be processed'),'warn');
}

if (!isset($_GET['NewJournal']) or $_GET['NewJournal']=='') {
	echo '<script>defaultControl(document.form.GLManualCode);</script>';
} else {
	echo '<script>defaultControl(document.form.JournalProcessDate);</script>';
}



echo '</form>';
include('includes/footer.inc');
?>