<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, B4, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HBCFDC');
$pdf->SetTitle('HBCFDC');
$pdf->SetSubject('HBCFDC');
$pdf->SetKeywords('HBCFDC');
$pdf->SetPrintHeader(false);
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
$pdf->SetFont('times', '', 10);
// add a page
$pdf->AddPage();
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
font-family:Times New Roman;
font-size: 13pt;

font-weight:bold;
background-color:#ffffff;
}
table{
width:1230px;
}
table.tbl_border{border:1px solid #1D374C;
background-color:#1D374C;
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
background-color:#1D374C;
font-family:Verdana;
font-size: 12pt;
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

global $user, $base_url;
$datefrom = $_REQUEST['datefrom'];
$dateto = $_REQUEST['dateto'];


$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">HBCFDC KANGRA (H.P.) Daily Recovery Schedule From: '.date("d-m-Y",$datefrom).' to '.date("d-m-Y",$dateto).'</td></tr><tr ><td align="left"><strong>'.date("d/m/Y").'</strong></td></tr><tr><td>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr></table><br/><table cellpadding="0" cellspacing="0" border="0">
           </table><br />';
			
$sql ="SELECT `id`,
                       `tbl_loanee_detail`.`account_id`,
                       `tbl_loanee_detail`.`fname`,
                       `tbl_loanee_detail`.`lname`,
                       `tbl_loanee_detail`.`district`,
                       `amount`,
                       `paytype`,
                       `payment_date`,
                       `cheque_number`
                FROM `tbl_loan_repayment` 
                INNER JOIN `tbl_loanee_detail`
                ON `tbl_loanee_detail`.`loanee_id` = `tbl_loan_repayment`.`loanee_id`
                WHERE `payment_date` BETWEEN '".date("Y-m-d",$datefrom)."' AND '".date("Y-m-d",$dateto)."'
                ORDER BY `id`";
		
 $query = "SELECT SUM(a.amount_recovered) as alramount 
                 FROM alr a,
                      tbl_loanee_detail l 
                      WHERE l.account_id = a.case_no AND l.alr_status = 2 
                      AND a.date >= ".intval($datefromstr)." AND a.date <=".intval($datetostr)."";
	   
$r = db_query($query);
$alr = db_fetch_object($r);
$output .= '<table class="tbl_border" cellpadding="2" cellspacing="2" width="1200px;"><tr>';

$output .= '<td  class="header2" width="5%">S No.</td>
            <td class="header2">District</td>
			<td class="header2">Loanee Name</td>
			<td class="header2">Account No.</td>
			<td class="header2">Scheme</td>
			<td class="header2">Reciept No.</td>
			<td class="header2">FR No.</td>
			<td class="header2">Recovery Dt.</td>
			<td class="header2" align="right">Amount Recovered</td>
			<td class="header2" align="right">Balance Amount</td></tr>';
		
$counter = 0;
$alltotal = 0;
$allbalance = 0;
$res = db_query($sql);
while($rs = db_fetch_object($res)){
     $counter++;
     $alltotal += $rs->amount;
     $res1 = db_query("SELECT `reg_number` FROM `tbl_loanee_detail` WHERE `account_id` = '".$rs->account_id."'"); 
     $regno = db_fetch_object($res1);
     $reg_number = $regno->reg_number;
            
     $res2 = db_query("SELECT `scheme_name`,`o_principal` FROM `tbl_loan_detail` WHERE `reg_number` = '".$reg_number."'"); 
     $ress2 = db_fetch_object($res2);
     $scheme_name = $ress2->scheme_name;
     $o_principal = $ress2->o_principal;
     $allbalance += $o_principal;        
     $res3 = db_query("SELECT `scheme_name` FROM `tbl_scheme_master` WHERE `loan_scheme_id` = '".$scheme_name."'");
     $ress3 = db_fetch_object($res3);
     $schemename = $ress3->scheme_name;
             
     $res4 = db_query("SELECT `district_name` FROM `tbl_district` WHERE `district_id` = '$rs->district'"); 
     $ress4 = db_fetch_object($res4);
     $district_name = $ress4->district_name;
     // echo 'fetched values = '.$district_name.' | '.$schemename.' | '.$o_principal.' <br />';
	   $res5 = db_query("SELECT `loanee_id` FROM `tbl_loanee_detail` WHERE `reg_number`='".$reg_number ."'");
            $ress5 = db_fetch_object($res5);
            $loanee_id = $ress5->loanee_id;
            
        	$res6 = db_query("SELECT sum(amount) as disam FROM `tbl_loan_disbursement` WHERE loanee_id='".$loanee_id."'");
        	$ress6 = db_fetch_object($res6);
       		$dis_amount = $ress6->disam;
            
            $res7 = db_query("SELECT sum(amount) as repam FROM `tbl_loan_repayment` WHERE loanee_id='".$loanee_id."' AND payment_date<='".date("Y-m-d",$dateto)."'");
        	$ress7 = db_fetch_object($res7);
       		$rep_amount = $ress7->repam;
            
            $res8 = db_query("SELECT `account_id` FROM `tbl_loanee_detail` WHERE `reg_number`='".$reg_number."'");
        	$ress8 = db_fetch_object($res8);
       		$accountid = $ress8->account_id;
            
            $res9 = db_query("SELECT sum(amount) as intam FROM `tbl_loan_interestld` WHERE account_id='".$accountid."' AND calculation_date <= '".date("Y-m-d",$dateto)."' AND type='interest'");
        	$ress9 = db_fetch_object($res9);
       		$int_amount = $ress9->intam;
            
          $outstand = $dis_amount + $int_amount - $rep_amount;	
     if($counter % 2){ $cl = 'header4_1';}
	 else {$cl = 'header4_2';}
			
		$datepay = date("d-m-Y",$rs->payment_date);
	 $output .= '<tr><td class="'.$cl.'" align="center">'.$counter.'</td>
			          <td align="left" class="'.$cl.'">'.$district_name.'</td>
					  <td align="left" class="'.$cl.'">'.$rs->fname.' &nbsp;'.$rs->lname.'</td>
					  <td align="left" class="'.$cl.'">'.$rs->account_id.'</td>
					  <td align="left" class="'.$cl.'">'.$schemename.'</td>
					  <td align="left" class="'.$cl.'">'.$rs->id.'</td>
					  <td align="left" class="'.$cl.'">'.$rs->cheque_number.'</td>
					  <td align="left" class="'.$cl.'">'.$rs->payment_date.'</td>
					  <td align="right" class="'.$cl.'">'.$rs->amount.'</td>
					  <td align="right" class="'.$cl.'">'.$outstand.'</td></tr>';
}

if($cl == 'header4_1')
	$cl = 'header4_2';
else
	$cl = 'header4_1';


$output .= '<tr style="background-color:white;"><td colspan="7"></td>
                       <td align="left" class="'.$cl.'"><strong>Grand Total</strong></td>';
$output .= '<td align="right" class="'.$cl.'"><strong>'.round($alltotal).'</strong></td><td colspan="1" align = "right"><strong>'.round($allbalance).'</strong></td></tr>';

$output .= '</table>';

ob_end_clean();
$pdf->writeHTML($output, true, 0, true, true);
//Close and output PDF document
$pdf->Output('schemerecovery_'.time().'.pdf', 'I');
?>