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




if($_REQUEST['op'] == 'advance_summary'){
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
Advance Summary</td></tr>
</table>';
	
$cond="";
	    if($_REQUEST['section'])
	   {
	     $cond .=" and ( loanadvance.section=".$_REQUEST['section'].")";
		 //$tbl=",tbl_lookups";
	   }
	   
	   if(isset($_REQUEST['type']) && $_REQUEST['type']!='')
	   {
		  $cond .=" and (loanadvance.type_loan='".$_REQUEST['type']."')";  
	   }
		 $cond .=" and tbl_joinings.program_uid=loanadvance.empid";
	   
	   $totbal=0;
	   $totadv=0;
	   
   $s="select * from loanadvance,tbl_joinings where loanadvance.approvestatus=1 $cond";
  
  
$totbal=0;
 	  
/*	    if($_REQUEST['section'])
	   {
	     $cond .="and ( loanadvance.section=".$_REQUEST['section'].")";
		 //$tbl=",tbl_lookups";
	   }
	   
	   
	  $s="select * from loanadvance where 1=1 ".$cond."";*/
	$q=db_query($s);
	//$n=db_num_rows($q);
	
  $output .='<table cellpadding="3" cellspacing="2">
		 
		 <tr><td class="header2" align="center"><b>S. No.</b></td>
		 <td class="header2"><b>Section Name</b></td>
<td class="header2"><b>Amount Received</b></td>
<td class="header2"><b>Balance Amount</b></td>
</tr>';
	 $i=1;
	
	 
	  while($r=db_fetch_array($q))
	  {  $totbal=$totbal+($r['amount']);
	                    $sec="select * from tbl_lookups where lookup_id='".$r['section']."'";
						 $secq=db_query($sec);
						 $secr=db_fetch_array($secq);
	    	   
	  if($i%2==0)
     {
	   $cl="header4_1";
	 }
	 else
	 {
	   $cl="header4_2";
	 }
	 
	 //getting data from empmonthdeduct where Acccode=id of loanadvance  
	 if($r['type_loan'] == 'House And Building Advance'){
    $typeloan = 7.00;
  }
  if($r['type_loan'] == 'Vehicle Advance'){
    $typeloan = 8.00;
  }
  if($r['type_loan'] == 'Warm Clothing Advance'){
    $typeloan = 9.00;
  }
   if($r['type_loan'] == 'Festival Advance'){
    $typeloan = 10.00;
  }
	
 

   $sqlg = "select sum(Amount) as Amount from empmonthdeduct where DeductCode='".$typeloan."'";
   $resg = DB_query($sqlg,$db);
  $rsg=DB_fetch_array($resg);
	 
	    $output .='<tr ><td class="'.$cl.'" align="center">'.$i.'</td><td class="'.$cl.'">'.ucwords($secr['lookup_name']).'</td>
		<td align="right" class="'.$cl.'">'.round(abs($rsg['Amount'])).'</td><td align="right" class="'.$cl.'">'.round(abs($r['amount'])).'</td></tr>';
		$i++;
	  }
	
	  
	  $output .='<tr><td colspan="3" class="header2"><b>Total</b></td><td align="right" class="header2"><b>'.round(abs($totbal)).'</b></td></tr></table>';
	

	


	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('advance_summary_'.time().'.pdf', 'I');
	
	db_set_active('default');
}


