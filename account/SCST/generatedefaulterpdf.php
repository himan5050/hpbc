<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/pdfcss.php');

// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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




if($_REQUEST['op'] == 'defaulter'){
global $user, $base_url;
$startdate =$_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];
$type=$_REQUEST['type'];
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
width:1065px;
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
Defaulter List</td></tr>
<tr><td class="header1"  colspan="5"><b>From Date :</b> '. date('d-m-Y',$startdate).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.date('d-m-Y',$enddate).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Type</b> :'.ucwords($_REQUEST['type']).'</td></tr>
</table>';

$output .='<table cellpadding="3" cellspacing="2">
		  <tr><td colspan="13" align="right"></td></tr>
<tr><td class="header2"><b>Account No.</b></td>
<td class="header2"><b>Scheme Name</b></td>
<td class="header2"><b>Loanee Name</b></td>
<td class="header2"><b>Address</b></td>
<td class="header2"><b>Block</b></td>
<td class="header2"><b>Tehsil</b></td>
<td class="header2"><b>Panchayat</b></td>
<td class="header2"><b>Opening Balance</b></td>
<td class="header2"><b>Interest</b></td>
<td class="header2"><b>Recover amount</b></td>
<td class="header2"><b>Expected Amount</b></td>
<td class="header2"><b>Outstanding Balance</b></td>
</tr>';

