<style type="text/css">
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
.maincoldate{margin-top:30px;}
</style>
<div id="rec_participant">
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
   <tr><td align="left" class="tdform-width"><fieldset><legend>Margin Money Loan Report </legend>
   <table align="left" class="frmtbl">
	<tr><td width="5%">&nbsp;</td><td><b>District:</b></td><td><div class="maincol"><?php print drupal_render($form['district_id']); ?></div></td><td><b>From Date:</b></td><td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td><td><b>To Date:</b></td><td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td><td width="5%">&nbsp;</td></tr><tr><td colspan="8"><div style="margin-right:110px; text-align:right;"><?php print drupal_render($form); ?></div></td></tr>
  </table>
  	</fieldset></td></tr>
  </table></div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($_REQUEST['page'] == 0){
  $_GET['page'] =0;
}
//$dest = $_REQUEST['destination'];	
//echo $_GET['page'];echo $_REQUEST['page'];
if($op == 'Generate Report'){
	

 $from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
  
 
 	
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['district_id'] == '' ){
  form_set_error('form','Please enter any one of the search fields.');
  $_REQUEST['page']=0;	
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  form_set_error('form','Please enter To Date');
  $_REQUEST['page']=0;	
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  form_set_error('form','Please enter From Date');
  $_REQUEST['page']=0;	
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] != '' &&($_REQUEST['to_date']['date'] < $_REQUEST['from_date']['date'])){

  form_set_error('form','To Date should be greater than the From Date');


}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
	$district_id=$_REQUEST['district_id'];

$append="";


	


 if($fromtime){
	 if($fromtime !='1970-01-01' && $totime !='1970-01-01' ){
//$_REQUEST['page']=0;
	$append .= " fdr_date BETWEEN '".$fromtime."' AND '".$totime."' AND ";
	

	$pdfurl = $base_url."/mmloanReportpdf.php?op=mmloanReport_report&fromtime=$fromtime&totime=$totime&district_id=$district_id";
	 }}
  $append .= " 1=1 ";
  
  
  $sql="select * from tbl_fdr where $append";
  
 
	
  
  
  
  
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);


   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";



////////////
$ieee=1;

