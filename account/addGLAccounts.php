<?php
/* $Id: GLAccounts.php 4629 2011-07-09 08:22:59Z daintree $*/

include('includes/session.inc');
$title = _('Chart of Accounts Maintenance');

include('includes/header.inc');
?>
<script type="text/javascript">
function clickroute() {
	window.location.href='GLAccounts.php';
	}
	</script>
<?php
echo "<div class='breadcrumb'><a href='/".$u[1]."'>Home</a> &raquo; <a href='GLAccounts.php'>GL Account Master</a> &raquo; <a href='".$_SERVER['PHP_SELF']."'>Add GL Account</a></div>";
if (isset($_POST['SelectedAccount'])){
	$SelectedAccount = $_POST['SelectedAccount'];
} elseif (isset($_GET['SelectedAccount'])){
	$SelectedAccount = $_GET['SelectedAccount'];
}

//echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/transactions.png" title="' .
		_('General Ledger Accounts') . '" alt="" />' . ' ' . $title . '</p>';

//echo '<a href="GLAccounts.php">'._('Back') . '</a>';
if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
if ($_POST['AccountCode']=='') {
		$InputError = 1;
		prnMsg(_('The account code must be Entered'),'warn');
	}
	
	if ($_POST['AccountCode']!='' && !is_numeric($_POST['AccountCode'])) {
		$InputError = 1;
		prnMsg(_('The account code must be numeric'),'warn');
	}
	if ($_POST['AccountName']=='') {
		$InputError = 1;
		prnMsg(_('The account name must be Entered'),'warn');
	}
	 if (($_POST['AccountName']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['AccountName'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid AccountName, A-Z or 0-9 is Allowed'),'error');
	}
	if (mb_strlen($_POST['AccountName']) >50) {
		$InputError = 1;
		prnMsg( _('The account name must be fifty characters or less long'),'warn');
	}
	 if ($_POST['AccountCode']!='') {
	    $ach="select * from chartmaster where accountcode='".$_POST['AccountCode']."'";
		$achq=DB_query($ach,$db);
		$achn=DB_num_rows($achq);
		if($achn>0)
		{
		  $InputError = 1;
		prnMsg(_('The account code Already Exist'),'warn');
		}
		
	}
	
	if ($_POST['Group']=='') {
		$InputError = 1;
		prnMsg(_('The account Group must be Selected'),'warn');
	}
	
	
	if ($_POST['description']=='') {
		$InputError = 1;
		prnMsg(_('The description must be Entered'),'warn');
	}
	if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Description, A-Z or 0-9 is Allowed'),'error');
	}
	if ($_POST['opening_balance']=='') {
		$InputError = 1;
		prnMsg(_('The opening balance must be Entered'),'warn');
	}
	if ($_POST['opening_balance']!='' && !is_numeric($_POST['opening_balance'])) {
		$InputError = 1;
		prnMsg(_('The opening balance must be Numeric'),'warn');
	}
	if (($_POST['tax_details']!='') && (strlen($_POST['tax_details'])<10))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Pan Card No'),'error');
	}
	if (($_POST['tax_details']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['tax_details'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Pan Card No, A-Z or 0-9 is Allowed'),'error');
	}
	if (isset($SelectedAccount) AND $InputError !=1) {

		$sql = "UPDATE chartmaster SET accountname='" . $_POST['AccountName'] . "',
						group_='" . $_POST['Group'] . "',description='" . $_POST['description'] . "',opening_balance='" . $_POST['opening_balance'] . "',opening_balance_type='" . $_POST['opening_balance_type'] . "',tax_details='" . $_POST['tax_details'] . "'
				WHERE accountcode ='" . $SelectedAccount . "'";

		$ErrMsg = _('Could not update the account because');
		$result = DB_query($sql,$db,$ErrMsg);
		prnMsg (_('The general ledger account has been updated'),'success');
	} elseif ($InputError !=1) {

	/*SelectedAccount is null cos no item selected on first time round so must be adding a	record must be submitting new entries */

		$ErrMsg = _('Could not add the new account code');
		$sql = "INSERT INTO chartmaster (accountcode,
						accountname,
						group_,
						description,
						opening_balance,
						opening_balance_type,
						tax_details)
					VALUES ('" . $_POST['AccountCode'] . "',
							'" . ucwords($_POST['AccountName']) . "',
							'" . $_POST['Group'] . "',
							'" . ucwords($_POST['description']) . "',
							'" . $_POST['opening_balance'] . "',
							'" . $_POST['opening_balance_type'] . "',
							'" . $_POST['tax_details'] . "')";
		$result = DB_query($sql,$db,$ErrMsg);

		prnMsg(_('The new general ledger account has been added'),'success');
		header("location:GLAccounts.php?msg=Account Added Successfully");
	}

	/*unset ($_POST['Group']);
	unset ($_POST['AccountCode']);
	unset ($_POST['AccountName']);*/
	unset($SelectedAccount);

} 

