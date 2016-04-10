<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A3, true, 'UTF-8', false);
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


if($_REQUEST['op'] == 'fdr_report'){
global $user, $base_url;
$rid = $_REQUEST['rid'];

$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = $fromtime;
$to = $totime;
$fdr_no = $_REQUEST['fdr_no'];
$interest_rate = $_REQUEST['interest_rate'];
$amount = $_REQUEST['amount'];
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
width:1290px;
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
/*border:#FFFFFF 1px solid;*/
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
</table><br>';
	

$append="";
	if($_REQUEST['fdr_no']){
	
	$append .= " fdr_no = '".$fdr_no."'  OR fdr_no LIKE '%".$fdr_no."%' AND ";
	}
	if($_REQUEST['interest_rate']){
	
	$append .= " interest_rate LIKE '%".$interest_rate."%' AND ";

	}
	if($_REQUEST['amount']){
	
	$append .= " amount LIKE '%".$amount."%' AND ";
	
	}
	 if($fromtime){
	 if($fromtime !='1970-01-01' && $totime !='1970-01-01' ){
	$append .= " fdr_date BETWEEN '".$fromtime."' AND '".$totime."' AND ";
	
	 }
	 }
  $append .= " 1=1 ";
  
  
  $sql="select * from tbl_fdr where $append";



 $output .='<table cellpadding="3" cellspacing="2" border="0" >';

if($_REQUEST['fdr_no']){
$output .='<tr><td class="header_first" align="left">
<b>FDR No.: </b>'.($fdr_no).'</td></tr>';

}

if($_REQUEST['interest_rate']){

$output .='<tr><td class="header_first" align="left">
<b>Interest Rate: </b>'.$interest_rate.'</td></tr>';


}

if($_REQUEST['amount']){

$output .='<tr><td class="header_first" align="left">
<b>Principal Amount: </b>'.$amount.'</td></tr>';


}
 if($fromtime ){

  if($fromtime !='1970-01-01' && $totime !='1970-01-01' ){
$output .='<tr><td class="header_first" align="left">
<b>From Date: </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date: </b>'.date('d-m-Y',strtotime($totime)).'</td></tr>';
  }
  }
  
$output .='</table><br>';




$output .='<table cellpadding="3" cellspacing="2" border="0" class="tbl_border">
<tr>
<td width="5%" class="header2">S. No.</td>
<td width="10%"class="header2">FDR No.</td>
<td width="10%" class="header2">Bank Name</td>
<td width="8%" class="header2">Loan Account No.</td>
<td width="9%" class="header2">FDR Date</td>
<td width="7%" class="header2">Principal Amount</td>
<td width="9%" class="header2">Maturity Date</td>
<td width="6%" class="header2">Interest Accrued</td>
<td width="6%" class="header2">Interest Rate</td>
<td width="10%" class="header2">Maturity Amount</td>
<td width="10%" class="header2">FDR Type</td>
<td width="20%" class="header2">Status</td>
</tr>';





 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
  $acc=getAccountNo($rs->account_no); if($acc){}else{$acc='N/A';}

   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="5%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->fdr_no.'</td>
				<td width="10%" class="'.$class.'" align="left">'.getBankName($rs->bank_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.$acc.'</td>

				<td width="9%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->fdr_date)).'</td>
			    <td width="7%" class="'.$class.'" align="right">'.round($rs->amount,2).'</td>
				<td width="9%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->maturity_date)).'</td>
				<td width="6%" class="'.$class.'" align="right">'.$rs->interest_accrued.'</td>
				
				<td width="6%" class="'.$class.'" align="right">'.$rs->interest_rate.'</td>
				<td width="10%" class="'.$class.'" align="right">'.$rs->maturity_amount.'</td>
			
				<td width="10%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->fdr_type)).'</td>
				<td width="20%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->status1)).'</td>
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
	ob_end_clean();
	 // print a block of text using Write()
 $pdf->writeHTML($output, true,1, false, false);
	 
	$pdf->Output('fdr_'.time().'.pdf', 'I');
}

