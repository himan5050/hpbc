<style type="text/css">
.container-inline-date .form-item, .container-inline-date .form-item input {
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
.maincoldate{margin-top:30px;}
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Sector-wise Cumulative Recovery Statement</legend>
	
    <table align="left" class="frmtbl">
    <tr><td width="5%">&nbsp;</td>
	 

  	  <td><b>From Date:<font color="#FF0000">*</font></b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><b>To Date:<font color="#FF0000">*</font></b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  <td align="right">
  <div><?php print drupal_render($form); ?></div></td><td width="5%">&nbsp;</td></tr>
  
  </table></fieldset>
  </td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date']==''){
  form_set_error('form','Please enter any one of search field..');
}		
	
else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  form_set_error('form','Please enter From Date');
}
else {
	$cond = '';
	$from_date = date ( 'Y-m-d', strtotime ( $_REQUEST ['from_date'] ['date'] ) );
	$to_date = date ( 'Y-m-d', strtotime ( $_REQUEST ['to_date'] ['date'] ) );
	
	if ($from_date && $to_date) {
		$cond .= ' and tbl_loan_disbursement.cheque_date BETWEEN "' . $from_date . '" AND "' . $to_date . '"';
		$_REQUEST ['page'] = 0;
	}
	
	$sql = "SELECT sector_id, sector_name from tbl_sectors ORDER BY sector_name";
	
	$pdfurl = $base_url . "/cumulativerecovery.php?op=cumulativerecovery_report&from_date=$from_date&to_date=$to_date";
	$count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
	
	$res = pager_query ( $sql, 10, 0, $count_query );
	$pdfimage = $base_url . '/' . drupal_get_path ( 'theme', 'scst' ) . "/images/pdf_icon.gif";
	
	
	$output = '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%">
	<tr class="oddrow"><td align="left" colspan="15"><h2 style="text-align:left;">Sector-Wise Cumulative Recovery Statement</h2></td>
	<tr>
	<td align="right"  colspan="15">
	<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	</table></div>';
	
	$output .= '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%" id="wrapper2">
					<tr><th colspan="2" style="text-align:center"></th><th colspan="9" style="text-align:center">DEMAND</th><th colspan="4" style="text-align:center">RECOVERY</th></tr>
                <tr>
				<th colspan="2"></th>
				<th colspan="3" style="text-align:center">Cumulative Demand upto Previous Month (Including Overdue)</th>
				<th colspan="3" style="text-align:center">Demand Due for Current Month</th>
				<th colspan="3" style="text-align:center">Total Demand Due (Cumulative Demand Till This Month)</th>
				<th colspan="4"></th>
				</tr>';
	$output .= '<tr><th colspan="1" style="text-align:center">Sr No.</th><th colspan="1" style="text-align:center">Name of Sector</th>
				<th colspan="1" style="text-align:center">NBCFDC Share</th><th colspan="1" style="text-align:center">HBCFDC Share</th><th colspan="1" style="text-align:center">Total Demand</th>
				<th colspan="1" style="text-align:center">NBCFDC Share</th><th colspan="1" style="text-align:center">HBCFDC Share</th><th colspan="1" style="text-align:center">Total Demand</th>
				<th colspan="1" style="text-align:center">NBCFDC Share</th><th colspan="1" style="text-align:center">HBCFDC Share</th><th colspan="1" style="text-align:center">Total Demand</th>
				<th>During The Month</th>
				<th>Cumulative Upto the Month</th>
				<th>Rate of Recovery</th>
				<th>Overdues At The End of The Month</th>
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
		
		$counter ++;
		if ($counter % 2 == 0) {
			$cla = "even";
		} else {
			$cla = "odd";
		}
		$output .= '<tr class="' . $cla . '">
					 	<td class="center" width="5%">' . $counter . '</td>
					 	<td width="10%">' . ucwords ( $rs->sector_name ) . '</td>
						<td align ="right">' . round ( $demandNBCFDC/100000, '2' ) . '</td>
						<td align ="right">' . round ( $demandHBCFDC/100000, '2' ) . '</td>
					    <td align ="right">' . round ( ($demandHBCFDC+$demandNBCFDC)/100000, '2' ) . '</td>
						<td align ="right">' . round ( $currentMonthDemandNBCFDC/100000, '2' ) . '</td>
						<td align ="right">' . round ( $currentMonthDemandHBCFDC/100000, '2' ) . '</td>
						<td align ="right">' . round ( ($currentMonthDemandHBCFDC + $currentMonthDemandNBCFDC)/100000, '2' ) . '</td>
						<td align ="right">' . round ( $totalDemandNBCFDC/100000, '2' ) . '</td>
						<td align ="right">' . round ( $totalDemandHBCFDC/100000, '2' ) . '</td>
						<td align ="right">' . round ( $totalDemand/100000, '2' ) . '</td>
  						<td align ="right">' . round ( $currentMonthRecovery/100000, '2' ) . '</td>
						<td align ="right">' . round ( $recovery/100000, '2' ) . '</td>
						<td align ="right">' . round ( ($recovery/$totalDemand) * 100, '2' ) . '%</td>
						<td align ="right">' . round ( ($totalDemand - $recovery)/100000, '2' ) . '</td>
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