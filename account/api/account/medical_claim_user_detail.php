<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$s="select * from medical_claim where id='".$_GET['id']."'";
$q=DB_query($s,$db);
 $r=DB_fetch_array($q);
if(isset($_POST['reply']))
{ 
 $re="insert into medical_query_comment set claim_id='".$_GET['id']."',enteredby='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  
  $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);
 
  $awt="insert into tbl_workflow_task set level='1',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];  
	 
	  $st="update medical_claim task_id='".$mtii."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
	 
  header("location:medical_claim_user.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div class="breadcrumb">Home &raquo; <a href="medical_claim_user.php">List of My Claim</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Claim Full detail</a></div>
<?php
        
    $un="select employee_name from tbl_joinings where employee_id='".$r['emp_id']."'";
	$unq=DB_query($un,$db);
	$unr=DB_fetch_array($unq);
	
	$des="select lookup_name from tbl_lookups where lookup_id='".$r['designation']."'";
    $desq=DB_query($des,$db);
    $desr=DB_fetch_array($desq);
  ?>

<table>
<tr class="oddrow"><td colspan="4" align="center"><h2>Claim Full detail</h2></td>
</tr>
 <tr class="evenrow">
    <td width="25%">Employee Id:</td>
    <td width="25%" class="ans"><?php echo $r['emp_id'];?></td>
    <td width="25%">Employee Name:</td>
    <td width="25%" class="ans"><?php echo $unr['employee_name'];?></td>
  </tr>
  <tr class="oddrow">
    <td width="25%">Designation:</td>
    <td width="25%" class="ans"><?php echo $desr['lookup_name'];?></td>
    <td width="25%">Net Amount:</td>
    <td width="25%" class="ans"><?php echo $r['net_amount'];?></td>
  </tr>
  <?php
  if($r['status']==3)
  {
  ?>
 <tr><td colspan="5"><?php echo $r['querycomment'];?></td></tr>
  <tr><td colspan="5"><a href="medical_claim_resubmit.php?clid=<?php echo $r['id'];?>">Resubmit</a> </td></tr>
 <?php }
 ?>
</table>
<a style="float:right;" href="bills/<?php echo $r['bill'];?>" target="_blank">View Bill</a>
  
<br /><br /><table>
<tr class="oddrow"><td  colspan="4" align="center"><h2>Medical Details</h2></td></tr>
  <tr class="evenrow">
    <td width="25%">Cash Memo Details</td>
    <td width="25%">Description</td>   
    <td width="25%">Type</td>
    <td width="25%">Charges</td>
  </tr>
 
<?php
 $ss="select * from medical_claim_detail where claim_id='".$_GET['id']."'";
$ssq=DB_query($ss,$db);
$j=1;
while($ssr=DB_fetch_array($ssq))
{ 
if($j%2==0)
{
  $cl="oddrow";
}
else
{
  $cl="oddrow";
}
 $t="select * from tbl_lookups where lookup_id='".$ssr['type']."'";
			          $tq=DB_query($t,$db);
					  $tr=DB_fetch_array($tq);
?>
 <tr class="<?php echo $cl;?>">
    <td class="ans"><?php echo $ssr['detail_cash_name'];?></td>
    <td class="ans"><?php echo $ssr['medicine'];?></td>
   
    <td class="ans"><?php echo $tr['lookup_name'];?></td>
    <td class="ans"><?php echo $ssr['charges'];?></td>
    
   
 </tr>
  <?php
  $j++;
  }
  $qu="select * from medical_query_comment where claim_id='".$_GET['id']."'";
  $quq=DB_query($qu,$db);
  while($qur=DB_fetch_array($quq))
  {
  ?>
  <tr><td colspan="4"><?php echo $qur['query'];?></td></tr>
  <?php } ?>
</table>
<?php
if($r['status']==3)
{ 
?>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="501">
  <tr>
  <td width="60">Comment</td>
  <td width="231"><textarea name="remark" cols="28"  rows="5"></textarea></td></tr>
  <tr><td align="center"><a href="medical_claim_details.php"><input type="button" name="back" value="Back" /></a></td><td> <input type="submit" value="Reply" name="reply" />  </td></tr></table>
</form>
<?php
}
?>
<?php include("includes/footer.inc"); ?>
</body>
</html>