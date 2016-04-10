<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
   if($_REQUEST['sort']=='basic_pay')
   {
     $reqsort="tour_claim.".$_REQUEST['sort'];
   }
   else
   {
     $reqsort=$_REQUEST['sort'];
   }
  $orderby="order by ".$reqsort." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='emp_id')
{
   if($_REQUEST['order']=='asc')
   {
    $valemp_id="desc";
	$emp_idimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valemp_id="asc";
	  $emp_idimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valemp_id="asc";
 $emp_idimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='employee_name')
{
 if($_REQUEST['order']=='asc')
   {
    $valemployee_name="desc";
	$employee_nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valemployee_name="asc";
	  $employee_nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valemployee_name="asc";
 $employee_nameimage='';
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='basic_pay')
{
 if($_REQUEST['order']=='asc')
   {
    $valbasic_pay="desc";
	$basic_payimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valbasic_pay="asc";
	  $basic_payimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valbasic_pay="asc";
 $basic_payimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='total_amount')
{
 if($_REQUEST['order']=='asc')
   {
    $valtotal_amount="desc";
	$total_amountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valtotal_amount="asc";
	  $total_amountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valtotal_amount="asc";
 $total_amountimage='';
}

$cond='';

if(isset($_POST['search']))
{
  $cond.="and (emp_id='".$_POST['searchtext']."' OR emp_name='".$_POST['searchtext']."' OR tbl_joinings.employee_name like '%".$_POST['searchtext']."%')";
}

$rec_limit = 10;
 $sqll="select emp_id from tour_claim,tbl_joinings where 1=1 ".$cond." and tour_claim.status=1 and tour_claim.voucher_generated!=1 and tour_claim.emp_id=tbl_joinings.employee_id ".$orderby."";
$count_query = "SELECT COUNT(*) FROM (" .$sqll . ") AS count_query";

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
$left_rec = $rec_count - ($page * $rec_limit);

$s="select * from tour_claim,tbl_joinings where 1=1 ".$cond." and tour_claim.status=1 and tour_claim.voucher_generated!=1 and tour_claim.emp_id=tbl_joinings.employee_id ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);
if(isset($_GET['status']))
{
  $sq="update tour_claim set status='".$_GET['status']."' where id='".$_GET['refid']."'";
  $sqq=DB_query($sq,$db);
  header("location:tour_claim_details.php");
}
?>
<script>
function paid(a,b,c,d)
{  
   /*if(c=='journal')
   {
      window.location.href="GLJournal.php?Debit="+a+"&GLManualCode="+b+"&GLCode="+b;
   }
   else if(c=='payment')
   {
     window.location.href="Payments.php?GLAmount="+a+"&GLCode="+b+"&GLManualCode="+b;
   }*/
   if(c=='journal')
   {
      window.location.href="claim_pay.php?Debit="+a+"&GLManualCode="+b+"&GLCode="+b+"&clid="+d+"&type=tour&vou=journal";
   }
   else if(c=='payment')
   {
     window.location.href="claim_pay.php?GLAmount="+a+"&GLCode="+b+"&GLManualCode="+b+"&clid="+d+"&type=tour&vou=payment";
   }
   
}
</script>

<body>


<br/>
<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="tour_claim_approve.php">List of Approved Claims</a></div>
<?php 
 if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='tour_claim_approve.php'>View all</a> </div>";
  }
  ?>
<div class="tblHeaderLeft"><h1>List of Approved Claims</h1></div><div class="tblHeaderRight"><form name="form" method="post" action="">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ;?>" /><input type="text" name="searchtext" value="" />&nbsp;<input type="submit" name="search" value="Search" /></form></div>
<input type="hidden" name="emp_id" id="emp_id" value="<?php echo $valemp_id;?>" />
<input type="hidden" name="employee_name" id="employee_name" value="<?php echo $valemployee_name;?>" />
<input type="hidden" name="basic_pay" id="basic_pay" value="<?php echo $valbasic_pay;?>" />
<input type="hidden" name="total_amount" id="total_amount" value="<?php echo $valtotal_amount;?>" />


<br>
<table>
<tr><th><strong>S. No.</strong></th>
    <th><strong><a href='javascript:void(0)' onClick="sorting('emp_id'");>Employee Id</a></strong> <?php echo $emp_idimage;?></th>
    <th><strong><a href='javascript:void(0)' onClick="sorting('employee_name')";>Employee Name</a></strong> <?php echo $employee_nameimage;?></th>
    <th><strong><a href='javascript:void(0)' onClick="sorting('basic_pay')";>Basic Pay</a></strong> <?php echo $basic_payimage;?></th>
    <th><strong><a href='javascript:void(0)' onClick="sorting('total_amount')";>Total Amount</a></strong> <?php echo $total_amountimage;?></th>   
    <th><strong>Options</strong></th>
  </tr>
  <?php
  $i=0;
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
  while($r=DB_fetch_array($q))
  {  
     $ca="select * from claim_type where id='".$r['claim_type']."'";
	 $caq=DB_query($ca,$db);
	 $car=DB_fetch_array($caq);
	 $acode=$car['account'];
  
    if($i==1)
	{
	  $cl="even";
	  $i=0;
	}
	else
	{
	  $cl="odd";
	  $i=1; 
	 
	}
  ?>
  <tr class="<?php echo $cl?>">
  <td><?php echo $nn;?></td>
    <td><?php echo $r['emp_id'];?></td>
    <td><?php echo $r['employee_name'];?></td>
    <td><?php echo round($r['basic_pay']);?></td>
    <td><?php echo round($r['total_amount']);?></td>
    <td><select name="voucher_type" onChange="paid(<?php echo $r['total_amount']?>,<?php echo $car['account']?>,this.value,<?php echo $r['id']?>)">
    <option value="">--Select Type--</option>
    <option value="journal">Journal</option>
    <option value="payment">Payment</option>
    </select>
    
    
   <!-- <a href="tour_claim_full_detail.php?account=<?php echo $acode;?>">View Detail</a>--></td>
  </tr>
  <?php
	   $nn++;
  }
  ?>
 </table><div class="paging">
  <?php 
  //echo $topage;
  	  for($nn=1;$nn<=$topage;$nn++)
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



?>
</div>

<?php
include('includes/footer.inc');
?>

<script type="text/javascript">
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
 window.location.href="tour_claim_approve.php?sort="+a+"&order="+order;
 
}
</script>