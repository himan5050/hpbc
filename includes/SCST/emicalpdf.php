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
$pdf->AddPage();

if($_REQUEST['op']  == 'emical'){
global $user, $base_url;

$loan_amount = $_REQUEST['loan_amount'] ;
$interest = $_REQUEST['interest'];
$subsidy = $_REQUEST['subsidy'];
$tenuare = $_REQUEST['tenuare'];


$output='';

$output .=add_css();


$p = $_REQUEST['loan_amount'];
  $p_orgi = $_REQUEST['loan_amount'];
  //$sub = 10000;

  if($_REQUEST['subsidy']){
    $sub =$_REQUEST['subsidy'];
  }else{
    $sub =0;
  }
  $psub_val = ($p-$sub);
   $r = $_REQUEST['interest'];
  $rpayapay = $r/1200;
  
  $eminew = ($psub_val*$rpayapay)*pow((1+$rpayapay), $_REQUEST['tenuare'])/(pow((1+$rpayapay),$_REQUEST['tenuare'])-1);
  
  $n = $_REQUEST['tenuare'];
  $t = $n/12;
  //$n = $t*12;
  //$N = $n+1;
  //$r = $interest;
  //$N1 = $n*2;
  //$rt = $r*$t;
  //$round = round($N/$N1,2);
 //$round = '.529';
  //$rounda = round($rt/100,2);
  //$round = $N/$N1;
//$rounda = $rt/100;
  //$int = round(($p-$sub)*$round*$rounda,2);
  $int=round((($eminew * $n)- $psub_val ),2);
  $interestfinal = $int;
  $totalp = ($p_orgi-$sub)+$int;
  //$int = ($p-$sub)*$round*$rounda;
  //$year_int = ($p-$sub)+$int;
  $year_int = ($p-$sub)+$int;
  $year_int_val = $year_int/$n;
  
//$emi_cal = round($year_int_val,2);
   //$emi_cal = $year_int_val;
  
$emi_calcal = round($eminew,4);
  	
   $emi_cal = round($eminew,2);
  for($k=1;$k<=$n;$k++){
     
         $p_rate = round($psub_val*$r,4);
	    // $p_rate = round($psub_val*$r,2);
		//$p_rate = $psub_val*$r;
	     $psub_div = 12*100;
		 //$psub_div = 12*100;
	    $p_orval = round(($p_rate/$psub_div),4);
		$p_orvald = round(($p_rate/$psub_div),2);
		// $p_orval = $p_rate/$psub_div;
	 
	
	  $bachat_khata = round($emi_calcal-$p_orval,4);
	  $bachat_khatad = round($emi_calcal-$p_orval,2);
	 // $bachat_khata = $emi_cal-$p_orval;
	  $p_new  = round($psub_val-$bachat_khata,4);
	  //$p_new  = $psub_val-$bachat_khata;
	  $p = $p_new;
	  $psub_val = round($p_new,4);
	  $psub_vald = round($p_new,2);
	  
	   if($k == $n){
	    $psub_val = 0.00;
	  }
	 
    if($k%2 == 0){
	  $class= 'header4_1';
	 }else{
	  $class = 'header4_2';
	}
 

$valout .='<tr class="'.$class.'">
				<td class="'.$class.'" align="right">'.$k.'</td>
				<td class="'.$class.'" align="right">'.$emi_cal.'</td>
				<td class="'.$class.'" align="right">'.$p_orvald .'</td>
				<td class="'.$class.'" align="right">'.$bachat_khatad .'</td>
				<td class="'.$class.'" align="right">'.$psub_vald.'</td>
		   </tr>';
  }


$output .='<table><tr><td align="left"><table cellpadding="2" cellspacing="2" border="0" class="tbl_border">
			<tr class="evenrow">
		   		<td width="12%" class="header2"><b>Loan Amount:</b></td>	
				<td width="15%" class="header2">'.$loan_amount.'</td>								
		   	</tr>
			<tr class="oddrow">
		   		<td class="header2"><b>Interest Rate:</b></td>
				<td class="header2">'.$interest.'</td>									
		   	</tr>
			<tr class="evenrow">
		   		<td class="header2"><b>Subsidy Amount:</b></td>
				<td class="header2">'.$subsidy.'</td>									
		   	</tr>
			<tr class="oddrow">
		   		<td class="header2"><b>Tenure:</b></td>
				<td class="header2">'.$tenuare.'</td>									
		   	</tr>			
		   </table></td>
';	

$output .='<td><table cellpadding="2" cellspacing="2" border="0" class="tbl_border">
			<tr class="evenrow">
		   	    <td width="12%" class="header2"><b>Interest:</b></td>
				<td width="15%" class="header2">'.$interestfinal.'</td>									
		   	</tr>
			<tr class="oddrow">
		   		<td class="header2"><b>Total Amount:</b></td>
				<td class="header2">'.$totalp.'</td>									
		   	</tr>			
		   </table></td></tr></table><br/>
';	

$output .='
	<table cellpadding="2" cellspacing="2" border="0" class="tbl_border">
		<tr>
			<td width="17%" align="left" class="header2">Month</td>
			<td width="17%" align="left" class="header2">EMI</td>
			<td width="17%" align="left" class="header2">Interest Paid</td>
			<td width="17%" align="left" class="header2">Principal Paid</td>
			<td width="18%" align="left" class="header2">Ending Balance</td>
		</tr>';

$output .=$valout;
$output .='</table>';
	
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('emical_'.time().'.pdf', 'I');
}	