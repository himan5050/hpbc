<?php
include('includes/session.inc');
$title = _('Audit');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$cond='';
if(isset($_POST['search']))
{
 $cond.="and ((tbl_corporations.corporation_name like '%".$_POST['search']."%' ) OR (audit_plan.period like '%".$_POST['search']."%'))";
}

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

$rec_limit = 10;

$sql = "SELECT count(id ) FROM audit_plan,tbl_corporations where levelstatus >=0 and audit_plan.status!=1 AND tbl_corporations.corporation_id=audit_plan.auditoffice";
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


$s="select * from audit_plan,tbl_corporations where 1=1 ".$cond." and levelstatus>=0 and audit_plan.status!=1 AND tbl_corporations.corporation_id=audit_plan.auditoffice ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

?>
<script>
function change(a,b)
{
 
 window.location.href="audit_plan_detail.php?status="+a+"&refid="+b;
}
</script>

<body>
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Audit List</a></div>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<input type="hidden" name="startdate" id="startdate" value="<?php echo $valstartdate;?>">
<input type="hidden" name="period" id="period" value="<?php echo $valperiod;?>">
 
 <?php 
 if(isset($_REQUEST['search']))
 {
  echo '<div class="searchrecord"> '.$nu.' Record(s) Found. &nbsp;| <a href="audit_report_approvelist.php">View all</a></div>';
  }
  ?>
<table cellpadding="0" cellspacing="0" border="0" id="wraper" width="100%">
<tr>
  <td class="tblHeaderLeft"><h1>Audit List</h1><span class="addrecord"><a href="audit_plan.php">Plan Audit</a></span>
  </td>
  <td class="tblHeaderRight"><input type="text" name="search"> <input type="submit" name="go" value="Search"></td>
</tr>
</table>

<table cellpadding="2" cellspacing="1" border="0" id="form-container" width="100%">
  <tr>
    <th>Office</th>
    <th><a href='javascript:void(0)' onclick=sorting('startdate');>Start Date</a> <?php echo $startdateimage;?></th>
    <th><a href='javascript:void(0)' onclick=sorting('period');>Period</a> <?php echo $periodimage;?></th>
    <th class="addeditview">Options</th>
  </tr>
  <?php
  $i=1;
  while($r=DB_fetch_array($q))
  {    
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
    <td class="normal"><?php echo ucwords($unr['corporation_name']);?></td>
    <td class="normal"><?php echo date('d-m-Y',$r['startdate']);?></td>
    <td class="normal"><?php echo $r['period'];?></td>
    <td class="normal"><?php if($r['levelstatus']==1) {?><a href="audit_report_approve.php?auid=<?php echo $r['id'];?>">View Report</a><?php } if($r['levelstatus']==2) {?> <a href="audit_report_approve.php?auid=<?php echo $r['id'];?>">Approved</a><?php }?></td>
  </tr>
  <?php
  $i++;
  }
  ?>
  <tr><td colspan="5" align="right"><?php 
  
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
 window.location.href="audit_report_approvelist.php?sort="+a+"&order="+order;
 
}
</script>