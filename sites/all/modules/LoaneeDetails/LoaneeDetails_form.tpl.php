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
        <tr><td align="left" class="tdform-width"><fieldset><legend>Loanee Details Report</legend>
                    <table align="left" class="frmtbl">
                        <tr>
                            <td><b>District:</b></td>
                            <td><?php print drupal_render($form['district_id']); ?></td>
                            <td><b>Sector:</b></td>
                            <td><?php print drupal_render($form['sector1']); ?></td>		     
                            <td><b>Scheme:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['scheme2']); ?></div></td>
                        </tr>
                        <tr>
                            <td><b>From:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['from_date']); ?></div></td>
                            <td><b>To:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['to_date']); ?></div></td>
                            <td><b>Gender:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['gender']); ?></div></td>
                        </tr>
                        <tr>
                            <td colspan="6" align="right"><div style="margin-right:93px;"><?php print drupal_render($form); ?></div></td>
                        </tr>
                    </table></fieldset>
            </td>
        </tr>
    </table>
</div>

<?php
global $base_url;
$op = $_REQUEST['op'];
//print_r($_REQUEST);
if ($op == 'Generate Report') {
    $district = $_REQUEST['district_id'];
    $sector = $_REQUEST['sector1'];
    $scheme = $_REQUEST['scheme2'];
    $gender = $_REQUEST['gender'];
    $from_date = date('Y-m-d', strtotime($_REQUEST['from_date']['date']));
    $to_date = date('Y-m-d', strtotime($_REQUEST['to_date']['date']));


//print_r($_REQUEST);exit;
//drupal_set_message($sector.$scheme);
    $val = '%' . strtoupper($district) . '%';
    $key = addslashes($val);
    if ($district == '' and $sector == '') {
        form_set_error('form', 'Please enter the district or sector .');
    } else {
        if ($district && $sector == '' && $scheme == '') {
            $cond = 'and tbl_district.district_id = "' . $district . '"';
        } else if ($district == '' && $sector && $scheme == '') {
            $_REQUEST['page'] = 0;
            //$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'"';
            $cond = "and tbl_sectors.sector_id='" . $sector . "' OR tbl_scheme_master.loan_scheme_id='" . $scheme . "' and tbl_loan_disbursement.cheque_date BETWEEN '" . $from_date. "' AND '". $to_date ."' ";
        } else if ($district && $sector && $scheme == '') {
            $cond = "and tbl_sectors.sector_id='" . $sector . "' and tbl_district.district_id = '" . $district . "' OR tbl_scheme_master.loan_scheme_id='" . $scheme . "' ";
            $_REQUEST['page'] = 0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';
        } else if ($district && $sector && $scheme) {
            $cond = "and tbl_sectors.sector_id='" . $sector . "' and tbl_district.district_id = '" . $district . "' and tbl_scheme_master.loan_scheme_id = '" . $scheme . "' ";
            $_REQUEST['page'] = 0;
        } else if ($district == '' && $sector && $scheme) {
            $cond = "and tbl_sectors.sector_id='" . $sector . "' and tbl_district.district_id = '" . $district . "' and tbl_scheme_master.loan_scheme_id='" . $scheme . "' ";
            $_REQUEST['page'] = 0;
        }



        $sql = "SELECT tbl_loan_detail.scheme_name,
                tbl_loan_detail.loan_amount,
				tbl_loan_detail.reg_number,
				tbl_loan_detail.o_principal,
				tbl_loanee_detail.account_id,
				tbl_loanee_detail.loanee_id,	  
				tbl_loanee_detail.fname,tbl_loanee_detail.lname,
				tbl_loanee_detail.district,
   				tbl_loanee_detail.tehsil,
   				tbl_loanee_detail.address1,
				tbl_district.district_name,
                tbl_tehsil.tehsil_name,
                tbl_scheme_master.scheme_name as schemename,
				tbl_sectors.sector_name,
				tbl_scheme_master.loan_scheme_id,
				tbl_scheme_master.apex_share,
				tbl_scheme_master.corp_share,
				tbl_scheme_master.promoter_share,
				tbl_loan_disbursement.createdon
	    FROM tbl_loanee_detail 
	    INNER JOIN tbl_loan_detail ON  (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number)
        INNER JOIN tbl_scheme_master ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
	    INNER JOIN tbl_sectors ON  (tbl_scheme_master.sector=tbl_sectors.sector_id) 
	    INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id)
        INNER JOIN tbl_tehsil ON  (tbl_loanee_detail.tehsil=tbl_tehsil.tehsil_id)
        INNER JOIN tbl_loan_disbursement ON (tbl_loanee_detail.loanee_id = tbl_loan_disbursement.loanee_id)
	    where 1=1  $cond";




        $pdfurl = $base_url . "/LoaneeDetailReportpdf.php?op=loaneedetail_report&district=$district&sector=$sector&scheme=$scheme";


        $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
    }

    $res = pager_query($sql, 10, 0, $count_query);
    $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";


    $output = '<div class="listingpage_scrolltable"><table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr class="oddrow"><td align="left"><h2 style="text-align:left;">Loanee Details Report</h2></td>
	<tr>
	<td align="right">
	<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	
	</tr>
	</table></div>';

    //$output .='';
    $output .='<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="1" border="0" id="wrapper2">';
    $output .='<tr>
   				<th>S. No.</th>
				<th>District Name</th>
                <th>Tehsil Name</th>
				<th>Sector Name</th>
				<th>Name of Scheme</th>
				<th>Account No.</th>
				<th>Name of Loanee</th>
                <th>Address of Loanee</th>
				<th>Loan Amount Disbursed</th>
				<th>Date of Disbursement</th>
				<th>Outstanding Principal</th>
				</tr>';


    $limit = '10';
    if ($_REQUEST['page']) {
        $counter = $_REQUEST['page'] * $limit;
    } else {
        $counter = 0;
    }
    //$counter =0;

    while ($rs = db_fetch_object($res)) {
        $counter++;
        $loanesql = db_query("select sum(amount) as disamount from tbl_loan_disbursement where loanee_id = '" . $rs->loanee_id . "'");
        $resql = db_fetch_object($loanesql);
        if ($counter % 2 == 0) {
            $cla = "even";
        } else {
            $cla = "odd";
        }
        if($rs->o_principal != 0){
            $output .='<tr class="' . $cla . '">
				   <td class="center" width="5%">' . $counter . '</td>
				   <td >' . ucwords($rs->district_name) . '</td>
                   <td >' . ucwords($rs->tehsil_name) . '</td>
				   <td >' . ucwords($rs->sector_name) . '</td>
				   <td >' . ucwords($rs->schemename) . '</td>
				   <td >' . $rs->account_id . '</td>
				   <td >' . ucwords($rs->fname) . ' ' . ucwords($rs->lname) . '</td>
				   <td >' . ucwords($rs->address1) . '</td>
				   <td >' . round($resql->disamount) . '</td>
				   <td align="right">' . date('d/m/Y', $rs->createdon) . '</td>
				   <td align="right">' . round($rs->o_principal) . '</td>
	               </tr>';
        }

    }

    if ($counter > 0) {

        $output .='</table></div>';
        echo $output .= theme('pager', NULL, 10, 0);
    } else {
        echo '<font color="red"><b>No Record found...</b></font>';
    }
}
?>