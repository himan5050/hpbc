<?php


//$protocol = $_SERVER['HTTPS'] ? "https" : "http";
  //echo  $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  //echo '<pre>';
//print_r($_SERVER);exit;
$u=explode('/',$_SERVER['REQUEST_URI']);
	$StrictXHTML=False;
//echo $u;
	if (!headers_sent()){
		if ($StrictXHTML) {
			header('Content-type: application/xhtml+xml; charset=utf-8');
		} else {
			header('Content-type: text/html; charset=utf-8');
		}
	}


/* Code for validation Vikas and neshat khan start*/

/*$leng=count($u);
$ref=$leng-1;
$filename=$u[$ref];
$uid=$_SESSION['uid'];
$true = userauthvalidate($filename , $uid,$db);
if($true == 0){
  header('LOCATION:/'.$u[1].'/access_denied');
}

function userauthvalidate($filename , $uid,$db){
    $rid = getRolebyUid($uid,$db);
  
   if($filename == "chequebook.php"){
       $router_path = 'chequebook';
   }
   else if($filename == "billsubmitreport.php"){
       $router_path = 'billsubmitreport';
   }
   else if($filename == "billsubmit_detail.php"){
       $router_path = 'billsubmit_detail';
   }
   else if($filename == "cashflow.php"){
       $router_path = 'Cashflow';
   }
   else if($filename == "advance_detail_report.php"){
       $router_path = 'advance_detail_report';
   }
   else if($filename == "advance_summery.php"){
       $router_path = 'advance_summery';
   }
   else if($filename == "incomeexp.php"){
       $router_path = 'IncomeExpenditure1';
   }
    else if($filename == "loanadvance_detail.php"){
       $router_path = 'loanadvance_detail';
   }
   else if($filename == "loanapplication.php"){
       $router_path = 'loanapplication';
   }
   else if($filename == "GLBalanceSheet.php"){
       $router_path = 'BalanceSheet2';
   }
    else if($filename == "trialbalance.php"){
       $router_path = 'TrialBalance';
   }
   else if($filename == "journalbook.php"){
       $router_path = 'journal_book';
   }
    else if($filename == "GLProfit_Loss.php"){
       $router_path = 'profit_loss';
   }
    else if($filename == "BankMatching.php?Type=Payments"){
       $router_path = 'payment_reconcillation';
   }
   else if($filename == "BankMatching.php?Type=Receipts"){
       $router_path = 'receipt_reconcillation';
   }
   else if($filename == "auditschedulereport.php"){
       $router_path = 'auditschedulereport';
   }
    else if($filename == "assign_item.php"){
       $router_path = 'issue_item';
   }
   else if($filename == "claim_details.php"){
       $router_path = 'claim_detail_report';
   }
    else if($filename == "StockAdjustments.php"){
       $router_path = 'inventory_adjustments';
   }
   else if($filename == "medical_claim_details.php"){
       $router_path = 'medical_claim_details';
   }
    else if($filename == "claim_type.php"){
       $router_path = 'claim_type';
   }
   else if($filename == "claim_details.php"){
       $router_path = 'claim_details_report';
   }
   else if($filename == "tour_claim_details.php"){
       $router_path = 'tour_claim_details';
   }
   else if($filename == "auditreport.php"){
       $router_path = 'auditreport';
   }
    else if($filename == "audit_plan.php"){
       $router_path = 'audit_plan';
   }
   else if($filename == "medical_claim_approve.php"){
       $router_path = 'medical_claim_approve';
   }
   else if($filename == "medical_claim_user.php"){
       $router_path = 'medical_claim_user';
   }
   else if($filename == "tour_claim_approve.php"){
       $router_path = 'tour_claim_approve';
   }
   else if($filename == "tour_claim_user.php"){
       $router_path = 'tour_claim_user';
   }
   else if($filename == "tour_claim.php"){
       $router_path = 'tour_claim';
   }
   else if($filename == "audit_mylist.php"){
       $router_path = 'audit_mylist';
   }
   else if($filename == "audit_report_approvelist.php"){
       $router_path = 'audit_report_approvelist';
   }
   else if($filename == "audit_correctivelist.php"){
       $router_path = 'audit_correctivelist';
   }
    else if($filename == "BankReconciliation.php"){
       $router_path = 'bank_Reconciliation';
   }
   else if($filename == "GLAccountInquiry.php"){
       $router_path = 'Account_Listing';
   }
   else if($filename == "GLAccountInquiry.php"){
       $router_path = 'Account_Inquiry';
   }
   else if($filename == "GLTrialBalance.php"){
       $router_path = 'Trial_Balance';
   }
   else if($filename == "dailycashbook.php"){
       $router_path = 'Daily_Cash_Book';
   }



   $returnval = getvaluebyuid($router_path, $rid,$db);
}

function getRolebyUid($uid,$db){
   $sqlrole = "select * from users_roles where uid='".$uid."'";
  $res = DB_query($sqlrole,$db);
  $rs = DB_fetch_array($res);

   return $rs['rid'];
} 

function getvaluebyuid($router_path, $rid,$db){
    $sql = "select mlid from menu_links where router_path='".$router_path."'";
	$res = DB_query($sql,$db);
	if($rs = DB_fetch_array($res)){
	  $mlid = $rs['mlid'];
	   $sqlr = "select mlid as m from menu_per_role  where rids REGEXP ('".$rid.",|".$rid."$') AND mlid =	'".$mlid."'";
	  $resr = DB_query($sqlr,$db);
	  if($rsr = DB_fetch_array($resr)){
	    return 1;
	  }else{
	    return 0;
	  } 

	}
}

function chequebook(){
  global  $base_url;
  drupal_goto($base_url.'/account/chequebook.php');
	}

function billsubmitreport(){
  global  $base_url;
  drupal_goto($base_url.'/account/billsubmitreport.php');
}

function billsubmit_detail(){
  global  $base_url;
  drupal_goto($base_url.'/account/billsubmit_detail.php');
}

function billsubmit(){
  global  $base_url;
  drupal_goto($base_url.'/account/billsubmit.php');
}

function Cashflow(){
  global  $base_url;
  drupal_goto($base_url.'/account/cashflow.php');
}

function advance_detail_report(){
  global  $base_url;
  drupal_goto($base_url.'/account/advance_detail_report.php');
}

function advance_summery(){
  global  $base_url;
  drupal_goto($base_url.'/account/advance_summery.php');
}

function IncomeExpenditure1(){
  global  $base_url;
  drupal_goto($base_url.'/account/incomeexp.php');

}
function e_samadhan_page(){
drupal_goto('http://admis.hp.nic.in/esamadhan/');

}
function loanadvance_detail(){
   global  $base_url;
  drupal_goto($base_url.'/account/loanadvance_detail.php');

}

function loanapplication(){
   global  $base_url;
  drupal_goto($base_url.'/account/loanapplication.php');
}

function BalanceSheet2(){
   global  $base_url;
  drupal_goto($base_url.'/account/GLBalanceSheet.php');

}

function TrialBalance(){
   global  $base_url;
  drupal_goto($base_url.'/account/trialbalance.php');

}


function journal_book(){
   global  $base_url;
  drupal_goto($base_url.'/account/journalbook.php');

}

function profit_loss(){
   global  $base_url;
  drupal_goto($base_url.'/account/GLProfit_Loss.php');

}

function payment_reconcillation(){
   global  $base_url;
  drupal_goto($base_url.'/account/BankMatching.php?Type=Payments');


}

function receipt_reconcillation(){
   global  $base_url;
  drupal_goto($base_url.'/account/BankMatching.php?Type=Receipts');


}

function auditschedulereport(){
   global  $base_url;
  drupal_goto($base_url.'/account/auditschedulereport.php');


}

function issue_item(){
   global  $base_url;
  drupal_goto($base_url.'/account/assign_item.php');


}


function claim_detail_report(){
   global  $base_url;
  drupal_goto($base_url.'/account/claim_details.php');


}


function inventory_adjustments(){
   global  $base_url;
  drupal_goto($base_url.'/account/StockAdjustments.php');


}

function medical_claim_details(){
   global  $base_url;
  drupal_goto($base_url.'/account/medical_claim_details.php');


}

function claim_type(){

   global  $base_url;
  drupal_goto($base_url.'/account/claim_type.php');


}
function claim_details_report(){
   global  $base_url;
  drupal_goto($base_url.'/account/claim_details.php');


}

function tour_claim_details(){
   global  $base_url;
  drupal_goto($base_url.'/account/tour_claim_details.php');


}
function auditreport(){
   global  $base_url;
  drupal_goto($base_url.'/account/auditreport.php');
}
function audit_plan(){
   global  $base_url;
  drupal_goto($base_url.'/account/audit_plan.php');

}
function medical_claim_approve(){
   global  $base_url;
  drupal_goto($base_url.'/account/medical_claim_approve.php');
  

}
function medical_claim_user(){
   global  $base_url;
  drupal_goto($base_url.'/account/medical_claim_user.php');
  
}
function tour_claim_approve(){
   global  $base_url;
  drupal_goto($base_url.'/account/tour_claim_approve.php');


}
function tour_claim_user(){
   global  $base_url;
  drupal_goto($base_url.'/account/tour_claim_user.php');


}
function tour_claim(){
   global  $base_url;
  drupal_goto($base_url.'/account/tour_claim.php');

}
function audit_mylist(){
   global  $base_url;
  drupal_goto($base_url.'/account/audit_mylist.php');

}
function audit_report_approvelist(){
   global  $base_url;
  drupal_goto($base_url.'/account/audit_report_approvelist.php');

}
function audit_correctivelist(){
   global  $base_url;
  drupal_goto($base_url.'/account/audit_correctivelist.php');
}


function bank_Reconciliation(){
   global  $base_url;
  drupal_goto($base_url.'/account/BankReconciliation.php');
}

function Account_Listing(){
  global  $base_url;
  drupal_goto($base_url.'/account/GLAccountInquiry.php');
}

function Account_Inquiry(){
  global  $base_url;
  drupal_goto($base_url.'/account/GLAccountInquiry.php');
}

function Trial_Balance(){
  global  $base_url;
  drupal_goto($base_url.'/account/GLTrialBalance.php');
}

function Daily_Cash_Book(){
  global  $base_url;
  drupal_goto($base_url.'/account/dailycashbook.php');
}



function Income_Expenditure(){
  global  $base_url;
  drupal_goto($base_url.'/account/incexp.php');
}


function Bank_Account_Payments_Entry(){
  global  $base_url;
  drupal_goto($base_url.'/account/Payments.php?NewPayment=Yes');
}

function Bank_Account_Receipts_Entry(){
  global  $base_url;
  drupal_goto($base_url.'/account/CustomerReceipt.php?NewReceipt=Yes&Type=GL');
}


function Journal_Voucher(){
  global  $base_url;
  drupal_goto($base_url.'/account/GLJournal.php?NewJournal=Yes');
}

function Account_Master(){
  global  $base_url;
  drupal_goto($base_url.'/account/GLAccounts.php');
}


function GL_Budgets(){
  global  $base_url;
  drupal_goto($base_url.'/account/GLBudgets.php');
}


function Account_Sections(){
  global  $base_url;
  drupal_goto($base_url.'/account/AccountSections.php');
}

function Account_Groups(){
  global  $base_url;
  drupal_goto($base_url.'/account/AccountGroups.php');
}


function Reorder_Level_By(){
  global  $base_url;
  drupal_goto($base_url.'/account/ReorderLevelLocation.php');
}

function View_or_Update_Prices(){
  global $base_url;
  drupal_goto($base_url.'/account/PricesByCost.php');
}


function Add_or_Update_Prices(){
  global $base_url;
  drupal_goto($base_url.'/account/PricesBasedOnMarkUp.php');
}

function Sales_Category_Maintenance(){
  global $base_url;
  drupal_goto($base_url.'/account/SalesCategories.php');
}

function Select_An_Item(){
  global $base_url;
  drupal_goto($base_url.'/account/SelectProduct.php');
}

function Add_A_New_Item(){
  global $base_url;
  drupal_goto($base_url.'/account/Stocks.php');
}

function Condemned_Items(){
  global $base_url;
  drupal_goto($base_url.'/account/condemned_items.php');
}

function Stock_Register(){
  global $base_url;
  drupal_goto($base_url.'/account/stock_register.php');
}

function Condemn_Item(){
  global $base_url;
  drupal_goto($base_url.'/account/condemnation.php');
}


function Stock_Issue_Register(){
  global $base_url;
  drupal_goto($base_url.'/account/stock_issue_register.php');
}

function Purchase_Order_Detail(){
  global $base_url;
  drupal_goto($base_url.'/account/purchase_order_detail.php');
}



function Item_Master(){
  global $base_url;
  drupal_goto($base_url.'/account/item_master.php');
}

function Purchase_Order(){
  global $base_url;
  drupal_goto($base_url.'/account/purchase_order.php');
}

function Add_Item(){
  global $base_url;
  drupal_goto($base_url.'/account/add_item.php');
}*/
 