if($_REQUEST['op'] == 'advance_detail'){
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
if($_REQUEST['section']!='')
{
 $sec="select * from tbl_lookups where lookup_id='".$_REQUEST['section']."'";
 $secq=db_query($sec);
 $secr=db_fetch_array($secq);
 $sect=$secr['lookup_name'];
 }
 else
 {
  $sect="All Section";
 }
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Advance Detail Report</td></tr>
</table><br>';
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td>
<b>Employee Name: </b>'.ucwords($_REQUEST['empname']).'</td></tr><tr><td>
<b>Section : </b>'.ucwords($sect).'</td></tr>
</table><br>';
	
     
	   if($_REQUEST['empname'])
	   { 
	     $cond .=" and ( tbl_joinings.employee_name like '%".$_REQUEST['empname']."%' )";
		// $cond .=",tbl_joinings where tbl_joinings.program_uid=loanadvance.empid ".$cond1."";
	   }
	   
	    if($_REQUEST['section'])
	   {
	     $cond .=" and ( loanadvance.section=".$_REQUEST['section'].")";
		 //$tbl=",tbl_lookups";
	   }
	   
	   if(isset($_REQUEST['type']) && $_REQUEST['type']!='')
	   {
		  $cond .=" and (loanadvance.type_loan='".$_REQUEST['type']."')";  
	   }
		 $cond .=" and tbl_joinings.program_uid=loanadvance.empid";
	   
	   $totbal=0;
	   $totadv=0;
	   
   $s="select * from loanadvance,tbl_joinings where loanadvance.approvestatus=1 $cond";
	$q=db_query($s);
	
	
  $output .='<table cellpadding="3" cellspacing="2">
		 
		  <tr><td class="header2" align="center"><b>S. No.</b></td>
		  <td class="header2"><b>Section Name</b></td>
<td class="header2"><b>Employee Id</b></td>
<td class="header2"><b>Employee Name</b></td>
<td class="header2"><b>Amount Received</b></td>
<td class="header2"><b>Balance Amount</b></td>
</tr>';
	 $i=1;
	
	
	  while($r=db_fetch_array($q))
	  {  
	    	   $totbal=$totbal+($r['amount']);
			   $totadv=$totadv+$r['advance'];
	  if($i%2==0)
     {
	   $cl="header4_1";
	 }
	 else
	 {
	   $cl="header4_2";
	 }
	 
	                    $sec="select * from tbl_lookups where lookup_id='".$r['section']."'";
						 $secq=db_query($sec);
						 $secr=db_fetch_array($secq);
						 
	    
		  //getting data from empmonthdeduct where Acccode=id of loanadvance  

   $sqlg = "select sum(Amount) as Amount from empmonthdeduct where Acccode='".$r['id']."'";
   $resg = DB_query($sqlg,$db);
  $rsg=DB_fetch_array($resg);
		
		$output .='<tr ><td class="'.$cl.'" align="center">'.$i.'</td><td class="'.$cl.'">'.ucwords($secr['lookup_name']).'</td><td class="'.$cl.'">'.$r['employee_id'].'</td><td class="'.$cl.'">'.ucwords($r['employee_name']).'</td>
		<td align="right" class="'.$cl.'">'.round(abs($rsg['Amount'])).'</td><td align="right" class="'.$cl.'">'.round(abs($r['amount'])).'</td></tr>';
		$i++;
	  }
	
	  
	  $output .='<tr><td colspan="4" class="header2"><b>Total</b></td><td class="header2"></td><td align="right"  class="header2"><b>'.round(abs($totbal)).'</b></td></tr></table>';
	
	

	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('advance_detail_'.time().'.pdf', 'I');
	
	db_set_active('default');
}

if($_REQUEST['op'] == 'billsubmit'){
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
Bill Detail</td></tr>
</table><br>';
	
    
	  $s="select * from billsubmit where (date>='".$from."' and date<='".$to."')";
	$q=db_query($s);
	
  $output .='<table cellpadding="3" cellspacing="2" class="tbl_border">
<tr><td  class="header2" width="8%" align="center"><b>S. No.</b></td>
<td  class="header2"><b>Vendor Name</b></td>
<td  class="header2" width="22%"><b>Work Order</b></td>
<td  class="header2"><b>Bill Date</b></td>
<td  class="header2"><b>Amount</b></td>
<td class="header2"><b>Bill Paid</b></td>
</tr>';
	 $i=1;
	
	
	  while($r=db_fetch_array($q))
	  {  
	    $ss="select * from tbl_pendingvouchers where transactionid='".$r['id']."' and entrytype='billsubmit'";
		$ssq=db_query($ss);
		$ssr=db_fetch_array($ssq);
		
		if($ssr['voucher_number']!='')
		{
		  $sta="Yes";
		}
		else
		{
		 $sta="No";
		}
	   
	  if($i%2==0)
     {
	   $cl="header4_1";
	 }
	 else
	 {
	   $cl="header4_2";
	 }
	    $output .='<tr ><td class="'.$cl.'" align="center">'.$i.'</td><td class="'.$cl.'">'.ucwords($r['name']).'</td><td align="left" class="'.$cl.'"><div style="text-transform:uppercase">'.ucwords($r['refnum']).'</div></td><td align="center" class="'.$cl.'">'.date('d-m-Y',$r['date']).'</td><td align="right" class="'.$cl.'">'.round(abs($r['amount'])).'</td><td class="'.$cl.'">'.$sta.'</td></tr>';
		$i++;
	  }
	
	  
	  $output .='</table>';
	
	ob_end_clean();

	// $output;
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('billsubmit_'.time().'.pdf', 'I');
	
	db_set_active('default');
}


