<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$s="select * from loanadvance where id='".$_GET['id']."'";
$q=DB_query($s,$db);
 $r=DB_fetch_array($q);
if(isset($_POST['reply']))
{ 
 $re="insert into loanadvance_query set loan_id='".$_GET['id']."',uid='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  
   $sqll="update loanadvance set  querystatus='0',replystatus='1' where id='".$_REQUEST['id']."'";
	 $queryl=DB_query($sqll,$db);		

  $cu="select empid from loanadvance where id='".$_REQUEST['id']."'";
  $cuq=DB_query($cu,$db);
  $cur=DB_fetch_array($cuq);
   $clu=$cur['empid'];
	 
	 $adi="select doc_id,task_id from loanadvance where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
   updateTask($adir['task_id'],$db);
 
  /*$awt="insert into tbl_workflow_task set uid='".$clu."',status='0',doc_id='".$adr."'";
	 $awtq=DB_query($awt,$db);*/
	 $role = getRole($_SESSION['uid'],$db);
	if( $role != 13 && $role != 5 && $role != 19 )
		$level=1;
	 if($role == 13)
	{
		 $level = 2;
	}
	 if($role == 5)
		 $level = 3;
	 if($role == 19)
		 $level = 4;
	 createTask($level,$adr,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);

	$mti="select max(task_id) as task_id from tbl_workflow_task";
	$mtiq=DB_query($mti,$db);
	$mtir=DB_fetch_array($mtiq);
	$mtii=$mtir['task_id'];  
	 
  $st="update loanadvance set task_id='".$mtii."' where id='".$_REQUEST['id']."'";
  $stq=DB_query($st,$db);
/* $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
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
  $stq=DB_query($st,$db);*/
	 
  header("location:loanadvance_user.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div class="breadcrumb">Home &raquo; <a href="loanadvance_user.php">List of My Loan/Advance</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $_REQUEST['id']?>">Loan Full detail</a></div>
<?php
        
    $un="select employee_name,employee_id from tbl_joinings where program_uid='".$r['empid']."'";
	$unq=DB_query($un,$db);
	$unr=DB_fetch_array($unq);
	
	$sec="select * from tbl_lookups where lookup_id='".$r['section']."'";
	$secq=DB_query($sec,$db);
	$secr=DB_fetch_array($secq);
	
  ?>
<table>
<tr class="oddrow"><td colspan="5" align="center"><h2>Loan Full Details</h2></td>
</tr>
  <tr class="evenrow">
    <td width="140">Employee Id:</td>
    <td width="178" class="ans"><?php echo $unr['employee_id'];?></td>
    <td width="146">Employee Name:</td>
    <td width="158" class="ans"><?php echo $unr['employee_name'];?></td>
  </tr>
  <tr class="oddrow">
    <td width="25%">Period:</td>
    <td width="25%" class="ans"><?php echo $r['period'];?> Months</td>
    <td width="25%">Loan Amount:</td>
    <td width="25%" class="ans"><?php echo round($r['amount']);?></td>
  </tr>
   <tr class="evenrow">
    <td width="25%">Loan Type:</td>
    <td width="25%" class="ans"><?php echo $r['type_loan'];?> </td>
    <td width="25%">Section:</td>
    <td width="25%" class="ans"><?php echo ucwords($secr['lookup_name']);?></td>
  </tr>
  <?php if($r['monthlyinstallment']!=0) { ?>
   <tr class="oddrow">
    <td width="25%">Installment:</td>
    <td width="25%" class="ans"><?php echo round($r['monthlyinstallment']);?> </td>
    <td width="25%">&nbsp;</td>
    <td width="25%" class="ans">&nbsp;</td>
  </tr>
  <?php
  }
  ?>
  
  </table>
 <?php
 $qu="select * from loanadvance_query where loan_id='".$_GET['id']."'";
  $quq=DB_query($qu,$db);
  $qun=DB_num_rows($quq);
  if($qun>0)
  {
  ?>
<table>
<tr class="odd"> <td colspan="4" align="center"><h2>Queries</h2> <?php if($r['querystatus']=='1') {?><span style="float:right"><a href="loanadvance_resubmit.php?id=<?php echo $r['id'];?>">Resubmit</a></span><?php } ?></td>
  </tr>
  <?php
 
  }
  $k=0;
  while($qur=DB_fetch_array($quq))
  { 
  if($k%2==0)
    {
	  $cl="even";
	  }
	  else
	  {
	   $cl="odd";
	  } 
	   $cou="select name from users where uid='".$qur['uid']."'";
		$couq=DB_query($cou,$db);
		$cour=DB_fetch_array($couq);
  ?>
  <tr class="<?php echo $cl;?>"><td colspan="5" align="left"><strong><?php echo $k+1;?>.</strong> <?php echo $qur['query'];?> <strong>By:</strong> <?php echo $cour['name'];?> <strong>On:</strong> <?php echo date('d-m-Y',$qur['date']);?></td></tr>
  
  <?php  $k++; } 
   if($r['comment']!='')
  {
  ?>
  <tr class="oddrow"><td colspan="5" align="center"><h2>Comment</h2> </td></tr>
  <tr class="even"><td colspan="5" align="left"><?php echo $r['comment'];?> </td></tr>
  <?php
  }
  ?>
</table>
<?php
if($r['querystatus']==1)
{ 
?>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="501">
  <tr class="oddrow">
  <td colspan="2"><div class="left">Comment</div><div class="right"><textarea name="remark" cols="28"  rows="5" onkeypress="return alphanumeric(event)"></textarea></div></td></tr>
  <tr class="evenrow"><td align="center" class="back"><a href="loanadvance_user.php">Back</a>&nbsp;&nbsp;<input type="submit" value="Reply" name="reply" />  </td></tr></table>
</form>
<?php
}
?>
<?php include("includes/footer.inc"); ?>
</body>
</html>
<script type="text/javascript">
function clickroute() {
	window.location.href='loanadvance_user_detail.php';
	}
	</script>