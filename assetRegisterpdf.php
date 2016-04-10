<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, B4, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SC and ST');
$pdf->SetTitle('SC and ST');
$pdf->SetSubject('SC and ST');
$pdf->SetKeywords('SC and ST');

//$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, '','');
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


if($_REQUEST['op'] == 'assetRegister_report'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = $fromtime;
$to = $totime;

//echo $fromtime.'jj';exit;

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
width:1200px;
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
Asset Register</td></tr>

<tr><td>&nbsp;</td></tr>
</table>';
	

$append='';
	
	 if($fromtime){
	 if($fromtime !='1970-01-01' && $totime !='1970-01-01' ){
	$append .= " date_amc BETWEEN '".$fromtime."' AND '".$totime."' AND ";
	
	 }
	 }
  $append .= " 1=1 ";
  
  
  $sql="select * from tbl_itassets where $append";



 $output .='<table cellpadding="3" cellspacing="2" border="0">';


 if($fromtime){

 
$output .='<tr><td class="header_first" align="left">
<b>From Date: </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date: </b>'.date('d-m-Y',strtotime($totime)).'</td></tr>';
 
  }
  
$output .='</table>';




$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" style="width:1095px;"><tr>
<td width="5%" colspan="1" class="header2">S. No.</td>
<td width="6%" colspan="1"  class="header2">Section</td>
<td width="6%" colspan="1"  class="header2">Asset Type</td>
<td width="8%" colspan="1"  class="header2">Quantity</td>
<td width="5%" colspan="1"  class="header2">Amount</td>
<td width="5%" colspan="1"  class="header2">Procurement Cost</td>
<td width="9%" colspan="1"  class="header2">Asset Details</td>
<td width="9%" colspan="1"  class="header2">Insurance Company</td>

<td width="9%" colspan="1"  class="header2">Sum Insured</td>

<td width="9%" colspan="1"  class="header2">Date of Renewal</td>

<td width="5%" colspan="1"  class="header2">Claim Details</td>

<td width="9%" colspan="1"  class="header2">AMC Vendor Name</td>

<td width="9%" colspan="1"  class="header2">AMC Date</td>
<td width="5%" colspan="1"  class="header2">AMC Amount</td>
<td width="9%" colspan="1"  class="header2">Contract Details</td>



</tr>';


				



 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
  
  $comp_name=ucwords($rs->company_name); if($comp_name==''){$comp_name='N/A';}
$sum_insured=($rs->sum_insured); if($sum_insured==''){$sum_insured='N/A';}
$date_renewal=date('d-m-Y',strtotime($rs->date_renewal)); if($date_renewal==''){$date_renewal='N/A';}
$claim_det=ucwords($rs->claim_details); if($claim_det==''){$claim_det='N/A';}
$vendor_name=ucwords($rs->vendor_name);if($vendor_name==''){$vendor_name='N/A';}
$date_amc=date('d-m-Y',strtotime($rs->date_amc)); if($date_amc==''){$date_amc='N/A';}
$amt_amc=$rs->amount_amc; if($amt_amc==''){$amt_amc='N/A';}
$contract_det=ucwords($rs->contract_details);if($contract_det==''){$contract_det='N/A';}
  
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="5%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="6%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->section)).'</td>
				<td width="6%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->asset_type)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->quantity).'</td>

				<td width="5%" class="'.$class.'" align="right">'.round($rs->amount).'</td>
			    <td width="5%" class="'.$class.'" align="right">'.round($rs->proc_cost).'</td>
				<td width="9%" class="'.$class.'" align="left">'.ucwords($rs->asset_details).'</td>
				<td width="9%" class="'.$class.'" align="left">'.$comp_name.'</td>
				<td width="9%" class="'.$class.'" align="right">'.round($sum_insured).'</td>
				<td width="9%" class="'.$class.'" align="center">'.$date_renewal.'</td>
				<td width="5%" class="'.$class.'" align="left">'.$claim_det.'</td>
				<td width="9%" class="'.$class.'" align="left">'.$vendor_name.'</td>
				<td width="9%" class="'.$class.'" align="center">'.$date_amc.'</td>
				<td width="5%" class="'.$class.'" align="right">'.round($amt_amc).'</td>
				<td width="9%" class="'.$class.'" align="left">'.$contract_det.'</td>

				</tr>';
				$counter++;
 }



		
		 $output .='</table>';
	
	ob_end_clean();
	 // print a block of text using Write()
 $pdf->writeHTML($output, true,1, false, false);
	 
	$pdf->Output('assetRegister_'.time().'.pdf', 'I');
}

