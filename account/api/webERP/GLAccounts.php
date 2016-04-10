<?php
/* $Id: GLAccounts.php 4629 2011-07-09 08:22:59Z daintree $*/

include('includes/session.inc');
$title = _('Chart of Accounts Maintenance');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');


if (isset($_POST['SelectedAccount'])){
	$SelectedAccount = $_POST['SelectedAccount'];
} elseif (isset($_GET['SelectedAccount'])){
	$SelectedAccount = $_GET['SelectedAccount'];
}


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
if ($_POST['AccountCode']=='') {
		$InputError = 1;
		prnMsg(_('The account code must be Entered'),'warn');
	}
	if (!is_long((integer)$_POST['AccountCode'])) {
		$InputError = 1;
		prnMsg(_('The account code must be an integer'),'warn');
	} elseif (mb_strlen($_POST['AccountName']) >50) {
		$InputError = 1;
		prnMsg( _('The account name must be fifty characters or less long'),'warn');
	}
if ($_POST['AccountName']=='') {
		$InputError = 1;
		prnMsg(_('The account name must be Entered'),'warn');
	}
	if ($_POST['description']=='') {
		$InputError = 1;
		prnMsg(_('The description must be Entered'),'warn');
	}
	if ($_POST['opening_balance']=='') {
		$InputError = 1;
		prnMsg(_('The opening balance must be Entered'),'warn');
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
							'" . $_POST['AccountName'] . "',
							'" . $_POST['Group'] . "',
							'" . $_POST['description'] . "',
							'" . $_POST['opening_balance'] . "',
							'" . $_POST['opening_balance_type'] . "',
							'" . $_POST['tax_details'] . "')";
		$result = DB_query($sql,$db,$ErrMsg);

		prnMsg(_('The new general ledger account has been added'),'success');
	}

	unset ($_POST['Group']);
	unset ($_POST['AccountCode']);
	unset ($_POST['AccountName']);
	unset($SelectedAccount);

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

