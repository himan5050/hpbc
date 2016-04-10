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
        <tr><td align="left" class="tdform-width"><fieldset><legend>Education Loan Details Report</legend>
                    <table align="left" class="frmtbl">
                        <tr>
                            <td><b>District:</b></td>
                            <td><?php print drupal_render($form['district_id']); ?></td>
                            <td><b>Tehsil:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['tehsil_id']); ?></div></td>
                            <td><b>Panchayat:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['panchayt_id']); ?></div></td>
                            </tr><tr>
                            <td><b>From:</b></td>
                            <td><?php print drupal_render($form['from_date']); ?></td>		     
                            <td><b>To:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['to_date']); ?></div></td>
                            <td><b>Course:</b></td>
                            <td><div id="sector2"><?php print drupal_render($form['course_id']); ?></div></td>
                            
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
	$tehsil = $_REQUEST['tehsil_id'];
	$panchayat = $_REQUEST['panchayt_id'];
    $from_date = $_REQUEST['from_date']['date'];
    $to_date = $_REQUEST['to_date']['date'];
	$course = $_REQUEST['course_id'];
	
	//echo 'District Value = '.$district.'<br>';
	//echo 'Tehsil Value = '.$tehsil.'<br>';
	//echo 'Panchayat Value = '.$panchayat.'<br>';
	//echo 'From Date = '.$from_date.'<br>';
	//echo 'To Date = '.$to_date.'<br>';
	//echo 'Course = '.$course.'<br>';
	
	$coursesql = db_query("select * from tbl_courses where course_id = '" . $course . "'");
    $recsql = db_fetch_object($coursesql);
	$course_name = $recsql->course_name;
	$course_duration = $recsql->course_duration;

    if ($district == '' and $tehsil == '') {
        form_set_error('form', 'Please enter the district or tehsil.');
    } else {
        if ($district && $tehsil == '' && $panchayt == '') {
            $cond = 'and tbl_district.district_id = "' . $district . '"';
        } else if ($district == '' && $tehsil && $panchayat == '') {
            $_REQUEST['page'] = 0;
            //$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'"';
            $cond = "and tbl_tehsil.tehsil_id='" . $tehsil . "' OR tbl_panchayt.panchayt_id='" . $panchayat . "' ";
        } else if ($district && $tehsil && $panchayt == '') {
            $cond = "and tbl_tehsil.tehsil_id='" . $tehsil . "' and tbl_district.district_id = '" . $district . "' OR tbl_panchayt.panchayt_id='" . $panchayat . "' ";
            $_REQUEST['page'] = 0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';
        } else if ($district && $tehsil && $panchayat) {
            $cond = "and tbl_tehsil.tehsil_id='" . $tehsil . "' and tbl_district.district_id = '" . $district . "' and tbl_panchayt.panchayt_id = '" . $panchayat . "' ";
            $_REQUEST['page'] = 0;
        } else if ($district == '' && $tehsil && $panchayat) {
            $cond = "and tbl_tehsil.tehsil_id='" . $tehsil . "' and tbl_district.district_id = '" . $district . "' and tbl_panchayt.panchayt_id='" . $panchayat . "' ";
            $_REQUEST['page'] = 0;
        }

        echo $sql = "SELECT tbl_loan_detail.scheme_name,
                       tbl_loan_detail.loan_amount,
				       tbl_loan_detail.reg_number,
					   tbl_loan_detail.disbursed_amount,
					   tbl_loanee_detail.education,
				       tbl_loanee_detail.account_id,
				       tbl_loanee_detail.loanee_id,	  
				       tbl_loanee_detail.fname,tbl_loanee_detail.lname,
					   tbl_loanee_detail.fh_name,
				       tbl_loanee_detail.district,
   				       tbl_loanee_detail.tehsil,
				       tbl_district.district_name,
                       tbl_tehsil.tehsil_name,
					   tbl_panchayt.panchayt_name,
                       tbl_scheme_master.scheme_name as schemename,
				       tbl_sectors.sector_name,
				       tbl_scheme_master.loan_scheme_id,
				       tbl_scheme_master.apex_share,
				       tbl_scheme_master.corp_share,
				       tbl_scheme_master.promoter_share,
					   tbl_guarantor_detail.gname,
					   tbl_guarantor_detail.address,
					   tbl_guarantor_detail.gnature,
					   tbl_lookups.lookup_name,
					   tbl_courses.course_name,
					   tbl_courses.course_duration
					   
	            FROM tbl_loanee_detail 
	            INNER JOIN tbl_loan_detail ON  (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number)
                INNER JOIN tbl_scheme_master ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
	            INNER JOIN tbl_sectors ON  (tbl_scheme_master.sector=tbl_sectors.sector_id) 
	            INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id)
				INNER JOIN tbl_tehsil ON  (tbl_loanee_detail.tehsil=tbl_tehsil.tehsil_id)
				INNER JOIN tbl_panchayt ON  (tbl_loanee_detail.panchayat=tbl_panchayt.panchayt_id)
				INNER JOIN tbl_guarantor_detail ON  (tbl_loanee_detail.loanee_id=tbl_guarantor_detail.loanee_id)
				INNER JOIN tbl_lookups ON  (tbl_lookups.lookup_id=tbl_guarantor_detail.gnature)
				INNER JOIN tbl_courses ON  (tbl_loanee_detail.education=tbl_courses.course_id)
				
	            where tbl_loanee_detail.refno = '5050' AND tbl_courses.course_id = '".$course."' $cond";


        // echo $sql;
        /* echo  $sql = "SELECT *
          FROM tbl_loanee_detail
          INNER JOIN tbl_loan_detail ON  (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number)
          INNER JOIN tbl_scheme_master ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id)
          INNER JOIN tbl_sectors ON  (tbl_scheme_master.sector=tbl_sectors.sector_id)
          INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id)



          where tbl_district.district_name Like '%".$district."%'  ";
         */


