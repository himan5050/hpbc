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
					<legend>General Loanee Details Report</legend>
					<table align="left" class="frmtbl">
						<tr>
							<td width="5%">&nbsp;</td>
							<td><b>District:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['district']); ?></div></td>
							<td><b>Tehsil:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['tehsil']); ?></div></td>
							<td><b>Panchayat:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['panchayat']); ?></div></td>
							<td><b>Sector:</b></td>
							<td><div class="maincol"><?php error_reporting(0); print drupal_render($form['sector9']); ?></div></td>
							<td><b>Scheme:</b></td>
							<td><div class="maincol" id="sector"><?php print drupal_render($form['scheme4']); ?></div>
							</td>
						</tr>
						<tr>
							<td width="5%">&nbsp;</td>
							<td><b>From:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['from_date']); ?></div></td>
							<td><b>To:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['to_date']); ?></div></td>
							<td><b>Account:</b></td>
							<td align="left"><div class="maincol"><?php print drupal_render($form['account']); ?></div></td>
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
$op = $_REQUEST ['op'];
if ($op == 'Generate Report') {
	$cond = '';
	$panchayat_join = '';
	$panchayat_table = '';
	$panchayat_header = '';
	$panchayat_row = '';
	$width = '27.5%';
	$district = isset ( $_REQUEST ['district'] ) ? $_REQUEST ['district'] : '';
	$tehsil = isset ( $_REQUEST ['tehsil'] ) ? $_REQUEST ['tehsil'] : '';
	$panchayat = isset ( $_REQUEST ['panchayat'] ) ? $_REQUEST ['panchayat'] : '';
	$sector = isset ( $_REQUEST ['sector9'] ) ? $_REQUEST ['sector9'] : '';
	$scheme = isset ( $_REQUEST ['scheme4'] ) ? $_REQUEST ['scheme4'] : '';
	$account = isset ( $_REQUEST ['account'] ) ? $_REQUEST ['account'] : '';
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
			$panchayat_join = 'INNER JOIN tbl_panchayt ON  (tbl_loanee_detail.panchayat=tbl_panchayt.panchayt_id)';
			$panchayat_table = 'tbl_panchayt.panchayt_name,';
			$panchayat_header = '<th >Panchayat</th>';
			$width = '34%';
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
		
		$pdfurl = $base_url . "/LoaneeIssueDetailReportpdf.php?op=loanissuedetail_report&district=$district&tehsil=$tehsil&panchayat=$panchayat&sector=$sector&scheme=$scheme&account=$account&from_date=$from_date&to_date=$to_date";
		$count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
		
		$res = pager_query ( $sql, 10, 0, $count_query );
		$pdfimage = $base_url . '/' . drupal_get_path ( 'theme', 'scst' ) . "/images/pdf_icon.gif";
		
		$output = '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%">
	<tr class="oddrow"><td align="left" colspan="15"><h2 style="text-align:left;">General Loanee Detail Report</h2></td>
	<tr>
	<td align="right"  colspan="15">
	<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	</table></div>';
		
		$output .= '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%" id="wrapper2"><tr><th colspan="7" style="text-align:center">Project Detail</th><th colspan="6" style="text-align:center">Loanee Detail</th><th colspan="3" style="text-align:center">Gaurantor Detail</th><th colspan="6" style="text-align:center">Loan Account Detail</th></tr>
               <tr>
   				<th width="5%">S. No.</th>
				<th >District</th>
				<th >Tehsil</th>
				<th >Block</th>
				' .$panchayat_header. '
				<th>Sector</th>
				<th>Scheme</th>
				<th>Account No.</th>
				<th>Name </th>
				<th>Sex</th>
				<th>Father Name</th>
           		<th>Contact No.</th>
				<th >Address</th>
				<th>Name </th>
				<th colspan="2">Address</th>
				<th>Loan Sanctioned Amount</th>
				<th>Loan Sanctioned Date </th>
				<th>Recovered Amount</th>
				<th>Interest Amount</th>
				<th>Overdue Charges</th>
				<th>Outstanding Balance</th>
				</tr>';
		
		if ($_REQUEST ['page']) {
			$counter = $_REQUEST ['page'] * 10;
		} else {
			$counter = 0;
		}
		// $counter =0;
		
		while ( $rs = db_fetch_object ( $res ) ) {
			$gender = getlookupName ( $rs->gender );
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
			
			$counter ++;
			if ($counter % 2 == 0) {
				$cla = "even";
			} else {
				$cla = "odd";
			}
			$accno = ($rs->account_id) ? $rs->account_id : 'N/A';
			$gname = ($g->gname) ? $g->gname : 'N/A';
			$gaddress = ($g->address) ? $g->address : 'N/A';
			$disbamount = ($d->disamount) ? $d->disamount : 'N/A';
			$panchayat_name = isset($rs->panchayt_name) ? $rs->panchayt_name : 'URBAN';
			if($panchayat) {
				$panchayat_row = '<td >' . ucwords ( $panchayat_name ) . '</td>';
			}else {
				$panchayat_row = '';
			}
			$output .= '<tr class="' . $cla . '">
					 <td class="center" width="10%">' . $counter . '</td> 
					 <td >' . ucwords ( $rs->district_name ) . '</td>
					 <td >' . ucwords ( $rs->tehsil_name ) . '</td>
					 <td >' . ucwords ( $rs->block_name ) . '</td>
					 '.$panchayat_row.'
					 <td >' . ucwords ( $rs->sector_name ) . '</td>
					 <td >' . ucwords ( $rs->schemename ) . '</td>
					 <td >' . $accno . '</td>
					 <td >' . ucwords ( $rs->fname ) . '&nbsp;' . ucwords ( $rs->lname ) . '</td>
					 <td align="right">' . $gender . '</td>
					 <td >' . ucwords ( $rs->fh_name ) . '</td>
					 <td >' . ucwords ( $rs->mobile ) . '</td>
					 <td align="right">' . ucwords ( $rs->address1 . " " . $rs->address2 ) . '</td>
					 <td align="right">' . $gname . '</td>
					 <td align="right" colspan="2">' . ucwords ( $gaddress ) . '</td>
					 <td >' . round ( $disbamount ) . '</td>
					 <td align="right">' . date ( 'd/m/Y', strtotime ( $rs->cheque_date ) ) . '</td>
					 <td align="right">' . round ( abs ( $recovered_amount ) ) . '</td>
					 <td align="right">' . round ( abs ( $interest_amount ) ) . '</td>
					 <td align="right">' . round ( abs ( $ld_amount ) ) . '</td>
					 <td align="right">' . round ( abs ( $balamount ) ) . '</td>
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