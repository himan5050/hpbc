<?php

global $bil,$cha,$ham,$kan,$kul,$man,$shi,$sir,$sol,$una;
    
    $bil = 10047;
    $cha = 12125;
    $ham = 14148;
    $kan = 22781;
    $kul = 71145;
    $man = 71134;
    $shi = 80048;
    $sir = 75187;
    $sol = 81044;
    $una = 86340;

function listdisbursment($loanid = '') {
    global $user;
    global $base_root;
    global $base_path;
    $array = explode('/', $_GET['q']);

    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = l('List of Loan(s)', 'loan/listloans');
    if ($array[1] == 'disbursement') {
        $breadcrumb[] = 'List of Disbursement(s)';
    }
    drupal_set_breadcrumb($breadcrumb);
//********************* DISBURSEMENT LISTING *******************
    $output = '';
    $query = "SELECT ld.loanee_id FROM tbl_loanee_detail ld,tbl_loan_detail l WHERE l.reg_number = ld.reg_number AND l.loan_id = '" . $loanid . "'";
    $res = db_query($query);
    $loanee = db_fetch_object($res);
    $loaneeid = $loanee->loanee_id;
    $limit = (int) getMessage('loans', 'code04', NULL);
    $header = array(
        array('data' => t('S. No.')),
        array('data' => t('Cheque/DD No.'), 'field' => 'cheque_number', 'sort' => 'desc'),
        array('data' => t('Amount'), 'field' => 'amount', 'sort' => 'asc'),
        array('data' => t('In Favour Of'), 'field' => 'in_favour_of', 'sort' => 'asc'),
        array('data' => t('Bank'), 'field' => 'bank', 'sort' => 'desc'),
        array('data' => t('Cheque Date'), 'field' => 'cheque_date', 'sort' => 'desc'),
            //array('data' => t('Action')),
    );

    if (isset($_REQUEST['searchtext']) && $_REQUEST['searchtext'] != '') {
        $val = '%' . strtoupper($_REQUEST['searchtext']) . '%';
        $val = addslashes($val);
        $query = "SELECT * FROM tbl_loan_disbursement where (UPPER(cheque_number) LIKE '" . $val . "' OR UPPER(amount) LIKE '" . $val . "' OR UPPER(in_favour_of) LIKE '" . $val . "' OR UPPER(cheque_date) LIKE '" . $val . "' OR UPPER(bank) LIKE '" . $val . "') AND loanee_id = $loaneeid " . tablesort_sql($header);
        $sqlcount = "SELECT COUNT(*) AS count FROM tbl_loan_disbursement where (UPPER(cheque_number) LIKE '" . $val . "' OR UPPER(amount) LIKE '" . $val . "' OR UPPER(in_favour_of) LIKE '" . $val . "' OR UPPER(cheque_date) LIKE '" . $val . "' OR UPPER(bank) LIKE '" . $val . "') AND loanee_id = $loaneeid " . tablesort_sql($header);
        $rscount = db_query($sqlcount);
        $rscounter = db_fetch_object($rscount);
    } else {
        $query = "SELECT * FROM tbl_loan_disbursement WHERE loanee_id = $loaneeid " . tablesort_sql($header);
    }
    $output .= <<<EOD
 	<form method="POST" action=""><table width="100%" border="0" cellspacing="1" cellpadding="1" id="wrapper">
	<tr><td colspan="3" class="searchrecord">
EOD;
    if (isset($_REQUEST['searchtext']) && $_REQUEST['searchtext'] != '') {
        $output .= t(getMessage('loans', 'code03', array("0" => $rscounter->count))) . " | " . l('View All', 'loan/disbursement/' . $loaneeid);
    }

    $output .='</td></tr>';

    $addurl = l('Disbursement Form', "loan/disbursementform/" . $loanid . '/0');
    $lising = 'List of Disbursement(s)';

    $output .='<tr><td colspan="3" class="tblHeaderLeft">' . $lising . '<span class="addrecord">' . $addurl . '</span></td>' .
            '   <td colspan="3" class="tblHeaderRight">' .
            '<input type="text" name="searchtext" value="' . $_POST['searchtext'] . '">' .
            '&nbsp;<input type="submit" name="search" value="Search"></td></tr>' .
            '</table></form>';
    $result = pager_query($query, $limit);

    if ($_REQUEST['page']) {
        $counter = $_REQUEST['page'] * $limit;
    } else {
        $counter = 0;
    }
    while ($row = db_fetch_object($result)) {
        $counter++;
        $deleteurl = '';
        //if(!$row->voucher_posted)
        //$deleteurl = l('Delete','loan/disbursementdelete/'.$row->id.'/'.$loanid);
        $rows[] = array(
            array('data' => $counter),
            array('data' => ucwords($row->cheque_number)),
            array('data' => round($row->amount)),
            array('data' => $row->in_favour_of),
            array('data' => $row->bank),
            array('data' => date("d-m-Y", strtotime($row->cheque_date))),
                //array('data' => $deleteurl,'onclick' => "return confirm('Are you sure you want to delete the disbursement');"),
        );
    }
    if ($rows == NULL)
        $header = NULL;
    if (count($rows) == 0) {
        $output .= "There Are No Disbursement(s) To Show.";
    }
    $output .= theme_table($header, $rows, $attributes = array(), $caption = NULL);
    $output .= theme('pager', NULL, $limit, 0);
    $output .= '<br><div class="back" align="center">' . l("Back", 'loan/listloans/') . '</div>';

//********************* DISBURSEMENT LISTING END *******************


    return $output;
}

