<?php

function pendingvouchers()
{
	
	global $user;
	global $base_root;
	global $base_path,$base_url;
	session_start();
	$array = explode('/',$_GET['q']);
	$output = '';
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loan(s)', 'loan/listloans');
	if($array[1] == 'pendingvouchers'){
		$breadcrumb[] = l('List of Pending Voucher(s)', 'loan/pendingvouchers');
	  }
	drupal_set_breadcrumb($breadcrumb);
	$ac = $base_url.'/loan/pendingvouchers';	
	$limit = (int)getMessage( 'loans', 'code04', NULL);
	$header = array(
		array('data' => t('S. No.')),
		array('data' => t('Account Id'), 'field' => 'account_id', 'sort' => 'desc'),
		array('data' => t('Amount'), 'field' => 'amount', 'sort' => 'asc'),
		array('data' => t('Bank'), 'field' => 'bank', 'sort' => 'desc'),
		array('data' => t('Transaction Type'), 'field' => 'entrytype', 'sort' => 'asc'),
		array('data' => t('Created Date'), 'field' => 'createdon', 'sort' => 'desc'),
		array('data' => t('Action')),
	);
		if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
			$val = '%'.strtoupper($_REQUEST['searchtext']).'%'; $val=addslashes($val);	 
			$query = "SELECT * FROM tbl_pendingvouchers lp WHERE lp.pendingstatus = 0 AND (UPPER(lp.account_id) LIKE '".$val."' OR UPPER(lp.amount) LIKE '".$val."' OR UPPER(lp.bank) LIKE '".$val."' OR UPPER(lp.entrytype) LIKE '".$val."' OR DATE_FORMAT( FROM_UNIXTIME( lp.createdon ) , '%%d-%%m-%%Y' ) LIKE '".$val."')".tablesort_sql($header);
			//echo $query;exit;
			$sqlcount = "SELECT COUNT(*) AS count FROM tbl_pendingvouchers lp WHERE lp.pendingstatus = 0 AND (UPPER(lp.account_id) LIKE '".$val."' OR UPPER(lp.amount) LIKE '".$val."' OR UPPER(lp.bank) LIKE '".$val."' OR UPPER(lp.entrytype) LIKE '".$val."' OR DATE_FORMAT( FROM_UNIXTIME( lp.createdon ) , '%%d-%%m-%%Y' ) LIKE '".$val."')".tablesort_sql($header);
			$rscount = db_query($sqlcount);
			$rscounter = db_fetch_object($rscount);
		}else{
			$query = "SELECT * FROM tbl_pendingvouchers lp WHERE lp.pendingstatus = 0".tablesort_sql($header); 
		}
		$result = pager_query($query, $limit);
	
		if($_REQUEST['page']){
		 $counter = $_REQUEST['page']*$limit;
		}else{
		 $counter = 0;
		}
		while($row=db_fetch_object($result)) {
			$counter++;		
			if($row->entrytype == 'loanadvance')
			{
				//$action = l('Detail','account/pendingvoudetail.php?id='.$row->transactionid).' | ';
					global $base_url;
				$action = '<a href= "'.$base_url.'/account/pendingvoudetail.php?id='.$row->transactionid.'">Detail</a> | ';
			}elseif($row->entrytype == 'billsubmit')
			{
				//$action = l('Detail','account/pendingbilldetail.php?id='.$row->transactionid).' | ';
				global $base_url;
				$action = '<a href= "'.$base_url.'/account/pendingbilldetail.php?id='.$row->transactionid.'">Detail</a> | ';
			}else{
				$action = l('Detail','loan/voucher_detail/'.$row->entrytype.'/'.$row->transactionid).' | ';
			}
			if($row->entrytype == 'interest')
				$action = '';
		 $action .= <<<EOD
			<select name="voucher_type" onChange="paid($row->amount,'$row->GLaccount_code',this.value,$row->id,'$row->entrytype','$base_url')";>
			<option value="">--Select Type--</option>
			<option value="journal">Journal</option>
EOD;
			if($row->vtype == 2)
				$action .= '<option value="payment">Payment</option>';
			if($row->vtype == 3)
				$action .= '<option value="receipt">Receipt</option>';
			$rows[] = array(
				
				array('data' => $counter),
				array('data' => ucwords($row->account_id)),
				array('data' => round($row->amount)),
				array('data' => ucwords($row->bank)),
				array('data' => ucwords($row->entrytype)),
				array('data' => date("d-m-Y",$row->createdon)),
				array('data' => $action),
			);
		}
		if($rows== NULL)
			$header=NULL;
		if(isset($_SESSION['message']))
		{
			$output .= '<div class="messages status">'.$_SESSION['message'].'</div>';
			$_SESSION['message'] = '';
			unset($_SESSION['message']);
		}
		 $output .= <<<EOD
			<form method="POST" action="$ac"><table width="100%" border="0" cellspacing="1" cellpadding="1" id="wrapper">
			<tr><td colspan="3" class="searchrecord">
