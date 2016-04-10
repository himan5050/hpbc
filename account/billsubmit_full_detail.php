<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$s="select * from billsubmit where id='".$_GET['id']."'";
$q=DB_query($s,$db);
 $r=DB_fetch_array($q);
  ?>
 <div class="breadcrumb">Home &raquo; <a href="billsubmit_detail.php">List of Bill for Approval</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $_REQUEST['id'];?>">Bill Full detail</a></div>
 <?php
if(isset($_POST['approve']))
{
	
	if($_POST['installment'] <= 0 ){
	echo '<div class="error">' . _('Amount should not be less then zero') . '</div>';
		
	}
	
	else if($r['amount'] < $_POST['installment']){
	echo '<div class="error">' . _(' Amount should not be more then bill Amount') . '</div>';	
	}else{
  $adi="select doc_id,task_id from billsubmit where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
  updateTask($adir['task_id'],$db);
/* $ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);
 
  $awt="insert into tbl_workflow_task set level='2',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];  

  $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);*/
  
  $st="update billsubmit set approvecomment='".$_POST['remark']."',approvestatus='1',approveamount='".$_POST['installment']."',status='1' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  
 
  
  voucherentry($_GET['id'],'','billsubmit',$_POST['installment'],'','','2',$db);
  
  if($stq)
  {
    header('location:billsubmit_detail.php?msg=Bill is approved');
  }
}
}

if(isset($_POST['reject']))
{  
   /*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_docket set status='rejected' where doc_id='".$adr."'";
 $taq=DB_query($ta,$db);
 
   $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);*/
  
   $st="update billsubmit set status='2',rejectcomment='".$_POST['remark']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  
 /* $clft="insert into tbl_workflow_task set message_time='".strtotime(date('d-m-Y'))."',level='".$rslevel['level']."',status='',comment='',doc_id='".$lastdocid."',work_id='".$_GET['id']."'";		
		$clftq=DB_query($clft,$db);		*/
		
  if($stq)
  {
    header("location:billsubmit_detail.php?msg=Bill is rejected");
  }
}

if(isset($_POST['query']))
{  
  $cu="select empid from loanadvance where id='".$_REQUEST['id']."'";
  $cuq=DB_query($cu,$db);
  $cur=DB_fetch_array($cuq);
  /*$ei="select program_uid from tbl_joinings where employee_id='".$cur['emp_id']."' ";
  $eiq=DB_query($ei,$db);
  $eir=DB_fetch_array($eiq);
  $clu=$eir['program_uid'];
  
  $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);
 
  $awt="insert into tbl_workflow_task set uid='".$clu."',status='0',doc_id='".$adr."'";
	 $awtq=DB_query($awt,$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];  

   $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);*/
  
   $st="update loanadvance set querycomment='".$_POST['remark']."',querystatus='1',monthlyinstallment='".$_POST['installment']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  $re="insert into loanadvance_query set loan_id='".$_GET['id']."',uid='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  if($req)
  {
    header("location:loanadvance_detail.php");
  }
}

if(isset($_POST['comment']))
{  
 
  /*$ei="select program_uid from tbl_joinings where employee_id='".$cur['emp_id']."' ";
  $eiq=DB_query($ei,$db);
  $eir=DB_fetch_array($eiq);
  $clu=$eir['program_uid'];
  
  $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);
 
  $awt="insert into tbl_workflow_task set uid='".$clu."',status='0',doc_id='".$adr."'";
	 $awtq=DB_query($awt,$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];  

   $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);*/
  
   $st="update loanadvance set comment='".$_POST['remark']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
 
  if($stq)
  {
    header("location:loanadvance_detail.php");
  }
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

 <?php
        
    $un="select employee_name,employee_id from tbl_joinings where program_uid='".$r['empid']."'";
	$unq=DB_query($un,$db);
	$unr=DB_fetch_array($unq);
	
	
  ?>

<table>
<tr class="oddrow"><td colspan="4" align="center"><h2>Bill Full Details</h2></td>
</tr>
  <tr class="evenrow">
    <td width="25%">Bill Date:</td>
    <td width="25%" class="ans"><?php echo date('d-m-Y',$r['date']);?></td>
    <td width="25%">Vender Name:</td>
    <td width="25%" class="ans"><?php echo $r['name'];?></td>
  </tr>
  <tr class="oddrow">
    <td width="25%">Remarks:</td>
    <td width="25%" class="ans"><?php echo $r['remarks'];?></td>
    <td width="25%">Bill Amount:</td>
    <td width="25%" class="ans"><?php echo round($r['amount']);?></td>
  </tr>
   <tr class="evenrow">
    <td width="25%">Budget Allocated:</td>
    <td width="25%" class="ans"><?php echo round($r['budget_allocated']);?></td>
    <td width="25%"></td>
    <td width="25%" class="ans"></td>
  </tr>
  <?php if($r['bill'] !='') {?>
   <tr class="oddrow">
    <td  colspan="4" align="right"><a href="../sites/default/files/bill/<?php echo $r['bill'];?>" target="_blank">View Bills</a></td>
    
  </tr>
   <?php } ?>
  </table>
 
  <br />
<?php /*?><table><tr class="odd">
  	
  <?php
  
 $qu="select * from loanadvance_query where loan_id='".$_GET['id']."'";
  $quq=DB_query($qu,$db);
  $qun=DB_num_rows($quq);
  if($qun>0)
  { ?>
  <td colspan="4" align="center"><h2>Queries</h2> </td>
  </tr>
  <?php
  }$k=0;
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
  <tr class="<?php echo $cl;?>"><td colspan="4" align="center"><?php echo $qur['query'];?> <strong>By:</strong> <?php echo $cour['name'];?> <strong>On:</strong> <?php echo date('d-m-Y',$qur['date']);?></td></tr>
  <?php $k++; } 
  if($r['comment']!='')
  {
  ?>
  <tr class="odd"><td colspan="4" align="center"><h2>Comment</h2> </td></tr>
  <tr class="even"><td colspan="4" align="center"><?php echo $r['comment'];?> </td></tr>
  <?php
  }
  ?>
</table><?php */?>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<br/><table>
 <tr class="evenrow"><td>
    <div class="left">Approved Amount:</div>
   <div class="right"><input type="text" name="installment" maxlength="11" class="number" value="<?php echo $r['amount'];?>"/></div></td>
  </tr>
<?php
if($r['approvestatus']!=1)
{ 
?>



  <tr class="oddrow"><td>
  <div class="left">Comment:</div>
  <div class="right"><textarea name="remark" cols="28"  rows="5" onKeyPress="return alphanumeric(event);"></textarea></div></td></tr>
  <tr class="evenrow"><td align="center"><!--<a href="billsubmit_detail.php"><input type="button" name="back" value="Back"></a>--> <input type="submit" name="approve" value="Approve" />    <input type="submit" name="reject" value="Reject" /> <!--<input type="submit" value="Query" name="query" /> <input type="submit" value="Comment" name="comment" />-->  </td></tr>

<?php
}
?>
</table>
</form>
<?php include("includes/footer.inc"); ?>
</body>
</html>
