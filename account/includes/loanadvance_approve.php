<?php
include('includes/session.inc');
$title = _('');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

$cond='';
if(isset($_POST['search']))
{
  $cond.="and ( amount like '%".$_POST['search']."%' OR tbl_joinings.employee_name like '%".$_POST['search']."%')";
}

 $s="select * from loanadvance,tbl_joinings where 1=1 ".$cond." and approvestatus='1' and voucher_generated!='1' and loanadvance.empid=tbl_joinings.program_uid";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

?>
<script>
function paid(a,c,d)
{  
  var b='NULL';
   if(c=='journal')
   {
      window.location.href="claim_pay.php?Debit="+a+"&GLManualCode="+b+"&GLCode="+b+"&clid="+d+"&type=loanadvance&vou=journal";
   }
   else if(c=='payment')
   {
     window.location.href="claim_pay.php?GLAmount="+a+"&GLCode="+b+"&GLManualCode="+b+"&clid="+d+"&type=loanadvance&vou=payment";
   }
   
}
</script>

<body>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">List of Approved Claims</a></div>
<?php 
 if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='loanadvance_approve.php'>View all</a> </div>";
  }
  ?>
<div class="tblHeaderLeft"><h1>List of Approved Claims</h1></div><div class="tblHeaderRight"><input type="text" name="search" value="">&nbsp;<input type="submit" name="go" value="Search"></div>
<table cellspacing="2" cellpadding="1" border="0" class="form-container">
  <tr>
    <th width="109"><strong>Employee Id</strong></th>
    <th width="126"><strong>Employee Name</strong></th>
    <th width="125"><strong>Section</strong></th>
    <th width="101"><strong>Total Loan</strong></th>
    <th width="101"><strong>Installment</strong></th>
    <th width="63"><strong>Options</strong></th>
  </tr>
  <?php
  $i=1;
  while($r=DB_fetch_array($q))
  {  
     $ca="select * from claim_type where id='".$r['claim_type']."'";
	 $caq=DB_query($ca,$db);
	 $car=DB_fetch_array($caq);
	 $acode=$car['account'];
  
   $un="select employee_name from tbl_joinings where employee_id='".$r['emp_id']."'";
	$unq=DB_query($un,$db);
	$unr=DB_fetch_array($unq);
  
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
    <td><?php echo $r['section'];?></td>
    <td><?php echo $r['amount'];?></td>
    <td><?php echo $r['monthlyinstallment'];?></td>
    <td><select name="voucher_type" onChange="paid(<?php echo $r['amount']?>,this.value,<?php echo $r['id']?>)">
    <option value="">--Select Type--</option>
    <option value="journal">Journal</option>
    <option value="payment">Payment</option>
    </select>
    
    
   <!-- <a href="tour_claim_full_detail.php?account=<?php echo $acode;?>">View Detail</a>--></td>
  </tr>
  <?php
  }
  ?>
</table>
</form>
<?php
include("includes/footer.inc");
?>
</body>
</html>
