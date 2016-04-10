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
width:2030px;
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
$month = $_REQUEST['month'];
$ppmonth = ($_REQUEST['month'] < 10)?'0'.$_REQUEST['month'] - 1:$_REQUEST['month'];
$pmonth = ($_REQUEST['month'] < 10)?'0'.$_REQUEST['month']:$_REQUEST['month'];
$datefrom = $_REQUEST['datefrom'];
$dateto = $_REQUEST['dateto'];
if($_REQUEST['month'] >= 4)
{
	$year = $_REQUEST['year'];
	$begnningofyear = $_REQUEST['year']."-04-01";
}else{
	$year = $_REQUEST['year'] - 1;
	$begnningofyear = ($_REQUEST['year'] - 1)."-04-01";
}
$end_of_month = date("d",mktime(23, 59, 59, date('m',$datefrom)+1, 00));

$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td class="header_report" align="center">RECOVERY AND OUTSTANDING POSITION UNDER DIFFERENT SCHEMES BEING RUN BY THE CORPORATION FOR THE MONTH OF '.date("F",strtotime($_REQUEST['year'].'-'.$pmonth.'-01')).'/'.$_REQUEST['year'].'</td></tr></table><br />';
global $user, $base_url;

		$output .= '<table class="tbl_border" cellpadding="2" cellspacing="2"><tr>';
		$output .= '<td class="header2" rowspan="3">S.No.</td><td  class="header2" rowspan="3">Name of Scheme Office</td><td class="header2" colspan="3">Total No. of Running A/C"s beginning as on</td><td class="header2" colspan="3">Outstanding /Openeing Balance as on '.date("d-m-Y",strtotime($begnningofyear)).'</td><td class="header2" colspan="3">Over due</td><td class="header2" rowspan="3">Loan finaced during the year</td><td class="header2" rowspan="3">Total demand for the year</td><td class="header2" colspan="4">Total Amount recovered upto previous month</td><td class="header2" colspan="4">Total Amount recovered during the month</td><td class="header2" rowspan="3">Total Recovery</td><td class="header2" rowspan="3">%</td><td class="header2" colspan="2">No. of accounts Closed</td></tr>';
		
		$output .= '<tr><td class="header2" rowspan="2">Beginning of the year</td><td class="header2" rowspan="2">No. of Cases financed during the year</td><td class="header2" rowspan="2">Total</td><td class="header2" colspan="3">Beginning of the year/quarter</td><td class="header2" colspan="3">Beginning of the quarter as on.........</td><td class="header2" colspan="2">Against Overdue</td><td class="header2" colspan="2">Current Demand</td><td class="header2" colspan="2">Against Overdue</td><td class="header2" colspan="2">Current Demand</td><td class="header2" rowspan="2">During the month</td><td class="header2" rowspan="2">During the year upto the month</td></tr>';
		
		$output .= '<tr><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">LD</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">LD</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">Prin.</td><td class="header2">Intt.</td></tr>';
		$sql = "SELECT s.schemeName_id,s.schemeName_name as scheme_name FROM tbl_schemenames s ORDER BY s.schemeName_name";
		$res = db_query($sql);
		$output .= '<tr >';
		for($i = 1; $i <=25; $i++)
		{
			$output .= '<td class="header4_2">'.$i.'</td>';
		}
		$output .= '</tr>';
		$counter = 0;
		$grandtotal_accounts = 0;
		$gfrandtotal_accounts = 0;
		$totalcur_dem_prin = 0;
		$totalcur_dem_int = 0;
		$totaloverdue_prin = 0;
		$totaloverdue_int = 0;
		$dtotalcur_dem_prin = 0;
		$dtotalcur_dem_int = 0;
		$dtotaloverdue_prin = 0;
		$dtotaloverdue_int = 0;
		$gtotal_rec = 0;
		$gclosed_during_month = 0;
		$gclosed_during_year = 0;
		$totacc = 0;
		while($r = db_fetch_object($res))
		{
			$lsql = "SELECT COUNT(ld.loan_id) as totalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_schemenames s,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND s.schemeName_id = sm.main_scheme AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND sm.loan_type = 148 AND ld.payment_order_released_date != '0000-00-00' AND ld.sanction_date < '".$begnningofyear."' AND  ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = '".$r->schemeName_id."'  GROUP BY s.schemeName_name";
			//echo $lsql;exit;
			$tloan = db_query($lsql);
			$runingacc = db_fetch_object($tloan);
			$grandtotal_accounts += $runingacc->totalaccounts;
			if($counter % 2)
				$cl = 'header4_2';
			else
				$cl = 'header4_1';
			$counter++;
			$fsql = "SELECT COUNT(ld.loan_id) as ftotalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_schemenames s,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND s.schemeName_id = sm.main_scheme AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.loan_disburse_date < '".$_REQUEST['year']."-".$pmonth."-".$end_of_month."'  AND ld.loan_disburse_date >= '".$begnningofyear."' AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = '".$r->schemeName_id."'  GROUP BY s.schemeName_name";
			//echo $lsql;exit;
			$floan = db_query($fsql);
			$facc = db_fetch_object($floan);
			$gfrandtotal_accounts += $facc->ftotalaccounts;
			$totacc += ($runingacc->totalaccounts + $facc->ftotalaccounts);
			$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND s.schemeName_id = '".$r->schemeName_id."' AND ld.loan_status = 0 AND ld.closed_date != '0000-00-00' AND sm.loan_type = 148 AND ld.closed_date <='".$_REQUEST['year'].'-'.$pmonth."-".$end_of_month."'";
			//echo $sql;exit;
			$result = db_query($sql);
			$closedacc = db_fetch_object($result);
			$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND s.schemeName_id = '".$r->schemeName_id."' AND ld.loan_status = 0 AND ld.closed_date != '0000-00-00' AND sm.loan_type = 148 AND DATE_FORMAT(ld.closed_date,'%Y-%m') ='".$_REQUEST['year'].'-'.$pmonth."'";
			//echo $sql;exit;
			$result = db_query($sql);
			$closedacc1 = db_fetch_object($result);
			$output .= '<tr>';
			$output .= '<td align="right" class="'.$cl.'">'.$counter.'</td><td class="'.$cl.'">'.$r->scheme_name.'</td><td align="right" class="'.$cl.'">'.$runingacc->totalaccounts.'</td>';
			$output .= '<td align="right" class="'.$cl.'">'.$facc->ftotalaccounts.'</td>';//Cases Financed During the year
			$output .= '<td align="right" class="'.$cl.'">'.($runingacc->totalaccounts + $facc->ftotalaccounts).'</td>';
			$output .= '<td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td>';
			
			$pemisql = "SELECT SUM( principal ) toprin, SUM( interest ) toint, period_diff('".$_REQUEST['year'].$ppmonth."', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) AS month FROM tbl_loan_detail ld,tbl_scheme_master sm,tbl_loan_emi_schedule les,tbl_schemenames s WHERE ld.scheme_name = sm.loan_scheme_id  AND s.schemeName_id = sm.main_scheme AND les.month < period_diff('".$_REQUEST['year'].$ppmonth."', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) + 1 AND s.schemeName_id = '".$r->schemeName_id."' AND sm.loan_type = 148 AND ld.loan_disburse_date <= '".$_REQUEST['year'].'-'.$ppmonth.'-'.$end_of_month."' GROUP BY s.schemeName_id ORDER BY s.schemeName_id";
			$pemires = db_query($pemisql);
			$pemitot = db_fetch_object($pemires);


			$psql = "SELECT sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_scheme_master sm,tbl_schemenames s WHERE ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND am.loan_id = ld.loan_id AND s.schemeName_id = '".$r->schemeName_id."' AND sm.loan_type = 148 AND  am.payment_date < '".$_REQUEST['year'].'-'.$ppmonth."-01' GROUP BY s.schemeName_id ";
			$preshand = db_query($psql);
			$pa = db_fetch_object($preshand);


			$pcur_dem_prin = (($pemitot->toprin - $pa->totprin) > 0)?($pemitot->toprin - $pa->totprin):0;
			$pcur_dem_int = (($pemitot->toint - $pa->totint) > 0)?($pemitot->toint - $pa->totint):0;

			$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND am.loan_id = ld.loan_id AND s.schemeName_id = '".$r->schemeName_id."' AND sm.loan_type = 148 AND  am.payment_date < '".$_REQUEST['year'].'-'.$pmonth."-01' GROUP BY s.schemeName_id";

			
			
			//$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND am.loan_id = ld.loan_id AND s.schemeName_id = '".$r->schemeName_id."' AND sm.loan_type = 148 AND  am.payment_date < '".$_REQUEST['year'].'-'.$pmonth."-01' GROUP BY s.schemeName_id";
			$reshand = db_query($sql);
			$a = db_fetch_object($reshand);
			$acc = 0;
			$printotal = 0;
			$inttotal = 0;
			$cur_dem_prin = 0;
			$cur_dem_int = 0;
			$overdue_prin = 0;
			$overdue_int = 0;
			$co = 0;
			$cur_dem_prin = (($a->totprin - $pcur_dem_prin)> 0)?($a->totprin - $pcur_dem_prin):0;
			$cur_dem_int = (($a->totint - $pcur_dem_int) > 0)?($a->totint - $pcur_dem_int):0;
			$overdue_prin = (($a->totprin - $pcur_dem_prin)> 0)?$pcur_dem_prin:$a->totprin;
			$overdue_int = (($a->totint - $pcur_dem_int) > 0)?$pcur_dem_int:$a->totint;

			$totalcur_dem_prin += $cur_dem_prin;
			$totalcur_dem_int += $cur_dem_int;
			$totaloverdue_prin += $overdue_prin;
			$totaloverdue_int += $overdue_int;

			
			$total_rec1 = $overdue_prin + $overdue_int + $cur_dem_prin + $cur_dem_int;
			$output .= '<td  align="right" class="'.$cl.'">'.round($overdue_prin).'</td><td  align="right" class="'.$cl.'">'.round($overdue_int).'</td><td  align="right" class="'.$cl.'">'.round($cur_dem_prin).'</td><td  align="right" class="'.$cl.'">'.round($cur_dem_int).'</td>';
			
			$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS pdate, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND am.loan_id = ld.loan_id AND s.schemeName_id = '".$r->schemeName_id."' AND DATE_FORMAT(am.payment_date,'%Y-%m') = '".$_REQUEST['year'].'-'.$pmonth."' AND sm.loan_type = 148 GROUP BY s.schemeName_id";
			if($counter == 2)
			{
				//echo $pemisql."<br>";
				//echo $sql;exit;
			}
			//echo $sql;exit;
			$reshand = db_query($sql);
			$a = db_fetch_object($reshand);
			$acc = 0;
			$printotal = 0;
			$inttotal = 0;
			$ccur_dem_prin = 0;
			$ccur_dem_int = 0;
			$coverdue_prin = 0;
			$coverdue_int = 0;
			$co = 0;

			$coverdue_prin = (($a->totprin - $overdue_prin) > 0)?$overdue_prin:$a->totprin;
			$coverdue_int = (($a->totint - $overdue_int) > 0)?$overdue_int:$a->totint;
			$ccur_dem_prin = (($a->totprin - $overdue_prin) > 0)?($a->totprin - $overdue_prin):'-';
			$ccur_dem_int = (($a->totint - $overdue_int) > 0)?($a->totint - $overdue_int):'-';
			
			$dtotalcur_dem_prin += $ccur_dem_prin;
			$dtotalcur_dem_int += $ccur_dem_int;
			$dtotaloverdue_prin += $coverdue_prin;
			$dtotaloverdue_int += $coverdue_int;

			$total_rec2 = $coverdue_prin + $coverdue_int + $ccur_dem_prin + $ccur_dem_int;
			$total_rec = $total_rec2 + $total_rec1;
			$gtotal_rec += $total_rec;
			$gclosed_during_month += $closedacc1->closed;
			$gclosed_during_year += $closedacc->closed;
			$output .= '<td  align="right" class="'.$cl.'">'.round($coverdue_prin).'</td><td  align="right" class="'.$cl.'">'.round($coverdue_int).'</td><td  align="right" class="'.$cl.'">'.round($ccur_dem_prin).'</td><td  align="right" class="'.$cl.'">'.round($ccur_dem_int).'</td>';
			$output .= '<td  align="right" class="'.$cl.'">'.round($total_rec).'</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">'.$closedacc1->closed.'</td><td  align="right" class="'.$cl.'">'.$closedacc->closed.'</td>';

			$output .= '</tr>';
		}
		$output .= '<tr><td  align="right" class="'.$cl.'">&nbsp;</td><td  align="right" class="'.$cl.'">Total</td><td  align="right" class="'.$cl.'">'.$grandtotal_accounts.'</td><td  align="right" class="'.$cl.'">'.$gfrandtotal_accounts.'</td><td  align="right" class="'.$cl.'">'.$totacc.'</td>';
		$output .= '<td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td>';
		$output .= '<td  align="right" class="'.$cl.'">'.round($totaloverdue_prin).'</td><td  align="right" class="'.$cl.'">'.round($totaloverdue_int).'</td><td  align="right" class="'.$cl.'">'.round($totalcur_dem_prin).'</td><td  align="right" class="'.$cl.'">'.round($totalcur_dem_int).'</td>';
		$output .= '<td  align="right" class="'.$cl.'">'.round($dtotaloverdue_prin).'</td><td  align="right" class="'.$cl.'">'.round($dtotaloverdue_int).'</td><td  align="right" class="'.$cl.'">'.round($dtotalcur_dem_prin).'</td><td  align="right" class="'.$cl.'">'.round($dtotalcur_dem_int).'</td>';
		$output .= '<td  align="right" class="'.$cl.'">'.round($gtotal_rec).'</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">'.round($gclosed_during_month).'</td><td  align="right" class="'.$cl.'">'.round($gclosed_during_year).'</td></tr>';
		$output .= "</table>";
		$output .= "</table>";
	ob_end_clean();
$pdf->writeHTML($output, true, 0, true, true);
//Close and output PDF document
$pdf->Output('schemerecovery_'.time().'.pdf', 'I');
?>