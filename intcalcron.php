<?php

//cron for interest calculation
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$sqlg = "select intbatch_id,Branch,Scheme,Account_Number,up_to_date,uid from tbl_intbatch  where batch_status=0";
$resg = db_query($sqlg);
while ($rsg = db_fetch_object($resg)) {
    db_query('START TRANSACTION');
    db_query('BEGIN');
    $account_number = $rsg->Account_Number;
    $to_date = $rsg->up_to_date;
    $corporation = $rsg->Branch;
    $scheme = $rsg->Scheme;
    if ($account_number) {
        $cond = "AND account_id = '" . $account_number . "' AND UNIX_TIMESTAMP(tbl_loan_detail.last_interest_calculated) < '" . $to_date . "' ";
    } else {
        $cond = "AND account_id != '' AND UNIX_TIMESTAMP(tbl_loan_detail.last_interest_calculated) < '" . $to_date . "'";
    }

    $sql = "select tbl_loan_detail.ROI,
                     tbl_loanee_detail.account_id,
                     tbl_loanee_detail.loanee_id,
                     tbl_loan_detail.project_cost,
                     tbl_loan_detail.o_principal,
                     tbl_loan_detail.o_interest,
                     tbl_loan_detail.o_LD,
                     tbl_loan_detail.o_other_charges,
                     tbl_loan_detail.last_interest_calculated,
                     tbl_loanee_detail.reg_number 
             from tbl_loan_detail 
		     INNER JOIN tbl_loanee_detail ON (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number) 
		     where tbl_loanee_detail.corp_branch='" . $corporation . "' AND tbl_loan_detail.scheme_name='" . $scheme . "' $cond";

    $res = db_query($sql);
    $error = 0;
    $counter = 0;
    while ($rs = db_fetch_object($res)) {
        $counter++;
        $counter++;
        $sqld = "select sum(amount) as dsum from tbl_loan_disbursement where loanee_id = '" . $rs->loanee_id . "'";
        $resd = db_query($sqld);
        $rsd = db_fetch_object($resd);

        $sqli = "select calculation_date,o_principle from tbl_loan_interestld 
			         where account_id = '" . $rs->account_id . "' AND type = 'interest'
					 order by calculation_date DESC limit 1
					 ";
        $resi = db_query($sqli);
        $rsi = db_fetch_object($resi);

       
        
        /*
        if (isset($rsi->o_principle)) {
            $interest_value = interest_calculation($rs->account_id, $rsi->o_principle, $rs->last_interest_calculated, date('Y-m-d', $to_date), $rs->ROI);
        } else {
            $interest_value = interest_calculation($rs->account_id, $rs->o_principal, $rs->last_interest_calculated, date('Y-m-d', $to_date), $rs->ROI);
        }*/
        
        if (isset($rsi->o_principle)) {
               // echo 'Regular Time = '.$rs->account_id.' / '.$rsi->o_principle.' / '.$rs->last_interest_calculated.' / '.$rs->ROI; 
                $interest_value = interest_calculation_regular($rs->account_id, $rsi->o_principle, $rs->last_interest_calculated, date('Y-m-d',$to_date), $rs->ROI);

                //  echo $interest_value; exit;
            } else {
                // Interest must be calculated on total term loan value.
                $sqlp = "select amount
			            from tbl_loan_repayment 
					    where loanee_id = '" . $rs->loanee_id . "' AND paytype = 'Promoter Share'";
                $resp = db_query($sqlp);
                $rsp = db_fetch_object($resp);

                $o_principal = $rs->project_cost - $rsp->amount;
               // echo $rs->account_id.' / '.$o_principal.' / '.$rs->last_interest_calculated.' / '.$rs->ROI.' / '.date('Y-m-d',$to_date);
                $interest_value = interest_calculation_first($rs->account_id, $o_principal, $rs->last_interest_calculated, date('Y-m-d',$to_date), $rs->ROI);
            }


        echo 'Interest Value = ' . $interest_value . '<br>';
        $last_interest_calculated = $rsg->up_to_date;
        $batch_id = $rsg->intbatch_id;
        //echo $reg_number = $rs->reg_number;

        $from_date = $rs->last_interest_calculated;
        $to_date = date("Y-m-d", $rsg->up_to_date);
        $calculation_date = $to_date;
        $amount = $interest_value;
        $type = 'interest';
        $account_id = $rs->account_id;
        $reg_number = $rs->reg_number;
        $last_interest_calculated = date('Y-m-d', $rsg->up_to_date);
        $final_principal = $rs->o_principal + $interest_value;

        if (!db_query("UPDATE {tbl_loan_detail} SET  o_principal='" . $final_principal . "',last_interest_calculated='" . $last_interest_calculated . "'  WHERE reg_number='" . $reg_number . "'")) {
            $error = 1;
        }
        if (!db_query("INSERT INTO {tbl_loan_interestld}
		                          (account_id,type,amount,from_date,to_date,calculation_date,o_principle,reason,intbatch_id) 
		                  VALUES ('" . $account_id . "','" . $type . "','" . $amount . "','" . $from_date . "','" . $to_date . "','" . $calculation_date . "',
						          '" . $final_principal . "','212','" . $batch_id . "') ")) {
            $error = 1;
        }
    }
    
    if ($error == 1) {
        db_query('ROLLBACK');
    } else {
        $cuser = user_load($rsg->uid);
        db_query('COMMIT');
        db_query("update tbl_intbatch set batch_status=1 where intbatch_id='" . $rsg->intbatch_id . "'");
        voucherentry($rsg->intbatch_id, $accountid = '', 'interest', $finalint, $GLcode = '', $bank = '', 1);
        //sending mail here with batch id for this uid
        $parameter = json_encode(array(0 => $cuser->name, 1 => $rsg->intbatch_id));
        createMail('interestcalculation', $cuser->mail, '', $parameter, '');
    }
}

function interest_calculation_regular($loanid, $odbalance, $last_int_cal, $new_int_cal, $roi) {
    $quarter_start_date = $last_int_cal;
    $quarter_end_date = $new_int_cal;
    $quarter_time = abs(strtotime($quarter_end_date) - strtotime($quarter_start_date));
    $quarter_days = round(($quarter_time / (60 * 60 * 24)));
    //echo $quarter_days; exit;
    $flag = db_query("SELECT * FROM tbl_loan_interestld 
	                    WHERE account_id =  '" . $loanid . "' 
						AND calculation_date = '" . $quarter_end_date . "'");
    $counter = 0;
    while ($res = db_fetch_object($flag)) {
        $counter++;
    }
    if ($counter == 0) {
        $loanee = db_query("SELECT * FROM tbl_loanee_detail 
		                       WHERE account_id =  '" . $loanid . "'");
        $res = db_fetch_object($loanee);
        $loaneeid = $res->loanee_id;
        //echo $loaneeid; exit;
        $interest_value = 0;

        $repayments = db_query("SELECT payment_date,amount FROM tbl_loan_repayment 
		                           WHERE loanee_id =  '" . $loaneeid . "' AND paytype = 'EMI' 
								   AND (payment_date BETWEEN '" . $quarter_start_date . "' AND '" . $quarter_end_date . "')");
        $repay_count = 0;
        while ($res1 = db_fetch_object($repayments)) {
            $repay_count++;
        }
        //-------------------------------In Case of Loanee did not pay installment in selected quarter--------------------------
        if ($repay_count == 0) {
            $interest_value = round(($odbalance * $quarter_days * $roi) / 36500);
        }
        //-------------------------------In Case when Loanee paid installment in selected quarter--------------------------
        else {
            $tdays = 0;
            $start_date = $quarter_start_date;
            $first_repayment = true;
            $total_repayment_amount = 0;
            $total_repayments = db_query("SELECT payment_date,amount FROM tbl_loan_repayment 
		                                    WHERE loanee_id =  '" . $loaneeid . "' AND paytype = 'EMI' 
								            AND (payment_date BETWEEN '" . $quarter_start_date . "' AND '" . $quarter_end_date . "')");

            while ($row = db_fetch_object($total_repayments)) {
                if ($first_repayment && ($row->payment_date != $quarter_start_date)) {
                    $time = abs(strtotime($row->payment_date) - strtotime($start_date));
                    $days = round(($time / (60 * 60 * 24))) - 1;
                    //echo 'No of days = '.$days; exit;
                    $interest_value += round(($odbalance * $days * $roi) / 36500);
                    $odbalance = $odbalance - $row->amount;
                    $tdays += $days;
                    $total_repayment_amount += $row->amount;
                    $start_date = $row->payment_date;
                    $first_repayment = false;
                } else if ($row->payment_date != $quarter_start_date) {
                    $time = abs(strtotime($row->payment_date) - strtotime($start_date));
                    $days = round(($time / (60 * 60 * 24)));
                    $interest_value += round(($odbalance * $days * $roi) / 36500);
                    $odbalance = $odbalance - $row->amount;
                    $tdays += $days;
                    $total_repayment_amount += $row->amount;
                    $start_date = $row->payment_date;
                }
            }

            $rdays = $quarter_days - $tdays;
            $interest_value += round(($odbalance * $rdays * $roi) / 36500);
        }
    } else {
        echo 'INTEREST ON ACCOUNT ID. = ' . $loanid . ' HAS ALREADY BEEN CALCULATED FOR QUARTER  = ' . $quarter_end_date . '<br>';
    }
    return $interest_value;
    ;
}

//Business Logic for interest calculation first..
function interest_calculation_first($loanid, $odbalance, $last_int_cal, $new_int_cal, $roi) {
    $quarter_start_date = $last_int_cal;
    $quarter_end_date = $new_int_cal;
    $quarter_time = abs(strtotime($quarter_end_date) - strtotime($quarter_start_date));
    $quarter_days = round(($quarter_time / (60 * 60 * 24))) + 1;
    //echo $quarter_days; exit;
    $flag = db_query("SELECT * FROM tbl_loan_interestld 
	                    WHERE account_id =  '" . $loanid . "' 
						AND calculation_date = '" . $quarter_end_date . "'");
    $counter = 0;
    while ($res = db_fetch_object($flag)) {
        $counter++;
    }
    if ($counter == 0) {
        $loanee = db_query("SELECT * FROM tbl_loanee_detail 
		                       WHERE account_id =  '" . $loanid . "'");
        $res = db_fetch_object($loanee);
        $loaneeid = $res->loanee_id;
        //echo $loaneeid; exit;
        $interest_value = 0;

        $repayments = db_query("SELECT payment_date,amount FROM tbl_loan_repayment 
		                           WHERE loanee_id =  '" . $loaneeid . "' AND paytype = 'EMI' 
								   AND (payment_date BETWEEN '" . $quarter_start_date . "' AND '" . $quarter_end_date . "')");
        $repay_count = 0;
        while ($res1 = db_fetch_object($repayments)) {
            $repay_count++;
        }
        //-------------------------------In Case of Loanee did not pay installment in selected quarter--------------------------
        if ($repay_count == 0) {
            $interest_value = round(($odbalance * $quarter_days * $roi) / 36500);
        }
        //-------------------------------In Case when Loanee paid installment in selected quarter--------------------------
        else {
            $tdays = 0;
            $start_date = $quarter_start_date;
            $first_repayment = true;
            $total_repayment_amount = 0;
            $total_repayments = db_query("SELECT payment_date,amount FROM tbl_loan_repayment 
		                                    WHERE loanee_id =  '" . $loaneeid . "' AND paytype = 'EMI' 
								            AND (payment_date BETWEEN '" . $quarter_start_date . "' AND '" . $quarter_end_date . "')");

            while ($row = db_fetch_object($total_repayments)) {
                if ($first_repayment && ($row->payment_date != $quarter_start_date)) {
                    $time = abs(strtotime($row->payment_date) - strtotime($start_date));
                    $days = round(($time / (60 * 60 * 24)));
                    //echo 'No of days = '.$days; exit;
                    $interest_value += round(($odbalance * $days * $roi) / 36500);
                    $odbalance = $odbalance - $row->amount;
                    $tdays += $days;
                    $total_repayment_amount += $row->amount;
                    $start_date = $row->payment_date;
                    $first_repayment = false;
                } else if ($row->payment_date != $quarter_start_date) {
                    $time = abs(strtotime($row->payment_date) - strtotime($start_date));
                    $days = round(($time / (60 * 60 * 24)));
                    $interest_value += round(($odbalance * $days * $roi) / 36500);
                    $odbalance = $odbalance - $row->amount;
                    $tdays += $days;
                    $total_repayment_amount += $row->amount;
                    $start_date = $row->payment_date;
                }
            }

            $rdays = $quarter_days - $tdays;
            $interest_value += round(($odbalance * $rdays * $roi) / 36500);
        }
    } else {
        echo 'INTEREST ON ACCOUNT ID. = ' . $loanid . ' HAS ALREADY BEEN CALCULATED FOR QUARTER  = ' . $quarter_end_date . '<br>';
    }
    return $interest_value;
    ;
}

?>
