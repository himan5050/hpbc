<head>
<?php
global $base_url;
$css_path = $base_url . '/' . drupal_get_path('module', 'educationloans') . "/form_style.css";
?>

<link rel="stylesheet" type="text/css" href= <?php echo $css_path; ?> >
</head>


<div id="rec_participant">
    <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
        <tr><td align="left" class="tdform-width"><fieldset><legend>Education Loan Scheme Report</legend>
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
                            <td colspan="6" align="right"><div style="margin-right:93px;">
							<?php print drupal_render($form); ?></div></td>
                        </tr>
                    </table></fieldset>
            </td>
        </tr>
    </table>
</div>


<?php
$op = $_REQUEST['op'];

if($op == 'Generate Report'){
    $district_id = $_REQUEST['district_id'];
	$tehsil_id = $_REQUEST['tehsil_id'];
	$panchayat_id = $_REQUEST['panchayt_id'];
	$from_date = databaseDateFormat($_REQUEST['from_date']['date'], 'indian', '-');
	$to_date = databaseDateFormat($_REQUEST['to_date']['date'], 'indian', '-');
	$course_id = $_REQUEST['course_id'];
	
	
	if($district_id == '' && $tehsil_id == '' && $panchayat_id == '' && $course_id == ''){
	   form_set_error('form', 'Please enter the district, tehsil, panchayat or course.');
	}else{
	   if($district_id){
	     $cond .= ' and tbl_district.district_id = "' . $district_id . '"';
	   }
	   if($tehsil_id){
	     $cond .= ' and tbl_tehsil.tehsil_id = "' . $tehsil_id . '"';
	   }
	   if($panchayat_id){
	     $cond .= ' and tbl_panchayt.panchayt_id = "' . $panchayat_id . '"';
	   }
	   if($course_id){
	     $cond .= ' and tbl_courses.course_id = "' . $course_id . '"';
	   }
	   if($from_date && $to_date){
	     $cond .= ' and tbl_loan_detail.disbursed_date between "' . $from_date . '" and "' . $to_date . '"';
	   }
	   
	   //echo $cond;
	   
	   $sql = "SELECT  tbl_loan_detail.scheme_name,
                       tbl_loan_detail.loan_amount,
				       tbl_loan_detail.reg_number,
					   tbl_loan_detail.disbursed_date,
					   tbl_loan_detail.disbursed_amount,
					   tbl_loanee_detail.education,
				       tbl_loanee_detail.account_id,
				       tbl_loanee_detail.loanee_id,	  
				       tbl_loanee_detail.fname,tbl_loanee_detail.lname,
					   tbl_loanee_detail.fh_name,
				       tbl_loanee_detail.district,
   				       tbl_loanee_detail.tehsil,
					   tbl_loanee_detail.panchayat,
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
				
	            where tbl_loanee_detail.refno = '5050' $cond";
				
				$pdfurl = $base_url . "/EducationLoanDetailReportpdf.php?op=educationloans_schemereport&district=$district_id&tehsil=$tehsil_id&panchayat=$panchayat_id
		&course=$course_id&from=$from_date&to=$to_date";
				
				$count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
				$res = pager_query($sql, 10, 0, $count_query);
				$pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";
				
				$output .= '<div class="listingpage_scrolltable">
				           <table cellpadding="0" cellspacing="2" border="0" width="100%">
	                       <tr class="oddrow">
						   <td align="left"><h2 style="text-align:left;">Education Loan Scheme Report</h2></td>
	                       <tr>
	                       <td align="right">
	                       <a target="_blank" href="' . $pdfurl . '">
						   <img src="' . $pdfimage . '" alt="Export to PDF" 
						        title="Export to PDF" style="float:right;"/>
						   </a>
						   </td>
	                       </tr>
	                       </tr>
	                       </table>
						   </div>';
			
			   $output .='<div class="listingpage_scrolltable">
			              <table cellpadding="0" cellspacing="2" border="0" width="100%" id="wrapper2"><tr>';
    
	           $output .= '<th colspan = "18">During the Course</th>
	                       <th colspan = "4">After Completion of Course</th>
				           </tr><tr>';
	
	           $output .= '<th>S.No.</th>
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
				           </tr>';
			
		$limit = '10';
        if ($_REQUEST['page']) {
            $counter = $_REQUEST['page'] * $limit;
        } else {
            $counter = 0;
        }
		
		while ($rs = db_fetch_object($res)) {
            $counter++;
            $loanesql = db_query("select sum(amount) as recovery 
			                      from tbl_loan_repayment where loanee_id = '" . $rs->loanee_id . "'");
            $resql = db_fetch_object($loanesql);
		
		    $intsql = db_query("select sum(amount) as intamount 
			                    from tbl_loan_repayment where loanee_id = '" . $rs->loanee_id . "'");
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
					   <td >' . ucwords($rs->disbursed_date) .'</td>
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
}
?>