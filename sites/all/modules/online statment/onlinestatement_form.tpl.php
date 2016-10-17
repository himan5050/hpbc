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
    .maincol select{
        width: 100px;
    }
</style>
<div id="rec_participant">
    <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
        <tr><td align="left" class="tdform-width"><fieldset><legend>Online Statement Report</legend>
                    <table align="left" class="frmtbl">
                        <tr><td width="5%">&nbsp;</td><td><b>Account :<sup><font color="#FF0000">*</font></sup></b></td><td><div class="maincol"><?php print drupal_render($form['account']); ?></div></td><td colspan="2" align="right">
                                <div class="mainsub_hide" style="margin-right:65px;"><?php print drupal_render($form); ?></div></td>
                            <td width="5%">&nbsp;</td></tr>
                    </table></fieldset>
            </td>
        </tr>
    </table>
</div>
<?php
global $base_url;
global $user;
$op = $_REQUEST['op'];
if ($op == 'Generate Report') {
    $account = $_REQUEST['account'];
    //check for loginned user.
    if($user->name != $account){
        form_set_error('form','Please enter your correct Account Id');
        return;
    }
    if ($account == '') {
        form_set_error('form', 'Please enter the Account Id .');
    } else if ($account != '') { ?>
        <script>$('.mainsub_hide').hide(function(){
            $('.maincol').before('<?php echo $account; ?>').hide();
        });
        </script>
        <?php
        $cond = 'and tbl_loanee_detail.account_id Like "' . $account . '"';
    }
    $dsql = "SELECT l.loanee_id,l.fname,l.lname,l.address1,l.mobile,ld.project_cost,ld.o_principal,ld.o_interest,ld.o_LD,ld.o_other_charges FROM tbl_loanee_detail l,tbl_loan_detail ld WHERE l.reg_number = ld.reg_number AND l.account_id = '" . $account . "' LIMIT 1";
    $dres = db_query($dsql);
    $loanee = db_fetch_object($dres);
    $sql = "SELECT *  FROM `tbl_loan_repayment` WHERE `loanee_id` = '" . $loanee->loanee_id . "'";

    $pdfurl = $base_url . "/onlinestatementpdf.php?op=loanissuedetail_report&account=$account";
    $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
    $dcount_query = "SELECT COUNT(*) FROM (" . $dsql . ") AS count_query";
    $res = pager_query($sql, 20, 0, $count_query);
    $resd = pager_query($sql, 20, 0, $dcount_query);
    $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";
    $output .= '<div class="listingpage_scrolltable"><table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr class="oddrow"><td align="left" colspan="2"><h2 style="text-align:left;">Online Statement Report</h2></td>
	
	
	</tr>';

    $output .= '
	
	<tr>
	  <td width="14%">
	
	<b>&nbsp;&nbsp;Loanee Name:</b></td><td>' . $loanee->fname . ' ' . $loanee->lname . '</td>
	</tr>
	
	<tr><td><b>&nbsp;&nbsp;Full Address. :</b></td><td>' . $loanee->address1 . '</td></tr>
	<tr><td>
    <b>&nbsp;&nbsp;Contact Number:</b></td><td>' . $loanee->mobile . '<br>	
	</td></tr>
		<tr>
	
	<td align="right" colspan="2">
	<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	</table></div>';

    //$output .='';
    $output .='<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="1" border="0" id="wrapper2" >
               <tr><th width="5%">S. No.</th>
   				<th>Date</th>
			<th>Starting Balance</th>
                        <th>LD Outstanding</th>
			<th>EMI Type</th>
			<th>Installment Paid</th>
                        <th>Ending Balance</th>
			
				</tr>';


    if ($_REQUEST['page']) {
        $counter = $_REQUEST['page'] * $limit;
    } else {
        $counter = 0;
    }
    $counter = 0;


    while ($rs = db_fetch_object($res)) {
        $gender = getlookupName($rs->gender);
        $gender = getlookupName($rs->gender);
        $dam = $rs->o_other_charges;
        $dam1 = $rs->o_intrest;
        $dam2 = $rs->o_principal;
        $dam3 = $rs->o_LD;
        $dlname = $rs->lname;
        $dfname = $rs->fname;
        $daccount = $rs->account_id;
        
        if ($rs->paytype != 'Promoter Share') {
            $ending_balance = coreloanledger($account, $rs->payment_date);
            $starting_balance = $ending_balance + $rs->amount;
        } else {
            $ending_balance = $loanee->project_cost - $rs->amount;
            $starting_balance = $loanee->project_cost;
        }
        $dbalamount = $dam + $dam1 + $dam2 + $dam3;
        $counter++;
        if ($counter % 2 == 0) {
            $cla = "even";
        } else {
            $cla = "odd";
        }
        $output .='
	    <tr class="' . $cla . '">
            <td class="center" width="5%" >' . $counter . '</td>';
        $output .='<td align="center">' . date('d-m-Y', strtotime($rs->payment_date)) . '</td>';
        $output .='<td align="right">' . round($starting_balance) . '</td>';
        $output .='<td align="right">' . round($rs->o_LD) . '</td>';
        $output .='<td align="right">' . $rs->paytype. '</td>';
        $output .='<td align="right">' . round($rs->amount) . '</td>';
        $output .='<td align="right">' . round($ending_balance + $rs->o_LD) . '</td>
	            </tr>';
    }

    if ($counter > 0) {

        $output .='</table></div>';
        echo $output .= theme('pager', NULL, 20, 0);
    } else {
        echo '<font color="red"><b>No Record found...</b></font>';
    }
}
?>