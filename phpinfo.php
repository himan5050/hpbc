<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


$file = fopen("csv/sms_reminder.csv","r");
while(! feof($file)){
    $row = fgetcsv($file);
    if(isset($row[0])){
        $loan['account_id'] = $row[0];
        $loan['loan_id'] = substr($row[0],2);
        $loans[] = $loan;
    }
}

foreach ($loans as $loan){
    $result = db_query("SELECT o_principle FROM `tbl_loan_interestld` WHERE account_id = '".$loan['account_id']."' ORDER BY calculation_date DESC LIMIT 1");
    $res = db_fetch_object($result);

    if (db_query("UPDATE {tbl_loan_detail} SET  o_principal='" . $res->o_principle . "' WHERE loan_id='".$loan['loan_id']."'")) {
        echo $loan['account_id'].'<BR>';
        print_r('done');
    }
}
