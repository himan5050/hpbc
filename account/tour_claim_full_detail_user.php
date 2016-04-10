<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$s="select * from tour_claim where id='".$_GET['id']."'";
$q=DB_query($s,$db);
$r=DB_fetch_array($q);
if(isset($_POST['reply']))
{
 $re="insert into tour_query_comment set claim_id='".$_GET['id']."',enteredby='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  
  $adi="select doc_id,task_id from tour_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
	$corp=0;
	 $role = getRole($_SESSION['uid'],$db);
	if( $role != 13 && $role != 5 && $role != 19 )
		$corp=getCorporationBranch($_SESSION['uid'],$db);
	 $level = 1;
	 if($role == 13)
	{
		 $corp=getCorporationBranch($_SESSION['uid'],$db);
		 $level = 2;
	}
	 if($role == 5)
		 $level = 3;
	 if($role == 19)
		 $level = 4;
 /*$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);*/
 updateTask($adir['task_id'],$db);
 
  /*$awt="insert into tbl_workflow_task set level='1',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);*/
	 createTask($level,$adr,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];  
	 
	  $st="update tour_claim set status='0', task_id='".$mtii."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
	 
  header("location:tour_claim_user.php");
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
  <div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="tour_claim_user.php">List of My Claim</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $_REQUEST['id'];?>">Claim Full detail</a></div>
<table>
<tr class="oddrow">
<td colspan="4" align="center"><h2>Claim Full Detail<h2></td>
</tr>
  <tr class="evenrow">
    <td width="25%"><strong>Employee Id:</strong></td>
    <td width="25%" class="ans"><?php echo $r['emp_id'];?></td>
    <td width="25%"><strong>Employee Name:</strong></td>
    <td width="25%" class="ans"><?php echo ucwords($unr['employee_name']);?></td>
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
    <td width="111"><strong>Departure Station</strong></td>
    <td width="100"><strong>Arrival Station</strong></td>
    <td width="104"><strong>Departure Date</strong></td>
    <td width="86"><strong>Arrival Date</strong></td>
    <td width="71"><strong>No. of Km</strong></td>
    <td width="93"><strong>Mode of Journey</strong></td>
    <td width="94"><strong>TA</strong></td>
    <td width="94"><strong>DA</strong></td>
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
    <td class="ans"><?php echo round($ssr['amount']);?></td>
    <td class="ans"><?php echo round($ssr['daily_allowance']);?></td>
 </tr>
  <?php
  }
 $qu="select * from tour_query_comment where claim_id='".$_GET['id']."'";
  $quq=DB_query($qu,$db);
  $qun=DB_num_rows($quq);
  if($qun>0)
  { ?>
	    <tr >
<td colspan="8" align="center">&nbsp;</td></tr>
       <tr class="oddrow">
<td colspan="8" align="center"><h2>Query</h2></td></tr>
  <?php }
  $qun=1;
  while($qur=DB_fetch_array($quq))
  { 
  ?>
  <tr><td colspan="8" class="evenrow"><?php echo $qur['query'];?>  <b>On</b>  <?php echo date('d-m-Y',$qur['date']);?></td></tr>
  <?php $qun++; } ?>
</table>
<?php  if($r['status']==3)
  { ?>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="303">

  <tr class="oddrow"><td width="60">Remarks</td>
  <td width="231"><textarea name="remark" cols="50"  rows="5" onkeypress="return alphanumeric(event)"></textarea></td></tr>
  <tr class="evenrow"><td align="center" class="back" colspan="2"><a href="tour_claim_user.php">Back</a><input type="submit" value="Reply" name="reply"  /></td></tr></table>
</form>
<?php
}
?>
<?php include("includes/footer.inc"); ?>

</body>
</html>
