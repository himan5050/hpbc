<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
$pdf->AddPage();
//getting number of page $pdf->pagenumber()

if($_REQUEST['op'] == 'vehicleInsurance_report'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$reg_no = $_REQUEST['regi_no'];
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = $fromtime;
$to = $totime;

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
width:915px;
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
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td colspan="0" align="center" class="header_report">Vehicle Insurance Report</td></tr><tr><td>&nbsp;</td></tr>

</table>';
	

 if($reg_no && $fromtime && $totime){
    $sql = "SELECT tbl_vehicles.reg_no,date_insurance,date_to,policy_no,date_from,date_due,sum_insured,person_name,add_line1,add_line2,block,panchayat,pincode,tehsil_id,state_id,district_id FROM tbl_vehicleInsurance INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleInsurance.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."') and LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($reg_no)."%'";
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
<b>Reg. No. of Vehicle : </b>'.$reg_no.'</td></tr>
<tr><td class="header1"><b>From Date : </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.date('d-m-Y',strtotime($totime)).'</td></tr>
</table><br>';
  }
  else if($reg_no){
    $sql = "SELECT tbl_vehicles.reg_no,date_insurance,date_to,policy_no,date_from,date_due,sum_insured,person_name,add_line1,add_line2,block,panchayat,pincode,tehsil_id,state_id,district_id FROM tbl_vehicleInsurance INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleInsurance.reg_no) where LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($reg_no)."%'";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
<b>Reg. No. of Vehicle : </b>'.$reg_no.'</td></tr>
</table><br>';
  }else{
    $sql = "SELECT tbl_vehicles.reg_no,date_insurance,date_to,policy_no,date_from,date_due,sum_insured,person_name,add_line1,add_line2,block,panchayat,pincode,tehsil_id,state_id,district_id FROM tbl_vehicleInsurance INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleInsurance.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."')";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>From Date : </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.date('d-m-Y',strtotime($totime)).'</td></tr>
<tr><td>&nbsp;</td></tr></table>';
  }




$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border"><tr>
<td width="4%" colspan="1" align="center" class="header2">S. No.</td>
<td width="11%" colspan="1" align="center" class="header2">Reg. No. of Vehicle</td>
<td width="12%" colspan="1" align="center" class="header2">Date of Insurance</td>

<td width="12%" colspan="1" align="center" class="header2">Start Date</td>
<td width="12%" colspan="1" align="center" class="header2">Valid Upto</td>
<td width="12%" colspan="1" align="center" class="header2">Due Date</td>
<td width="11%" colspan="1" align="center" class="header2">Policy No.</td>
<td width="11%" colspan="1" align="center" class="header2">Sum Insured</td>
<td width="11%" colspan="1" align="center" class="header2">Insurer Name</td>
<td width="11%" colspan="1" align="center" class="header2">Insurer Address</td>
</tr>';





 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				
				$getstate1=db_query("select state_name from tbl_dsjestate where state_id='".$rs->state_id."'");
				$getstate=db_fetch_object($getstate1);
				$getdistrict1=db_query("select district_name from tbl_district where district_id='".$rs->district_id."'");
				$getdistrict=db_fetch_object($getdistrict1);
				$gettehsil1=db_query("select tehsil_name from tbl_tehsil where tehsil_id='".$rs->tehsil_id."'");
				$gettehsil=db_fetch_object($gettehsil1);
				
				$output .='<tr>
				<td width="4%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="11%" class="'.$class.'" align="left">'.$rs->reg_no.'</td>
				<td width="12%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date_insurance)).'</td>
				<td width="12%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date_from)).'</td>
				<td width="12%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date_to)).'</td>
				<td width="12%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date_to)).'</td>
				<td width="11%" class="'.$class.'" align="right">'.$rs->policy_no.'</td>
				<td width="11%" class="'.$class.'" align="right">'.$rs->sum_insured.'</td>
				<td width="11%" class="'.$class.'" align="left">'.ucwords($rs->person_name).'</td>
				<td width="11%" class="'.$class.'" align="left">'.ucwords($rs->add_line1).', '.ucwords($rs->add_line2).', '.ucwords($rs->panchayat).','.ucwords($rs->block).',  '.ucwords($gettehsil->tehsil_name).', '.ucwords($getdistrict->district_name).', '.ucwords($getstate->state_name).', '.$rs->pincode.'</td>
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('vehicleInsurance_report_'.time().'.pdf', 'I');
}

