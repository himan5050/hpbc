<?php

function getReceipt($loan_id,$amount,$amid = '')
{   
	global $user;
	global $base_url;
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loans', 'loan/listloans');
	if($loan_id)
		$breadcrumb[] = l('List of Repayments', 'loan/repayment/'.$loan_id);
	    $breadcrumb[] = 'Payment Receipt';
	    drupal_set_breadcrumb($breadcrumb);
	    $pdfurl = $base_url."/cashReceiptpdf.php?loan_id=$loan_id&amount=$amount&amid=$amid";
        $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
    
	    $query = "SELECT r.*,l.fname,l.lname,l.account_id FROM tbl_loan_repayment r,tbl_loanee_detail l,tbl_loan_detail ld WHERE r.loanee_id = l.loanee_id AND ld.reg_number = l.reg_number AND r.id = '".$amid."'"; 
	$res = db_query($query);
	$re = db_fetch_object($res);
	if($re->paytype == 'Promoter Share')
	{
	$currentdate = date("d-m-Y",strtotime($re->payment_date));
	$othercharges = 'N/A';
	$installment = 'N/A';
	$lin=$base_url."/nodues/".$re->account_id;
	$rsword = convert_number(round($amount));
	$name = ucwords($re->fname.' '.$re->lname);
	$ben = round($amount);
	$gamount = round($amount);
	$famount = round($amount);
	$amount = 'N/A';
	$accnumber = $re->account_id;
	}else{

	$query = "SELECT *,sm.scheme_name as schemename FROM tbl_loanee_detail l,tbl_loan_detail ld,tbl_loan_amortisaton la,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.loan_id = la.loan_id AND ld.scheme_name = sm.loan_scheme_id AND ld.loan_id = '".$loan_id."'"; 
	$res = db_query($query);
	$lquery = "SELECT *,sm.scheme_name as schemename FROM tbl_loanee_detail l,tbl_loan_detail ld,tbl_loan_amortisaton la,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.loan_id = la.loan_id AND ld.scheme_name = sm.loan_scheme_id AND la.payment_id = '".$amid."'"; 
	$lres = db_query($lquery);
	$rquery = "SELECT id FROM  tbl_loan_repayment ORDER BY id DESC LIMIT 1"; 
	$rres = db_query($rquery);
	
	$am = db_fetch_object($lres);
	$loan = db_fetch_object($res);
	$reciept_no = db_fetch_object($rres);
	
	$currentdate = date("d-m-Y",strtotime($am->payment_date));
	$othercharges = round($am->other_charges_paid + $am->LD_paid);
	$installment = round($am->principal_paid + $am->interest_paid);
	$lin=$base_url."/nodues/".$loan->account_id;
	$rsword = convert_number(round($amount));
	$name = ucwords($loan->fname.' '.$loan->lname);
        $mobile_no = $loan->mobile;
	$gamount = 'N/A';
	$famount = round($amount);
	$recno = $reciept_no->id;
        $paymentdate = $reciept_no->payment_date;
	$recnumber = $amid;
	$accnumber = $loan->loanacc_id;
        $o_principal = $loan->o_principal;
        
	$ben = 'N/A';
	}
	//$phone = getMessage('corporation', 'phone', '');
        $phone = '01892-264326'; 
	$for = getMessage('corporation', 'for', '');
	$scstname = getMessage('corporation', 'name', '');
	$address = getMessage('corporation', 'address', '');
	//$gamount = $amount + $loan->processing_fee;
	//$processing_fee = ($loan->processing_fee != '0.00')?$loan->processing_fee:'-';
	//$total=round($famount,2);
	$tot=round($amount);
	$processing_fee = 0.0;
	$output = <<<EOD
	
		<div id="scst-receipt">
		<a href="$lin">Get No Dues Certificate</a>&nbsp;&nbsp;&nbsp;
		<a target="_blank" href="$pdfurl"><img style="float:right;" src="$pdfimage" alt="Export to PDF" title="Export to PDF" /></a>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
			<td colspan="3" align="right"><strong>PH.:</strong> $phone</td>
		  </tr>
		  <tr>
			<td colspan="3" align="center"><h1>Himachal Backward Classes Finance & Development Corporation</h1>
			<br><strong>HP PWD House,Kangra - 176001</strong></td>
		  </tr>
		  <tr>
			<td width="28%"><strong>Reciept No.:</strong> $recnumber</td>
			<td width="30%">&nbsp;</td>
			<td width="42%" align="left"><strong>Dated:</strong> $currentdate</td>
		  </tr>
		  <tr>
			<td colspan="3">Received with thanks from Sh./Smt./Ms./Mr. <b>$name</b></td>
			</tr>
		  <tr>
			<td colspan="3">a sum of Rs. $famount Rupees: &nbsp; <b>$rsword</b> Only<br />
			  by cash/demand draft/cheque subject to realisation ............ dated <b>$currentdate</b><br />
			  Description <b>Payment Receipt</b> Account No.<b> $accnumber</b></td>
			</tr>
		  <tr>
			<td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td colspan="2">Installment <b>$installment</b></td>
				</tr>
			  <tr>
				<td width="49%">Others Charges <b>$othercharges</b></td>
				<td width="51%">Beneficiary Share <b></b></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>Processing Fee  <b>$processing_fee</b></td>
			  </tr>
			  <tr>
				<td>Total: <b>$tot</b></td>
				<td>Total: <b>$gamount</b></td>
			  </tr>
			  <tr>
				<td><strong>Beneficiary Account No.</strong> $accnumber</td>
				<td><strong>Scheme</strong> $loan->schemename</td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td align="left">For: <strong>$for</strong></td>
			  </tr>
			  <tr>
				<td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td><strong>Total Amount Rs.</strong>$famount</td>
					<td><strong>Cashier/Field Asstt.</strong></td>
					<td><strong>Accountant/Manager</strong></td>
				  </tr>
				</table></td>
				</tr>
			</table></td>
		  </tr>
		</table>
	</div>
EOD;
        
        /*------------------------------------------------------------------------
         * SMS Integration Code for each repayment.
         */
         //Sending SMS
         /*
        $data = array("username" => "hpgovt",
                      "password" => "hpdit@1234",
                      "senderid" =>"hpgovt",
                      "smsservicetype" =>"singlemsg",
                      "mobileno" =>$mobile_no,
                      "bulkmobno" => "bulkmobno",
                     // "content"  => "Namaskar ! HBCFDC Kangra thankfully acknowledges receipt of Rs. $tot against A/c no. $accnumber on vide receipt no. $recnumber . $currentdate.Repayment as per schedule is in your interest.For Help, contact landline no. 01892-264326."
                    //  "content"  => "Payment of Rs. $tot received vide RT No. $recnumber  has been credited into your HBCFDC Kangra Loan A/c No. $accnumber on $currentdate.Your payable outstanding amount now is $o_principal.For help,contact on 01892-264334,262282."
                       "content"  => "Payment of Rs. $tot received vide RT No. $recnumber  has been credited into your HBCFDC Kangra Loan A/c No. $accnumber on $currentdate.For help,contact on 01892-264334,262282."
                );
        $url = "http://msdgweb.mgov.gov.in/esms/sendsmsrequest";
        post_to_url($url, $data);
        /*------------------------------------------------------------------------
         * End of SMS Integration.
         */
        
return $output;
}