$us="select current_officeid from tbl_joinings where program_uid='".$user->uid."'";
$usq=db_query($us);
$usr=db_fetch_array($usq);
$usid=$usr['current_officeid'];
$type=$_REQUEST['type'];
if($type=='alr')
{
 $sql="select tbl_loanee_detail.alr_status, tbl_loan_detail.emi_amount,tbl_loan_detail.ROI,tbl_loanee_detail.loanee_id,tbl_loanee_detail.corp_branch,tbl_scheme_master.scheme_name,tbl_loanee_detail.account_id,tbl_loanee_detail.fname,tbl_loanee_detail.address1,tbl_loanee_detail.address2,tbl_loanee_detail.district,tbl_loanee_detail.tehsil,tbl_loanee_detail.block,tbl_loanee_detail.reg_number from tbl_loanee_detail
 INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
 INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
 where UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$startdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$enddate."' and tbl_loanee_detail.corp_branch='".$usid."'";
// where alr_status=1";
 // where UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$startdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$enddate."'
 $query=db_query($sql);
 $l=1;
 while($res=db_fetch_array($query))
   {  if($l%2==0)
       {
	     $cla="header4_1";
	   }
	   else
	   {
	    $cla="header4_2";
	   }
     
     $opb="select sum(amount) as opbal from tbl_loan_disbursement where loanee_id='".$res['loanee_id']."' group by loanee_id";
	 $opbq=db_query($opb);
	 $opbr=db_fetch_array($opbq);
	 $opi="select sum(interest_paid) as intpaid, sum(principal_paid) as recovery from tbl_loan_amortisaton where loanacc_id='".$res['account_id']."' group by loanacc_id";
	 $opiq=db_query($opi);
	 $opir=db_fetch_array($opiq);
	 
	  $teh="select tehsil_name from tbl_tehsil where tehsil_id='".$res['tehsil']."'";
	 $tehq=db_query($teh);
	 $tehr=db_fetch_array($tehq);
	 
	 $blo="select block_name from tbl_block where block_id='".$res['block']."'";
	 $bloq=db_query($blo);
	 $blor=db_fetch_array($bloq);
	 
	 $panc="select panchayt_name from tbl_panchayt where panchayt_id='".$res['panchayat']."'";
	 $pancq=db_query($panc);
	 $pancr=db_fetch_array($pancq);
	 
	  $ss1="select min(createdon) as start_date from tbl_loan_disbursement where loanee_id='".$res['loanee_id']."' group by loanee_id";
    $q1=db_query($ss1);
    $r1=db_fetch_array($q1);
	
	 $months = floor((($enddate - $r1['start_date']) % 31556926) / 2629743.83);   
	/*$ex="SELECT MONTHS_BETWEEN('".date('d-m-Y',$r1['start_date'])."','".date('d-m-Y')."') AS MONTHS_BETWEEN FROM dual";
	$exq=db_query($ex);
	$exr=db_fetch_array($exq);
	echo $exr['MONTHS_BETWEEN'];*/
	
	$expted=$months*($res['emi_amount']*$res['ROI']);
	$outstanding=$expted-$opir['recovery'];
	 
	 
	 if($res['alr_status']==2)
	 {
    $output .='<tr><td class="'.$cla.'">'.$res['account_id'].'</td><td class="'.$cla.'">'.ucwords($res['scheme_name']).'</td><td class="'.$cla.'">'.ucwords($res['fname']).'</td><td class="'.$cla.'">'.ucwords($res['address1']).'</td><td class="'.$cla.'">'.ucwords($blor['block_name']).'</td><td class="'.$cla.'">'.ucwords($tehr['tehsil_name']).'</td><td class="'.$cla.'">'.ucwords($pancr['panchayt_name']).'</td><td class="'.$cla.'">'.$opbr['opbal'].'</td><td class="'.$cla.'">'.$opir['intpaid'].'</td><td class="'.$cla.'">'.$opir['recovery'].'</td><td class="'.$cla.'">'.$expted.'</td><td class="'.$cla.'">'.$outstanding.'</td></tr>';
     }
	 $l++;
   }
}
if($type=='defaulter')
{
 $sql="select tbl_loan_detail.emi_amount,tbl_loan_detail.ROI,tbl_loanee_detail.loanee_id,tbl_loanee_detail.corp_branch,tbl_scheme_master.scheme_name,tbl_panchayt.panchayt_name,tbl_tehsil.tehsil_name,tbl_block.block_name,tbl_loanee_detail.account_id,tbl_loanee_detail.fname,tbl_loanee_detail.address1,tbl_loanee_detail.address2,tbl_loanee_detail.district,tbl_loanee_detail.tehsil,tbl_loanee_detail.block,tbl_loanee_detail.reg_number from tbl_loanee_detail
 INNER JOIN tbl_panchayt ON (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat)
 INNER JOIN tbl_tehsil ON (tbl_tehsil.tehsil_id=tbl_loanee_detail.tehsil)
 INNER JOIN tbl_block ON (tbl_block.block_id=tbl_loanee_detail.block)
 INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
 INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
 where UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$startdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$enddate."' and tbl_loanee_detail.corp_branch='".$usid."'";
// where alr_status=1";
 // where UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$startdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$enddate."'
 $query=db_query($sql);
 $l=1;
 while($res=db_fetch_array($query))
   { 
     if($l%2==0)
       {
	     $cla="header4_1";
	   }
	   else
	   {
	    $cla="header4_2";
	   }
	   
     $opb="select sum(amount) as opbal from tbl_loan_disbursement where loanee_id='".$res['loanee_id']."' group by loanee_id";
	 $opbq=db_query($opb);
	 $opbr=db_fetch_array($opbq);
	 
	 $opi="select sum(interest_paid) as intpaid, sum(principal_paid) as recovery from tbl_loan_amortisaton where loanacc_id='".$res['account_id']."' group by loanacc_id";
	 $opiq=db_query($opi);
	 $opir=db_fetch_array($opiq);
	 
	 $ss="select max(payment_date) as last_date from tbl_loan_amortisaton where loanacc_id='".$res['account_id']."' group by loanacc_id";
    $q=db_query($ss);
    $r=db_fetch_array($q);
	
	if($r['last_date']!='')
	{
	 $ld=explode('-',$r['last_date']);
	 $mkt= mktime(0,0,0,($ld[1]+3),($ld[2]),($ld[0]));
	 $newdate=date('Y-m-d',$mkt);
	$checkdate= strtotime($newdate);
	$currdate=$enddate;
	
	 $ss1="select min(createdon) as start_date from tbl_loan_disbursement where loanee_id='".$res['loanee_id']."' group by loanee_id";
    $q1=db_query($ss1);
    $r1=db_fetch_array($q1);
	
	 $months = floor((($enddate - $r1['start_date']) % 31556926) / 2629743.83);   
	/*$ex="SELECT MONTHS_BETWEEN('".date('d-m-Y',$r1['start_date'])."','".date('d-m-Y')."') AS MONTHS_BETWEEN FROM dual";
	$exq=db_query($ex);
	$exr=db_fetch_array($exq);
	echo $exr['MONTHS_BETWEEN'];*/
	
	$expted=$months*($res['emi_amount']*$res['ROI']);
	$outstanding=$expted-$opir['recovery'];
	if($currdate>=$checkdate)
	  {
    $output .='<tr><td class="'.$cla.'">'.$res['account_id'].'</td><td class="'.$cla.'">'.ucwords($res['scheme_name']).'</td><td class="'.$cla.'">'.ucwords($res['fname']).'</td><td class="'.$cla.'">'.ucwords($res['address1']).'</td><td class="'.$cla.'">'.ucwords($res['block_name']).'</td><td class="'.$cla.'">'.ucwords($res['tehsil_name']).'</td><td class="'.$cla.'">'.ucwords($res['panchayt_name']).'</td><td class="'.$cla.'">'.$opbr['opbal'].'</td><td class="'.$cla.'">'.$opir['intpaid'].'</td><td class="'.$cla.'">'.$opir['recovery'].'</td><td class="'.$cla.'">'.$expted.'</td><td class="'.$cla.'">'.$outstanding.'</td></tr>';
       }
	   }
	   $l++;
   }
}

