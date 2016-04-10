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

$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
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


if($_REQUEST['op'] == 'vehicleAMC_report'){
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
width:980px;
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
<tr><td class="header_report" colspan="5" align="center">
Vehicle AMC Register</td></tr>
<!--<tr><td colspan="0" class="header1" align="right">Status as on '.date("d-m-Y").'</td></tr>-->
<tr><td>&nbsp;</td></tr>
</table>';
	

 if($reg_no && $fromtime && $totime){
    $sql = "SELECT name_vendor,amc_details,date_from,date_valid,terms,tbl_vehicles.reg_no FROM tbl_vehicleamc INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleamc.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."') and    LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($_REQUEST['regi_no'])."%'  ";
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"  align="left">From Date : '.date('d-m-Y',strtotime($fromtime)).'</td></tr><tr><td class="header1" align="left">To Date : '.date('d-m-Y',strtotime($totime)).'</td></tr><tr><td>&nbsp;</td></tr>
</table>';
  }
  else if($reg_no){
    $sql = "SELECT name_vendor,amc_details,date_from,date_valid,terms,tbl_vehicles.reg_no FROM tbl_vehicleamc INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleamc.reg_no) where LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($_REQUEST['regi_no'])."%' ";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr><tr><td>&nbsp;</td></tr>
</table>';
  }else{
    $sql = "SELECT name_vendor,amc_details,date_from,date_valid,terms,tbl_vehicles.reg_no FROM tbl_vehicleamc INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleamc.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."')";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1"  align="left"><b>From Date : </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.date('d-m-Y',strtotime($totime)).'</td></tr><tr><td>&nbsp;</td></tr>
</table>';
  }




$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border"><tr>
<td width="5%" colspan="1" class="header2">S. No.</td>
<td width="15%" colspan="1"  class="header2">Registration Number of Vehicle</td>
<td width="15%" colspan="1"  class="header2">Name of Vendor</td>
<td width="20%" colspan="1" class="header2">AMC Details</td>
<td width="15%" colspan="1"  class="header2">Start Date</td>
<td width="15%" colspan="1"  class="header2">Valid Upto</td>
<td width="15%" colspan="1"  class="header2">Terms And Conditions</td>

</tr>';





 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="5%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="15%" class="'.$class.'" align="left">'.$rs->reg_no.'</td>
				<td width="15%" class="'.$class.'" align="left">'.ucwords($rs->name_vendor).'</td>
				<td width="20%" class="'.$class.'" align="left">'.ucwords($rs->amc_details).'</td>
				<td width="15%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date_from)).'</td>
				<td width="15%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date_valid)).'</td>
				<td width="15%" class="'.$class.'" align="left">'.ucwords($rs->terms).'</td>
				
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
		ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('vehicleAMC_report_'.time().'.pdf', 'I');
}

