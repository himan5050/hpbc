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




if($_REQUEST['op'] == 'fundutilizationreport'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];
$head_name = $_REQUEST['head_name'];
$scheme_name = $_REQUEST['scheme_name'];

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
width:1800px;
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
<tr><td class="header_report" align="center">Fund Utilization Report</td><td></td></tr></table><br />';



//header close





	

  $sql = "SELECT tbl_headmaster.name1,tbl_headmaster.vid,tbl_scheme_master.scheme_name,tbl_headmaster.createdon,tbl_headmaster.code,tbl_loan_detail.loan_requirement, 
		 tbl_loan_detail.o_disburse_amount,tbl_scheme_master.promoter_share,tbl_scheme_master.apex_share,tbl_scheme_master.corp_share,tbl_loanee_detail.district,tbl_loan_detail.sanction_date
		 ,tbl_loanee_detail.reg_number
		 
		 FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_schemenames on (tbl_schemenames.schemeName_id=tbl_scheme_master.main_scheme) 
inner join tbl_headmaster on (tbl_headmaster.vid=tbl_schemenames.head) 
where 1=1";


	
$cond = '';	
	

	if($head_name){
		$cond .= " AND tbl_headmaster.name1='$head_name'";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>Head Name : </b>'.ucwords($head_name).'</td></tr>
</table><br />';
	}	
	
	
	if($scheme_name){
		$cond .= " AND tbl_scheme_master.scheme_name='$scheme_name'";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>District : </b>'.ucwords($scheme_name).'</td></tr>
</table><br />';
	}	
	

$cond .= 'GROUP BY tbl_headmaster.name1';	
 

$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="3%" class="header2">S. No.</td>
<td width="5%" class="header2">Date</td>
<td width="7%" class="header2">Code number</td>
<td width="7%" class="header2">Amount Disbursed</td>
<td width="7%" class="header2">Promoter Share</td>
<td width="5%" class="header2">Term Loan</td>
<td width="6%" class="header2">NBCFDC Share</td>
<td width="6%" class="header2">HBCFDC Share</td>
<td width="8%" class="header2">District</td>
</tr>';

  
  $outputh .= $header1.$header2.$header3;
  
  $query = $sql . $cond;
  $sql_count = "SELECT count(*) as count_neshat FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_schemenames on (tbl_schemenames.schemeName_id=tbl_scheme_master.main_scheme) 
inner join tbl_headmaster on (tbl_headmaster.vid=tbl_schemenames.head) 
where 1=1";

  $query_count = $sql_count . $cond;
  $rescount = db_query($query_count);
  $rscount = db_fetch_object($rescount);
  
  $res = db_query($query);
 
 $counter=1;
 $neshatcount =1;
 
 while($rs = db_fetch_object($res)){

	 
	 
	$loan_requirement=$rs->loan_requirement;
	$o_disburse_amount=$rs->o_disburse_amount;
	 
	$disamount= $loan_requirement-$o_disburse_amount;
	 $loan_term=$rs->apex_share+$rs->corp_share;
	 
	

				if($counter%2==0){ $class='header4_1';}else{$class='header4_1';}
				$output .='<tr>
				<td width="3%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="5%" class="'.$class.'" align="center">'.date("d-m-Y",strtotime($rs->sanction_date)).'</td>				
				<td width="7%" class="'.$class.'" align="right">'.$rs->reg_number.'</td>
				<td width="7%" class="'.$class.'" align="right">'.$disamount.'</td>
				<td width="7%" class="'.$class.'" align="right">'.$rs->promoter_share.'</td>
				<td width="5%" class="'.$class.'" align="right">'.$loan_term.'</td>
				<td width="6%" class="'.$class.'" align="right">'.$rs->apex_share.'</td>
				<td width="6%" class="'.$class.'" align="right">'.$rs->corp_share.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords(getdistrict($rs->district)).'</td>
				
				';
				$output .='</tr>';
				$counter++; 
				
				
				
			  /*  if($neshatcount+1 != $pdf->pagenumber1()){
				
				}else{
				   $neshat .='neshat';
				   $neshatcount =1;
				
				}
				$neshatcount++;	*/
		
 }

$outputt .='</table>';
 //$outputtt .='<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
 $outputf = $outputh.$output.$outputt;
		
	
	
	

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
	ob_end_clean();
	 $pdf->writeHTML($outputf, true,1, false, false);
	 
	$pdf->Output('rti_report_'.time().'.pdf', 'I');
}

