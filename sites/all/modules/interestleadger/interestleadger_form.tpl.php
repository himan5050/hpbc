<style>
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}

input[type="text"] {
width: 100px;
height: 18px;
margin: 0;
padding: 2px;
vertical-align: middle;
font-family: sans-serif;
font-size: 14px;
border: #BCBCBC 1px solid;
}
.maincoldate{margin-top:12px;}
.maincoldate label{float:left; margin:5px 10px 0 0;}
#edit-schemename-wrapper label{float:left; margin:5px 10px 0 0;}
</style>

  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Interest Ledger Report</legend>
    
    <table align="left" class="frmtbl"><tr><td><div class="maincol"><?php print drupal_render($form['schemename']); ?></div></td><td><div class="maincol maincoldate"><?php print drupal_render($form['startdate']); ?></div></td><td><div class="maincol maincoldate"><?php print drupal_render($form['enddate']); ?></div></td></tr><tr><td colspan="3" align="right"><div style="margin-right:113px;"><?php print drupal_render($form); ?></div></td></tr>    
    
	</table>
	</fieldset></td></tr>
  </table>

<?php
$op=$_REQUEST['op'];
if($op == 'Generate'){

if(($_REQUEST['startdate']['date'])!='' && ($_REQUEST['enddate']['date'])!='')
{
/*
SELECT SUM( tbl_loan_detail.loan_requirement ) AS tsac, SUM( tbl_loan_detail.capital_subsidy ) AS capital_subsidy, SUM( tbl_loan_amortisaton.interest_paid ) AS interest_paid, tbl_scheme_master.scheme_name AS scheme_name
FROM tbl_loan_detail
LEFT OUTER JOIN tbl_loanee_detail ON ( tbl_loanee_detail.reg_number = tbl_loan_detail.reg_number ) 
LEFT OUTER JOIN tbl_loan_amortisaton ON ( tbl_loan_amortisaton.loanacc_id = tbl_loanee_detail.account_id ) 
LEFT OUTER JOIN tbl_scheme_master ON ( tbl_scheme_master.loan_scheme_id = tbl_loan_detail.scheme_name ) 
GROUP BY tbl_loan_detail.scheme_name
*/

global $base_url;

$cond='and 1=1';
//tbl_loan_detail sanction_date	
 $stdate= strtotime($_REQUEST['startdate']['date']);
$endate=  strtotime($_REQUEST['enddate']['date']); 
$std=explode('-',$_REQUEST['startdate']['date']);
$startdate=$std[2].'-'.$std[1].'-'.$std[0];
$entd=explode('-',$_REQUEST['enddate']['date']);
$enddate=$entd[2].'-'.$entd[1].'-'.$entd[0];
if($_REQUEST['schemename'] != 'all')
{
  $cond ='and tbl_loan_detail.scheme_name="'.$_REQUEST['schemename'].'" GROUP BY tbl_loan_detail.scheme_name';
}else{
  $cond .= ' GROUP BY tbl_loan_detail.scheme_name';
}
//tbl_loan_detail.scheme_name='".$_REQUEST['schemename']."'
 $sqlc = "SELECT SUM( tbl_loan_detail.loan_requirement ) AS tsac, SUM( tbl_loan_detail.capital_subsidy ) AS capital_subsidy, SUM( tbl_loan_amortisaton.interest_paid ) AS interest_paid, SUM( tbl_fdr.amount ) AS fdramount, tbl_scheme_master.scheme_name AS scheme_name
FROM tbl_loan_detail
LEFT OUTER JOIN tbl_loanee_detail ON ( tbl_loanee_detail.reg_number = tbl_loan_detail.reg_number ) 
LEFT OUTER JOIN tbl_loan_amortisaton ON ( tbl_loan_amortisaton.loanacc_id = tbl_loanee_detail.account_id ) 
LEFT OUTER JOIN tbl_scheme_master ON ( tbl_scheme_master.loan_scheme_id = tbl_loan_detail.scheme_name ) 
LEFT OUTER JOIN tbl_fdr ON (tbl_fdr.account_no = tbl_loanee_detail.account_id )
WHERE UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$stdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$endate."' $cond ";

/*$cond='';
if($_REQUEST['schemename']!='all')
{
  $cond .='and loan_scheme_id="'.$_REQUEST['schemename'].'"';
}

$std=explode('-',$_REQUEST['startdate']['date']);
$startdate=$std[2].'-'.$std[1].'-'.$std[0];
$entd=explode('-',$_REQUEST['enddate']['date']);
$enddate=$entd[2].'-'.$entd[1].'-'.$entd[0];*/
$output='<table>
		  <tr class="oddrow"><td colspan="7"><h2 style="text-align:left;">Interest Leadger Report</h2></td></tr>
		   <tr><td colspan="7" align="right"><a href="'.$base_url.'/generatedefaulterpdf.php?op=interestleadger&stdate='.$stdate.'&endate='.$endate.'&schemename='.$_REQUEST['schemename'].'" target="_blank"><img src="account/images/pdf_icon.gif" style="float:right;"/></a></td></tr>
		  <tr><td colspan="7" align="right"></td></tr>
<tr><th><b>Scheme name</b></th>
<th><b>Total Loan Sanctioned</b></th>
<th><b>Total interest Received</b></th>
<th><b>Total Capital Subsidy</b></th>
<th><b>Total Interest Subsidy</b></th>
<th><b>Total MMD amount</b></th>
<th><b>Total FDR amount</b></th>
</tr>';
/*$use="select * from tbl_scheme_master where 1=1 ".$cond."";
$usq = db_query($use);
 while($usre = db_fetch_array($usq))
 {
   $la="select * from tbl_loan_detail where scheme_name='".$usre['loan_scheme_id']."'";
   $laq=db_query($la);
   $totlone=0;
   $capital_sub=0;
   $int_received=0;
	   while($lar=db_fetch_array($laq))
	   {
	     $totlone=$totlone+$lar['loan_requirement'];
		 $capital_sub=$capital_sub+$lar['capital_subsidy'];
		 
		  $int="select tbl_loan_amortisaton.interest_paid as loan_paid,tbl_loan_amortisaton.payment_date from tbl_loan_amortisaton,tbl_loanee_detail,tbl_loan_detail where tbl_loan_amortisaton.loanacc_id=tbl_loanee_detail.account_id and tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number and tbl_loan_detail.loan_id='".$lar['loan_id']."' ";
		 $intq=db_query($int);
		 while($intr=db_fetch_array($intq))
		 { 
		    if($intr['payment_date']>=$startdate || $intr['payment_date']<=$startdate){
		  $intr['payment_date'];
		 }
		   $int_received=$int_received+$intr['loan_paid'];
		 }
	   }
	   $output .='<tr><td>'.$usre['scheme_name'].'</td><td>'.$totlone.'</td><td>'.$int_received.'</td><td>'.$capital_sub.'</td><td></td><td></td><td></td></tr>'; 
	   
 }*/
 $sqlcq=db_query($sqlc);
 $l=1;
 while($sqlcr=db_fetch_array($sqlcq))
 {  
    if($l%2==0)
       {
	     $cla="even";
	   }
	   else
	   {
	    $cla="odd";
	   }
    $output .='<tr class="'.$cla.'"><td>'.ucwords($sqlcr['scheme_name']).'</td><td align="right">'.$sqlcr['tsac'].'</td><td align="right">'.$sqlcr['interest_paid'].'</td><td align="right">'.$sqlcr['capital_subsidy'].'</td><td align="right"></td><td align="right"></td><td align="right">'.number_format($sqlcr['fdramount'],2).'</td></tr>'; 
	$l++;
 }
 
 $output .='</table>';
 }
 
 echo $output;
 
 
}

?>