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
?>
