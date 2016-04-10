<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET['q']; 
 $sql= "SELECT tbl_loan_detail.loan_requirement,tbl_loan_detail.reg_number,  
tbl_loan_detail.bank_acc_no,tbl_loanee_detail.fname,tbl_loanee_detail.lname,tbl_loan_detail.disbursed_date 	

	FROM tbl_loanee_detail 
	INNER JOIN tbl_loan_detail ON (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number) 
   	
	 WHERE tbl_loan_detail.reg_number = '".$q."'";
$res= db_query($sql);
$rs=db_fetch_object($res);

echo $output = $rs->fname.'|'.$rs->bank_acc_no.'|'.$rs->loan_requirement.'|'.date("d-m-Y",strtotime($rs->disbursed_date));
?>




 