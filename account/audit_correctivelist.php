<?php
include('includes/session.inc');
$title = _('Audit');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');


if(isset($_POST['search']))
{  
  $auda=substr(strtotime($_POST['search']),0,5);
  if($auda=='')
 {
	 $cond.="and ((audit_plan.period like '%".$_POST['search']."%' ) OR (audit_plan.startdate = '%".$auda."%'))";
 }
 else
 {
 $cond.="and ((audit_plan.period like '%".$_POST['search']."%' ) OR (audit_plan.startdate like '%".$auda."%'))";
 }
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

 /*$s1="select * from tbl_joinings where program_uid='".$_SESSION['uid']."'";
$q1=DB_query($s1,$db);
$r1=DB_fetch_array($q1);
$auo=$r1['current_officeid'];*/

 $s="select * from audit_plan,tbl_joinings where 1=1 ".$cond." and audit_plan.auditoffice=tbl_joinings.current_officeid and levelstatus=2 and status=0 and tbl_joinings.program_uid='".$_SESSION['uid']."' ".$orderby."";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);

?>


<body>
<div class="breadcrumb"><a href="/<?php echo $u[1]; ?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">Audit List</a></div>
<?php 
if(isset($_GET['msg']))
 {
  echo '<div class="success"> '.$_GET['msg'].'</div>';
  }

 if(isset($_REQUEST['search']))
 {
  echo '<div class="searchrecord"> '.$nu.' Record(s) Found. &nbsp;| <a href="audit_correctivelist.php">View all</a></div>';
  }
  ?>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<input type="hidden" name="startdate" id="startdate" value="<?php echo $valstartdate;?>">
<input type="hidden" name="period" id="period" value="<?php echo $valperiod;?>">

<table cellpadding="0" cellspacing="0" border="0" id="wraper" width="797">
<tr>
<tr>
	<td class="searchrecord"></td>
</tr>
<td class="tblHeaderLeft">Audit List</td>
<td class="tblHeaderRight"><input type="text" name="search"> <input type="submit" name="go" value="Search"></td>
</table>
<table cellpadding="2" cellspacing="1" border="0" id="form-container">
  <tr>  
  <th>S. No.</th>
    <th><a href='javascript:void(0)' onclick=sorting('startdate');>Start Date</a> <?php echo $startdateimage;?></th>
    <th><a href='javascript:void(0)' onclick=sorting('period');>Period</a> <?php echo $periodimage;?></th>
    <th>Options</th>
  </tr>
  <?php
  $i=1;
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
    <td><?php echo $i;?></td>
    <td><?php echo date('d-m-Y',$r['startdate']);?></td>
    <td><?php echo $r['period'];?></td>
    <td><a href="audit_corrective.php?auid=<?php echo $r['id'];?>"><?php if($r['correctivestatus']=='0') {?>Add Corrective<?php } else {?> View Corrective <?php } ?></a> </td>
  </tr>
  <?php
  $i++;
  }
  ?>
  
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
 window.location.href="audit_correctivelist.php?sort="+a+"&order="+order;
 
}
</script>