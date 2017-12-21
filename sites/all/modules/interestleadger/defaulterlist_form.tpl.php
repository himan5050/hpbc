<style>
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
    .maincoldate{margin-top:25px;}
    label{float:left; margin-right:5px;margin-top: 5px;}
</style>

<div id="rec_participant">
    <table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper">
        <tr>
            <td align="left" class="tdform-width"><fieldset><legend>Defaulters List Within Loan Period:</legend>
                    <table align="left" class="frmtbl" >
                        <tr><td width="5%">&nbsp;</td><td><div class="maincol maincoldate"><?php print drupal_render($form['district_id']); ?></div></td><td><div class="maincol maincoldate"><?php print drupal_render($form['tehsil_id']); ?></div></td><td><div class="maincol maincoldate"><?php print drupal_render($form['panchayat_id']); ?></div></td></tr>
                        <tr><td width="5%">&nbsp;</td><td><div class="maincol maincoldate"><?php print drupal_render($form['startdate']); ?></div></td><td><div class="maincol maincoldate"><?php print drupal_render($form['enddate']); ?></div></td><td><div class="maincol maincoldate"><?php print drupal_render($form['type']); ?></div></td><td width="5%">&nbsp;</td></tr><tr><td colspan="7" align="right"><div  style="margin-right:70px;"><?php print drupal_render($form); ?></div></td></tr>
                    </table>
                </fieldset></td></tr>
    </table>
</div>

