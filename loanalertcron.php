<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
	$dueday = getMessage( 'loans', 'dueday', NULL);
	$currentdate = date("Y-m-d");
	$sql = "select * from 
(
SELECT l.account_id,l.email,l.fname,l.lname,l.reg_number,ld.loan_disburse_date,ld.emi_amount,
period_diff(DATE_FORMAT(curdate(), '%Y%m'),DATE_FORMAT(ld.loan_disburse_date, '%Y%m')) as period,
period_diff(DATE_FORMAT(curdate(), '%Y%m'),DATE_FORMAT(ld.loan_disburse_date, '%Y%m')) 
 * ld.emi_amount as TotalPayment 	
FROM tbl_loan_detail ld,
tbl_loanee_detail l 
WHERE ld.reg_number = l.reg_number AND ld.loan_disburse_date != '0000-00-0'
) a
left outer join 
(
SELECT am.loanacc_id,sum(installment_paid) as totalPaid
FROM tbl_loan_amortisaton am
group by am.loanacc_id
) b
on a.account_id = b.loanacc_id
where a.TotalPayment > b.totalPaid";
$res = db_query($sql);
while($r = db_fetch_object($res))
{
	if(date("d") > $dueday)
	{
		if(date("m") == 12)
			$duedate = date("d-m-Y",mktime(0 ,0 ,0,date("m") + 1,$dueday,date("Y") + 1));
		else
			$duedate = date("d-m-Y",mktime(0 ,0 ,0,date("m") + 1,$dueday,date("Y")));
	}else{
		$duedate = $dueday.'-'.date("m").'-'.date("Y");
	}
	if($r->email)
	{
		$parameter = '';
		$to = $r->email;
		$name =  ucwords($r->fname.' '.$r->lname);
		$accountno = $r->account_id;
		$emi =  $r->emi_amount;
		$parameter = json_encode(array("$name","$duedate","$name","$accountno","$duedate","$emi"));
		createMail('installment_alert',$to,'',$parameter);
	}
}

?>