$output .='</table>';
 
 //echo $record;
	
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('defaulter_'.time().'.pdf', 'I');
	
}


if($_REQUEST['op'] == 'interestleadger'){
global $user, $base_url;
$stdate =$_REQUEST['stdate'];
$endate = $_REQUEST['endate'];

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
width:965px;
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
Interest Leadger</td></tr>
</table>';

$output .='<table cellpadding="3" cellspacing="2">
		  <tr><td colspan="7" align="right"></td></tr>
<tr><td class="header2"><b>Scheme name</b></td>
<td class="header2"><b>Total Loan Sanctioned</b></td>
<td class="header2"><b>Total interest Received</b></td>
<td class="header2"><b>Total Capital Subsidy</b></td>
<td class="header2"><b>Total Interest Subsidy</b></td>
<td class="header2"><b>Total MMD amount</b></td>
<td class="header2"><b>Total FDR amount</b></td>
</tr>';

if($_REQUEST['schemename'] != 'all')
{
  $cond ='and tbl_loan_detail.scheme_name="'.$_REQUEST['schemename'].'" GROUP BY tbl_loan_detail.scheme_name';
}else{
  $cond .= ' GROUP BY tbl_loan_detail.scheme_name';
}
//tbl_loan_detail.scheme_name='".$_REQUEST['schemename']."'
 $sqlc = "SELECT SUM( tbl_loan_detail.loan_requirement ) AS tsac, SUM( tbl_loan_detail.capital_subsidy ) AS capital_subsidy, SUM( tbl_loan_amortisaton.interest_paid ) AS interest_paid, SUM( tbl_fdr.amount ) AS fdramount, tbl_scheme_master.scheme_name AS scheme_name
FROM tbl_loan_detail
LEFT OUTER JOIN tbl_loanee_detail ON ( tbl_loanee_detail.reg_number = tbl_loan_detail.reg_number ) 
LEFT OUTER JOIN tbl_loan_amortisaton ON ( tbl_loan_amortisaton.loanacc_id = tbl_loanee_detail.account_id ) 
LEFT OUTER JOIN tbl_scheme_master ON ( tbl_scheme_master.loan_scheme_id = tbl_loan_detail.scheme_name ) 
LEFT OUTER JOIN tbl_fdr ON (tbl_fdr.account_no = tbl_loanee_detail.account_id )
WHERE UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$stdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$endate."' $cond ";

 $sqlcq=db_query($sqlc);
 $l=1;
 while($sqlcr=db_fetch_array($sqlcq))
 {  
    if($l%2==0)
       {
	     $cla="header4_1";
	   }
	   else
	   {
	    $cla="header4_2";
	   }
    $output .='<tr><td class="'.$cla.'">'.$sqlcr['scheme_name'].'</td><td class="'.$cla.'">'.$sqlcr['tsac'].'</td><td class="'.$cla.'">'.$sqlcr['interest_paid'].'</td><td class="'.$cla.'">'.$sqlcr['capital_subsidy'].'</td><td class="'.$cla.'"></td><td class="'.$cla.'"></td><td class="'.$cla.'">'.number_format($sqlcr['fdramount'],2).'</td></tr>'; 
	$l++;
 }

$output .='</table>';
 
 //echo $record;
	
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('interestleadger_'.time().'.pdf', 'I');
	
}