/*end*/


echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" src="includes/jquery1.2.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DSJE</title>
<link rel="stylesheet" href="images/style.css" type="text/css"/>
<script type="text/javascript" src ="images/MiscFunctions.js"></script>
<script type="text/javascript" src ="images/validationweberp.js"></script>


</head>

<body class="body">
<div class="main-container">
<div id="top-gradient"></div>
<div id="header">

<div id="top">
<div id="top-left">
<div id="top-right">
<div class="hindi"><div class="clear-block block block-locale" id="block-locale-0">
 
</div>
 </div>
<img width="364" vspace="0" hspace="0" height="38" title="Himachal Pradesh Backward Classes Finance and Development Corporation" alt="Himachal Pradesh Backward Classes Finance and Development Corporation" src="/hpbc/sites/all/themes/scst/images/logo.png">
</div>
</div>
</div>
<div class="menu-block">
<div class="top-search middle">    
  	<form id="csearch-form" method="post" accept-charset="UTF-8" action="/hpbc/welcome-deshboard">
	<div id="edit-search-box-wrapper" class="form-item">
 		<input type="text" class="form-text" value="" size="20" id="edit-search-box" name="search_box" maxlength="128">
	</div>
		<input type="submit" class="form-submit" value="search" id="edit-search" name="op">			
	</form>
