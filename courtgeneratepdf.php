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
//$pdf->AddPage();




if($_REQUEST['op'] == 'courtcase_report'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];
$case_no = $_REQUEST['case_no'];
$court_name_name = $_REQUEST['court_name_name'];
$lawyer_name = $_REQUEST['lawyer_name'];
$loan_account = $_REQUEST['loan_account'];
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
width:1140px;
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
<tr><td class="header_report" style="text-align:center;" colspan="6">Court Case Report</td><td></td></tr></table><br />';



//header close

  $sql = "SELECT tbl_courtcasehearing.courtcase_id,tbl_courtcasehearing.court_name_id,tbl_courtcasehearing.date1,tbl_courtcasehearing.loan_account,tbl_courtcasehearing.lawyer_id,tbl_court_names.court_name_name,tbl_lawyer.lawyer_name,tbl_courtcasehearing.title_case,tbl_courtcasehearing.case_detail,tbl_courtcasehearing.fee_charge,tbl_courtcasehearing.court_states,tbl_courtcasehearing.	hearing_date FROM tbl_courtcasehearing INNER JOIN tbl_court_names ON(tbl_courtcasehearing.court_name_id=tbl_court_names.court_name_id) INNER JOIN tbl_lawyer ON(tbl_courtcasehearing.lawyer_id=tbl_lawyer.lawyer_id) where 1=1";
	
$cond = '';	
	
	if($case_no){
		$cond .= " AND tbl_courtcasehearing.courtcase_id = '$case_no'";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left">Case Number : '.ucwords($case_no).'</td></tr>
</table><br />';
	}
	
	if($court_name_name){
		$cond .= " AND tbl_courtcasehearing.court_name_id = '$court_name_name' ";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left">Court Name : '.ucwords(getcourtname($court_name_name)).'</td></tr>
</table><br />';
	}
	
	if($lawyer_name){
		$cond .= " AND UCASE(tbl_lawyer.lawyer_name) LIKE '%".strtoupper($lawyer_name)."%' ";
		/*$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left">Lawyer Name : '.ucwords($lawyer_name).'</td></tr>
</table><br />';*/
	}
	
	
	if($loan_account){
		$cond .= " AND tbl_courtcasehearing.loan_account lIKE '%$loan_account%' ";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left">Loan Account No : '.ucwords($loan_account).'</td></tr>
</table><br />';
	}
	
	if($fromtime!='' && $totime!=''){
		$cond .= " AND (tbl_courtcasehearing.date1 BETWEEN '$from' AND '$to') ";
		$header2 .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>From Date : </b>'.$fromtime.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.$totime.'</td></tr><tr><td>&nbsp;</td></tr>
</table><br />';
	}else{
		if($fromtime!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$fromtime' ";
		}
		if($totime!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$totime' ";
		}
	}
	
$header3 .='<table cellpadding="2" cellspacing="2" border="0" class="tbl_border" align="center"><tr>
<td width="6%" colspan="1" align="center" class="header2">S. No.</td>
<td width="8%" colspan="1" align="center" class="header2">Case No.</td>
<td width="8%" colspan="1" align="center" class="header2">Court Name</td>
<td width="8%" colspan="1" align="center" class="header2">Case Title</td>
<td width="8%" colspan="1" align="center" class="header2">Case Detail</td>
<td width="8%" colspan="1" align="center" class="header2">Lawyer Name</td>
<td width="8%" colspan="1" align="center" class="header2">Loan Account No.</td>
<td width="8%" colspan="1" align="center" class="header2">Hearing Date</td>
<td width="8%" colspan="1" align="center" class="header2">Fee Detail</td>
<td width="8%" colspan="1" align="center" class="header2">Status</td>
<td width="8%" colspan="1" align="center" class="header2">Hearing Action Comment</td>
</tr>';

	
  
  $outputh .= $header1.$header2.$header3;
  
  $query = $sql . $cond;
  $sql_count = "SELECT count(*) as count_neshat FROM tbl_courtcasehearing  