if($_REQUEST['op'] == 'alrcases'){
global $user, $base_url;
$startdate =$_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];

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
ALR Cases With Status</td></tr>
<tr><td class="header1" colspan="5"><b>From Date :</b> '. date('d-m-Y',$startdate).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.date('d-m-Y',$enddate).'</td></tr>
</table>';

$output .='<table cellpadding="3" cellspacing="2">
		  <tr><td colspan="5" align="right"></td></tr>
<tr><td class="header2" align="center" width="5%"><b>S. No.</b></td>
<td class="header2" align="center" width="14%"><b>Account No.</b></td>
<td class="header2" width="13%"><b>Loanee Name</b></td>
<td class="header2"><b>Loanee Address</b></td>
<td class="header2"><b>Last Payment made</b></td>
<td class="header2" width="28%"><b>Date of ALR</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Status of ALR</b></td>
</tr>';

 $wr="select account_number from tbl_writ where tbl_writ.current_time>='".$startdate."' or tbl_writ.current_time<='".$enddate."' group by account_number";
$wrq=db_query($wr);
$l=1;
while($wrr=db_fetch_array($wrq))
{
 $wrr['account_number'];


$sql="select tbl_loanee_detail.account_id,tbl_loanee_detail.fname,tbl_loanee_detail.address1,tbl_loanee_detail.address2 from tbl_loanee_detail 
where alr_status=2 and tbl_loanee_detail.account_id='".$wrr['account_number']."'";
$query=db_query($sql);


while($res=db_fetch_array($query))
{   
   if($l%2==0)
   {
    $cla="header4_1";
   }
   else
   {
     $cla="header4_2";
   }
   $ss="select max(payment_date) as last_date from tbl_loan_amortisaton where loanacc_id='".$res['account_id']."'";
   $q=db_query($ss);
   $r=db_fetch_array($q);
    $st="select * from tbl_writ where account_number ='".$res['account_id']."'";
   $qt=db_query($st);
   $sta ='<table>';
   while($rt=db_fetch_array($qt))
   {  if($rt['status']=='amapp_property')
       {
         $stat="Movable Property Attached";
       }
	   else if($rt['status']=='iamapp_property')
	   {
	      $stat="Fixed Property Attached";
	   }
	   else
	   {
	    $stat=$rt['status'];
	   }
     $sta .='<tr><td>';
	 
	$sta .= date('d-m-Y',$rt['current_time']); 
	
	$sta .='</td><td>'.ucwords($stat).'</td></tr>';
   }
   $sta .='</table>';
   
   $ld=explode('-',$r['last_date']);
   $output .='<tr><td class="'.$cla.'" align="center">'.$l.'</td><td class="'.$cla.'" align="right">'.$res['account_id'].'</td><td class="'.$cla.'">'.ucwords($res['fname']).'</td><td class="'.$cla.'">'.$res['address1'].'<br>'.ucwords($res['address2']).'</td><td class="'.$cla.'" align="center">'.$ld[2].'-'.$ld[1].'-'.$ld[0].'</td><td class="'.$cla.'">'.ucwords($sta).'</td></tr>';
   $l++;
} 
}
$output .='</table>';
 
 //echo $record;
	
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('alrcases_'.time().'.pdf', 'I');
	
}



