<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/pdfcss.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A4, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SC and ST');
$pdf->SetTitle('SC and ST');
$pdf->SetSubject('SC and ST');
$pdf->SetKeywords('SC and ST');

$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, 'Nikhil Bhawan, Power House Road Saproon, Solan-17321');
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
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


if($_REQUEST['op'] == 'serviceBook_report'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$employee_id = $_REQUEST['employee_id'];


$output='';
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
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:975px;
}
table.tbl_border{border:1px solid #a7c942;
background-color:#a7c942;
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
background-color:#a7c942;
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
.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
.header4_2  {
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
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">SERVICE BOOK</td></tr>
<tr><td class="header_report"  align="center"></td></tr>
<!--<tr><td colspan="0" class="header1" align="right">Status as on '.date("d-m-Y").'</td></tr>-->
<tr><td>&nbsp;</td></tr>
</table>';
	

 $sql="select * from tbl_joinings where employee_id='".$employee_id."'";

$emp1=db_query($sql);
$rs=db_fetch_object($emp1);

//////////////////////////////////////Bio Data

				$getstate1=db_query("select state_name from tbl_dsjestate where state_id='".$rs->state_id."'");
				$getstate=db_fetch_object($getstate1);
				$getdistrict1=db_query("select district_name from tbl_district where district_id='".$rs->district_id."'");
				$getdistrict=db_fetch_object($getdistrict1);
				$gettehsil1=db_query("select tehsil_name from tbl_tehsil where tehsil_id='".$rs->tehsil_id."'");
				$gettehsil=db_fetch_object($gettehsil1);
				


$output .='<table width="100%" cellpadding="2" cellspacing="1" border="1" id="form-container1" class="tbl_border1">';
//$output .='<tr ><td >&nbsp;</td></tr>';
//$output .='<tr ><td colspan="2" align="center"><h2>I-BIO-DATA</h2></td></tr>';
$output .='<tr ><td align="center" colspan="3" class="header4_2 header_report">I-BIO-DATA</td></tr>';
//$output .='<tr ><td width="50%">UID:</td><td >'.$unique.'</td></tr>';
//$rid = getRole($rs->program_uid);
 global $user,$base_url;
	$cnode = node_load($rs->nid);

  $user_pic = $rs->field_photo_upload;

//print_r($cnode->field_photo_upload[0]);
$output .='<tr ><td colspan="2" align="right"><div class="fright"><img src="'.$base_url.'/'.$user_pic.'" width="100" height="100" /></div></td></tr>';

$output .='<tr><td width="50%"  align="center"   ><b>Employee Id:</b></td><td align="center">'.ucwords($rs->employee_id).'</td><td width="16%" hight="10%" rowspan="6"><div class="fright"><img src="'.$base_url.'/'.$user_pic.'"  /></div> </td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Employee Name:</b></td><td align="center" > '.ucwords($rs->employee_name).'</td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Father Name:</b></td><td align="center" > '.ucwords($rs->father_name).'</td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Nationality:</b></td><td align="center"  > '.ucwords(getLookupName($rs->nationality)).'</td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Caste:</b></td><td align="center"  > '.ucwords(getLookupName($rs->caste)).'</td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Date of Birth:</b></td><td align="center" >'.date('d-m-Y',strtotime(substr($rs->dob,0,10))).'</td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Educational Qualification:</b></td><td align="center" > '.ucwords($rs->edu_qual).'</td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Professional Qualification:</b></td><td align="center" > '.ucwords($rs->prof_qual).'</td></tr>';

$output .='<tr ><td width="50%" align="center" ><b>Height (in feet):</b></td><td align="center" > '.ucwords($rs->height).'</td></tr>';

$output .='<tr ><td width="50%" align="center" class="header4_2"><b>Personal Mark Of Identity:</b></td><td align="center" class="header4_2"> '.ucwords($rs->mark).'</td></tr>';

$output .='';$output .='';

if($rs->pincode!=''){
$output .='<tr ><td width="50%" align="center" ><b>Permanent Home Address:</b></td><td width="50%"  align="center" >'.ucwords($rs->add_line1).', '.ucwords($rs->add_line2).', '.ucwords($rs->panchayat).','.ucwords($rs->block).',  '.ucwords($gettehsil->tehsil_name).', '.ucwords($getdistrict->district_name).', '.ucwords($getstate->state_name).', '.$rs->pincode.'</td></tr>';
}else{
$output .='<tr ><td width="50%" align="center" ><b>Permanent Home Address:</b></td><td width="50%"  align="center" >'.ucwords($rs->add_line1).', '.ucwords($rs->add_line2).', '.ucwords($rs->panchayat).','.ucwords($rs->block).',  '.ucwords($gettehsil->tehsil_name).', '.ucwords($getdistrict->district_name).', '.ucwords($getstate->state_name).'</td></tr>';


}
$output .='<tr ><td width="50%" align="center" class="header4_2"><b>Signature or left hand thumb impression of the Government servant (with date):</b></td><td width="50%" class="header4_2"></td></tr>';$output .='';

$output .='<tr ><td width="50%" align="center" ><b>Signature and designation of attesting officer (with date):</b></td><td width="50%" ></td></tr>';

$output .='<tr ><td align="center" class="header4_2" colspan="2"><b>*To be attested by the head of office before posting</b></td></tr>';

$output .='<tr ><td  align="center"  colspan="2"><b>Note:-Photograph should be renewed after 10 years of service of Government servant.</b></td></tr>';

$output .='</table>';
$pdf->writeHTML($output, true, 0, true, true);
/*
*/
$output ="";
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
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:975px;
}
table.tbl_border{border:1px solid #a7c942;
background-color:#a7c942;
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
background-color:#a7c942;
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
.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
.header4_2  {
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

$output .='<br/><br/><table width="100%" cellpadding="3" cellspacing="2" border="0" id="form-container" class="tbl_border">';
$output .='<tr ><td colspan="2" align="center" class="header_report">II-CERTIFICATE AND ATTESTATION</td></tr>';
//$output .='<tr ><td >&nbsp;</td></tr>';
//$output .='<tr ><td >&nbsp;</td></tr>';
//$output .='<tr ><td width="50%">UID:</td><td >'.$unique.'</td></tr>';
//$rid = getRole($rs->program_uid);
$output .='<tr ><td width="5%" class="header2">S. No.</td><td width="12%" class="header2">Subject</td><td width="60%" class="header2">Certificate</td><td width="20%" class="header2">Signature and Designation of Certifying officer</td></tr>';

$output .='<tr ><td class="header4_1" width="5%">1.</td><td width="12%" class="header4_1">Medical Examination</td><td width="60%" class="header4_1">The employee was medically examined by <span style="text-decoration:underline">'.$rs->medical_by.'</span> on <span style="text-decoration:underline">'.$rs->medical_on.'</span> and found fit. The medical certificate has been kept in safe custody vide S. No. <span style="text-decoration:underline">'.$rs->medical_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp; '.$rs->medical_certified_by.'<br> '.getLookupName($rs->medical_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2">2.</td><td width="12%" class="header4_2">Character and Antecedents</td><td width="60%" class="header4_2">His/Her character and antecedents have been verified and the verification report kept in safe custody vide S. No. <span style="text-decoration:underline">'.$rs->character_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_2">&nbsp;'.$rs->character_certified_by.'<br> '.getLookupName($rs->character_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1">3.</td><td width="12%" class="header4_1">Allegiance to the Constitution</td><td width="60%" class="header4_1">He/She has taken the oath of allegiance/affirmation to the constitution vide S. No. <span style="text-decoration:underline">'.$rs->allegiance_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1"> &nbsp; '.$rs->allegiance_certified_by.'<br>'.getLookupName($rs->allegiance_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2">4.</td><td width="12%" class="header4_2">Oath of Secrecy</td><td width="60%" class="header4_2">He/She has read the Official Secrets Act and Central Services (Conduct) Rules and has also takent the oath of secrecy vide S. No. <span style="text-decoration:underline">'.$rs->oath_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_2">&nbsp; '.$rs->oath_certified_by.'<br> '.getLookupName($rs->oath_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1">5.</td><td width="12%" class="header4_1">Marital Status</td><td width="60%" class="header4_1">He/She hsa furnished declaration regarding his/her not having contracted bigamous marriage. he relevant declaration has been filled at S. No. <span style="text-decoration:underline">'.$rs->marital_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp; '.$rs->marital_certified_by.'<br> '.getLookupName($rs->marital_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2">6.</td><td width="12%" class="header4_2">Declaration</td><td width="60%" class="header4_2">He/She has furnished the declaration of ?????????</td><td width="22.5%" class="header4_2">&nbsp; '.$rs->declaration_certified_by.'<br>'.getLookupName($rs->declaration_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1">7.</td><td width="12%" class="header4_1">Verification of entries in Part-I.</td><td width="60%" class="header4_1">The correctness of the entries against S. No. 5-8 of part-I "Bio-data" has been verified from original certificates considered as valid documentary evidence for the respective purposes. Attested copies of these certificates have been filed at S. No. <span style="text-decoration:underline">'.$rs->verification_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp; '.$rs->verification_certified_by.'<br> '.getLookupName($rs->verification_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2">8.</td><td width="12%" class="header4_2">(a) G.P.F. No. &nbsp; Nomination for G.P.F.</td><td width="60%" class="header4_2"><span style="text-decoration:underline">'.$gpf->sno.'</span> &nbsp; He/She has filed nomination of G.P.F and the following related notices which have been forwarded to the Accouns Officer on dates shown against them have been filed in Volume-II of the Service Book. &nbsp; <span style="text-decoration:underline">'.$rs->gpf_nomination.'</span></td><td width="22.5%" class="header4_2">&nbsp;'.$rs->gpf_certified_by.'<br> '.getLookupName($rs->gpf_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1">9.</td><td width="12%" class="header4_1">Family Particulars</td><td width="60%" class="header4_1">He/She has furnished details of the family members which have been filed at Sr. No. <span style="text-decoration:underline">'.$rs->family_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp;'.$rs->family_certified_by.'<br>'.getLookupName($rs->family_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2">10.</td><td width="12%" class="header4_2">D.C.R gratuity and family pension.</td><td width="60%" class="header4_2">He she has filed nomination for D.C.R. gratuity and family pension and and the following related notices which have been filed in Volume-II of the Service Book vide Sl. Nos. shown against them:- <span style="text-decoration:underline">'.$rs->dcr_nomination.'</span></td><td width="22.5%" class="header4_2">&nbsp;'.$rs->dcr_certified_by.'<br> '.getLookupName($rs->dcr_designationid).'</td></tr>';

//$output .='<tcpdf method="AddPage" />';
$output .='</table>';
//////////

$pdf->AddPage();
$pdf->writeHTML($output, true, 0, true, true);
//$output .='<div style="page-break-before: always;">';
//$output .='<tr ><td >&nbsp;</td></tr>';

$output="";
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
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:935px;
}
table.tbl_border{border:1px solid #a7c942;
background-color:#a7c942;
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
background-color:#a7c942;
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
.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
.header4_2  {
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
$output .='<table width="100%" cellpadding="3" cellspacing="2" border="0" id="form-container" class="tbl_border">';


//$output .='<div style="page-break-before: always;">';

$output .='<tr ><td colspan="2" align="center" class="header_report">II-HISTORY OF SERVICES</td></tr>';



$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border"><tr>
<td width="3%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Name of Post</td>
<td width="7%" colspan="1" align="center" class="header2">Whether substantive or officiating and whether permanent or temporary</td>
<td width="7%" colspan="1" align="center" class="header2">If officiating state-(i)Substantive appointment or(ii) Whether service counts for pension under act 371 G.S.R</td>
<td width="7%" colspan="1" align="center" class="header2">Pay in substantive post</td>
<td width="7%" colspan="1" align="center" class="header2">Additional pay for officiating</td>
<td width="7%" colspan="1" align="center" class="header2">Other emoluments falling under the term "pay"</td>
<td width="8%" colspan="1" align="center" class="header2">Date of appointment</td>
<td width="7%" colspan="1" align="center" class="header2">Signature of Government Servant</td>
<td width="7%" colspan="1" align="center" class="header2">Signature and designation of the head of the office or other attesting officer in attestation of columns 1 to 6</td>
<td width="8%" colspan="1" align="center" class="header2">Date of termination of appointment</td>
<td width="7%" colspan="1" align="center" class="header2">Reasons for termination (such as promoion, transfer, dismissal etc.)</td>
<td width="7%" colspan="1" align="center" class="header2">Signature of the head of the office or other attesting officer</td>
<td width="6%" colspan="1" align="center" class="header2">Verification of periods of service</td>
<td width="9%" colspan="1" align="center" class="header2">Reference to any recorded punishment or censure, or reward or praise of the Government servant</td>
</tr>';




$blnk='';
$sql="select * from tbl_transferPromotions where employee_id='".$employee_id."'";
//$sql="select * from tbl_joinings where employee_id='".$employee_id."'";
 $res = db_query($sql);
 $counter=1;
 while($ks = db_fetch_object($res)){
  
  $ds=db_query("select releiving_date from tbl_transferPromotions where employee_id='".$employee_id."'  and releiving_date > '".$ks->joining_date."'");
  $rds = db_fetch_object($ds);
  //if($rds->releiving_date=="1970-01-01"){}else{$rdsd=NULL;}//$rdsd=$rds->releiving_date;}
  
  if($rds->releiving_date){
    $radet =date('d-m-Y',strtotime($rds->releiving_date));
  }else{
   $radet = 'N/A';
  }
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
 
   
				$output .='<tr>
				<td width="3%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getLookupName($ks->current_designationid)).'</td>
				<td width="7%" class="'.$class.'" align="left">'.$blnk.'</td>
				<td width="7%" class="'.$class.'" align="left">'.$blnk.'</td>
				<td width="7%" class="'.$class.'" align="center">'.($rs->grade_pay + $rs->basic_pay).'</td>
				<td width="7%" class="'.$class.'" align="center">'.$blnk.'</td>
				<td width="7%" class="'.$class.'" align="left">'.$blnk.'</td>
				<td width="8%" class="'.$class.'" align="left">'.date('d-m-Y',strtotime($ks->joining_date)).'</td>
				<td width="7%" class="'.$class.'" align="left">'.$blnk.'</td>
				<td width="7%" class="'.$class.'" align="left">'.$blnk.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$radet.'</td>
				<td width="7%" class="'.$class.'" align="left">'.getLookupName($ks->action).'</td>
				<td width="7%" class="'.$class.'" align="left">'.$blnk.'</td>
				<td width="6%" class="'.$class.'" align="left">'.$blnk.'</td>
				<td width="9%" class="'.$class.'" align="left">'.$blnk.'</td>
				</tr>';
				$counter++;
 


}

//$output .='</div>';
		
		 $output .='</table>';
	$pdf->AddPage();
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('serviceBook_report_'.time().'.pdf', 'I');
}

