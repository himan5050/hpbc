<?php
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$q=$_GET["q"];
$sql="SELECT fname,district,o_principal,o_interest,o_LD,o_other_charges FROM tbl_loanee_detail,tbl_loan_detail WHERE account_id = '".$q."' and tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number";
$res= db_query($sql);
$rs=db_fetch_object($res);
$namee=$rs->fname;
$district=$rs->district;
$total_amount=$rs->o_principal+$rs->o_interest+$rs->o_LD+$rs->o_other_charges;
echo $output = $rs->fname.'|'.$district.'|'.$total_amount;
?>