if($district_id){

$check="select loanee_id from tbl_loanee_detail where district='".$district_id."'";
$check1=db_query($check);
$chk=db_fetch_object($check1);

if($chk->loanee_id==''){$ieee=2; echo '<font color="red"><b>No Record found...</b></font>';}
}
if($ieee==1){
  $pdfurl = $base_url."/mmloanReportpdf.php?op=mmloanReport_report&fromtime=$fromtime&totime=$totime&district_id=$district_id";
	$output = '<div class="listingpage_scrolltable"><table class="listingpage_scrolltable" >
	  <tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">Margin Money Loan Report</h2></td></tr>
	<tr>
	<td colspan="7">
<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" style="border-collapse: separate !important;">';
  $output .='<tr>
   				<th align="center">S. No.</th>
				<th>Scheme Name</th>
				<th>Account No.</th>
				<th>Loanee Name</th>
				<th>Disburse Amount</th>
				<th>Bank Name</th>
				<th>Principal Amount</th>
				
				</tr>';}
		//$_REQUEST['page']=0;		
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*10;
	}else{
	  $counter = 0;
	}
	$i=1;
   while($rs = db_fetch_object($res)){
      $counter++;

	  $bank=db_query("select bank_name from tbl_bank where tbl_bank.bank_id='".$rs->bank_name."'");

//$bank=db_query("select tbl_bank.bank_name from tbl_bank,tbl_fdr where tbl_fdr.bank_name=tbl_bank.bank_id");
$bank_name=db_fetch_object($bank);


$loanee_id=$rs->account_no;

/*if($district_id){

$check="select loanee_id from tbl_loanee_detail where district='".$district_id."'";
$check1=db_query($check);
$chk=db_fetch_object($check1);

if($chk->loanee_id==''){break;}else{

$reg=db_query("select led.reg_number ,led.lname,led.fname,sm.scheme_name as scheme1 ,ld.scheme_name as schemeid,ld.loan_requirement,ld.o_disburse_amount from tbl_loanee_detail led,tbl_loan_detail ld,tbl_scheme_master sm
WHERE ld.reg_number=led.reg_number AND sm.loan_scheme_id=ld.scheme_name AND led.loanee_id='".$loanee_id."' AND led.district='".$district_id."'");

//echo"select led.reg_number ,led.lname,led.fname,sm.scheme_name as scheme1 ,ld.scheme_name as schemeid,ld.loan_requirement,ld.o_disburse_amount from tbl_loanee_detail led,tbl_loan_detail ld,tbl_scheme_master sm
//WHERE ld.reg_number=led.reg_number AND sm.loan_scheme_id=ld.scheme_name AND led.loanee_id='".$loanee_id."' AND //led.district='".$district_id."'"; exit;

$reg_no=db_fetch_object($reg);
}
}else{


$reg=db_query("select led.reg_number ,led.lname,led.fname,sm.scheme_name as scheme1 ,ld.scheme_name as schemeid,ld.loan_requirement,ld.o_disburse_amount from tbl_loanee_detail led,tbl_loan_detail ld,tbl_scheme_master sm
WHERE ld.reg_number=led.reg_number AND sm.loan_scheme_id=ld.scheme_name AND led.loanee_id='".$loanee_id."'");
$reg_no=db_fetch_object($reg);

}
/*
$scheme_id=db_query("select scheme_name,loan_requirement,o_disburse_amount from tbl_loan_detail where reg_number='".$reg_no->reg_number."'");
$loan_scheme_id=db_fetch_object($scheme_id);

$scheme_nam=db_query("select scheme_name from tbl_scheme_master where loan_scheme_id='".$loan_scheme_id->scheme_name."'");
$scheme_name=db_fetch_object($scheme_nam);

*//*
$bank=db_query("select tbl_bank.bank_name from tbl_bank,tbl_fdr where tbl_fdr.bank_name=tbl_bank.bank_id");
$bank_name=db_fetch_object($bank);

$acc=getAccountNo($rs->account_no); if($acc){}else{$acc='N/A';}
$disburse=$reg_no->loan_requirement - $reg_no->o_disburse_amount;*/


 $loanee_id=$rs->account_no;
if($district_id){
$reg=db_query("select reg_number,lname,fname from tbl_loanee_detail where loanee_id='".$loanee_id."' and district='".$district_id."'");
$reg_no=db_fetch_object($reg);

}else{


$reg=db_query("select reg_number,lname,fname from tbl_loanee_detail where loanee_id='".$loanee_id."'");
$reg_no=db_fetch_object($reg);

}

$scheme_id=db_query("select scheme_name,loan_requirement,o_disburse_amount,disbursed_amount,bank_acc_no,bank from tbl_loan_detail where reg_number='".$reg_no->reg_number."'");
$loan_scheme_id=db_fetch_object($scheme_id);
if($loan_scheme_id->bank==0 || $loan_scheme_id->bank==''){continue;}
$scheme_nam=db_query("select scheme_name from tbl_scheme_master where loan_scheme_id='".$loan_scheme_id->scheme_name."'");
$scheme_name=db_fetch_object($scheme_nam);
$disbursed_amnt=$loan_scheme_id->disbursed_amount;



$acc=$loan_scheme_id->bank_acc_no; if($acc){}else{$acc='N/A';}
$disburse=$loan_scheme_id->loan_requirement - $loan_scheme_id->o_disburse_amount;


	if ($i%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="6%">'.$i.'</td>
					   <td>'.ucwords($scheme_name->scheme_name).'</td>
					    <td align="right">'.$acc.'</td>
					    <td>'.ucwords($reg_no->fname).' '.ucwords($reg_no->lname).'</td>
						 <td align="right">'.round(abs($disbursed_amnt)).'</td>
					<td >'.ucwords($bank_name->bank_name).'</td>
					   <td align="right">'.round(abs($rs->amount)).'</td>
	            </tr>';
   $i++;
   }

  if($counter > 0){
  
   $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
  }else{
   echo '<font color="red"><b>No Record found...</b></font>';
  }
}
}
?>