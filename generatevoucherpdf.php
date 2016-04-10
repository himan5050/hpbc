<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/pdfcss.php');

// create new PDF document
$pdf = new TCPDF(P, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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
$pdf->AddPage();




if($_REQUEST['op'] == 'payment'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
$voucher="sc-".$_REQUEST['voucher'];

$branch=$_REQUEST['branch'];
db_set_active('scst_branch_'.$branch);


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
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:665px;
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

$cond='';
$deb=0;
$cre=0;
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Payment Voucher</td></tr>
</table>';
	
   $s="select * from gltrans where voucher_no='".$voucher."'";
   $q=db_query($s,$db);
	$n=1;
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>

</table><br>';
 
 $k=1;
	  while($r=db_fetch_array($q))
	  { $date=$r['trandate'];
	    $preparedby=$r['preparedby'];
		$approvedby=$r['approvedby'];
	       $s1="select * from chartmaster where accountcode='".$r['account']."'";
		   $q1=db_query($s1);
		   $r1=db_fetch_array($q1);
	    	   if($r['amount'] >0)
			   { $deb=$deb+$r['amount'];
	            $outputd .='<tr><td valign="top"  class="'.$cl.'">Dbt. '.$r1['accountname'].'</td><td valign="top"  class="'.$cl.'">'.round(abs($r['amount'])).'</td><td valign="top"  class="'.$cl.'">&nbsp;</td></tr>';
	             }
				 else if($r['amount'] <0)
				 {$cre=$cre+$r['amount'];
				 $outputd .='<tr><td valign="top"  class="'.$cl.'">Cr. '.$r1['accountname'].'</td><td valign="top"  class="'.$cl.'">&nbsp;</td><td valign="top"  class="'.$cl.'">'.round(abs($r['amount'])).'</td></tr>';
				 }
	   	$k++;	   
	}
	
	$dat=explode('-',$date);
	$output .='</table><table><tr><td colspan="2"><b>Payment Voucher No: </b>'.$voucher.'</td><td colspan="2" align="right"><b>Date: </b>'.$dat[2].'-'.$dat[1].'-'.$dat[0].'</td></tr>
	  <tr></tr></table><br>
	  <table border="1" style="border:1px solid #ccc;"><tr><td width="50%"><b>Particulars</b></td><td width="25%"><b>Debit</b></td><td width="25%"><b>Credit</b></td></tr>'.$outputd.'<tr><td></td><td  class="header4_2"><b>'.round(abs($deb)).'</b></td><td class="header4_2"><b>'.round(abs($cre)).'</b></td></tr>
	  <tr><td colspan="3">Rupees : '.convert_number(round(abs($deb))).' Only</td></tr></table><br><br>
	  <table border="0">
	  <tr><td>Prepared By </td><td>Manager </td><td>G.M.</td><td>M.D.</td></tr>
	  <tr><td>&nbsp;</td></tr>
	   <tr><td colspan="4">Received a sum of Rs '.round(abs($deb)).' (Rupees '.convert_number(round(abs($deb))).' Only) from Himachal Backward Classes Finance & Development Corporation on account of...... </td></tr>
	  </table>';




	 
	
	

 
		 $output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('payment_'.time().'.pdf', 'I');
	
	db_set_active('default');
}


if($_REQUEST['op'] == 'cash'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
$voucher="sc-".$_REQUEST['voucher'];

$branch=$_REQUEST['branch'];
db_set_active('scst_branch_'.$branch);


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
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:665px;
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

$cond='';
$deb=0;
$cre=0;
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Receipt Voucher</td></tr>
</table>';
	
   $s="select * from gltrans where voucher_no='".$voucher."'";
   $q=db_query($s,$db);
	$n=1;
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>

</table><br>';
 




$output .=' <table cellspacing="0" cellpadding="0" border="0">
	  ';

 
	  $k=1;
	  while($r=db_fetch_array($q))
	  { $date=$r['trandate'];
	    $preparedby=$r['preparedby'];
		$approvedby=$r['approvedby'];
	       $s1="select * from chartmaster where accountcode='".$r['account']."'";
		   $q1=db_query($s1);
		   $r1=db_fetch_array($q1);
	    	   if($r['amount'] >0)
			   { $deb=$deb+$r['amount'];
	            $outputd .='<tr><td valign="top"  class="'.$cl.'">Dbt. '.$r1['accountname'].'</td><td valign="top"  class="'.$cl.'">'.round($r['amount']).'</td><td valign="top"  class="'.$cl.'">&nbsp;</td></tr>';
	             }
				 else if($r['amount'] <0)
				 {$cre=$cre+$r['amount'];
				 $outputd .='<tr><td valign="top"  class="'.$cl.'">Cr. '.$r1['accountname'].'</td><td valign="top"  class="'.$cl.'">&nbsp;</td><td valign="top"  class="'.$cl.'">'.round(abs($r['amount'])).'</td></tr>';
				 }
	   	$k++;	   
	}
	
	$dat=explode('-',$date);
	$output .='</table><table><tr><td colspan="2"><b>Receipt Voucher No: </b>'.$voucher.'</td><td colspan="2" align="right"><b>Date: </b>'.$dat[2].'-'.$dat[1].'-'.$dat[0].'</td></tr>
	  <tr></tr></table><br>
	  <table border="1" style="border:1px solid #ccc;"><tr><td width="50%"><b>Particulars</b></td><td width="25%"><b>Debit</b></td><td width="25%"><b>Credit</b></td></tr>'.$outputd.'<tr><td></td><td  class="header4_2"><b>'.round(abs($deb)).'</b></td><td class="header4_2"><b>'.round(abs($cre)).'</b></td></tr>
	  <tr><td colspan="3">Rupees : '.convert_number(round(abs($deb))).' Only</td></tr></table><br><br>
	 <table border="0">
	  <tr><td>Prepared By </td><td>Manager </td><td>G.M.</td><td>M.D.</td></tr>
	  <tr><td>&nbsp;</td></tr>
	   <tr><td colspan="4">Received a sum of Rs '.round(abs($deb)).' (Rupees '.convert_number(round(abs($deb))).' Only) from Himachal Backward Classes Finance & Development Corporation on account of...... </td></tr></table>';



	 
	
	

 
		 $output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('cash_'.time().'.pdf', 'I');
	
	db_set_active('default');
}

if($_REQUEST['op'] == 'journal'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
$voucher=$_REQUEST['voucher'];

$branch=$_REQUEST['branch'];
db_set_active('scst_branch_'.$branch);


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
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:665px;
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

$cond='';
$deb=0;
$cre=0;
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Journal Voucher</td></tr>
</table>';
	
   $s="select * from gltrans where voucher_no='".$voucher."'";
   $q=db_query($s,$db);
	$n=1;
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>

</table><br>';
 
	   $k=1;
	  while($r=db_fetch_array($q))
	  { $date=$r['trandate'];
	    $preparedby=$r['preparedby'];
		$approvedby=$r['approvedby'];
	       $s1="select * from chartmaster where accountcode='".$r['account']."'";
		   $q1=db_query($s1);
		   $r1=db_fetch_array($q1);
	    	   if($r['amount'] >0)
			   { $deb=$deb+$r['amount'];
	            $outputd .='<tr><td valign="top"  class="'.$cl.'">Dbt. '.$r1['accountname'].'</td><td valign="top"  class="'.$cl.'">'.round($r['amount']).'</td><td valign="top"  class="'.$cl.'">&nbsp;</td></tr>';
	             }
				 else if($r['amount'] <0)
				 {$cre=$cre+$r['amount'];
				 $outputd .='<tr><td valign="top"  class="'.$cl.'">Cr. '.$r1['accountname'].'</td><td valign="top"  class="'.$cl.'">&nbsp;</td><td valign="top"  class="'.$cl.'">'.round(abs($r['amount'])).'</td></tr>';
				 }
	   	$k++;	   
	}
	
	$dat=explode('-',$date);
	$output .='</table><table><tr><td colspan="2"><b>Journal Voucher No: </b>'.$voucher.'</td><td colspan="2" align="right"><b>Date: </b>'.$dat[2].'-'.$dat[1].'-'.$dat[0].'</td></tr>
	  <tr></tr></table><br>
	  <table border="1" style="border:1px solid #ccc;"><tr><td width="50%"><b>Particulars</b></td><td width="25%"><b>Debit</b></td><td width="25%"><b>Credit</b></td></tr>'.$outputd.'<tr><td></td><td  class="header4_2"><b>'.round(abs($deb)).'</b></td><td class="header4_2"><b>'.round(abs($cre)).'</b></td></tr>
	  <tr><td colspan="3">Rupees : '.convert_number(round(abs($deb))).' Only</td></tr></table><br><br>
	<table border="0">
	  <tr><td>Prepared By </td><td>Manager </td><td>G.M.</td><td>M.D.</td></tr>
	  <tr><td>&nbsp;</td></tr>
	   <tr><td colspan="4">Received a sum of Rs '.round(abs($deb)).' (Rupees '.convert_number(round(abs($deb))).' Only) from Himachal Backward Classes Finance & Development Corporation on account of...... </td></tr>
	  </table>';




	 
	
	

 
		 $output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('journal_'.time().'.pdf', 'I');
	
	db_set_active('default');
}


if($_REQUEST['op'] == 'trial_balance'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

$branch=$_REQUEST['branch'];
db_set_active('scst_branch_'.$branch);


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
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:665px;
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

$NumberOfMonths = $to - $from + 1;

$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $to . "'";
	$PrdResult = db_query($sql, $db);
	$myrow = db_fetch_array($PrdResult);
	$PeriodToDate = $myrow['lastdate_in_period'];
	
	$daa=explode('-',$PeriodToDate);
if($daa[1]=='01')
{
 $mo="January";
}
if($daa[1]=='02')
{
 $mo="February";
}
if($daa[1]=='03')
{
 $mo="March";
}
if($daa[1]=='04')
{
 $mo="April";
}
if($daa[1]=='05')
{
 $mo="May";
}
if($daa[1]=='06')
{
 $mo="June";
}
if($daa[1]=='07')
{
 $mo="July";
}
if($daa[1]=='08')
{
 $mo="August";
}
if($daa[1]=='09')
{
 $mo="September";
}
if($daa[1]=='10')
{
 $mo="October";
}
if($daa[1]=='11')
{
 $mo="November";
}
if($daa[1]=='12')
{
 $mo="December";
}
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">
Trial Balance</td></tr>
</table><br/>';
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">
<b>Trial Balance for the month of ' .$mo.' '.$daa[0].' and for the ' . $NumberOfMonths . ' months to ' .$mo.' '.$daa[0].' </b></td></tr>
</table><br/>';
	
$SQL = "SELECT accountgroups.groupname,
			accountgroups.parentgroupname,
			accountgroups.pandl,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $from . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
			Sum(CASE WHEN chartdetails.period='" . $from . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
		FROM chartmaster INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails ON chartmaster.accountcode= chartdetails.accountcode
		GROUP BY accountgroups.groupname,
				accountgroups.pandl,
				accountgroups.sequenceintb,
				accountgroups.parentgroupname,
				chartdetails.accountcode,
				chartmaster.accountname
		ORDER BY accountgroups.pandl desc,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";


	$AccountsResult = db_query($SQL);

	//echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Trial Balance</a></div>';

	/*show a table of the accounts info returned by the SQL
	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */

	$output .='<table cellpadding="2" width="1000">';
	//$output .='<tr><th colspan=6><font size=3 color=blue><b>Trial Balance for the month of '. $PeriodToDate .
		//' and for the ' . $NumberOfMonths . ' months to ' . $PeriodToDate .'</b></font></th></tr>';
	$TableHeader ='<tr><td class="header2">Account</td><td class="header2">Account Name</td><td class="header2">Debit</td><td class="header2">Credit</td></tr>';

	$j = 1;
	$k=0; //row colour counter
	$ActGrp ='';
	$ParentGroups = array();
	$Level =1; //level of nested sub-groups
	$ParentGroups[$Level]='';
	$GrpActual =array(0);
	$GrpBudget =array(0);
	$GrpPrdActual =array(0);
	$GrpPrdBudget =array(0);

	$PeriodProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;
	$MonthProfitLoss = 0;
	$MonthBudgetProfitLoss = 0;
	$BFwdProfitLoss = 0;
	$CheckMonth = 0;
	$CheckBudgetMonth = 0;
	$CheckPeriodActuald = 0;
	$CheckPeriodActualc = 0;
	$CheckPeriodBudget = 0;

	while ($myrow=db_fetch_array($AccountsResult)) {

		if ($myrow['groupname']!= $ActGrp ){
			if ($ActGrp !=''){ //so its not the first account group of the first account displayed
				if ($myrow['parentgroupname']==$ActGrp){
					$Level++;
					$ParentGroups[$Level]=$myrow['groupname'];
					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]='';
				} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
					$output .='<tr>
						<td align="right" colspan="2"><b>'.$ParentGroups[$Level].' Total </b></td><td class="number"><b>'.round(abs($GrpPrdActual[$Level])).'</b></td><td></td>
						
						</tr>';

					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]=$myrow['groupname'];
				} else {
					do {
						$output .='<tr>
							<td colspan="2"><b>'.$ParentGroups[$Level].' Total </b></td>
							<td class="number" align="right"><b>'.round(abs($GrpPrdActual[$Level])).'</b></td>
							
							</tr><tr><td>&nbsp;</td></tr>';

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
						$Level--;

						$j++;
					} while ($Level>0 and $myrow['groupname']!=$ParentGroups[$Level]);

					if ($Level>0){
						$output .='<tr>
						<td colspan="2"><b>'.$ParentGroups[$Level].' Total </b></td>
						<td class="number" colspan="2" align="right"><b>'.round(abs($GrpPrdActual[$Level])).'</b></td>
						
						</tr><tr><td>&nbsp;</td></tr>';

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
					} else {
						$Level=1;
					}
				}
			}
			$ParentGroups[$Level]=$myrow['groupname'];
			$ActGrp = $myrow['groupname'];
			$output .='<tr ><td colspan="6"><b>'.$myrow['groupname'].'</b></td></tr>';
			$output .=$TableHeader;
			$j++;
		}

		/*if ($k==1){
			$output .= '<tr class="header4_1">';
			$k=0;
		} else {
			$output .= '<tr class="header4_2">';
			$k++;
		}*/
		/*MonthActual, MonthBudget, FirstPrdBFwd, FirstPrdBudgetBFwd, LastPrdBudgetCFwd, LastPrdCFwd */


		if ($myrow['pandl']==1){

			$AccountPeriodActual = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
			$AccountPeriodBudget = $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];

			$PeriodProfitLoss += $AccountPeriodActual;
			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
			$MonthProfitLoss += $myrow['monthactual'];
			$MonthBudgetProfitLoss += $myrow['monthbudget'];
			$BFwdProfitLoss += $myrow['firstprdbfwd'];
		} else { /*PandL ==0 its a balance sheet account */
			if ($myrow['accountcode']==$RetainedEarningsAct){
				$AccountPeriodActual = $BFwdProfitLoss + $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $BFwdProfitLoss + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			} else {
				$AccountPeriodActual = $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $myrow['firstprdbfwd'] + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			}

		}

		if (!isset($GrpActual[$Level])) {
			$GrpActual[$Level]=0;
		}
		if (!isset($GrpBudget[$Level])) {
			$GrpBudget[$Level]=0;
		}
		if (!isset($GrpPrdActual[$Level])) {
			$GrpPrdActual[$Level]=0;
		}
		if (!isset($GrpPrdBudget[$Level])) {
			$GrpPrdBudget[$Level]=0;
		}
		$GrpActual[$Level] +=$myrow['monthactual'];
		$GrpBudget[$Level] +=$myrow['monthbudget'];
		$GrpPrdActual[$Level] +=$AccountPeriodActual;
		$GrpPrdBudget[$Level] +=$AccountPeriodBudget;

		$CheckMonth += $myrow['monthactual'];
		$CheckBudgetMonth += $myrow['monthbudget'];
		//echo $AccountPeriodActual."<br>";
		if($AccountPeriodActual>0)
		{
		$CheckPeriodActuald += $AccountPeriodActual;
		}
		if($AccountPeriodActual<0)
		{
		 $CheckPeriodActualc += $AccountPeriodActual;
        }
		$CheckPeriodBudget += $AccountPeriodBudget;
		$ActEnquiryURL = $myrow['accountcode'];
		
		if((round($AccountPeriodActual))>0)
		   { 
		     $actd=round(abs($AccountPeriodActual)) ;
			 $actc="0" ;
			} 
		else if ((round($AccountPeriodActual))<0)
		   { 
		     $actc=round(abs($AccountPeriodActual)) ;
			 $actd="0";
		   }
		   else
		   {
		     $actd=round(abs($AccountPeriodActual)) ;
			  $actc=round(abs($AccountPeriodActual)) ;
		   }
