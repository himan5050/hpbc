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


if($_REQUEST['op'] == 'transferPromotionReport'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$employee_id= $_REQUEST['employee_id'];
	$employee_name = $_REQUEST['employee_name'];
	$section = $_REQUEST['Departmentid'];
	$status = $_REQUEST['status'];

$output='';
// define some HTML content with style

$output .= add_css();

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td colspan="0" align="center" class="header_report">Transfer/Promotion Register</td></tr><tr><td>&nbsp;</td></tr>
</table><br>';
	
///

$append="";
	if($_REQUEST['status']){
	
	$append .= " LOWER(action)='".strtolower($status)."' AND ";
	}
	if($_REQUEST['employee_id']){
	
	$append .= " LOWER(employee_id) LIKE '%".strtolower($employee_id)."%' AND ";
	}
	if($_REQUEST['employee_name']){
	
	$append .= " employee_name LIKE '%".$employee_name."%' AND ";
	}
	 if($_REQUEST['Departmentid']){
	
	$append .= " LOWER(current_Departmentid) LIKE '%".strtolower($section)."%' AND ";
	}
  
  $append .= " 1=1 ";

$sql="select * from tbl_transferpromotions where $append";
////
/* if($status && $fromtime && $totime){
    $sql = "SELECT * FROM tbl_inboundDeputation where (date_from BETWEEN '".$fromtime."' AND '".$totime."') and status='".$status."'";
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
Diary Number : '.$status.'</td></tr>
<tr><td class="header1">From Date : '.date('d-m-Y',strtotime($fromtime)).'</td></tr><tr><td colspan="0" class="header1" align="left">To Date : '.date('d-m-Y',strtotime($totime)).'</td></tr><tr><td>&nbsp;</td></tr>
</table>';
  }
  else if($status){
    $sql = "SELECT * FROM tbl_inboundDeputation where status='".$status."'";
	$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td class="header_first" align="left">
Diary Number : '.$status.'</td></tr>
</table>';
  }else{
    $sql = "SELECT * FROM tbl_inboundDeputation where (date_from BETWEEN '".$fromtime."' AND '".$totime."')";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left" >From Date : '.date('d-m-Y',strtotime($fromtime)).'</td></tr><tr><td colspan="4" class="header1">To Date : '.date('d-m-Y',strtotime($totime)).'</td></tr><tr><td>&nbsp;</td></tr>
</table>';
  }
*/

	



$output .='<table cellpadding="3" cellspacing="2" border="0" >';

if($_REQUEST['status']){
$output .='<tr><td class="header_first" align="left">
<b>Status: </b>'.getLookupName($status).'</td></tr>';

}

if($_REQUEST['employee_id']){

$output .='<tr><td class="header_first" align="left">
<b>Employee id: </b>'.$employee_id.'</td></tr>';


}

if($_REQUEST['employee_name']){

$output .='<tr><td class="header_first" align="left">
<b>Employee Name: </b>'.$employee_name.'</td></tr>';


}
 if($_REQUEST['Departmentid']){

 
$output .='<tr><td class="header_first" align="left">
<b>Section: </b>'.getLookupName($section).'</td></tr>';
 
  }
  
$output .='</table><br>';

$output .='<table cellpadding="3" cellspacing="2" border="0" class="tbl_border" style="width:1250px;"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Employee Name</td>
<td width="8%" colspan="1" align="center" class="header2">Employee Id</td>
<td width="8%" colspan="1" align="center" class="header2">Current Office</td>
<td width="8%" colspan="1" align="center" class="header2">Current Section</td>
<td width="8%" colspan="1" align="center" class="header2">Current Designation</td>
<td width="8%" colspan="1" align="center" class="header2">Previous Office</td>
<td width="8%" colspan="1" align="center" class="header2">Previous Section</td>
<td width="8%" colspan="1" align="center" class="header2">Previous Designation</td>
<td width="8%" colspan="1" align="center" class="header2">Status</td>


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

//$assign=db_query("select name from users where uid='".$rs->assign_to."'");
//$assigned_to=db_fetch_object($assign);

   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr> 
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->employee_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->employee_id).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getCorporationName($rs->current_officeid)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->current_Departmentid)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->current_designationid)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->prev_officeid).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->prev_Departmentid)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->prev_designationid).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->action)).'</td>
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	ob_end_clean();
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('transferPromotionReport_'.time().'.pdf', 'I');
}

