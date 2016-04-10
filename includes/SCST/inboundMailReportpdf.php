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


if($_REQUEST['op'] == 'inboundMailReport'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$diary_no = $_REQUEST['diary_no'];
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = date("Y-m-d",$fromtime);
$to = date("Y-m-d",$totime);

$output='';
// define some HTML content with style

$output .= add_css();

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td colspan="0" align="center" class="header_report">In-coming Mail Register</td></tr><tr><td>&nbsp;</td></tr>
<tr><td colspan="0" class="header1" align="right"></td></tr>
<tr><td>&nbsp;</td></tr>
</table>';
	

 if($diary_no && $fromtime && $totime){
    $sql = "SELECT * FROM tbl_inboundMails where (date1 BETWEEN '".$fromtime."' AND '".$totime."') and LOWER(diary_no) LIKE '%".strtolower($diary_no)."%'";
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
Diary Number : '.$diary_no.'</td></tr>
<tr><td class="header1"><b>From Date : </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.date('d-m-Y',strtotime($totime)).'</td></tr><tr><td>&nbsp;</td></tr>
</table>';
  }
  else if($diary_no){
    $sql = "SELECT * FROM tbl_inboundMails where LOWER(diary_no) LIKE '%".strtolower($diary_no)."%'";
	$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td class="header_first" align="left">
<b>Diary Number : </b>'.$diary_no.'</td></tr>
</table><br/>';
  }else{
    $sql = "SELECT * FROM tbl_inboundMails where (date1 BETWEEN '".$fromtime."' AND '".$totime."')";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left" ><b>From Date : </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.date('d-m-Y',strtotime($totime)).'</td></tr>
</table><br/>';
  }




$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Diary No.</td>
<td width="8%" colspan="1" align="center" class="header2">Details of the person</td>
<td width="8%" colspan="1" align="center" class="header2">Subject</td>
<td width="8%" colspan="1" align="center" class="header2">Date</td>
<td width="8%" colspan="1" align="center" class="header2">Address To</td>

<td width="8%" colspan="1" align="center" class="header2">File Number</td>

<td width="8%" colspan="1" align="center" class="header2">Mode</td>
<td width="8%" colspan="1" align="center" class="header2">Entry By</td>
<td width="8%" colspan="1" align="center" class="header2">Assigned To</td>
<td width="8%" colspan="1" align="center" class="header2">Status</td>

</tr>';

  


 $res = db_query($sql);
 $counter=1;
 //dispatch amt bal

 while($rs = db_fetch_object($res)){
 // $type=getLookupName($rs->dispatch_type);
 $mod=getLookupName($rs->mod);
 
$address=db_query("select username from tbl_joinings where LOWER(employee_id) LIKE '%".strtolower($rs->address_to)."%'");
$address_to=db_fetch_object($address);
//$section=getLookupName($rs->sender_details);

$assign=db_query("select username from tbl_joinings where LOWER(employee_id) LIKE '%".strtolower($rs->assigned_to)."%'");
$assigned_to=db_fetch_object($assign);
$section=getLookupName($rs->sender_details);

  $status1=getLookupName($rs->status1);
	  if ($status1==''){
	  $status1="N/A";}
	  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr> 
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->diary_no.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->person_details).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->subject).'</td>
				<td width="8%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date1)).'</td>
								<td width="8%" class="'.$class.'" align="left">'.ucwords($address_to->username).'</td>

				<td width="8%" class="'.$class.'" align="right">'.$rs->file_no.'</td>
				
				<td width="8%" class="'.$class.'" align="left">'.ucwords($mod).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->entry_by).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($assigned_to->username).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($status1).'</td>
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('inboundMailReport_'.time().'.pdf', 'I');
}