EOD;
			if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
			$output .= t(getMessage( 'loans', 'code03', array("0"=>$rscounter->count)))." | ".l('View All','loan/pendingvouchers/'.$loanid);
			}
			
			$output .='</td></tr>';
			
			$lising = 'List of Pending Vouchers';
			
			$output .='<tr><td colspan="3" class="tblHeaderLeft">'.$lising.'</td>'.
			'   <td colspan="3" class="tblHeaderRight">'.
			'<input type="text" name="searchtext" value="'.$_POST['searchtext'].'">'.
			'&nbsp;<input type="submit" name="search" value="Search"></td></tr>'.
			'</table></form>';
		if(count($rows) == 0)
		{
			$output .= "<br /><br /><center><b>No pending vouchers to show.</b></center>";
		}
		$output .= theme_table($header,$rows, $attributes = array(), $caption = NULL);
		$output .= theme('pager', NULL, $limit,0 );
	return $output;
}
function voucher_detail($entrytype = '',$trid = '')
{
	if(!$entrytype || !$trid)
	{
		$output = "Invalid url.";
		return $output;
	}
	switch($entrytype)
	{
		case 'Repayment':
				$output = getRepaymentDetail($entrytype,$trid);
				return $output;
			break;
		case 'EMI':
				$output = getRepaymentDetail($entrytype,$trid);
				return $output;
			break;
		case 'Promoter Share':
				$output = getRepaymentDetail($entrytype,$trid);
				return $output;
			break;
		case 'Disbursement':
				$output = getDisbursementDetail($entrytype,$trid);
				return $output;
			break;
		case 'One Time Settlement':
				$output = getRepaymentDetail($entrytype,$trid);
				return $output;
			break;
		case 'Weaver':
				$output = getWeaverDetail($trid);
				return $output;
			break;
		case 'Claim':
				$output = getClaimDetail($entrytype,$trid);
				return $output;
			break;
	}
}

