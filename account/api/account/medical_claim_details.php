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


$cond='';
if(isset($_POST['search']))
{  $uni=explode('-',$_POST['search']);
  $cond.="and (emp_id like '%".$_POST['search']."%' OR net_amount like '%".$_POST['search']."%' OR tbl_joinings.employee_name like '%".$_POST['search']."%' OR id = '".$uni[1]."')";
}

$of="select current_officeid from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$ofq=DB_query($of,$db);
$ofr=DB_fetch_array($ofq);
$rec_limit = 10;

$sql = "SELECT count(id ) FROM medical_claim,tbl_joinings where 1=1 ".$cond." and (status='0' or status='3') and medical_claim.emp_id=tbl_joinings.employee_id and medical_claim.office='".$ofr['current_officeid']."'";
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


 $s="select * from medical_claim,tbl_joinings where 1=1 ".$cond." and (status='0' or status='3') and medical_claim.emp_id=tbl_joinings.employee_id and medical_claim.office='".$ofr['current_officeid']."' ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

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

<input type="hidden" name="emp_id" id="emp_id" value="<?php echo $valemp_id;?>" />
<input type="hidden" name="employee_name" id="employee_name" value="<?php echo $valemployee_name;?>" />
<input type="hidden" name="net_amount" id="net_amount" value="<?php echo $valnet_amount;?>" />
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF']; ?>">List of Claims for Approval</a></div>
 <?php
  $i=1;
   if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='medical_claim_details.php'>View all</a> </div>";
  }
?>
<div class="tblHeaderLeft"><h1>List of Claims for Approval</h1></div><div class="tblHeaderRight"><input type="text" name="search" value="" />&nbsp;<input type="submit" name="go" value="Search" /></div>
<table>
  <tr>
  <th>Claim Id</th>
    <th><a href='javascript:void(0)' onclick=sorting('emp_id');>Employee Id</a> <?php echo $emp_idimage;?></th>
    <th><a href='javascript:void(0)' onclick=sorting('employee_name');>Employee Name</a> <?php echo $employee_nameimage;?></th>
    <th>Designation</th>
    <th><a href='javascript:void(0)' onclick=sorting('net_amount');>Net Amount</a> <?php echo $net_amountimage;?></th>
   
    <th>Options</th>
  </tr>
 <?php
  while($r=DB_fetch_array($q))
  {    
    $un="select employee_name from tbl_joinings where employee_id='".$r['emp_id']."'";
	$unq=DB_query($un,$db);
	$unr=DB_fetch_array($unq);
	
	$des="select lookup_name from tbl_lookups where lookup_id='".$r['designation']."'";
    $desq=DB_query($des,$db);
    $desr=DB_fetch_array($desq);
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
    <td>MD-<?php echo $r['id'];?></td>
    <td><?php echo $r['emp_id'];?></td>
    <td><?php echo $r['employee_name'];?></td>
    <td><?php echo $desr['lookup_name'];?></td>
    <td><?php echo $r['net_amount'];?></td>
    <td><a href="medical_claim_full_detail.php?id=<?php echo $r['id'];?>">View Detail</a></td>
  </tr>
  <?php
  $i++;
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
 window.location.href="medical_claim_details.php?sort="+a+"&order="+order;
 
}
</script>