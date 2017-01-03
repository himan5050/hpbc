<?php
drupal_add_js(drupal_get_path('module', 'hp_interestcalculation') . '/hp_interestcalculation.js');
?>

<style>
    .container-inline-date .form-item, .container-inline-date .form-item input {
        width: 100px;
        display:inline;
    }

    select { width:120px; }

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

    .maincoldate{margin-top:12px;}
    #edit-date-to-datepicker-popup-0{width:auto;}
</style>

<div id="rec_participant">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
        <tr><td align="left" class="tdform-width"><fieldset><legend>Interest Calculator</legend>
                    <table align="left" class="frmtbl">
                        <tr><td><b>Branch: <font color="red">*</font></b></td>
                            <td><div class="maincol"><?php print drupal_render($form['corporation']); ?></div></td>
                            <td><b>Scheme: <font color="red">*</font></b></td>
                            <td><div class="maincol"><?php print drupal_render($form['scheme']); ?></div></td>
                            <td><b>Account No.:</b></td>
                            <td><div class="maincol"><?php print drupal_render($form['account_number']); ?></div></td>
                        </tr>
                        <tr><td><b>Calculate up to: <font color="red">*</font></b></td>
                            <td><div class="maincol"><?php print drupal_render($form['date_to']); ?></div></td>
                            <td colspan="4" align="right"><div style="margin-right:70px;"><?php print drupal_render($form); ?></div></td>
                        </tr>    
                    </table>
                </fieldset></td>
        </tr>
    </table>
</div>

<?php
$op = $_REQUEST['op'];
if ($op == 'Calculate Interest') {
    if ($_REQUEST['corporation'] == '' && $_REQUEST['scheme'] == '' && $_REQUEST['date_to']['date'] == '') {
        
    } else if ($_REQUEST['corporation'] == '') {
        
    } else if ($_REQUEST['scheme'] == '') {
        
    } else if ($_REQUEST['date_to']['date'] == '') {
        
    } else {
        $sdate = $_REQUEST['date_to']['date'];
        $to_date = strtotime($_REQUEST['date_to']['date']);
        $corporation = $_REQUEST['corporation'];
        $scheme = $_REQUEST['scheme'];
        $account_number = $_REQUEST['account_number'];
        //echo $sdate.' / '.$to_date.' / '.$corporation.' / '.$scheme.' / '.$account_number;

        if ($account_number) {
            $cond = "AND account_id = '" . $account_number . "' 
				           AND UNIX_TIMESTAMP(tbl_loan_detail.last_interest_calculated) < '" . $to_date . "' ";
        } else {
            $cond = "AND account_id != '' 
				           AND UNIX_TIMESTAMP(tbl_loan_detail.last_interest_calculated) < '" . $to_date . "'";
        }

        $sql = "select tbl_loan_detail.ROI, 
                  tbl_loanee_detail.loanee_id,
                  tbl_loanee_detail.account_id,
		  tbl_loan_detail.project_cost,
                  tbl_loan_detail.o_principal,
                  tbl_loan_detail.o_interest,
                  tbl_loan_detail.o_LD,
                  tbl_loan_detail.o_other_charges,
                  tbl_loan_detail.last_interest_calculated
                  from tbl_loan_detail 
		   INNER JOIN tbl_loanee_detail ON (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number) 
		   where tbl_loanee_detail.corp_branch='" . $corporation . "' AND tbl_loan_detail.scheme_name='" . $scheme . "' $cond";
        $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
        $res = pager_query($sql, 10, 0, $count_query);
        global $base_url;

        $output = '<div class="listingpage_scrolltable">
                      <table cellpadding="0" cellspacing="2" border="0" width="100%">
	                  <tr class=oddrow>
			          <td colspan=3><h2 style="text-align:left;">Interest Calculation Report</h2></td>
			          <td colspan="3"><span id="txtHint">
			          <a href="#" onclick=showHint("' . $base_url . '/","' . $corporation . '","' . $scheme . '","' . $account_number . '","' . $to_date . '")>
			          Process</a> </span></td></tr>
	                  <tr></tr></tr>
	                  </table></div>';

        $output .='<div class="listingpage_scrolltable">
		              <table cellpadding="2" cellspacing="1">';
        $output .='<tr>
   				      <th >S. No.</th>
				      <th>Account Number</th>
				      <th>Total Disbursed Amount</th>
				      <th>Principal Amount</th>
				      <th>O/S Interest</th>
				      <th>LD Charges</th>
				      <th>Other Charges </th>
				      <th>ROI</th>
				      <th>Interest Type</th>
				      <th>Last Interest Calculated Date </th>
				      <th>Upto Date </th>
				      <th>Interest Amount</th>
				      </tr>';

        if ($_REQUEST['page']) {
            $counter = $_REQUEST['page'] * $limit;
        } else {
            $counter = 0;
        }
        while ($rs = db_fetch_object($res)) {
            $counter++;
            $sqld = "select sum(amount) as dsum from tbl_loan_disbursement where loanee_id = '" . $rs->loanee_id . "'";
            $resd = db_query($sqld);
            $rsd = db_fetch_object($resd);

            $sqli = "select calculation_date,o_principle 
			            from tbl_loan_interestld 
				    where account_id = '" . $rs->account_id . "' AND type = 'interest'
				    order by calculation_date DESC limit 1";

            $resi = db_query($sqli);
            $rsi = db_fetch_object($resi);

            // echo $rsi->o_principle; exit;

            if (isset($rsi->o_principle)) {
                //  echo $rs->account_id.' / '.$rsi->o_principle.' / '.$rs->last_interest_calculated.' / '.$rs->ROI; 
                $interest_value = interest_calculation_regular($rs->account_id, $rsi->o_principle, $rs->last_interest_calculated, date('Y-m-d', strtotime($_REQUEST['date_to']['date'])), $rs->ROI);

                //  echo $interest_value; exit;
            } else {
                // Interest must be calculated on total term loan value.
                $sqlp = "select amount
			            from tbl_loan_repayment 
					    where loanee_id = '" . $rs->loanee_id . "' AND paytype = 'Promoter Share'";
                $resp = db_query($sqlp);
                $rsp = db_fetch_object($resp);

                //$o_principal = $rs->project_cost - $rsp->amount;
                $o_principal  = isset($rs->o_principal)?$rs->o_principal:0;
                //print_r($o_principal); exit;
                $interest_value = interest_calculation_first($rs->account_id, $o_principal, $rs->last_interest_calculated, date('Y-m-d', strtotime($_REQUEST['date_to']['date'])), $rs->ROI);
            }
            if ($counter % 2 == 0) {
                $cl = "even";
            } else {
                $cl = "odd";
            }
            $output .='<tr class="' . $cl . '">
					      <td>' . $counter . '</td>
					      <td>' . $rs->account_id . '</td>
					      <td>' . $rsd->dsum . '</td>
					      <td>' . $rs->o_principal . '</td>
					      <td>' . $rs->o_interest . '</td>
					      <td>' . $rs->o_LD . '</td>
					      <td>' . $rs->o_other_charges . '</td>
					      <td>' . $rs->ROI . '</td>
					      <td>' . $valint[1] . '</td>
					      <td>' . date('d-m-Y', strtotime($rs->last_interest_calculated)) . '</td>
					      <td>' . $_REQUEST['date_to']['date'] . '</td>
					      <td>' . $interest_value['interest_value'] . ' </td>
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