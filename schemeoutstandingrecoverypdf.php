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
$pmonth = ($_REQUEST['month'] < 10)?'0'.$_REQUEST['month']:$_REQUEST['month'];
if($_REQUEST['month'] >= 4)
{
	$year = $_REQUEST['year'];
	$begnningofyear = $_REQUEST['year']."-04-01";
}else{
	$year = $_P_REQUESTOST['year'] - 1;
	$begnningofyear = ($_REQUEST['year'] - 1)."-04-01";
}
$end_of_month = date("d",mktime(23, 59, 59, date('m',$datefrom)+1, 00));

$output .='<table cellpadding="0" cellspacing="0" border="0" >
<tr><td class="header_report" align="center">RECOVERY AND OUTSTANDING POSITION UNDER DIFFERENT SCHEMES BEING RUN BY THE CORPORATION FOR THE MONTH OF '.date("F",strtotime($_REQUEST['year'].'-'.$pmonth.'-01')).'/'.$_REQUEST['year'].'</td></tr></table><br />';
global $user, $base_url;

		$output .= '<table class="tbl_border" cellpadding="2" cellspacing="2"><tr>';
		$output .= '<td class="header2" rowspan="3">S.No.</td><td  class="header2" rowspan="3">Name of Scheme Office</td><td class="header2" colspan="3">Total No. of Running A/C"s beginning as on</td><td class="header2" colspan="3">Outstanding /Openeing Balance as on</td><td class="header2" colspan="3">Over due</td><td class="header2" rowspan="3">Loan finaced during the year</td><td class="header2" rowspan="3">Total demand for the year</td><td class="header2" colspan="4">Total Amount recovered upto previous month</td><td class="header2" colspan="4">Total Amount recovered during the month</td><td class="header2" rowspan="3">Total Recovery</td><td class="header2" rowspan="3">%</td><td class="header2" colspan="2">No. of accounts Closed</td></tr>';
		
		$output .= '<tr><td class="header2" rowspan="2">Beginning of the year</td><td class="header2" rowspan="2">No. of Cases financed during the year</td><td class="header2" rowspan="2">Total</td><td class="header2" colspan="3">Beginning of the year/quarter</td><td class="header2" colspan="3">Beginning of the quarter as on.........</td><td class="header2" colspan="2">Against Overdue</td><td class="header2" colspan="2">Current Demand</td><td class="header2" colspan="2">Against Overdue</td><td class="header2" colspan="2">Current Demand</td><td class="header2" rowspan="2">During the month</td><td class="header2" rowspan="2">During the year upto the month</td></tr>';
		
		$output .= '<tr><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">LD</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">LD</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">Prin.</td><td class="header2">Intt.</td><td class="header2">Prin.</td><td class="header2">Intt.</td></tr>';
		$sql = "SELECT sm.loan_scheme_id,sm.scheme_name FROM tbl_scheme_master sm WHERE sm.status = 167 AND sm.loan_type = 148 ORDER BY sm.scheme_name";
		$res = db_query($sql);
		$output .= '<tr >';
		for($i = 1; $i <=25; $i++)
		{
			$output .= '<td class="header4_2">'.$i.'</td>';
		}
		$output .= '</tr>';
		$counter = 0;
		$grandtotal_accounts = 0;
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
		while($r = db_fetch_object($res))
		{
			$lsql = "SELECT COUNT(ld.loan_id) as totalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l WHERE ld.reg_number = l.reg_number AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.sanction_date < '".$begnningofyear."' AND l.alr_status = 0 AND ld.scheme_name = '".$r->loan_scheme_id."'  GROUP BY ld.scheme_name";
			//echo $lsql;exit;
			$tloan = db_query($lsql);
			if($counter % 2)
				$cl = 'header4_2';
			else
				$cl = 'header4_1';
			$counter++;
			$grandtotal_accounts += $tloan->totalaccounts;
			$fsql = "SELECT COUNT(ld.loan_id) as ftotalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l WHERE ld.reg_number = l.reg_number AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.loan_disburse_date < '".$_POST['year']."-".$pmonth."-".$end_of_month."' AND l.alr_status = 0 AND ld.scheme_name = '".$r->loan_scheme_id."'  GROUP BY ld.scheme_name";
			//echo $lsql;exit;
			$floan = db_query($fsql);
			$gfrandtotal_accounts += $floan->ftotalaccounts;
			$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id  AND sm.loan_scheme_id = '".$r->loan_scheme_id."' AND ld.loan_status = 0 AND sm.loan_type = 148 AND ld.closed_date <=".$_REQUEST['year'].'-'.$pmonth."-".$end_of_month;
			//echo $sql;exit;
			$result = db_query($sql);
			$closedacc = db_fetch_object($result);
			$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id  AND sm.loan_scheme_id = '".$r->loan_scheme_id."' AND ld.loan_status = 0 AND sm.loan_type = 148 AND DATE_FORMAT(ld.closed_date,'%Y-%m') =".$_REQUEST['year'].'-'.$pmonth;
			//echo $sql;exit;
			$result = db_query($sql);
			$closedacc1 = db_fetch_object($result);
			$output .= '<tr>';
			$output .= '<td align="right" class="'.$cl.'">'.$counter.'</td><td class="'.$cl.'">'.$r->scheme_name.'</td><td align="right" class="'.$cl.'">'.$r->totalaccounts.'</td>';
			$output .= '<td align="right" class="'.$cl.'">-</td>';//Cases Financed During the year
			$output .= '<td align="right" class="'.$cl.'">'.$r->totalaccounts.'</td>';
			$output .= '<td  align="right" class="'.$cl.'">'.$gfrandtotal_accounts.'</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td>';
			
			$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '".$r->loan_scheme_id."' AND sm.loan_type = 148 AND  am.payment_date < '".$_REQUEST['year'].'-'.$pmonth."-01' GROUP BY am.loanacc_id,DATE_FORMAT(am.payment_date,'%m') ORDER BY am.loanacc_id";
			$reshand = db_query($sql);
			$acc = 0;
			$printotal = 0;
			$inttotal = 0;
			$cur_dem_prin = 0;
			$cur_dem_int = 0;
			$overdue_prin = 0;
			$overdue_int = 0;
			$co = 0;
			while($a = db_fetch_object($reshand))
			{
				$co++;
				$cemi = $a->emi_amount;
				if(!$acc)
				{
					$acc = $a->loanacc_id;
				}
				for($s = 4; $s <= $month - 1; $s++)
				{
					if($a->pdate == $s)
					{
						 if($acc == $a->loanacc_id)
						 {
						 	$printotal += $a->totprin;
						 	$inttotal += $a->totint;
						 }else{
							if(($printotal + $inttotal) <= $cemi)
							{
								$cur_dem_prin += $printotal;
								$cur_dem_int += $inttotal;
							}else{
								$cur_dem_prin += $lemi;
								$cur_dem_int += $inttotal;
								$overdue_prin += abs($lemi - $printotal);
								$overdue_int += $inttotal;
							}
							 $acc = $a->loanacc_id;
							 $printotal = $a->totprin;
							 $inttotal = $a->totint;
						 }
					}
				}
				$lemi = $cemi;
				if($co == 1)
				{
					$cur_dem_prin = $printotal;
					$cur_dem_int = $inttotal;
					$overdue_prin = abs($lemi - $printotal);
					//echo $lemi."==".$overdue_prin;exit;
					$overdue_int = $inttotal;
				}
			}
			$totalcur_dem_prin += $cur_dem_prin;
			$totalcur_dem_int += $cur_dem_int;
			$totaloverdue_prin += $overdue_prin;
			$totaloverdue_int += $overdue_int;
			
			$total_rec1 = $overdue_prin + $overdue_int + $cur_dem_prin + $cur_dem_int;
			$output .= '<td  align="right" class="'.$cl.'">'.$overdue_prin.'</td><td  align="right" class="'.$cl.'">'.$overdue_int.'</td><td  align="right" class="'.$cl.'">'.$cur_dem_prin.'</td><td  align="right" class="'.$cl.'">'.$cur_dem_int.'</td>';
			
			$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS pdate, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '".$r->loan_scheme_id."' AND DATE_FORMAT(am.payment_date,'%Y-%m') = '".$_REQUEST['year'].'-'.$pmonth."' AND sm.loan_type = 148 GROUP BY am.loanacc_id,DATE_FORMAT(am.payment_date,'%m') ORDER BY am.loanacc_id";
			//echo $sql;exit;
			$reshand = db_query($sql);
			$acc = 0;
			$printotal = 0;
			$inttotal = 0;
			$cur_dem_prin = 0;
			$cur_dem_int = 0;
			$overdue_prin = 0;
			$overdue_int = 0;
			$co = 0;
			while($a = db_fetch_object($reshand))
			{
				$co++;
				$cemi = $a->emi_amount;
				if(!$acc)
				{
					$acc = $a->loanacc_id;
				}
				//echo $acc." == ".$a->loanacc_id."==".$a->totprin."<br>";
				 if($acc == $a->loanacc_id)
				 {
					$printotal += $a->totprin;
					$inttotal += $a->totint;
				 }else{
					if(($printotal + $inttotal) <= $cemi)
					{
						$cur_dem_prin += $printotal;
						$cur_dem_int += $inttotal;
					}else{
						$cur_dem_prin += $lemi;
						$cur_dem_int += $inttotal;
						$overdue_prin += abs($lemi - $printotal);
						//echo $lemi."==".$overdue_prin;exit;
						$overdue_int += $inttotal;
					}
					 $acc = $a->loanacc_id;
					 $printotal = $a->totprin;
					 $inttotal = $a->totint;
				 }
				$lemi = $cemi;
				 if($co == 1)
				 {
					$cur_dem_prin = $printotal;
					$cur_dem_int = $inttotal;
					$overdue_prin = abs($lemi - $printotal);
					//echo $lemi."==".$overdue_prin;exit;
					$overdue_int = $inttotal;
				 }
			}
			$dtotalcur_dem_prin += $cur_dem_prin;
			$dtotalcur_dem_int += $cur_dem_int;
			$dtotaloverdue_prin += $overdue_prin;
			$dtotaloverdue_int += $overdue_int;
			
			$total_rec2 = $overdue_prin + $overdue_int + $cur_dem_prin + $cur_dem_int;
			$total_rec = $total_rec2 + $total_rec1;
			$gtotal_rec += $total_rec;
			$gclosed_during_month += $closedacc1->closed;
			$gclosed_during_year += $closedacc->closed;
			$output .= '<td  align="right" class="'.$cl.'">'.$overdue_prin.'</td><td  align="right" class="'.$cl.'">'.$overdue_int.'</td><td  align="right" class="'.$cl.'">'.$cur_dem_prin.'</td><td  align="right" class="'.$cl.'">'.$cur_dem_int.'</td>';
			$output .= '<td  align="right" class="'.$cl.'">'.$total_rec.'</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">'.$closedacc1->closed.'</td><td  align="right" class="'.$cl.'">'.$closedacc->closed.'</td>';

			$output .= '</tr>';
		}
		$output .= '<tr><td  align="right" class="'.$cl.'">&nbsp;</td><td  align="right" class="'.$cl.'">Total</td><td  align="right" class="'.$cl.'">'.$grandtotal_accounts.'</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">'.$grandtotal_accounts.'</td>';
		$output .= '<td  align="right" class="'.$cl.'">'.$gfrandtotal_accounts.'</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">-</td>';
		$output .= '<td  align="right" class="'.$cl.'">'.$totaloverdue_prin.'</td><td  align="right" class="'.$cl.'">'.$totaloverdue_int.'</td><td  align="right" class="'.$cl.'">'.$totalcur_dem_prin.'</td><td  align="right" class="'.$cl.'">'.$totalcur_dem_int.'</td>';
		$output .= '<td  align="right" class="'.$cl.'">'.$dtotaloverdue_prin.'</td><td  align="right" class="'.$cl.'">'.$dtotaloverdue_int.'</td><td  align="right" class="'.$cl.'">'.$dtotalcur_dem_prin.'</td><td  align="right" class="'.$cl.'">'.$dtotalcur_dem_int.'</td>';
		$output .= '<td  align="right" class="'.$cl.'">'.$gtotal_rec.'</td><td  align="right" class="'.$cl.'">-</td><td  align="right" class="'.$cl.'">'.$gclosed_during_month.'</td><td  align="right" class="'.$cl.'">'.$gclosed_during_year.'</td></tr>';
		$output .= "</table>";
		$output .= "</table>";
	ob_end_clean();
$pdf->writeHTML($output, true, 0, true, true);
//Close and output PDF document
$pdf->Output('schemerecovery_'.time().'.pdf', 'I');
?>