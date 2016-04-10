<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="medical_claim_details.php">List of Claims for Approval</a> &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Claim Full detail</a></div>';

$s="select * from medical_claim where id='".$_GET['id']."'";
$q=DB_query($s,$db);
 $r=DB_fetch_array($q);
if(isset($_POST['approve']))
{
	if( $_POST['approvedamount'] <= 0 )
	{
		$er=1;
		 prnMsg(_('Aproved amount should be greater than 0.'),'error');
	}
	if( $_POST['approvedamount'] > $r['net_amount'] )
	{
		$er=1;
		 prnMsg(_('Aproved amount should be less than or equal to net amount.'),'error');
	}
	
	if($er != 1)
	{
	  $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
					$adiq=DB_query($adi,$db);
					$adir=DB_fetch_array($adiq);
					$adr=$adir['doc_id'];
	 
	 /*$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
	 $taq=DB_query($ta,$db);*/
	 
	 updateTask($adir['task_id'],$db);
	 
	  /*$awt="insert into tbl_workflow_task set level='2',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
		 $awtq=DB_query($awt,$db);*/
		 createTask('5',$adr,'',$uid = '',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
			
		$mti="select max(task_id) as task_id from tbl_workflow_task";
		$mtiq=DB_query($mti,$db);
		$mtir=DB_fetch_array($mtiq);
		$mtii=$mtir['task_id'];  

	  $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
									   postedby='".$_SESSION['uid']."',
									   remarks='".$_POST['remark']."',
									   dateon='".strtotime(date('d-M-Y'))."'";
	  $ssq=DB_query($ss,$db);
	  
	  $st="update medical_claim set status='1',approvecomment='".$_POST['remark']."',approvedamount='".$_POST['approvedamount']."',task_id='".$mtii."' where id='".$_GET['id']."'";
	  $stq=DB_query($st,$db);
	  
	  if($ssq)
	  {
		header("location:medical_claim_details.php?msg=Medical Claim MD-".$_GET['id']." Approved");
	  }
	}
}


if(isset($_POST['reject']))
{  
   $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_docket set status='rejected' where doc_id='".$adr."'";
 $taq=DB_query($ta,$db);
 
   $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);
  
   $st="update medical_claim set status='2',rejectcomment='".$_POST['remark']."',approvedamount='".$_POST['approvedamount']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
   updateTask($adir['task_id'],$db);
  
 /* $clft="insert into tbl_workflow_task set message_time='".strtotime(date('d-m-Y'))."',level='".$rslevel['level']."',status='',comment='',doc_id='".$lastdocid."',work_id='".$_GET['id']."'";		
		$clftq=DB_query($clft,$db);		*/
		
  if($ssq)
  {
    header("location:medical_claim_details.php?msg=Medical Claim MD-".$_GET['id']." Rejected");
  }
}

