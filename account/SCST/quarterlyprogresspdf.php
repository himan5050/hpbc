<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A2, true, 'UTF-8', false);
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

font-weight:bold;
background-color:#ffffff;
}
table{
width:1950px;
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

global $user, $base_url;

	$district = $_REQUEST['district'];
	if(date("m") >= 4)
	{
		$current_quarter = '01-04-'.date("Y");
		$lastquarter = '31-03-'.date("Y");
		$qlastdate = '30-06-'.date("Y");
	}else{
		$current_quarter =  date("d-m-Y",mktime(0, 0, 0, 4, 1, date("Y") - 1));
		$lastquarter = date("d-m-Y",mktime(0, 0, 0, 3, 31, date("Y") - 1));
		$qlastdate =  date("d-m-Y",mktime(0, 0, 0, 6, 30, date("Y") - 1));
	}
	$dres = db_query("SELECT district_name FROM tbl_district WHERE district_id = $district LIMIT 1");
	$d = db_fetch_object($dres);
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">Quarterly Progress Report in respect of District - '.ucwords($d->district_name).' </td></tr></table><br />';

		$output .= '<table border="0" cellpadding="2" cellspacing="2" class="tbl_border"><tr><td class="header2" align="center" width="2%">S. No.</td><td class="header2" align="center">Name of Loanee</td><td class="header2" align="center">Name of Scheme</td><td class="header2" align="center">Total Amount Finaced</td><td class="header2" align="center" colspan="4" width="20%">Total Outstanding  at the begining of the quarter as on '.$current_quarter.'</td><td class="header2" align="center">Loan financed during the year</td><td class="header2" align="center" colspan="3">Over due last quarter as on '.$lastquarter.'</td><td class="header2" align="center" colspan="3">Current demand during the quarter w.e.f '.$current_quarter.' to '.$qlastdate.'.</td><td class="header2" align="center">Total demand</td><td class="header2" align="center" colspan="4">Recovery during the quarter</td><td class="header2" align="center" colspan="4">Over due end of the quarter as on '.$qlastdate.'</td><td class="header2" align="center" colspan="4">Total Outstanding at the end of quarter as on '.$qlastdate.'</td><td class="header2" align="center">Vol. Rec excess on demand</td></tr><tr>';
		
		$output .= '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td class="header2" align="center">Prin.</td><td class="header2" align="center">Intt.</td><td class="header2" align="center">LD</td><td class="header2" align="center">Total</td>';
		
		$output .= '<td class="header2" align="center">&nbsp;</td><td class="header2" align="center">Prin.</td><td class="header2" align="center">Intt.</td><td class="header2" align="center">LD</td><td class="header2" align="center">Prin.</td><td class="header2" align="center">Intt.</td><td class="header2" align="center">LD</td><td class="header2" align="center">&nbsp;</td><td class="header2" align="center">Prin.</td><td class="header2" align="center">Intt.</td><td class="header2" align="center">LD</td><td class="header2" align="center">Total</td><td class="header2" align="center">Prin.</td><td class="header2" align="center">Intt.</td><td class="header2" align="center">LD</td><td class="header2" align="center">Total</td><td class="header2" align="center">Prin.</td><td class="header2" align="center">Intt.</td><td class="header2" align="center">LD</td><td class="header2" align="center">Total</td><td class="header2" align="center">&nbsp;</td></tr>';
		
		$output .= "<tr>";
		for($i = 1; $i <=29; $i++)
		{
			$output .= '<td class="header2" align="center">'.$i.'</td>';
		}
		$output .= "</tr>";
		
		$sql = "SELECT l.account_id,ld.emi_amount,ld.loan_disburse_date,ld.o_principal,ld.o_interest,ld.o_LD,l.fname,l.lname,sm.loan_scheme_id,sm.scheme_name as scheme,ld.loan_requirement FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND l.district = $district AND l.alr_status = 0 AND sm.loan_type = 148 AND l.account_id ORDER BY l.lname";
		//echo $sql;exit;
		$res = db_query($sql);
		$counter = 0;
		while($r = db_fetch_object($res))
		{
			if($counter % 2)
				$cl = 'header4_2';
			else
				$cl = 'header4_1';
			$counter++;
			$daydiff = dateDiffByDays($r->loan_disburse_date,$current_quarter)/30;
			//OUTSTANDING INTEREST AND PRINCIPAL AT BEGINING OF QUARTER
			$amortq = "SELECT SUM(principal) as outprin,SUM(interest) outint FROM tbl_loan_emi_schedule WHERE loan_id = '".$r->loan_id."' AND month < $daydiff GROUP BY loan_id";
			$amres = db_query($amortq);
			$oamt = db_fetch_object($amres);
			
			//OUTSTANDING LD AT BEGINING OF QUARTER
			$ldq = "SELECT SUM(amount) as outld FROM tbl_loan_interestld WHERE account_id = '".$r->account_id."' AND calculation_date < ".databaseDateFormat($current_quarter,'indian','-')." AND type = 'LD' GROUP BY account_id";
			$ldres = db_query($amortq);
			$oldamt = db_fetch_object($ldres);
			$ctotaloutstanding_amt = $oamt->outprin + $oamt->outint + $oldamt->outld;
			
			$out_beg_of_quarter_prin = $oamt->outprin;
			$out_beg_of_quarter_int = $oamt->outint;
			$out_beg_of_quarter_ld = $oldamt->outld;
			
			$output .= '<tr>';
			$output .= '<td align="right" class="'.$cl.'">'.$counter.'</td><td align="left" class="'.$cl.'">'.ucwords($r->fname.' '.$r->lname).'</td><td align="left" class="'.$cl.'">'.ucwords($r->scheme).'</td><td align="right" class="'.$cl.'">'.$r->loan_requirement.'</td>';

			$output .= '<td align="right" class="'.$cl.'">'.$oamt->outprin.'</td><td align="right" class="'.$cl.'">'.$oamt->outint.'</td><td align="right" class="'.$cl.'">'.$oldamt->outld.'</td><td align="right" class="'.$cl.'">'.$ctotaloutstanding_amt.'</td><td align="right" class="'.$cl.'">-</td>';
			//OVERDUE  AT LAST QUARTER
			$overq = "SELECT SUM(principal_paid) as prin,SUM(interest_paid) as interest,SUM(LD_paid) as ld FROM tbl_loan_amortisaton WHERE loanacc_id = '".$r->account_id."' AND payment_date < ".databaseDateFormat($current_quarter,'indian','-')." GROUP BY loanacc_id";
			$overres = db_query($overq);
			$overamt = db_fetch_object($overres);
			
			$overdueprin = $oamt->outprin - $overamt->prin;
			$overdueint = $oamt->outint - $overamt->interest;
			$overdueld = $oldamt->outld - $overamt->ld;
			//$overduetotal = $overdueprin + $overdueint + $overdueld ;
			$output .= '<td align="right" class="'.$cl.'">'.$overdueprin.'</td><td align="right" class="'.$cl.'">'.$overdueint.'</td><td align="right" class="'.$cl.'">'.$overdueld.'</td>';
			
			//CURRENT DEMAND PRINCIPAL, INTEREST DURING THE QUARTER
			$amortq = "SELECT SUM(principal) as outprin,SUM(interest) outint FROM tbl_loan_emi_schedule WHERE loan_id = '".$r->loan_id."' AND month >= 4 AND month <= 6 GROUP BY loan_id";
			$amres = db_query($amortq);
			$cdemand = db_fetch_object($amres);
			
			//CURRENT DEMAND LD DURING THE QUARTER
			$ldq = "SELECT SUM(amount) as outld FROM tbl_loan_interestld WHERE account_id = '".$r->account_id."' AND calculation_date >= ".databaseDateFormat($current_quarter,'indian','-')." AND calculation_date <= ".databaseDateFormat($qlastdate,'indian','-')." GROUP BY account_id";
			$ldres = db_query($ldq);
			$oldamt = db_fetch_object($ldres);
			
			$cur_demand_prin = $cdemand->outprin;
			$cur_demand_int = $cdemand->outint;
			$cur_demand_ld = $oldamt->outld;
			
			$totaldemand_amt = $cdemand->outprin + $cdemand->outint + $oldamt->outld + $overdueprin + $overdueint + $overdueld;
			$output .= '<td align="right" class="'.$cl.'">'.$cdemand->outprin.'</td><td align="right" class="'.$cl.'">'.$cdemand->outint.'</td><td class="'.$cl.'">'.$oldamt->outld.'</td><td align="right" class="'.$cl.'">'.$totaldemand_amt.'</td>';

			$recovq = "SELECT SUM(principal_paid) as prin,SUM(interest_paid) as interest,SUM(LD_paid) as ld FROM tbl_loan_amortisaton WHERE loanacc_id = '".$r->account_id."' AND payment_date >= ".databaseDateFormat($current_quarter,'indian','-')." AND payment_date <= ".databaseDateFormat($qlastdate,'indian','-')." GROUP BY loanacc_id";
			$overres = db_query($recovq);
			$recovamt = db_fetch_object($overres);
			
			$rec_during_quarter_prin = $recovamt->prin;
			$rec_during_quarter_int = $recovamt->interest;
			$rec_during_quarter_ld = $recovamt->ld;
			
			$totalrecovery = $rec_during_quarter_prin + $rec_during_quarter_int + $rec_during_quarter_ld;
			$output .= '<td align="right" class="'.$cl.'">'.$rec_during_quarter_prin.'</td><td align="right" class="'.$cl.'">'.$rec_during_quarter_int.'</td><td align="right" class="'.$cl.'">'.$rec_during_quarter_ld.'</td><td class="'.$cl.'">'.$totalrecovery.'</td>';
			
			//TOTAL OVERDUE AT END OF CURRENT QUARTER ( OVERDUE LAST QUARTER + CURRENT DEMAND)
			$overdue_endof_qprin = ($overdueprin + $cur_demand_prin) - $rec_during_quarter_prin;
			$overdue_endof_qint = ($overdueint + $cur_demand_int) - $rec_during_quarter_int;
			$overdue_endof_qld = ($overdueld + $cur_demand_ld) - $rec_during_quarter_ld;
			$totaloverdue_end_of_quarter = $totaldemand_amt - $totalrecovery;
			$output .= '<td align="right" class="'.$cl.'">'.$overdue_endof_qprin.'</td><td align="right" class="'.$cl.'">'.$overdue_endof_qint.'</td><td align="right" class="'.$cl.'">'.$overdue_endof_qld.'</td><td align="right" class="'.$cl.'">'.$totaloverdue_end_of_quarter.'</td>';
			//OUTSTANDING AT THE END OF THE CURRENT QUARTER
			$outat_end_of_quarter_prin = ($out_beg_of_quarter_prin + $cur_demand_prin) - $rec_during_quarter_prin;
			$outat_end_of_quarter_int = ($out_beg_of_quarter_int + $cur_demand_int) - $rec_during_quarter_int;
			$outat_end_of_quarter_ld = ($out_beg_of_quarter_ld + $cur_demand_ld) - $rec_during_quarter_ld;
			$grandtotaloutstanding = $outat_end_of_quarter_prin + $outat_end_of_quarter_int + $outat_end_of_quarter_ld;
			$output .= '<td align="right" class="'.$cl.'">'.$outat_end_of_quarter_prin.'</td><td align="right" class="'.$cl.'">'.$outat_end_of_quarter_int.'</td><td align="right" class="'.$cl.'">'.$outat_end_of_quarter_ld.'</td><td align="right" class="'.$cl.'">'.$grandtotaloutstanding.'</td><td align="right" class="'.$cl.'">-</td>';
			$output .= '</tr>';
		} 
		$output .= '</table>';
	ob_end_clean();
$pdf->writeHTML($output, true, 0, true, true);
//Close and output PDF document
$pdf->Output('quarterly_'.time().'.pdf', 'I');

?>