<?php
include('includes/session.inc');
$title = _('Add Item');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';

$s="select rid from users_roles where uid='".$_SESSION['uid']."'";
$q=DB_query($s,$db);
$r=DB_fetch_array($q);

?><div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Audit</a></div>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table>
<tr>
<td>
<?php 
if($r['rid']=='9')
{
 header("location:audit_report_approvelist.php");

}
?>

<?php 
if($r['rid']=='7')
{
 header("location:audit_mylist.php");

}
?>

<?php 
if($r['rid']=='42')
{
 header("location:audit_correctivelist.php");

}
?>
 </td>
</tr>
</table>
</form>
<?php include('includes/footer.inc'); ?>
