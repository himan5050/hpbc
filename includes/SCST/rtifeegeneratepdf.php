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
//$pdf->AddPage();




if($_REQUEST['op'] == 'rtifee_report'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];
$mode_payment = $_REQUEST['mode_payment'];
$fromtime = $_REQUEST['from_date'];
$totime = $_REQUEST['to_date'];
$from = date("Y-m-d",strtotime($fromtime));
$to = date("Y-m-d",strtotime($totime));

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
width:1570px;
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



$header1 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" style="text-align:center;" colspan="2">RTI Fee Report</td><td></td></tr></table><br />';



//header close

  $sql = "SELECT * FROM tbl_rti_management 

where 1=1";
	
$cond = '';	
	
	if($mode_payment){
		$cond .= " AND tbl_rti_management.mode_payment='$mode_payment'";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>Mod Of Payment : </b>'.ucwords($mode_payment).'</td></tr>
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
	
	
if($mode_payment == '')
{	
	
$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Application Number</td>
<td width="8%" colspan="1" align="center" class="header2">Application Type</td>
<td width="8%" colspan="1" align="center" class="header2">Applicant Name</td>
<td width="8%" colspan="1" align="center" class="header2">Mode Of Fee</td>
<td width="8%" colspan="1" align="center" class="header2">Amount</td>
<td width="8%" colspan="1" align="center" class="header2">Date</td>
<td width="8%" colspan="1" align="center" class="header2">IPO No.</td>
</tr>';

}

else if($mode_payment == 'ipo')
{

$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Application Number</td>
<td width="8%" colspan="1" align="center" class="header2">Application Type</td>
<td width="8%" colspan="1" align="center" class="header2">Applicant Name</td>
<td width="8%" colspan="1" align="center" class="header2">Mode Of Fee</td>
<td width="8%" colspan="1" align="center" class="header2">Amount</td>
<td width="8%" colspan="1" align="center" class="header2">Date</td>
<td width="8%" colspan="1" align="center" class="header2">IPO No.</td>
</tr>';

}

else{

$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Application Number</td>
<td width="8%" colspan="1" align="center" class="header2">Application Type</td>
<td width="8%" colspan="1" align="center" class="header2">Applicant Name</td>
<td width="8%" colspan="1" align="center" class="header2">Mode Of Fee</td>
<td width="8%" colspan="1" align="center" class="header2">Amount</td>
<td width="8%" colspan="1" align="center" class="header2">Date</td>
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
 /* $sd= date('d-m-Y',strtotime($rs->datecurrent));
	 $dsd=substr($sd,0,10);
	  $hearingdate ="";*/
	  
	  
	  if($mode_payment == 'ipo'){
		$cond1 = "cashipo";
	}
	else if($mode_payment == 'mo'){
		$cond1 = "cashmo";
	}
	else if($mode_payment == 'cash'){
		$cond1 = "cashcash";
	}
	else if($mode_payment == ''){
	  $cond1 = 'cashipo+cashmo+cashcash';
	} 
	 
	 //$sum = "select sum ";
	$conddate ="1=1";
	if($fromtime!='' && $totime!=''){
		 $conddate .= " AND (tbl_rti_management.datecurrent BETWEEN '$from' AND '$to') ";
	}
	
	
	 //echo "select sum(".$cond1.") as sumamount from tbl_rti_management where $conddate";exit;
	$sqlamount=db_query("select sum(".$cond1.") as sumamount from tbl_rti_management where $conddate");
	
$sqlqu=db_fetch_object($sqlamount);

//echo $lo1 =$sqlqu->ipo;exit;

$sumamount =$sqlqu->sumamount;
	
	$ipono =$rs->ipono;
	$ipodate =$rs->currdatefield;
	
	$currdatemo =$rs->currdatemo;
	
	$currdatecash=$rs->currdatecash;
	if($rs->cashipo){$amount =$rs->cashipo;}
	else if($rs->cashmo){$amount =$rs->cashmo;}
	else if($rs->cashcash){$amount=$rs->cashcash;} 
	
	
	if($rs->currdatefield){$date =$rs->currdatefield;}
	else if($rs->currdatemo){$date =$rs->currdatemo;}
	else if($rs->currdatecash){$date=$rs->currdatecash;} 
	
	

	if($mode_payment == '')
	{
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->appno).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->application_type).'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->application_name.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->mode_payment.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$amount.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$date.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$ipono.'</td>
				';
				$output .='</tr>';
				$counter++; 
				
				}
				
				else if($mode_payment == 'ipo')
				{
				 if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->appno).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->application_type).'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->application_name.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->mode_payment.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$amount.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$date.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$ipono.'</td>
				';
				$output .='</tr>';
				$counter++; 
				
				
				
				}
				
				else {
				
				 if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->appno).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->application_type).'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->application_name.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$rs->mode_payment.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$amount.'</td>
				<td width="8%" class="'.$class.'" align="left">'.$date.'</td>
				';
				$output .='</tr>';
				$counter++; 
				
				
				}
			  /*  if($neshatcount+1 != $pdf->pagenumber1()){
				
				}else{
				   $neshat .='neshat';
				   $neshatcount =1;
				
				}
				$neshatcount++;	*/
		
 }

$outputt .='</table>';
 $outputtt .='<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
 $outputf = $outputh.$output.$outputt.$outputtt;
		
	    
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
	 $pdf->writeHTML($outputf, true,1, false, false);
	 
	$pdf->Output('rti_report_'.time().'.pdf', 'I');
}