function repayment_form($type = '',$loan_id = 0)
{
	global $user;
	global $base_root;
	global $base_path;
	global $base_url;

	$cheque_number = isset($_POST['cheque_number'])?$_POST['cheque_number']:'';
	$amount = isset($_POST['amount'])?$_POST['amount']:'';
	$in_favour_of = isset($_POST['in_favour_of'])?$_POST['in_favour_of']:'';
	$cheque_date = isset($_POST['cheque_date'])?$_POST['cheque_date']:'';
	$bank = isset($_POST['bank'])?$_POST['bank']:'';
	$bank_branch = isset($_POST['bank_branch'])?$_POST['bank_branch']:'';
	$paytype = isset($_POST['paytype'])?$_POST['paytype']:'';
	$payment_date = isset($_POST['payment_date'])?$_POST['payment_date']:'';
	$payment_type = isset($_POST['payment_type'])?$_POST['payment_type']:'';
	

	$array = explode('/',$_GET['q']);
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	if($type != 'onetime')
	{
		$breadcrumb[] = l('List of Loans', 'loan/listloans');
		if($loan_id)
			$breadcrumb[] = l('List of Repayments', 'loan/repayment/'.$loan_id);
	}
	if($array[1] == 'repaymentform'){
		$breadcrumb[] = ($type == 'repayment')?'Repayment Collection Form':'One Time Settlement Form';
	  }
	drupal_set_breadcrumb($breadcrumb);
	$buttonvalue = 'Save';
	if($type == 'onetime')
		$buttonvalue = 'Save and Close';
	$account_id = '';
	$loaneeid = '';
	if($loan_id)
	{
		$query = "SELECT l.account_id FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND ld.loan_id = '".$loan_id."'"; 
		$res = db_query($query);
		$l = db_fetch_object($res);
		$account_id = $l->account_id;
                $mobile_no = $l->mobile;
		$readonly = 'readonly';
	}
	if(isset($_POST['account_id']) && $_POST['account_id'])
	{
		$loan_id = '';
		$account_id = $_POST['account_id'];
		$query = "SELECT ld.loan_id,ld.loan_docket FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND  l.account_id = '".$_POST['account_id']."'"; 
		$res = db_query($query);
		$loanid = db_fetch_object($res);
		$loan_id = $loanid->loan_id;
		//$loan_id = isset($_POST['loan_id'])?$_POST['loan_id']:$loanid;
	}
	//echo $loan_id;exit;
	$query = "SELECT ld.loanee_id,l.scheme_name,ld.corp_branch,ld.account_id,l.o_principal FROM tbl_loanee_detail ld,tbl_loan_detail l WHERE l.reg_number = ld.reg_number AND l.loan_id = '".$loan_id."'"; 
	$res = db_query($query);
	$loanee = db_fetch_object($res);
	
	$loaneeid = $loanee->loanee_id;
	$accountid = $_POST['account_id'];
	$sql = "SELECT ending_balance FROM tbl_loan_amortisaton WHERE loan_id = $loan_id ORDER BY am_id DESC LIMIT 1";
	$res = db_query($sql);
	$endingbalanceh = db_fetch_object($res);
	if($endingbalanceh->ending_balance)
		$startingbalance = $endingbalanceh->ending_balance;
	else
		$startingbalance = $loanee->o_principal;
	
	if(isset($_POST['cheque_number']))
	{
		$fieldstr = '';
		$valuestr = '';
		$updatestr = '';
		$error = validateRepaymentForm($loaneeid);
		if(!$error)
		{
			$paytype = 'One Time Settlement';			
			if($_POST['cheque_number'])
			{
				if(isset($_POST['rid'])){
					$updatestr .= "cheque_number = '".db_escape_string($_POST['cheque_number'])."',";
				}else{
					$fieldstr .= "cheque_number,";
					$valuestr .= "'".db_escape_string($_POST['cheque_number'])."',";
				}
			}
			if($_POST['amount'])
			{
				if(isset($_POST['rid'])){
					$updatestr .= "amount = '".db_escape_string($_POST['amount'])."',";
				}else{
					$fieldstr .= "amount,";
					$valuestr .= "'".db_escape_string($_POST['amount'])."',";
				}
			}
			if($_POST['in_favour_of'])
			{
				if(isset($_POST['rid'])){
					$updatestr .= "in_favour_of = '".$_POST['in_favour_of']."',";
				}else{
					$fieldstr .= "in_favour_of,";
					$valuestr .= "'".db_escape_string($_POST['in_favour_of'])."',";
				}
			}
			if(isset($_POST['paytype']) && $_POST['paytype'])
			{
				if(isset($_POST['rid'])){
					$updatestr .= "paytype = '".$_POST['paytype']."',";
				}else{
					$fieldstr .= "paytype,";
					$valuestr .= "'".db_escape_string($_POST['paytype'])."',";
				}
			}else{
					$fieldstr .= "paytype,";
					$valuestr .= "'".db_escape_string($paytype)."',";
			}
			
			
			if(isset($_POST['payment_type']) && $_POST['payment_type'])
			{
				if(isset($_POST['rid'])){
					$updatestr .= "payment_type = '".$_POST['payment_type']."',";
				}else{
					$fieldstr .= "payment_type,";
					$valuestr .= "'".db_escape_string($_POST['payment_type'])."',";
				}
			}else{
					$fieldstr .= "payment_type,";
					$valuestr .= "'".db_escape_string($payment_type)."',";
			}
			
			if($_POST['cheque_date'])
			{
				$_POST['cheque_date'] = databaseDateFormat($_POST['cheque_date'],'indian','-');
				if(isset($_POST['rid'])){
					$updatestr .= "cheque_date = '".$_POST['cheque_date']."',";
				}else{
					$fieldstr .= "cheque_date,";
					$valuestr .= "'".db_escape_string($_POST['cheque_date'])."',";
				}
			}
			if($_POST['payment_date'])
			{
				$_POST['payment_date'] = databaseDateFormat($_POST['payment_date'],'indian','-');
				if(isset($_POST['rid'])){
					$updatestr .= "payment_date = '".$_POST['payment_date']."',";
				}else{
					$fieldstr .= "payment_date,";
					$valuestr .= "'".db_escape_string($_POST['payment_date'])."',";
				}
			}
			 
			
			if($_POST['bank'])
			{
				$query = "SELECT bank_name FROM tbl_bank  WHERE bank_id = '".$_POST['bank']."' AND status = 1 LIMIT 1";
				$res = db_query($query);
				$b = db_fetch_object($res);
				if(isset($_POST['rid'])){
					$updatestr .= "bank = '".$b->bank_name."',";
				}else{
					$fieldstr .= "bank,";
					$valuestr .= "'".db_escape_string($b->bank_name)."',";
				}
				
				
				
			}
			
			
			if($_POST['bank_branch'])
			{
				$query = "SELECT bankbranch_name FROM tbl_bankbranch  WHERE bankbranch_id = '".$_POST['bank_branch']."' AND status = 1 LIMIT 1";
				$res = db_query($query);
				$b = db_fetch_object($res);
				if(isset($_POST['rid'])){
					$updatestr .= "bank_branch = '".$b->bankbranch_name."',";
				}else{
					$fieldstr .= "bank_branch,";
					$valuestr .= "'".db_escape_string($b->bankbranch_name)."',";
				}
			}
			
			
			
			db_query('START TRANSACTION');
			db_query('BEGIN');
			$lastiid = '';
			if(isset($_POST['rid'])){
				$updatestr .= "modifiedby = '".$user->uid."', modifieddon = '".time()."'";
				$stmt = "UPDATE tbl_loan_repayment SET $updatestr WHERE id = ".$_POST['rid'];
				
				if(!db_query($stmt))
					$inserterror = 1;
			}else{
				$fieldstr .= "createdby,createdon";
				$valuestr .= "'".$user->uid."','".time()."'";
				$insert_statement = "INSERT INTO tbl_loan_repayment (loanee_id,$fieldstr) VALUES (".$loaneeid.",$valuestr)";
				if(!db_query($insert_statement))
					$inserterror = 1;
				else
					$lastiid = db_last_insert_id('tbl_loan_repayment','id');
															
				if($_POST['paytype'] == 'EMI')
					$paytype = 'EMI';
				if($_POST['paytype'] == 'Promoter Share')
					$paytype = 'Promoter Share';
				$success = voucherentry($lastiid,$accountid,$paytype,$_POST['amount'],'',$b->bank_name,'3');
				if(!$success)
					$inserterror = 1;
				if($paytype == 'Promoter Share')
				{
					$amid = $lastiid;
				}
				
			}
			
			if($paytype == 'EMI' || $paytype == 'One Time Settlement' || $paytype == 'Promoter Share')
			{
			       $updatestr = '';
			       $sql = "SELECT o_principal,o_interest,o_LD,o_other_charges FROM tbl_loan_detail WHERE loan_id = $loan_id LIMIT 1";
			       $res = db_query($sql);
			       $charges = db_fetch_object($res);
			       $o_LD = $charges->o_LD;
			       $o_other_charges = $charges->o_other_charges;
			       //$o_interest = $charges->o_interest;
			       $o_principal = $charges->o_principal;
			       $ra = trim($_POST['amount']) - $charges->o_principal;
			       //echo "RA :: ".$ra;
			       if($ra > 0){
				        $o_principal = 0;
				        $o_principalpaid = $charges->o_principal;
				        $ra1 = $ra - $charges->o_LD;
				        if($ra1 > 0){
					          $o_LD = 0;
					          $o_LDpaid = $charges->o_LD;
					          $ra2 = $ra1 - $charges->o_other_charges;
					          if($ra2 > 0){
						           $o_other_charges = 0;
						           $o_other_chargespaid = $charges->o_other_charges;
					          }else{
					               $o_other_charges = abs($ra2);
						           $o_other_chargespaid = $ra1;
					          }
				         }else{
				               $o_LD = abs($ra1);
				               $o_LDpaid = $ra;
                                        }
                                        /*------------------------------------------------------------------------
                                        * SMS Integration Code for closing of account.
                                        */
                                        //Sending SMS
                                        /*
                                        $data = array("username" => "hpgovt",
                                                      "password" => "hpdit@1234",
                                                      "senderid" =>"hpgovt",
                                                      "smsservicetype" =>"singlemsg",
                                                      "mobileno" =>$mobile_no,
                                                      "bulkmobno" => "bulkmobno",
                                                      "content"  => "Attention HBCFDC Kangra Loan A/c No.$accountid. Thanks ! You have cleared the entire loan amount alongwith interest.  You are advised to apply for No Dues Certificate.  For any help, contact on 01892-264334, 262282."
                                                );
                                        $url = "http://msdgweb.mgov.gov.in/esms/sendsmsrequest";
                                        post_to_url($url, $data);
                                        /*------------------------------------------------------------------------
                                        * End of SMS Integration.
                                        */                
			        }else{
			              $o_principal = abs($ra);
			              $o_principalpaid = $_POST['amount'];
			        }	
						
			        $updatestr .= "o_LD = '".$o_LD."',
					               o_other_charges = '".$o_other_charges."',
								   o_interest = '".$o_interest."',o_principal = '".$o_principal."' ";
			
			        $endingbalance = $o_LD + $o_other_charges + $o_interest + $o_principal;
			        $updatestr = trim($updatestr,', ');
			        $sql = "UPDATE tbl_loan_detail SET $updatestr WHERE loan_id = '".$loan_id."'";

			        if(!db_query($sql))
				           $inserterror = 1;
			        $sql = "INSERT INTO tbl_loan_amortisaton (loan_id, loanacc_id,cheque_date, payment_date, starting_balance, LD_paid, other_charges_paid, interest_paid, principal_paid, ending_balance,installment_paid,payment_id) VALUES ('".$loan_id."','".db_escape_string($accountid)."','".db_escape_string($_POST['cheque_date'])."','".$_POST['payment_date']."','".db_escape_string($startingbalance)."','".db_escape_string($o_LDpaid)."','".db_escape_string($o_other_chargespaid)."','".db_escape_string($o_interestpaid)."','".db_escape_string($o_principalpaid)."','".db_escape_string($endingbalance)."','".db_escape_string($_POST['amount'])."','".$lastiid."')";
			        if(!db_query($sql))
				            $inserterror = 1;
			        else
				            $amid = db_last_insert_id('tbl_loan_amortisaton','am_id');
				
			        if(isset($_POST['status'])){
				            $query = "SELECT ld.* 
							                 FROM tbl_loanee_detail l,tbl_loan_detail ld 
											 WHERE ld.reg_number = l.reg_number AND l.account_id = '".$accountid."'"; 
				            $res = db_query($query);
				            $loan = db_fetch_object($res);
				            $pending_balance = $loan->o_principal + $loan->o_other_charges + $loan->o_LD + $loan->o_interest;
				            $fieldstr .= ",status,pending_balance";
				            $valuestr .= ",'".$_POST['status']."','".$pending_balance."'";
				            $insert_statement = "INSERT INTO tbl_loan_onetimesettlement (loanee_id,$fieldstr) VALUES (".$loaneeid.",$valuestr)";
				            if(!db_query($insert_statement))
					                    $inserterror = 1;
				            $stmt = "UPDATE tbl_loan_detail SET  closed_date = '".date("Y-m-d")."',loan_status = 0,weaver = '".$pending_balance."' WHERE loan_id = ".$loan_id;
				            if(!db_query($stmt))
					                    $inserterror = 1;
				            $stmt = "UPDATE tbl_workflow_docket SET status = 'closed' WHERE doc_id = ".$loanid->loan_docket;
				            if(!db_query($stmt))
					                    $inserterror = 1;
				            if($pending_balance){
					                    $success = voucherentry($lastiid,$accountid,'Weaver',$pending_balance,'','','1');
					        if(!$success)
						                $inserterror = 1;
				    }
			}
	}
			
			if($inserterror)
			{
				db_query('ROLLBACK');
				$message = getMessage('loans', 'code15','');
				drupal_set_message($message);
				//drupal_goto('loan/repayment/'.$loan_id);
			}else{
				db_query('COMMIT');
				if(isset($_POST['rid']))
					$message = getMessage('loans', 'code17','');
				else
					$message = getMessage('loans', 'code16','');
				drupal_set_message($message);
				$parameter = '';
				$l = unserialize(getLoanDetail($loan_id,1));
				$to = $l[9];
				$applicant = ucwords($l[4]);
				$accountno = $_POST['account_id'];
				$amount = number_format($_POST['amount'],2,'.','');
				$payment_date = date("d-m-Y",strtotime($_POST['payment_date']));
				if(isset($_POST['status']))
				{
					$balance = '0.00';
					$parameter = json_encode(array("$applicant","$applicant","$accountno","$payment_date","$amount","$balance"));
					createMail('loan_onetime_settlement',$to,'',$parameter);
				}else{
					$balance = number_format($endingbalance,2,'.','');
					$parameter = json_encode(array("$applicant","$applicant","$accountno","$payment_date","$amount","$balance"));
					createMail('loan_repayment',$to,'',$parameter);
				}
				drupal_goto('loan/getReceipt/'.$loan_id.'/'.$_POST['amount'].'/'.$lastiid);
			}
		}
	}
	
	$formtype = ($type == 'repayment')?'Loan Repayment Form':'Loan One Time Settlement Form';
	
	$output = <<<EOD
	<script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.core.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.datepicker.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/lib/jquery.timeentry.pack.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/date_popup.js?K"></script>
		<script>
			$(function() {
				//$( "#fname" ).focus();
				$( "#cheque_dateid" ).datepicker();
				$( "#cheque_dateid" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
				$( "#payment_dateid" ).datepicker();
				$( "#payment_dateid" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
EOD;
if($error)
{
	$output .= $error;
}

$output .= <<<EOD
			});
		</script>
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">
		<form action="" name="repaymentform" method="post" enctype="multipart/form-data">
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="disburse_container">
		<tr class="oddrow">
			<td align="center" colspan=4><h2>$formtype</h2></td> 
		</tr>
        	<tr class="evenrow">
			<td><div class="loantext1">Account No.: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="account_id" value="$account_id" $readonly id="account_idid" />
					<input type="button" class="form-submit" value="Show Detail" id="search" name="ls" onclick="return showAccountDetail('$base_url');"/>
<!--					<input type="button" class="form-submit" value="Calculate Interest" id="search" name="ls" onclick="return calculateInterest('$base_url');"/>
-->				</div></div><div id="cinterestid"></div>
			</td>
		</tr>
        </table>
      
    
		<div id="accdetail"></div>
       
        
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="disburse_container">
		<tr class="oddrow" id="chequedd">
			<td><div class="loantext1">Cheque/DD No.: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="cheque_number" value="$cheque_number" id="cheque_numberid" maxlength="6" onkeypress="return fononlyn(event);" /></div>
				</div>
			</td>
		</tr>
		<tr class="evenrow">
			<td><div class="loantext1">Amount: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="amount" value="$amount" id="amountid" onkeypress="return paypaymain_custom(event,'amountid',11);"  maxlength="11" />
				</div></div>
			</td>
		</tr>

		<tr class="oddrow" id="favourdd">
			<td><div class="loantext1">In Favour of: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="in_favour_of" value="$in_favour_of" id="in_favour_ofid" maxlength="45" onkeypress="return alphabet(event);" /></div>
				</div>
			</td>
		</tr>
		<tr class="evenrow" id="statementceque">
			<td><div class="loantext1">Cheque Date: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="cheque_date" value="$cheque_date" id="cheque_dateid" readonly="readonly" />
				</div></div>
			</td>
		</tr>
		<tr class="oddrow">
			<td><div class="loantext1">Payment Date: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="payment_date" value="$payment_date" id="payment_dateid" readonly="readonly" />
				</div></div>
			</td>
		</tr>

		<tr class="evenrow" id="statementbankname">
			<td><div class="loantext1">Bank Name: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
        <select name="bank" id="bankid">
		<option value="">Select</option>
        
EOD;

 if($_POST['payment_type'] == 'cash'){
$output .= <<<EOD
	<script>changeonetime('cash');</script>
EOD;
      }
	
    	$query = "SELECT bank_id, bank_name FROM tbl_bank  WHERE status = 1 ORDER BY bank_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($bank == $row->bank_id)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->bank_id.'" '.$selected.'>'.ucwords($row->bank_name).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select>	
				</div></div>
			</td>
		</tr>
                
        
        <tr class="oddrow" id="statementbankbranchname">
			<td><div class="loantext1">Bank Branch Name: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
        <select name="bank_branch" id="bankid">
		<option value="">Select</option>
        
EOD;

 if($_POST['payment_tp'] == 'cash'){
$output .= <<<EOD
	<script>changeonetime('cash');</script>
EOD;
      }
      
	
    	$query = "SELECT bb.bankbranch_id as bbid, bb.bankbranch_name as bbname FROM tbl_bankbranch bb,tbl_bank b  WHERE bb.bank_id = b.bank_id  AND bb.status = 1 ORDER BY bb.bankbranch_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($bank == $row->bbid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->bbid.'" '.$selected.'>'.ucwords($row->bbname).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select>	
				</div></div>
			</td>
		</tr>
          
        <tr class="evenrow" id="statementbankname">
			<td><div class="loantext1">Payment Mode: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
        <select name="payment_type" id="bankid">
		<option value="">Select</option>
        
EOD;

 if($_POST['payment_type'] == 'cash'){
$output .= <<<EOD
	<script>changeonetime('cash');</script>
EOD;
      }
	
    	$query = "SELECT lookup_id, lookup_name FROM tbl_lookups  WHERE lookupType_id = 120 ORDER BY lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($payment_type == $row->lookup_id)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lookup_name.'" '.$selected.'>'.ucwords($row->lookup_name).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select>	
				</div></div>
			</td>
		</tr>
                
        
EOD;
		if($type != 'onetime')
		{
$output .= <<<EOD
		<tr class="evenrow">
			<td><div class="loantext1">Payment Type: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
        <select name="paytype" id="paytypeid">
		<option value="">Select</option>
EOD;
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Loan Payment Type' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$selected = '';
			if($paytype == $row->lname)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lname.'" '.$selected.'>'.ucwords($row->lname).'</option>';
		}
$output .= <<<EOD
		</select>	
				</div></div>
			</td>
		</tr>
       
EOD;
		}
		if($type == 'onetime')
		{
$output .= <<<EOD
		<tr class="evenrow">
			<td><div class="loantext1">Status: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<select name="status">
					<option value="approved">Approved</option>
					</select>
				</div>
                </div>
			</td>
		</tr>
EOD;

$output .= <<<EOD

</table>

<table width="100%" cellpadding="2" cellspacing="1" border="0">	
	<tr class="oddrow">
		<td  align="center" class="back">
			<input type="hidden" value="$loan_id" name="loan_id">
EOD;
			if($loan_id)
                $output .= l("Back",'loan/repayment/'.$loan_id);
            elseif($type == 'onetime')
            	$output .= l("Back",'');
            else
            	$output .= l("Back",'loan/listloans/');
$output .= <<<EOD
			<input type="submit" class="form-submit" value="$buttonvalue" id="submit" name="ls"/>
		
		</td>
	</tr>
</table>
</form>
EOD;
		}else{
        
        $output .= <<<EOD

</table>

<table width="100%" cellpadding="2" cellspacing="1" border="0">	
	<tr class="evenrow">
		<td  align="center" class="back">
			<input type="hidden" value="$loan_id" name="loan_id">
EOD;
			if($loan_id)
                $output .= l("Back",'loan/repayment/'.$loan_id);
            else
            	$output .= l("Back",'loan/listloans/');
$output .= <<<EOD
			<input type="submit" class="form-submit" value="$buttonvalue" id="submit" name="ls"/>
		
		</td>
	</tr>
</table>
</form>
EOD;
        
        }




$output .= <<<EOD
	</div>
EOD;

return $output;
}


function listrepayments()
{
	global $user;
	global $base_root;
	global $base_url;

	$array = explode('/',$_GET['q']);
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = 'Find List of Repayments';
	drupal_set_breadcrumb($breadcrumb);
	if(isset($_POST['account_id']))
	{
        $query = "SELECT ld.loan_id,ld.loan_docket FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND l.account_id = '".$_POST['account_id']."'"; 
        $res = db_query($query);
        $loanid = db_fetch_object($res);
        $loan_id = $loanid->loan_id;
    	return listrepayment($loan_id);
    }
	$output = <<<EOD
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">
		<form action="" name="onetimesettlementform" method="post" enctype="multipart/form-data" onSubmit="return onetimesettlementValidation();">
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="onetimesettlement_container">
		<tr class="oddrow">
			<td align="center" colspan=4><h2>List of Repayments</h2></td> 
		</tr>
		<tr class="evenrow">
			<td class="form-text1" class="back"><div class="loantext1">Account No.: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="account_id" value="$account_id" id="account_idid" />
					<input type="button" class="form-submit" value="Show Detail" id="search" name="ls" onclick="return showAccountDetail('$base_url');"/>
					<input type="submit" class="form-submit" value="Repayments" id="submit" name="ls"/>
EOD;
                    $output .= l("Back",'loan/listloans/');
$output .= <<<EOD
				</div><div id="cinterestid"></div></div>
			</td>
		</tr>
		</table>
		<div id="accdetail"></div>
</form>
</div>
EOD;


$output .= <<<EOD
	</div>
EOD;
return $output;
}

function listrepayment($loanid = '')
{
	global $user;
	global $base_root;
	global $base_path;
	global $base_url;
	$output = '';
	$array = explode('/',$_GET['q']);
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loans', 'loan/listloans');
	if($array[1] == 'repayment'){
		$breadcrumb[] = 'List of Repayments';
	  }
	drupal_set_breadcrumb($breadcrumb);
	//********************* REPAYMENT LISTING *******************
	
		$query = "SELECT ld.loanee_id,l.scheme_name,ld.corp_branch FROM tbl_loanee_detail ld,tbl_loan_detail l WHERE l.reg_number = ld.reg_number AND l.loan_id = '".$loanid."'"; 
		$res = db_query($query);
		$loanee = db_fetch_object($res);
		$loaneeid = $loanee->loanee_id;
		
		$limit = (int)getMessage( 'loans', 'code04', NULL);
		$header = array(
			array('data' => t('S. No.')),
			array('data' => t('Payment Type'), 'field' => 'paytype', 'sort' => 'asc'),
            array('data' => t('Payment Mode'), 'field' => '	payment_type', 'sort' => 'asc'),
			array('data' => t('Cheque/DD No.'), 'field' => 'cheque_number', 'sort' => 'desc'),
			array('data' => t('Amount'), 'field' => 'amount', 'sort' => 'asc'),
			array('data' => t('In Favour Of'), 'field' => 'in_favour_of', 'sort' => 'asc'),
			array('data' => t('Bank'), 'field' => 'bank', 'sort' => 'asc'),
            array('data' => t('Bank Branch'), 'field' => 'bank_branch', 'sort' => 'asc'),
			array('data' => t('Cheque Date'), 'field' => 'cheque_date', 'sort' => 'desc'),
            array('data' => t('Action')),
		);
	
		if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
			$val = '%'.strtoupper($_REQUEST['searchtext']).'%'; $val=addslashes($val);	 
			$query = "SELECT * FROM tbl_loan_repayment where (UPPER(cheque_number) LIKE '".$val."' OR UPPER(amount) LIKE '".$val."' OR UPPER(in_favour_of) LIKE '".$val."' OR UPPER(cheque_date) LIKE '".$val."' OR UPPER(bank) LIKE '".$val."') AND loanee_id = $loaneeid ".tablesort_sql($header);
			$sqlcount = "SELECT COUNT(*) AS count FROM tbl_loan_repayment where (UPPER(cheque_number) LIKE '".$val."' OR UPPER(amount) LIKE '".$val."' OR UPPER(in_favour_of) LIKE '".$val."' OR UPPER(cheque_date) LIKE '".$val."' OR UPPER(bank) LIKE '".$val."') AND loanee_id = $loaneeid ".tablesort_sql($header);
			$rscount = db_query($sqlcount);
			$rscounter = db_fetch_object($rscount);
		}else{
			$query = "SELECT * FROM tbl_loan_repayment WHERE loanee_id = $loaneeid ".tablesort_sql($header);
		}
		 
		$result = pager_query($query, $limit);
	
		if($_REQUEST['page']){
		 $counter = $_REQUEST['page']*$limit;
		}else{
		 $counter = 0;
		}
		while($row=db_fetch_object($result)) {
			$counter++;
			//$editurl = l('Edit','loan/repaymentform/'.$loanid.'/'.$row->id);
			$sql = "SELECT am_id FROM tbl_loan_amortisaton WHERE payment_id = '".$row->id."' ";
            $amres = db_query($sql);
            $am = db_fetch_object($amres);
			//if($am->am_id)
			//	$am = $am->am_id;
			//else
				$am = $row->id;
			$rows[] = array(
				
				array('data' => $counter),
				array('data' => ucwords($row->paytype)),
                array('data' => ucwords($row->payment_type)),
				array('data' => ucwords($row->cheque_number)),
				array('data' => round($row->amount)),
				array('data' => $row->in_favour_of),
				array('data' => $row->bank),
                array('data' => $row->bank_branch),
				array('data' => date("d-m-Y",strtotime($row->cheque_date))),
                array('data' => l('Get Reciept','loan/getReceipt/'.$loanid.'/'.$row->amount.'/'.$am)),
			);
		}
		if($rows== NULL)
			$header=NULL;
	
		 $output .= <<<EOD
			<form method="POST" action=""><table width="100%" border="0" cellspacing="1" cellpadding="1" id="wrapper">
			<tr><td colspan="3" class="searchrecord">
EOD;
			if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
			$output .= t(getMessage( 'loans', 'code03', array("0"=>$rscounter->count)))." | ".l('View All','loan/repayment/'.$loanid);
			}
			
			$output .='</td></tr>';
			
			$addurl = l('Repayment Form',"loan/repaymentform/repayment/".$loanid);
			$lising = 'List of Repayments';
			
			$output .='<tr><td colspan="3" class="tblHeaderLeft">'.$lising.'<span class="addrecord">'.$addurl.'</span></td>'.
			'   <td colspan="3" class="tblHeaderRight">'.
			'<input type="text" name="searchtext" value="'.$_POST['searchtext'].'">'.
			'&nbsp;<input type="submit" name="search" value="Search"></td></tr>'.
			'</table></form>';
		if(count($rows) == 0)
		{
			$output .= "<br /><br /><center><b>No repayment records to show.</b></center>";
		}
	
	
		$output .= theme_table($header,$rows, $attributes = array(), $caption = NULL);
		$output .= theme('pager', NULL, $limit,0 );
        $output .= '<br /><div class="back" align="center">'.l("Back",'loan/listloans/').'</div>';

	//********************* DISBURSEMENT LISTING END *******************
	
	return $output;
}


