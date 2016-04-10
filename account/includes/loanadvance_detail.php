<?php
include('includes/session.inc');
$title = _('');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$cond='';
if(isset($_POST['search']))
{
  $cond.="and (employee_id like '%".$_POST['search']."%' OR amount like '%".$_POST['search']."%' OR tbl_joinings.employee_name like '%".$_POST['search']."%')";
}

$of="select current_officeid from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$ofq=DB_query($of,$db);
$ofr=DB_fetch_array($ofq);
$rec_limit = 10;

$sql = "SELECT count(id ) FROM loanadvance,tbl_joinings where 1=1 ".$cond." and (status='0' or querystatus='0') and loanadvance.empid=tbl_joinings.program_uid ";
$retval =DB_query( $sql, $db );

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


 $s="select * from loanadvance,tbl_joinings where 1=1 ".$cond." and (status='0' or querystatus='0') and loanadvance.empid=tbl_joinings.program_uid LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

?>


<body>

<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF']; ?>">List of Loan for Approval</a></div>
 <?php
  $i=1;
   if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='loanadvance_detail.php'>View all</a> </div>";
  }
?>
<div class="tblHeaderLeft"><h1>List of Loan for Approval</h1></div><div class="tblHeaderRight"><input type="text" name="search" value="">&nbsp;<input type="submit" name="go" value="Search"></div>
<table>
</tr>
  <tr>
    <th>Employee Id</th>
    <th>Employee Name</th>
    
    <th>Loan Amount</th>
   
    <th>Options</th>
  </tr>
 <?php
  while($r=DB_fetch_array($q))
  {    
    
	
    if(($i%2)==0)
	{
	  $cl="even";
	}
	else
	{
	  $cl="odd";
	}
  ?>
  <tr class="<?php echo $cl?>">
    <td><?php echo $r['employee_id'];?></td>
    <td><?php echo $r['employee_name'];?></td>
   
    <td><?php echo $r['amount'];?></td>
    <td><?php if($r['approvestatus']==1) {?><a href="loanadvance_full_detail.php?id=<?php echo $r['id'];?>">Approved</a><?php } else {?><a href="loanadvance_full_detail.php?id=<?php echo $r['id'];?>">View Detail</a><?php } ?></td>
  </tr>
  <?php
  $i++;
  }
  ?>
  <tr><td colspan="5" align="right"><?php 
  
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


?>

</td></tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
</body>
</html>