</div>
<ul class="menu-nav">
 <li class="top"><a href="/'.$u[1].'/node"><span>Home</span></a></li>';
				  $s="select * from menu_links where menu_name='primary-links' and plid=0 and link_path!='node' and hidden = 0";
				  $q=DB_query($s,$db);
				  while($r=Db_fetch_array($q))
				  {
				    if(substr($r['options'],25,2) == "hi"){
					   
				   }else{
				    echo '<li class="top"><a href="/'.$u[1].'/'.$r['link_path'].'"><span>'.$r['link_title'].'</span></a></li>';
				   }
				  }
echo '</ul>        
</div>
</div>
<div id="main-content">
 <div class="adminpanel" align="right">
				<span>Hi '.$_SESSION['UsersRealName'].'! | <a href="/'.$u[1].'/node/211">Dashboard</a> | <a href="/'.$u[1].'/logout">Logout</a>&nbsp;</span></div>';
?>
<script language="javascript">
$(document).ready(function(){

$('#main-content form:not(.filter) :input:visible:first').focus()
});
</script>
<?php
function getCorporationBranch($uid,$db)
{
	$sql = "SELECT j.officeid,c.corporation_type FROM tbl_joinings j, users u,tbl_corporations c WHERE u.uid = j.program_uid AND j.officeid = c.corporation_id AND u.uid = '".$uid."'";
	$res = DB_query($sql,$db);
	$branchrow = DB_fetch_array($res);
	if($branchrow['officeid'] && ($branchrow['corporation_type'] == 70))
		return $branchrow['officeid'];
	else
		return 0;
}
//GET CORPORATION BRANCH OF VENDOR FROM USER ID
function getVendorBranch($uid,$db)
{
	 $sql = "SELECT officeid2 FROM tbl_vendor,users WHERE users.name = tbl_vendor.username AND users.uid = '".$uid."'";
	 //echo $sql;exit;
	$res = DB_query($sql,$db);
	$branchrow = DB_fetch_array($res);

	//if($branchrow->officeid && ($branchrow->corporation_type == 70))
	if($branchrow['officeid2'])
		return $branchrow['officeid2'];
	else
		return 0;
}

