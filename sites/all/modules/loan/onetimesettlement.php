<?php
function onetimesettlement()
{
	global $user;
	global $base_root;
	global $base_path;
	global $base_url;

	$account_id = isset($_POST['account_id'])?$_POST['account_id']:'';
	$amount = isset($_POST['amount'])?$_POST['amount']:'';
	$interest = isset($_POST['interest'])?$_POST['interest']:'';

	$array = explode('/',$_GET['q']);
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List Of One Time Settlement(s)', 'loan/listsettlement');
	$breadcrumb[] = 'One Time Settlement Form';
	drupal_set_breadcrumb($breadcrumb);
	
	if(isset($_POST['account_id']))
	{
		$fieldstr = '';
		$valuestr = '';
		$updatestr = '';
		$error = validateOnetimesettlementForm();
		if(!$error)
		{
			$query = "SELECT ld.loan_id,ld.loan_docket FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND l.account_id = '".$_POST['account_id']."'"; 
			$res = db_query($query);
			$loanid = db_fetch_object($res);
			$loan_id = $loanid->loan_id;
			if($_POST['account_id'])
			{
				$fieldstr .= "account_id,";
				$valuestr .= "'".db_escape_string($_POST['account_id'])."',";
			}
			if($_POST['amount'])
			{
				$fieldstr .= "amount,";
				$valuestr .= "'".db_escape_string($_POST['amount'])."',";
			}
			if($_POST['interest'])
			{
				$fieldstr .= "interest,";
				$valuestr .= "'".db_escape_string($_POST['interest'])."',";
			}
			if($_POST['status'])
			{
				$fieldstr .= "status,";
				$valuestr .= "'".db_escape_string($_POST['status'])."',";
			}
			db_query('START TRANSACTION');
			db_query('BEGIN');
			if(isset($_POST['oid'])){
				$updatestr .= "modifiedby = '".$user->uid."', modifieddon = '".time()."'";
				$stmt = "UPDATE tbl_loan_onetimesettlement SET $updatestr WHERE id = ".$_POST['oid'];
				if(!db_query($stmt))
					$inserterror = 1;
			}else{
				$query = "SELECT ld.* FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE ld.reg_number = l.reg_number AND l.account_id = '".$accountnum."'"; 
				$res = db_query($query);
				$loan = db_fetch_object($res);
				$pending_balance = $loan->o_principal + $loan->o_other_charges + $loan->o_LD + $loan->o_interest;
				$fieldstr .= "pending_balance,createdby,createdon";
				$valuestr .= "'".$pending_balance."','".$user->uid."','".time()."'";
				$insert_statement = "INSERT INTO tbl_loan_onetimesettlement ($fieldstr) VALUES ($valuestr)";
				if(!db_query($insert_statement))
					$inserterror = 1;
			}
			
			$stmt = "UPDATE tbl_loan_detail SET loan_status = 0 WHERE loan_id = ".$loan_id;
			if(!db_query($stmt))
				$inserterror = 1;
			$stmt = "UPDATE tbl_workflow_docket SET status = 'closed' WHERE doc_id = ".$loanid->loan_docket;
			if(!db_query($stmt))
				$inserterror = 1;
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
				drupal_goto('loan/onetimesettlement');
			}
		}
	}
	 
	$output = <<<EOD
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">
		<form action="" name="onetimesettlementform" method="post" enctype="multipart/form-data" onSubmit="return onetimesettlementValidation();">
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="onetimesettlement_container">
		<tr class="oddrow">
			<td align="center" colspan="4"><h2>Loan One Time Settlement Form</h2></td> 
		</tr>
		<tr class="evenrow">
			<td class="form-text1">Account Number <span title="This field is required." class="form-required">*</span></td>
			<td align="left" class="form-text1">
				<div class='form-item'>
					<input type="text" name="account_id" value="$account_id" id="account_idid" />
					<input type="button" class="form-submit" value="Show Detail" id="search" name="ls" onclick="return showAccountDetail('$base_url');"/>
					<input type="button" class="form-submit" value="Calculate Interest" id="search" name="ls" onclick="return calculateInterest('$base_url');"/>
				</div><div id="cinterestid"></div>
			</td>
		</tr>
		</table>
		<div id="accdetail"></div>
		<br>
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="onetimesettlement_container">
		<tr class="oddrow">
			<td class="form-text1">Amount <span title="This field is required." class="form-required">*</span></td>
			<td align="left" class="form-text1">
				<div class='form-item'>
					<input type="text" name="amount" value="$amount" id="amountid" />
				</div>
			</td>
		</tr>
		<tr class="oddrow">
			<td class="form-text1">Interest <span title="This field is required." class="form-required">*</span></td>
			<td align="left" class="form-text1">
				<div class='form-item'>
					<input type="text" name="interest" value="$interest" id="interestid" />
				</div>
			</td>
		</tr>

		<tr class="evenrow">
			<td class="form-text1">Status <span title="This field is required." class="form-required">*</span></td>
			<td align="left" class="form-text1">
				<div class='form-item'>
					<select name="status">
					<option value="approved">Approved</option>
					</select>
				</div>
			</td>
		</tr>

</table>

