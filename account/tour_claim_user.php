<?php

include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='purpose_journey')
{
   if($_REQUEST['order']=='asc')
   {
    $valpurpose_journey="desc";
	$purpose_journeyimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valpurpose_journey="asc";
	  $purpose_journeyimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valpurpose_journey="asc";
 $purpose_journeyimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='date')
{
 if($_REQUEST['order']=='asc')
   {
    $valdate="desc";
	$dateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valdate="asc";
	  $dateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valdate="asc";
 $dateimage='';
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

if(isset($_POST['search']) && $_POST['search']!='')
{ 
   $uni=explode('-',$_POST['search']);
   $da=substr(strtotime($_POST['search']),0,5);
     if($da!='')
	 {
		 $con="OR date like '%".substr(strtotime($_POST['search']),0,5)."%'";
	 }
  $cond.="and (purpose_journey like '%".$_POST['search']."%' OR total_amount like '%".$_POST['search']."%' OR id = '".$uni[1]."' $con)";
}
if(isset($_POST['statustype']) && $_POST['statustype']!='')
{
  $cond.="and (status='".$_POST['statustype']."')";
}

$rec_limit = 10;

$emi="select employee_id from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$emiq=DB_query($emi,$db);
$emir=DB_fetch_array($emiq);
//$sql = "SELECT count(id ) FROM tour_claim where (status='0' or status='3') and emp_id='".$_SESSION['uid']."'";
 $sql = "SELECT count(id) as id FROM tour_claim where 1=1 ".$cond." and emp_id='".$emir['employee_id']."' and voucher_generated!='1'";
$retval =DB_query( $sql, $db );

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


// $s="select * from tour_claim where 1=1 ".$cond." and emp_id='".$emir['employee_id']."' ".$orderby." LIMIT $offset, $rec_limit";
 $s="select * from tour_claim where 1=1 ".$cond." and emp_id='".$emir['employee_id']."' and voucher_generated!='1' ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

?>
<script type="text/javascript">
function change(a,b)
{
 
 window.location.href="tour_claim_full_details.php?status="+a+"&refid="+b;
}
</script>

<body>
<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">List of My Claim</a></div>
<?php if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='tour_claim_user.php'>View all</a> </div>";
  }
  ?>
  <?php if(isset($_REQUEST['msg']))
 {
  echo "<div><span style='color:#0F0'>".$_REQUEST['msg']."</span> </div>";
  }
  ?>
<form name="form" method="post" action="">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<input type="hidden" name="purpose_journey" id="purpose_journey" value="<?php echo $valpurpose_journey;?>">
<input type="hidden" name="date" id="date" value="<?php echo $valdate;?>">
<input type="hidden" name="total_amount" id="total_amount" value="<?php echo $valtotal_amount;?>">

<table cellspacing="0" cellpadding="0" border="0" id="wrapper">
<tr>
<td class="tblHeaderLeft"><h1>My Claims</h1> <span class="addrecord"><a href="tour_claim.php">Add Claim</a></span><div class="tblHeaderRight"><select name="statustype"><option value="">--Select--</option>
<option value="0">Pending</option>
<option value="1">Approved</option>
<option value="2">Rejected</option>
<option value="3">Queried</option></select><input type="text" name="search" value="">&nbsp;<input type="submit" name="go" value="Search"></div></td>
</tr>
</table>
<table cellspacing="2" cellpadding="1" border="0" class="form-container">

  <tr>
  <th width="109">S. No.</th>
  <th width="109">Claim Id</th>
    <th width="109">Employee Name</th>
    <th width="126"><a href='javascript:void(0)' onclick=sorting('purpose_journey');>Purpose of Journey</a> <?php echo $period_illnessimage;?></th>
    <th width="125"><a href='javascript:void(0)' onclick=sorting('date');>Date</a> <?php echo $dateimage;?></th>
    <th width="101"><a href='javascript:void(0)' onclick=sorting('total_amount');>Total Amount</a> <?php echo $net_amountimage;?></th>   
    <th width="63">Status</th>
    <th width="63">Action</th>
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
  <td><?php echo $nn;?></td>
  <td>TA-<?php echo $r['id'];?></td>
    <td><?php echo $unr['employee_name'];?></td>
    <td><?php echo $r['purpose_journey'];?></td>
    <td><?php echo date('d-m-Y',$r['date']);?></td>
    <td><?php echo round($r['total_amount']);?></td>
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
     <td> <a href="tour_claim_full_detail_user.php?id=<?php echo $r['id'];?>">View/Reply</a><?php if($r['status']==3) {?> <a href="tour_claim_resubmit.php?clid=<?php echo $r['id'];?>">Resubmit</a> <?php } ?></td>
  </tr>
  <?php
  $i++;
  $nn++;
  }
  ?>
  </table>
  <div class="paging"><?php 
  
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
 window.location.href="tour_claim_user.php?sort="+a+"&order="+order;
 
}
</script>