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

ob_end_clean();

if($_REQUEST['op'] == 'dispatchAccountReport'){
global $user, $base_url;
$rid = $_REQUEST['rid'];
//$diary_no = $_REQUEST['diary_no'];
$fromtime = $_REQUEST['fromtime'];
$totime = $_REQUEST['totime'];
$from = date("Y-m-d",$fromtime);
$to = date("Y-m-d",$totime);

$output='';
 $output = <<<EOF
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
width:1235px;
}
table.tbl_border{border:1px solid #ffffff;
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
border-left-color:#ffffff;
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
// define some HTML content with style


// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td colspan="0" align="center" class="header_report">Ticket Received by Dispatch Section from Account</td></tr><tr><td>&nbsp;</td></tr>
</table><br/>';
	

 if($fromtime && $totime){
     $sql = "SELECT * FROM tbl_dispatchaccounts where (date BETWEEN '".$fromtime."' AND '".$totime."')";
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left" ><b>From Date : </b>'.date('d-m-Y',strtotime($fromtime)).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.date('d-m-Y',strtotime($totime)).'</td></tr>
</table><br/>';
  }



$output .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border"><tr>
<td  width="50" colspan="1" align="center" class="header2">S. No.</td>
<td  colspan="1" align="center" class="header2">Date</td>
<td  colspan="1" align="center" class="header2">Amount</td>
<td  colspan="1" align="center" class="header2">Remarks</td>
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
$date=$rs->date;
$remark=$rs->remarks; if($remark==''){$remark='N/A';}
   if($counter%2==0){ $class='header4_2';}else{$class='header4_1';}
				$output .='<tr> 
				<td class="'.$class.'" align="center" width="50">'.$counter.'</td>
				<td  class="'.$class.'" align="center">'.date('d-m-Y',strtotime($date)).'</td>
				<td  class="'.$class.'" align="right">'.round($rs->amount).'</td>
				<td  class="'.$class.'" align="left">'.ucwords($remark).'</td>
			
				
				</tr>';
				$counter++;
 }





		
		 $output .='</table>';
	
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('dispatchAccountReport_'.time().'.pdf', 'I');
}

