<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$s="select * from audit_detail where audit_id='".$_GET['auid']."'";
$q=DB_query($s,$db);


$s1="select * from audit_plan where id='".$_GET['auid']."'";
$q1=DB_query($s1,$db);
$r1=DB_fetch_array($q1);

$cou1="select name from users where uid='".$r1['auditor']."'";
		$couq1=DB_query($cou1,$db);
		$cour1=DB_fetch_array($couq1);
/*if(isset($_POST['approve']))
{
  $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);
  
  $st="update medical_claim set status='1',approvecomment='".$_POST['remark']."',approvedamount='".$_POST['approvedamount']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  
  if($ssq)
  {
    header("location:medical_claim_details.php");
  }
}


if(isset($_POST['reject']))
{  
   $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);
  
   $st="update medical_claim set status='2',rejectcomment='".$_POST['remark']."',approvedamount='".$_POST['approvedamount']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  if($ssq)
  {
    header("location:medical_claim_details.php");
  }
}

if(isset($_POST['query']))
{  
   $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);
  
   $st="update medical_claim set status='3',querycomment='".$_POST['remark']."',approvedamount='".$_POST['approvedamount']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  $re="insert into medical_query_comment set claim_id='".$_GET['id']."',enteredby='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  if($ssq)
  {
    header("location:medical_claim_details.php");
  }
}*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div class="breadcrumb">Home &raquo; <a href="audit_mylist.php">Audit List</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Audit Detail</a></div>
<table cellpadding="2" cellspacing="1" border="0" id="form-container" width="100%">
<tr class="oddrow">
  <td colspan="5" align="center"><h2>Audit Detail</h2></td>
</tr>
   <?php
    $r=DB_fetch_array($q);
        
    $un="select corporation_name from tbl_corporations where corporation_id='".$r['auditoffice']."'";
	$unq=DB_query($un,$db);
	$unr=DB_fetch_array($unq);
	
	
  ?>
 
 
  <tr class="evenrow">
    <td><b>Office:</b></td>
    <td class="ans"><?php echo $unr['corporation_name'];?></td>
    <td><b>Name of Auditor:</b></td>
    <td class="ans"><?php echo $cour1['name'];?></td>
  </tr>
 <tr class="oddrow">
    <td><b>Audit Date:</b></td>
    <td class="ans"><?php echo date('d-m-Y',$r1['startdate']);?></td>
    <td><b>Actual Audit Date:</b></td>   
     <td class="ans"><?php echo date('d-m-Y',$r['auditdate']);?></td>
  </tr>
  <tr class="evenrow">
    <td><b>Schedule Period:</b></td>
    <td class="ans"><?php echo $r1['period'];?></td>
    <td><b>Attachments:</b></td>
    <td class="ans"><?php if($r['attachedncs'] !='') {?><a href="../sites/default/files/audit/<?php echo $r['attachedncs'];?>">View NCs</a> <?php } else echo "None";?></td>
  </tr>
   <tr class="oddrow">
    <td><b>Remarks:</b></td>
    <td class="ans"><?php echo $r['remark'];?></td>
    <td></td>   
     <td></td>
  </tr>
</table>
<br/>
<table cellpadding="2" cellspacing="1" border="0" id="form-container" width="100%">
<tr class="odd"><td colspan="7" align="center"><h2>NCs</h2></td>
</tr>
  <tr>
   <th>NCs</th>
   <th>Description</th>
   <th>Severity</th>   
   <th>Clause</th>
</tr>
 
<?php
  $l=1;
 $ss="select * from nsc_detail where audit_id='".$_GET['auid']."'";
$ssq=DB_query($ss,$db);
while($ssr=DB_fetch_array($ssq))
{  if($ssr['nsc']!='')
{
if($l%2==0) {  
   $cla='oddrow';
  }
  else
  {
    $cla='oddrow';
  }
?>
 <tr class="<?php echo $cla;?>">
    <td class="ans"><?php echo $ssr['nsc'];?></td>
    <td class="ans"><?php echo $ssr['description'];?></td>   
    <td class="ans"><?php echo $ssr['sevirity'];?></td>
    <td class="ans"><?php echo $ssr['clause'];?></td>
 </tr>
  <?php  $l++;
  }
  }
  $auq="select * from audit_query_comment where audit_id='".$_GET['auid']."'";
  $auqq=DB_query($auq,$db);
  $auqn=DB_num_rows($auqq);
  if($auqn>0)
  {
  ?></table><br /><table>
  <tr class="odd"><td colspan="4" align="center"><h2>Queries</h2> <?php if($r1['querystatus']=='1') {?><a href="audit_plan_resubmit.php?auid=<?php echo $_GET['auid'];?>">Resubmit</a> <?php } ?></td>
  </tr>
 <?php }
  $k=0;
  while($auqr=DB_fetch_array($auqq))
  {  if($k%2==0)
    {
	  $cl="even";
	  }
	  else
	  {
	   $cl="odd";
	  } 
	    $cou="select name from users where uid='".$auqr['enteredby']."'";
		$couq=DB_query($cou,$db);
		$cour=DB_fetch_array($couq);
	?>
  
   <tr class="<?php echo $cl;?>"><td colspan="4" align="left"><b><?php echo $k+1;?>.&nbsp;&nbsp;</b> <?php echo $auqr['query'];?> <strong>By:</strong> <?php echo $cour['name'];?> <strong>On:</strong> <?php echo date('d-m-Y',$auqr['date']);?></td></tr>
 <?php  $k++;}
  ?>
</table>
<?php include("includes/footer.inc"); ?>
</body>
</html>
