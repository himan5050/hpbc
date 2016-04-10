<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<div class="breadcrumb">Home &raquo; <a href="loanadvance_detail.php">List of Loan for Approval</a> &raquo; <a href="'. $_SERVER['PHP_SELF'].'"?id="'.$_REQUEST['id'].'">Loan Full detail</a></div>';
/*function paypay($field_name,$value,$field){
 $val = explode(".",$value);
 if(sizeof($val) > 2){
   form_set_error($field_name,'Only numbers and one dot are allowed in '.$field.'.');
    return 1;
 }
  $AllowRegex  = "^([\.0-9]+([0-9]+)?$)";
 // $AllowRegex1 = "(^\d*\.?\d*[1-9]+\d*$)|(^[1-9]+\d*\.\d*$)";
    //  $AllowRegex  = " ^[0-9]\d*(\.\d+)?$";
		if(!eregi($AllowRegex,$value)){
          form_set_error($field_name,'Only numbers and one dot are allowed in '.$field.'.');
          return 1;
        }
        return 0;
}*/

$s="select * from loanadvance where id='".$_GET['id']."'";
$q=DB_query($s,$db);
 $r=DB_fetch_array($q);
 
 
if(isset($_POST['approve']))
{

 $val = explode(".",$_POST['installment']);
 if(sizeof($val) > 2){
	$er=1;
	 prnMsg(_('Enter valid Amount, Numeric Value And One . is Accepted'),'error');
 }
if( $_POST['installment'] <= 0 )
{
	$er=1;
	 prnMsg(_('Installment amount should be greater than 0.'),'error');
}
 if($_POST['installment'] > $r['amount'])
 {
	 $er=1;
	 prnMsg(_('Installment Can Not Be Greater Than Loan Amount'),'error');
 }
   /*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
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
  if($er!=1)
  {
	    $adi="select doc_id,task_id from loanadvance where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 /*$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);*/
 
 updateTask($adir['task_id'],$db);
 
  /*$awt="insert into tbl_workflow_task set level='2',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);*/
	 //createTask('2',$adr,'',$uid = '',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
	 	
		//$mti="select max(task_id) as task_id from tbl_workflow_task";
	 //$mtiq=DB_query($mti,$db);
	// $mtir=DB_fetch_array($mtiq);
	 //$mtii=$mtir['task_id'];  
	 
  $st="update loanadvance set approvecomment='".$_POST['remark']."',approvestatus='1',monthlyinstallment='".$_POST['installment']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  
  /*loan sanctioned here insert into vouchsup for salary process*/
  //getting all data from loanadvance 
  
  $sqlget = "select empid,type_loan,amount,period,monthlyinstallment,id from  loanadvance where id='".$_GET['id']."'";
  $resget = DB_query($sqlget,$db);
  $rsget = DB_fetch_array($resget);
  $currentdate = date('Y-m-d');
  if($rsget['type_loan'] == 'House And Building Advance'){
    $typeloan = 7;
  }
  if($rsget['type_loan'] == 'Vehicle Advance'){
    $typeloan = 8;
  }
  if($rsget['type_loan'] == 'Warm Clothing Advance'){
    $typeloan = 9;
  }
   if($rsget['type_loan'] == 'Festival Advance'){
    $typeloan = 10;
  }
  
    $mti="select max(Vno) as vno from vouchsup";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['Vno']+1;
	 
	 
  $sqlsal = "INSERT INTO `vouchsup` (`Vno`, `vdate`, `EmpCode`, `DeductCode`, `Narr`, `Vamt`, `NoOfMonth`, `EmiAmount`, `Acccode`) VALUES 
('".$mtii."', '".$currentdate."', '".getUidtoEmployeeId($rsget['empid'],$db)."', '".$typeloan."', 'Loan And advance', 
'".$rsget['amount']."', '".$rsget['period']."', '".$rsget['monthlyinstallment']."','".$rsget['id']."')";
$query=DB_query($sqlsal,$db);

  
  /*end*/
  
  voucherentry($_GET['id'],'','loanadvance',$r['amount'],'','','2',$db);
  
  if($stq)
  {
    header("location:loanadvance_detail.php?msg=Loan Application Approved Successfully");
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
  $val = explode(".",$_POST['installment']);
 if(sizeof($val) > 2){
	$er=1;
	 prnMsg(_('Enter valid Amount, Numeric Value And One . is Accepted'),'error');
 }
 
  if($_POST['installment'] > $r['amount'])
 {
	 $er=1;
	 prnMsg(_('Installment Can Not Be Greater Than Loan Amount'),'error');
 }
 
 if($er!=1)
  { 
    $adi="select doc_id,task_id from loanadvance where id='".$_REQUEST['id']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 $ta="update tbl_workflow_docket set status='rejected' where doc_id='".$adr."'";
 $taq=DB_query($ta,$db);
 
   $st="update loanadvance set status='2',rejectcomment='".$_POST['remark']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  updateTask($adir['task_id'],$db);
 
 /* $clft="insert into tbl_workflow_task set message_time='".strtotime(date('d-m-Y'))."',level='".$rslevel['level']."',status='',comment='',doc_id='".$lastdocid."',work_id='".$_GET['id']."'";		
		$clftq=DB_query($clft,$db);		*/
		
  if($stq)
  {
    header("location:loanadvance_detail.php?msg=Loan Application Rejected Successfully!");
  }
  }
}

if(isset($_POST['query']))
{  
  $cu="select empid from loanadvance where id='".$_REQUEST['id']."'";
  $cuq=DB_query($cu,$db);
  $cur=DB_fetch_array($cuq);
   $clu=$cur['empid'];
  $val = explode(".",$_POST['installment']);
 if(sizeof($val) > 2){
	$er=1;
	 prnMsg(_('Enter valid Amount, Numeric Value And One . is Accepted'),'error');
 }
 
  if($_POST['installment'] > $r['amount'])
 {
	 $er=1;
	 prnMsg(_('Installment Can Not Be Greater Than Loan Amount'),'error');
 }
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
  if($er!=1)
  { 
     $adi="select doc_id,task_id from loanadvance where id='".$_REQUEST['id']."'";
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

   $st="update loanadvance set querycomment='".$_POST['remark']."',querystatus='1',replystatus='0',monthlyinstallment='".$_POST['installment']."',task_id='".$mtii."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
  $re="insert into loanadvance_query set loan_id='".$_GET['id']."',uid='".$_SESSION['uid']."',query='".$_POST['remark']."',date='".strtotime(date('d-m-Y'))."'";
  $req=DB_query($re,$db);
  if($req)
  {
    header("location:loanadvance_detail.php?msg=Loan Application Queried Successfully!");
  }
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
  
   $val = explode(".",$_POST['installment']);
 if(sizeof($val) > 2){
	$er=1;
	 prnMsg(_('Enter valid Amount, Numeric Value And One . is Accepted'),'error');
 }
 
  if($_POST['installment'] > $r['amount'])
 {
	 $er=1;
	 prnMsg(_('Installment Can Not Be Greater Than Loan Amount'),'error');
 }
 
  if($er!=1)
  {
   $st="update loanadvance set comment='".$_POST['remark']."' where id='".$_GET['id']."'";
  $stq=DB_query($st,$db);
 
  if($stq)
  {
    header("location:loanadvance_detail.php?msg=Loan Application Commented Successfully!");
  }
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
	
	$sec="select * from tbl_lookups where lookup_id='".$r['section']."'";
	$secq=DB_query($sec,$db);
	$secr=DB_fetch_array($secq);
	
  ?>

<table>
<tr class="oddrow"><td colspan="5" align="center"><h2>Loan Full Details</h2></td>
</tr>
  <tr class="evenrow">
    <td width="200">Employee Id:</td>
    <td class="ans"><?php echo $unr['employee_id'];?></td>
    <td width="200">Employee Name:</td>
    <td width="30%" class="ans"><?php echo $unr['employee_name'];?></td>
  </tr>
  <tr class="oddrow">
    <td>Period:</td>
    <td class="ans"><?php echo $r['period'];?> Months</td>
    <td>Loan Amount:</td>
    <td class="ans"><?php echo round($r['amount']);?></td>
  </tr>
   <tr class="evenrow">
    <td>Loan Type:</td>
    <td class="ans"><?php echo $r['type_loan'];?> </td>
    <td>Section:</td>
    <td class="ans"><?php echo ucwords($secr['lookup_name']);?></td>
  </tr>
  </table>
 
  <br />

  	
  <?php
  
 $qu="select * from loanadvance_query where loan_id='".$_GET['id']."'";
  $quq=DB_query($qu,$db);
  $qun=DB_num_rows($quq);
  if($qun>0)
  { ?><table><tr class="odd">
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
  <tr class="<?php echo $cl;?>"><td colspan="4" align="left"><strong><?php echo $k+1;?>.</strong> <?php echo $qur['query'];?> <strong>By:</strong> <?php echo $cour['name'];?> <strong>On:</strong> <?php echo date('d-m-Y',$qur['date']);?></td></tr>
  <?php $k++; } 
  if($r['comment']!='')
  {
  ?>
  <tr class="odd"><td colspan="4" align="center"><h2>Comment</h2> </td></tr>
  <tr class="even"><td colspan="4" align="left"><b>1.</b> <?php echo $r['comment'];?> </td></tr></table>
  <?php
  }
  ?>

<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<br/><table>
 <tr class="evenrow"><td>
    <div class="left">Installment:</div>
   <div class="right"><input type="text" name="installment" maxlength="11" class="number" value="<?php echo $r['monthlyinstallment'];?>"/></div></td>
  </tr>
<?php
if($r['approvestatus']!=1)
{ 
?>



  <tr class="oddrow"><td>
  <div class="left">Comment:</div>
  <div class="right"><textarea name="remark" cols="28"  rows="5" onkeypress = "return alphanumericdot(event)"></textarea></div></td></tr>
  <tr class="evenrow"><td class="back" align="center"><a href="loanadvance_detail.php">Back</a> <input type="submit" name="approve" value="Approve" />    <input type="submit" name="reject" value="Reject" /> <?php if( $r['querystatus'] != 1 ) echo '<input type="submit" value="Query" name="query" />'; ?> <input type="submit" value="Comment" name="comment" />  </td></tr>

<?php
}
?>
</table>
</form>
<?php include("includes/footer.inc"); ?>
</body>
</html>