<table width="100%" cellpadding="2" cellspacing="1" border="0">	
	<tr class="oddrow">
		<td colspan="4" align="center" class="back">
			<input type="submit" class="form-submit" value="Save" id="submit" name="ls"/>
			<input type="button" class="form-submit" value="Back" id="back" name="back" onClick="history.back(-1)"/>
		</td>
	</tr>
</table>
</form>
</div>
EOD;


$output .= <<<EOD
	</div>
EOD;
return $output;
}

function listsettlement()
{
	global $user;
	global $base_root;
	global $base_path;
	global $base_url;
	$output = '';
	$array = explode('/',$_GET['q']);
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = 'List Of One Time Settlement(s)';
	drupal_set_breadcrumb($breadcrumb);
	//********************* REPAYMENT LISTING *******************
	
		$limit = (int)getMessage( 'loans', 'code04', NULL);
		$header = array(
			array('data' => t('S. No.')),
			array('data' => t('Account Number'), 'field' => 'account_id', 'sort' => 'desc'),
			array('data' => t('Amount'), 'field' => 'amount', 'sort' => 'asc'),
			array('data' => t('Interest'), 'field' => 'interest', 'sort' => 'asc'),
			array('data' => t('Pending Balance'), 'field' => 'pending_balance', 'sort' => 'asc'),
			array('data' => t('Settlement Date'), 'field' => 'createdon', 'sort' => 'desc'),
		);
	
		if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
			$val = '%'.strtoupper($_REQUEST['searchtext']).'%'; $val=addslashes($val);	 
			$query = "SELECT * FROM tbl_loan_onetimesettlement where UPPER(account_id) LIKE '".$val."' OR UPPER(amount) LIKE '".$val."' OR UPPER(interest) LIKE '".$val."' OR UPPER(pending_balance) LIKE '".$val."'".tablesort_sql($header);
			$sqlcount = "SELECT COUNT(*) AS count FROM tbl_loan_onetimesettlement where UPPER(account_id) LIKE '".$val."' OR UPPER(amount) LIKE '".$val."' OR UPPER(interest) LIKE '".$val."' OR UPPER(pending_balance) LIKE '".$val."'".tablesort_sql($header);
			$rscount = db_query($sqlcount);
			$rscounter = db_fetch_object($rscount);
		}else{
			$query = "SELECT * FROM tbl_loan_onetimesettlement ORDER BY createdon DESC"; 
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
			$rows[] = array(
				
				array('data' => $counter),
				array('data' => ucwords($row->account_id)),
				array('data' => $row->amount),
				array('data' => $row->interest),
				array('data' => $row->pending_balance),
				array('data' => date("d-m-Y",$row->createdon)),
			);
		}
		if($rows== NULL)
			$header=NULL;
	
		 $output .= <<<EOD
			<form method="POST" action=""><table width="100%" border="0" cellspacing="1" cellpadding="1" id="wrapper">
			<tr><td colspan="3" class="searchrecord">
EOD;
			if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
			$output .= t(getMessage( 'loans', 'code03', array("0"=>$rscounter->count)))." | ".l('View All','loan/listsettlement/');
			}
			
			$output .='</td></tr>';
			
			$addurl = l('Settlement Form',"loan/onetimesettlement");
			$lising = 'List Of One Time Settlements';
			
			$output .='<tr><td colspan="3" class="tblHeaderLeft">'.$lising.'<span class="addrecord">'.$addurl.'</span></td>'.
			'   <td colspan="3" class="tblHeaderRight">'.
			'<input type="text" name="searchtext" value="'.$_POST['searchtext'].'">'.
			'&nbsp;<input type="submit" name="search" value="Search"></td></tr>'.
			'</table></form>';
		if(count($rows) == 0)
		{
			$output .= "<br /><br /><center><b>No One time settlement records to show.</b></center>";
		}
	
	
		$output .= theme_table($header,$rows, $attributes = array(), $caption = NULL);
		$output .= theme('pager', NULL, $limit,0 );
	
	//********************* DISBURSEMENT LISTING END *******************
	
	return $output;
}


function validateOnetimesettlementForm()
{
	$errorstr = '';
	$scriptcss = '';

	if(!$_POST['account_id'])
	{
		form_set_error('account_id','Account number should not be blank.');
		$scriptcss .= '$("input[name=account_id]").addClass("error");';
	}
	if($_POST['amount'] >= 0)
	{
		if(indianRuppee('amount',$_POST['amount'],'Amount should be valid amount and in decimal format.'))
		{
			$errorstr = 1;
			$scriptcss .= '$("input[name=amount]").addClass("error");';
		}
	}else{
		form_set_error('amount','Amount should not be blank.');
		$scriptcss .= '$("input[name=amount]").addClass("error");';
	}
	if($_POST['interest'] >= 0)
	{
		if(indianRuppee('interest',$_POST['amount'],'Interest should be valid amount and in decimal format.'))
		{
			$errorstr = 1;
			$scriptcss .= '$("input[name=interest]").addClass("error");';
		}
	}else{
		form_set_error('interest','Interest should not be blank.');
		$scriptcss .= '$("input[name=interest]").addClass("error");';
	}
	return $scriptcss;
}

?>