if($j%2==0)
	{
	  $cl="header4_1"; 
	}
	else
	{
	 $cl="header4_2";
	}
	
		$output .='<tr ><td class="'.$cl.'">'.$ActEnquiryURL.'</td><td class="'.$cl.'">'.$myrow['accountname'].'</td><td class="'.$cl.'" align="right">'. $actd.'</td><td class="'.$cl.'" align="right">'. $actc.'</td></tr>';
			

		$j++;
	}
	//end of while loop


	if ($ActGrp !=''){ //so its not the first account group of the first account displayed
		if ($myrow['parentgroupname']==$ActGrp){
			$Level++;
			$ParentGroups[$Level]=$myrow['groupname'];
		} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
			$output .='<tr>
				<td colspan="2">'.$ParentGroups[$Level].' Total</td>
				
				
				<td class="number" colspan="2">'.round(abs($GrpPrdActual[$Level])).'</td>
				
				</tr>';

			$GrpActual[$Level] =0;
			$GrpBudget[$Level] =0;
			$GrpPrdActual[$Level] =0;
			$GrpPrdBudget[$Level] =0;
			$ParentGroups[$Level]=$myrow['groupname'];
		} else {
			do {
				$output .='<tr>
					<td colspan="2"><b>'.$ParentGroups[$Level].' Total</b></td>
					
					
					<td align="right"><b>'.round(abs($GrpPrdActual[$Level])).'</b></td>
					<td></td>
					</tr>';

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
				$Level--;

				$j++;
			} while (isset($ParentGroups[$Level]) and ($myrow['groupname']!=$ParentGroups[$Level] and $Level>0));

			if ($Level >0){
				$output .='<tr>
				<td colspan="2">'.$ParentGroups[$Level].' Total </td>
				
				
				<td  colspan="2">'.round(abs($GrpPrdActual[$Level])).'</td>
				
				</tr>';

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
			} else {
				$Level =1;
			}
		}
	}



	$output .='<tr bgcolor="#ffffff">
			<td colspan="2" class="header2"><b>Check Totals</b></td>
			
			<td class="header2" align="right">'.round(abs($CheckPeriodActuald)).'</td>
			<td class="header2" align="right">'.round(abs($CheckPeriodActualc)).'</td>
			
		</tr>';

	$output .= '</table>';
	ob_end_clean();

	// $output;
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('trial_balance_'.time().'.pdf', 'I');
	
	db_set_active('default');
}