//$sql = "SELECT * FROM tbl_district  where district_name Like '%".$district."%'";
        $pdfurl = $base_url . "/EducationLoanDetailReportpdf.php?op=loaneedetail_report&district=$district&tehsil=$tehsil&panchayat=$panchayat
		&course=$course&from=$from_date&to=$to_date";


        $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
    }

    $res = pager_query($sql, 10, 0, $count_query);
    $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";


    $output = '<div class="listingpage_scrolltable"><table cellpadding="0" cellspacing="2" border="0" width="100%">
	<tr class="oddrow"><td align="left"><h2 style="text-align:left;">Education Loan Scheme Report</h2></td>
	<tr>
	<td align="right">
	<a target="_blank" href="' . $pdfurl . '"><img src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	
	</tr>
	</table></div>';

    //$output .='';
    $output .='<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="1" border="0" id="wrapper2"><tr>';
    
	$output .= "<th colspan = '16'>During the Course</th>
	            <th colspan = '4'>After Completion of Course</th>
				</tr><tr>";
	
	$output .= "<th>S.No.</th>
	            <th>Education Loan Account No.</th>
				<th>Course Name</th>
				<th>Course Duration</th>
				<th>Date of Commencement of Course</th>
				<th>Date of Completion of Course</th>
				<th>Loanee Name</th>
				<th>Gardian Name</th>
				<th>Gaurantor Name</th>
				<th>Gaurantor Address</th>
				<th>Type of Gaurantee</th>
				<th>Sanction Amount</th>
				<th>Disbursed Amount</th>
				<th>Promoter Share</th>
				<th>Total Term Loan</th>
				<th>Recovery During Course</th>
				<th>Total Simple Interest</th>
				<th>Principal Amount Including SI Amount</th>
				<th>Compount Interest Amount</th>
				<th>LD & Other Charges</th>
				<th>Recovery After Completion of Course</th>
				<th>Current Outstanding Balance.</th>
				</tr>";


    $limit = '10';
    if ($_REQUEST['page']) {
        $counter = $_REQUEST['page'] * $limit;
    } else {
        $counter = 0;
    }
    //$counter =0;
	
	//course name fetched.
		

    while ($rs = db_fetch_object($res)) {
        $counter++;
        $loanesql = db_query("select sum(amount) as recovery from tbl_loan_repayment where loanee_id = '" . $rs->loanee_id . "'");
        $resql = db_fetch_object($loanesql);
		
		$intsql = db_query("select sum(amount) as intamount from tbl_loan_repayment where loanee_id = '" . $rs->loanee_id . "'");
        $insql = db_fetch_object($intsql);
        
        if ($counter % 2 == 0) {
            $cla = "even";
        } else {
            $cla = "odd";
        }
		
		//Calculation of Promoter Share.
		$promoter_share = round(($rs->loan_amount)*($rs->promoter_share)/100);
		// Total Term Loan Calculation.
		$total_term_loan = round($rs->disbursed_amount - $promoter_share); 
		//Calculation of Principal amount including SI accrued.
		$principal_siacc = round($total_term_loan + $insql->intamount - $resql->recovery);
		
        $output .='<tr class="' . $cla . '">
				   <td class="center" width="5%">' . $counter . '</td>
				   <td >' . ucwords($rs->account_id) . '</td>
                   <td >' . ucwords($rs->course_name) . '</td>
				   <td >' . ucwords($rs->course_duration) .' months</td>
				   <td >' . ucwords($rs->fname) . ' ' . ucwords($rs->lname) . '</td>
				   <td >' . ucwords($rs->fh_name) . '</td>
				   <td >' . ucwords($rs->gname) . '</td>
				   <td >' . ucwords($rs->address) . '</td>
				   <td >' . ucwords($rs->lookup_name) . '</td>
				   <td align="right">' . round($rs->loan_amount) . '</td>
				   <td align="right">' . round($rs->disbursed_amount) . '</td>
				   <td align="right">' . round($promoter_share) . '</td>
				   <td align="right">' . round($total_term_loan) . '</td>
				   <td align="right">' . round($resql->recovery) . '</td>
				   <td align="right">' . round($insql->intamount) . '</td>
				   <td align="right">' . round($principal_siacc) . '</td>
				   <td align="right">' . ' '. '</td>
				   <td align="right">' . ' '. '</td>
				   <td align="right">' . ' '. '</td>
				   <td align="right">' . ' '. '</td>
	               </tr>';
    }

    if ($counter > 0) {

        $output .='</table></div>';
        echo $output .= theme('pager', NULL, 10, 0);
    } else {
        echo '<font color="red"><b>No Record found...</b></font>';
    }
}
?>