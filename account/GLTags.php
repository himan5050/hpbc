<?php

/* $Id: GLTags.php 4630 2011-07-14 10:27:29Z daintree $*/

include('includes/session.inc');
$title = _('Maintain General Ledger Tags');

include('includes/header.inc');

if (isset($_GET['SelectedTag'])) {
	if($_GET['Action']=='delete'){
		//first off test there are no transactions created with this tag
		$Result = DB_query("SELECT counterindex 
					FROM gltrans 
					WHERE tag='" . $_GET['SelectedTag'] . "'",$db);
		if (DB_num_rows($Result)>0){
			prnMsg(_('This tag cannot be deleted since there are already general ledger transactions created using it.'),'error');
		} else	{
			$Result = DB_query("DELETE FROM tags WHERE tagref='" . $_GET['SelectedTag'] . "'",$db);
			prnMsg(_('The selected tag has been deleted'),'success');
		}
	} else {
		$sql="SELECT tagref, 
				tagdescription 
			FROM tags 
			WHERE tagref='".$_GET['SelectedTag']."'";
			
		$result= DB_query($sql,$db);
		$myrow = DB_fetch_array($result,$db);
		$ref=$myrow['tagref'];
		$Description=$myrow['tagdescription'];
	}
} else {
	$Description='';
	$_GET['SelectedTag']='';
}

if (isset($_POST['submit'])) {
	$sql = "INSERT INTO tags values(NULL, '".$_POST['Description']."')";
	$result= DB_query($sql,$db);
}

if (isset($_POST['update'])) {
	$sql = "UPDATE tags SET tagdescription='".$_POST['Description']. "' 
		WHERE tagref='".$_POST['reference']."'";
	$result= DB_query($sql,$db);
}
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' .
		_('Print') . '" alt="" />' . ' ' . $title . '</p>';

echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '" name="form">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
echo '<br />
	<table cellspacing="1" cellpadding="2">
	<tr class="oddrow"><td colspan="3" align="center"><font size="3">General Ledger Tags</font></td></tr>
	<tr class="evenrow">';


echo '<td>'. _('Description') . '</td>
	<td><input type="text" size=30 maxlength=30 name="Description" value="'.$Description.'">
	&nbsp;&nbsp;
	<input type="hidden" name="reference" value="'.$_GET['SelectedTag'].'">';

if (isset($_GET['Action']) AND $_GET['Action']=='edit') {
	echo '<input type="submit" name="update" value="' . _('Update') . '" />';
} else {
	echo '<input type="submit" name="submit" value="' . _('Insert') . '" />';
}

echo '</td></tr></table><p></p>';

echo '</form>';

echo '<table class="selection">';
echo '<tr class="evenrow">
		<td align="center"><font size="2">'. _('Tag ID') .'</td>
		<td align="center"><font size="2">'. _('Description'). '</td>
		<td colspan="2"></td>
	  </tr>';

$sql="SELECT tagref, tagdescription FROM tags order by tagref";
$result= DB_query($sql,$db);

while ($myrow = DB_fetch_array($result,$db)){

	
	if ($k==1){
			echo '<tr class="evenrow">';
			$k=0;
		} else {
			echo '<tr class="oddrow">';
			$k++;
		}
	
	echo'<td>' . $myrow['tagref'].'</td>
			<td>' . $myrow['tagdescription'].'</td>
			<td><a href="' . $_SERVER['PHP_SELF'] . '?SelectedTag=' . $myrow['tagref'] . '&Action=edit">' . _('Edit') . '</a></td>
			<td><a href="' . $_SERVER['PHP_SELF'] . '?SelectedTag=' . $myrow['tagref'] . '&Action=delete" onclick="return confirm(\'' . _('Are you sure you wish to delete this GL tag?') . '\');">' . _('Delete') . '</a></td>
		</tr>';
}

echo '</table>';

echo '<script>defaultControl(document.form.Description);</script>';

include('includes/footer.inc');

?>