<?php
global $base_url;
$op = $_REQUEST['op'];
if ($op == 'Generate') {
	$cond = '';
	$endate = date('Y-m-d');
	$stdate = date('Y-m-d', strtotime($endate.' - 5 year'));
		
	if(isset($_REQUEST['district_id']) && ($_REQUEST['district_id'] != '')) {
		$cond .= " and tbl_loanee_detail.district = '".$_REQUEST['district_id']."'";
		$_REQUEST ['page'] = 0;
	}
		
	if(isset($_REQUEST['tehsil_id']) && ($_REQUEST['tehsil_id'] != '')) {
		$cond .= " and tbl_loanee_detail.tehsil = '".$_REQUEST['tehsil_id']."'";
		$_REQUEST ['page'] = 0;
	}
	
	if(isset($_REQUEST['panchayat_id']) && ($_REQUEST['panchayat_id'] != '')) {
		$cond .= " and tbl_loanee_detail.panchayat = '".$_REQUEST['panchayat_id']."'";
		$_REQUEST ['page'] = 0;
	}
	if ($stdate && $endate) {
		$cond .= ' and tbl_loan_disbursement.cheque_date BETWEEN "' . $stdate . '" AND "' . $endate . '"';
		$_REQUEST ['page'] = 0;
	}
	// Ommiting closed accounts
	$last_quarter_end_date = getLastQuaterEndDate ();
	$cond .= ' and tbl_loan_detail.emi_amount != 0 and tbl_loan_detail.o_principal != 0 and tbl_loan_detail.last_interest_calculated >= "' . $last_quarter_end_date . '"';
	
	$sql = "select tbl_loan_detail.emi_amount,
	tbl_loan_detail.ROI,
	tbl_loan_detail.o_principal,
	tbl_loanee_detail.loanee_id,
	tbl_loanee_detail.fh_name,
	tbl_loanee_detail.corp_branch,
	tbl_scheme_master.scheme_name,
	tbl_panchayt.panchayt_name,
	tbl_tehsil.tehsil_name,
	tbl_block.block_name,
	tbl_loanee_detail.account_id,
	tbl_loanee_detail.fname,
	tbl_loanee_detail.address1,
	tbl_loanee_detail.address2,
	tbl_loanee_detail.district,
	tbl_loanee_detail.tehsil,
	tbl_loanee_detail.block,
	tbl_loanee_detail.reg_number,
	tbl_loanee_detail.mobile,
	tbl_loan_disbursement.cheque_date
	from tbl_loanee_detail
	INNER JOIN tbl_loan_disbursement ON  (tbl_loanee_detail.loanee_id=tbl_loan_disbursement.loanee_id)
	INNER JOIN tbl_panchayt ON (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat)
	INNER JOIN tbl_tehsil ON (tbl_tehsil.tehsil_id=tbl_loanee_detail.tehsil)
	INNER JOIN tbl_block ON (tbl_block.block_id=tbl_loanee_detail.block)
	INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
	INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
	WHERE tbl_loanee_detail.account_id !='' $cond ";
	
	$pdfurl = $base_url . "/LoaneeIssueDetailReportpdf.php?op=loanissuedetail_report&district=$district&tehsil=$tehsil&panchayat=$panchayat&sector=$sector&scheme=$scheme&account=$account&from_date=$from_date&to_date=$to_date";
	$count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
	
	$res = pager_query ( $sql, 10, 0, $count_query );
	$pdfimage = $base_url . '/' . drupal_get_path ( 'theme', 'scst' ) . "/images/pdf_icon.gif";
	
	$output = '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%">
	<tr class="oddrow"><td align="left" colspan="15"><h2 style="text-align:left;">Defaulter List With in Loan Period</h2></td>
	<tr>
	<td align="right"  colspan="15">
	<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	</table></div>';
	
	$output .= '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%" id="wrapper2">
               <tr>
   				<th width="5%">S. No.</th>
				<th >A/c No.</th>
				<th >Loanee Name</th>
				<th >Father Name</th>
				<th>Address</th>
				<th>Tehsil</th>
				<th>Scheme</th>
				<th>Disbursement Date</th>
				<th>Disbursement Amt.</th>
				<th>Recovered Amt.</th>
           		<th>Default Amt.</th>
				<th >Intt Accrued</th>
				<th>Out. Amt.</th>
				<th>LD</th>
				<th>Mobile No.</th>
				<th>Last Date and Amt. of Recovery</th>
				</tr>';
	
	if ($_REQUEST ['page']) {
		$counter = $_REQUEST ['page'] * 10;
	} else {
		$counter = 0;
	}
	
	while ( $rs = db_fetch_object ( $res ) ) {
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
		$disbamount = ($d->disamount) ? $d->disamount : 'N/A';
		
		//Calculate Default Amount.
		$default_amount = calculateDefaultAmount($rs->emi_amount, $recovered_amount, $rs->cheque_date);
		
		if($default_amount != 0) {
			$output .= '<tr class="' . $cla . '">
					 <td class="center" width="10%">' . $counter . '</td>
					 <td >' . ucwords ( $rs->account_id ) . '</td>
					 <td >' . ucwords ( $rs->fname ) . '&nbsp;' . ucwords ( $rs->lname ) . '</td>
					 <td >' . ucwords ( $rs->fh_name ) . '</td>
					 <td align="right">' . ucwords ( $rs->address1 . " " . $rs->address2 ) . '</td>
					 <td >' . ucwords ( $rs->tehsil_name ) . '</td>
 					 <td >' . ucwords ( $rs->scheme_name ) . '</td>
					 <td align="right">' . date ( 'd/m/Y', strtotime ( $rs->cheque_date ) ) . '</td>
					 <td >' . round ( $disbamount ) . '</td>
					 <td align="right">' . round ( abs ( $recovered_amount ) ) . '</td>
					 <td align="right">' . round ( abs ( $default_amount ) ) . '</td>
					 <td align="right">' . round ( abs ( $interest_amount ) ) . '</td>
					 <td align="right">' . round ( abs ( $balamount ) ) . '</td>
   					 <td align="right">' . round ( abs ( $ld_amount ) ) . '</td>
					 <td >' . ucwords ( $rs->mobile ) . '</td>
					 <td align="right">' . date ( 'd/m/Y', strtotime ( $last_rec_date) ) . '</td>
	            </tr>';
			
		}
		
			
		
	}
	
	if ($counter > 0) {
		
		$output .= '</table></div>';
		echo $output .= theme ( 'pager', NULL, 10, 0 );
	} else {
		echo '<font color="red"><b>No Record found...</b></font>';
	}
	
	
	
	
	
	
	
}
	
	














	
	
	/**
	
	
	
    $type = $_REQUEST['type'];
    if ($type != '') {
        $cond = '';
        $endate = date('Y-m-d');
        $stdate = date('Y-m-d', strtotime($endate.' - 5 year'));
        
        if(isset($_REQUEST['district_id']) && ($_REQUEST['district_id'] != '')) {
        	$cond .= " and tbl_loanee_detail.district = '".$_REQUEST['district_id']."'";
        }
        
        if(isset($_REQUEST['tehsil_id']) && ($_REQUEST['tehsil_id'] != '')) {
        	$cond .= " and tbl_loanee_detail.tehsil = '".$_REQUEST['tehsil_id']."'";
        }
        
        if(isset($_REQUEST['panchayat_id']) && ($_REQUEST['panchayat_id'] != '')) {
        	$cond .= " and tbl_loanee_detail.panchayat = '".$_REQUEST['panchayat_id']."'";
        }
        if ($stdate && $endate) {
        	$cond .= ' and tbl_loan_disbursement.cheque_date BETWEEN "' . $stdate . '" AND "' . $endate . '"';
        	$_REQUEST ['page'] = 0;
        }
        
        
        if ($type == 'defaulter') {
            $sql = "select tbl_loan_detail.emi_amount,
	                tbl_loan_detail.ROI,
                    tbl_loan_detail.o_principal,
	                tbl_loanee_detail.loanee_id,
	                tbl_loanee_detail.corp_branch,
	                tbl_scheme_master.scheme_name,
	                tbl_panchayt.panchayt_name,
	                tbl_tehsil.tehsil_name,
	                tbl_block.block_name,
	                tbl_loanee_detail.account_id,
	                tbl_loanee_detail.fname,
	                tbl_loanee_detail.address1,
	                tbl_loanee_detail.address2,
	                tbl_loanee_detail.district,
	                tbl_loanee_detail.tehsil,
	                tbl_loanee_detail.block,
	                tbl_loanee_detail.reg_number 
	         from tbl_loanee_detail
			 INNER JOIN tbl_loan_disbursement ON  (tbl_loanee_detail.loanee_id=tbl_loan_disbursement.loanee_id)
             INNER JOIN tbl_panchayt ON (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat)
             INNER JOIN tbl_tehsil ON (tbl_tehsil.tehsil_id=tbl_loanee_detail.tehsil)
             INNER JOIN tbl_block ON (tbl_block.block_id=tbl_loanee_detail.block)
             INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
             INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
             WHERE tbl_loanee_detail.account_id !='' $cond ";
            
            
            $pdfurl = $base_url . "/LoaneeIssueDetailReportpdf.php?op=loanissuedetail_report&district=$district&tehsil=$tehsil&panchayat=$panchayat&sector=$sector&scheme=$scheme&account=$account&from_date=$from_date&to_date=$to_date";
            $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
            
            $res = pager_query ( $sql, 10, 0, $count_query );
            $pdfimage = $base_url . '/' . drupal_get_path ( 'theme', 'scst' ) . "/images/pdf_icon.gif";
            
            $query = db_query($sql);
            $l = 1;
            
            
            $pdfurl = $base_url . "/LoaneeIssueDetailReportpdf.php?op=loanissuedetail_report&district=$district&tehsil=$tehsil&panchayat=$panchayat&sector=$sector&scheme=$scheme&account=$account&from_date=$from_date&to_date=$to_date";
            $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
            
            $res = pager_query ( $sql, 10, 0, $count_query );
            $pdfimage = $base_url . '/' . drupal_get_path ( 'theme', 'scst' ) . "/images/pdf_icon.gif";
            
            $sqlcount = "select COUNT(*) AS count ,
			                  tbl_loan_detail.emi_amount,
			                  tbl_loan_detail.ROI,
                                          tbl_loan_detail.o_principal,
			                  tbl_loanee_detail.loanee_id,
			                  tbl_loanee_detail.corp_branch,
			                  tbl_scheme_master.scheme_name,
			                  tbl_panchayt.panchayt_name,
			                  tbl_tehsil.tehsil_name,
			                  tbl_block.block_name,
			                  tbl_loanee_detail.account_id,
			                  tbl_loanee_detail.fname,
			                  tbl_loanee_detail.address1,
			                  tbl_loanee_detail.address2,
			                  tbl_loanee_detail.district,
			                  tbl_loanee_detail.tehsil,
			                  tbl_loanee_detail.block,
			                  tbl_loanee_detail.reg_number 
			          from tbl_loanee_detail
                      INNER JOIN tbl_panchayt ON (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat)
                      INNER JOIN tbl_tehsil ON (tbl_tehsil.tehsil_id=tbl_loanee_detail.tehsil)
                      INNER JOIN tbl_block ON (tbl_block.block_id=tbl_loanee_detail.block)
                      INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
                      INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
                      where tbl_loanee_detail.account_id !='' $cond ";
            
            $rscount = db_query($sqlcount);
            $rscounter = db_fetch_object($rscount);
            if ($rscounter->count == 0 || $rscounter->count == '') {
                echo '<font color="red"><b>No Record found...</b></font>';
                print_r('thhhshs'); exit;
            } else {
            	print_r('tesinnfn'); exit;
                $sk = 0;
                while ($res = db_fetch_array($query)) {
                    if ($l % 2 == 0) {
                        $cla = "even";
                    } else {
                        $cla = "odd";
                    }
                    $opb = "select sum(amount) as opbal 
						              from tbl_loan_disbursement 
									  where loanee_id='" . $res['loanee_id'] . "' group by loanee_id";
                    $opbq = db_query($opb);
                    $opbr = db_fetch_array($opbq);

                    $opi = "select sum(amount) as intpaid 
							   from tbl_loan_interestld 
							   where account_id='" . $res['account_id'] . "' and type = 'interest'";
                    $opiq = db_query($opi);
                    $opir = db_fetch_array($opiq);

                    $opr = "select sum(amount) as recovery
							   from tbl_loan_repayment 
							   where loanee_id='" . $res['loanee_id'] . "'";
                    $oprq = db_query($opr);
                    $oprr = db_fetch_array($oprq);


                    // Return Outstanding Principal.
                    $o_principle = coreloanledger($res['account_id'],'2016-12-31');
                    
                    
                    $output = '<table>
		                                        <tr class="oddrow">
												<td colspan="13" align="right">
												<h2 style="text-align:left;">Defaulters List</h2></td></tr>
		                                        <tr><td colspan="13" align="right">
												<a href="' . $base_url . '/generatedefaulterpdf.php?op=defaulter&startdate=' . $startdate . '&enddate=' . $enddate . '&type=' . $type . '&district=' . $_REQUEST['district_id'] . '&tehsil=' . $_REQUEST['tehsil_id'] . '&panchayat=' . $_REQUEST['panchayat_id'] . '" target="_blank">
												<img src="account/images/pdf_icon.gif" style="float:right;" alt="pdf"/></a></td></tr>
		                                        <tr><td colspan="13" align="right"></td></tr>
                                                <tr><th><b>Account No.</b></th>
                                                <th><b>Scheme Name</b></th>
                                                <th><b>Loanee Name</b></th>
                                                <th><b>Address</b></th>
                                                <th><b>Block</b></th>
                                                <th><b>Tehsil</b></th>
                                                <th><b>Panchayat</b></th>
                                                <th><b>Opening Balance</b></th>
                                                <th><b>Interest</b></th>
                                                <th><b>Recover amount</b></th>
                                                <th><b>Expected Amount</b></th>
                                                <th><b>Default Amount</b></th>
                                                <th><b>Outstanding Balance</b></th></tr>';
                    
                    $default_value = 450;
                    if($default_value > 0) {
                    		$output .='<tr class="' . $cla . '">
									            <td>' . $res['account_id'] . '</td>
												<td>' . $res['scheme_name'] . '</td>
												<td>' . $res['fname'] . '</td>
												<td>' . $res['address1'] . '</td>
												<td>' . $res['block_name'] . '</td>
												<td>' . $res['tehsil_name'] . '</td>
												<td>' . $res['panchayt_name'] . '</td>
												<td>' . round($opbr['opbal']) . '</td>
												<td>' . round($opir['intpaid']) . '</td>
												<td>' . round($oprr['recovery']) . '</td>
												<td>' . round($expted) . '</td>
												<td>' . round($expted) . '</td>
												<td>' . $o_principle . '</td></tr>';
                    }
                    
                   
                    } // if condition ends...
                    $l++;
                } // while loop ends///
            // else loop ends..
            if ($val == 1) {
                
            } else {
                echo '<font color="red"><b>No Record found...</b></font>';
            }
        }

        $output .='</table>';
        echo $output;
    }
} */
?>