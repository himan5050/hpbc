<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(P, PDF_UNIT, A3, true, 'UTF-8', false);
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
//$pdf->AddPage();






if($_REQUEST['op'] == 'loanledger_report'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];
$scheme_name = $_REQUEST['scheme_name'];
$fromtime = $_REQUEST['from_date'];
$totime = $_REQUEST['to_date'];
$from = date("Y-m-d",strtotime($fromtime));
$to = date("Y-m-d",strtotime($totime));

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

font-weight:bold;
background-color:#ffffff;
}
table{
width:1655px;
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



$header1 .='<table cellpadding="0" cellspacing="0" border="0" style="width:985px;">
<tr><td class="header_report" align="center">Loan Ledger Report</td></tr></table><br />';



//header close





	

  $sql = "SELECT tbl_scheme_master.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.loanee_id,tbl_loan_detail.loan_requirement,
	 tbl_loan_detail.o_interest,tbl_loan_detail.capital_subsidy,sum(tbl_interestsubsidy1.interest_sub_due) as interestsubdueh
FROM tbl_loanee_detail
left JOIN tbl_loan_detail ON ( tbl_loan_detail.reg_number = tbl_loanee_detail.reg_number )
left JOIN tbl_scheme_master ON ( tbl_loan_detail.scheme_name = tbl_scheme_master.loan_scheme_id )
left JOIN tbl_fdr ON (tbl_loanee_detail.loanee_id = tbl_fdr.account_no)
left JOIN tbl_interestsubsidy1 ON (tbl_interestsubsidy1.corp_reg_no = tbl_loanee_detail.reg_number)

WHERE 1=1";


	
$cond = '';	
	

	if($scheme_name){
		$cond .= " AND tbl_loan_detail.scheme_name='$scheme_name'";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>Scheme Name : </b>'.ucwords(getscheme1($scheme_name)).'</td></tr>
</table><br />';
	}	
	
	
	if($fromtime!='' && $totime!=''){
		$cond .= " AND (tbl_loan_detail.sanction_date BETWEEN '$from' AND '$to') ";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>From Date : </b>'.$fromtime.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.$totime.'</td></tr>
</table><br />';
	}else{
		if($fromtime!=''){
			$cond .= " AND tbl_loan_detail.sanction_date='$fromtime' ";
		}
		if($totime!=''){
			$cond .= " AND tbl_loan_detail.sanction_date='$totime' ";
		}
	}
	
$cond .= ' group by tbl_loan_detail.scheme_name';		


 

$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="3%" colspan="1" align="left" class="header2">S. No.</td>
<td width="8%" colspan="1" align="left" class="header2">Scheme Name</td>
<td width="8%" colspan="1" align="left" class="header2">Total Loan Sanctioned</td>
<td width="8%" colspan="1" align="left" class="header2">Total interest Received</td>
<td width="8%" colspan="1" align="left" class="header2">Total Capital Subsidy</td>
<td width="8%" colspan="1" align="left" class="header2">Total Interest Subsidy</td>
<td width="8%" colspan="1" align="left" class="header2">Total MMD</td>
<td width="8%" colspan="1" align="left" class="header2">Total FDR</td>
</tr>';

  
  $outputh .= $header1.$header2.$header3;
  
  $query = $sql . $cond;
  $sql_count = "SELECT count(*) as count_neshat FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_loan_disbursement on (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id) 

where 1=1";

  $query_count = $sql_count . $cond;
  $rescount = db_query($query_count);
  $rscount = db_fetch_object($rescount);
  
  $res = db_query($query);
 
 $counter=1;
 $neshatcount =1;
 
 while($rs = db_fetch_object($res)){
 
	
	$sqlamount=db_query("select sum(maturity_amount) as maturityamount,sum(amount) as fdramount from tbl_fdr where account_no = '".$rs->loanee_id."'");
	
$sqlqu=db_fetch_object($sqlamount);
$maturityamount =$sqlqu->maturityamount;
$fdramount =$sqlqu->fdramount;

				 if($counter%2==0){ $class='header4_1';}else{$class='header4_1';}
				$output .='<tr>
				<td width="3%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->scheme_name.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->loan_requirement.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->o_interest.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->capital_subsidy.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->interestsubdueh.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$maturityamount.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$fdramount.'</td>
				
				';
				$output .='</tr>';
				$counter++; 
							
				
			
		
 }

 $output .='</table>';
 //$outputtt .='<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
 $outputf = $outputh.$output.$outputt;
		
	
	
	
 	ob_end_clean();
	
	$pdf->AddPage();
	 $pdf->writeHTML($outputf, true,1, false, false);
	 
	$pdf->Output('rti_report_'.time().'.pdf', 'I');
}

