<?php
include('includes/session.inc');
$title = _('');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$cond='';
$role = getRole($_SESSION['uid'],$db);
if(isset($_POST['search']))
{
  $cond.="and (employee_id like '%".$_POST['search']."%' OR amount like '%".$_POST['search']."%' OR tbl_joinings.employee_name like '%".$_POST['search']."%')";
   unset($_GET{'page'});
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='employee_id')
{
   if($_REQUEST['order']=='asc')
   {
    $valemployee_id="desc";
	$employee_idimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valemployee_id="asc";
	  $employee_idimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valemployee_id="asc";
 $employee_idimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='employee_name')
{
 if($_REQUEST['order']=='asc')
   {
    $valemployee_name="desc";
	$employee_nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valemployee_name="asc";
	  $employee_nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valemployee_name="asc";
 $employee_nameimage='';
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

$of="select current_officeid from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$ofq=DB_query($of,$db);
$ofr=DB_fetch_array($ofq);
$rec_limit = 10;
 
 $ur="select rid from users_roles where uid='".$_SESSION['uid']."'";
		$urq=DB_query($ur,$db);
		$urr=DB_fetch_array($urq);
		$usrid=$urr['rid'];
 
 if($usrid==13)
{
 $ncod="and loanadvance.office='".$ofr['current_officeid']."'";	
}
 
$sql = "SELECT count(id ) FROM loanadvance,tbl_joinings where 1=1 ".$cond." and (status='0') and loanadvance.empid=tbl_joinings.program_uid $ncod and loanadvance.reportedto=".$usrid."";
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


 $s="select * from loanadvance,tbl_joinings where 1=1 ".$cond." and (status='0') and loanadvance.empid=tbl_joinings.program_uid $ncod and loanadvance.reportedto=".$usrid."  ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

?>


<body>

<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<input type="hidden" name="employee_id" id="employee_id" value="<?php echo $valemployee_id;?>">
<input type="hidden" name="employee_name" id="employee_name" value="<?php echo $valemployee_name;?>">
<input type="hidden" name="amount" id="amount" value="<?php echo $valamount;?>">


<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">List of Loan for Approval</a></div>
 <?php
 if(isset($_REQUEST['msg']))
{
 echo "<div class='success'>".$_REQUEST['msg']."</div>";
}
 
  $i=1;
   if(isset($_REQUEST['search']))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='loanadvance_detail.php'>View all</a> </div>";
  }
?>
<div class="tblHeaderLeft"><h1>List of Loan for Approval</h1><?php if($role !=6) echo '<span class="addrecord"><a href="loanadvance_user.php">My Loan</a></span>'; ?></div><div class="tblHeaderRight"><input type="text" name="search" value="">&nbsp;<input type="submit" name="go" value="Search"></div>
<table>
</tr>
  <tr>
  <th>S. No.</th>
    <th><a href='javascript:void(0)' onclick=sorting('employee_id');>Employee Id</a> <?php echo $employee_idimage;?></th>
    <th><a href='javascript:void(0)' onclick=sorting('employee_name');>Employee Name</a> <?php echo $employee_nameimage;?></th>
    
    <th><a href='javascript:void(0)' onclick=sorting('amount');>Loan Amount</a> <?php echo $amountimage;?></th>
   
    <th>Options</th>
  </tr>
 <?php
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
    <td><?php echo $r['employee_id'];?></td>
    <td><?php echo $r['employee_name'];?></td>
   
    <td><?php echo round($r['amount']);?></td>
    <td><?php if($r['approvestatus']==1) {?><a href="loanadvance_full_detail.php?id=<?php echo $r['id'];?>">Approved</a><?php }else if($r['replystatus']==1) { ?><a href="loanadvance_full_detail.php?id=<?php echo $r['id'];?>">Replied</a><?php } else if($r['querystatus']==1) { ?><a href="loanadvance_full_detail.php?id=<?php echo $r['id'];?>">Queried</a><?php }else{?><a href="loanadvance_full_detail.php?id=<?php echo $r['id'];?>">View Detail</a><?php } ?></td>
  </tr>
  <?php
  $i++;
  $nn++;
  }
  ?>
  <tr><td colspan="5" align="right"><?php 
  	/* for($nn=1;$nn<=$topage;$nn++)
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
 window.location.href="loanadvance_detail.php?sort="+a+"&order="+order;
 
}
</script>