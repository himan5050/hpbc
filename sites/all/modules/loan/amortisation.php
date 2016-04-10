<?php
function amortisationf(){
	return amortisation('');
}

function amortisation($accountid = '')
{
	global $user;
	global $base_root;
	global $base_path;
	$array = explode('/',$_GET['q']);
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List Of Loans', 'loan/listloans');
	if($array[1] == 'amortisation'){
		$breadcrumb[] = 'Amortisation And EMI Schedule';
	  }
	  
	drupal_set_breadcrumb($breadcrumb);
	$accountid = isset($_POST['account'])?$_POST['account']:$accountid;
	//$loan_id = isset($_POST['loan_id'])?$_POST['loan_id']:$loanid;
    $output = <<<EOD
		      <div id="errorid" class="messages error" style="display:none;"></div>
		      <div id="form-container">
			  <form action="" name="repaymentform" method="post" enctype="multipart/form-data" onSubmit="return amortisationValidation();">
			  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="am_container">
			  <tr class="evenrow">
			  <td class="form-text1">Account Number <span title="This field is required." class="form-required">*</span></td>
			  <td align="left" class="form-text1">
			  <div class='form-item'>
			  <input type="text" name="account" value="$accountid" id="accountid"/>
			  <input type="submit" class="form-submit" value="Search" id="search" name="ls"/>
			  </div>
			  </td>
			  </tr>
			  </table>
			  </form>
		      </div>EOD;
	
	if($accountid){
		
		$sql = "SELECT l.account_id,
		               l.fname, l.lname 
					   FROM tbl_loan_detail ld, tbl_loanee_detail l 
					   WHERE ld.reg_number = l.reg_number AND l.account_id = '".$accountid."'";
		
		$res = db_query($sql);
		$l = db_fetch_object($res);
		if($l->fname)
		{
			//GET TENURE FROM SCHEME MASTER TABLE
			$sql = "SELECT ld.loan_requirement,
			               sm.tenure,
						   ld.ROI,
						   l.account_id,
						   l.fname, l.lname 
						   FROM tbl_loan_detail ld,tbl_loanee_detail l, tbl_scheme_master sm 
						   WHERE ld.reg_number = l.reg_number 
						   AND   ld.scheme_name = sm.loan_scheme_id AND l.account_id = '".$accountid."'";
			
			$res = db_query($sql);
			$em = db_fetch_object($res);
			$tenure = $em->tenure;
			$amount = $em->loan_requirement;
			$interest = $em->ROI;
			$output .= "<br><b>EMI Schedule</b> <br>";
			$output .= emi_calculator($tenure,$amount,$interest,0);
			
			//AMORTISATION TABLE
			$output .= <<<EOD
				<br>
				<b>Repayments</b> <br>
				<b>Account Number :</b> $em->account_id <b>Name :</b> $em->fname $em->lname<br>
EOD;
			$output .= getAmortisationTable($l->account_id);
		}else{
			$output .= "<br><center>There are no information  to show ralated to the account number <b>$accountid</b>";
		}
	}
	return $output;
}