INNER JOIN tbl_court_names ON(tbl_courtcasehearing.court_name_id=tbl_court_names.court_name_id)
 INNER JOIN tbl_lawyer ON(tbl_courtcasehearing.lawyer_id=tbl_lawyer.lawyer_id) where 1=1";
  $query_count = $sql_count . $cond;
  $rescount = db_query($query_count);
  $rscount = db_fetch_object($rescount);
  
  $res = db_query($query);
 
 $counter=1;
 $neshatcount =1;
 
 while($rs = db_fetch_object($res)){

	 $sd=$rs->hearing_date;
	 $dsd=substr($sd,0,10);
	 $sd1=$rs->date;
	 $dsd1=substr($sd1,0,10);
	 //$sdf=$rs->current_hearing_date;
	 $ert=substr($sdf,0,10); 
	 
	 $hearingdate ="";
	 
	 
	   
	  $sqlh = "select tbl_courtcase.hearing_date,tbl_courtcase.status,tbl_courtcase.current_hearing_date,tbl_courtcasehearing.court_name_id,tbl_lawyer.lawyer_name from tbl_courtcase inner join tbl_courtcasehearing on (tbl_courtcasehearing.courtcase_id=tbl_courtcase.case_no) INNER JOIN tbl_lawyer ON (tbl_courtcasehearing.lawyer_id=tbl_lawyer.lawyer_id) where 1=1";
	 
	 
	 
	  
	  $cond = " and tbl_courtcase.case_no ='".$rs->courtcase_id."'";	
	
	if($case_no){
		$cond .= " AND tbl_courtcasehearing.courtcase_id ='$case_no'";
	}
	
	if($court_name_name){
		$cond .= " AND tbl_courtcasehearing.court_name_id = '$court_name_name' ";
	}
	
	if($lawyer_name){
		$cond .= " AND UCASE(tbl_lawyer.lawyer_name) LIKE '%".strtoupper($lawyer_name)."%' ";
	}
	
	
	if($loan_account){
		$cond .= " AND tbl_courtcasehearing.loan_account ='$loan_account' ";
	}
	
	
	
	
	
	if($fromtime!='' && $totime!=''){
		 $cond .= " AND (tbl_courtcasehearing.date1 BETWEEN '$from' AND '$to') ";
	}else{
		if($fromtime!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$fromtime' ";
		}
		if($totime!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$totime' ";
		}
	}
	
	
	
  
$query1 = $sqlh . $cond;
 
	  
	  
	  
	 $resh = db_query($query1);
	 $comment="";
	 $hearingdate ="";
	 while($rsh = db_fetch_object($resh)){
	  $hearingdate .= date('d-m-Y',strtotime($rsh->hearing_date)).'<br />';
	 $comment .=$rsh->current_hearing_date.'<br />';
	   
	   
	   
	   
	   //$statust .=ucwords($rsh->status).'<br />';
	   
	    
	   
	   
	   
	  /* if($rsh->status=='hearing')
	   
	   {
	   $statust='Hearing';
	   
	   }
	   
	   else if($rsh->status=='argument')
	   {
	   
	    $statust='Argument';
	   
	   
	   }
	   
	   else if($rsh->status=='decision')
	   {
	   
	    $statust='Decision';
	   
	   
	   }*/
	  
	 }
  
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				$output .='<tr>
				<td width="6%" class="'.$class.'" align="center">'.$counter.'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->courtcase_id).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->court_name_name).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->title_case).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->case_detail).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($rs->lawyer_name).'</td>
				<td width="8%" class="'.$class.'" align="right">'.$rs->loan_account.'</td>
				<td width="8%" class="'.$class.'" align="center">'.date('d-m-Y',strtotime($rs->hearing_date)).'</td>
				<td width="8%" class="'.$class.'" align="right">'.round($rs->fee_charge).'</td>
				<td width="8%" class="'.$class.'" align="left">'.getLookupName($rs->court_states).'</td>
				<td width="8%" class="'.$class.'" align="left">'.ucwords($comment).'</td>
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
ob_end_clean();

	$pdf->AddPage();
	 $pdf->writeHTML($outputf, true,1, false, false);
	 
	$pdf->Output('courtcase_report_'.time().'.pdf', 'I');
}

