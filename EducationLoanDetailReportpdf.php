<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HBCFDC');
$pdf->SetTitle('HBCFDC');
$pdf->SetSubject('HBCFDC');
$pdf->SetKeywords('HBCFDC');
$pdf = new TCPDF(L, PDF_UNIT, A3, true, 'UTF-8', false);
//$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, '','');
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetPrintHeader(false);
//set margins
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('helvetica', '', 10);
// add a page
$pdf->AddPage();
//getting number of page $pdf->pagenumber()

if($_REQUEST['op'] == 'educationloans_schemereport'){
global $user, $base_url;
$district_id = $_REQUEST['district'];
echo 'District id is = '.$district_id;
$tehsil_id = $_REQUEST['tehsil'];
$panchayat_id = $_REQUEST['panchayat'];
$course_id = $_REQUEST['course']; 
$from_date = $_REQUEST['from'];
$to_date = $_REQUEST['to'];
$output.='';
//$output .= 'District is = '.$to_date;
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Times New Roman;
font-size: 13pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:1040px;
}
table.tbl_border{border:1px solid #1D374C; 
background-color:#1D374C;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
color: #ffffff;
background-color:#1D374C;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">';

$output .='<tr><td colspan="0" align="center" class="header_report">HIMACHAL BACKWARD CLASSES FINANCE AND DEVELOPMENT CORPORATION KANGRA (H.P.) </td></tr><tr><td  class="header_report">Education Loan Scheme Report(From: '.$from_date.' 
 To: '.$to_date.') </td></tr><tr ><td align="left"><strong>Date : '.date("d/m/Y").'</strong></td></tr><tr ><td align="center"><strong></strong></td></tr>
</table>';
	