function ld_othercharges()
{
	global $user;
	global $base_root;
	global $base_path;
	$array = explode('/',$_GET['q']);
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = 'LD And Other Charges Entry Form';
	drupal_set_breadcrumb($breadcrumb);
	$accountno = '';
	$class = 'evenrow';
	$output = '';
	
	$accountnum = isset($_POST['account'])?$_POST['account']:'';
	$LD = isset($_POST['LD'])?$_POST['LD']:'';
	$LDreason = isset($_POST['LDreason'])?$_POST['LDreason']:'';
	$other = isset($_POST['other'])?$_POST['other']:'';
	$otherchargesreason = isset($_POST['otherchargesreason'])?$_POST['otherchargesreason']:'';
	if(isset($_POST['account']))
	{
		$err = validateLD();
		if(!$err)
		{
			$accountno = $_POST['account'];
			$accountid = $_POST['account'];
			$query = "SELECT ld.loan_id FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND l.account_id = '".$_POST['account']."'"; 
			$res = db_query($query);
			$loanid = db_fetch_object($res);
			if(isset($_POST['LD']) && $_POST['LD'])
			{
				db_query('START TRANSACTION');
				db_query('BEGIN');
				$sql = "INSERT INTO tbl_loan_interestld (account_id,type,amount,calculation_date,reason) VALUES ('".$_POST['account']."','LD','".$_POST['LD']."',now(),'".$_POST['LDreason']."')";
				if(!db_query($sql))
					$inserterror = 1;
				$sql = "UPDATE tbl_loan_detail SET o_LD = o_LD + ".$_POST['LD']." WHERE loan_id = ".$loanid->loan_id;
				if(!db_query($sql))
					$inserterror = 1;
			}
			if(isset($_POST['other']) && $_POST['other'])
			{
				$accountnum = $_POST['account'];
				$sql = "INSERT INTO tbl_loan_interestld (account_id,type,amount,calculation_date,reason) VALUES ('".$_POST['account']."','other','".$_POST['other']."',now(),'".$_POST['otherchargesreason']."')";
				if(!db_query($sql))
					$inserterror = 1;
				$sql = "UPDATE tbl_loan_detail SET o_other_charges = o_other_charges + ".$_POST['other']." WHERE loan_id = ".$loanid->loan_id;
				if(!db_query($sql))
					$inserterror = 1;
			}
			if($inserterror)
			{
				form_set_error('form',"There is some error in process.Please try again.");
				db_query('ROLLBACK');
			}else{
				db_query('COMMIT');
				$success = 1;
				drupal_set_message("LD and Other Charges successfully applied.");
				//drupal_goto('node/211');
				$_POST['LD'] = '';
				$_POST['other'] = '';
				$_POST['LDreason'] = '';
				$_POST['otherchargesreason'] = '';
			}
			if(isset($_POST['interest']))
			{
				
				$statement = addInterest($_POST['account']);
			}
			$query = "SELECT ld.* FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND l.account_id = '".$_POST['account']."'"; 
			$res = db_query($query);
			$loan = db_fetch_object($res);
		}
	}
	if(isset($success))
	{
			$output = '<table width="100%" cellpadding="2" cellspacing="1" border="0">	';

					if(!$err && (isset($_POST['LD']) || isset($_POST['other'])))
					{
						$class = 'oddrow';
						$output .= '<tr class="evenrow">
							<td colspan="4" align="center" class="back">';
						$output .= "<br>
						<b>Account Number :</b> ".$_POST['account']."<br><br>
						Total Outstanding Interest : ".round($loan->o_interest).", Total Outstanding LD : ".round($loan->o_LD).", Total Outstanding Other Charges : ".round($loan->o_other_charges).", Total Outstanding Pricipal : ".round($loan->o_principal)."
						";
						$output .= '<br><br> '.l("Back to LD & Other charges form","loan/ld_othercharges").'</td>
					</tr>';
					}					
	$output .= <<<EOD
			</table>
EOD;
	}else{
	if($err)
	{
		$output .= '<script>$(function() {'.$err.'});</script>';
	}
	$output .= <<<EOD
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">

		<form action="" name="LD_otherchargesform" method="post" enctype="multipart/form-data">
			<table width="100%" cellpadding="2" cellspacing="1" border="0" id="am_container">
			<tr class="evenrow">
				<td align="center" colspan=4><h2>LD and Other charges entry Form</h2></td> 
			</tr>
			<tr class="oddrow">
				<td><div class="loantext1">Account No. <span title="This field is required." class="form-required">*</span></div>
				<div class="loanform1">
					<div class='form-item'>
						<input type="text" name="account" value="$accountnum" id="accountid" maxlength="15" />
					</div>
				</div></td>
			</tr>
			<tr class="evenrow">
				<td class="form-text1">LD Charges <span title="This field is required.">*</span></td>
				</tr>
			<tr class="oddrow">
				<td><div class="loantext1">Amount:</div>
				<div><input type="text" name="LD" value="$LD" id="ldid" maxlength = "11"  onkeypress="return paypaymain_custom(event,'ldid',11);" /></div></td>
			</tr>
			<tr class="evenrow">
				<td><div class="loantext1">Reason:</div>
				<div><select name="LDreason" id="LDreasonid">
						<option value="">Select</option>
EOD;
	$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'LD Charges' ORDER BY l.lookup_name";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$selected = "";
		if($LDreason == $row->lid)
			$selected = " selected = 'selected'";
		$output .= '<option value="'.$row->lid.'" '.$selected.'>'.ucwords($row->lname).'</option>';
	}
						
	$output .= <<<EOD
					</select>
			</div></td></tr>					
				
		
			<tr class="oddrow">
				<td class="form-text1" colspan="2">Other Charges <span title="This field is required.">*</span></td>				
			</tr>
			<tr class="evenrow">
				<td><div class="loantext1">Amount:</div>
				<div class="loanform1"><input type="text" name="other" value="$other" id="otherid" maxlength = "11" onkeypress="return paypaymain_custom(event,'ldid',11);" /></div></td>				
			</tr>
			<tr class="oddrow">
				<td><div class="loantext1">Reason:</div>
				<div class="loanform1"><select name="otherchargesreason" id="otherreasonid">
						<option value="">Select</option>
EOD;
	$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Other Charges' ORDER BY l.lookup_name";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$selected = "";
		if($otherchargesreason == $row->lid)
			$selected = " selected = 'selected'";
		$output .= '<option value="'.$row->lid.'" '.$selected.'>'.ucwords($row->lname).'</option>';
	}
	$output .= <<<EOD
					</select></div></td>				
			</tr>
			
			<tr class="$class">
				<td colspan="4" align="center" class="back">
					<input type="submit" class="form-submit" value="Save" id="submit" name="ls"/>
				</td>
			</tr>
			</table>
		</form>
	</div>