function disbursementdelete2($id, $loan_id) {
    $error = '';
    db_query('START TRANSACTION');
    db_query('BEGIN');
    $query = "SELECT amount FROM tbl_loan_disbursement WHERE id = '" . $id . "' LIMIT 1";
    $res = db_query($query);
    $d = db_fetch_object($res);
    $sql = "UPDATE tbl_loan_detail SET o_principal = o_principal - " . $d->amount . ", o_disburse_amount = o_disburse_amount + " . $d->amount . " WHERE loan_id = " . $loan_id;
    if (!db_query($sql))
        $error = 1;
    $query = "DELETE FROM tbl_loan_disbursement WHERE id = '" . $id . "' LIMIT 1";
    if (!db_query($query)) {
        $error = 1;
        $message = "There is some error deleting Disbursement";
    } else {
        $message = "Disbursement successfully deleted";
    }
    $query = "DELETE FROM tbl_pendingvouchers WHERE transactionid = '" . $id . "' LIMIT 1";
    if (!db_query($query))
        $error = 1;
    if ($error) {
        db_query('ROLLBACK');
    } else {
        db_query('COMMIT');
    }
    drupal_set_message($message);
    drupal_goto('loan/disbursement/' . $loan_id);
}

function disbursmentform($loanid = '', $did = '') {
    global $user;
    global $base_root;
    global $base_path;
    global $base_url;
    
    
    
    $array = explode('/', $_GET['q']);
    $cheque_number = isset($_POST['cheque_number']) ? $_POST['cheque_number'] : '';
    $amount = isset($_POST['amount']) ? $_POST['amount'] : '';
    $in_favour_of = isset($_POST['in_favour_of']) ? $_POST['in_favour_of'] : '';
    $cheque_date = isset($_POST['cheque_date']) ? $_POST['cheque_date'] : '';
    $bank = isset($_POST['bank']) ? $_POST['bank'] : '';

    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = l('List of Loans', 'loan/listloans');
    $breadcrumb[] = l('List of Disbursement(s)', 'loan/disbursement/' . $loanid);
    if ($array[1] == 'disbursementform') {
        $breadcrumb[] = 'Loan Disburse Form';
    }
    drupal_set_breadcrumb($breadcrumb);

    $loan_id = isset($_POST['loan_id']) ? $_POST['loan_id'] : $loanid;

    $query = "SELECT ld.loanee_id,l.scheme_name,ld.corp_branch FROM tbl_loanee_detail ld,tbl_loan_detail l WHERE l.reg_number = ld.reg_number AND l.loan_id = '" . $loan_id . "'";
    $res = db_query($query);
    $loanee = db_fetch_object($res);

    $loaneeid = $loanee->loanee_id;
    if ($did) {
        $query = "SELECT * FROM tbl_loan_disbursement WHERE id = $did";
        $res = db_query($query);
        $r = db_fetch_object($res);
        $cheque_number = $r->cheque_number;
        $amount = $r->amount;
        $in_favour_of = $r->in_favour_of;
        $cheque_date = $r->cheque_date;
        $bank = $r->bank;
    }

    if (isset($_POST['cheque_number'])) {
        $fieldstr = '';
        $valuestr = '';
        $updatestr = '';
        $error = validateDisbursementForm();
        if (!$error) {
            if ($_POST['cheque_number']) {
                if (isset($_POST['did'])) {
                    $updatestr .= "cheque_number = '" . db_escape_string($_POST['cheque_number']) . "',";
                } else {
                    $fieldstr .= "cheque_number,";
                    $valuestr .= "'" . db_escape_string($_POST['cheque_number']) . "',";
                }
            }
            if ($_POST['amount']) {
                if (isset($_POST['did'])) {
                    $updatestr .= "amount = '" . db_escape_string($_POST['amount']) . "',";
                } else {
                    $fieldstr .= "amount,";
                    $valuestr .= "'" . db_escape_string($_POST['amount']) . "',";
                }
            }
            if ($_POST['in_favour_of']) {
                if (isset($_POST['did'])) {
                    $updatestr .= "in_favour_of = '" . $_POST['in_favour_of'] . "',";
                } else {
                    $fieldstr .= "in_favour_of,";
                    $valuestr .= "'" . db_escape_string($_POST['in_favour_of']) . "',";
                }
            }
            if ($_POST['cheque_date']) {
                $_POST['cheque_date'] = databaseDateFormat($_POST['cheque_date'], 'indian', '-');
                if (isset($_POST['rid'])) {
                    $updatestr .= "cheque_date = '" . $_POST['cheque_date'] . "',";
                } else {
                    $fieldstr .= "cheque_date,";
                    $valuestr .= "'" . db_escape_string($_POST['cheque_date']) . "',";
                }
            }
            if ($_POST['bank']) {
                $query = "SELECT bank_name FROM tbl_bank  WHERE bank_id = '" . $_POST['bank'] . "' AND status = 1 LIMIT 1";
                $res = db_query($query);
                $b = db_fetch_object($res);
                if (isset($_POST['rid'])) {
                    $updatestr .= "bank = '" . $b->bank_name . "',";
                } else {
                    $fieldstr .= "bank,";
                    $valuestr .= "'" . db_escape_string($b->bank_name) . "',";
                }
            }
            db_query('START TRANSACTION');
            db_query('BEGIN');
            if (isset($_POST['did'])) {
                $updatestr .= "modifiedby = '" . $user->uid . "', modifieddon = '" . time() . "'";
                $stmt = "UPDATE tbl_loan_disbursement SET $updatestr WHERE id = " . $_POST['did'];
                if (!db_query($stmt))
                    $inserterror = 1;
            }else {
                $fieldstr .= "createdby,createdon";
                $valuestr .= "'" . $user->uid . "','" . time() . "'";
                //$fields = trim($fields,',');
                //$valuestr = trim($valuestr,',');
                $lastiid = '';
                $insert_statement = "INSERT INTO tbl_loan_disbursement (loanee_id,$fieldstr) VALUES (" . $loaneeid . ",$valuestr)";
                if (!db_query($insert_statement))
                    $inserterror = 1;
                else
                    $lastiid = db_last_insert_id('tbl_loan_disbursement', 'id');
                $sql = "SELECT COUNT(*) FROM tbl_loan_disbursement WHERE loanee_id = $loaneeid";
                $dcount = db_result(db_query($sql));

                if ($dcount == 1) {//FIRST DISBURSEMENT
                    $sql = "SELECT scheme_code,tenure FROM tbl_scheme_master WHERE loan_scheme_id = " . $loanee->scheme_name . " AND active = 1";
                    $snres = db_query($sql);
                    $sn = db_fetch_object($snres);
                    
                    $sql = "SELECT BO_code FROM tbl_corporations WHERE corporation_id = " . $loanee->corp_branch;
                    $bores = db_query($sql);
                    $bo = db_fetch_object($bores);
                    
                    $sql = "SELECT uniquenum FROM tbl_loanee_detail ORDER by uniquenum DESC LIMIT 1";
                    $unumres = db_query($sql);
                    $unum = db_fetch_object($unumres);
                    
                    if ($unum->uniquenum)
                        $uniqueno = $unum->uniquenum + 1;
                    else
                        $uniqueno = 100000;

                    //$accountno = $bo->BO_code . $sn->scheme_code . $uniqueno;
                    $sql = "SELECT `district` FROM `tbl_loanee_detail` WHERE `loanee_id` = '".$loaneeid."'";
                    $sdistres = db_query($sql);
                    $sdist = db_fetch_object($sdistres);
                    //$district = $sdist->district;
                    
                    $sql2 = "SELECT `district_uid`,`short_code` FROM `tbl_district` WHERE `district_id` = '".$sdist->district."'";
                    $sdistres2 = db_query($sql2);
                    $sdist2 = db_fetch_object($sdistres2);
                    //$district_name = $sdist2->district_name;
                    $short_code = $sdist2->short_code;
                    $dist_uid = $sdist2->district_uid;
                    if(isset($dist_uid))
                        $dist_uid++;
                    else
                        $dist_uid = 00000;
                    //echo $short_code;
                    $accountno = generate_accountno($short_code,$dist_uid);
                    //echo 'First Call = '.$accountno.'<br>';
                   
                    //Emi Calculation on total term loan for perticular loan_id.
                    $sql = "SELECT tbl_loan_detail.project_cost,tbl_loan_detail.ROI FROM tbl_loan_detail WHERE loan_id = " . $_POST['loan_id'];
                    $pcres = db_query($sql);
                    $pc = db_fetch_object($pcres);
                    $project_cost = $pc->project_cost;
                    //echo 'Project Cost Value = '.$project_cost.'<br>';
                    $promotor_share = round(($project_cost*5)/100);
                    //echo 'Promotor Share Value = '.$promotor_share.'<br>';
                    $total_term_loan = round($project_cost - $promotor_share);
                    //echo 'Total Term Loan = '.$total_term_loan.'<br>';
                    $tenure_period = $sn->tenure;
                    //echo 'Tenure Period = '.$tenure_period.'<br>';
                    $roi = $pc->ROI;
                    //echo 'Rate of Interest = '.$roi.'<br>';
                    $emi_value = emi_calculation($total_term_loan, $roi, $tenure_period);
                    //echo 'EMI Calculated value = '.$emi_value.'<br>';
                    $sql = "UPDATE tbl_loan_detail SET emi_amount = '" .$emi_value. "' WHERE loan_id = " . $_POST['loan_id'];
                    if(!db_query($sql))
                        $inserterror = 1;
                    
                    $sql = "UPDATE tbl_loanee_detail SET account_id = '" . $accountno . "', uniquenum = '" . $uniqueno . "' WHERE loanee_id = $loaneeid";
                    if (!db_query($sql))
                        $inserterror = 1;
                    
                    $sql = "UPDATE `tbl_district` SET `district_uid`= '".$dist_uid."' WHERE `district_id` = '".$sdist->district."'";
                    if(!db_query($sql))
                        $inserterror = 1;
                    //$sql = "UPDATE tbl_loan_detail SET last_interest_calculated = '".$_POST['cheque_date']."',o_disburse_amount = loan_requirement - ".$_POST['amount'].",o_principal =  WHERE loan_id = ".$_POST['loan_id'];
                    //if(!db_query($sql))
                    //$inserterror = 1;
                    $sql = "UPDATE tbl_loan_detail SET last_interest_calculated = '" . date("Y-m-d") . "',loan_disburse_date = '" . date("Y-m-d") . "' WHERE loan_id = " . $_POST['loan_id'];
                    if (!db_query($sql))
                        $inserterror = 1;
                    
                    
                    
                    
                }
                
                
                
                $sql = "UPDATE tbl_loan_detail SET o_principal = o_principal + " . $_POST['amount'] . ", o_disburse_amount = o_disburse_amount - " . $_POST['amount'] . " WHERE loan_id = " . $_POST['loan_id'];
                if (!db_query($sql))
                    $inserterror = 1;
                $q = "SELECT am_id FROM tbl_loan_amortisaton WHERE loan_id = '" . $_POST['loan_id'] . "' ORDER BY am_id DESC LIMIT 1";
                $rh = db_query($q);
                $amd = db_fetch_object($rh);
                if ($am->am_id) {
                    $q = "UPDATE tbl_loan_amortisaton SET ending_balance = ending_balance + " . $_POST['amount'] . " WHERE am_id = '" . $amd->am_id . "'";
                    if (!db_query($q))
                        $inserterror = 1;
                }
                $q = "SELECT ld.o_disburse_amount,sm.tenure,ld.loan_requirement,ld.ROI FROM tbl_loan_detail ld,tbl_scheme_master sm WHERE ld.scheme_name = sm.loan_scheme_id AND loan_id = '" . $_POST['loan_id'] . "' LIMIT 1";
                $rl = db_query($q);
                $dis = db_fetch_object($rl);
                
               /* if ($dis->o_disburse_amount == '0.00') {
                    $err = emi_calculator($dis->tenure, $dis->loan_requirement, $dis->ROI, $dis->capital_subsidy, $_POST['loan_id']);
                    if ($err)
                        $inserterror = 1;
                }*/

                /* 				$query = "SELECT account_id FROM tbl_loanee_detail WHERE loanee_id = '".$loaneeid."'"; 				
                  $res = db_query($query);
                  $loanee = db_fetch_object($res);
                 */
                $success = voucherentry($lastiid, getAccountNo($loaneeid), 'Disbursement', $_POST['amount'], '', $b->bank_name, '2');
                if (!$success)
                    $inserterror = 1;
            }
            if ($inserterror) {
                db_query('ROLLBACK');
                $message = getMessage('loans', 'code15', '');
                drupal_set_message($message);
                drupal_goto('loan/disbursement/' . $loan_id);
            } else {
                db_query('COMMIT');
                $reg = getRegNumber($loan_id);
                if (isset($_POST['did']))
                    $message = getMessage('loans', 'code14', '');
                else
                    $message = getMessage('loans', 'code13', array("0" => $reg));
                drupal_set_message($message);
                $l = unserialize(getLoanDetail('', 1, $docket));
                $applicant = ucwords($l[4]);
                $accountno = getAccountNo($loaneeid);
                $amount = number_format($_POST['amount'], 2, '.', '');
                $sanctioned_date = getSanctionedDate($loanid);
                $parameter = json_encode(array("$applicant", "$applicant", "$accountno", "$sanctioned_date", "$amount"));
                createMail('loan_disbursed', $to, '', $parameter);
                drupal_goto('loan/disbursement/' . $loan_id);
            }
        }
    }

    $output = <<<EOD
	<script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.core.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.datepicker.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/lib/jquery.timeentry.pack.js?K"></script>
    
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/date_popup.js?K"></script>
		<script>
			$(function() {
				$( "#cheque_dateid" ).datepicker();
				$( "#cheque_dateid" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
EOD;
    if ($error) {
        $output .= $error;
    }

    $output .= <<<EOD
			});
		</script>
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">
		<form action="" name="disburseform" method="post" enctype="multipart/form-data">
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper2">
		<tr class="oddrow">
			<td align="center" colspan=4><h2>Loan Disburse Form</h2></td> 
		</tr>
		<tr class="evenrow">
			<td ><div class="loantext1">Cheque/DD No.: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="cheque_number" value="$cheque_number" id="cheque_numberid" maxlength="6" onkeypress="return fononlyn(event);" />
				</div></div>
			</td>
		</tr>
		<tr class="oddrow">
			<td><div class="loantext1">Amount: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
					<input type="text" name="amount" value="$amount" id="amountid" maxlength="11" onkeypress="return paypaymain_custom(event,'amountid',11);" />
				</div></div>
			</td>
		</tr>

		<tr class="evenrow">
			<td><div class="loantext1">In Favour Of: <span title="This field is required." class="form-required">*</span></div>
	<div class="loanform">
				<div class='form-item'>
					<input type="text" name="in_favour_of" value="$in_favour_of" id="in_favour_ofid" maxlength="45" onkeypress="return alphabet(event);" /></div>
				</div>
			</td>
		</tr>
		<tr class="oddrow">
			<td><div class="loantext1">Cheque Date: <span title="This field is required." class="form-required">*</span></div>
		<div class="loanform">
				<div class='form-item'>
					<input type="text" name="cheque_date" value="$cheque_date" id="cheque_dateid" readonly="readonly" />
				</div></div>
			</td>
		</tr>

		<tr class="evenrow">
			<td ><div class="loantext1">Bank Name: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform">
				<div class='form-item'>
        <select name="bank" id="bankid">
		<option value="">Select</option>
EOD;
    $query = "SELECT bank_id, bank_name FROM tbl_bank  WHERE status = 1 ORDER BY bank_name";
    $res = db_query($query);
    while ($row = db_fetch_object($res)) {
        if ($bank == $row->bank_id)
            $selected = 'selected="selected"';
        $output .= '<option value="' . $row->bank_id . '" ' . $selected . '>' . ucwords($row->bank_name) . '</option>';
        $selected = '';
    }
    $output .= <<<EOD
		</select>	
				</div></div>
			</td>
		</tr>
</table>

<table width="100%" cellpadding="2" cellspacing="1" border="0">	
	<tr class="oddrow">
		<td colspan="4" align="center" class="back">
			<input type="hidden" value="$loan_id" name="loan_id">
EOD;
    if ($loan_id)
        $output .= l("Back", 'loan/disbursement/' . $loan_id);
    else
        $output .= l("Back", 'loan/listloans/');
    $output .= <<<EOD
			<input type="submit" class="form-submit" value="Save" id="submit" name="ls"/>
		</td>
	</tr>
</table>
</form>
	</div>
EOD;

    return $output;
}

//Server validation
function validateDisbursementForm() {
    $errorstr = '';
    $scriptcss = '';

    if ($_POST['cheque_number']) {
        if (strlen($_POST['cheque_number']) < 6) {
            $errorstr = 1;
            form_set_error('cheque_number', 'Please enter a valid Cheque number.');
            $scriptcss .= '$("input[name=cheque_number]").addClass("error");';
        }
        if (isValidNumber('Cheque/DD number should be valid number.', 'cheque_number', $_POST['cheque_number'])) {
            $errorstr = 1;
            $scriptcss .= '$("input[name=cheque_number]").addClass("error");';
        }
    } else {
        form_set_error('cheque_number', 'Cheque number should not be blank.');
        $scriptcss .= '$("input[name=cheque_number]").addClass("error");';
    }
    if ($_POST['amount'] != '') {
        if (paypay('amount', $_POST['amount'], 'Amount')) {
            $errorstr = 1;
            $scriptcss .= '$("input[name=amount]").addClass("error");';
        }
    } else {
        form_set_error('amount', 'Amount should not be blank.');
        $scriptcss .= '$("input[name=amount]").addClass("error");';
    }
    if (!$_POST['in_favour_of']) {
        form_set_error('in_favour_of', 'In Favour Of should not be blank.');
        $errorstr = 1;
        $scriptcss .= '$("input[name=in_favour_of]").addClass("error");';
    }
    if (!$_POST['cheque_date']) {
        form_set_error('cheque_date', 'Cheque date should not be blank.');
        $errorstr = 1;
        $scriptcss .= '$("input[name=cheque_date]").addClass("error");';
    }
    if (!$_POST['bank']) {
        form_set_error('bank', 'Bank name should not be blank.');
        $errorstr = 1;
        $scriptcss .= '$("select[name=bank]").addClass("error");';
    }

    $sql = "SELECT o_disburse_amount,payment_order_released_date FROM tbl_loan_detail WHERE loan_id = " . $_POST['loan_id'] . " LIMIT 1";
    $res = db_query($sql);
    $d = db_fetch_object($res);

    if ($d->o_disburse_amount < $_POST['amount']) {
        form_set_error('amount', 'Amount you provided is more than pending amount to be disbursed ie . Rs. ' . $d->o_disburse_amount);
        $errorstr = 1;
        $scriptcss .= '$("input[name=amount]").addClass("error");';
    }
    if ($d->payment_order_released_date > databaseDateFormat($_POST['cheque_date'], 'indian', '-')) {
        form_set_error('cheque_date', 'Cheque date should be higher than or same as Payment order released date.');
        $errorstr = 1;
        $scriptcss .= '$("input[name=cheque_date]").addClass("error");';
    }
    return $scriptcss;
}

?>