$val = '%'.strtoupper($district_id).'%'; 
$key=addslashes($val);
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
		
		$result = db_query($sql);
	    $rs2 = db_fetch_object($result);

        if ($district_id)
        {
            $output .='<tr><td><b>District Name :</b><strong> '.ucwords($rs2->district_name).'</strong><br></td></tr>';
        }
		if ($tehsil_id)
        {
            $output .='<tr><td><b>Tehsil Name :</b><strong> '.ucwords($rs2->tehsil_name).'</strong><br></td></tr>';
        }
		if ($panchayat_id)
        {
            $output .='<tr><td><b>Panchayat Name :</b><strong> '.ucwords($rs2->panchayt_name).'</strong><br></td></tr>';
        }
		if ($course_id)
        {
            $output .='<tr><td><b>Course Name :</b><strong> '.ucwords($rs2->course_name).'</strong><br></td></tr>';
        }



    $output .='<table cellpadding="3" cellspacing="2" id="wrapper" class="tbl_border" width="1410px">';
       
	$output .= '<tr><td class="header2" colspan="16">During the Course</td>
	            <td class="header2" colspan="4">After Completion of Course</td>
				</tr>';
    $output .='<tr>
   				<td class="header2" width="40">S.No.</td>
				<td class="header2">Education Loan Account No.</td>
				<td class="header2">Course Name</td>
				<td class="header2">Course Duration</td>
				<td class="header2">Date of Commencement of Course</td>
				<td class="header2">Date of Completion of Course</td>
				<td class="header2">Loanee Name</td>
				<td class="header2">Gardian Name</td>
				<td class="header2">Gaurantor Name</td>
				<td class="header2">Gaurantor Address</td>
				<td class="header2">Type of Gaurantee</td>
				<td class="header2">Sanction Amount</td>
				<td class="header2">Disbursed Amount</td>
				<td class="header2">Promoter Share</td>
				<td class="header2">Total Term Loan</td>
				<td class="header2">Recovery During Course</td>
				<td class="header2">Total Simple Interest</td>
				<td class="header2">Principal Amount Including SI Amount</td>
				<td class="header2">Compound Interest Amount</td>
				<td class="header2">LD & Other Charges</td>
				<td class="header2">Recovery After Completion of Course</td>
				<td class="header2">Current Outstanding Balance.</td>
				</tr>';
				
 $res = db_query($sql);
 $counter=1;
 $resgrand = db_query($grandsql);
    while ($rsgrand = db_fetch_object($resgrand)) {
		$loang += $rsgrand->loantotal;
		$apexg += $rsgrand->apexgtotal;
		$corpg += $rsgrand->corpgtotal;
		$promog += $rsgrand->promogtotal;
		}
 while($rs = db_fetch_object($res)){
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
   
   
   $loanesql = db_query("select sum(amount) as recovery from tbl_loan_repayment where loanee_id = '" . $rs->loanee_id . "'");
   $resql = db_fetch_object($loanesql);
		
   $intsql = db_query("select sum(amount) as intamount from tbl_loan_repayment where loanee_id = '" . $rs->loanee_id . "'");
   $insql = db_fetch_object($intsql);
   
   //Calculation of Promoter Share.
		$promoter_share = round(($rs->loan_amount)*($rs->promoter_share)/100);
		// Total Term Loan Calculation.
		$total_term_loan = round($rs->disbursed_amount - $promoter_share); 
		//Calculation of Principal amount including SI accrued.
		$principal_siacc = round($total_term_loan + $insql->intamount - $resql->recovery);
		$start_date = '2014-12-12';
		$completion_date = '2016-12-31';
		
        
				 $output .='<tr>
					  <td class="'.$class.'">'.$counter.'</td>
					  <td class="'.$class.'">'.ucwords($rs2->account_id).'</td>
					  <td class="'.$class.'" style="height:65px;">'.ucwords($rs2->course_name).'</td>
					   <td class="'.$class.'">'.ucwords($rs2->course_duration).' Months</td>
					   <td class="'.$class.'">'.ucwords($start_date).'</td>
					   <td class="'.$class.'">'.ucwords($completion_date).'</td>
					    <td class="'.$class.'">' . ucwords($rs2->fname) . ' ' . ucwords($rs2->lname) . '</td>
						 <td class="'.$class.'">' . ucwords($rs2->fh_name) . '</td>
						  <td class="'.$class.'">' . ucwords($rs2->gname) . '</td>
						  <td class="'.$class.'">' . ucwords($rs2->address) . '</td>
						  <td class="'.$class.'">' . ucwords($rs2->lookup_name) . '</td>
						  <td class="'.$class.'" align="right">' . round($rs2->loan_amount) . '</td>
						  <td class="'.$class.'" align="right">' . round($rs2->disbursed_amount) . '</td>
						  <td class="'.$class.'" align="right">' . round($promoter_share) . '</td>
						  <td class="'.$class.'" align="right">' . round($total_term_loan) . '</td>
						  <td class="'.$class.'" align="right">' . round($resql->recovery) . '</td>
						  <td class="'.$class.'" align="right">' . round($insql->intamount) . '</td>
						  <td class="'.$class.'" align="right">' . round($principal_siacc) . '</td>
						  <td class="'.$class.'" align="right">' . ' '. '</td>
				          <td class="'.$class.'" align="right">' . ' '. '</td>
				          <td class="'.$class.'" align="right">' . ' '. '</td>
				          <td class="'.$class.'" align="right">' . ' '. '</td>
						  <td class="'.$class.'" align="right">' . ' '. '</td>
	               
						</tr>';
				$counter++;
 }
if($cl == 'header4_1')
	$cl = 'header4_2';
else
	$cl = 'header4_1';


 $output .='</table>';
		$output .='</table><table border="0" style="width:930px;"><tr >
						<td colspan="8" width="105%">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr ><tr><td width="5%">&nbsp;</td><td width="55%"><b>Grand Total</b></td>
		<td align="right" width="27%"><b>'.round(181250).'</b></td>
		<td align="right" width="8%"><b>'.round(181250).'</b></td>
		<td align="right" width="8%"><b>'.round(9063).'</b></td>
		<td align="right" width="8%"><b>'.round(172187).'</b></td>
		<td align="right" width="8%"><b>'.round(19750).'</b></td>
		<td align="right" width="8%"><b>'.round(19750).'</b></td>
		<td align="right" width="8%"><b>'.round(172187).'</b></td>
		<td align="right" width="8%"><b>'.round($promog).'</b></td>
		<td align="right" width="8%"><b>'.round($corpg).'</b></td>
		<td align="right" width="8%"><b>'.round($promog).'</b></td></tr><tr >
		
		
						<td colspan="8" width="105%">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr >';
		
		 $output .='</table>';
	
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('loaneedetail_report_'.time().'.pdf', 'I');
}
}
