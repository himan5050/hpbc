<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

$role = getRole($_SESSION['uid'],$db);
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
  $uni=explode('-',$_POST['searchtext']);
  
  $cond.="and (emp_id like '%".$_POST['searchtext']."%' OR emp_name='".$_POST['searchtext']."' OR tbl_joinings.employee_name like '%".$_POST['searchtext']."%' OR id = '".$uni[1]."' OR total_amount like '%".$_POST['searchtext']."%')";
}

$rec_limit = 10;

$of="select current_officeid from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$ofq=DB_query($of,$db);
$ofr=DB_fetch_array($ofq);

 $ur="select rid from users_roles where uid='".$_SESSION['uid']."'";
		$urq=DB_query($ur,$db);
		$urr=DB_fetch_array($urq);
		$usrid=$urr['rid'];


if($usrid==13)
{
 $ncod="and tour_claim.office='".$ofr['current_officeid']."'";	
}

 $sql = "SELECT count(id) FROM tour_claim,tbl_joinings where 1=1 ".$cond." and (status='0' or status='3') and tour_claim.emp_id=tbl_joinings.employee_id $ncod and tour_claim.reportedto=".$usrid."";
$retval =DB_query($sql, $db);

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



  $s="select * from tour_claim,tbl_joinings where 1=1 ".$cond." and (status='0' or status='3') and tour_claim.emp_id=tbl_joinings.employee_id $ncod and tour_claim.reportedto=".$usrid." ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);
?>
<script type="text/javascript">
function change(a,b)
{
 
 window.location.href="tour_claim_details.php?status="+a+"&refid="+b;
}
</script>

<body>

<div class="breadcrumb"><a href="/<?php echo $u[1]; ?>">Home</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">List of Claims for Approval</a></div>
<?php 
 if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='tour_claim_details.php'>View all</a> </div>";
  }
  ?>
  <?php 
 if(isset($_REQUEST['msg']))
 {
  echo "<div><span style='color:#0F0'>".$_REQUEST['msg']."</span></div>";
  }
  ?>
<div class="tblHeaderLeft"><h1>List of Claims for Approval</h1><?php if($role !=6) echo '<span class="addrecord"><a href="tour_claim_user.php">My Claims</a></span>'; ?></div><div class="tblHeaderRight"><form name="form" method="post" action="">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID']; ?>" /><input type="text" name="searchtext" >&nbsp;<input type="submit" name="search" value="Search" /></div>
<input type="hidden" name="basic_pay" id="basic_pay" value="<?php echo $valbasic_pay;?>" />
<input type="hidden" name="employee_name" id="employee_name" value="<?php echo $valemployee_name;?>" />
<input type="hidden" name="total_amount" id="total_amount" value="<?php echo $valtotal_amount;?>" />
<table>
  <tr>
   <th><strong>S. No.</strong></th>
   <th><strong>Claim Id</strong></th>
    <th><strong>Employee Id</strong></th>
    <th><a href='javascript:void(0)' onClick="sorting('employee_name')";><strong>Employee Name</strong></a> <?php echo $employee_nameimage;?></th>
    <th><a href='javascript:void(0)' onClick="sorting('basic_pay')";><strong>Basic Pay</strong></a> <?php echo $basic_payimage;?></th>
    <th><a href='javascript:void(0)' onClick="sorting('total_amount')";><strong>Total Amount</strong></a> <?php echo $total_amountimage;?></th>
   
    <th><strong>Options</strong></th>
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
   <td>TA-<?php echo $r['id'];?></td>
    <td><?php echo $r['emp_id'];?></td>
    <td><?php echo $r['employee_name'];?></td>
    <td><?php echo round($r['basic_pay']);?></td>
    <td><?php echo round($r['total_amount']);?></td>
    <td><a href="tour_claim_full_detail.php?id=<?php echo $r['id'];?>">View Detail</a></td>
  </tr>
  <?php
  $i++;
  $nn++;
  }
  ?>
 </table><div class="paging"><?php 
  /*for($nn=1;$nn<=$topage;$nn++)
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
 window.location.href="tour_claim_details.php?sort="+a+"&order="+order;
 
}
</script>