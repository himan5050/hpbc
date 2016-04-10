<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/pdfcss.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, B4, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SC and ST');
$pdf->SetTitle('SC and ST');
$pdf->SetSubject('SC and ST');
$pdf->SetKeywords('SC and ST');

$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('helvetica', '', 10);
// add a page
$pdf->AddPage();


if($_REQUEST['op'] == 'LoaneeDetailsReport'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];
$acc_no = $_REQUEST['acc_no'];


$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:956px;
}
table.tbl_border{border:1px solid #a7c942;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

// Header Title

 
$output .='<table cellpadding="0" cellspacing="0" border="0" style="width:1175px;">
<tr><td class="header_report" colspan="5" align="center">
Loanee Detail</td></tr>
<!--<tr><td colspan="0" class="header1" align="right">Status as on '.date("d-m-Y").'</td></tr>-->

</table><br/>';
	

 if($_REQUEST['op'] == 'LoaneeDetailsReport'){

$acc=$_REQUEST['acc_no'];
if( $acc == ''){
  form_set_error('form','Please enter the search field.');

}
else {
	
	
  $sql = "SELECT  tbl_loan_detail.work_place,tbl_loan_detail.loan_requirement,tbl_loan_detail.reg_number,  
tbl_loanee_detail.account_id,	tbl_loanee_detail.fname,tbl_loanee_detail.lname,tbl_loanee_detail.fh_name,tbl_loanee_detail.address1,tbl_loanee_detail.address2,	tbl_district.district_name,tbl_tehsil.tehsil_name,tbl_loan_disbursement.cheque_date,

 tbl_scheme_master.scheme_name,tbl_guarantor_detail.gname,tbl_guarantor_detail.address

	 FROM tbl_loanee_detail 
	 INNER JOIN tbl_loan_detail ON (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number) 
   	INNER JOIN tbl_scheme_master  ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
	INNER JOIN tbl_loan_disbursement ON  (tbl_loanee_detail.loanee_id=tbl_loan_disbursement .loanee_id) 
	INNER JOIN tbl_guarantor_detail ON  (tbl_loanee_detail.loanee_id=tbl_guarantor_detail.loanee_id) 
	INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id) 
	INNER JOIN tbl_tehsil  ON  (tbl_loanee_detail.tehsil=tbl_tehsil.tehsil_id) 
 
	 
	 
	 
	 where tbl_loanee_detail.account_id like '%".$acc."%' ";
	 
	 
	 	
  }




$output .='<table cellpadding="3" cellspacing="2" border="0" class="tbl_border"><tr>
<td width="5%" colspan="1" class="header2">S.No.</td>
<td width="12%" colspan="1"  class="header2">Account No.</td>
<td width="12%" colspan="1"  class="header2">Loanee Name</td>
<td width="12%" colspan="1" class="header2">Guardian Name</td>
<td width="12%" colspan="1"  class="header2">Address</td>
<td width="8%" colspan="1"  class="header2">District</td>
<td width="8%" colspan="1"  class="header2">Tehsil</td>
<td width="8%" colspan="1"  class="header2">Schemes</td>
<td width="8%" colspan="1"  class="header2">Loan Amount</td>
<td width="9%" colspan="1"  class="header2">Disbursement Date</td>
<td width="12%" colspan="1"  class="header2">Gurantor Name</td>
<td width="10%" colspan="1"  class="header2">Gurantor Address</td>
<td width="8%" colspan="1"  class="header2">Business Place</td>

</tr>';





 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
  
   if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}
				$output .='<tr>
				<td width="5%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="12%" class="'.$class.'" align="left">'.$rs->account_id.'</td>
				<td width="12%" class="'.$class.'" align="left">'.ucwords($rs->fname).' '.ucwords($rs->lname).'</td>
				<td width="12%" class="'.$class.'" align="left">'.ucwords($rs->fh_name).'</td>
				<td width="12%" class="'.$class.'" align="left">'.ucwords($rs->address1.$rs->address2).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->district_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->tehsil_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->scheme_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->loan_requirement).'</td>
				<td width="9%" class="'.$class.'" align="left">'.date('d-m-Y',strtotime($rs->cheque_date)).'</td>
				<td width="12%" class="'.$class.'" align="left">'.ucwords($rs->gname).'</td>
				<td width="10%" class="'.$class.'" align="left">'.ucwords($rs->address).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->work_place).'</td>
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	ob_end_clean();
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('LoaneeDetailsReport_'.time().'.pdf', 'I');
}

}