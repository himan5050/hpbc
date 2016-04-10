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


if($_REQUEST['op'] == 'issue'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
$itemcode=$_REQUEST['itemcode'];
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
width:980px;
}
table.tbl_border{border:2px solid #ffffff;
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

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0" class="tbl_border">
<tr><td class="header_report" colspan="5" align="center">
Stock Issue Register</td></tr>

</table><br />';
	
$cond='';
	  if(isset($_REQUEST['itemcode']) && $_REQUEST['itemcode']!='')
	  {
	    $cond.= "and im.code='".$_REQUEST['itemcode']."'";
	  }

   $s="select at.itemdetails,at.date,at.quantity,im.name,im.itemrate,at.enteredby,im.openingval,at.checkedby,at.verifyphysically,at.remarks from assigned_item as at,item_master as im where 1=1 ".$cond." and (at.date>='".$from."' and at.date<='".$to."') and at.itemdetails=im.code";
   $output .='<table cellpadding="0" cellspacing="0" border="0">

<tr><td class="header1"><b>From Date :</b> '. date('d-m-Y',$from).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.date('d-m-Y',$to).'</td></tr>
</table><br />';
 




$output .='<table  cellpadding="2" cellspacing="2" class="tbl_border">
<tr><td class="header2" align="center">S. No.</td>
<td class="header2">Name of the article</td>
<td class="header2">Date</td>
<td class="header2">Quantity</td>
<td class="header2">Rate</td>
<td class="header2">Amount</td>
<td class="header2">Balance Quantity</td>
<td class="header2">Balance Amount</td>
<td class="header2">Entered by</td>
<td class="header2">Checked by</td>
<td class="header2">Name of person who has verified the item physically</td>
</tr>';




	 
	$q=db_query($s);
	$n=1;
	if($n)
	{ $counter=1;
	  while($r=db_fetch_object($q))
	  {   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
	    $output .='<tr>
		<td class="'.$class.'" align="center">'.$counter.'</td>
		<td class="'.$class.'">'.ucwords($r->name).'</td>
		<td class="'.$class.'" align="center">'.date('d-m-Y',$r->date).'</td>
		<td class="'.$class.'" align="right">'.$r->quantity.'</td>
		<td class="'.$class.'" align="right">'.$r->itemrate.'</td>
		<td class="'.$class.'" align="right">'.($r->quantity * $r->itemrate).'</td>
		<td class="'.$class.'" align="right">'.$r->openingval.'</td>
		<td class="'.$class.'" align="right">'.($r->openingval*$r->itemrate).'</td>
		
		<td class="'.$class.'">'.ucwords($r->enteredby).'</td>
		<td class="'.$class.'">'.ucwords($r->checkedby).'</td>
		<td class="'.$class.'">'.ucwords($r->verifyphysically).'</td>
		</tr>';
		
		$counter++;
	  }
	}
	  
	  $output .="</table>";
	

 
		 $output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('stock_issue_'.time().'.pdf', 'I');
}

