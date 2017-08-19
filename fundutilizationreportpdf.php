<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap ( DRUPAL_BOOTSTRAP_FULL );

require_once ('tcpdf/config/lang/eng.php');
require_once ('tcpdf/tcpdf.php');
require_once ('tcpdf/pdfcss.php');

// create new PDF document
$pdf = new TCPDF ( L, PDF_UNIT, A4, true, 'UTF-8', false );
// set document information
$pdf->SetCreator ( PDF_CREATOR );
$pdf->SetAuthor ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPEMENT CORPORATION' );
$pdf->SetTitle ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPEMENT CORPORATION' );
$pdf->SetSubject ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPEMENT CORPORATION' );
$pdf->SetKeywords ( 'HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPEMENT CORPORATION' );
$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );

// $pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetHeaderData ( '', PDF_HEADER_LOGO_WIDTH );
// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
// set default monospaced font
$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
$pdf->SetPrintHeader ( false );
// set margins
$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT );
// set auto page breaks
$pdf->SetAutoPageBreak ( TRUE, 10 );
// set image scale factor
$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
// set some language-dependent strings
$pdf->setLanguageArray ( $l );
// set font
$pdf->SetFont ( 'times', '', 10 );
// add a page
$pdf->AddPage ();

if ($_REQUEST ['op'] == 'fundutilization_report') {
	$cond = '';
	$panchayat_join = '';
	$district = isset ( $_REQUEST ['district'] ) ? $_REQUEST ['district'] : '';
	$tehsil = isset ( $_REQUEST ['tehsil'] ) ? $_REQUEST ['tehsil'] : '';
	$panchayat = isset ( $_REQUEST ['panchayat'] ) ? $_REQUEST ['panchayat'] : '';
	$from_date = isset ( $_REQUEST ['from_date'] ) ? $_REQUEST ['from_date'] : '';
	$to_date = isset ( $_REQUEST ['to_date'] ) ? $_REQUEST ['to_date'] : '';
	
	$output = '';
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
font-family:Times New Roman;
font-size: 8pt;
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
		font-size: 8pt;
		font-weight: normal;
}
			
