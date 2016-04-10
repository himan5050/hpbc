<?php

/* $Id: AccountGroups.php 4622 2011-07-03 04:33:19Z daintree $*/

include('includes/session.inc');

$title = _('Account Groups');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '';
function CheckForRecursiveGroup ($ParentGroupName, $GroupName, $db) {

/* returns true ie 1 if the group contains the parent group as a child group
ie the parent group results in a recursive group structure otherwise false ie 0 */

	$ErrMsg = _('An error occurred in retrieving the account groups of the parent account group during the check for recursion');
	$DbgMsg = _('The SQL that was used to retrieve the account groups of the parent account group and that failed in the process was');

	do {
		$sql = "SELECT parentgroupname FROM accountgroups WHERE groupname='" . $GroupName ."'";

		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
		$myrow = DB_fetch_row($result);
		if ($ParentGroupName == $myrow[0]){
			return true;
		}
		$GroupName = $myrow[0];
	} while ($myrow[0]!='');
	return false;
} //end of function CheckForRecursiveGroupName

// If $Errors is set, then unset it.
if (isset($Errors)) {
	unset($Errors);
}

$Errors = array();

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test

	$InputError = 0;

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;

	$sql="SELECT count(groupname)
			FROM accountgroups WHERE groupname='".$_POST['GroupName']."'";

    $DbgMsg = _('The SQL that was used to retrieve the information was');
    $ErrMsg = _('Could not check whether the group exists because');

    $result=DB_query($sql, $db,$ErrMsg,$DbgMsg);
    $myrow=DB_fetch_row($result);

  if (mb_strlen($_POST['GroupName'])==0){
		$InputError = 1;
		prnMsg( _('The account group name must be at least one character long'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	 if (($_POST['GroupName']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['GroupName'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid GroupName, A-Z or 0-9 is Allowed'),'error');
	}
  
	 if ($_POST['SectionInAccounts']==''){
		$InputError = 1;
		prnMsg( _('Select Section in Account'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	

	if ($myrow[0]!=0 and $_POST['SelectedAccountGroup']=='') {
		$InputError = 1;
		prnMsg( _('The account group name already exists in the database'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	if (ContainsIllegalCharacters($_POST['GroupName'])) {
		$InputError = 1;
		prnMsg( _('The account group name cannot contain the character') . " '&' " . _('or the character') ."' '",'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	
	if (mb_strlen($_POST['description'])==0){
		$InputError = 1;
		prnMsg( _('The Description must be at least one character long'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	if (($_POST['description']!='') && (!eregi('^[a-z0-9A-Z ]+$' , $_POST['description'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Description, A-Z  is Allowed'),'error');
	}
	if ($_POST['ParentGroupName'] !=''){
		if (CheckForRecursiveGroup($_POST['GroupName'],$_POST['ParentGroupName'],$db)) {
			$InputError =1;
			prnMsg(_('The parent account group selected appears to result in a recursive account structure - select an alternative parent account group or make this group a top level account group'),'error');
			$Errors[$i] = 'ParentGroupName';
			$i++;
		}/* else {
			$sql = "SELECT pandl,
						sequenceintb,
						sectioninaccounts,description
					FROM accountgroups
					WHERE groupname='" . $_POST['ParentGroupName'] . "'";

            $DbgMsg = _('The SQL that was used to retrieve the information was');
            $ErrMsg = _('Could not check whether the group is recursive because');

            $result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

            $ParentGroupRow = DB_fetch_array($result);
			$_POST['SequenceInTB'] = $ParentGroupRow['sequenceintb'];
			$_POST['PandL'] = $ParentGroupRow['pandl'];
			$_POST['SectionInAccounts']= $ParentGroupRow['sectioninaccounts'];
			$_POST['description']= $ParentGroupRow['description'];
			prnMsg(_('Since this account group is a child group, the sequence in the trial balance, the section in the accounts and whether or not the account group appears in the balance sheet or profit and loss account are all properties inherited from the parent account group. Any changes made to these fields will have no effect.'),'warn');
		}*/
	}
	if (!is_long((int) $_POST['SectionInAccounts'])) {
		$InputError = 1;
		prnMsg( _('The section in accounts must be an integer'),'error');
		$Errors[$i] = 'SectionInAccounts';
		$i++;
	}
	if (!is_long((int) $_POST['SequenceInTB'])) {
		$InputError = 1;
		prnMsg( _('The sequence in the trial balance must be an integer'),'error');
		$Errors[$i] = 'SequenceInTB';
		$i++;
	}
	if (!is_numeric($_POST['SequenceInTB']) or $_POST['SequenceInTB'] > 10000) {
		$InputError = 1;
		prnMsg( _('The sequence in the TB must be numeric and less than') . ' 10,000','error');
		$Errors[$i] = 'SequenceInTB';
		$i++;
	}


	if ($_POST['SelectedAccountGroup']!='' AND $InputError !=1) {

		/*SelectedAccountGroup could also exist if submit had not been clicked this code would not run in this case cos submit is false of course  see the delete code below*/

		$sql = "UPDATE accountgroups
				SET groupname='" . $_POST['GroupName'] . "',
					sectioninaccounts=" . $_POST['SectionInAccounts'] . ",
					pandl=" . $_POST['PandL'] . ",
					sequenceintb=" . $_POST['SequenceInTB'] . ",
					parentgroupname='" . $_POST['ParentGroupName'] . "',
					description='" . $_POST['description'] . "'
				WHERE groupname = '" . $_POST['SelectedAccountGroup'] . "'";
        $ErrMsg = _('An error occurred in updating the account group');
        $DbgMsg = _('The SQL that was used to update the account group was');

		$msg = _('Record Updated');
	} elseif ($InputError !=1) {

	/*Selected group is null cos no item selected on first time round so must be adding a record must be submitting new entries in the new account group form */

		 $sql = "INSERT INTO accountgroups (
					groupname,
					sectioninaccounts,
					sequenceintb,
					pandl,
					parentgroupname,
					description)
			VALUES (
				'" . $_POST['GroupName'] . "',
				" . $_POST['SectionInAccounts'] . ",
				" . $_POST['SequenceInTB'] . ",
				" . $_POST['PandL'] . ",
				'" . $_POST['ParentGroupName'] . "',
				'" . $_POST['description'] . "'
				)";
        $ErrMsg = _('An error occurred in inserting the account group');
        $DbgMsg = _('The SQL that was used to insert the account group was');
		$msg = _('Record inserted');
		header("location:AccountGroups.php?msg=Group Added Successfully");
	}

	if ($InputError!=1){
		//run the SQL from either of the above possibilites
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
		prnMsg($msg,'success');
		unset ($_POST['SelectedAccountGroup']);
		unset ($_POST['GroupName']);
		unset ($_POST['SequenceInTB']);
	}
} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'ChartMaster'

	$sql= "SELECT COUNT(*) FROM chartmaster WHERE chartmaster.group_='" . $_GET['SelectedAccountGroup'] . "'";
    $ErrMsg = _('An error occurred in retrieving the group information from chartmaster');
    $DbgMsg = _('The SQL that was used to retrieve the information was');
	$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		prnMsg( _('Cannot delete this account group because general ledger accounts have been created using this group'),'warn');
		echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('general ledger accounts that refer to this account group') . '</font>';

	} else {
		$sql = "SELECT COUNT(groupname) FROM accountgroups WHERE parentgroupname = '" . $_GET['SelectedAccountGroup'] . "'";
        $ErrMsg = _('An error occurred in retrieving the parent group information');
        $DbgMsg = _('The SQL that was used to retrieve the information was');
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			prnMsg( _('Cannot delete this account group because it is a parent account group of other account group(s)'),'warn');
			echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('account groups that have this group as its/there parent account group') . '</font>';
		} else {
			$sql="DELETE FROM accountgroups WHERE groupname='" . $_GET['SelectedAccountGroup'] . "'";
            $ErrMsg = _('An error occurred in deleting the account group');
            $DbgMsg = _('The SQL that was used to delete the account group was');
			$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
			prnMsg( $_GET['SelectedAccountGroup'] . ' ' . _('group has been deleted') . '!','success');
		}

	} //end if account group used in GL accounts

}




if (isset($_POST['SelectedAccountGroup']) OR isset($_GET['SelectedAccountGroup'])) {
	echo '<br /><div class="centre"><a href="' . $_SERVER['PHP_SELF'] .'">' . _('Review Account Groups') . '</a></div>';
}

if (! isset($_GET['delete'])) {

	echo '<form method="post" id="AccountGroups" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


	if (isset($_GET['SelectedAccountGroup'])) {
		//editing an existing account group

		$sql = "SELECT groupname,
				sectioninaccounts,
				sequenceintb,
				pandl,
				parentgroupname,
				description
			FROM accountgroups
			WHERE groupname='" . $_GET['SelectedAccountGroup'] ."'";

		$ErrMsg = _('An error occurred in retrieving the account group information');
		$DbgMsg = _('The SQL that was used to retrieve the account group and that failed in the process was');
		$result = DB_query($sql, $db,$ErrMsg,$DbgMsg);
		if (DB_num_rows($result) == 0) {
			prnMsg( _('The account group name does not exist in the database'),'error');
			include('includes/footer.inc');
			exit;
		}
		$myrow = DB_fetch_array($result);

		$_POST['GroupName'] = $myrow['groupname'];
		$_POST['SectionInAccounts']  = $myrow['sectioninaccounts'];
		$_POST['SequenceInTB']  = $myrow['sequenceintb'];
		$_POST['PandL']  = $myrow['pandl'];
		$_POST['ParentGroupName'] = $myrow['parentgroupname'];
		$_POST['description'] = $myrow['description'];

		echo '<table class="selection"><tr><td>';
		echo '<input type="hidden" name="SelectedAccountGroup" value="' . $_GET['SelectedAccountGroup'] . '" />';
		echo '<input type="hidden" name="GroupName" value="' . $_POST['GroupName'] . '" />';

		echo _('Account Group') . ':' . '</td>';

		echo '<td>' . $_POST['GroupName'] . '</td></tr>';

	} else { //end of if $_POST['SelectedAccountGroup'] only do the else when a new record is being entered

		if (!isset($_POST['SelectedAccountGroup'])){
			$_POST['SelectedAccountGroup']='';
		}
		if (!isset($_POST['GroupName'])){
			$_POST['GroupName']='';
		}
		if (!isset($_POST['SectionInAccounts'])){
			$_POST['SectionInAccounts']='';
		}
		if (!isset($_POST['SequenceInTB'])){
			$_POST['SequenceInTB']='';
		}
		if (!isset($_POST['PandL'])){
			$_POST['PandL']='';
		}
		if (!isset($_POST['description'])){
			$_POST['description']='';
		}

		echo '<div class="breadcrumb">Home &raquo; <a href="AccountGroups.php">Accounts Groups</a> &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Add Account Group</a></div><table class="selection">
		<tr class="oddrow"><td align="center"><h2>Add Account Group</h2></td></tr>
		<tr class="evenrow"><td><div class="left">';
		echo '<input  type="hidden" name="SelectedAccountGroup" value="' . $_POST['SelectedAccountGroup'] . '" />';
		echo _('Group Name') . ':' . ' <span style="color:#FF0000">*</span></div><div class="right">
		<input tabindex="1" ' . (in_array('GroupName',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="GroupName" size="50" maxlength="45" value="' . $_POST['GroupName'] . '" onkeypress="return alphanumeric(event)" onkeypress = "return fononlyn(event)" /></div></td></tr>';
	}
	echo '<tr class="oddrow"><td><div class="left">' . _('Parent Group') . ':' . ' <span style="color:#FF0000">*</span></div>
			<div class="right"><select tabindex="2" ' . (in_array('ParentGroupName',$Errors) ?  'class="selecterror"' : '' ) . '  name="ParentGroupName">';
   
	$sql = "SELECT groupname FROM accountgroups order by groupname";
	$groupresult = DB_query($sql, $db,$ErrMsg,$DbgMsg);
	/*if (!isset($_POST['ParentGroupName'])){
		echo '<option selected value="">' ._('--Select Group--').'</option>';
	} else {
		echo '<option value="">' ._('--Select Group--').'</option>';
	}*/
	if (!isset($_POST['ParentGroupName'])){
		echo '<option selected value="">' ._('Top Level Group').'</option>';
	} else {
		echo '<option value="">' ._('Top Level Group').'</option>';
	}


	while ( $grouprow = DB_fetch_array($groupresult) ) {

		if (isset($_POST['ParentGroupName']) and $_POST['ParentGroupName']==$grouprow['groupname']) {
			echo '<option selected="selected" value="'.htmlentities($grouprow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlentities(ucwords($grouprow['groupname']), ENT_QUOTES,'UTF-8').'</option>';
		} else {
			echo '<option value="'.htmlentities($grouprow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlentities(ucwords($grouprow['groupname']), ENT_QUOTES,'UTF-8').'</option>';
		}
	}
	echo '</select>';
	echo '</div></td></tr>';

	echo '<tr class="evenrow"><td><div class="left">' . _('Section In Accounts') . ':' . ' <span style="color:#FF0000">*</span></div>
	<div class="right"><select tabindex="3" ' . (in_array('SectionInAccounts',$Errors) ?  'class="selecterror"' : '' ) .
      '  name="SectionInAccounts">';
    echo "<option value=''>--Select Section--</option>";
	$sql = "SELECT sectionid, sectionname FROM accountsection ORDER BY sectionname,sectionid";
	$secresult = DB_query($sql, $db,$ErrMsg,$DbgMsg);
	while( $secrow = DB_fetch_array($secresult) ) {
		if ($_POST['SectionInAccounts']==$secrow['sectionid']) {
			echo '<option selected="selected" value="'.$secrow['sectionid'].'">'.ucwords($secrow['sectionname']).' ('.ucwords($secrow['sectionid']).')</option>';
		} else {
			echo '<option value="'.$secrow['sectionid'].'">'.ucwords($secrow['sectionname']).' ('.ucwords($secrow['sectionid']).')</option>';
		}
	}
	echo '</select>';
	echo '</div></td></tr>';

	echo '<tr class="oddrow"><td><div class="left">' . _('Profit and Loss') . ':' . ' </div>
	<div class="right"><select tabindex="4" name="PandL">';

	if ($_POST['PandL']!=0 ) {
		echo '<option selected="selected" value="1">' . _('Yes').'</option>';
	} else {
		echo '<option value="1">' . _('Yes').'</option>';
	}
	if ($_POST['PandL']==0) {
		echo '<option selected="selected" value="0">' . _('No').'</option>';
	} else {
		echo '<option value="0">' . _('No').'</option>';
	}

	echo '</select></div></td></tr>';

	echo '<tr class="evenrow"><td><div class="left">' . _('Sequence In TB') . ':' . ' <span style="color:#FF0000">*</span></div>';
	echo '<div class="right"><input tabindex="5" ' . (in_array('SequenceInTB',$Errors) ? 'class="inputerror"' : '' ) .
		' type="text" maxlength="5" name="SequenceInTB" class="number" value="' . $_POST['SequenceInTB'] . '" onkeypress = "return fononlyn(event)" /></div></td></tr>';
	echo '<tr class="oddrow"><td><div class="left">' . _('Description') . ': <span style="color:#FF0000">*</span></div><div class="right"><input type="text" name="description" value="' . $_POST['description'] .'" maxlength="200"onkeypress="return alphanumeric(event)" tabindex="6" /></div></td></tr>';
	echo '<tr class="evenrow"><td align="center"><div class="centre"><a href="AccountGroups.php" tabindex="7"> <input  type="submit" value="Back"/></a> <input tabindex="8" type="submit" name="submit" value="' . _('Save') . '" /></div></td></tr>';

	echo '</table><br />';

	echo '<script  type="text/javascript">defaultControl(document.forms[0].GroupName);</script>';

	echo '</form>';

} //end if record deleted no point displaying form to add record
include('includes/footer.inc');
?>