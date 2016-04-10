<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

echo ' <div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="tour_claim_details.php">List of Claims for Approval</a> &raquo; <a href="'. $_SERVER['PHP_SELF'].'"?id="'. $_GET['id'].'">Claim Full Detail</a></div>';
$s="select * from tour_claim,tbl_joinings where id='".$_GET['id']."' and tour_claim.emp_id=tbl_joinings.employee_id";
$q=DB_query($s,$db);
$r=DB_fetch_array($q);
if(isset($_POST['approve']))
{ 
	$er=0;
	if( $_POST['approvedamount'] <= 0 )
	{
		$er=1;
		 prnMsg(_('Aproved amount should be greater than 0.'),'error');
	}
	if( $_POST['approvedamount'] > $r['total_amount'] )
	{
		$er=1;
		 prnMsg(_('Aproved amount should not be greater than '.$_POST['approvedamount']),'error');
	}
	if($er != 1)
	{
	  $adi="select doc_id,task_id from tour_claim where id='".$_REQUEST['id']."'";
					$adiq=DB_query($adi,$db);
					$adir=DB_fetch_array($adiq);
					$adr=$adir['doc_id'];
	 
	 /*$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
	 $taq=DB_query($ta,$db);*/
	 updateTask($adir['task_id'],$db);
	 
	 /* $awt="insert into tbl_workflow_task set level='2',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
		 $awtq=DB_query($awt,$db);*/
		 createTask('5',$adr,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
			
			$mti="select max(task_id) as task_id from tbl_workflow_task";
		 $mtiq=DB_query($mti,$db);
		 $mtir=DB_fetch_array($mtiq);
		 $mtii=$mtir['task_id'];  

	  $ss="insert into claim_flow set claim_id='".$_GET['id']."',
									   postedby='".$_SESSION['uid']."',
									   remarks='".$_POST['remark']."',
									   dateon='".strtotime(date('d-M-Y'))."'";
	  $ssq=DB_query($ss,$db);
	  
	  $st="update tour_claim set status='1', total_amount='".$_POST['approvedamount']."',approvedamount='".$_POST['totalamount']."' where id='".$_GET['id']."'";
	  $stq=DB_query($st,$db);
	  
	  if($ssq)
	  {
		header("location:tour_claim_details.php?msg=Tour Claim TA-".$_GET['id']." Approved");
	  }
	}
}

if(isset($_POST['reject']))
{  
$adi="select doc_id,task_id from tour_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_docket set status='rejected' where doc_id='".$adr."'";
 $taq=DB_query($ta,$db);
   updateTask($adir['task_id'],$db);
 
   $ss="insert into claim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);
  
   $st="update tour_claim set status='2',rejectcomment='".$_POST['remark']."',total_amount='".$_POST['approvedamount']."',approvedamount='".$_POST['totalamount']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  if($ssq)
  {
    header("location:tour_claim_details.php?msg=Tour Claim TA-".$_GET['id']." Rejected");
  }
}

if(isset($_POST['query']))
{  
  if($_POST['remark']!='')
   {
   $cu="select emp_id from tour_claim where id='".$_REQUEST['id']."'";
  $cuq=DB_query($cu,$db);
  $cur=DB_fetch_array($cuq);
  $ei="select program_uid from tbl_joinings where employee_id='".$cur['emp_id']."' ";
  $eiq=DB_query($ei,$db);
  $eir=DB_fetch_array($eiq);
  $clu=$eir['program_uid'];
   $adi="select doc_id,task_id from tour_claim where id='".$_REQUEST['id']."'";
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

   $ss="insert into claim_flow set claim_id='".$_GET['id']."',
                                   postedby='".$_SESSION['uid']."',
								   remarks='".$_POST['remark']."',
								   dateon='".strtotime(date('d-M-Y'))."'";
  $ssq=DB_query($ss,$db);
  
   $st="update tour_claim set status='3',querycomment='".$_POST['remark']."',total_amount='".$_POST['approvedamount']."',approvedamount='".$_POST['totalamount']."',task_id='".$mtii."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  $re="insert into tour_query_comment set claim_id='".$_GET['id']."',enteredby='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  if($ssq)
  {
    header("location:tour_claim_details.php?msg=Tour Claim TA-".$_GET['id']." Queried");
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
  
  
  ?>
 
<table>
<tr class="oddrow">
<td colspan="4" align="center"><h2>Claim Full Detail</h2></td>
</tr>
  <tr class="evenrow">
    <td width="25%"><strong>Employee Id:</strong></td>
    <td width="25%" class="ans"><?php echo $r['emp_id'];?></td>
    <td width="25%"><strong>Employee Name:</strong></td>
    <td width="25%" class="ans"><?php echo $r['employee_name'];?></td>
  </tr>
  <tr class="oddrow">
    <td><strong>Basic Pay:</strong></td>
    <td class="ans"><?php echo round($r['basic_pay']);?></td>
    <td 	><strong>Total Amount:</strong></td>
	<td><?php echo round($r['total_amount']);?></td>
   </tr>
  <?php
  ?>
</table>
<a style="float:right;" href="bills/<?php echo $r['bill_claim'];?>" target="_blank"> View Bill</a>
  
<br/>
<table>
<tr class="oddrow">
<td colspan="8" align="center"><h2>Travel Details</h2></td></tr>
  <tr class="evenrow">
    <td><strong>Departure Station</strong></td>
    <td><strong>Arrival Station</strong></td>
    <td><strong>Departure Date</strong></td>
    <td><strong>Arrival Date</strong></td>
    <td><strong>No. of Km</strong></td>
    <td><strong>Mode of Journey</strong></td>
    <td><strong>TA</strong></td>
    <td><strong>DA</strong></td>
  </tr>
 
<?php
 $ss="select * from tour_claim_detail where claim_id='".$_GET['id']."'";
$ssq=DB_query($ss,$db);
while($ssr=DB_fetch_array($ssq))
{
?>
 <tr class="oddrow">
    <td class="ans"><?php echo $ssr['dep_station'];?></td>
    <td class="ans"><?php echo $ssr['arr_station'];?></td>
    <td class="ans"><?php echo date('d-M-Y',$ssr['dep_date']);?></td>
    <td class="ans"><?php echo date('d-M-Y',$ssr['arr_date']);?></td>
    <td class="ans"><?php echo $ssr['no_of_km'];?></td>
    <td class="ans"><?php echo $ssr['mode_journey'];?></td>
    <td class="ans"><?php echo round(abs($ssr['amount']));?></td>
    <td class="ans"><?php echo round(abs($ssr['daily_allowance']));?></td>
 </tr>
  <?php
  }
  $qu="select * from tour_query_comment where claim_id='".$_GET['id']."'";
  $quq=DB_query($qu,$db);
  $m=1;
  ?>
  <tr class="oddrow"><td colspan="8" align="center"><h2>Queries/Replies</h2></td></tr>
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
  <tr class="<?php print $cl; ?>"><td align="center" class="normal"><?php echo $qur['query'];?></td><td colspan="7" class="normal"><?php echo 'By    <b>'.getUidtoEmployeeName($qur['enteredby'],$db).'</b>'; ?></td></tr>
  <?php $m++;} ?>
  <?php
  if($r['status']==3)
  {
  ?>
  <tr class="oddrow"><td colspan="7" align="center"> <input type="button" name="back" value="Back" onclick="changeurl()" /></td></tr>
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
<tr class="oddrow">
    <td width="100%">
	<div class="left">Approved Amount</div>
    <div class="right"><input type="text" name="approvedamount" class="number"  value="<?php echo $r['total_amount'];?>"/><input type="hidden" name="totalamount"   value="<?php echo $r['total_amount'];?>"/></div>
 </td> 
 </tr>
  <tr class="evenrow">
  <td>
  <div class="left">Remarks</div>
  <div class="right"><textarea name="remark" cols="28"  rows="5" onkeypress="return alphanumeric(event)"></textarea></div>
  </td>
  </tr>
  <tr class="oddrow">
  <td align="center" colspan="2"> <input type="button" name="back" value="Back" onclick="changeurl()" /> <input type="submit" name="approve" value="Approve" />   <input type="submit" name="reject" value="Reject" /> <input type="submit" value="Query" name="query" /></td></tr></table>
</form>
<?php
}
?>
<?php include("includes/footer.inc"); ?>

</body>
</html>
<script>
function changeurl()
{
 window.location.href='tour_claim_details.php';
}
</script>