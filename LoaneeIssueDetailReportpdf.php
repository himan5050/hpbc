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
$pdf->SetMargins ( 5, PDF_MARGIN_RIGHT );
// set auto page breaks
$pdf->SetAutoPageBreak ( TRUE, 10 );
// set image scale factor
$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
// set some language-dependent strings
$pdf->setLanguageArray ( $l );
// set font
$pdf->SetFont ( 'times', '', 10 );
// add a page
// $pdf->AddPage ();
if ($_REQUEST ['op'] == 'loanissuedetail_report') {
	$cond = '';
	$panchayat_table = '';
	$panchayat_join = '';
	$panchayat_header = '';
	$panchayat_row = '';
	$width = '36.0%';
	$district = isset ( $_REQUEST ['district'] ) ? $_REQUEST ['district'] : '';
	$tehsil = isset ( $_REQUEST ['tehsil'] ) ? $_REQUEST ['tehsil'] : '';
	$panchayat = isset ( $_REQUEST ['panchayat'] ) ? $_REQUEST ['panchayat'] : '';
	$sector = isset ( $_REQUEST ['sector'] ) ? $_REQUEST ['sector'] : '';
	$scheme = isset ( $_REQUEST ['scheme'] ) ? $_REQUEST ['scheme'] : '';
	$account = isset ( $_REQUEST ['account'] ) ? $_REQUEST ['account'] : '';
	$from_date = isset($_REQUEST ['from_date']) ? $_REQUEST ['from_date'] : '';
	$to_date = isset($_REQUEST ['to_date']) ? $_REQUEST ['to_date'] : '';
	
	$output = '';
	$header = '';
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
border-bottom-color:#3b3c3c;
border-left-color:#ffffff;
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
background-color:#ffffff;
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
	$header .='<table cellpadding="0" cellspacing="0" border="0">
			   <tr><td class="header_report" width="68%">HIMACHAL BACKWARD CLASSES FINANCE AND DEVELOPMENT CORPORATION KANGRA (H.P.)</td><td class="header_report" width="30%" align="right">General Loanee Details Report ( ' .date('d/m/Y', strtotime($from_date)). ' To '.date('d/m/Y', strtotime($to_date)).' ) </td></tr><tr><td><strong>Date  : '.date('d/m/y').'</strong></td></tr><tr><td colspan="2">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr></table><br/>';
	
	if ($district == '' && $tehsil == '' && $panchayat == '' && $sector == '' && $account == '') {
		form_set_error ( 'form', 'Please provide an input to generate report.' );
	} else if ($from_date == '' || $to_date == '') {
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
			$panchayat_table = 'tbl_panchayt.panchayt_name,';
			$panchayat_join = 'INNER JOIN tbl_panchayt ON  (tbl_loanee_detail.panchayat=tbl_panchayt.panchayt_id)';
			$_REQUEST ['page'] = 0;
		}
		if ($sector) {
			$cond .= ' and tbl_sectors.sector_id LIKE "' . $sector . '"';
			$_REQUEST ['page'] = 0;
		}
		if ($scheme) {
			$cond .= ' and tbl_scheme_master.loan_scheme_id LIKE "' . $scheme . '"';
			$_REQUEST ['page'] = 0;
		}
		if ($account) {
			$cond .= ' and tbl_loanee_detail.account_id LIKE "' . $account . '"';
			$_REQUEST ['page'] = 0;
		}
		if ($from_date && $to_date) {
			$cond .= ' and tbl_loan_disbursement.cheque_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
			$_REQUEST ['page'] = 0;
		}
		// Ommiting closed accounts
		$last_quarter_end_date = getLastQuaterEndDate ();
		$cond .= ' and tbl_loan_detail.o_principal != 0 and tbl_loan_detail.last_interest_calculated >= "' . $last_quarter_end_date . '"';
		
		$sql = "SELECT tbl_loan_detail.scheme_name,
	tbl_loan_detail.reg_number,
	tbl_loan_detail.loan_amount,
	tbl_loan_detail.o_other_charges,
	tbl_loan_detail.o_interest,
	tbl_loan_detail.o_principal,
	tbl_loan_detail.o_LD,
	tbl_loan_disbursement.cheque_date,
	tbl_loanee_detail.account_id,
	tbl_loanee_detail.loanee_id,
	tbl_loanee_detail.fname,
	tbl_loanee_detail.lname,
	tbl_loanee_detail.fh_name,
	tbl_loanee_detail.mobile,
	tbl_loanee_detail.district,
	tbl_loanee_detail.gender,
	tbl_loanee_detail.address1,
	tbl_loanee_detail.address2,
	tbl_district.district_name,
	tbl_tehsil.tehsil_name,
	$panchayat_table
	tbl_block.block_name,
	tbl_scheme_master.scheme_name as schemename ,
	tbl_scheme_master.tenure ,
	tbl_sectors.sector_name,
	tbl_scheme_master.loan_scheme_id,
	tbl_scheme_master.apex_share,
	tbl_scheme_master.corp_share,
	tbl_scheme_master.promoter_share
	FROM tbl_loanee_detail
	INNER JOIN tbl_loan_detail ON  (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number)
	INNER JOIN tbl_loan_disbursement ON  (tbl_loanee_detail.loanee_id=tbl_loan_disbursement.loanee_id)
	INNER JOIN tbl_scheme_master ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id)
	INNER JOIN tbl_sectors ON  (tbl_scheme_master.sector=tbl_sectors.sector_id)
	INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id)
	INNER JOIN tbl_tehsil ON  (tbl_loanee_detail.tehsil=tbl_tehsil.tehsil_id)
	$panchayat_join
	INNER JOIN tbl_block ON  (tbl_loanee_detail.block=tbl_block.block_id)
	LEFT OUTER JOIN tbl_loan_repayment   ON (tbl_loanee_detail.loanee_id=tbl_loan_repayment.loanee_id)
	where 1=1  $cond GROUP BY tbl_loan_repayment.loanee_id";
	
	if ($district == '' && $tehsil == '' && $panchayat == '' && $sector == '' && $account == '') {
		form_set_error ( 'form', 'Please provide an input to generate report.' );
	} else if ($from_date == '' || $to_date == '') {
		form_set_error ( 'form', 'Please select period to generate report.' );
	} else if (strtotime ( $from_date ) > strtotime ( $to_date )) {
		form_set_error ( 'form', 'Please select dates properly.' );
	} else {
		if ($district) {
			$intcal = "SELECT district_name FROM `tbl_district` WHERE district_id = $district";
			$intcal1 = db_query ( $intcal );
			$ic = db_fetch_object ( $intcal1 );
			$district_name = isset ( $ic->district_name) ? $ic->district_name: '';
			$header1 .= '<tr><td><b>District Name :</b> ' . ucwords ( $district_name ) . '<br></td></tr>';
			$_REQUEST ['page'] = 0;
		}
		if ($tehsil) {
			$intcal = "SELECT tehsil_name FROM `tbl_tehsil` WHERE tehsil_id = $tehsil";
			$intcal1 = db_query ( $intcal );
			$ic = db_fetch_object ( $intcal1 );
			$tehsil_name = isset ( $ic->tehsil_name) ? $ic->tehsil_name: '';
			$header1 .= '<tr><td><b>Tehsil Name :</b> ' . ucwords ( $tehsil_name ) . '<br></td></tr>';
			$_REQUEST ['page'] = 0;
		}
		if ($panchayat) {
			$intcal = "SELECT panchayt_name FROM `tbl_panchayt` WHERE panchayt_id = $panchayat";
			$intcal1 = db_query ( $intcal );
			$ic = db_fetch_object ( $intcal1 );
			$panchayt_name = isset ( $ic->panchayt_name) ? $ic->panchayt_name: '';
			$header1 .= '<tr><td><b>Panchayat Name :</b> ' . ucwords ( $panchayt_name) . '<br></td></tr>';
			$_REQUEST ['page'] = 0;
		}
		if ($sector) {
			$intcal = "SELECT sector_name FROM `tbl_sectors` WHERE sector_id = $sector";
			$intcal1 = db_query ( $intcal );
			$ic = db_fetch_object ( $intcal1 );
			$sector_name = isset ( $ic->sector_name ) ? $ic->sector_name : '';
			$header1 .= '<tr><td><b>Sector Name :</b> ' . ucwords ( $sector_name ) . '<br></td></tr>';
			$_REQUEST ['page'] = 0;
		}
		if ($scheme) {
			$intcal = "SELECT scheme_name FROM `tbl_scheme_master` WHERE loan_scheme_id = $scheme";
			$intcal1 = db_query ( $intcal );
			$ic = db_fetch_object ( $intcal1 );
			$scheme_name = isset ( $ic->scheme_name ) ? $ic->scheme_name : '';
			$header1 .= '<tr><td><b>Scheme Name :</b> ' . ucwords ( $scheme_name ) . '<br></td></tr>';
			$_REQUEST ['page'] = 0;
		}
		if ($account) {
			$header1 .= '<tr><td><b>Account Name :</b> ' . ucwords ( $account ) . '<br></td></tr>';
			$_REQUEST ['page'] = 0;
		}
	}
	
		$header1 .= '<table cellpadding="2" cellspacing="2" id="wrapper" class="tbl_border">';
		$header1 .= '<tr><td align="center" style="border:1px solid #fff;" width="'.$width.'" colspan="6" class="header2">Loanee Detail</td><td colspan="6" align="center" class="header2" width="14.5%" style="border:1px solid #fff;">Gaurantor Detail</td><td colspan="2" align="center" class="header2" width="18.0%" style="border:1px solid #fff;">Address Detail</td><td align="center" style="border:1px solid #fff;" colspan="6" class="header2" width="34.2%">Project Detail</td></tr>';
		
		$header1 .= '<tr><td width="2%" class="header2">S.No.</td>
				<td width="5.0%" class="header2">A/c No.</td>
				<td width="6.0%" class="header2">Name</td>
				<td width="6.0%" class="header2">Father</td>
				<td width="11.0%" class="header2">Address</td>
				<td width="2.0%" class="header2">Sex</td>
				<td width="3.5%" class="header2">Mobile</td>
				<td width="4.5%" class="header2">Name</td>
				<td width="11.0%" class="header2">Address</td>
				<td width="6.0%" class="header2">District</td>
				<td width="6.0%" class="header2">Tehsil</td>
				<td width="6.0%" class="header2">Block</td>
				<td width="4.2%" class="header2">Scheme</td>
				<td width="4.8%" class="header2">Loan Sanctioned Amount</td>
				<td width="5.4%" class="header2">Loan Sanctioned Date</td>
				<td width="5.0%" class="header2">Recovered Amount</td>
				<td width="5.0%" class="header2">Interest Amount</td>
				<td width="5.0%" class="header2">Overdue Charges</td>
				<td width="5.0%" class="header2">Outstanding Balance</td>
				</tr>';
		
		$res = db_query ( $sql );
		$counter = 1;
		$alldisbamount = 0;
		$allrecovered_amount = 0;
		$allinterest_amount = 0;
		$allld_amount = 0;		
		$allbalance_amount = 0;
		$output .= $header_css;
		$output .= $header;
		$output .= $header1;
		while ( $rs = db_fetch_object ( $res ) ) {
			$gender = getlookupName ( $rs->gender );
			if($gender == 'Male') {
				$gender = 'M';
			} else {
				$gender = 'F';
			}
			$intcal = "SELECT calculation_date FROM `tbl_loan_interestld` WHERE `account_id` = '" . $rs->account_id . "' ORDER BY calculation_date DESC LIMIT 1";
			$intcal1 = db_query ( $intcal );
			$ic = db_fetch_object ( $intcal1 );
			$last_int_date = isset ( $ic->calculation_date ) ? $ic->calculation_date : '';
			
			$recPay = "SELECT payment_date FROM `tbl_loan_repayment` WHERE `loanee_id` = '" . $rs->loanee_id . "' order by `payment_date` DESC LIMIT 1";
			$recPay1 = db_query ( $recPay );
			$rP = db_fetch_object ( $recPay1 );
			$last_rec_date = isset ( $rP->payment_date ) ? $rP->payment_date : '';
			$timeDiff = (strtotime ( $last_int_date ) - strtotime ( $last_rec_date ));
			if ($timeDiff > 0) {
				$curr_date = $last_int_date;
			} else {
				$curr_date = $last_rec_date;
			}
			$balamount = coreloanledger ( $rs->account_id, $curr_date );
			
			// Get Recovered Amount
			$rec = "SELECT SUM(amount) as amount FROM tbl_loan_repayment WHERE loanee_id = '" . $rs->loanee_id . "'";
			$rec1 = db_query ( $rec );
			$r = db_fetch_object ( $rec1 );
			$recovered_amount = isset ( $r->amount ) ? $r->amount : 0;
			
			// Get Interest amount
			$int = "SELECT SUM(amount) as int_amount FROM tbl_loan_interestld WHERE type = 'interest' and account_id = '" . $rs->account_id . "'";
			$int1 = db_query ( $int );
			$i = db_fetch_object ( $int1 );
			$interest_amount = isset ( $i->int_amount ) ? $i->int_amount : 0;
			
			// Get overdue amount
			$ld = "SELECT SUM(amount) as ld_amount FROM tbl_loan_interestld WHERE type = 'LD' and account_id = '" . $rs->account_id . "'";
			$ld1 = db_query ( $ld );
			$i = db_fetch_object ( $ld1 );
			$ld_amount = isset ( $i->ld_amount ) ? $i->ld_amount : 0;
			
			// Get Gauranter Detail
			$gsql = "SELECT * FROM tbl_guarantor_detail WHERE loanee_id = '" . $rs->loanee_id . "' LIMIT 1";
			$gres = db_query ( $gsql );
			$g = db_fetch_object ( $gres );
			
			// Get Disb Amount
			$dsql = "SELECT SUM(amount) AS disamount FROM tbl_loan_disbursement WHERE loanee_id = '" . $rs->loanee_id . "'";
			$dres = db_query ( $dsql );
			$d = db_fetch_object ( $dres );
			
				
			if ($counter % 2 == 0) {
				$class = 'header4_1';
			} else {
				$class = 'header4_2';
			}
			$accno = ($rs->account_id) ? $rs->account_id : 'N/A';
			$gname = ($g->gname) ? $g->gname : 'N/A';
			$gaddress = ($g->address) ? $g->address : 'N/A';
			$disbamount = ($d->disamount) ? $d->disamount : 'N/A';
			$panchayat_name = isset($rs->panchayt_name) ? $rs->panchayt_name : 'URBAN';
			if($panchayat) {
				$panchayat_row = '<td class="' . $class . '">' . ucwords ( $panchayat_name ) . '</td>';
			}else {
				$panchayat_row = '';
			}
			$output .= '<tr>
					  <td class="' . $class . '">' . $counter . '</td>
					  <td class="' . $class . '">' . $accno . '</td>
					  <td class="' . $class . '">' . strtoupper( $rs->fname ) . '&nbsp;<br>' . strtoupper( $rs->lname ) . '</td>
					  <td class="' . $class . '">' . strtoupper( $rs->fh_name ) . '</td>
					  <td class="' . $class . '">' . strtoupper ( $rs->address1 . " " . $rs->address2 ) . '</td>
					  <td class="' . $class . '">' . strtoupper ( $gender ) . '</td>
					  <td class="' . $class . '">' .  $rs->mobile  . '</td>
					  <td class="' . $class . '" >' . strtoupper ( $gname ) . '</td>
					  <td class="' . $class . '" >' . strtoupper ( $gaddress ) . '</td>
					  <td class="' . $class . '">' . strtoupper ( $rs->district_name ) . '</td>
					  <td class="' . $class . '">' . strtoupper ( $rs->tehsil_name ) . '</td>
					  <td class="' . $class . '">' . strtoupper ( $rs->block_name ) . '</td>
					  <td class="' . $class . '">' . strtoupper ( $rs->schemename ) . '</td>
					  <td class="' . $class . '" align="right">' . round ( $disbamount ) . '</td>
				      <td class="' . $class . '" align="right">' . date ( 'd/m/Y', strtotime ( $rs->cheque_date ) ). '</td>
					  <td class="' . $class . '" align="right">' . round ( abs ( $recovered_amount ) ). '</td>
					  <td class="' . $class . '" align="right">' . round ( abs ( $interest_amount ) ). '</td>
					  <td class="' . $class . '" align="right">' . round ( abs ( $ld_amount ) ). '</td>
					  <td class="' . $class . '" align="right">' . round ( abs ( $balamount ) ) . '</td>
	                  </tr>';
			
			$alldisbamount = $disbamount + $alldisbamount;
			$allrecovered_amount = $recovered_amount + $allrecovered_amount;
			$allinterest_amount = $interest_amount + $allinterest_amount;
			$allld_amount = $ld_amount + $allld_amount;
			$allbalance_amount = $balamount + $allbalance_amount;
			if($counter % 6 == '0') {
				// add a page
				$pdf->AddPage ();
				$output .= '</table>';
				ob_end_clean ();
				// print a block of text using Write()
				$pdf->writeHTML ( $output, true, 0, true, true );
				$output = '';
				$output .= $header_css;
				$output .= $header1;
			}
			$counter ++;
		}
		
		if($cl == 'header4_1')
			$cl = 'header4_2';
		else
			$cl = 'header4_1';
		
		$output .= '<tr style="background-color:white;"><td colspan="12"></td>
                    <td align="left" class="'.$cl.'"><strong>Grand Total</strong></td>';
		$output .= '<td align="right" class="'.$cl.'"><strong>'.round($alldisbamount).'</strong></td><td colspan="1" align = "right" class="'.$cl.'"></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allrecovered_amount).'</strong></td><td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allinterest_amount).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allld_amount).'</strong></td><td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allbalance_amount).'</strong></td>
					</tr>';
		$output .= '</table>';
		$pdf->AddPage ();
		ob_end_clean ();
		
		// print a block of text using Write()
		$pdf->writeHTML ( $output, true, 0, true, true );
		// Close and output PDF document
		$pdf->Output ( 'loanissuedetail_report_' . time () . '.pdf', 'I' );
	}
}
