<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap ( DRUPAL_BOOTSTRAP_FULL );
require_once ('tcpdf/pdfcss.php');
require_once ('tcpdf/config/lang/eng.php');
require_once ('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF ( L, PDF_UNIT, A3, true, 'UTF-8', false );
// set document information
$pdf->SetCreator ( PDF_CREATOR );
$pdf->SetAuthor ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION' );
$pdf->SetTitle ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION' );
$pdf->SetSubject ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION' );
$pdf->SetKeywords ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION' );

//$pdf->SetHeaderData ( 'tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, " ", " " );
// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont ( Array (
		PDF_FONT_NAME_MAIN,
		'',
		PDF_FONT_SIZE_MAIN 
) );
$pdf->setFooterFont ( Array (
		PDF_FONT_NAME_DATA,
		'',
		PDF_FONT_SIZE_DATA 
) );
// set default monospaced font
$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
// set margins
$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
// set auto page breaks
$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
// set image scale factor
$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
// set some language-dependent strings
//$pdf->setLanguageArray ( $l );
// set font
$pdf->SetFont ( 'helvetica', '', 20 );
ob_end_clean ();

if ($_REQUEST ['op'] == 'disbursement_report') {
	$district_id = $_REQUEST ['district_id'];
	$fromtime = $_REQUEST ['from_date'];
	$totime = $_REQUEST ['to_date'];
	
	$from = strtotime ( "0 day", strtotime ( $fromtime ) );
	$to = strtotime ( "+1 day", strtotime ( $totime ) );
	$output = '';
	$header_css = '';
	// define some HTML content with style
	$header_css .= <<<EOF
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
font-family:Times New Roman;
font-size: 12pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:1000px;
}
table.tbl_border{border:1px solid #ffffff;
background-color: #ffffff;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 12pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#3b3c3c;
border-left-color:#ffffff;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 10pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 10pt;
font-weight: normal;
}
td.msg{
color:#FF0000;
text-align:left;
}
</style>
EOF;
	
	// Header Title
	$header1 .= '<table cellpadding="0" cellspacing="0" border="0" style="width:1420px;">
<tr><td class="header_report" align="center">LOAN DISBURSEMENT REPORT ( FROM "'.$fromtime.'" To "'.$totime.'" )</td></tr></table>';
	// header close
	
	$sql = "SELECT tbl_scheme_master.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.account_id,tbl_loanee_detail.fname,tbl_loanee_detail.lname,tbl_loanee_detail.loanee_id,tbl_loanee_detail.district,
	tbl_loanee_detail.tehsil,tbl_loanee_detail.address1,tbl_loanee_detail.address2,tbl_loan_detail.loan_requirement,tbl_loan_detail.project_cost,tbl_loan_disbursement.createdon,tbl_loan_disbursement.cheque_number,tbl_loan_disbursement.amount,tbl_loanee_detail.fh_name
	
	 FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_loan_disbursement on (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id) 

where 1=1";
	
	$cond = '';
	
	if ($district_id) {
		$cond .= " AND tbl_loanee_detail.district='$district_id'";
		$header2 .= '<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>District : </b>' . ucwords ( getdistrict ( $district_id ) ) . '</td></tr>
</table>';
		$header2 .= '<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"></td></tr>
</table>';
	}
	
	if ($fromtime != '' && $totime != '') {
		$cond .= " AND (tbl_loan_disbursement.createdon BETWEEN '$from' AND '$to') ";
	} else {
		if ($fromtime != '') {
			$cond .= " AND tbl_loan_disbursement.createdon='$from' ";
		}
		if ($totime != '') {
			$cond .= " AND tbl_loan_disbursement.createdon='$to' ";
		}
	}
	
	$cond .= ' order by tbl_loanee_detail.account_id';
	
	$header3 .= '<table cellpadding="3" cellspacing="2" border="0" class="tbl_border" align="center" width="1400px;"><tr>
<td width="3%" colspan="1" align="center" class="header2">S. No.</td>
<td width="5%" colspan="1" align="center" class="header2">Account No.</td>
<td width="9.5%" colspan="1" align="center" class="header2">Loanee Name</td>
<td width="9.5%" colspan="1" align="center" class="header2">Father Name/Husband Name </td>
<td width="16%" colspan="1" align="center" class="header2">Address</td>
<td width="8%" colspan="1" align="center" class="header2">Tehsil</td>
<td width="8%" colspan="1" align="center" class="header2">Scheme</td>
<td width="8%" colspan="1" align="center" class="header2">Project Cost</td>
<td width="8%" colspan="1" align="center" class="header2">Disbursement Date</td>
<td width="8%" colspan="1" align="center" class="header2">Disbursement Amount</td>
<td width="8%" colspan="1" align="center" class="header2">DD No.</td>
<td width="8%" colspan="1" align="center" class="header2">Balance Amount</td>
</tr>';
	
	$outputh .= $header_css. $header1 . $header2 . $header3;
	
	$query = $sql . $cond;
	$sql_count = "SELECT count(*) as count_neshat FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_loan_disbursement on (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id) 

