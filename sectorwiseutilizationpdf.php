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
if ($_REQUEST ['op'] == 'sectorwiseutilization_report') {
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
			   <tr><td class="header_report" width="68%">HIMACHAL BACKWARD CLASSES FINANCE AND DEVELOPMENT CORPORATION KANGRA (H.P.)</td><td class="header_report" width="30%" align="right">Sector-Wise Utilization Certificate ( ' .date('d/m/Y', strtotime($from_date)). ' To '.date('d/m/Y', strtotime($to_date)).' ) </td></tr><tr><td><strong>Date  : '.date('d/m/y').'</strong></td></tr><tr><td colspan="2">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr></table><br/>';
	
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
	
	   	if ($from_date == '' || $to_date == '') {
			form_set_error ( 'form', 'Please select period to generate report.' );
		} else if (strtotime ( $from_date ) > strtotime ( $to_date )) {
			form_set_error ( 'form', 'Please select dates properly.' );
		}
		
		
		$output .= '<table cellpadding="2" cellspacing="2" id="wrapper" class="tbl_border">
					<tr><td class="header2" colspan="1" style="text-align:center"></td><td class="header2" colspan="1" style="text-align:center"></td><td class="header2" colspan="8" style="text-align:center">CURRENT UTILIZATION W.E.F. ( '.date('d-m-Y', strtotime($from_date)).' TO '.date('d-m-Y', strtotime($to_date)).' )</td><td class="header2" colspan="8" style="text-align:center">CUMULATIVE UTILIZATION AS ON '.date('d-m-Y', strtotime($to_date)).'</td></tr>
					<tr><td class="header2" colspan="1" style="text-align:center"></td><td class="header2" colspan="1" style="text-align:center"></td><td class="header2" colspan="2" style="text-align:center">UPTO 2 Lac</td><td class="header2" colspan="2" style="text-align:center">ABOVE 2 Lac</td><td class="header2" colspan="2" style="text-align:center">TOTAL</td><td class="header2" colspan="2" style="text-align:center">GENDER</td>
					<td class="header2" colspan="2" style="text-align:center">UPTO 2 Lac</td><td class="header2" colspan="2" style="text-align:center">ABOVE 2 Lac</td><td class="header2" colspan="2" style="text-align:center">TOTAL</td><td class="header2" colspan="2" style="text-align:center">GENDER</td></tr>
               <tr>
   				<td width="5.6%" class="header2">S. No.</td>
				<td class="header2">Name of Sector</td>
				<td class="header2">AMOUNT</td>
				<td class="header2">BENEF</td>
				<td class="header2">AMOUNT</td>
				<td class="header2">BENEF</td>
				<td class="header2">AMOUNT</td>
				<td class="header2">BENEF</td>
				<td class="header2">MALE</td>
				<td class="header2">FEMALE</td>
				<td class="header2">AMOUNT</td>
				<td class="header2">BENEF</td>
				<td class="header2">AMOUNT</td>
				<td class="header2">BENEF</td>
				<td class="header2">AMOUNT</td>
				<td class="header2">BENEF</td>
				<td class="header2">MALE</td>
				<td class="header2">FEMALE</td>
				</tr>';
		
		$res = db_query ( $sql );
		$counter = 1;
		$allupto2Lac_amount = 0;
		$allupto2Lac_benef = 0;
		$allabove2Lac_amount = 0;
		$allabove2Lac_benef = 0;
		$allmale_count = 0;
		$allfemale_count = 0;
		$allupto2Lac_amount1 = 0;
		$allupto2Lac_benef1 = 0;
		$allabove2Lac_amount1 = 0;
		$allabove2Lac_benef1 = 0;
		$allmale_count1 = 0;
		$allfemale_count1 = 0;
		while ( $rs = db_fetch_object ( $res ) ) {
			//-------Business Logic-----
			$sector_id = isset($rs->sector_id) ? $rs->sector_id : '0';
			if ($sector_id != '0') {
				$schemes_under_sector = getSchemeBySector($sector_id);
			}
			
			$upto2Lac_amount = 0;
			$upto2Lac_benef = 0;
			$above2Lac_amount = 0;
			$above2Lac_benef = 0;
			$male_count = 0;
			$female_count = 0;
			$upto2Lac_amount1 = 0;
			$upto2Lac_benef1 = 0;
			$above2Lac_amount1 = 0;
			$above2Lac_benef1 = 0;
			$male_count1 = 0;
			$female_count1 = 0;
			$year = date('Y', strtotime($from_date));
			foreach ($schemes_under_sector as $key => $value) {
				$data = loanUnderSchemeWithDates($key, $from_date, $to_date);
				$data1 = loanUnderSchemeAsOnDate($key, $year, $to_date);
				foreach($data as $key => $value) {
					if ($key == 'upto_2lac') {
						$upto2Lac_amount = $value['amount'] + $upto2Lac_amount;
						$upto2Lac_benef = $value['benef'] + $upto2Lac_benef;
					} else if ($key == 'above_2lac') {
						$above2Lac_amount = $value['amount'] + $above2Lac_amount;
						$above2Lac_benef = $value['benef'] + $above2Lac_benef;
					} else if ($key == 'gender') {
						$male_count = $value['male'] + $male_count;
						$female_count = $value['female'] + $female_count;
					}
				}
				foreach($data1 as $key => $value) {
					if ($key == 'upto_2lac') {
						$upto2Lac_amount1 = $value['amount'] + $upto2Lac_amount1;
						$upto2Lac_benef1 = $value['benef'] + $upto2Lac_benef1;
					} else if ($key == 'above_2lac') {
						$above2Lac_amount1 = $value['amount'] + $above2Lac_amount1;
						$above2Lac_benef1 = $value['benef'] + $above2Lac_benef1;
					} else if ($key == 'gender') {
						$male_count1 = $value['male'] + $male_count1;
						$female_count1 = $value['female'] + $female_count1;
					}
				}
			}
			// Total
			$total_amount = $upto2Lac_amount + $above2Lac_amount;
			$total_benef = $upto2Lac_benef + $above2Lac_benef;
			$total_amount1 = $upto2Lac_amount1 + $above2Lac_amount1;
			$total_benef1 = $upto2Lac_benef1 + $above2Lac_benef1;
			
			if ($counter % 2 == 0) {
				$class = 'header4_1';
			} else {
				$class = 'header4_2';
			}
			
			$output .= '<tr>
					 	<td class="' . $class . '">' . $counter . '</td>
					 	<td class="' . $class . '">' . ucwords ( $rs->sector_name ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $upto2Lac_amount/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $upto2Lac_benef ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $above2Lac_amount/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $above2Lac_benef ) . '</td>
					    <td class="' . $class . '" align ="right">' . round ( $total_amount/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $total_benef ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $male_count ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $female_count ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $upto2Lac_amount1/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $upto2Lac_benef1 ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $above2Lac_amount1/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $above2Lac_benef1 ) . '</td>
					    <td class="' . $class . '" align ="right">' . round ( $total_amount1/100000, '2' ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $total_benef1 ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $male_count1 ) . '</td>
						<td class="' . $class . '" align ="right">' . round ( $female_count1 ) . '</td>
	            	   </tr>';
			$counter ++;
			
			// Grand Total
			$allupto2Lac_amount = $upto2Lac_amount + $allupto2Lac_amount;
			$allupto2Lac_benef = $allupto2Lac_benef + $upto2Lac_benef;
			$allabove2Lac_amount = $above2Lac_amount + $allabove2Lac_amount;
			$allabove2Lac_benef = $above2Lac_benef + $allabove2Lac_benef;
			$allmale_count = $male_count + $allmale_count;
			$allfemale_count = $female_count + $allfemale_count;
			$allupto2Lac_amount1 = $upto2Lac_amount1 + $allupto2Lac_amount1;
			$allupto2Lac_benef1 = $allupto2Lac_benef1 + $upto2Lac_benef1;
			$allabove2Lac_amount1 = $above2Lac_amount1 + $allabove2Lac_amount1;
			$allabove2Lac_benef1 = $above2Lac_benef1 + $allabove2Lac_benef1;
			$allmale_count1 = $male_count1 + $allmale_count1;
			$allfemale_count1 = $female_count1 + $allfemale_count1;
			
		}
		//All Total
		$alltotal_amount = $allupto2Lac_amount + $allabove2Lac_amount;
		$alltotal_benef = $allupto2Lac_benef + $allabove2Lac_benef;
		$alltotal_amount1 = $allupto2Lac_amount1 + $allabove2Lac_amount1;
		$alltotal_benef1 = $allupto2Lac_benef1 + $allabove2Lac_benef1;
		
		
		if($cl == 'header4_1')
			$cl = 'header4_2';
		else
			$cl = 'header4_1';
		$output .= '<tr style="background-color:white;"><td colspan="1"></td>
                    <td align="left" class="'.$cl.'"><strong>Grand Total</strong></td>';
		$output .= '<td align="right" class="'.$cl.'"><strong>'.round($allupto2Lac_amount/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allupto2Lac_benef).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allabove2Lac_amount/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allabove2Lac_benef).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($alltotal_amount/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($alltotal_benef).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allmale_count).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allfemale_count).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allupto2Lac_amount1/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allupto2Lac_benef1).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allabove2Lac_amount1/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allabove2Lac_benef1).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($alltotal_amount1/100000, '2').'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($alltotal_benef1).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allmale_count1).'</strong></td>
					<td colspan="1" align = "right" class="'.$cl.'"><strong>'.round($allfemale_count1).'</strong></td>
					</tr>';
		$output .= '</table>';
		ob_end_clean ();
		
		// print a block of text using Write()
		$pdf->writeHTML ( $output, true, 0, true, true );
		// Close and output PDF document
		$pdf->Output ( 'sectorwise_utilization_certificate_' . time () . '.pdf', 'I' );
	}
}
