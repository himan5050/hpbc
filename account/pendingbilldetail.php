<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$s="select * from billsubmit where id='".$_GET['id']."'";
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
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $_REQUEST['id'];?>">Bill Full detail</a></div>
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
  <?php if($r['bill'] !='') {?>
   <tr class="evenrow">
    <td  colspan="4" align="right"><a href="../sites/default/files/bill/<?php echo $r['bill'];?>" target="_blank">View Bills</a></td>
    
  </tr>
   <?php } ?>
  </table>
 
  <br />

<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<br/><table>
 <tr class="evenrow"><td>
    <div class="left">Approved Amount:</div>
   <div class="right"><input type="text" name="installment" maxlength="11" class="number" value="<?php echo $r['amount'];?>" readonly="readonly"/></div></td>
  </tr>

</table>
</form>
<?php include("includes/footer.inc"); ?>
</body>
</html>
