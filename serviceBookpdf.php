<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/pdfcss.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A3, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION');
$pdf->SetTitle('HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION');
$pdf->SetSubject('HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION');
$pdf->SetKeywords('HIMACHAL BACKWARD CLASSES FINANCE & DEVELOPMENT CORPORATION');

//$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, '','');
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
				
				$designationid = getLookupName ( $rs->current_designationid );


$output .='<table  border="0"  height="40%" >';
//$output .='<tr ><td >&nbsp;</td></tr>';
//$output .='<tr ><td colspan="2" align="center"><h2>I-BIO-DATA</h2></td></tr>';

//$output .='<tr ><td width="50%">UID:</td><td >'.$unique.'</td></tr>';
//$rid = getRole($rs->program_uid);
 global $user,$base_url;
	$cnode = node_load($rs->nid);

  $user_pic = $rs->field_photo_upload;
  if($user_pic==''){
  
  $user_pic='sites/default/files/defaultimage.jpg';
  
  }
 
  $output .='
<tr><td class="header_report" width="74%" align="right" hight="40%" colspan="2">SERVICE BOOK</td><td width="40%"></td><td class="header_report1"  rowspan="4" width="14%"  hight="60%"   align="right"><div  class="fright"><img border="1"  src="'.$user_pic.'"  /></div> </td></tr>
<!--<tr><td colspan="0" class="header1" align="right">Status as on '.date("d-m-Y").'</td></tr>-->';

  
  
  $output .='<tr ><td align="center" colspan="3" class="header4_2 header_report">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I-BIO-DATA</td></tr>';
  
$output .='</table>';
$output .='<br><br><br>';

$output .='<table align="center" cellpadding="7" cellspacing="1" border="0" id="form-container" width="1650px">';

//print_r($cnode->field_photo_upload[0]);
//$output .='<tr ><td colspan="2" align="right"><div class="fright"><img src="'.$base_url.'/'.$user_pic.'" width="100" height="100" /></div></td></tr>';
$output .='<br><br>';
$output .='<tr><td width="12%"></td><td  align="left" width="42%"><b>Employee Id:</b></td><td align="left">'.ucwords($rs->employee_id).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Employee Name:</b></td><td align="left" > '.ucwords($rs->employee_name).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Father Name:</b></td><td align="left" > '.ucwords($rs->father_name).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Nationality:</b></td><td align="left"  > '.ucwords(getLookupName($rs->nationality)).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Caste:</b></td><td align="left"  > '.ucwords(getCastemain($rs->caste)).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Block:</b></td><td align="left"  > '.ucwords($rs->block).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Tehsil:</b></td><td align="left"  > '.ucwords($gettehsil->tehsil_name).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Panchayat:</b></td><td align="left"  > '.ucwords($rs->panchayat).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Designation:</b></td><td align="left"  > '.ucwords($designationid).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Date of Birth:</b></td><td align="left" >'.date('d-m-Y',strtotime(substr($rs->dob,0,10))).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Date of Joining:</b></td><td align="left" >'.date('d-m-Y',strtotime(substr($rs->doj,0,10))).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Educational Qualification:</b></td><td align="left" > '.ucwords($rs->edu_qual).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Professional Qualification:</b></td><td align="left" > '.ucwords($rs->prof_qual).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Height (in feet):</b></td><td align="left" > '.ucwords($rs->height).'</td></tr>';

$output .='<tr ><td></td><td align="left" ><b>Personal Mark Of Identity:</b></td><td align="left" > '.ucwords($rs->mark).'</td></tr>';

$output .='';$output .='';

if($rs->pincode!=''){
	$output .='<tr ><td></td><td align="left" ><b>Permanent Home Address:</b></td><td  align="left" ><div style="width:50px;">'.ucwords($rs->add_line1).', '.ucwords($rs->add_line2).', '.$rs->pincode.'</div></td></tr>';
}else{
$output .='<tr ><td></td><td align="left" ><b>Permanent Home Address:</b></td><td  align="left" ><div style="width:50px;">'.ucwords($rs->add_line1).', '.ucwords($rs->add_line2).'</div></td></tr>';


}
$output .='<tr ><td></td><td align="left" ><b>Signature or left hand thumb impression of the Government servant (with date):</b></td><td ></td></tr>';


