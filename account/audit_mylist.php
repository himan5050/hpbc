<?php
include('includes/session.inc');
$title = _('Audit');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');


if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='startdate')
{
   if($_REQUEST['order']=='asc')
   {
    $valstartdate="desc";
	$startdateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valstartdate="asc";
	  $startdateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valstartdate="asc";
 $startdateimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='period')
{
 if($_REQUEST['order']=='asc')
   {
    $valperiod="desc";
	$periodimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valperiod="asc";
	  $periodimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valperiod="asc";
 $periodimage='';
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='corporation_name')
{
 if($_REQUEST['order']=='asc')
   {
    $valcorporation_name="desc";
	$corporation_nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valcorporation_name="asc";
	  $corporation_nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valcorporation_name="asc";
 $corporation_nameimage='';
}

$cond='';
if(isset($_POST['search']))
{
	 $auda=substr(strtotime($_POST['search']),0,5);
	 if($auda=='')
	 {
 $cond.="and ((tbl_corporations.corporation_name like '%".$_POST['search']."%' ) OR (audit_plan.period like '%".$_POST['search']."%') OR (audit_plan.startdate = '".$auda."'))";
	 }
	 else
	 {
		 $cond.="and ((tbl_corporations.corporation_name like '%".$_POST['search']."%' ) OR (audit_plan.period like '%".$_POST['search']."%') OR (audit_plan.startdate like '%".$auda."%'))";
	 }
}
$rec_limit = 10;

$sql = "select audit_plan.id,audit_plan.auditoffice,audit_plan.auditee,audit_plan.auditor,audit_plan.startdate,audit_plan.period,audit_plan.status,audit_plan.levelstatus,audit_plan.querystatus,audit_plan.approvecomment,audit_plan.resubmitstatus,audit_plan.correctivestatus,tbl_corporations.corporation_name FROM audit_plan,tbl_corporations where 1=1 ".$cond." and audit_plan.auditor='".$_SESSION['uid']."' and audit_plan.status=0 AND tbl_corporations.corporation_id=audit_plan.auditoffice";
 $count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";
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


 $s="select audit_plan.id,audit_plan.auditoffice,audit_plan.auditee,audit_plan.auditor,audit_plan.startdate,audit_plan.period,audit_plan.status,audit_plan.levelstatus,audit_plan.querystatus,audit_plan.approvecomment,audit_plan.resubmitstatus,audit_plan.correctivestatus,tbl_corporations.corporation_name  from audit_plan,tbl_corporations where 1=1 ".$cond."  and auditor='".$_SESSION['uid']."' and audit_plan.status=0 AND tbl_corporations.corporation_id=audit_plan.auditoffice ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
//$nu=DB_num_rows($q);
$nu=$rec_count;
?>
<script>
function change(a,b)
{
 
 window.location.href="audit_plan_detail.php?status="+a+"&refid="+b;
}
</script>

<body>
<div class="breadcrumb"><a href="/<?php echo $u[1]; ?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">Audit List</a></div>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
  <?php 
 if(isset($_REQUEST['msg']))
 {
  echo '<div class="success">'.$_REQUEST['msg'].'</div>';
  }
  ?>
<?php 
 if(isset($_REQUEST['search']))
 {
  echo '<div class="searchrecord"> '.$nu.' Record(s) Found. &nbsp;| <a href="audit_mylist.php">View all</a></div>';
  }
  ?>
  <input type="hidden" name="startdate" id="startdate" value="<?php echo $valstartdate;?>">
<input type="hidden" name="period" id="period" value="<?php echo $valperiod;?>">
<input type="hidden" name="corporation_name" id="corporation_name" value="<?php echo $valcorporation_name;?>">
<table cellpadding="0" cellspacing="0" border="0" id="wraper" width="100%">
<tr>
  <td class="tblHeaderLeft">Audit List</td> <td class="tblHeaderRight"><input type="text" name="search"> <input type="submit" name="go" value="Search"></td>
</tr>
</table>

<table cellpadding="2" cellspacing="1" border="0" id="form-container">
  <tr>
  <th>S. No.</th>
    <th><a href='javascript:void(0)' onclick=sorting('corporation_name');>Office</a> <?php echo $corporation_nameimage;?></th>
    <th><a href='javascript:void(0)' onclick=sorting('startdate');>Start Date</a> <?php echo $startdateimage;?></th>
    <th><a href='javascript:void(0)' onclick=sorting('period');>Period</a> <?php echo $periodimage;?></th>
     <th>Options</th>
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
  {   // print_r($r);
    $un="select corporation_name from tbl_corporations where corporation_id='".$r['auditoffice']."'";
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
    <td><?php echo ucwords($r['corporation_name']);?></td>
     <td><?php echo date('d-m-Y',$r['startdate']);?></td>
    <td><?php echo $r['period'];?></td>
    <td width="30%"><?php if($r['levelstatus']==0) {?><a href="audit_report.php?auid=<?php echo $r['id'];?>">Add Report</a><?php } ?><?php if($r['levelstatus']>0 && $r['correctivestatus']==0) {?><a href="audit_report_view.php?auid=<?php echo $r['id'];?>">View Report</a><?php }  if($r['querystatus']==1) { echo "Queried"; } if($r['correctivestatus']==1) {?> <a href="audit_verify.php?auid=<?php echo $r['id'];?>">Verify Compliance</a> <?php } if($r['status']==1) { echo "Successfully Complete"; } ?> </td>
  </tr>
  <?php
   $nn++;
  $i++;
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
 window.location.href="audit_mylist.php?sort="+a+"&order="+order;
 
}
</script>