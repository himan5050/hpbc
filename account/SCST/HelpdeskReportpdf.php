<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A4, true, 'UTF-8', false);
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


if($_REQUEST['op'] == 'HelpdeskReport'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
//$diary_no = $_REQUEST['diary_no'];
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = date('Y-m-d',strtotime($fromtime));
$to = date('Y-m-d',strtotime($totime));

$output='';
// define some HTML content with style

$output .= add_css();

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td colspan="0" align="center" class="header_report">Help Desk Register</td></tr><tr><td>&nbsp;</td></tr>
<tr><td colspan="0" class="header1" align="right"></td></tr>
<tr><td>&nbsp;</td></tr>
</table>';
	

 if($fromtime && $totime){
     $sql = "SELECT * FROM tbl_helpdesklogcomplaint where (date_time BETWEEN '".$fromtime."' AND '".$totime."')";
	$output .='<table cellpadding="0" cellspacing="0" border="0" width="40%">
<tr><td class="header1" align="left" >From Date : '.date('d-m-Y',strtotime($fromtime)).'</td>
<td class="header1">To Date : '.date('d-m-Y',strtotime($totime)).'</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
</table>';
  }



$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border"><tr>
<td width="5%" align="center" class="header2">S. No.</td>
<td width="13%" align="center" class="header2">Complaint Type</td>
<td width="13%" align="center" class="header2">Related Tp</td>
<td width="15%" align="center" class="header2">Details of the calls</td>
<td width="13%" align="center" class="header2">Assigned To</td>
<td width="12%" align="center" class="header2">Priority</td>
<td width="15%" align="center" class="header2">Status</td>
</tr>';
  


 $res = db_query($sql);
 $counter=1;
 //dispatch amt bal

 while($rs = db_fetch_object($res)){
 // $type=getLookupName($rs->dispatch_type);
 //$mod=getLookupName($rs->mod);
 
//$address=db_query("select name from users where uid='".$rs->address_to."'");
//$address_to=db_fetch_object($address);
//$section=getLookupName($rs->sender_details);

$assign=db_query("select name from users where uid='".$rs->assign_to."'");
$assigned_to=db_fetch_object($assign);
$assigned=$assigned_to->name;

if($assigned==''){$assigned='N/A';}
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr> 
				<td width="5%" class="'.$class.'" align="center">'.$counter.'</td>
				<td class="'.$class.'" align="left">'.getLookupName($rs->complaint_type).'</td>
				<td class="'.$class.'" align="left">'.ucwords(getLookupName($rs->related_to)).'</td>
				<td class="'.$class.'" align="left">'.ucwords($rs->details).'</td>
				<td class="'.$class.'" align="left">'.ucwords($assigned).'</td>
				<td class="'.$class.'" align="left">'.ucwords(getLookupName($rs->priority)).'</td>
				<td class="'.$class.'" align="left">'.ucwords(getLookupName($rs->status2)).'</td>				
			</tr>';
				$counter++;
 }
		
		 $output .='</table>';
	
	
	 // print a block of text using Write()
	 ob_end_clean();
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('HelpdeskReport_'.time().'.pdf', 'I');
}

