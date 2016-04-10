<?php ob_start();
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
global $nc;
$s="select * from audit_detail where audit_id='".$_GET['auid']."'";
$q=DB_query($s,$db);

$s1="select * from audit_plan where id='".$_GET['auid']."'";
$q1=DB_query($s1,$db);
$r1=DB_fetch_array($q1);

$cou1="select name from users where uid='".$r1['auditor']."'";
		$couq1=DB_query($cou1,$db);
		$cour1=DB_fetch_array($couq1);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div class="breadcrumb">Home &raquo; <a href="audit_mylist.php">Audit List</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Audit Detail</a></div>

<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="odd">
  <td colspan="5" align="center"><h2>Audit Detail</h2></td>
</tr>
  


<?php
  $i=1;
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
  
  <tr>
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
    <td class="ans"><div style="width:100px;"><?php echo $r['remark'];?></div></td>
    <td></td>   
     <td></td>
  </tr>


</table>
<br/>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="odd"><td colspan="7" align="center"><h2>NCs</h2></td>
</tr>
  <tr>
   <th>NCs</th>
   <th>Description</th>
   <th>Severity</th>   
   <th>Clause</th>
</tr>
 
<?php
$ss="select * from nsc_detail where audit_id='".$_GET['auid']."'";
$ssq=DB_query($ss,$db);

while($ssr=DB_fetch_array($ssq))
{  
 $ncd[]=$ssr['id'];
 if($ssr['nsc']!='')
 {
?>
 <tr class="oddrow">
    <td class="ans"><?php echo $ssr['nsc'];?></td>
    <td class="ans"><?php echo $ssr['description'];?></td>   
    <td class="ans"><?php echo $ssr['sevirity'];?></td>
    <td class="ans"><?php echo $ssr['clause'];?></td>
 </tr>
 <tr class="even"><td colspan="4">NC Reply :  <?php echo $ssr['corrective_report'];?></td></tr>
  <?php 
  $nc++;
  }
 }

if(isset($_POST['submit']))
{ 
	$adi="select doc_id,task_id from audit_plan where id='".$_REQUEST['auid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);
	
  $apc="update audit_plan set status='1' where id='".$_GET['auid']."'";
  $apcq=DB_query($apc,$db);
  
  if($apcq)
  {
    @header("location:audit_mylist.php");
  }
 
}

 
  ?>

</table><br />
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<?php
   $auq="select * from audit_query_comment where audit_id='".$_GET['auid']."'";
  $auqq=DB_query($auq,$db);
  $auqn=DB_num_rows($auqq);
  if($auqn>0)
  {
  ?>
  <tr class="oddrow"><td colspan="4" align="center"><h2>Queries</h2> <?php if($r['querystatus']=='1') {?><a href="audit_plan_resubmit.php?auid=<?php echo $_GET['auid'];?>">Resubmit</a> <?php } ?></td>
  </tr>
  <?php } $n=0;
  while($auqr=DB_fetch_array($auqq))
  { 
     if($n%2==0)
	 {
	   $cla='even';
	 }
	 else
	 {
	   $cla='odd';
	 }
	 $cou="select name from users where uid='".$auqr['enteredby']."'";
		$couq=DB_query($cou,$db);
		$cour=DB_fetch_array($couq);
  ?>
  
   <tr class="<?php echo $cla;?>"><td colspan="4" align="left"><b><?php echo $n+1;?>.&nbsp;&nbsp;</b> <?php echo $auqr['query'];?> <strong>By:</strong> <?php echo $cour['name'];?> <strong>On:</strong> <?php echo date('d-m-Y',$auqr['date']);?></td></tr>
 <?php  
 $n++;}
  ?>
    <tr class="odd"><td colspan="4" align="center"> <input type="submit" name="submit" value="Approved And Finish" /></td></tr>
  </table><br/>
</form>
<?php include("includes/footer.inc"); ?>
</body>
</html>
