<?php
include('includes/session.inc');
$title = _('');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$cond='';


$rec_limit = 10;

$sql = "SELECT count(id ) FROM loanadvance where  empid='".$_SESSION['uid']."'";
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

$emi="select employee_id from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$emiq=DB_query($emi,$db);
$emir=DB_fetch_array($emiq);
 $s="select * from loanadvance where empid='".$_SESSION['uid']."' LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);


?>
<!--<script>
function change(a,b)
{
 
 window.location.href="medical_claim_details.php?status="+a+"&refid="+b;
}
</script>-->

<body>

<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">List of My Loan</a></div>

<div class="tblHeaderLeft"><h1>My Loan</h1><span class="addrecord"><a href="loanapplication.php"> Add Loan Application</a></span></div><div class="tblHeaderRight"></div>
<table>

  <tr>
    <th width="109"><strong>Description</strong></th>
    <th width="126"><strong>Period of Loan</strong></th>
    <th width="140"><strong>Date</strong></th>
    <th width="86"><strong>Loan Amount</strong></th>   
    <th width="63"><strong>Status</strong></th>
    <th width="130"><strong>Option</strong></th>
  </tr>
  <?php
  $i=1;
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
    <td><?php echo $r['description'];?></td>
    <td><?php echo $r['period'];?> Months</td>
    <td><?php echo date('d-m-Y',$r['date']);?></td>
    <td><?php echo $r['amount'];?></td>
     <td><?php 
	 if($r['status']==0)
	 {
	   echo "Pending";
	 }
	 else if($r['status']==1)
	 {
	   echo "Approved";
	 }
	 else if($r['status']==2)
	 {
	   echo "Rejected";
	 }
	 else if($r['status']==3)
	 {
	   echo "Queried";
	 }
	 
	 ?></td>
     <td> <a href="loanadvance_user_detail.php?id=<?php echo $r['id'];?>">View</a> </td>
  </tr>
  <?php
  $i++;
  }
  ?>
  <tr><td colspan="6" align="right"><?php 
  
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
