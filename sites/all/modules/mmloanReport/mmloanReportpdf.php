<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A4, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SC and ST');
$pdf->SetTitle('SC and ST');
$pdf->SetSubject('SC and ST');
$pdf->SetKeywords('SC and ST');

$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, 'Nikhil Bhawan, Power House Road Saproon, Solan-173211');
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


if($_REQUEST['op'] == 'mmloanReport_report'){
global $user, $base_url;
$rid = $_REQUEST['rid'];

$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = $fromtime;
$to = $totime;

$output ='';
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
width:980px;
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
 $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
FDR Details Report</td></tr>

<tr><td>&nbsp;</td></tr>
</table>';
	

$append="";
	
	 if($fromtime){
	 if($fromtime !='1970-01-01' && $totime !='1970-01-01' ){
	$append .= " fdr_date BETWEEN '".$fromtime."' AND '".$totime."' AND ";
	
	 }
	 }
  $append .= " 1=1 ";
  
  
  $sql="select * from tbl_fdr where $append";



 $output .='<table cellpadding="3" cellspacing="2" border="0" >';


 if($fromtime){

 
$output .='<tr><td class="header_first" align="left">
<b>From Date: </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date: </b>'.date('d-m-Y',strtotime($totime)).'</td></tr>';
 
  }
  
$output .='</table>';




$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" style="width:940px;"><tr>
<td width="5%" colspan="1" class="header2">S. No.</td>
<td width="10%" colspan="1"  class="header2">Scheme Name</td>
<td width="10%" colspan="1"  class="header2">Account No.</td>
<td width="8%" colspan="1"  class="header2">Loanee Name</td>
<td width="9%" colspan="1"  class="header2">Disbursement Amount</td>
<td width="7%" colspan="1"  class="header2">Bank Name</td>
<td width="9%" colspan="1"  class="header2">Principal Amount</td>

</tr>';





 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
  $loanee_id=$rs->account_no;

$reg=db_query("select reg_number,lname,fname from tbl_loanee_detail where loanee_id='".$loanee_id."'");
$reg_no=db_fetch_object($reg);

$scheme_id=db_query("select scheme_name,loan_requirement,o_disburse_amount from tbl_loan_detail where reg_number='".$reg_no->reg_number."'");
$loan_scheme_id=db_fetch_object($scheme_id);

$scheme_nam=db_query("select scheme_name from tbl_scheme_master where loan_scheme_id='".$loan_scheme_id."'");
$scheme_name=db_fetch_object($scheme_nam);


$bank=db_query("select tbl_bank.bank_name from tbl_bank,tbl_fdr where tbl_fdr.bank_name=tbl_bank.bank_id");
$bank_name=db_fetch_object($bank);

$acc=getAccountNo($rs->account_no); if($acc){}else{$acc='N/A';}
$disburse=$loan_scheme_id->loan_requirement - $loan_scheme_id->o_disburse_amount;
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="5%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="10%" class="'.$class.'" align="left">'.ucwords($scheme_name->scheme_name).'</td>
				<td width="10%" class="'.$class.'" align="left">'.$acc.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($reg_no->fname).' '.ucwords($reg_no->lname).'</td>

				<td width="9%" class="'.$class.'" align="center">'.round($disburse,2).'</td>
			    <td width="7%" class="'.$class.'" align="right">'.ucwords($bank_name).'</td>
				<td width="9%" class="'.$class.'" align="center">'.round($rs->amount,2).'</td>
				
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
	ob_end_clean();
	 // print a block of text using Write()
 $pdf->writeHTML($output, true,1, false, false);
	 
	$pdf->Output('mmloanReport_'.time().'.pdf', 'I');
}

