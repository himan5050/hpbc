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
					<legend>Fund Utilization Report</legend>
					<table align="left" class="frmtbl">
						<tr>
							<td width="5%">&nbsp;</td>
							<td><b>District:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['district']); ?></div></td>
							<td><b>Tehsil:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['tehsil']); ?></div></td>
							<td><b>Panchayat:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['panchayat']); ?></div></td>
						</tr>
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
	$district = isset ( $_REQUEST ['district'] ) ? $_REQUEST ['district'] : '';
	$tehsil = isset ( $_REQUEST ['tehsil'] ) ? $_REQUEST ['tehsil'] : '';
	$panchayat = isset ( $_REQUEST ['panchayat'] ) ? $_REQUEST ['panchayat'] : '';
	$from_date = date ( 'Y-m-d', strtotime ( $_REQUEST ['from_date'] ['date'] ) );
	$to_date = date ( 'Y-m-d', strtotime ( $_REQUEST ['to_date'] ['date'] ) );
	
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
				INNER JOIN tbl_panchayt ON  (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat)
				INNER JOIN tbl_sectors ON  (tbl_sectors.sector_id=tbl_scheme_master.sector)
				INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
				WHERE 1=1 $cond GROUP BY `tbl_loan_detail`.scheme_name ORDER by `tbl_loan_disbursement`.cheque_date DESC";
		
		$pdfurl = $base_url . "/fundutilizationreportpdf.php?op=fundutilization_report&district=$district&tehsil=$tehsil&panchayat=$panchayat&from_date=$from_date&to_date=$to_date";
		$count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
		
		$res = pager_query ( $sql, 10, 0, $count_query );
		$pdfimage = $base_url . '/' . drupal_get_path ( 'theme', 'scst' ) . "/images/pdf_icon.gif";
		
		$output = '<div class="listingpage_scrolltable">
    			   <table cellpadding="2" cellspacing="1" border="0" width="100%">
					<tr class="oddrow">
					<td align="left" colspan="15">
						<h2 style="text-align:left;">Fund Utilization Report</h2></td>
					<tr>
					<td align="right"  colspan="15">
						<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
					</tr>
				   </table></div>';
		
		$output .= '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%" id="wrapper2">
               <tr>
   				<th width="5%">S. No.</th>
				<th width="15%">Sector</th>
				<th width="15%">Scheme</th>
				<th >No of Units</th>
				<th>Loan Accounts</th>				
				<th >Project Cost (Rs. In Lakh)</th>
				<th width="10%">NBCFDC Share (Rs. In Lakh)</th>
				<th width="10%">HBCFDC Share (Rs. In Lakh)</th>
				<th width="10%">Promoter Share (Rs. In Lakh)</th>
				</tr>';
		
		if ($_REQUEST ['page']) {
			$counter = $_REQUEST ['page'] * 10;
		} else {
			$counter = 0;
		}
		
		while ( $rs = db_fetch_object ( $res ) ) {
			//Calculate number of units
			$sql_loan_count = "SELECT COUNT(DISTINCT loan_id) as unit
							   FROM `tbl_loan_detail` 
							   INNER JOIN tbl_loanee_detail ON  (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
							   INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
                               WHERE 1=1 $cond and scheme_name = '".$rs->loan_scheme_id."'";
			$sql_loan_count1 = db_query($sql_loan_count);
			$slc = db_fetch_object($sql_loan_count1);
			
			//get account ids
			$sql_account_id = "SELECT DISTINCT account_id
							   FROM `tbl_loan_detail`
							   INNER JOIN tbl_loanee_detail ON  (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
							   INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
							   WHERE 1=1 $cond and scheme_name = '".$rs->loan_scheme_id."'";
			$sql_account_id1 = db_query($sql_account_id);
			$account_ids = '';
			$first = true;
			while ( $sai = db_fetch_object ( $sql_account_id1 ) ) {
				if ($first) {
					$account_ids .= $sai->account_id;
					$first = false;
				}else{
					$account_ids .= ', '.$sai->account_id;
				}
			}
			
			// Get sanctioned amount.
			$sql_sanc_amt = "SELECT SUM(amount) as sanctioned_amount
			FROM `tbl_loan_detail`
			INNER JOIN tbl_loanee_detail ON  (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
			INNER JOIN tbl_loan_disbursement ON  (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id)
			WHERE 1=1 $cond and scheme_name = '".$rs->loan_scheme_id."'";
			$sql_sanc_amt1 = db_query($sql_sanc_amt);
			$ssa = db_fetch_object($sql_sanc_amt1);
			$sanctioned_amount = $ssa->sanctioned_amount/100000;
			
			// GET NBCFDC, HBCFDC and Promoter Share.
			$sql_share = "SELECT apex_share,corp_share,promoter_share FROM `tbl_scheme_master` where loan_scheme_id = '". $rs->loan_scheme_id. "'";
			$sql_share1 = db_query($sql_share);
			$ss = db_fetch_object($sql_share1);
			$NBCFDC_amount = $ssa->sanctioned_amount * $ss->apex_share / 100;
			$nbcfdc_amount = $NBCFDC_amount / 100000;
			$HBCFDC_amount = $ssa->sanctioned_amount * $ss->corp_share / 100;
			$hbcfdc_amount = $HBCFDC_amount / 100000;
			$PROMOTER_amount = $ssa->sanctioned_amount * $ss->promoter_share / 100;
			$promoter_amount = $PROMOTER_amount / 100000;
			
			
				
			$counter ++;
			if ($counter % 2 == 0) {
				$cla = "even";
			} else {
				$cla = "odd";
			}
			$output .= '<tr class="' . $cla . '">
					 <td class="center" width="5%">' . $counter . '</td>
					 <td >' . ucwords ( $rs->sector_name ) . '</td>
					 <td >' . ucwords ( $rs->scheme_name ) . '</td>
					 <td align="right">' . $slc->unit . '</td>
					 <td >' . $account_ids . '</td>
					 <td align="right">' . round($sanctioned_amount,'2') . '</td>
					 <td align="right">' . round($nbcfdc_amount,'2') . '</td>
					 <td align="right">' . round($hbcfdc_amount,'2') . '</td>
					 <td align="right">' . round($promoter_amount,'2') . '</td>
	            	</tr>';
		}
		
		if ($counter > 0) {
			$output .= '</table></div>';
			echo $output .= theme ( 'pager', NULL, 10, 0 );
		} else {
			echo '<font color="red"><b>No Record found...</b></font>';
		}
	}
}
?>