if (!isset($_GET['delete'])) {

	echo '<form method="post" name="GLAccounts" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	if (isset($SelectedAccount)) {
		//editing an existing account

		$sql = "SELECT accountcode, accountname, group_,description, opening_balance,opening_balance_type,tax_details FROM chartmaster WHERE accountcode='" . $SelectedAccount ."'";

		$result = DB_query($sql, $db);
		$myrow = DB_fetch_array($result);

		$_POST['AccountCode'] = $myrow['accountcode'];
		$_POST['AccountName']	= $myrow['accountname'];
		$_POST['Group'] = $myrow['group_'];
		$_POST['description'] = $myrow['description'];
		$_POST['opening_balance'] = $myrow['opening_balance'];
		$_POST['opening_balance_type'] = $myrow['opening_balance_type'];
		$_POST['tax_details'] = $myrow['tax_details'];

		echo '<input type="hidden" name="SelectedAccount" value="' . $SelectedAccount . '">';
		echo '<input type="hidden" name="AccountCode" VALUE="' . $_POST['AccountCode'] .'">';
		echo '<table cellpadding="2" cellspacing="1">';
		echo '<tr class="oddrow"><td colspan="2">' . _('Account Code') . '</td>
					</tr>';
			echo	'<tr><td>' . _('Account Code') . ': <span style="color:#FF0000">*</span></td>
					<td>' . $_POST['AccountCode'] . '</td></tr>';
	} else {
		echo "<table cellpadding='2' cellspacing='1'>";
		
		echo '<tr class="oddrow" ><td colspan="2" align="center"><h2>Add GL Account</h2></td>
					</tr>';
		echo '<tr class="evenrow"><td><div class="left">' . _('Account Code') . ': <span style="color:#FF0000">*</span></div>
					<div class="right"><input type="text" name="AccountCode" id="AccountCode" size="12"  maxlength="12" value="'. $_POST['AccountCode'].'" onkeypress="return fononlyn(event)"  ></div></td>
				</tr>';
	}

	if (!isset($_POST['AccountName'])) {$_POST['AccountName']='';}
	echo '<tr class="oddrow"><td><div class="left">' . _('Account Name') . ": <span style='color:#FF0000'>*</span></div><div class='right'><input type='Text' size=51 maxlength=45 name='AccountName' id='AccountName' onkeypress='return alphanumeric(event)' value='" . $_POST['AccountName'] . "' ></div></td></tr>";

	$sql = 'SELECT groupname FROM accountgroups ORDER BY groupname,sequenceintb';
	$result = DB_query($sql, $db);

	echo '<tr class="evenrow"><td><div class="left">' . _('Account Group') . ': <span style="color:#FF0000">*</span></div><div class="right"><select name="Group" id="Group" >';
     echo '<option value="">--Select--</option>';
	while ($myrow = DB_fetch_array($result)){
		if ($myrow['groupname']==$_POST['Group']){
			echo '<option  value="'.$myrow[0].'" selected>' . ucwords($myrow[0]) . '</option>';
		} else {
			echo '<option value="'.$myrow[0] . '">' . ucwords($myrow[0]) . '</option>';
		}
		
	}

	/*if (!isset($_GET['SelectedAccount']) or $_GET['SelectedAccount']=='') {
		echo '<script>defaultControl(document.GLAccounts.AccountCode);</script>';
	} else {
		echo '<script>defaultControl(document.GLAccounts.AccountName);</script>';
	}*/

	echo '</select></div></td></tr>';
	//echo $_POST['opening_balance_type'];
	echo '<tr class="oddrow"><td><div class="left">' . _('Description') . ': <span style="color:#FF0000">*</span></div><div class="right"><input type="text" name="description" size="51"  maxlength="200" value="'.$_POST['description'].'" id="description" onkeypress="return alphanumeric(event)" /></div></td></tr>';
		echo '<tr class="evenrow"><td><div class="left">' . _('Opening Balance') . ': <span style="color:#FF0000">*</span></div>
					<div class="right"><input type="text" name="opening_balance" size="51" id="opening_balance"  maxlength="11" value="'.$_POST['opening_balance'].'" class="number" /></div></td>
				</tr>';
		echo '<tr class="oddrow"><td><div class="left">' . _('Opening Balance Type') . ':</div>
					<div class="right"><select name="opening_balance_type">';
					
					if (isset($_POST['opening_balance_type'])){
					
					if( $_POST['opening_balance_type']=='dr')
					{
					
			echo '<option value="cr" >Cr</option>
			<option value="dr" selected>Dr</option>';
			   
		} else {
			echo '<option value="cr" selected>Cr</option>
			<option value="dr">Dr</option>';
		}
			}	else {
			echo '<option value="cr" >Cr</option>
			<option value="dr">Dr</option>';
			}
				  echo '</select></div></td>
				</tr>';
				echo '<tr class="evenrow"><td><div class="left">' . _('Tax details') . ':</div>
					<div class="right"><input type="text" name="tax_details" size="21" maxlength="10" value="'.$_POST['tax_details'].'" id="tax_details" onkeypress="return alphanumeric(event)" /></div></td>
				</tr><tr class="oddrow"><td align="center" style="border-right:hidden;"><input type="button" value="Back"
				ONCLICK="clickroute();" />&nbsp; <input type="Submit" name="submit" value="'. _('Save') . '"></td></tr>';
	echo '</table>';

	//echo '<br /><div class="centre"><input type="Submit" name="submit" value="'. _('Save') . '"></div>';

	echo '</form>';

} //end if record deleted no point displaying form to add record