td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#1D374C;
font-family:Verdana;
font-size: 8pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 8pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 8pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#9dcae7;
font-family:Verdana;
font-size: 8pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 8pt;
font-weight: normal;
}
td.msg{
color:#FF0000;
text-align:left;
}
</style>
EOF;
	
	// Header Title
	$output .= '<table cellpadding="0" cellspacing="0" border="0">
			   <tr><td class="header_report" width="68%">HIMACHAL BACKWARD CLASSES FINANCE AND DEVELOPMENT CORPORATION KANGRA (H.P.)</td><td class="header_report" width="30%" align="right">Fund Utilization Certificate ( ' . date ( 'd/m/Y', strtotime ( $from_date ) ) . ' To ' . date ( 'd/m/Y', strtotime ( $to_date ) ) . ' ) </td></tr><tr><td><strong>Date  : ' . date ( 'd/m/y' ) . '</strong></td></tr><tr><td colspan="2">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr></table><br/>';
	
	if ($from_date == '' || $to_date == '') {
		form_set_error ( 'form', 'Please select period to generate report.' );
	} else if (strtotime ( $from_date ) > strtotime ( $to_date )) {
		form_set_error ( 'form', 'Please select dates properly.' );
	} else {
		if ($district) {
			$cond = ' and tbl_loanee_detail.district Like "' . $district . '"';
			$_REQUEST ['page'] = 0;
		}
		if ($tehsil) {
			$cond .= ' and tbl_loanee_detail.tehsil LIKE "' . $tehsil . '"';
			$_REQUEST ['page'] = 0;
		}
		if ($panchayat) {
			$cond .= ' and tbl_loanee_detail.panchayat LIKE "' . $panchayat . '"';
			$panchayat_join .= 'INNER JOIN tbl_panchayt ON  (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat)';
			$_REQUEST ['page'] = 0;
		}
		if ($from_date && $to_date) {
			$cond .= ' and tbl_loan_disbursement.cheque_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
			$_REQUEST ['page'] = 0;
		}
		
		$sql = "SELECT tbl_scheme_master.loan_scheme_id,
	tbl_scheme_master.scheme_name,
	tbl_sectors.sector_name
	FROM `tbl_loan_detail`
	INNER JOIN tbl_scheme_master ON  (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
	INNER JOIN tbl_loanee_detail ON  (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
	INNER JOIN tbl_district ON  (tbl_district.district_id=tbl_loanee_detail.district)
	INNER JOIN tbl_tehsil ON  (tbl_tehsil.tehsil_id=tbl_loanee_detail.tehsil)
	$panchayat_join
	INNER JOIN tbl_sectors ON  (tbl_sectors.sector_id=tbl_scheme_master.sector)
	INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
	WHERE 1=1 $cond GROUP BY `tbl_loan_detail`.scheme_name ORDER by `tbl_loan_disbursement`.cheque_date DESC";
		
		if ($from_date == '' || $to_date == '') {
			form_set_error ( 'form', 'Please select period to generate report.' );
		} else if (strtotime ( $from_date ) > strtotime ( $to_date )) {
			form_set_error ( 'form', 'Please select dates properly.' );
		} else {
			if ($district) {
				$intcal = "SELECT district_name FROM `tbl_district` WHERE district_id = $district";
				$intcal1 = db_query ( $intcal );
				$ic = db_fetch_object ( $intcal1 );
				$district_name = isset ( $ic->district_name ) ? $ic->district_name : '';
				$output .= '<tr><td><b>District Name :</b> ' . ucwords ( $district_name ) . '<br></td></tr>';
				$_REQUEST ['page'] = 0;
			}
			if ($tehsil) {
				$intcal = "SELECT tehsil_name FROM `tbl_tehsil` WHERE tehsil_id = $tehsil";
				$intcal1 = db_query ( $intcal );
				$ic = db_fetch_object ( $intcal1 );
				$tehsil_name = isset ( $ic->tehsil_name ) ? $ic->tehsil_name : '';
				$output .= '<tr><td><b>Tehsil Name :</b> ' . ucwords ( $tehsil_name ) . '<br></td></tr>';
				$_REQUEST ['page'] = 0;
			}
			if ($panchayat) {
				$intcal = "SELECT panchayt_name FROM `tbl_panchayt` WHERE panchayt_id = $panchayat";
				$intcal1 = db_query ( $intcal );
				$ic = db_fetch_object ( $intcal1 );
				$panchayt_name = isset ( $ic->panchayt_name ) ? $ic->panchayt_name : '';
				$output .= '<tr><td><b>Panchayat Name :</b> ' . ucwords ( $panchayt_name ) . '<br></td></tr>';
				$_REQUEST ['page'] = 0;
			}
		}
		
		$output .= '<table cellpadding="2" cellspacing="2" id="wrapper" class="tbl_border">';
		
		$output .= '<tr><td width="5%" class="header2">S.No.</td>
				<td width="15%" class="header2">Sector</td>
				<td width="15%" class="header2">Scheme</td>
				<td class="header2">No. of Units</td>
				<td class="header2">Loan Accounts</td>
				<td class="header2">Project Cost (Rs. In Lakh)</td>
				<td width="10%" class="header2">NBCFDC Share (Rs. In Lakh)</td>
				<td width="10%" class="header2">HBCFDC Share (Rs. In Lakh)</td>
				<td width="10%" class="header2">Promoter Share (Rs. In Lakh)</td>
				</tr>';
		
		$res = db_query ( $sql );
		$counter = 1;
		$allunits = 0;
		$allprojectcosts = 0;
		$allnbcfdc = 0;
		$allhbcfdc = 0;
		$allpromotershare = 0;
		while ( $rs = db_fetch_object ( $res ) ) {
			// Calculate number of units
			$sql_loan_count = "SELECT COUNT(DISTINCT loan_id) as unit
		FROM `tbl_loan_detail`
		INNER JOIN tbl_loanee_detail ON  (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
		INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
		WHERE 1=1 $cond and scheme_name = '" . $rs->loan_scheme_id . "'";
			$sql_loan_count1 = db_query ( $sql_loan_count );
			$slc = db_fetch_object ( $sql_loan_count1 );
			
			// get account ids
			$sql_account_id = "SELECT DISTINCT account_id
		FROM `tbl_loan_detail`
		INNER JOIN tbl_loanee_detail ON  (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
		INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
		WHERE 1=1 $cond and scheme_name = '" . $rs->loan_scheme_id . "'";
			$sql_account_id1 = db_query ( $sql_account_id );
			$account_ids = '';
			$first = true;
			while ( $sai = db_fetch_object ( $sql_account_id1 ) ) {
				if ($first) {
					$account_ids .= $sai->account_id;
					$first = false;
				} else {
					$account_ids .= ', ' . $sai->account_id;
				}
			}
			
			// Get sanctioned amount.
			$sql_sanc_amt = "SELECT SUM(amount) as sanctioned_amount
		FROM `tbl_loan_detail`
		INNER JOIN tbl_loanee_detail ON  (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
		INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
		WHERE 1=1 $cond and scheme_name = '" . $rs->loan_scheme_id . "'";
			$sql_sanc_amt1 = db_query ( $sql_sanc_amt );
			$ssa = db_fetch_object ( $sql_sanc_amt1 );
			$sanctioned_amount = $ssa->sanctioned_amount ;
			
			// GET NBCFDC, HBCFDC and Promoter Share.
			$sql_share = "SELECT apex_share,corp_share,promoter_share FROM `tbl_scheme_master` where loan_scheme_id = '" . $rs->loan_scheme_id . "'";
			$sql_share1 = db_query ( $sql_share );
			$ss = db_fetch_object ( $sql_share1 );
			$NBCFDC_amount = $ssa->sanctioned_amount * $ss->apex_share / 100;
			$nbcfdc_amount = $NBCFDC_amount;
			$HBCFDC_amount = $ssa->sanctioned_amount * $ss->corp_share / 100;
			$hbcfdc_amount = $HBCFDC_amount;
			$PROMOTER_amount = $ssa->sanctioned_amount * $ss->promoter_share / 100;
			$promoter_amount = $PROMOTER_amount;
			
			if ($counter % 2 == 0) {
				$class = 'header4_1';
			} else {
				$class = 'header4_2';
			}
			
			$output .= '<tr>
					  <td class="' . $class . '">' . $counter . '</td>
					  <td class="' . $class . '">' . ucwords ( $rs->sector_name ) . '</td>
					  <td class="' . $class . '">' . ucwords ( $rs->scheme_name ) . '</td>
					  <td align="right" class="' . $class . '">' . $slc->unit . '</td>
					  <td class="' . $class . '">' . $account_ids . '</td>
					  <td align="right" class="' . $class . '">' . round ( $sanctioned_amount, '2' ) . '</td>
					  <td align="right" class="' . $class . '">' . round ( $nbcfdc_amount, '2' ) . '</td>
					  <td align="right" class="' . $class . '">' . round ( $hbcfdc_amount, '2' ) . '</td>
					  <td align="right" class="' . $class . '">' . round ( $promoter_amount, '2' ) . '</td>
	                  </tr>';
			$counter ++;
			$allunits = $slc->unit + $allunits;
			$allprojectcosts = $sanctioned_amount + $allprojectcosts;
			$allnbcfdc = $nbcfdc_amount + $allnbcfdc;
			$allhbcfdc = $hbcfdc_amount + $allhbcfdc;
			$allpromotershare = $promoter_amount + $allpromotershare;
		}
		if ($cl == 'header4_1')
			$cl = 'header4_2';
		else
			$cl = 'header4_1';
		$output .= '<tr style="background-color:white;"><td colspan="2"></td>
                    <td align="left" class="' . $cl . '"><strong>Grand Total</strong></td>';
		$output .= '<td align="right" class="' . $cl . '"><strong>' . round ( $allunits, '2' ) . '</strong></td><td colspan="1" align = "right" class="' . $cl . '"></td>
					<td colspan="1" align = "right" class="' . $cl . '"><strong>' . round ( $allprojectcosts, '2' ) . '</strong></td><td colspan="1" align = "right" class="' . $cl . '"><strong>' . round ( $allnbcfdc, '2' ) . '</strong></td>
					<td colspan="1" align = "right" class="' . $cl . '"><strong>' . round ( $allhbcfdc, '2' ) . '</strong></td><td colspan="1" align = "right" class="' . $cl . '"><strong>' . round ( $allpromotershare, '2' ) . '</strong></td>
					</tr>';
		
		$output .= '</table>';
		ob_end_clean ();
		
		// print a block of text using Write()
		$pdf->writeHTML ( $output, true, 0, true, true );
		// Close and output PDF document
		$pdf->Output ( 'fundutilization_report_' . time () . '.pdf', 'I' );
	}
}