where 1=1";
	
	$query_count = $sql_count . $cond;
	$rescount = db_query ( $query_count );
	$rscount = db_fetch_object ( $rescount );
	
	$res = db_query ( $query );
	
	$counter = 1;
	$neshatcount = 1;
	$alltotal = 0;
	$allbalance = 0;
	while ( $rs = db_fetch_object ( $res ) ) {
		//$wer = $rs->loan_requirement;
		$wer = $rs->project_cost;
		$dis_amount = $rs->amount;
		$alltotal = $alltotal + $wer;
		//$accountid=$rs->account_id;
		if(!$accountid) {
			$accountid = $rs->account_id;
		}
		if($accountid !=$rs->account_id) {
			$accountid = $rs->account_id;
			$ending_balance= $wer;
			$balance_amount=$ending_balance-$dis_amount;
		}
		else{
			if($ending_balance==0) {
				$ending_balance= $wer;
			}
			else{
				$ending_balance =$balance_amount;
			}
			$balance_amount=$ending_balance-$dis_amount;
		}
		
		$allbalance = $allbalance + $dis_amount;
		
		if ($counter % 2 == 0) {
			$class = 'header4_2';
		} else {
			$class = 'header4_1';
		}
		$output .= '<tr>
				<td width="3%" class="' . $class . '" align="center">' . $counter . '</td>
				<td width="8%" class="' . $class . '" align="center">' . $rs->account_id . '</td>
				<td width="8%" class="' . $class . '" align="center">' . ucwords ( $rs->fname ) . '<br>' . ucwords ( $rs->lname ) . '</td>
				<td width="8%" class="' . $class . '" align="center">' . $rs->fh_name . '</td>
				<td width="16%" class="' . $class . '" align="center">' . ucwords ( $rs->address1 ) . ' ' . ucwords ( $rs->address2 ) . '</td>
				<td width="8%" class="' . $class . '" align="center">' . ucwords ( gettehsil ( $rs->tehsil ) ) . '</td>
				<td width="8%" class="' . $class . '" align="center">' . ucwords ( $rs->scheme_name ) . '</td>
				<td width="8%" class="' . $class . '" align="center">' . round ( $wer ) . '</td>
				<td width="8%" class="' . $class . '" align="center">' . date ( "d-m-Y", $rs->createdon ) . '</td>
				<td width="8%" class="' . $class . '" align="center">' . round ( $dis_amount ) . '</td>
				<td width="8%" class="' . $class . '" align="center">' . $rs->cheque_number . '</td>
				<td width="8%" class="' . $class . '" align="center">' . round ( $balance_amount ) . '</td>
				
				
				';
		$output .= '</tr>';
		if($counter % 12 == '0') {
			$outputt .= '</table>';
			$outputf = $outputh . $output . $outputt;
			// Close and output PDF document
			ob_end_clean ();
			$pdf->AddPage ();
			$pdf->writeHTML ( $outputf, true, 0, true, true );
			$outputf = '';
			$outputt = '';
			$output = '';	
		}
		$counter ++;
	}
	
	if ($cl == 'header4_1')
		$cl = 'header4_2';
		else
			$cl = 'header4_1';
			
			$output .= '<tr style="background-color:white;"><td colspan="6"></td>
                       <td align="center" class="' . $cl . '"><strong>Grand Total</strong></td>';
			$output .= '<td align="center" class="' . $cl . '"><strong>' . round ( $alltotal ) . '</strong></td><td></td><td align = "center" class="' . $cl . '"><strong>' . round ( $allbalance ) . '</strong></td></tr>';
	
	$outputt .= '</table>';
	// $outputtt .='<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
	$outputf = $outputh . $output . $outputt;
	// Close and output PDF document
	$pdf->AddPage ();
	$pdf->writeHTML ( $outputf, true, 0, true, true );
	
	$pdf->Output ( 'disbursement_report_' . time () . '.pdf', 'I' );
}

