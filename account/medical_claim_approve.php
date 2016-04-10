<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
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

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='designation')
{
 if($_REQUEST['order']=='asc')
   {
    $valdesignation="desc";
	$designationimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valdesignation="asc";
	  $designationimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valdesignation="asc";
 $designationimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='tot_claim')
{
 if($_REQUEST['order']=='asc')
   {
    $valtot_claim="desc";
	$tot_claimimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valtot_claim="asc";
	  $tot_claimimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valtot_claim="asc";
 $tot_claimimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='approvedamount')
{
 if($_REQUEST['order']=='asc')
   {
    $valapprovedamount="desc";
	$approvedamountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valapprovedamount="asc";
	  $approvedamountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valapprovedamount="asc";
 $approvedamountimage='';
}


$cond='';
if(isset($_POST['search']))
{
  $cond.="and (emp_id like '%".$_POST['search']."%' OR net_amount like '%".$_POST['search']."%' OR tbl_joinings.employee_name like '%".$_POST['search']."%')";
}

$rec_limit = 10;
 $sqll="select emp_id from medical_claim,tbl_joinings where 1=1 ".$cond." and status='1' and voucher_generated!='1' and medical_claim.emp_id=tbl_joinings.employee_id ".$orderby."";
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

 $s="select * from medical_claim,tbl_joinings where 1=1 ".$cond." and status='1' and voucher_generated!='1' and medical_claim.emp_id=tbl_joinings.employee_id ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);
if(isset($_GET['status']))
{
  $sq="update medical_claim set status='".$_GET['status']."' where id='".$_GET['refid']."'";
  $sqq=DB_query($sq,$db);
  header("location:medical_claim_details.php");
}
?>
<script type="text/javascript">
function paid(a,b,c,d)
{  

   if(c=='journal')
   {
      window.location.href="claim_pay.php?Debit="+a+"&GLManualCode="+b+"&GLCode="+b+"&clid="+d+"&type=medical&vou=journal";
   }
   else if(c=='payment')
   {
     window.location.href="claim_pay.php?GLAmount="+a+"&GLCode="+b+"&GLManualCode="+b+"&clid="+d+"&type=medical&vou=payment";
   }
   
}
</script>

<body>
<form name="form" method="post" action="">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<input type="hidden" name="emp_id" id="emp_id" value="<?php echo $valemp_id;?>" />
<input type="hidden" name="employee_name" id="employee_name" value="<?php echo $valemployee_name;?>" />
<input type="hidden" name="designation" id="designation" value="<?php echo $valdesignation;?>" />
<input type="hidden" name="tot_claim" id="tot_claim" value="<?php echo $valtot_claim;?>" />
<input type="hidden" name="approvedamount" id="approvedamount" value="<?php echo $valapprovedamount;?>" />

<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">List of Approved Claims</a></div>
<?php 
 if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='medical_claim_approve.php'>View all</a> </div>";
  }
  ?>
<div class="tblHeaderLeft"><h1>List of Approved Claims</h1></div><div class="tblHeaderRight"><input type="text" name="search" value="" />&nbsp;<input type="submit" name="go" value="Search" /></div>
<table cellspacing="2" cellpadding="1" border="0" class="form-container">
  <tr>
   <th><strong>S. No.</strong></th>
   <th><strong>Employee Id</strong></th>
    <th><strong><a href='javascript:void(0)' onClick="sorting('employee_name');">Employee Name</a></strong> <?php echo $employee_nameimage;?></th>
    <!--<th><strong><a href='javascript:void(0)' onClick="sorting('designation');">Designation</a></strong> <?php echo $designationimage;?></th>-->
    <th><strong><a href='javascript:void(0)' onClick="sorting('tot_claim');">Total Claim</a></strong> <?php echo $tot_claimimage;?></th>
    <th><strong><a href='javascript:void(0)' onClick="sorting('approvedamount');">Approved Amount</a></strong> <?php echo $approvedamountimage;?></th>
    <th><strong>Action</strong></th>
  </tr>
  <?php
  $i=1;
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
  
   $un="select lookup_name from tbl_lookups where lookup_id='".$r['designation']."'";
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
  <td><?php echo $nn;?></td>
    <td><?php echo $r['emp_id'];?></td>
    <td><?php echo $r['employee_name'];?></td>
   <!-- <td><?php echo $unr['lookup_name'];?></td>-->
    <td><?php echo round($r['tot_claim']);?></td>
    <td><?php echo round($r['approvedamount']);?></td>
    <td><select name="voucher_type" onChange="paid(<?php echo $r['approvedamount']?>,<?php echo $car['account']?>,this.value,<?php echo $r['id']?>)">
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
</form>
<?php
include("includes/footer.inc");
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
 window.location.href="medical_claim_approve.php?sort="+a+"&order="+order;
 
}
</script>