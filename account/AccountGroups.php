<?php

/* $Id: AccountGroups.php 4622 2011-07-03 04:33:19Z daintree $*/

include('includes/session.inc');

$title = _('Account Groups');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

$bro="select current_officeid from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$broq=DB_query($bro,$db);
$bror=DB_fetch_array($broq);
$branchoffice=$bror['current_officeid'];

echo '<div class="breadcrumb">Home &raquo; <a href="AccountGroups.php">Account Groups</a> ';
if ((isset($_POST['SelectedAccountGroup']) OR isset($_GET['SelectedAccountGroup'])) AND !isset($_REQUEST['delete'])) {
if(!isset($_REQUEST['delete'])) { echo'&raquo; <a >Edit Account Group</a>';} echo '</div>';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='groupname')
{
   if($_REQUEST['order']=='asc')
   {
    $valgroupname="desc";
	$groupnameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valgroupname="asc";
	  $groupnameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valgroupname="asc";
 $groupnameimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='sectionname')
{
 if($_REQUEST['order']=='asc')
   {
    $valsectionname="desc";
	$sectionnameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valsectionname="asc";
	  $sectionnameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valsectionname="asc";
 $sectionnameimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='sequenceintb')
{
 if($_REQUEST['order']=='asc')
   {
    $valsequenceintb="desc";
	$sequenceintbimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valsequenceintb="asc";
	  $sequenceintbimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valsequenceintb="asc";
  $sequenceintbimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='pandl')
{
 if($_REQUEST['order']=='asc')
   {
    $valpandl="desc";
	$pandlimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valpandl="asc";
	  $pandlimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valpandl="asc";
 $pandlimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='parentgroupname')
{
 if($_REQUEST['order']=='asc')
   {
    $valparentgroupname="desc";
	$parentgroupnameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valparentgroupname="asc";
	  $parentgroupnameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valparentgroupname="asc";
 $parentgroupnameimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='description')
{
 if($_REQUEST['order']=='asc')
   {
    $valdescription="desc";
	$descriptionimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valdescription="asc";
	  $descriptionimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valdescription="asc";
 $descriptionimage='';
}

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
	if (mb_strlen($_POST['GroupName'])==0){
		$InputError = 1;
		prnMsg( _('The account group name must be at least one character long'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
	}
	if (mb_strlen($_POST['description'])==0){
		$InputError = 1;
		prnMsg( _('The Description must be at least one character long'),'error');
		$Errors[$i] = 'GroupName';
		$i++;
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

		$msg = _('Group '.$_POST['SelectedAccountGroup'].' Updated');
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
		echo '<div class="error">' . _('There are') . ' ' . $myrow[0] . ' ' . _('general ledger accounts that refer to this account group') . '</div>';

	} else {
		$sql = "SELECT COUNT(groupname) FROM accountgroups WHERE parentgroupname = '" . $_GET['SelectedAccountGroup'] . "'";
        $ErrMsg = _('An error occurred in retrieving the parent group information');
        $DbgMsg = _('The SQL that was used to retrieve the information was');
		$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			prnMsg( _('Cannot delete this account group because it is a parent account group of other account group(s)'),'warn');
			echo '<div class="error">' . _('There are') . ' ' . $myrow[0] . ' ' . _('account groups that have this group as its/there parent account group') . '</div>';
		} else {
			$sql="DELETE FROM accountgroups WHERE groupname='" . $_GET['SelectedAccountGroup'] . "'";
            $ErrMsg = _('An error occurred in deleting the account group');
            $DbgMsg = _('The SQL that was used to delete the account group was');
			$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
			prnMsg( $_GET['SelectedAccountGroup'] . ' ' . _('group has been deleted') . '!','success');
		}

	} //end if account group used in GL accounts

}

if ((!isset($_GET['SelectedAccountGroup']) and !isset($_POST['SelectedAccountGroup'])) OR isset($_REQUEST['delete'])) {

/* An account group could be posted when one has been edited and is being updated or GOT when selected for modification
 SelectedAccountGroup will exist because it was sent with the page in a GET .
 If its the first time the page has been displayed with no parameters
then none of the above are true and the list of account groups will be displayed with
links to delete or edit each. These will call the same page again and allow update/input
or deletion of the records*/
if(isset($_POST['searchtext']))
{
  $cond.="and (sectionname like '%".$_POST['searchtext']."%' OR groupname like '%".$_POST['searchtext']."%' OR description like '%".$_POST['searchtext']."%' OR sequenceintb like '%".$_POST['searchtext']."%')";
  unset($_GET{'page'});
}
	$rec_limit = 10;
 $sql = "SELECT groupname,
			sectionname,
			sequenceintb,
			pandl,
			parentgroupname,
			description
		FROM accountgroups
		LEFT JOIN accountsection ON sectionid = sectioninaccounts where 1=1  ".$cond."
		";
//$retval =DB_query( $sql, $db );

$count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";
$retval =DB_query( $count_query, $db );
$row = DB_fetch_array($retval);
 $rec_count = $row[0];

$topage=ceil($rec_count/$rec_limit);
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
 $rec_count;
 $left_rec = $rec_count - ($page * $rec_limit);
	
	 $sql = "SELECT groupname,
			sectionname,
			sequenceintb,
			pandl,
			parentgroupname,
			description
		FROM accountgroups
		LEFT JOIN accountsection ON sectionid = sectioninaccounts where 1=1  ".$cond." ".$orderby."  LIMIT $offset, $rec_limit";

    $DbgMsg = _('The sql that was used to retrieve the account group information was ');
	$ErrMsg = _('Could not get account groups because');
	$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);
	$nu=DB_num_rows($result);
  
	/*echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="AccountGroups.php">Account Groups</a></div>';*/
	if(isset($_GET['msg']))
  {
    echo '<div class="success">'.$_GET['msg'].'</div>';
  }
	if(isset($_REQUEST['searchtext']))
 {
  echo "<div class='searchrecord'> ".$nu." Record(s) Found. &nbsp;| <a href='AccountGroups.php'>View all</a></div>";
  }

echo '<div class="tblHeaderLeft"><h1>Account Groups</h1><span class="addrecord"><a href="addaccountgroup.php">Add Account Group</a></span></div><div class="tblHeaderRight"><form name="form" method="post">
<input type="hidden" name="FormID" value="'. $_SESSION['FormID'] .'" /><input type="text" name="searchtext" value="">&nbsp;<input type="submit" name="search" value="Search"></form></div>
';
echo '<input type="hidden" name="groupname" id="groupname" value="'.$valgroupname.'">
<input type="hidden" name="sectionname" id="sectionname" value="'.$valsectionname.'">
<input type="hidden" name="sequenceintb" id="sequenceintb" value="'.$valsequenceintb.'">
<input type="hidden" name="pandl" id="pandl" value="'.$valpandl.'">
<input type="hidden" name="parentgroupname" id="parentgroupname" value="'.$valparentgroupname.'">
<input type="hidden" name="description" id="description" value="'.$valdescription.'">';
if($nu>0)
{
	echo '<table><tr>
	    <th>' . _('S. No.') . '</th>
		<th><a href="javascript:void(0)" onclick=sorting("groupname");>' . _('Group Name') . '</a> '.$groupnameimage.'</th>
		<th><a href="javascript:void(0)" onclick=sorting("sectionname");>' . _('Section') . '</a> '.$sectionnameimage.'</th>
		<th><a href="javascript:void(0)" onclick=sorting("sequenceintb");>' . _('Sequence In TB') . '</a> '.$sequenceintbimage.'</th>
		<th><a href="javascript:void(0)" onclick=sorting("pandl");>' . _('Profit and Loss') . '</a> '.$pandlimage.'</th>
		<th><a href="javascript:void(0)" onclick=sorting("parentgroupname");>' . _('Parent Group') . '</a> '.$parentgroupnameimage.'</th>
		<th><a href="javascript:void(0)" onclick=sorting("description");>' . _('Description') . '</a> '.$descriptionimage.'</th>
		<th colspan="2"  style="text-align:center;">' . _('Action') . '</th>
		</tr>';

	$k=0; //row colour counter
	 if(isset($_GET['page']) && $_GET['page']>1)
	{
	  $pp=($_GET['page']*10)+11;
	}
	else if(isset($_GET['page']) && $_GET['page']==0)
	{
	  $pp=11;
	}
	else if(isset($_GET['page']) && $_GET['page']==1)
	{
	  $pp=21;
	}
	else
	{
	  $pp=1;
	}
    $nn=1*($pp);
	while ($myrow = DB_fetch_row($result)) {

		if ($k==1){
			echo '<tr class="even">';
			$k=0;
		} else {
			echo '<tr class="odd">';
			$k++;
		}

		switch ($myrow[3]) {
		case -1:
			$PandLText=_('Yes');
			break;
		case 1:
			$PandLText=_('Yes');
			break;
		case 0:
			$PandLText=_('No');
			break;
		} //end of switch statement

		echo '<td>'.($nn).'</td>
		    <td>' . ucwords(htmlentities($myrow[0], ENT_QUOTES,'UTF-8')) . '</td>
			<td width="100">' . ucwords($myrow[1]) . '</td>
			<td align="right">' . $myrow[2] . '</td>
			<td>' . $PandLText . '</td>
			<td>' . $myrow[4] . '</td>
			<td>' . ucwords($myrow[5]) . '</td>';
		echo '<td width="100"><a href="' . $_SERVER['PHP_SELF'] . '?SelectedAccountGroup=' . htmlentities($myrow[0], ENT_QUOTES,'UTF-8') . '">' . _('Edit') . '</a> &nbsp;|&nbsp;<a href="' . $_SERVER['PHP_SELF'] . '?SelectedAccountGroup=' . htmlentities($myrow[0], ENT_QUOTES,'UTF-8') . '&amp;delete=1" onclick="return confirm(\'' . _('Are you sure you wish to delete this account group?') . '\');">' . _('Delete') .'</a></td>';
		echo '</tr>';
  $nn++;
	} //END WHILE LIST LOOP
	echo '</table><br><div class="paging">';
	
	/* for($nn=1;$nn<=$topage;$nn++)
	 {
	   if(isset($_GET['page']))
	   {
		   if($_GET['page']==($nn-2))
		   {
			 $pg="<strong>".$nn."</strong>";
			}
			else
			{
			  $pg=$nn;
			}
		}
		else
		{
		  if($nn==1)
		  {
		   $pg="<strong>".$nn."</strong>";
		  }
		  else
		  {
		    $pg=$nn;
		  }
		}	
	      $datap .="<a href=\"$_PHP_SELF?page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	  
	  
	 }*/
	  if(isset($_GET['page']) && $_GET['page'] >3){
   $nn = $_GET['page']-3;
   for($nn;$nn<=($_GET['page']+3);$nn++){
      
	   if($_GET['page']==($nn-2))
		   {
			 $pg="<strong>".$nn."</strong>";
			}
			else
			{
			  $pg=$nn;
			}
	      //$pg = $nn;
		
		  $datap .="<a href=\"$_PHP_SELF?page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	
   }
     if(($_GET['page']+ 2) != $topage){
		    $datap .= '..';
		  }
 }else{
    if($topage > 7){
	   $tp = 7;
	}else if($topage < 7 && $topage > 1){
	   $tp = $topage;
	}
     for($nn=1;$nn<=$tp;$nn++){
	  if(isset($_GET['page']))
	   {
		   if($_GET['page']==($nn-2))
		   {
			 $pg="<strong>".$nn."</strong>";
			}
			else
			{
			  $pg=$nn;
			}
		}
		else
		{
		  if($nn==1)
		  {
		   $pg="<strong>".$nn."</strong>";
		  }
		  else
		  {
		    $pg=$nn;
		  }
		}	
      $datap .="<a href=\"$_PHP_SELF?page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	 } 
 }
	
if($left_rec <= $rec_limit && $page!=0)
{   
   $last = $page-2;
   echo "<a href=\"$_PHP_SELF?order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous</a>";
}

	else if( $page > 0)
{  
   $last = $page - 2;
      echo "<a href=\"$_PHP_SELF?order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous </a>&nbsp;  &nbsp;";
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}
 
else if( $page == 0 && $left_rec > $rec_limit)
{   
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}

	echo '</div></div>';
	echo '';
} //end of ifs and buts!
}

if ((isset($_POST['SelectedAccountGroup']) OR isset($_GET['SelectedAccountGroup'])) AND !isset($_REQUEST['delete'])) {
		 //if(!isset($_REQUEST['delete'])) { echo'&raquo; <a >Edit Account Group</a>';} echo '</div>';
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
			//prnMsg( _('The account group name does not exist in the database'),'error');
			
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

		echo '<table cellspacing="1" cellpadding="2"><tr  class="oddrow"><td colspan="2" align="center"><h2>'.'Edit Account Group' . '</h2></td></tr><tr  class="evenrow"><td><div class="left">';
		echo '<input type="hidden" name="SelectedAccountGroup" value="' . $_GET['SelectedAccountGroup'] . '" />';
		echo '<input type="hidden" name="GroupName" value="' . $_POST['GroupName'] . '" />';

		echo _('Account Group') . ':' . '</div>';

		echo '<div class="leftans">' . $_POST['GroupName'] . '</div></tr>';

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

		echo '<table cellpadding="2" cellspacing="1"><tr class="oddrow"><td colspan="2" align="center"><h2>Edit Account Group</h2></td></tr><tr class="evenrow">';
		echo '<input  type="hidden" name="SelectedAccountGroup" value="' . $_POST['SelectedAccountGroup'] . '" />';
		echo '<td><div class="left">'._('Group Name') . ':' . ' <span style="color:#FF0000">*</span></div><div class="leftans">
		<input tabindex="1" ' . (in_array('GroupName',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="GroupName" size="50" maxlength="45" value="' . $_POST['GroupName'] . '" /></div></td></tr>';
	}
	echo '<tr class="oddrow"><td><div class="left">' . _('Parent Group') . ':' . ' <span style="color:#FF0000">*</span></div>
			<div class="right"><select tabindex="2" ' . (in_array('ParentGroupName',$Errors) ?  'class="selecterror"' : '' ) . '  name="ParentGroupName">';

	$sql = "SELECT groupname FROM accountgroups order by groupname";
	$groupresult = DB_query($sql, $db,$ErrMsg,$DbgMsg);
	if (!isset($_POST['ParentGroupName'])){
		echo '<option selected value="">' ._('Top Level Group').'</option>';
	} else {
		echo '<option value="">' ._('Top Level Group').'</option>';
	}

	while ( $grouprow = DB_fetch_array($groupresult) ) {

		if (isset($_POST['ParentGroupName']) and $_POST['ParentGroupName']==$grouprow['groupname']) {
			echo '<option selected="selected" value="'.htmlentities($grouprow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlentities($grouprow['groupname'], ENT_QUOTES,'UTF-8').'</option>';
		} else {
			echo '<option value="'.htmlentities($grouprow['groupname'], ENT_QUOTES,'UTF-8').'">' .htmlentities($grouprow['groupname'], ENT_QUOTES,'UTF-8').'</option>';
		}
	}
	echo '</select>';
	echo '</div></td></tr>';

	echo '<tr class="evenrow"><td><div class="left">' . _('Section In Accounts') . ':' . ' <span style="color:#FF0000">*</span></div>
	<div class="right"><select tabindex="3" ' . (in_array('SectionInAccounts',$Errors) ?  'class="selecterror"' : '' ) .
      '  name="SectionInAccounts">';

	$sql = "SELECT sectionid, sectionname FROM accountsection ORDER BY sectionname,sectionid";
	$secresult = DB_query($sql, $db,$ErrMsg,$DbgMsg);
	while( $secrow = DB_fetch_array($secresult) ) {
		if ($_POST['SectionInAccounts']==$secrow['sectionid']) {
			echo '<option selected="selected" value="'.$secrow['sectionid'].'">'.$secrow['sectionname'].' ('.$secrow['sectionid'].')</option>';
		} else {
			echo '<option value="'.$secrow['sectionid'].'">'.$secrow['sectionname'].' ('.$secrow['sectionid'].')</option>';
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
		' type="text" maxlength="5" name="SequenceInTB" class="number"
		 value="' . $_POST['SequenceInTB'] . '" /></div></td></tr>';
	echo '<tr class="oddrow"><td><div class="left">' . _('Description') . ': <span style="color:#FF0000">*</span></div><div class="right"><input type="text" name="description" size="50" maxlength="200" value="'.$_POST['description'].'" onkeypress="return alphanumeric(event)"></div></td></tr>';
	echo '<tr class="evenrow"><td colspan="2" align="center"><div class="centre"><input tabindex="7" type="button" name="back" value="Back"  ONCLICK="clickroute();"><input tabindex="8" type="submit" name="submit" value="' . _('Save') . '" /></div></td></tr>';

	echo '</table>';

	echo '<script  type="text/javascript">defaultControl(document.forms[0].GroupName);</script>';

	echo '</form>';

} //end if record deleted no point displaying form to add record
include('includes/footer.inc');
?>
<script type="text/javascript">
function clickroute() {
	window.location.href='AccountGroups.php';
	}
function sorting(a)
{  
 
var order=document.getElementById(a).value;

var corder;
if(order=='asc')
 {
   corder='desc';
 }
 else if(order=='desc')
 {
  corder='asc';
 }
  //alert(order);
 document.getElementById(a).value=corder;
//alert(document.getElementById(a).value);
 window.location.href="AccountGroups.php?sort="+a+"&order="+order;
 
}
</script>