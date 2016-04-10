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


if($_REQUEST['op'] == 'budgetDetailReport'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
$sectionname= $_REQUEST['sectionname'];
	$schemename = $_REQUEST['schemename'];
	$headcode = $_REQUEST['Departmentid'];
	$headname = $_REQUEST['headname'];

$output='';
// define some HTML content with style

$output .= add_css();

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td colspan="0" align="center" class="header_report">Budget Detail Register</td></tr><tr><td>&nbsp;</td></tr>
</table><br>';
	
///

$append="";
	if($_REQUEST['headcode']){
	
	$append .= " headtype='".$headcode."' AND ";
	}
	if($_REQUEST['headname']){
	
	$append .=" headtype='".$headname."' AND ";
	

	}
	if($_REQUEST['sectionname']){
	
	//$append .= " LOWER(headname) LIKE '%".strtolower($headname)."%' AND ";
	$append .= " branch LIKE '%".$sectionname."%' AND ";
	
	$append .= " 1=1 "; 

  $sql1=db_query("select nid from tbl_budgetDistribution where $append");
	   $sql2=db_fetch_object($sql1);

	 
	 if($_REQUEST['schemename']){
	
	$sql="select * from tbl_budgetMonths where nid='".$sql2->nid."' and schemename='".$schemename."'";
	

  else {
  $sql="select * from tbl_budgetMonths where nid='".$sql2->nid."'";
	
  }

	



$output .='<table cellpadding="3" cellspacing="2" border="0" id="wrapper">';

if($_REQUEST['headname']){
$output .='<tr><td class="header_first" align="left"><b>Head Name: </b></td><td class="header_first" align="left">'.$headname.'</td></tr>';
}

if($_REQUEST['headcode']){

$output .='<tr><td class="header_first" align="left"><b>Head Code: </b></td><td class="header_first" align="left">'.$headcode.'</td></tr>';
}

if($_REQUEST['schemename']){

$output .='<tr><td class="header_first" align="left"><b>Scheme Name: </b></td><td class="header_first" align="left">'.$schemename.'</td></tr>';

}
 if($_REQUEST['sectionname']){

$output .='<tr><td colspan="2" align="center"><b>Branch: </b>'.$sectionname.'</td></tr>';
}
  
$output .='</table><br>';

$output .='<table cellpadding="3" cellspacing="2" border="0" class="tbl_border" style="width:1250px;"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Head Code</td>
<td width="8%" colspan="1" align="center" class="header2">Head Name</td>
<td width="8%" colspan="1" align="center" class="header2">Scheme Name</td>
<td width="8%" colspan="1" align="center" class="header2">Branch</td>
<td width="8%" colspan="1" align="center" class="header2">Amount Allocated</td>
<td width="8%" colspan="1" align="center" class="header2">Amount Consumed</td>
<td width="8%" colspan="1" align="center" class="header2">Balance Amount</td>
</tr>';

  


 $res = db_query($sql);
 $counter=1;
 //dispatch amt bal

 while($rs = db_fetch_object($res)){
 
$stuff=db_query("select tbl_headMaster.code,tbl_headMaster.name1,tbl_schemeNames.schemeName_name,tbl_corporations.corporation_name  from tbl_budgetDistribution 
 inner join tbl_headMaster on (tbl_headMaster.vid=tbl_budgetDistribution.headtype)
 inner join tbl_schemeNames on (tbl_schemeNames.schemeName_id=tbl_budgetMonths.schemename)
 inner join tbl_corporations on (tbl_corporations.corporation_id=tbl_budgetDistribution.branch) where nid='".$rs->nid."'");
 $stuff1=db_fetch_object($stuff);

$allocated= $rs->apr + $rs->may + $rs->jun + $rs->jul + $rs->aug + $rs->sept + $rs->oct + $rs->nov + $rs->dec + $rs->jan + $rs->feb + $rs->mar;
$consumed= $rs->consume_apr + $rs->consume_may + $rs->consume_jun + $rs->consume_jul + $rs->consume_aug + $rs->consume_sept + $rs->consume_oct + $rs->consume_nov + $rs->consume_dec + $rs->consume_jan + $rs->consume_feb + $rs->consume_mar;
$bal=$allocated-$consumed;


   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr> 
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$stuff1->code.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($stuff1->name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($stuff1->schemeName_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($stuff1->corporation_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.$allocated.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$consumed.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$bal.'</td>
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('budgetDetailReport_'.time().'.pdf', 'I');
}