// PREVENT DELETES IF DEPENDENT RECORDS IN 'ChartDetails'

	$sql= "SELECT COUNT(*) 
			FROM chartdetails 
			WHERE chartdetails.accountcode ='" . $SelectedAccount . "' 
			AND chartdetails.actual <>0";
	$result = DB_query($sql,$db);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		$CancelDelete = 1;
		prnMsg(_('Cannot delete this account because chart details have been created using this account and at least one period has postings to it'),'warn');
		echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('chart details that require this account code');

	} else {
// PREVENT DELETES IF DEPENDENT RECORDS IN 'GLTrans'
		$sql= "SELECT COUNT(*) 
				FROM gltrans 
				WHERE gltrans.account ='" . $SelectedAccount . "'";

		$ErrMsg = _('Could not test for existing transactions because');

		$result = DB_query($sql,$db,$ErrMsg);

		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			$CancelDelete = 1;
			prnMsg( _('Cannot delete this account because transactions have been created using this account'),'warn');
			echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('transactions that require this account code');

		} else {
			//PREVENT DELETES IF Company default accounts set up to this account
			$sql= "SELECT COUNT(*) FROM companies
					WHERE debtorsact='" . $SelectedAccount ."'
					OR pytdiscountact='" . $SelectedAccount ."'
					OR creditorsact='" . $SelectedAccount ."'
					OR payrollact='" . $SelectedAccount ."'
					OR grnact='" . $SelectedAccount ."'
					OR exchangediffact='" . $SelectedAccount ."'
					OR purchasesexchangediffact='" . $SelectedAccount ."'
					OR retainedearnings='" . $SelectedAccount ."'";


			$ErrMsg = _('Could not test for default company GL codes because');

			$result = DB_query($sql,$db,$ErrMsg);

			$myrow = DB_fetch_row($result);
			if ($myrow[0]>0) {
				$CancelDelete = 1;
				prnMsg( _('Cannot delete this account because it is used as one of the company default accounts'),'warn');

			} else  {
				//PREVENT DELETES IF Company default accounts set up to this account
				$sql= "SELECT COUNT(*) FROM taxauthorities
					WHERE taxglcode='" . $SelectedAccount ."'
					OR purchtaxglaccount ='" . $SelectedAccount ."'";

				$ErrMsg = _('Could not test for tax authority GL codes because');
				$result = DB_query($sql,$db,$ErrMsg);

				$myrow = DB_fetch_row($result);
				if ($myrow[0]>0) {
					$CancelDelete = 1;
					prnMsg( _('Cannot delete this account because it is used as one of the tax authority accounts'),'warn');
				} else {
//PREVENT DELETES IF SALES POSTINGS USE THE GL ACCOUNT
					$sql= "SELECT COUNT(*) FROM salesglpostings
						WHERE salesglcode='" . $SelectedAccount ."'
						OR discountglcode='" . $SelectedAccount ."'";

					$ErrMsg = _('Could not test for existing sales interface GL codes because');

					$result = DB_query($sql,$db,$ErrMsg);

					$myrow = DB_fetch_row($result);
					if ($myrow[0]>0) {
						$CancelDelete = 1;
						prnMsg( _('Cannot delete this account because it is used by one of the sales GL posting interface records'),'warn');
					} else {
//PREVENT DELETES IF COGS POSTINGS USE THE GL ACCOUNT
						$sql= "SELECT COUNT(*) 
								FROM cogsglpostings 
								WHERE glcode='" . $SelectedAccount ."'";

						$ErrMsg = _('Could not test for existing cost of sales interface codes because');

						$result = DB_query($sql,$db,$ErrMsg);

						$myrow = DB_fetch_row($result);
						if ($myrow[0]>0) {
							$CancelDelete = 1;
							prnMsg(_('Cannot delete this account because it is used by one of the cost of sales GL posting interface records'),'warn');

						} else {
//PREVENT DELETES IF STOCK POSTINGS USE THE GL ACCOUNT
							$sql= "SELECT COUNT(*) FROM stockcategory
									WHERE stockact='" . $SelectedAccount ."'
									OR adjglact='" . $SelectedAccount ."'
									OR purchpricevaract='" . $SelectedAccount ."'
									OR materialuseagevarac='" . $SelectedAccount ."'
									OR wipact='" . $SelectedAccount ."'";

							$Errmsg = _('Could not test for existing stock GL codes because');

							$result = DB_query($sql,$db,$ErrMsg);

							$myrow = DB_fetch_row($result);
							if ($myrow[0]>0) {
								$CancelDelete = 1;
								prnMsg( _('Cannot delete this account because it is used by one of the stock GL posting interface records'),'warn');
							} else {
//PREVENT DELETES IF STOCK POSTINGS USE THE GL ACCOUNT
								$sql= "SELECT COUNT(*) FROM bankaccounts
								WHERE accountcode='" . $SelectedAccount ."'";
								$ErrMsg = _('Could not test for existing bank account GL codes because');

								$result = DB_query($sql,$db,$ErrMsg);

								$myrow = DB_fetch_row($result);
								if ($myrow[0]>0) {
									$CancelDelete = 1;
									prnMsg( _('Cannot delete this account because it is used by one the defined bank accounts'),'warn');
								} else {

									$sql = "DELETE FROM chartdetails WHERE accountcode='" . $SelectedAccount ."'";
									$result = DB_query($sql,$db);
									$sql="DELETE FROM chartmaster WHERE accountcode= '" . $SelectedAccount ."'";
									$result = DB_query($sql,$db);
									prnMsg( _('Account') . ' ' . $SelectedAccount . ' ' . _('has been deleted'),'succes');
								}
							}
						}
					}
				}
			}
		}
	}
}
if (!isset($SelectedAccount) || (isset($_GET['delete']))) {
/* It could still be the second time the page has been run and a record has been selected for modification - SelectedAccount will exist because it was sent with the new call. If its the first time the page has been displayed with no parameters
then none of the above are true and the list of ChartMaster will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/
if(isset($_POST['search']))
{
  $cond.="and (chartmaster.accountcode='".$_POST['searchtext']."' OR accountname like '%".$_POST['searchtext']."%' OR group_ like '%".$_POST['searchtext']."%' OR chartmaster.description like '%".$_POST['searchtext']."%' OR chartmaster.opening_balance like '%".$_POST['searchtext']."%')";
  unset($_GET{'page'});
}
$rec_limit = 10;
$sql = "SELECT accountcode,
			accountname,
			group_,chartmaster.description,chartmaster.opening_balance,chartmaster.opening_balance_type,chartmaster.tax_details,
			CASE WHEN pandl=0 THEN '" . _('Balance Sheet') . "' ELSE '" . _('Profit/Loss') . "' END AS acttype
		FROM chartmaster,
			accountgroups
		WHERE 1=1 and chartmaster.group_=accountgroups.groupname ".$cond."
		ORDER BY chartmaster.accountcode";
$count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";

$retval =DB_query( $count_query, $db );
$row = DB_fetch_array($retval);
$rec_count = $row[0];

if( isset($_GET{'page'} ) )
{
   $page = $_GET{'page'} + 1;
   $offset = $rec_limit * $page ;
}
else
{
   $page = 0;
   $offset = 0;
}
$left_rec = $rec_count - ($page * $rec_limit);

	 $sql = "SELECT accountcode,
			accountname,
			group_,chartmaster.description,chartmaster.opening_balance,chartmaster.opening_balance_type,chartmaster.tax_details,
			CASE WHEN pandl=0 THEN '" . _('Balance Sheet') . "' ELSE '" . _('Profit/Loss') . "' END AS acttype
		FROM chartmaster,
			accountgroups
		WHERE 1=1 and chartmaster.group_=accountgroups.groupname ".$cond."
		ORDER BY chartmaster.accountcode LIMIT $offset, $rec_limit";

	$ErrMsg = _('The chart accounts could not be retrieved because');

	$result = DB_query($sql,$db,$ErrMsg);
	$nu=DB_num_rows($result);
    if(isset($_GET['msg']))
	{
	 echo '<div class="success">'.$_GET['msg'].'</div>';
	}
	echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">GL Account Master</a></div>';
	 if(isset($_REQUEST['search']))
 {
  echo '<div class="searchrecord"> '.$nu.' Record(s) Found. &nbsp;| <a href="GLAccounts.php">View all</a></div>';
  }
	
echo '<div class="tblHeaderLeft"><h1>GL Account Master</h1><span class="addrecord"><a href="addGLAccounts.php"> Add Account</a></span></div><div class="tblHeaderRight"><form name="form" method="post">
<input type="hidden" name="FormID" value="'. $_SESSION['FormID'] .'" /><input type="text" name="searchtext" value="">&nbsp;<input type="submit" name="search" value="Search"></form></div>';
	echo '<table>';
	if($nu>0)
	{
	echo '<thead><tr>
		<th>' . _('Account Code') . '</th>
		<th>' . _('Account Name') . '</th>
		<th>' . _('Account Group') . '</th>
		
		<th  >' . _('Description') . '</th>
		<th width="40px">' . _('Opening Balance') . '</th>
		<th>' . _('Opening Balance Type') . '</th>
		
		<th width="70px">' . _('P/L or B/S') . '</th>
		<th colspan="4" align="center" style="text-align:center;">' . _('Action') . '</th>
		
	</tr></thead>';
	}

	$k=0; //row colour counter

	while ($myrow = DB_fetch_row($result)) {
		if ($k==1){
			echo '<tr class="even">';
			$k=0;
		} else {
			echo '<tr class="odd">';
			$k=1;
		}

  $pag='GLAccountInquiry.php';
	printf("<td align='right'>%s</td>
		<td>%s</td>
		<td width='90'>%s</td>
		<td>%s</td>
		<td align='right'>%s</td>
		<td>%s</td>
		
		<td width='90'>%s</td>
		<td colspan='3' width='330'><a href=\"%s&SelectedAccount=%s\">" . _('Edit') . "</a> | <a href=\"%s&SelectedAccount=%s&delete=1\" onclick=\"return confirm('" . _('Are you sure you wish to delete this account? Additional checks will be performed in any event to ensure data integrity is not compromised.') . "');\">" . _('Delete') . "</a> | <a href=\"%s&Account=%s\">Transaction</a></td>
		</tr>",
		$myrow[0],
		$myrow[1],
		$myrow[2],
		$myrow[3],
		$myrow[4],
		$myrow[5],
	
		$myrow[7],
		$_SERVER['PHP_SELF'] . '?',
		$myrow[0],
		$_SERVER['PHP_SELF'] . '?',
		$myrow[0],
		$pag.'?',
		$myrow[0]);
//echo '<tr><td> <a href="?code='.$myrow['accountcode'].'">Transaction</a></td></tr>';
	}
	//END WHILE LIST LOOP
	echo '<tr><td colspan="8" align="right">';
	 
  
	if($left_rec <= $rec_limit && $page!=0)
{   
   $last = $page-2;
   echo "<a href=\"$_PHP_SELF?page=$last\"> Previous</a>";
}

	else if( $page > 0)
{  
   $last = $page - 2;
      echo "<a href=\"$_PHP_SELF?page=$last\"> Previous </a>&nbsp;  &nbsp;";
   echo "<a href=\"$_PHP_SELF?page=$page\"> Next </a>";
}
 
else if( $page == 0 && $left_rec > $rec_limit)
{   
   echo "<a href=\"$_PHP_SELF?page=$page\"> Next </a>";
}


echo '</td></tr>';
	echo '</table>';
	
}
if ( !isset($_GET['delete']) and (isset($_GET['SelectedAccount']) || isset($_POST['SelectedAccount']))) {

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
		echo '<table >
		<tr class="oddrow"><td align="center"><h2>Edit GL Account</h2></td></tr>
				<tr class="evenrow"><td><div class="left">' . _('Account Code') . ': </div>
					<div class="leftans">' . $_POST['AccountCode'] . '</div></td></tr>';
	

	if (!isset($_POST['AccountName'])) {$_POST['AccountName']='';}
	echo '<tr class="oddrow"><td><div class="left">' . _('Account Name') . ": <span style='color:#FF0000'>*</span></div><div class='right'><input type='Text' size=51 maxlength=45 name='AccountName' value='" . $_POST['AccountName'] . "'></div></td></tr>";

	$sql = 'SELECT groupname FROM accountgroups ORDER BY sequenceintb';
	$result = DB_query($sql, $db);

	echo '<tr class="evenrow"><td><div class="left">' . _('Account Group') . ': <span style="color:#FF0000">*</span></div><div class="right"><select name=Group>';

	while ($myrow = DB_fetch_array($result)){
		if (isset($_POST['Group']) and $myrow[0]==$_POST['Group']){
			echo '<option selected value="';
		} else {
			echo '<option VALUE="';
		}
		echo $myrow[0] . '">' . $myrow[0] . '</option>';
	}

	if (!isset($_GET['SelectedAccount']) or $_GET['SelectedAccount']=='') {
		echo '<script>defaultControl(document.GLAccounts.AccountCode);</script>';
	} else {
		echo '<script>defaultControl(document.GLAccounts.AccountName);</script>';
	}

	echo '</select></div></td></tr>';
	//echo $_POST['opening_balance_type'];
	echo '<tr class="oddrow"><td><div class="left">' . _('Description') . ': <span style="color:#FF0000">*</span></div><div class="right"><textarea name="description" rows="5" cols="28" >' . $_POST['description'] . '</textarea></div></td></tr>';
		echo '<tr class="evenrow"><td><div class="left">' . _('Opening Balance') . ': <span style="color:#FF0000">*</span></div>
					<div class="right"><input type="text" name="opening_balance" size="51" class="number" maxlength="10" value="'.$_POST['opening_balance'].'"/></div></td>
				</tr>';
		echo '<tr class="oddrow"><td><div class="left">' . _('Opening Balance Type') . ': <span style="color:#FF0000">*</span></div>
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
				echo '<tr class="evenrow"><td><div class="left">' . _('Tax details') . ': <span style="color:#FF0000">*</span></div>
					<div class="right">' . _('') . '<input type="text" name="tax_details" size="51" maxlength="25" value="'.$_POST['tax_details'].'"/></div></td>
				</tr>';
				
				echo '<tr class="oddrow"><td align="center"><div class="centre"><a href="GLAccounts.php"><input type="button" name="back" value="Back"/></a>&nbsp;&nbsp;<input type="Submit" name="submit" value="'. _('Save') . '"></div></td></tr>';

	echo '</table>';

	
	echo '</form>';

} //end if record deleted no point displaying form to add record


}

echo '<p>';

if (isset($SelectedAccount) && !(isset($_GET['delete']))) {
	echo '<div class="centre"><a href="' . $_SERVER['PHP_SELF'] . '">' .  _('Show All Accounts') . '</a></div>';
}

echo '<p />';

include('includes/footer.inc');
?>