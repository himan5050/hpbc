<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function pre_migration_steps(){
    $counter = 1;
    $file = fopen("csv/notok.csv","r");
    while(! feof($file)){
        $row = fgetcsv($file);
        if(isset($row[0])){
            $not_ok_loan_ids[$counter] = $row[0];
            $counter++;
        }
    }
    
   // echo '<PRE>';
   // print_r($not_ok_loan_ids); exit;
    
    foreach ($not_ok_loan_ids as $loan_id){
        
        $sqlg = "SELECT `account_id`,`loan_id`,`o_principal`,`last_interest_calculated`,`ROI`,`project_cost`,`loanee_id`
              FROM `tbl_loan_detail` AS `tl`
              LEFT JOIN `tbl_loanee_detail` AS `tle`
              ON `tl`.reg_number=`tle`.reg_number WHERE `tl`.`loan_id` = '".$loan_id."'";
        
        $resg = db_query($sqlg);
        while ($rsg = db_fetch_object($resg)) {
            db_query('START TRANSACTION');
            db_query('BEGIN');
            
            $disb_query = db_query("SELECT * FROM `tbl_loan_disbursement` WHERE loanee_id = '".$rsg->loanee_id."' ORDER BY `tbl_loan_disbursement`.`cheque_date` ASC LIMIT 0,1");
            while($rdisb = db_fetch_object($disb_query)){
                $disbursement_date = $rdisb->cheque_date;
                $o_principal_update = db_query("UPDATE `tbl_loan_detail` SET `last_interest_calculated` = '".$disbursement_date."' WHERE loan_id = '".$loan_id."'");
                $delete_int = db_query("DELETE FROM `tbl_loan_interestld` WHERE `account_id` = '".$rsg->account_id."' AND calculation_date > '2014-03-31'");
                if($o_principal_update && $delete_int){
                     echo $loan_id.' Successfully updated with '.$disbursement_date; echo '<BR>';
                }
            }
        }
    }
}