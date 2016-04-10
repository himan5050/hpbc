<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$Link = mysql_connect('localhost','root','root');
   if ($Link) {
    mysql_select_db('hpbctest', $Link); 
   }
  
   
/*   
$counter = 0;
$sqlc = "SELECT loan_id FROM `hpbctest`.`tbl_loan_detail`";
$sqlcc = mysql_query($sqlc);
while($res = mysql_fetch_object($sqlcc)){
    
    $counter = $counter + 1;
    $loan_id = $res->loan_id;
    $sql = "SELECT * FROM `hpbctest`.`tbl_loan_detail` WHERE loan_id = '".$loan_id."'";
    $sqlr = mysql_query($sql);
    //echo $sqlr;
    $sqlres = mysql_fetch_object($sqlr);
    $projectcost = $sqlres->project_cost;
    //echo 'The Project Cost is = '.$projectcost;
    $pshare = ($projectcost*5)/100;
    $total_term_loan = $projectcost-$pshare;
    //echo 'Total Term Loan = '.$total_term_loan;

    $sql = "SELECT * FROM `hpbctest`.`tbl_scheme_master` WHERE loan_scheme_id = '".$sqlres->scheme_name."'";
    $sqlr2 = mysql_query($sql);
    //echo $sqlr;
    $sqlres2 = mysql_fetch_object($sqlr2);
    $tenure = $sqlres2->tenure;
    //echo $sqlres2->tenure;

    $roi = $sqlres->ROI;
    //echo $roi;
    $emi_val = emi_calculation($total_term_loan, $roi, $tenure);
    //echo 'Calculated EMI Value = '.$emi_val;

    $sql = "UPDATE tbl_loan_detail SET emi_amount= '".$emi_val."' WHERE loan_id = $loan_id";
    $updqrr = mysql_query($sql);
    if(!$updqrr){
        echo 'UPDATE QUERY NOT EXECUTED ('.$counter.') <br>';
    }else{
        echo $counter.': EMI Value of Loan ID = '.$loan_id.' is = '.$emi_val.'<br>';
    }
}

function emi_calculation($total_term_loan,$roi,$tenure_period){
    $no_of_quaters = ($tenure_period/12)*4;
    //echo 'Total Number of Quarters in '.$tenure_period.' months = '.$no_of_quaters.'<br>';
    
    $principal_value = round($total_term_loan/$no_of_quaters);
    
    $interest_paid_value = round(($principal_value)*(($no_of_quaters + 1)/(2*$no_of_quaters))*($roi/$no_of_quaters));
    //echo 'Interest to Paid = '.$interest_paid_value.'<br>';
    
    $quaterly_paid = round($principal_value + $interest_paid_value);
    //echo 'Total Quaterly Paid amount = '.$quaterly_paid.'<br>';
    
    $emi_value = round($quaterly_paid/3);
    //echo 'Total Emi Value = '.$emi_value.'<br>';
    
    return $emi_value;
    
}

*/