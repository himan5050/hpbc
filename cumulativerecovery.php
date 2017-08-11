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
if ($_REQUEST ['op'] == 'cumulativerecovery_report') {
	$cond = '';
	$from_date = isset($_REQUEST ['from_date']) ? $_REQUEST ['from_date'] : '';
	$to_date = isset($_REQUEST ['to_date']) ? $_REQUEST ['to_date'] : '';
	
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
	$output .='<table cellpadding="0" cellspacing="0" border="0">
			   <tr><td class="header_report" width="68%">HIMACHAL BACKWARD CLASSES FINANCE AND DEVELOPMENT CORPORATION KANGRA (H.P.)</td><td class="header_report" width="30%" align="right">Sector-Wise Cumulative Recovery Statement ( ' .date('d/m/Y', strtotime($from_date)). ' To '.date('d/m/Y', strtotime($to_date)).' ) </td></tr><tr><td><strong>Date  : '.date('d/m/y').'</strong></td></tr><tr><td colspan="2">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr></table><br/>';
	
	if ($from_date == '' || $to_date == '') {
		form_set_error ( 'form', 'Please select period to generate report.' );
	} else if (strtotime ( $from_date ) > strtotime ( $to_date )) {
		form_set_error ( 'form', 'Please select dates properly.' );
	} else {
		if ($from_date && $to_date) {
			$cond .= ' and tbl_loan_disbursement.cheque_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
			$_REQUEST ['page'] = 0;
		}
		
		$sql = "SELECT sector_id, sector_name from tbl_sectors ORDER BY sector_name";
		
		$output .= '<table cellpadding="2" cellspacing="2" id="wrapper" class="tbl_border">
					<tr><td align="center" colspan="2" style="border:1px solid #fff;" width="12.5%" class="header2"></td><td class="header2" colspan="9" style="text-align:center">DEMAND</td><td class="header2" colspan="4" style="text-align:center">RECOVERY</td></tr>
                <tr>
				<td class="header2" colspan="2"></td>
				<td class="header2" colspan="3" style="text-align:center">Cumulative Demand upto Previous Month (Including Overdue)</td>
				<td class="header2" colspan="3" style="text-align:center">Demand Due for Current Month</td>
				<td class="header2" colspan="3" style="text-align:center">Total Demand Due (Cumulative Demand Till This Month)</td>
				<td class="header2" colspan="4"></td>
				</tr>';
		$output .= '<tr><td width="4.5%" class="header2" colspan="1" style="text-align:center">Sr No.</td><td width="8.0%" class="header2" colspan="1" style="text-align:center">Name of Sector</td>
				<td class="header2" colspan="1" style="text-align:center">NBCFDC Share</td><td class="header2" colspan="1" style="text-align:center">HBCFDC Share</td><td class="header2" colspan="1" style="text-align:center">Total Demand</td>
				<td class="header2" colspan="1" style="text-align:center">NBCFDC Share</td><td class="header2" colspan="1" style="text-align:center">HBCFDC Share</td><td class="header2" colspan="1" style="text-align:center">Total Demand</td>
				<td class="header2" colspan="1" style="text-align:center">NBCFDC Share</td><td class="header2" colspan="1" style="text-align:center">HBCFDC Share</td><td class="header2" colspan="1" style="text-align:center">Total Demand</td>
				<td class="header2">During The Month</td>
				<td class="header2">Cumulative Upto the Month</td>
				<td class="header2">Rate of Recovery</td>
				<td class="header2">Overdues At The End of The Month</td>
				</tr>';
		
		
		
		$counter = 1;
		$alldemandNBCFDC = 0;
		$alldemandHBCFDC = 0;
		$allcurrentMonthDemandHBCFDC = 0;
		$allcurrentMonthDemandNBCFDC = 0;
		$allrecovery = 0;
		$allcurrentMonthRecovery = 0;
		$res = db_query ( $sql );
		while ( $rs = db_fetch_object ( $res ) ) {
			//-------Business Logic-----
			$sector_id = isset($rs->sector_id) ? $rs->sector_id : '0';
			if ($sector_id != '0') {
				$schemes_under_sector = getSchemeBySector($sector_id);
			}
			$demandNBCFDC = 0;
			$demandHBCFDC = 0;
			$currentMonthDemandNBCFDC = 0;
			$currentMonthDemandHBCFDC = 0;
			$recovery = 0;
			$currentMonthRecovery = 0;
			foreach ($schemes_under_sector as $key => $value) {
				$data = getDemand($key, $from_date, $to_date);
				$data1 = getRecovery($key, $from_date, $to_date);
				$demandNBCFDC = $data['previousMonthDemand']['NBCFDC'] + $demandNBCFDC;
				$demandHBCFDC = $data['previousMonthDemand']['HBCFDC'] + $demandHBCFDC;
				$currentMonthDemandNBCFDC = $data['currentMonthDemand']['NBCFDC'] + $currentMonthDemandNBCFDC;
				$currentMonthDemandHBCFDC = $data['currentMonthDemand']['HBCFDC'] + $currentMonthDemandHBCFDC;
				
				$recovery = $data1['previousMonthRecovery'] + $recovery;
				$currentMonthRecovery = $data1['currentMonthRecovery'] + $currentMonthRecovery;
				
			}
			//Total
			$totalDemandNBCFDC = $demandNBCFDC + $currentMonthDemandNBCFDC;
			$totalDemandHBCFDC = $demandHBCFDC + $currentMonthDemandHBCFDC;
			$totalDemand = $totalDemandHBCFDC + $totalDemandNBCFDC;
			$totalRecovery = $recovery + $currentMonthRecovery;
			
			if ($counter % 2 == 0) {
				$class = 'header4_1';
			} else {
				$class = 'header4_2';
			}
			$output .= '<tr>
					 	<td class="' . $class . '" width="4.5%">' . $counter . '</td>
					 	<td class="' . $class . '" width="8.0%">' . ucwords ( $rs->sector_name ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $demandNBCFDC/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $demandHBCFDC/100000, '2' ) . '</td>
					    <td class="' . $class . '" align ="right">' . round ( ($demandHBCFDC+$demandNBCFDC)/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $currentMonthDemandNBCFDC/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $currentMonthDemandHBCFDC/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( ($currentMonthDemandHBCFDC + $currentMonthDemandNBCFDC)/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $totalDemandNBCFDC/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $totalDemandHBCFDC/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $totalDemand/100000, '2' ) . '</td>
  						<td class="' . $class . '" align ="right">' . round ( $currentMonthRecovery/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $recovery/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( ($recovery/$totalDemand) * 100, '2' ) . '%</td>
						<td class="' . $class . '" align ="right">' . round ( ($totalDemand - $recovery)/100000, '2' ) . '</td>
	            	   </tr>';
			$counter++;
			
			// Grand Total
			$alldemandNBCFDC = $demandNBCFDC + $alldemandNBCFDC;
			$alldemandHBCFDC = $demandHBCFDC + $alldemandHBCFDC;
			$allcurrentMonthDemandNBCFDC = $currentMonthDemandNBCFDC + $allcurrentMonthDemandNBCFDC;
			$allcurrentMonthDemandHBCFDC = $currentMonthDemandHBCFDC + $allcurrentMonthDemandHBCFDC;
			$allrecovery = $recovery + $allrecovery;
			$allcurrentMonthRecovery = $currentMonthRecovery + $allcurrentMonthRecovery;
			
		}
		
		//All Total
		$allNBCFDC = $alldemandNBCFDC + $allcurrentMonthDemandNBCFDC;
		$allHBCFDC = $alldemandHBCFDC + $allcurrentMonthDemandNBCFDC;
		$allTotal = $allNBCFDC + $allHBCFDC;
		
		
		if($cl == 'header4_1')
			$cl = 'header4_2';
		else
			$cl = 'header4_1';
				$output .= '<tr style="background-color:white;"><td colspan="1"></td>
                    <td align="left" class="'.$cl.'"><strong>Grand Total</strong></td>';
				$output .= '<td align="right" class="'.$cl.'"><strong>'.round($alldemandNBCFDC/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($alldemandHBCFDC/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round(($alldemandHBCFDC + $alldemandNBCFDC)/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allcurrentMonthDemandNBCFDC/100000,'2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allcurrentMonthDemandHBCFDC/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round(($allcurrentMonthDemandHBCFDC + $allcurrentMonthDemandNBCFDC)/100000,'2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allNBCFDC/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allHBCFDC/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allTotal/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allcurrentMonthRecovery/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allrecovery/100000, '2').'</strong></td>
					</tr>';
		
			
				$output .= '</table>';
				ob_end_clean ();
				
				// print a block of text using Write()
				$pdf->writeHTML ( $output, true, 0, true, true );
				// Close and output PDF document
				$pdf->Output ( 'sectorwisecumulative_' . time () . '.pdf', 'I' );
	}
}
