<?php
include('includes/session.inc');
$title = _('');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='name')
{
   if($_REQUEST['order']=='asc')
   {
    $valname="desc";
	$nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valname="asc";
	  $nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valname="asc";
 $nameimage='';
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
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='refnum')
{
 if($_REQUEST['order']=='asc')
   {
    $valrefnum="desc";
	$refnumimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valrefnum="asc";
	  $refnumimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valrefnum="asc";
 $refnumimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='remarks')
{
 if($_REQUEST['order']=='asc')
   {
    $valremarks="desc";
	$remarksimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valremarks="asc";
	  $remarksimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valremarks="asc";
 $remarksimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='approveamount')
{
 if($_REQUEST['order']=='asc')
   {
    $valapproveamount="desc";
	$approveamountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valapproveamount="asc";
	  $approveamountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valapproveamount="asc";
 $approveamountimage='';
}

$cond='';
$rec_limit = 10;

if(isset($_POST['search']))
{
  $cond.="and (refnum like '%".$_POST['search']."%' OR name like '%".$_POST['search']."%' OR amount like '%".$_POST['search']."%' OR date like '%".$_POST['search']."%' OR remarks like '%".$_POST['search']."%' OR approveamount like '%".$_POST['search']."%' )";
  
  unset($_GET{'page'});
}


$sql = "SELECT count(id ) FROM billsubmit where 1=1 ".$cond." and userid='".$_SESSION['uid']."'";
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

$emi="select employee_id,employee_name from tbl_joinings where program_uid='".$_SESSION['uid']."' ";
$emiq=DB_query($emi,$db);
$emir=DB_fetch_array($emiq);
 $s="select * from billsubmit where 1=1 ".$cond." and userid='".$_SESSION['uid']."' ".$orderby."  LIMIT $offset, $rec_limit";
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
<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">List of My Bills</a></div>

<input type="hidden" name="refnum" id="refnum" value="<?php echo $valrefnum;?>">
<input type="hidden" name="name" id="name" value="<?php echo $valname;?>">
<input type="hidden" name="amount" id="amount" value="<?php echo $valamount;?>">
<input type="hidden" name="date" id="date" value="<?php echo $valdate;?>">
<input type="hidden" name="remarks" id="remarks" value="<?php echo $valremarks;?>">
<input type="hidden" name="approveamount" id="approveamount" value="<?php echo $valapproveamount;?>"> 
<?php
  $i=1;
   if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='billsubmit_user.php'>View all</a> </div>";
  }
?>
<?php
if(isset($_REQUEST['msg']))
{
 echo "<div class='success'>".$_REQUEST['msg']."</div>";
}
?>
<div class="tblHeaderLeft"><h1>My Bills</h1><span class="addrecord"><a href="billsubmit.php"> Add Bill Application</a></span></div><div class="tblHeaderRight"><input type="text" name="search" value="">&nbsp;<input type="submit" name="go" value="Search"></div>

<table>

  <tr>
   <th width="109"><strong>S. No.</strong></th>
   <th width="109"><strong><a href='javascript:void(0)' onclick=sorting('refnum');>Reference No.</a></strong> <?php echo $refnumimage;?></th>
    <th width="109"><strong><a href='javascript:void(0)' onclick=sorting('name');>Name</a></strong> <?php echo $nameimage;?></th>
    <th width="126"><strong><a href='javascript:void(0)' onclick=sorting('amount');>Amount</a></strong> <?php echo $amountimage;?></th>
    <th width="140"><strong><a href='javascript:void(0)' onclick=sorting('date');>Date</a></strong> <?php echo $dateimage;?></th>
    <th width="86"><strong><a href='javascript:void(0)' onclick=sorting('remarks');>Remark</a></strong> <?php echo $remarksimage;?></th> 
    <th width="86"><strong><a href='javascript:void(0)' onclick=sorting('approveamount');>Approved Amount</a></strong> <?php echo $approveamountimage;?></th>   
    <th width="63"><strong>Status</strong></th>
   
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
   <td><?php echo  $nn;?></td>
  <td><?php echo $r['refnum'];?></td>
    <td><?php echo $r['name'];?></td>
    <td><?php echo round(abs($r['amount']));?></td>
    <td><?php echo date('d-m-Y',$r['date']);?></td>
    <td><?php echo $r['remarks'];?></td>
     <td><?php echo round(abs($r['approveamount']));?></td>
     <td><?php 
	
	 if($r['status']==1 && $r['voucher_generated']==1)
	 {
	   echo "Paid";
	 }
	  else if($r['status']==1 && $r['voucher_generated']!=1)
	 {
	   echo "Approved";
	 }
	 else if($r['status']==2)
	 {
	   echo "Rejected";
	 }
	/* else if($r['querystatus']==1)
	 {
	   echo "Queried";
	 } */
	 else
	 {
	   echo "Pending";
	 }
	 
	 ?>
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
 window.location.href="billsubmit_user.php?sort="+a+"&order="+order;
 
}
</script>