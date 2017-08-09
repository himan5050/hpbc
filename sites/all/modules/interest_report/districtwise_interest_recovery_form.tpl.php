<style>
.container-inline-date .form-item, .container-inline-date .form-item input
	{
	width: 100px;
	display: inline;
}

input[type="text"] {
	width: 100px;
	height: 18px;
	margin: 0;
	padding: 2px;
	vertical-align: middle;
	font-family: sans-serif;
	font-size: 14px;
	border: #BCBCBC 1px solid;
}

.maincol select {
	width: 100px;
}
</style>

<div id="rec_participant">
	<table width="100%" cellpadding="2" cellspacing="0" border="0"
		id="wrapper">
		<tr>
			<td align="left" class="tdform-width"><fieldset>
					<legend>Sector-wise Utilization Certificate</legend>
					<table align="left" class="frmtbl">
						<tr>
							<td width="5%">&nbsp;</td>
							<td><b>From:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['from_date']); ?></div></td>
							<td><b>To:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['to_date']); ?></div></td>
							<td colspan="5" align="right">
								<div style="margin-right: 90px;"><?php print drupal_render($form); ?></div>
							</td>
						</tr>
					</table>
				</fieldset></td>
		</tr>
	</table>
</div>


<?php
global $base_url;
$op = $_REQUEST['op'];
if ($op == 'Generate Report') {
	$cond = '';
	$from_date = date ( 'Y-m-d', strtotime ( $_REQUEST ['from_date'] ['date'] ) );
	$to_date = date ( 'Y-m-d', strtotime ( $_REQUEST ['to_date'] ['date'] ) );
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
		
		$pdfurl = $base_url . "/sectorwiseutilizationpdf.php?op=sectorwiseutilization_report&from_date=$from_date&to_date=$to_date";
		$count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
		
		$res = pager_query ( $sql, 10, 0, $count_query );
		$pdfimage = $base_url . '/' . drupal_get_path ( 'theme', 'scst' ) . "/images/pdf_icon.gif";
		
		$output = '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%">
	<tr class="oddrow"><td align="left" colspan="15"><h2 style="text-align:left;">Sector-Wise Utilization Certificate</h2></td>
	<tr>
	<td align="right"  colspan="15">
	<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	</table></div>';
		
		$output .= '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%" id="wrapper2">
					<tr><th colspan="1" style="text-align:center"></th><th colspan="1" style="text-align:center"></th><th colspan="8" style="text-align:center">CURRENT UTILIZATION W.E.F. ( '.date('d-m-Y', strtotime($from_date)).' TO '.date('d-m-Y', strtotime($to_date)).' )</th><th colspan="8" style="text-align:center">CUMULATIVE UTILIZATION AS ON '.date('d-m-Y', strtotime($to_date)).'</th></tr>
					<tr><th colspan="1" style="text-align:center"></th><th colspan="1" style="text-align:center"></th><th colspan="2" style="text-align:center">UPTO 2 Lac</th><th colspan="2" style="text-align:center">ABOVE 2 Lac</th><th colspan="2" style="text-align:center">TOTAL</th><th colspan="2" style="text-align:center">GENDER</th>
					<th colspan="2" style="text-align:center">UPTO 2 Lac</th><th colspan="2" style="text-align:center">ABOVE 2 Lac</th><th colspan="2" style="text-align:center">TOTAL</th><th colspan="2" style="text-align:center">GENDER</th></tr>
               <tr>
   				<th width="5%">S. No.</th>
				<th>Name of Sector</th>
				<th >AMOUNT</th>
				<th >BENEF</th>
				<th >AMOUNT</th>
				<th>BENEF</th>
				<th>AMOUNT</th>
				<th>BENEF</th>
				<th>MALE</th>
				<th>FEMALE</th>
				<th >AMOUNT</th>
				<th >BENEF</th>
				<th >AMOUNT</th>
				<th>BENEF</th>
				<th>AMOUNT</th>
				<th>BENEF</th>
				<th>MALE</th>
				<th>FEMALE</th>
				</tr>';
		
		if ($_REQUEST ['page']) {
			$counter = $_REQUEST ['page'] * 10;
		} else {
			$counter = 0;
		}
		// $counter =0;
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
			// Grand Total
			$total_amount = $upto2Lac_amount + $above2Lac_amount;
			$total_benef = $upto2Lac_benef + $above2Lac_benef;
			$total_amount1 = $upto2Lac_amount1 + $above2Lac_amount1;
			$total_benef1 = $upto2Lac_benef1 + $above2Lac_benef1;
			
			$counter ++;
			if ($counter % 2 == 0) {
				$cla = "even";
			} else {
				$cla = "odd";
			}
			$output .= '<tr class="' . $cla . '">
					 	<td class="center" width="10%">' . $counter . '</td>
					 	<td >' . ucwords ( $rs->sector_name ) . '</td>
						<td align ="right">' . round ( $upto2Lac_amount/100000, '2' ) . '</td>
						<td align ="right">' . round ( $upto2Lac_benef ) . '</td>
						<td align ="right">' . round ( $above2Lac_amount/100000, '2' ) . '</td>
						<td align ="right">' . round ( $above2Lac_benef ) . '</td>
					    <td align ="right">' . round ( $total_amount/100000, '2' ) . '</td>
						<td align ="right">' . round ( $total_benef ) . '</td>
						<td align ="right">' . round ( $male_count ) . '</td>
						<td align ="right">' . round ( $female_count ) . '</td>
						<td align ="right">' . round ( $upto2Lac_amount1/100000, '2' ) . '</td>
						<td align ="right">' . round ( $upto2Lac_benef1 ) . '</td>
						<td align ="right">' . round ( $above2Lac_amount1/100000, '2' ) . '</td>
						<td align ="right">' . round ( $above2Lac_benef1 ) . '</td>
					    <td align ="right">' . round ( $total_amount1/100000, '2' ) . '</td>
						<td align ="right">' . round ( $total_benef1 ) . '</td>
						<td align ="right">' . round ( $male_count1 ) . '</td>
						<td align ="right">' . round ( $female_count1 ) . '</td>
	            	   </tr>';
		}
		
		if ($counter > 0) {
			$output .='</table></div>';
			echo $output .= theme('pager', NULL, 10, 0);
		} else {
			echo '<font color="red"><b>No Record found...</b></font>';
		}
	}
}
?>