if($_REQUEST['op'] == 'stock'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
$itemcode=$_REQUEST['item'];
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
width:980px;
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

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Stock Register</td></tr>

</table>';
	
$cond='';
	  if(isset($itemcode) && $itemcode!='')
	  {
	    $cond.= "and im.code='".$itemcode."'";
		 $opb="select openval from item_master where code='".$itemcode."'";
	  $opbq=db_query($opb);
	  $opbr=db_fetch_array($opbq);
	  $op_bal=$opbr['openval'];
	  }



  $s="select at.code,at.date,at.quantity,im.name,im.itemrate,at.enteredby,im.openingval,at.checkedby,at.verifyphysically,at.details,at.remarks,at.billno from item_details as at,item_master as im where 1=1 ".$cond." and (at.date>='".$from."' and at.date<='".$to."') and at.code=im.code";
   
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>From Date :</b> '. date('d-m-Y',$from).'                <b>To Date :</b> '.date('d-m-Y',$to).'</td></tr>';
if(isset($itemcode) && $itemcode!='')
	  {
$output .='<tr><td class="header1" align="center"><b>Opening Balance:</b> '.$op_bal.'</td></tr>';
}
$output .='</table><br/>';
 

$output .='<table  cellpadding="3" cellspacing="2" class="tbl_border">
<tr >
<td class="header2">S. No.</td>
<td class="header2">Name of the article</td>
<td class="header2">Date</td>
<td class="header2">Particulars</td>
<td class="header2">Bill No</td>
<td class="header2">Receive Quantity</td>
<td class="header2">Issue Quantity</td>
<td class="header2">Balance Quantity</td>
<td class="header2">Entered by</td>
</tr>
';



	  
	$q=db_query($s,$db);
	$n=1;
	$m=1;
	if($n)
	{ $counter=0;
	  while($r=db_fetch_array($q))
	  { 
	   if($m%2==0)
		{
		  $cl="header4_2";
		}
		else
		{
		  $cl="header4_1";
		}
	  $qua=$qua+$r['quantity'];
	    $output.='<tr><td class="'.$cl.'">'.$m.'</td><td class="'.$cl.'">'.ucwords($r['name']).'</td><td class="'.$cl.'" align="center">'.date('d-m-Y',$r['date']).'</td><td class="'.$cl.'">'.ucwords($r['details']).'<br>'.$r['remarks'].'</td><td class="'.$cl.'">'.$r['billno'].'</td><td class="'.$cl.'" align="right">'.$r['quantity'].'</td><td class="'.$cl.'"></td><td class="'.$cl.'" align="right">'.$qua.'</td><td class="'.$cl.'">'.ucwords($r['enteredby']).'</td></tr>';
		$counter++;
		$m++;
	  }
	}
	
	$sa="select at.itemdetails,at.date,at.quantity,im.name,im.itemrate,at.enteredby,im.openingval,at.checkedby,at.remarks,at.office from assigned_item as at,item_master as im where 1=1 ".$cond." and (at.date>='".$from."' and at.date<='".$to."') and at.itemdetails=im.code ";
	$qa=db_query($sa,$db);
	$na=1;
	if($na)
	{
	  while($ra=db_fetch_array($qa))
	  { 
	     if($m%2==0)
		{
		  $cl="header4_2";
		}
		else
		{
		  $cl="header4_1";
		}
	   $qua=$qua-$ra['quantity']; 
	    $sqll = "SELECT loccode, locationname FROM locations where loccode='".$ra['office']."'";
             $resultStkLocs = db_query($sqll,$db);
			 $myrow=db_fetch_array($resultStkLocs);
	    $output.='<tr ><td class="'.$cl.'">'.$m.'</td><td class="'.$cl.'">'.ucwords($ra['name']).'</td><td class="'.$cl.'" align="center">'.date('d-m-Y',$ra['date']).'</td><td class="'.$cl.'">Issued To: '.$myrow['locationname'].'<br>'.ucwords($ra['remarks']).'</td><td class="'.$cl.'"></td><td class="'.$cl.'"></td><td class="'.$cl.'" align="right">'.$ra['quantity'].'</td><td class="'.$cl.'" align="right">'.$qua.'</td><td class="'.$cl.'">'.ucwords($ra['enteredby']).'</td></tr>';
		
		$m++;
		
	  }
	}
	
	  
	  $output.='</table>';
	

 
		 //$output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('stock_issue_'.time().'.pdf', 'I');
}

if($_REQUEST['op'] == 'condemned'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

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
width:980px;
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
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Condemned Items</td></tr>

</table>';
	


   $s="select at.code,at.date,at.quantity,im.name,im.itemrate,at.particulars,im.openingval,at.written_value from condem_item as at,item_master as im where (at.date>='".$from."' and at.date<='".$to."') and at.code=im.code";
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>From Date :</b> '. date('d-m-Y',$from).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.date('d-m-Y',$to).'</td></tr>
</table><br/>';
 

$output .='<table  cellpadding="2" cellspacing="2" class="tbl_border">
<tr >
<td  class="header2"><b>S. No.</b></td>
<td  class="header2"><b>Name of the article</b></td>
<td  class="header2"><b>Date of condemn</b></td>
<td  class="header2"><b>Quantity</b></td>
<td  class="header2"><b>Particulars</b></td>
<td  class="header2"><b>Depreciation value</b></td>
<td  class="header2"><b>Amount received</b></td></tr>
';


	  
	$q=db_query($s);
	$n=1;
	if($n)
	{
	{ $counter=1;
	  while($r=db_fetch_object($q))
	  { 
	
		
		  if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
	    $output .='<tr>
		<td class="'.$class.'">'.$counter.'</td>
		<td class="'.$class.'">'.ucwords($r->name).'</td>
		<td class="'.$class.'" align="center">'.date('d-m-Y',$r->date).'</td>
		<td class="'.$class.'" align="right">'.$r->quantity.'</td>
		<td class="'.$class.'">'.ucwords($r->particulars).'</td>
		<td class="'.$class.'" align="right">'.($r->quantity * $r->itemrate).'</td>
		<td class="'.$class.'" align="right">'.$r->written_value.'</td></tr>';
		
		$counter++;
		
	  }
	}
	
	  
	  $output.="</table>";
	

 
		 //$output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('stock_issue_'.time().'.pdf', 'I');
}
}