function getWeaverDetail($trid = '')
{
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List Of Pending Voucher(s)', 'loan/pendingvouchers');
	$breadcrumb[] = "Weaver Voucher Detail";
	drupal_set_breadcrumb($breadcrumb);
	$query = "SELECT *,pv.amount as vamount,lr.amount as lamount FROM tbl_loan_repayment lr,tbl_loan_detail ld,tbl_loanee_detail l,tbl_pendingvouchers pv WHERE lr.loanee_id = l.loanee_id AND l.reg_number = ld.reg_number AND pv.transactionid = lr.id AND pv.entrytype = 'Weaver' AND pv.transactionid = $trid ";
	//echo $query;exit;
	$res = db_query($query);
	$detail = db_fetch_object($res);
	$output ='<table width="100%" cellpadding="2" cellspacing="1" border="0">';
	$output .='<tr class="evenrow"><td align="center" colspan="2"><h2>Weaver Voucher Detail</h2></td></tr>';
	$output .='<tr class="oddrow"><td >Loan Account No.</td><td width="446">'.$detail->account_id.'</td></tr>';
	$output .= '<tr class="evenrow"><td>Name</td><td>'.ucwords($detail->fname.' '.$detail->lname).'</td></tr>';
	$output .='<tr class="oddrow"><td>Loan Amount</td><td>'.round($detail->loan_requirement).'</td></tr>';
	$output .='<tr class="evenrow"><td>Last Amount Paid</td><td>'.round($detail->lamount).'</td></tr>';
	$output .='<tr class="oddrow"><td>Weaver Amount</td><td>'.round($detail->vamount).'</td></tr>';
	$output .='<tr class="oddrow"><td class="back" colspan="2" align="center">'.l("Back",'loan/pendingvouchers/').'</td></tr>';
	$output .='</table>';
	return $output;
}
function getRepaymentDetail($entrytype = '',$trid = '')
{
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List Of Pending Voucher(s)', 'loan/pendingvouchers');
	$breadcrumb[] = "Payment Voucher Detail";
	drupal_set_breadcrumb($breadcrumb);
	$query = "SELECT * FROM tbl_loan_repayment lr,tbl_loan_detail ld,tbl_loanee_detail l WHERE lr.loanee_id = l.loanee_id AND l.reg_number = ld.reg_number AND lr.id = $trid ";
	//echo $query;exit;
	$res = db_query($query);
	$detail = db_fetch_object($res);
	$output ='<table width="100%" cellpadding="2" cellspacing="1" border="0">';
	$output .='<tr class="evenrow"><td align="center" colspan="2"><h2>Payment Voucher Detail</h2></td></tr>';
	$output .='<tr class="oddrow"><td >Loan Account No.</td><td width="446">'.$detail->account_id.'</td></tr>';
	$output .= '<tr class="evenrow"><td>Name</td><td>'.ucwords($detail->fname.' '.$detail->lname).'</td></tr>';
	$output .='<tr class="oddrow"><td>Loan Amount</td><td>'.round($detail->loan_requirement).'</td></tr>';
	$output .='<tr class="evenrow"><td>Cheque/DD Number</td><td>'.$detail->cheque_number.'</td></tr>';
	$output .='<tr class="oddrow"><td>Cheque Date</td><td>'.date("d-m-Y",strtotime($detail->cheque_date)).'</td></tr>';
	$output .='<tr class="oddrow"><td>Cheque Date</td><td>'.date("d-m-Y",strtotime($detail->payment_date)).'</td></tr>';
	$output .='<tr class="evenrow"><td>Amount</td><td>'.round($detail->amount).'</td></tr>';
	$output .='<tr class="oddrow"><td>In Favour Of</td><td>'.ucwords($detail->in_favour_of).'</td></tr>';
	$output .='<tr class="oddrow"><td class="back" colspan="2" align="center">'.l("Back",'loan/pendingvouchers/').'</td></tr>';
	$output .='</table>';
	return $output;
}

function getDisbursementDetail($entrytype = '',$trid = '')
{
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List Of Pending Voucher(s)', 'loan/pendingvouchers');
	$breadcrumb[] = "Disbursement Voucher Detail";
	drupal_set_breadcrumb($breadcrumb);
	$query = "SELECT * FROM tbl_loan_disbursement d,tbl_loan_detail ld,tbl_loanee_detail l WHERE d.loanee_id = l.loanee_id AND l.reg_number = ld.reg_number AND d.id = $trid ";
	//echo $query;exit;
	$res = db_query($query);
	$detail = db_fetch_object($res);
	$output ='<table width="100%" cellpadding="2" cellspacing="1" border="0">';
	$output .='<tr class="evenrow"><td align="center" colspan="2"><h2>Disbursement Voucher Detail</h2></td></tr>';
	$output .='<tr class="oddrow"><td >Loan Account No.</td><td width="446">'.$detail->account_id.'</td></tr>';
	$output .= '<tr class="evenrow"><td>Name</td><td>'.ucwords($detail->fname.' '.$detail->lname).'</td></tr>';
	$output .='<tr class="oddrow"><td>Loan Amount</td><td>'.round($detail->loan_requirement).'</td></tr>';
	$output .='<tr class="evenrow"><td>Cheque/DD Number</td><td>'.$detail->cheque_number.'</td></tr>';
	$output .='<tr class="oddrow"><td>Cheque Date</td><td>'.date("d-m-Y",strtotime($detail->cheque_date)).'</td></tr>';
	$output .='<tr class="evenrow"><td>Amount</td><td>'.round($detail->amount).'</td></tr>';
	$output .='<tr class="oddrow"><td>In Favour Of</td><td>'.$detail->in_favour_of.'</td></tr>';
	$output .='<tr class="oddrow"><td class="back" colspan="2" align="center">'.l("Back",'loan/pendingvouchers/').'</td></tr>';
	$output .='</table>';
	return $output;
}

?>