function voucherentry($trid = '',$accountid = '',$entrytype = '',$amount = '',$GLcode = '',$bank = '',$vtype = '',$db)
{
	global $user;
	$insert_statement = "INSERT INTO tbl_pendingvouchers (account_id,amount,bank,vtype,entrytype,transactionid,GLaccount_code,createdby,createdon) VALUES ('".db_escape_string($accountid)."','".$amount."','".$bank."','".$vtype."','".$entrytype."','".$trid."','".$GLcode."','".$user->uid."','".time()."')";
	if(!DB_query($insert_statement,$db))
		return 0;
	else
		return 1;
}

function createTask($level = '',$doc_id = '',$comment = '',$uid = '',$assignedbyuid = '',$Is_escalation = '',$writ_level = '',$db)
{
	 $tasksql = "INSERT INTO tbl_workflow_task (level, doc_id, comment, uid, assignedbyuid, Is_escalation, writ_level) VALUES ('".$level."','".$doc_id."', '".$comment."','".$uid."','".$assignedbyuid."','".$Is_escalation."','".$writ_level."')";
	if(!DB_query($tasksql,$db))
		return 1;
	else
		return 0;
}
function updateTask($taskid = '',$db)
{
	$taskupdatesql = "UPDATE tbl_workflow_task SET status = 1 WHERE task_id = '".$taskid."'";
	if(!DB_query($taskupdatesql,$db))
		return 1;
	else
		return 0;
}
function empname($empid,$db){
$sql = "select employee_name from tbl_joinings where employee_id = '".$empid."'";
$res = DB_query($sql,$db);

$rs = DB_fetch_array($res);
	return $rs[0];
	}
function getUidtoEmployeeName($uid,$db){
	$sql = "select employee_name from tbl_joinings where program_uid = '".$uid."'";
	$res = DB_query($sql,$db);

	$rs = DB_fetch_array($res);
		return $rs[0];
}
function getRole($uid,$db){
	$sqlrole = "select rid from users_roles where uid='".$uid."'";
	$res = DB_query($sqlrole,$db);
	$rs = DB_fetch_array($res);

	return $rs[0];
}

function getemployeegrade2($employeegrade_id,$db)
{
 $h="select employeeGrade_name from tbl_employeegrades where employeeGrade_id='".$employeegrade_id."' ORDER BY employeeGrade_name ASC";
$res = DB_query($h,$db);
$d=DB_fetch_array($res);
 
 return $d[0];

}

?>