<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$cond='';

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='period_illness')
{
   if($_REQUEST['order']=='asc')
   {
    $valperiod_illness="desc";
	$period_illnessimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valperiod_illness="asc";
	  $period_illnessimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valperiod_illness="asc";
 $period_illnessimage='';
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

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='net_amount')
{
 if($_REQUEST['order']=='asc')
   {
    $valnet_amount="desc";
	$net_amountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png" />';
	}
	else
	{
	  $valnet_amount="asc";
	  $net_amountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png" />';
	}
}
else
{
 $valnet_amount="asc";
 $net_amountimage='';
}

$rec_limit = 10;
$cond='';

if(isset($_POST['search']) && $_POST['search'])
{  
   $uni=explode('-',$_POST['search']);
   $da=substr(strtotime($_POST['search']),0,5);
   if(count($uni) > 0)
	   $ids = " OR id LIKE '%".$uni[1]."%'";
   else
	   $ids = '';
   if($da=='')
  {
     $cond.="and (period_illness like '%".$_POST['search']."%' OR net_amount like '%".$_POST['search']."%' OR j.employee_name like '%".$_POST['search']."%' ".$ids." )";
	 unset($_GET['page']);
  }
  else
   {
	    $cond.="and (period_illness like '%".$_POST['search']."%' OR net_amount like '%".$_POST['search']."%' OR j.employee_name like '%".$_POST['search']."%' ".$ids." OR date like '%".$da."%')";
		unset($_GET['page']);
  }
}
if(isset($_POST['statustype']) && $_POST['statustype']!='')
{
  $cond.="and status='".$_POST['statustype']."'";
}

$emi="select employee_id from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$emiq=DB_query($emi,$db);
$emir=DB_fetch_array($emiq);
 $sql = "SELECT count(id) as cid FROM medical_claim,tbl_joinings j where j.employee_id=emp_id ".$cond." and emp_id='".$emir['employee_id']."' and voucher_generated!='1'";
//$sql = "SELECT count(id) as id FROM medical_claim where 1=1 ".$cond."  and voucher_generated!='1'";
$retval =DB_query( $sql, $db );

$row = DB_fetch_array($retval);
 $rec_count = $row[0];
 $nu=$row[0];
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


 $s="select medical_claim.*,j.employee_name from medical_claim,tbl_joinings j where j.employee_id=emp_id ".$cond." and emp_id='".$emir['employee_id']."' and voucher_generated!='1' ".$orderby." LIMIT $offset, $rec_limit";
//$s="select * from medical_claim where 1=1 ".$cond."  and voucher_generated!='1' ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu1=DB_num_rows($q);

?>
<script type="text/javascript">
function change(a,b)
{
 
 window.location.href="medical_claim_details.php?status="+a+"&refid="+b;
}
</script>

<body>

<form name="form" method="post" action="">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<input type="hidden" name="period_illness" id="period_illness" value="<?php echo $valperiod_illness;?>" />
<input type="hidden" name="date" id="date" value="<?php echo $valdate;?>" />
<input type="hidden" name="net_amount" id="net_amount" value="<?php echo $valnet_amount;?>" />

<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">List of My Claims</a></div>
 <?php
  $i=1;
   if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='medical_claim_user.php'>View all</a> </div>";
  }
  
   if(isset($_REQUEST['msg']))
 {
  echo "<div><span style='color:#6F0'>".$_REQUEST['msg']."</span></div>";
  }
?>
<div class="tblHeaderLeft"><h1>My Claims</h1> <span class="addrecord"><a href="medical_claim.php">Add Claim</a></span></div><div class="tblHeaderRight"><select name="statustype"><option <?php if($_POST['statustype'] == "") echo 'selected="selected"'; ?> value="">--Select--</option>
<option <?php if($_POST['statustype'] == '0') echo 'selected="selected"'; ?> value="0">Pending</option>
<option <?php if($_POST['statustype'] == '1') echo 'selected="selected"'; ?> value="1">Approved</option>
<option <?php if( $_POST['statustype'] == '2' ) echo 'selected="selected"'; ?> value="2">Rejected</option>
<option <?php if( $_POST['statustype'] == '3' ) echo 'selected="selected"'; ?> value="3">Queried</option></select><input type="text" name="search" value="<?php if(isset($_POST['search'])) echo $_POST['search']; ?>">&nbsp;<input type="submit" name="go" value="Search" /></div>
<table>

  <tr>
  <th width="109"><strong>S. No.</strong></th>
  <th width="109"><strong>Claim Id</strong></th>
    <th width="109"><strong>Employee Name</strong></th>
    <th width="126"><strong><a href='javascript:void(0)' onClick="sorting('period_illness');">Period of Illness</a></strong> <?php echo $period_illnessimage;?></th>
    <th width="125"><strong><a href='javascript:void(0)' onClick="sorting('date');">Date</a></strong> <?php echo $dateimage;?></th>
    <th width="101"><strong><a href='javascript:void(0)' onClick="sorting('net_amount');">Net Amount</a></strong> <?php echo $net_amountimage;?></th>   
    <th width="63"><strong>Status</strong></th>
    <th width="63"><strong>Option</strong></th>
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
  <td>MD-<?php echo $r['id'];?></td>
    <td><?php echo ucwords($unr['employee_name']);?></td>
    <td><?php echo $r['period_illness'];?></td>
    <td><?php echo date('d-m-Y',$r['date']);?></td>
    <td><?php echo round($r['net_amount']);?></td>
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
     <td> <a href="medical_claim_user_detail.php?id=<?php echo $r['id'];?>">View</a> <?php if($r['status']==3) {?><a href="medical_claim_resubmit.php?clid=<?php echo $r['id'];?>">Resubmit</a> <?php } ?></td>
  </tr>
  <?php
  $i++;
  $nn++;
  }
  ?>
 </table><div class="paging">
  <?php 
  //echo $topage;
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
 window.location.href="medical_claim_user.php?sort="+a+"&order="+order;
 
}
</script>