if(isset($_POST['query']))
{  
if($_POST['remark']!='')
 {
  $cu="select emp_id from medical_claim where id='".$_REQUEST['id']."'";
  $cuq=DB_query($cu,$db);
  $cur=DB_fetch_array($cuq);
  $ei="select program_uid from tbl_joinings where employee_id='".$cur['emp_id']."' ";
  $eiq=DB_query($ei,$db);
  $eir=DB_fetch_array($eiq);
  $clu=$eir['program_uid'];
  
  $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 /*$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);*/
 updateTask($adir['task_id'],$db);
 
  /*$awt="insert into tbl_workflow_task set uid='".$clu."',status='0',doc_id='".$adr."'";
	 $awtq=DB_query($awt,$db);*/
	 createTask('',$adr,'',$clu,$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];  

   $ss="insert into mediclaim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);
  
   $st="update medical_claim set status='3',querycomment='".$_POST['remark']."',approvedamount='".$_POST['approvedamount']."',task_id='".$mtii."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  $re="insert into medical_query_comment set claim_id='".$_GET['id']."',enteredby='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  if($ssq)
  {
    header("location:medical_claim_details.php?msg=Medical Claim MD-".$_GET['id']." Queried");
  }
    }
	else
	{
	  echo '<span style="color:#ff0000"><b>Enter Comment</b></span>';	
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
        
    $un="select employee_name from tbl_joinings where employee_id='".$r['emp_id']."'";
	$unq=DB_query($un,$db);
	$unr=DB_fetch_array($unq);
	
	$des="select lookup_name from tbl_lookups where lookup_id='".$r['designation']."'";
    $desq=DB_query($des,$db);
    $desr=DB_fetch_array($desq);
  ?>

<table>
<tr class="oddrow"><td colspan="4" align="center"><h2>Claim Full Details</h2></td>
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
    <td width="25%" class="ans"><?php echo round($r['net_amount']);?></td>
  </tr>
  </table>
 <a style="float:right;" href="bills/<?php echo $r['bill'];?>" target="_blank">View Bill</a>
  <br/><br/>
<table>
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
{  if($ssr['type']!='')
	{
	$t="select * from tbl_lookups where lookup_id='".$ssr['type']."'";
						  $tq=DB_query($t,$db);
						  $tr=DB_fetch_array($tq);
	?>
	 <tr class="oddrow">
		<td class="ans"><?php echo $ssr['detail_cash_name'];?></td>
		<td class="ans"><?php echo $ssr['medicine'];?></td>   
		<td class="ans"><?php echo $tr['lookup_name'];?></td>
		<td class="ans"><?php echo round($ssr['charges']);?></td>
	 </tr>
	  <?php
	  $j++;
	}
  } 
 $qu="select * from medical_query_comment where claim_id='".$_GET['id']."'";
  $quq=DB_query($qu,$db);
  $m=1;
  ?>
  <tr class="oddrow"><td colspan="4" align="center"><h2>Queries/Replies</h2></td></tr>
<?php
  while($qur=DB_fetch_array($quq))
  {
	  if($m%2==0)
	  {
		  $cl = "evenrow";
	  }
	  else
	  {
		  $cl = "oddrow";
	  }
  ?>
  <tr class="<?php print $cl; ?>"><td colspan="2" align="center" class="normal"><?php echo $qur['query'];?></td><td colspan="2" class="normal"><?php echo 'By    <b>'.getUidtoEmployeeName($qur['enteredby'],$db).'</b>'; ?></td></tr>
  <?php $m++; } ?>
   <?php
  if($r['status']==3)
  {
  ?>
  <tr class="oddrow"><td colspan="4" class="center"><a href="medical_claim_details.php" class="back"> Back </a></td></tr>
  <?php
  }
  ?>
</table>
<?php
if($r['status']!=3)
{ 
?>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<br/>

<table>
  <tr class="evenrow"><td>
    <div class="left">Approved Amount</div>
   <div class="right"><input type="text" name="approvedamount"  value="<?php echo $r['net_amount'];?>" class="number"/></div></td>
  </tr>
  <tr class="oddrow"><td>
  <div class="left">Comment</div>
  <div class="right"><textarea name="remark" cols="28"  rows="5" onkeypress="return alphanumeric(event);"></textarea></div></td></tr>
  <tr class="evenrow"><td align="center">

  <table align="center" id="wraper" style="width:500px;">
	  <tr class="evenrow">
  		<td align="center" class="back"><a href="medical_claim_details.php">Back</a></td>
  		<td align="center"> <input type="submit" name="approve" value="Approve" />    </td>
  		<td align="center"><input type="submit" name="reject" value="Reject" /></td>
  		<td align="center"> <input type="submit" value="Query" name="query" /></td>                        
      </tr>
  </table>      

   
 
   
   
  </td></tr></table>
</form>
<?php
}
?>
<?php include("includes/footer.inc"); ?>
</body>
</html>