function validateRepaymentForm($loaneeid)
{
	$errorstr = '';
	$scriptcss = '';

	if(!$_POST['account_id'])
	{
		form_set_error('account_id','Account number should not be blank.');
		$errorstr = 1;
		$scriptcss .= '$("input[name=account_id]").addClass("error");';
	}
	if($loaneeid && !isLoanDisbursed($loaneeid))
    {
		form_set_error('account_id','No money disbursed yet. Repayment can not be done.');
    	$scriptcss .= '$("input[name=account_id]").addClass("error");';
    }
    
  
  
	if($_POST['cheque_number'] != '')
	{
    	if(strlen($_POST['cheque_number']) < 6) 
        {
			$errorstr = 1;
            form_set_error('cheque_number','Please enter a valid Cheque number.');
			$scriptcss .= '$("input[name=cheque_number]").addClass("error");';
        }
		if(isValidNumber('Cheque/DD number should be valid number.','cheque_number',$_POST['cheque_number']))
		{
			$errorstr = 1;
			$scriptcss .= '$("input[name=cheque_number]").addClass("error");';
		}
	}else{
		form_set_error('cheque_number','Cheque number should not be blank.');
		$scriptcss .= '$("input[name=cheque_number]").addClass("error");';
	}
	if($_POST['amount'])
	{
		if(paypay('amount',$_POST['amount'],'Amount'))
		{
			$errorstr = 1;
			$scriptcss .= '$("input[name=amount]").addClass("error");';
		}
        //$sql = "SELECT SUM(amount) totalamount FROM tbl_loan_disbursement WHERE loanee_id = $loaneeid GROUP BY loanee_id";
        //$res = db_query($sql);
        //$d = db_fetch_object($res);
        //if($_POST['amount'] > $d->totalamount)
        //{
          //  form_set_error('amount','Amount should not exceed total outstanding amount '.$d->totalamount);
			//$scriptcss .= '$("input[name=amount]").addClass("error");';
        //}
	}else{
		form_set_error('amount','Amount should not be blank.');
		$scriptcss .= '$("input[name=amount]").addClass("error");';
	}
	if(!$_POST['in_favour_of'])
	{
		form_set_error('in_favour_of','In Favour Of should not be blank.');
		$errorstr = 1;
		$scriptcss .= '$("input[name=in_favour_of]").addClass("error");';
	}
    
    
    
    
    
    /*
    
    
	if(!$_POST['cheque_date'])
	{
		form_set_error('cheque_date','Cheque date should not be blank.');
		$errorstr = 1;
		$scriptcss .= '$("input[name=cheque_date]").addClass("error");';
	}
	if(!$_POST['payment_date'])
	{
		form_set_error('payment_date','Payment date should not be blank.');
		$errorstr = 1;
		$scriptcss .= '$("input[name=payment_date]").addClass("error");';
	}else{
    	//echo $_POST['payment_date']."==".date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));exit;
        if(databaseDateFormat($_POST['payment_date'],'indian','-') < date("Y-m-d",mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"))))
        {
            form_set_error('payment_date','Payment date should not be older than 7 days.');
            $scriptcss .= '$("input[name=payment_date]").addClass("error");';
        }
    	$ddate = getDisbursedDate($_POST['loan_id']);
        //echo $_POST['payment_date']."==".$ddate;exit;
        if(databaseDateFormat($_POST['payment_date'],'indian','-') < $ddate)
        {
            form_set_error('payment_date1','Payment date should be greater than Disburse date.');
            $scriptcss .= '$("input[name=payment_date]").addClass("error");';
        }
    }
    
      */
    
    
    
    
    
    
    
    
	if(!$_POST['bank'])
	{
		form_set_error('bank','Bank name should not be blank.');
		$errorstr = 1;
		$scriptcss .= '$("select[name=bank]").addClass("error");';
	}
	if(isset($_POST['paytype']) && !$_POST['paytype'])
	{
		form_set_error('paytype','Payment type should not be blank.');
		$errorstr = 1;
		$scriptcss .= '$("select[name=paytype]").addClass("error");';
	}
	if(isset($_POST['status']))
	{
		$query = "SELECT (ld.o_principal+ld.o_interest+ld.o_LD+ld.o_other_charges) as totaltorepay FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE l.reg_number = ld.reg_number AND l.loanee_id = '".$loaneeid."'"; 
		//echo $query;exit;
		$res = db_query($query);
		$loanee = db_fetch_object($res);
		//$totaltorepay = $loanee->o_principal + $loanee->o_interest + $loanee->o_LD + $loanee->o_other_charges;
		if($loanee->totaltorepay < $_POST['amount'])
		{
			form_set_error('amount','Payment amount is exceeding the balance amount i.e Rs.'.$loanee->totaltorepay);
			$errorstr = 1;
			$scriptcss .= '$("input[name=amount]").addClass("error");';
		}
	}
    
	return $scriptcss;
}

?>