$output .='<tr ><td></td><td align="left" ><b>Signature and designation of attesting officer (with date):</b></td><td ></td></tr>';
$output .='<br><br><br>';

$output .='<tr ><td align="center" colspan="3"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*To be attested by the head of office before posting</b></td></tr>';

$output .='<tr ><td  align="center"  colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Note:-Photograph should be renewed after 10 years of service of Government servant.</b></td></tr>';

$output .='</table></div>';
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
table.tbl_border{border:1px solid #ccc;
background-color:#ccc;
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
color: ;
background-color:#ccc;
font-family:Verdana;
font-size: 12pt;
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
background-color:#F7F7F7;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
.header4_2  {
color:#222222;
background-color:#E8E8E8;
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
$medico=date('d-m-Y',strtotime($rs->medical_on));
if($medico== '01-01-1970'){$medico='';}
$output .='<br/><br/><table width="100%" cellpadding="3" cellspacing="2" border="0" id="form-container2" class="tbl_border2">';
$output .='<tr ><td colspan="2" align="center" class="header_report">II-CERTIFICATE AND ATTESTATION</td></tr>';
//$output .='<tr ><td >&nbsp;</td></tr>';
//$output .='<tr ><td >&nbsp;</td></tr>';
//$output .='<tr ><td width="50%">UID:</td><td >'.$unique.'</td></tr>';
//$rid = getRole($rs->program_uid);
$output .='<tr><td  width="5%" class="header2">S. No.</td><td width="12%" class="header2">Subject</td><td width="60%" class="header2">Certificate</td><td width="22.5%" class="header2">Signature and Designation of Certifying officer</td></tr>';

$output .='<tr ><td class="header4_1" width="5%" align="center">1.</td><td width="12%" class="header4_1">Medical Examination</td><td width="60%" class="header4_1">The employee was medically examined by <span style="text-decoration:underline">'.$rs->medical_by.'</span> on <span style="text-decoration:underline">'.$medico.'</span> and found fit. The medical certificate has been kept in safe custody vide S. No. <span style="text-decoration:underline">'.$rs->medical_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp; '.$rs->medical_certified_by.'<br> '.getLookupName($rs->medical_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2" align="center">2.</td><td width="12%" class="header4_2">Character and Antecedents</td><td width="60%" class="header4_2">His/Her character and antecedents have been verified and the verification report kept in safe custody vide S. No. <span style="text-decoration:underline">'.$rs->character_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_2">&nbsp;'.$rs->character_certified_by.'<br> '.getLookupName($rs->character_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1" align="center">3.</td><td width="12%" class="header4_1">Allegiance to the Constitution</td><td width="60%" class="header4_1">He/She has taken the oath of allegiance/affirmation to the constitution vide S. No. <span style="text-decoration:underline">'.$rs->allegiance_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1"> &nbsp; '.$rs->allegiance_certified_by.'<br>'.getLookupName($rs->allegiance_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2" align="center">4.</td><td width="12%" class="header4_2">Oath of Secrecy</td><td width="60%" class="header4_2">He/She has read the Official Secrets Act and Central Services (Conduct) Rules and has also takent the oath of secrecy vide S. No. <span style="text-decoration:underline">'.$rs->oath_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_2">&nbsp; '.$rs->oath_certified_by.'<br> '.getLookupName($rs->oath_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1" align="center">5.</td><td width="12%" class="header4_1">Marital Status</td><td width="60%" class="header4_1">He/She hsa furnished declaration regarding his/her not having contracted bigamous marriage. he relevant declaration has been filled at S. No. <span style="text-decoration:underline">'.$rs->marital_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp; '.$rs->marital_certified_by.'<br> '.getLookupName($rs->marital_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2" align="center">6.</td><td width="12%" class="header4_2">Declaration</td><td width="60%" class="header4_2">He/She has furnished the declaration of ?????????</td><td width="22.5%" class="header4_2">&nbsp; '.$rs->declaration_certified_by.'<br>'.getLookupName($rs->declaration_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1" align="center">7.</td><td width="12%" class="header4_1">Verification of entries in Part-I.</td><td width="60%" class="header4_1">The correctness of the entries against S. No. 5-8 of part-I "Bio-data" has been verified from original certificates considered as valid documentary evidence for the respective purposes. Attested copies of these certificates have been filed at S. No. <span style="text-decoration:underline">'.$rs->verification_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp; '.$rs->verification_certified_by.'<br> '.getLookupName($rs->verification_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2" align="center">8.</td><td width="12%" class="header4_2">(a) G.P.F. No. &nbsp; Nomination for G.P.F.</td><td width="60%" class="header4_2"><span style="text-decoration:underline">'.$gpf->sno.'</span> &nbsp; He/She has filed nomination of G.P.F and the following related notices which have been forwarded to the Accouns Officer on dates shown against them have been filed in Volume-II of the Service Book. &nbsp; <span style="text-decoration:underline">'.$rs->gpf_nomination.'</span></td><td width="22.5%" class="header4_2">&nbsp;'.$rs->gpf_certified_by.'<br> '.getLookupName($rs->gpf_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_1" align="center">9.</td><td width="12%" class="header4_1">Family Particulars</td><td width="60%" class="header4_1">He/She has furnished details of the family members which have been filed at Sr. No. <span style="text-decoration:underline">'.$rs->family_sno.'</span> of Volume-II of the Service Book.</td><td width="22.5%" class="header4_1">&nbsp;'.$rs->family_certified_by.'<br>'.getLookupName($rs->family_designationid).'</td></tr>';

$output .='<tr ><td width="5%" class="header4_2" align="center">10.</td><td width="12%" class="header4_2">D.C.R gratuity and family pension.</td><td width="60%" class="header4_2">He she has filed nomination for D.C.R. gratuity and family pension and and the following related notices which have been filed in Volume-II of the Service Book vide Sl. Nos. shown against them:- <span style="text-decoration:underline">'.$rs->dcr_nomination.'</span></td><td width="22.5%" class="header4_2">&nbsp;'.$rs->dcr_certified_by.'<br> '.getLookupName($rs->dcr_designationid).'</td></tr>';

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
width:892px;
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

tr.header2 {
border-bottom-color:#FFFFFF;
color:;
background-color:#ccc;
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
background-color:#F7F7F7;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
.header4_2  {
color:#222222;
background-color:#E8E8E8;
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
$output .='<table width="100%" cellpadding="3" cellspacing="2" border="0" id="form-container1" class="tbl_border1">';

//$output .='<div style="page-break-before: always;">';

$output .='<tr ><td colspan="2" align="center" class="header_report">III-HISTORY OF SERVICES</td></tr>';



$output .='<table width="100%" cellpadding="2" cellspacing="2" border="0" class="tbl_border12"><tr class="header2" >
<td  >S. No.</td>
<td >Name of Post</td>
<td >Whether substantive or officiating and whether permanent or temporary</td>
<td >If officiating state-(i)Substantive appointment or(ii) Whether service counts for pension under act 371 G.S.R</td>
<td  >Pay in substantive post</td>
<td  >Additional pay for officiating</td>
<td  >Other emoluments falling under the term "pay"</td>
<td  >Date of appointment</td>
<td >Signature of Government Servant</td>
<td   >Signature and designation of the head of the office or other attesting officer in attestation of columns 1 to 6</td>
<td  >Date of termination of appointment</td>
<td   >Reasons for termination (such as promoion, transfer, dismissal etc.)</td>
<td  >Signature of the head of the office or other attesting officer</td>
<td   >Verification of periods of service</td>
<td  >Reference to any recorded punishment or censure, or reward or praise of the Government servant</td>
</tr>';




$blnk='';
$sql="select * from tbl_transferpromotions where employee_id='".$employee_id."'";
//$sql="select * from tbl_joinings where employee_id='".$employee_id."'";
 $res = db_query($sql);
 $counter=1;
 while($ks = db_fetch_object($res)){
  
  $ds=db_query("select releiving_date from tbl_transferpromotions where employee_id='".$employee_id."'  and releiving_date > '".$ks->joining_date."'");
  $rds = db_fetch_object($ds);
  //if($rds->releiving_date=="1970-01-01"){}else{$rdsd=NULL;}//$rdsd=$rds->releiving_date;}
  
  if($rds->releiving_date){
    $radet =date('d-m-Y',strtotime($rds->releiving_date));
  }else{
   $radet = 'N/A';
  }
  
   if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}
 
   
				$output .='<tr>
				<td class="'.$class.'" align="center">'.$counter.'</td>
				<td  class="'.$class.'" align="left">'.ucwords(getLookupName($ks->current_designationid)).'</td>
				<td  class="'.$class.'" align="left">'.$blnk.'</td>
				<td   class="'.$class.'" align="left">'.$blnk.'</td>
				<td   class="'.$class.'" align="center">'.round(($rs->grade_pay + $rs->basic_pay),2).'</td>
				<td   class="'.$class.'" align="center">'.$blnk.'</td>
				<td  class="'.$class.'" align="left">'.$blnk.'</td>
				<td   class="'.$class.'" align="left">'.date('d-m-Y',strtotime($ks->joining_date)).'</td>
				<td  class="'.$class.'" align="left">'.$blnk.'</td>
				<td   class="'.$class.'" align="left">'.$blnk.'</td>
				<td   class="'.$class.'" align="left">'.$radet.'</td>
				<td   class="'.$class.'" align="left">'.getLookupName($ks->action).'</td>
				<td   class="'.$class.'" align="left">'.$blnk.'</td>
				<td  class="'.$class.'" align="left">'.$blnk.'</td>
				<td   class="'.$class.'" align="left">'.$blnk.'</td>
				</tr>';
				$counter++;
 


}

//$output .='</div>';
		
		 $output .='</table>';



//-------------------------------------------------LEAVE MANAGEMENT------------------------------


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
width:892px;
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

tr.header2 {
border-bottom-color:#FFFFFF;
color:;
background-color:#ccc;
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
background-color:#F7F7F7;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
.header4_2  {
color:#222222;
background-color:#E8E8E8;
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
$output .='<table width="100%" cellpadding="3" cellspacing="2" border="0" id="form-container1" class="tbl_border1">';

//$output .='<div style="page-break-before: always;">';

$leavename1=db_query("select * from tbl_leavetype where status = 1");


$output .='<tr ><td colspan="2" align="center" class="header_report">IV-DETAILED DESCRIPTION OF ALL KINDS OF LEAVE</td></tr>';



$output .='<table width="100%" cellpadding="2" cellspacing="2" border="0" class="tbl_border12"><tr class="header2" >';
while($leavename=db_fetch_object($leavename1)){
$output .='<td align="center">'.$leavename->leave_name.'</td>';
}
/*
<td >Half Pay Leave</td>
<td >Commuted Leave</td>
<td  >Leave Not Due</td>
<td  >Extraordinary Leave(with or without medical certificate)</td>
<td  >Maternity Leave</td>
<td  >Paternity Leave</td>
<td  >Leave travelled concession availed of</td>
*/
$output .='</tr>';




$blnk='';



function leavetypenum($empid,$leavetype_name){

$lsql ="select * from tbl_leave_management where emp_id='".$empid."' AND leave_type='".$leavetype_name."'";
$lres = db_query($lsql);
while($lrs= db_fetch_object($lres))
	{
	if($lrs->day_of_leave == 2){
	$leaveday += $lrs->no_of_daye;
	}
	else if($lrs->day_of_leave == 1){
	$leaveday +=0.5;
	}
	
	}
	return $leaveday;
}


$earned_leave=leavetypenum($employee_id,1); if($earned_leave==''){$earned_leave=0;}
$half_pay=leavetypenum($employee_id,17);if($half_pay==''){$half_pay=0;}
$commuted_leave=leavetypenum($employee_id,8);if($commuted_leave==''){$commuted_leave=0;}
$extraordinary=leavetypenum($employee_id,10);if($extraordinary==''){$extraordinary=0;}
$leave_travel=leavetypenum($employee_id,18);if($leave_travel==''){$leave_travel=0;}
$paternity=leavetypenum($employee_id,9);if($paternity==''){$paternity=0;}
	$maternity=leavetypenum($employee_id,5);if($maternity==''){$maternity=0;}
	$leave_notdue=leavetypenum($employee_id,19);if($leave_notdue==''){$leave_notdue=0;}

$counter=1;
  
   if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}
 $output .='<tr>';
 
 $leavename1=db_query("select * from tbl_leavetype");
   while($leavename=db_fetch_object($leavename1)){
   $leaves=leavetypenum($employee_id,$leavename->leave_id); if($leaves==''){$leaves=0;}
   
$output .='<td class="'.$class.'" align="center">'.$leaves.'</td>';
}
				
				
				/*
				
				<td  >'.$earned_leave.'</td>
				<td  class="'.$class.'" align="center">'.$half_pay.'</td>
				<td   class="'.$class.'" align="center">'.$commuted_leave.'</td>
				<td   class="'.$class.'" align="center">'.$leave_notdue.'</td>
				<td   class="'.$class.'" align="center">'.$extraordinary.'</td>
				<td  class="'.$class.'" align="center">'.$maternity.'</td>
				<td   class="'.$class.'" align="center">'.$paternity.'</td>
				<td  class="'.$class.'" align="center">'.$leave_notdue.'</td>
			*/
				$output .='</tr>';
				$counter++;
 




//$output .='</div>';
		
		 $output .='</table>';


///--------------------------------LEAVE END



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
width:892px;
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

tr.header2 {
border-bottom-color:#FFFFFF;
color:;
background-color:#ccc;
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
background-color:#F7F7F7;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
.header4_2  {
color:#222222;
background-color:#E8E8E8;
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
$output .='<table width="100%" cellpadding="3" cellspacing="2" border="0" id="form-container1" class="tbl_border1">';

//$output .='<div style="page-break-before: always;">';

$output .='<tr ><td colspan="2" align="center" class="header_report"></td></tr>';


///-----------------functions

//////////func to find no of leaves based on the leave type
function leavetypenumdate($empid,$leavetype_name,$from,$to){

    $lsql2 ="select * from tbl_leave_management where emp_id='".$empid."' AND leave_type='".$leavetype_name."' AND from_date BETWEEN '".$from."' AND '".$to."'";
//$dodo=db_fetch_object($lsql2);

$lres2 = db_query($lsql2);
$noleavehalf=0;
$leaveday=0;
while($lrs2= db_fetch_object($lres2))
	{	
	if($lrs2->day_of_leave == 2){
	$leaveday += $lrs2->no_of_daye;
	}
	else if($lrs2->day_of_leave == 1){

	$leaveday +=0.5;
	$noleavehalf++;
	}
	
	}
	//if($leaveday !=''){echo $from.' '.'' .' '.$leaveday.' '.$to.$leaveday;exit;}
	return $leaveday+$noleavehalf;
}
/////////
//function for half leaves
function halfleavetypenumdate($empid,$leavetype_name,$from,$to){

$lsql ="select * from tbl_leave_management where emp_id='".$empid."' AND leave_type='".$leavetype_name."' AND from_date between ('".$from."' AND '".$to."')";
$lres = db_query($lsql);
$noleavehalf=0;
while($lrs= db_fetch_object($lres))
	{
	if($lrs->day_of_leave == 2){
	$leaveday += $lrs->no_of_daye;
	}
	else if($lrs->day_of_leave == 1){
	$leaveday +=0.5;
	$noleavehalf++;
	}
	
	}
	return $noleavehalf;
}
//////////////



///////////functions end



$output .='<table border="1" style="width:1410px;">
  <tr class="header2">
    <td colspan="11" rowspan="2" align="center">Earned Leave</td>
    <td colspan="22" align="center">Half Pay Leave on private affair and on Medical Certificate including commuted leave and leave not due</td>
    <td rowspan="5">Total Half Pay Leave Taken</td>
    <td rowspan="5">Balance of half pay leave on return from leave</td>
    <td rowspan="5">Any other kinds of leave viz. EOL/Maternity/Study/Disability/Hospital Quaren</td>
  </tr>
  <tr class="header2">
    <td colspan="22" align="center">Leave Taken</td>
  </tr>
  <tr class="header2">
    <td colspan="2" rowspan="2">Particulars of service in calender half year</td>
    <td rowspan="3">Completed months of service in calender half year</td>
    <td rowspan="3">E.L. credited at the begining of half year</td>
    <td rowspan="3">No. of days of EOL availed of during the previous calender half-year</td>
    <td rowspan="3">E.L To be deducted(1/10th of the period in col. 5)</td>
    <td rowspan="3">Total E.L. at credit in day(Col. 4+13-6)</td>
    <td colspan="3" rowspan="2">Leave Taken</td>
    <td rowspan="3">Balance of E.L. on return from leave</td>
    <td colspan="3" rowspan="2">Particulars of service in calender half-year</td>
    <td colspan="2" rowspan="2">Credit of leave</td>
    <td colspan="3" rowspan="2">Against the earning on half pay</td>
    <td colspan="3" rowspan="2">Commuted leave on medical certificate on full  pay</td>
    <td colspan="3" rowspan="2">Commuted leave without medical certificate for studies certified to be in public interest(limited to 180 days half pay leave converted into 90 days commuted leave in entire service)</td>
    <td rowspan="3">Commuted leave converetd into half pay leave (twice of column 22 and 22c)</td>
    <td colspan="7">Leave not due limited to 360 days in entire service</td>
  </tr>
  <tr class="header2">
    <td colspan="3">On Medical Certificate</td>
    <td colspan="3">Otherwise than on medical certificate limited to 180 days</td>
    <td rowspan="2">Total of leave not due(col 26+29)</td>
  </tr>
  <tr class="header2">
    <td>From</td>
    <td>To</td>
    <td>From</td>
    <td>To</td>
    <td>No. of days</td>
    <td>From</td>
    <td>To</td>
    <td>Completed month(each half year)</td>
    <td>Leave earned (in days)</td>
    <td>Leave at credit(col. 15+32)</td>
    <td>From </td>
    <td>To</td>
    <td>No. of days</td>
    <td>From</td>
    <td>To</td>
    <td>No. of Days</td>
    <td>From</td>
    <td>To</td>
    <td>No. of days</td>
    <td>From</td>
    <td>To</td>
    <td>No.of days</td>
    <td>From</td>
    <td>To</td>
    <td>No.of days</td>
  </tr>
';







$blnk='';
//all leaves
$kku="select * from tbl_leave_management where emp_id='".$employee_id."' order by from_date ASC";


/// The first calender half year starts from the year of joining
$kkv=db_query("select doj from tbl_joinings where employee_id='".$employee_id."'");
$dv=db_fetch_object($kkv);


///date of joinin

$dateofjoin=db_query("select doj from tbl_joinings where employee_id='".$employee_id."'");
$dojoin1=db_fetch_object($dateofjoin);
$dojoin=$dojoin1->doj;

$curr_date1=date('Y-m-d');


// 

//year of joining of employee
$dojyear=date('Y',strtotime($dv->doj));

//current date year
$curr_date=date('Y');

$kao=1;

// to get the first calender half year

for($i=$dojyear;$i<=$curr_date;$i++){

$yearjan[$kao]='1-1-'.$i;
$yearjun[$kao]='30-6-'.$i;
$yearjul[$kao]='1-7-'.$i;
$yeardec[$kao]='31-12-'.$i;

$kao++;
}


// fetching the data from tbl_leavemanagement
$res=db_query($kku);
$lol=0;

 while($ks = db_fetch_object($res)){
  $lol++;
 
 // fetch the from - to dates of calender half year
   for($i=1;$i<=2;$i++){

   if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}

if($i==1){$from=$yearjan[$lol];$to=$yearjun[$lol];$from_prev=$yearjan[$lol-1];$to_prev=$yearjun[$lol-1];}else{$from=$yearjul[$lol];$to=$yeardec[$lol];$from_prev=$yearjul[$lol-1];$to_prev=$yeardec[$lol-1];}

$nomonths=dateDiffBymonths($dojoin, $to);

// to find the number of leaves both half pay and full day based on the type of leave 
$fromel=date('Y-m-d 00:00:00',strtotime($from));
$toel=date('Y-m-d 00:00:00',strtotime($to));
//$fromel='2012-01-01 00:00:00';
//$toel='2012-06-30 00:00:00';
$elcredited=leavetypenumdate($employee_id,4,$fromel,$toel);

$elcredited2=$elcredited;
if($elcredited>300){

$elcredited2=300; 
}

$eoleave=0;

$fromeol=date('Y-m-d 00:00:00',strtotime($from_prev));
$toeol=date('Y-m-d 00:00:00',strtotime($to_prev));

$eoleave=leavetypenumdate($employee_id,10,$fromeol,$toeol);

if($to_prev<$dojoin){

$eoleave=0;
}

///eoleaves deduct

$count=0;
$countit=$eoleave/10;
$notodeduct=1;
$notodeduct=$notodeduct*$countit;


//
///total earned leaves at credit in day


$elinday=$elcredited2-$notodeduct;



///

//echo "select no_of_leave from tbl_leaveallocation where leavetype_name=1 and timestamp BETWEEN '".strtotime($from)."' and '".strtotime($to)."'";exit;
$allocated1=db_query("select no_of_leave from tbl_leaveallocation where leavetype_name=1 and timestamp BETWEEN '".strtotime($from)."' and '".strtotime($to)."'");
$allocated2=db_fetch_object($allocated1);
$allocated=$allocated2->no_of_leave;
//$noofel=$allocated2->no_of_leave;

if($from==''){continue;}

$fromw=date('Y-m-d 00:00:00',strtotime($from));;

$tow=date('Y-m-d 00:00:00',strtotime($to));

$findleave1=db_query("select * from tbl_leave_management where emp_id='".$employee_id."' ");
$jama=0;
$test=1;
$noofel=0;
while($findleaves=db_fetch_object($findleave1)){


//find no of half pay leave

$findleavesfrom=date('Y-m-d',strtotime($findleaves->from_date));
$findleavesto=date('Y-m-d',strtotime($findleaves->to_date));

 $lsql ="select * from tbl_leave_management where emp_id='".$employee_id."'  AND  ( from_date between '".$findleavesfrom."' AND '".$findleavesto."')";
$lres = db_query($lsql);
$noleavehalf=0;

while($lrs= db_fetch_object($lres))
	{
	if($lrs->day_of_leave == 2){
	$leaveday += $lrs->no_of_daye;
	}
	else if($lrs->day_of_leave == 1){
	$leaveday +=0.5;
	$noleavehalf++;
	}
	
	}


///
//against the earning on half pay



///

if($findleaves->leave_type==4){

//$noofel=$noofel-1;

}
if($findleaves->day_of_leave==1){


$halffrom=$findleaves->from_date;
if($halffrom=='1970-01-01'){$halffrom='';}
$halfto=$findleaves->to_date;
if($halfto=='1970-01-01'){$halfto='';}
$halfnoday=$findleaves->no_of_daye;

}
 if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}

$jama++;
$from3=$from; $to3=$to; $nomonths3=$nomonths; $elcredited3=$elcredited2; $eoleave3=$eoleave; $notodeduct3=$notodeduct; $elinday3=$elinday;
		if($jama==1){
		$from=''; $to=''; $nomonths=''; $elcredited2=''; $eoleave=''; $notodeduct=''; $elinday='';

		}
		
		if($test==1){$from5=$from3;$to5=$to3;$test++;}
		
		$findleavefromdate=$findleaves->from_date;
		$fromleavewa=date('d-m-Y',strtotime($findleavefromdate));
		if($fromleavewa=='01-01-1970'){$fromleavewa='';}
		$findleavetodate=$findleaves->to_date;
		$toleavewa=date('d-m-Y',strtotime($findleavetodate));
		if($toleavewa=='01-01-1970'){$toleavewa='';}
		
		//echo date('Y',strtotime($from3));exit;
		$no_days=$findleaves->no_of_daye;
		if(date('Y',strtotime ($from5))==date('Y',strtotime($fromleavewa))&& date('m',strtotime($fromleavewa))>=date('m',strtotime($from5)) && date('m',strtotime($fromleavewa))<=date('m',strtotime($to5))){}else{$fromleavewa='';$toleavewa='';$no_days='';
		$noofel='';}
		if(date('Y',strtotime ($from5))==date('Y',strtotime($halffrom))&& date('m',strtotime($halffrom))>=date('m',strtotime($from5)) && date('m',strtotime($halffrom))<=date('m',strtotime($to5))){$halffrom=date('d-m-Y',strtotime($halffrom));}else{$halffrom='';$halfnoday='';}
	
if($nomonths3=='' && $elcredited3=='' && $eoleav3=='' && $notodeduct3=='' && $elinday3=='' && $fromleavewa=='' && $toleavewa=='' && $no_days=='' && ($noofel=='' || $noofel==0) && $nomonths3=='' && $halfnoday=='' && $halfnoday=='')
	
	{
	continue;
	}
	//if($noofel==''){$noofel=0;}
	//if($no_days='1/2'){$no_days2=0.5;}else{$no_days2=$no_days;}
	if($no_days=='1/2'){$noo_day=0.5;}else{$noo_day= $no_days;}
	$diffofel=$elinday3-$noo_day;
	
	$noofel=$noofel+$diffofel;
//if($noofel==-1){echo $diffofel.' '.$elinday3.' '.$no_days.' '.$noofel;exit;};
	$output .='<tr class="'.$class.'">
				
				<td  tbl_helpdeskcomment align="left">'.$from3.'</td>
				<td  tbl_helpdeskcomment align="left">'.$to3.'</td>
				<td   tbl_helpdeskcomment align="left">'.$nomonths3.'</td>
				<td   tbl_helpdeskcomment align="center">'.$elcredited3.'</td>
				<td   tbl_helpdeskcomment align="center">'.$eoleav3.'</td>
				<td  tbl_helpdeskcomment align="left">'.$notodeduct3.'</td>
				<td   tbl_helpdeskcomment align="left">'.$elinday3.'</td>
			
			
					<td  tbl_helpdeskcomment align="left">'.$fromleavewa.'</td>
				<td   tbl_helpdeskcomment align="left">'.$toleavewa.'</td>
				<td   tbl_helpdeskcomment align="left">'.$no_days.'</td>
				<td   tbl_helpdeskcomment align="left">'.$noofel.'</td>
				<td   tbl_helpdeskcomment align="left">'.$from3.'</td>
				<td  tbl_helpdeskcomment align="left">'.$to3.'</td>
				<td   tbl_helpdeskcomment align="left">'.$nomonths3.'</td>



				<td   tbl_helpdeskcomment align="left">'.$nomonths.'</td>

				<td   tbl_helpdeskcomment align="left">'.$noleavehalfday.'</td>
				<td   tbl_helpdeskcomment align="left">'.$halffrom.'</td>
				<td   tbl_helpdeskcomment align="left">'.$halfto.'</td>
				<td   tbl_helpdeskcomment align="left">'.$halfnoday.'</td>
				
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>';
					
			
				$counter++;
   }}


}


//$output .='</div>';
		
		 $output .='</table>';




///-------------Earned Leave / Half Pay Leave----------------






///end

	$pdf->AddPage();
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('serviceBook_report_'.time().'.pdf', 'I');
}

