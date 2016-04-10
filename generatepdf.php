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


if($_REQUEST['op'] == 'dispatch_report'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$dispatch_no = $_REQUEST['dispatch_no'];
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = date("Y-m-d",$fromtime);
$to = date("Y-m-d",$totime);

$output='';
// define some HTML content with style

$output .= add_css();

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td colspan="0" align="center" class="header_report">Dispatch Report</td></tr><tr><td>&nbsp;</td></tr>
<tr><td colspan="0" class="header1" align="right"></td></tr>
<tr><td>&nbsp;</td></tr>
</table>';
	

 if($dispatch_no && $fromtime && $totime){
    $sql = "SELECT * FROM tbl_dispatchforms where (date1 BETWEEN '".$fromtime."' AND '".$totime."') and LOWER(dispatch_no) LIKE '%".strtolower($dispatch_no)."%'";
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
Dispatch Number : '.$dispatch_no.'</td></tr>
<tr><td class="header1">From Date : '.date('d-m-Y',strtotime($fromtime)).'</td></tr><tr><td colspan="0" class="header1" align="left">To Date : '.date('d-m-Y',strtotime($totime)).'</td></tr><tr><td>&nbsp;</td></tr>
</table><br />s';
  }
  else if($dispatch_no){
    $sql = "SELECT * FROM tbl_dispatchforms where LOWER(dispatch_no) LIKE '%".strtolower($dispatch_no)."%'";
	$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td class="header_first" align="left">
Dispatch Number : '.$dispatch_no.'</td></tr>
</table><br />';
  }else{
    $sql = "SELECT * FROM tbl_dispatchforms where (date1 BETWEEN '".$fromtime."' AND '".$totime."')";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left" ><b>From Date : </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.date('d-m-Y',strtotime($totime)).'</td></tr><tr><td>&nbsp;</td></tr>
</table>';
  }




$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Dispatch No.</td>
<td width="8%" colspan="1" align="center" class="header2">Details of the person to whom it is dispatched</td>
<td width="8%" colspan="1" align="center" class="header2">Details of the Sender</td>
<td width="8%" colspan="1" align="center" class="header2">Subject</td>
<td width="8%" colspan="1" align="center" class="header2">File No.</td>
<td width="8%" colspan="1" align="center" class="header2">Date</td>
<td width="8%" colspan="1" align="center" class="header2">Mode of Dispatch</td>
<td width="8%" colspan="1" align="center" class="header2">Amount of Stamp</td>
<td width="8%" colspan="1" align="center" class="header2">Balance Amount</td>
<td width="8%" colspan="1" align="center" class="header2">Type</td>
</tr>';





 $res = db_query($sql);
 $counter=1;
 //dispatch amt bal
 if($fromtime != "1970-01-01" && $dispatch_no ==NULL){
	$bbs=db_query("select sum(amount) as amount from tbl_dispatchforms where date1 < '".$fromtime."'");
$bbamt=db_fetch_object($bbs);

$sss=db_query("select sum(amount) as amount from tbl_dispatchaccounts");
$ssamt=db_fetch_object($sss);

$opening= $ssamt->amount-$bbamt->amount;
	//echo $opening;
	$open=$opening;
	}else{ 
	$dhakan=db_query("select nid from tbl_dispatchforms where LOWER(dispatch_no) LIKE '%".strtolower($dispatch_no)."%'");
	$makhan=db_fetch_object($dhakan);
	$bbs=db_query("select sum(amount) as amount from tbl_dispatchforms where nid < '".$makhan->nid."'");
$bbamt=db_fetch_object($bbs);

$sss=db_query("select sum(amount) as amount from tbl_dispatchaccounts");
$ssamt=db_fetch_object($sss);
//echo $ssamt->amount;
$opening= $ssamt->amount-$bbamt->amount;
	//echo $opening;
	$open=$opening;}
   $output .='<tr> <td colspan="11" align="left"><b>Opening Balance = '.round($opening,2).'</b></td></tr>';
   
 while($rs = db_fetch_object($res)){
  $type=getLookupName($rs->dispatch_type);
 $mod=getLookupName($rs->mod);
 
 $ballu= $open - $rs->amount ;
$open=$ballu;
 $section=getLookupName($rs->sender_details);
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr> 
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->dispatch_no.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->person_details).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->person_name).', '.ucwords($section).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->subject).'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->file_no.'</td>
				<td width="8%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->date1)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($mod).'</td>
				<td width="8%" class="'.$class.'" align="right">'.round($rs->amount).'</td>
				<td width="8%" class="'.$class.'" align="right">'.round($ballu).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($type).'</td>';
				$output .='</tr>';
				$counter++;
 }
	
		 $output .='</table>';
	
	
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('dispatch_report_'.time().'.pdf', 'I');
}

