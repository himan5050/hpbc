<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');



$s="select * from loanadvance where id='".$_GET['id']."'";
$q=DB_query($s,$db);
 $r=DB_fetch_array($q);





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $_REQUEST['id'];?>">Loan Full detail</a></div>
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
<table><tr class="odd">
  	
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
  <tr class="<?php echo $cl;?>"><td colspan="4" align="left"><strong><?php echo $k+1;?>.</strong> <?php echo $qur['query'];?> <strong>By:</strong> <?php echo $cour['name'];?> <strong>On:</strong> <?php echo date('d-m-Y',$qur['date']);?></td></tr>
  <?php $k++; } 
  if($r['comment']!='')
  {
  ?>
  <tr class="odd"><td colspan="4" align="center"><h2>Comment</h2> </td></tr>
  <tr class="even"><td colspan="4" align="left"><b>1.</b> <?php echo $r['comment'];?> </td></tr>
  <?php
  }
  ?>
</table>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<br/><table>
 <tr class="evenrow"><td>
    <div class="left">Installment:</div>
   <div class="right"><input type="text" name="installment" maxlength="11" class="number" value="<?php echo $r['monthlyinstallment'];?>" readonly="readonly"/></div></td>
  </tr>

</table>
</form>
<?php include("includes/footer.inc"); ?>
</body>
</html>