if (isset($_POST['submit'])) {

	
if ($_POST['AccountCode']=='') {
		echo "<script type='text/javascript'>document.getElementById('AccountCode').className='ercol';</script>";
	}
	
	if (!is_numeric($_POST['AccountCode'])) {
		echo "<script type='text/javascript'>document.getElementById('AccountCode').className='ercol';</script>";
	}
	if ($_POST['AccountName']=='') {
		echo "<script type='text/javascript'>document.getElementById('AccountName').className='ercol';</script>";
	}
	 if (($_POST['AccountName']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['AccountName'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('AccountName').className='ercol';</script>";
	}
	if (mb_strlen($_POST['AccountName']) >50) {
		echo "<script type='text/javascript'>document.getElementById('AccountName').className='ercol';</script>";
	}
	 if ($_POST['AccountCode']!='') {
	    $ach="select * from chartmaster where accountcode='".$_POST['AccountCode']."'";
		$achq=DB_query($ach,$db);
		$achn=DB_num_rows($achq);
		if($achn>0)
		{
		  echo "<script type='text/javascript'>document.getElementById('AccountCode').className='ercol';</script>";
		}
		
	}
	
	

	if ($_POST['Group']=='') {
		echo "<script type='text/javascript'>document.getElementById('Group').className='ercol';</script>";
	}
	
	
	if ($_POST['description']=='') {
		echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	if ($_POST['opening_balance'] == '') {
		/*echo "<script type='text/javascript'>document.getElementById('opening_balance').className='ercol';</script>";*/
		echo "<script type='text/javascript'>document.getElementById('opening_balance').style.border='1px solid red !important';</script>";
	      echo "<script type='text/javascript'>document.getElementById('opening_balance').className='number';</script>";
	}
	if (!is_numeric($_POST['opening_balance'])) {
		echo "<script type='text/javascript'>document.getElementById('opening_balance').style.border='1px solid red !important';</script>";
	      echo "<script type='text/javascript'>document.getElementById('opening_balance').className='number';</script>";
	}
	if (($_POST['tax_details']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['tax_details'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('tax_details').className='ercol';</script>";
	}
		if (($_POST['tax_details']!='') && (strlen($_POST['tax_details'])<10))
	{
	  echo "<script type='text/javascript'>document.getElementById('tax_details').className='ercol';</script>";
	}
	}
 //END IF selected ACCOUNT

//end of ifs and buts!

echo '<p>';

if (isset($SelectedAccount)) {
	echo '<div class="centre"><a href="' . $_SERVER['PHP_SELF'] . '">' .  _('Show All Accounts') . '</a></div>';
}

echo '<p />';

include('includes/footer.inc');
?>