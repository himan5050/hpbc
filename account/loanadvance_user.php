<?php
include('includes/session.inc');
$title = _('');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_REQUEST['sort']) && ($_REQUEST['sort'])!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='description')
{
   if($_REQUEST['order']=='asc')
   {
    $valdescription="desc";
	$descriptionimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valdescription="asc";
	  $descriptionimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valdescription="asc";
 $descriptionimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='period')
{
 if($_REQUEST['order']=='asc')
   {
    $valperiode="desc";
	$periodimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valperiode="asc";
	  $periodimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valperiod="asc";
 $periodimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='amount')
{
 if($_REQUEST['order']=='asc')
   {
    $valamount="desc";
	$amountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valamount="asc";
	  $amountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valamount="asc";
 $amountimage='';
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='date')
{
 if($_REQUEST['order']=='asc')
   {
    $valdate="desc";
	$dateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valdate="asc";
	  $dateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valdate="asc";
 $dateimage='';
}

$cond='';
if(isset($_POST['search']))
{
  $cond.="and (period like '%".$_POST['search']."%' OR amount like '%".$_POST['search']."%' )";
}


$rec_limit = 10;

$sql = "SELECT count(id ) FROM loanadvance where 1=1 ".$cond." and empid='".$_SESSION['uid']."'";
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

$emi="select employee_id from tbl_joinings where program_uid='".$_SESSION['uid']."' ";
$emiq=DB_query($emi,$db);
$emir=DB_fetch_array($emiq);
 $s="select * from loanadvance where 1=1 ".$cond." and empid='".$_SESSION['uid']."'  and status!='2' ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

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
<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">List of My Loan/Advance</a></div>
 <?php
  $i=1;
   if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='loanadvance_user.php'>View all</a> </div>";
  }
?>
<?php
if(isset($_REQUEST['msg']))
{
 echo "<div class='success'>".$_REQUEST['msg']."</div>";
}
?>
<div class="tblHeaderLeft"><h1>My Loan</h1><span class="addrecord"><a href="loanapplication.php"> Add Loan Application</a></span></div><div class="tblHeaderRight"><input type="text" name="search" value="">&nbsp;<input type="submit" name="go" value="Search"></div>

<input type="hidden" name="description" id="description" value="<?php echo $valdescription;?>">
<input type="hidden" name="period" id="period" value="<?php echo $valperiod;?>">
<input type="hidden" name="amount" id="amount" value="<?php echo $valamount;?>">
<input type="hidden" name="date" id="date" value="<?php echo $valdate;?>">
<table>

  <tr>
   <th width="25"><strong>S. No.</strong></th>
    <th width="109"><a href='javascript:void(0)' onclick=sorting('description');><strong>Description</strong></a> <?php echo $descriptionimage;?></th>
    <th width="126"><a href='javascript:void(0)' onclick=sorting('period');><strong>Period of Loan</strong></a> <?php echo $periodimage;?></th>
    <th width="140"><a href='javascript:void(0)' onclick=sorting('date');><strong>Date</strong></a> <?php echo $dateimage;?></th>
    <th width="86"><a href='javascript:void(0)' onclick=sorting('amount');><strong>Loan Amount</strong></a> <?php echo $amountimage;?></th>   
    <th width="63"><strong>Status</strong></th>
    <th width="130"><strong>Option</strong></th>
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
    <td><?php echo $r['description'];?></td>
    <td><?php echo $r['period'];?> Months</td>
    <td><?php echo date('d-m-Y',$r['date']);?></td>
    <td><?php echo round($r['amount']);?></td>
     <td><?php 
	
	 if($r['approvestatus']==1)
	 {
	   echo "Approved";
	 }
	 else if($r['status']==2)
	 {
	   echo "Rejected";
	 }
	 else if($r['querystatus']==1)
	 {
	   echo "Queried";
	 } 
	 else
	 {
	   echo "Pending";
	 }
	 
	 ?></td>
     <td> <?php if($r['approvestatus']==1) {?><a href="loanadvance_user_detail.php?id=<?php echo $r['id'];?>">Approved</a><?php } else if($r['querystatus']==1) { ?><a href="loanadvance_user_detail.php?id=<?php echo $r['id'];?>">Queried</a><?php }else{?><a href="loanadvance_user_detail.php?id=<?php echo $r['id'];?>">View Detail</a><?php } ?> </td>
  </tr>
  <?php
  $i++;
  $nn++;
  }
  ?>
  <tr><td colspan="6" align="right"><?php 
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

</td></tr>
</table>
</form>
<?php
include("includes/footer.inc");
?>
</body>
</html>
<script>
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
 window.location.href="loanadvance_user.php?sort="+a+"&order="+order;
 
}
</script>