EOD;
	}
	return $output;
}
function validateLD()
{
	$errorstr = '';
	$scriptcss = '';
	if(isEmpty('account',$_POST['account'],'Account number'))
	{
		$scriptcss .= '$("#accountid").addClass("error");';
	}
	/*
	elseif(alphanumeric('account',$_POST['account'],'Account number'))
	{
		$scriptcss .= '$("#accountid").addClass("error");';
	} */
	$query = "SELECT ld.loan_id FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND l.account_id = '".$_POST['account']."'"; 
	$res = db_query($query);
	$loanid = db_fetch_object($res);
	if(!$loanid->loan_id)
	{
		form_set_error('account','Invalid account number.');
		$scriptcss .= '$("#accountid").addClass("error");';
	}
	
	if(trim($_POST['LD']) == '' && trim($_POST['other']) == '')
	{
		form_set_error('LD', t('Both LD and other charges amount can not be blank'));
		$scriptcss .= '$("#ldid").addClass("error");';
		$scriptcss .= '$("#otherid").addClass("error");';
		$scriptcss .= '$("#LDreasonid").addClass("error");';
		$scriptcss .= '$("#otherreasonid").addClass("error");';
	}
	
	if($_POST['LD'])
	{
		if(paypay('LD',$_POST['LD'],'LD'))
		{
			$scriptcss .= '$("#ldid").addClass("error");';
		}
	}
	if($_POST['LD'] && isEmpty('LDreason',$_POST['LDreason'],'LD reason'))
	{
		$scriptcss .= '$("#LDreasonid").addClass("error");';
	}
	if($_POST['other'])
	{
		if(paypay('other',$_POST['other'],'Other charges'))
		{
			$scriptcss .= '$("#otherid").addClass("error");';
		}
	}
	if($_POST['other'] && isEmpty('otherchargesreason',$_POST['otherchargesreason'],'Other charges reason'))
	{
		$scriptcss .= '$("#otherreasonid").addClass("error");';
	}
	return $scriptcss;
}
?>