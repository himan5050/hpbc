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


if($_REQUEST['op'] == 'deputationReport_report'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$deputation_type = $_REQUEST['deputation_type'];
	$section = $_REQUEST['section'];
$output='';
// define some HTML content with style

$output .= add_css();

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td colspan="0" align="center" class="header_report">List of employees on deputation</td></tr>
</table><br>';
	
//

if($_REQUEST['deputation_type'] && $_REQUEST['section']){
  //inbound-203 outbound-204
  		if($deputation_type==203){
		
				
		$sql="select * from tbl_inboundDeputation where LOWER(department) LIKE '%".strtolower($section)."%' and status2=95";
		 $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1"><b>Deputation Type : </b>'.getLookupName($deputation_type).'</td></tr><tr><td colspan="0" class="header1" align="left">Section : '.$section.'</td></tr>
</table><br>';

	}else{
		
			
		$sql="select * from tbl_outboundDeputation where LOWER(prev_Departmentid) LIKE '%".strtolower($section)."%'";

		 $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1"><b>Deputation Type : </b>'.getLookupName($deputation_type).'</td></tr><tr><td colspan="0" class="header1" align="left">Section : '.$section.'</td></tr>
</table><br>';
		
		}
  }
 else if($_REQUEST['section']==''){
  
  		if($deputation_type==203){
		
		
		
		$sql="select * from tbl_inboundDeputation where status2=95";
	 $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1"><b>Deputation Type : </b>'.getLookupName($deputation_type).'</td></tr>
</table><br>';
		}else{
		
		
$sql="select * from tbl_outboundDeputation ";
 $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1"><b>Deputation Type : </b>'.getLookupName($deputation_type).'</td></tr>
</table><br>';
  }
  
  }

///
 



$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" style="width:2120px;"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Employee Id</td>
<td width="8%" colspan="1" align="center" class="header2">Previous Section</td>
<td width="8%" colspan="1" align="center" class="header2">Previous Designation</td>
<td width="8%" colspan="1" align="center" class="header2">Contact No.</td>
<td width="8%" colspan="1" align="center" class="header2">Email Address</td>


</tr>';

  


 $res = db_query($sql);
 $counter=1;
 //dispatch amt bal

 while($rs = db_fetch_object($res)){

 $phone=$rs->phone; $mobile=$rs->mobile;
	  if($phone==''){$phone='N/A';} if($mobile==''){$mobile='N/A';}
	  
if($deputation_type==203){

   if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}
				$output .='<tr> 
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->employee_id.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->department).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->designation).'</td>
				<td width="8%" class="'.$class.'" align="center">Phone:'.$phone.'<br>Mobile'.$mobile.'</td>
								<td width="8%" class="'.$class.'" align="center">'.$rs->email.'</td>

				
				</tr>';
				}
				
				else{
				if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}
					$output .='<tr> 
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->employee_id.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->prev_Departmentid)).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getLookupName($rs->prev_designationid)).'</td>
				<td width="8%" class="'.$class.'" align="center">Phone:'.$phone.'<br>Mobile'.$mobile.'</td>
								<td width="8%" class="'.$class.'" align="center">'.$rs->email.'</td>

				
				</tr>';
				
				}
				
				
				$counter++;
 }





		
		 $output .='</table>';
	
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('deputationReport_'.time().'.pdf', 'I');
}

