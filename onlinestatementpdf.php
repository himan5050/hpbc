<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
//getting number of page $pdf->pagenumber()

if($_REQUEST['op'] == 'loanissuedetail_report'){
global $user, $base_url;

$account = $_REQUEST['account'];


$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 10pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 12pt;
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
$output .='<table cellpadding="0" cellspacing="0" border="0">';

$output .='<tr><td colspan="0" align="center" class="header_report">Online Statement</td></tr><tr><td>&nbsp;</td></tr>

</table>';
	

/*if($district == '' and $sector == '' )
{
  form_set_error('form','Please enter the district or sector .');
     
}else{
if($district && $sector == ''){
   $cond = 'and tbl_district.district_name Like "'.'%'.$district.'%'.'"';
}else if($district == '' && $sector ){

  $cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'"';
//  $cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_scheme_master.schemename Like "'.'%'.$scheme.'%'.'"';
}else if($district  && $sector ){
  //$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'" AND tbl_scheme_master.schemename Like "'.'%'.$scheme.'%'.'"';

$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';
}
*/


if($account=='' )
{
  form_set_error('form','Please enter the Account Id .');
     
}else if($account!='')
{
   $cond = 'and tbl_loanee_detail.account_id Like "'. $account.'"';
}


$sql = "SELECT * FROM tbl_loan_amortisaton WHERE loanacc_id = '".$account."'";
$dsql = "SELECT l.fname,l.lname,ld.o_principal,ld.o_interest,ld.o_LD,ld.o_other_charges FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE l.reg_number = ld.reg_number AND l.account_id = '".$account."' LIMIT 1";
$dres = db_query($dsql);
$loanee = db_fetch_object($dres);
 
	
$output .= '<table>
	
	
	
	<tr>
	  <td width="14%">
	
	<b>Loanee Name:</b></td><td>'.$loanee->fname.' '.$loanee->lname.'</td>
	</tr>
	
	<tr><td><b>Account No. :</b></td><td>'.$account.'</td></tr>
	<tr><td><b>Total Amount: Rs.</b></td><td>'.($loanee->o_principal + $loanee->o_interest + $loanee->o_LD + $loanee->o_other_charges).'<br>	
	</td></tr></table>';
	
   $output .='<table cellpadding="3" cellspacing="2" id="wrapper" class="tbl_border">';
  
	
	$output .= '<tr>
	 
	
	
   
   			<td class="header2" width="5%">S. No.</td>
			<td class="header2">Date</td>
			<td class="header2" width="13.5%">Starting Balance</td>
			<td class="header2" width="15%">Other Charges Paid</td>
			<td class="header2">LD Paid</td>
			<td class="header2">Interest Paid</td>
			<td class="header2">Principal Paid</td>
			<td class="header2">Ending Balance</td>
			<td class="header2">Installment Paid</td>
				</tr>';





 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				
				
			$output .='<tr><td class="'.$class.'" align="center">'.$counter.'</td>';
			$output .='<td class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->payment_date)).'</td>';
			$output .='<td class="'.$class.'" align="right">'.round($rs->starting_balance).'</td>';
			$output .='<td class="'.$class.'" align="right">'.round($rs->other_charges_paid).'</td>';
			$output .='<td class="'.$class.'" align="right">'.round($rs->LD_paid).'</td>';
			$output .='<td class="'.$class.'" align="right">'.round($rs->interest_paid).'</td>';
			$output .='<td class="'.$class.'" align="right">'.round($rs->principal_paid).'</td>';
			$output .='<td class="'.$class.'" align="right">'.round($rs->ending_balance).'</td>';
			$output .='<td class="'.$class.'" align="right">'.round($rs->installment_paid).'</td>
							
	            </tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
	
	 // print a block of text using Write()
	 ob_end_clean();
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('loanissuedetail_report_'.time().'.pdf', 'I');
}

