<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A3, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SC and ST');
$pdf->SetTitle('SC and ST');
$pdf->SetSubject('SC and ST');
$pdf->SetKeywords('SC and ST');

$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, 'Nikhil Bhawan, Power House Road Saproon, Solan-173211');
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
//$pdf->AddPage();




if($_REQUEST['op'] == 'rti_report'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];
$section = $_REQUEST['section'];
$district_id = $_REQUEST['district_id'];
$status = $_REQUEST['status'];
$fromtime = $_REQUEST['from_date'];
$totime = $_REQUEST['to_date'];
$from = date('Y-m-d',strtotime('0 day',strtotime($fromtime)));
$to = date('Y-m-d',strtotime('+1 day',strtotime($totime)));

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

font-weight:bold;
background-color:#ffffff;
}
table{
width:1210px;
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



$header1 .='<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td class="header_report" style="text-align:center;" align="center" colspan="14">RTI Report</td></tr></table><br />';



//header close

  $sql = "SELECT * FROM tbl_rti_management 

where 1=1";
	
$cond = '';	
	
	if($section){
		$cond .= " AND tbl_rti_management.section='$section'";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>Sections : </b>'.getLookupName(ucwords($section)).'</td></tr>
</table><br />';
	}
	
	if($district_id){
		$cond .= " AND tbl_rti_management.district_id='$district_id'";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>District Name : </b>'.ucwords(getdistrict($district_id)).'</td></tr>
</table><br />';
	}
	
	if($status){
		$cond .= " AND tbl_rti_management.rti_management_status='$status' ";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>Status : </b>'.ucwords($status).'</td></tr>
</table><br />';
	}
	
	
	
	
	if($fromtime!='' && $totime!=''){
		$cond .= " AND (tbl_rti_management.datecurrent BETWEEN '$from' AND '$to') ";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>From Date : </b>'.$fromtime.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.$totime.'</td></tr><tr><td>&nbsp;</td></tr>
</table><br />';
	}else{
		if($fromtime!=''){
			$cond .= " AND tbl_rti_management.datecurrent='$fromtime' ";
		}
		if($totime!=''){
			$cond .= " AND tbl_rti_management.datecurrent='$totime' ";
		}
	}
	
 if($status == 'close')
   {
	
	
$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="6%" colspan="1" align="left" class="header2">S. No.</td>
<td width="8%" colspan="1" align="left" class="header2">Application No.</td>
<td width="8%" colspan="1" align="left" class="header2">Section</td>
<td width="8%" colspan="1" align="left" class="header2">Application Type</td>
<td width="8%" colspan="1" align="left" class="header2">District Name</td>
<td width="8%" colspan="1" align="left" class="header2">Office</td>
<td width="8%" colspan="1" align="left" class="header2">Date</td>
<td width="8%" colspan="1" align="left" class="header2">Applicant Name</td>
<td width="8%" colspan="1" align="left" class="header2">Application Category</td>
<td width="8%" colspan="1" align="left" class="header2">Corrspondance Address</td>
<td width="10%" colspan="1" align="left" class="header2">Mobile No.</td>
<td width="10%" colspan="1" align="left" class="header2">Email Address</td>
<td width="10%" colspan="1" align="left" class="header2">Status</td>
<td width="10%" colspan="1" align="left" class="header2">Remarks/Action Taken</td>
</tr>';

   }
   else if($status == '')
   {
	   
	$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="6%" colspan="1" align="left" class="header2">S. No.</td>
<td width="8%" colspan="1" align="left" class="header2">Application No.</td>
<td width="8%" colspan="1" align="left" class="header2">Section</td>
<td width="8%" colspan="1" align="left" class="header2">Application Type</td>
<td width="8%" colspan="1" align="left" class="header2">District Name</td>
<td width="8%" colspan="1" align="left" class="header2">Office</td>
<td width="8%" colspan="1" align="left" class="header2">Date</td>
<td width="8%" colspan="1" align="left" class="header2">Applicant Name</td>
<td width="8%" colspan="1" align="left" class="header2">Application Category</td>
<td width="8%" colspan="1" align="left" class="header2">Corrspondance Address</td>
<td width="10%" colspan="1" align="left" class="header2">Mobile No.</td>
<td width="10%" colspan="1" align="left" class="header2">Email Address</td>
<td width="10%" colspan="1" align="left" class="header2">Status</td>
<td width="10%" colspan="1" align="left" class="header2">Remarks/Action Taken</td>
</tr>';   
   }
   else{
	$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="7%" colspan="1" align="left" class="header2">S. No.</td>
<td width="9%" colspan="1" align="left" class="header2">Application No.</td>
<td width="9%" colspan="1" align="left" class="header2">Section</td>
<td width="9%" colspan="1" align="left" class="header2">Application Type</td>
<td width="9%" colspan="1" align="left" class="header2">District Name</td>
<td width="9%" colspan="1" align="left" class="header2">Office</td>
<td width="9%" colspan="1" align="left" class="header2">Date</td>
<td width="9%" colspan="1" align="left" class="header2">Applicant Name</td>
<td width="8%" colspan="1" align="left" class="header2">Application Category</td>
<td width="8%" colspan="1" align="left" class="header2">Corrspondance Address</td>
<td width="10%" colspan="1" align="left" class="header2">Mobile No.</td>
<td width="12%" colspan="1" align="left" class="header2">Email Address</td>
<td width="10%" colspan="1" align="left" class="header2">Status</td>

</tr>';   	   
	   
   }
  
  $outputh .= $header1.$header2.$header3;
  
  $query = $sql . $cond;
  $sql_count = "SELECT count(*) as count_neshat FROM tbl_rti_management  

where 1=1";
  $query_count = $sql_count . $cond;
  $rescount = db_query($query_count);
  $rscount = db_fetch_object($rescount);
  
  $res = db_query($query);
 
 $counter=1;
 $neshatcount =1;
 
 while($rs = db_fetch_object($res)){
  $sd= date('d-m-Y',strtotime($rs->datecurrent));
	 $dsd=substr($sd,0,10);
	  $hearingdate ="";
	
	
if($rs->rti_management_status=='Close'){
	
	
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
   
   
				$output .='<tr>
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->appno).'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName(ucwords($rs->section)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getdistrict($rs->district_id)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getCorporationname($rs->office)).'</td>
				<td width="8%" class="'.$class.'" align="center">'.$sd.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->application_name.'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName($rs->application_category).'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->correspondence_address.'</td>
				<td width="10%" class="'.$class.'" align="right">'.$rs->mobile_number.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->email_address.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->rti_management_status.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->remarks.'</td>
				';
				$output .='</tr>';
				$counter++; 
}
else if($status == '')
	{
	if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
   
   
				$output .='<tr>
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->appno).'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName(ucwords($rs->section)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getdistrict($rs->district_id)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getCorporationname($rs->office)).'</td>
				<td width="8%" class="'.$class.'" align="center">'.$sd.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->application_name.'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName($rs->application_category).'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->correspondence_address.'</td>
				<td width="10%" class="'.$class.'" align="right">'.$rs->mobile_number.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->email_address.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->rti_management_status.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->remarks.'</td>
				';
				$output .='</tr>';
				$counter++; 
	
}
else{
	
if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
   
   
				$output .='<tr>
				<td width="7%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="9%" class="'.$class.'" align="left">'.ucwords($rs->appno).'</td>
				<td width="9%" class="'.$class.'" align="left">'.getLookupName(ucwords($rs->section)).'</td>
				<td width="9%" class="'.$class.'" align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
				<td width="9%" class="'.$class.'" align="left">'.ucwords(getdistrict($rs->district_id)).'</td>
				<td width="9%" class="'.$class.'" align="left">'.ucwords(getCorporationname($rs->office)).'</td>
				<td width="9%" class="'.$class.'" align="center">'.$sd.'</td>
				<td width="9%" class="'.$class.'" align="left">'.$rs->application_name.'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName($rs->application_category).'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->correspondence_address.'</td>
				<td width="10%" class="'.$class.'" align="right">'.$rs->mobile_number.'</td>
				<td width="12%" class="'.$class.'" align="left">'.$rs->email_address.'</td>
				<td width="10%" class="'.$class.'" align="left">'.$rs->rti_management_status.'</td>
				
				';
				$output .='</tr>';
				$counter++; 	
	
}
		
 }

$outputt .='</table>';
	
 $outputf = $outputh.$output.$outputt;
		
	    
	//for($ik=1;$ik <= 10;$ik++){
//$pdf->AddPage();
// print a line
//$pdf->Cell(0, 12, 'DISPLAY PREFERENCES - PAGE 1', 0, 0, 'C');
	//$pdf->writeHTML($output, true, 0, true, true);
//$output .='neshat';
//}
	 // print a block of text using Write()

	//Close and output PDF document
	$pdf->AddPage();
	ob_end_clean();
	 $pdf->writeHTML($outputf, true,1, false, false);
	 
	$pdf->Output('rti_report_'.time().'.pdf', 'I');
}

