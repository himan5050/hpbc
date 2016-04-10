<?php

function sub_schemewise_outstanding_recovery() {
    global $user;
    global $base_root;
    global $base_url;
    $array = explode('/', $_GET['q']);
    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = 'Recovery and Outstanding Position Report';
    drupal_set_breadcrumb($breadcrumb);
    $current_month = strftime("%B");
    $current_year = date("Y");
    $selectedmonth = date("m");
    $selectedyear = date("Y");
    if (isset($_POST['month']) && $_POST['year']) {
        $selectedmonth = ($_POST['month']) ? $_POST['month'] : $current_month;
        $selectedyear = ($_POST['year']) ? $_POST['year'] : $current_year;
    }
    //echo $selectedmonth."==".$selectedyear;exit;
    $output = <<<EOD
	          <div id="errorid" class="messages error" style="display:none;"></div>
              <div id="form-container">
		      <form action="" name="onetimesettlementform" method="post" enctype="multipart/form-data">
		      <table width="100%" cellpadding="2" cellspacing="1" border="0" id="onetimesettlement_container" style="border:0px;">
		      <tr>
			  <td align="left" class="tdform-width"><fieldset><legend>Recovery and Outstanding Position Report</legend> 
		      <table align="left" class="frmtbl">
		      <tr>
			  <td width="5%">&nbsp;</td> <td align="left">
			  <strong>Month : <span title="This field is required." class="form-required">*</span></strong>
			  </td>
			  <td>EOD;
	$output .= getMonthDropdown($selectedmonth);
	$output .= <<<EOD
			   </td>
			   <td align="left">
			   <strong>Year : <span title="This field is required." class="form-required">*</span></strong>
			   </td><td>EOD;
	$output .= getYearDropdown($current_year,$selectedyear);
	$output .= <<<EOD
			   </td>
		<td colspan="4" align="right">
		<input type="submit" class="form-submit" value="Generate" id="submit" name="ls"/></td> 
		<td width="5%">&nbsp;</td> </tr>
		</table></fieldset></td></tr></table>
        <br />
</form>
</div>
EOD;
    if (isset($_POST['month']) && $_POST['year']) {
        $ppmonth = ($_POST['month'] < 10) ? '0' . $_POST['month'] - 1 : $_POST['month'];
        $pmonth = ($_POST['month'] < 10) ? '0' . $_POST['month'] : $_POST['month'];
        if ($_POST['month'] >= 4) {
            $year = $_POST['year'];
            $begnningofyear = $_POST['year'] . "-04-01";
        } else {
            $year = $_POST['year'] - 1;
            $begnningofyear = ($_POST['year'] - 1) . "-04-01";
        }
        $datefrom = strtotime($year . "-" . $pmonth . "-01");
        $end_of_month = date("d", mktime(23, 59, 59, date('m', $datefrom) + 1, 00));
        $dateto = strtotime($_POST['year'] . "-" . $pmonth . "-" . $end_of_month);
        //echo $dateto;exit;
        $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";
        $pdfurl = $base_url . "/schemeoutstandingrecoverypdf.php?datefrom=$datefrom&dateto=$dateto&year=" . $_POST['year'] . "&month=" . $_POST['month'];
        $output .= '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%" id="form-container">
	<tr class="oddrow"><td align="left"><h2 style="text-align:left; font-size:17px;">Recovery And Outstanding Position Under Different Schemes Being Run By The Corporation For The Month of ' . date("F", strtotime($_POST['year'] . '-' . $pmonth . '-01')) . ' / ' . $_POST['year'] . '</b></tr>
	<tr>';
        $output .= '<td><a target="_blank" href="' . $pdfurl . '"><img style="float:right;" src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" /></a></td></table></div><div class="listingpage_scrolltable"><table><tr>';
        $output .= "<th rowspan='3'>Sr.No.</th><th rowspan='3'>Name of Scheme Office</th><th colspan='3'>Total No. of Running A/C's beginning as on</th><th colspan='3'>Outstanding /Openeing Balance as on " . date("d-m-Y", strtotime($begnningofyear)) . "</th><th colspan='3'>Over due</th><th rowspan='3'>Loan finaced during the year</th><th rowspan='3'>Total demand for the year</th><th colspan='4'>Total Amount recovered upto previous month</th><th colspan='4'>Total Amount recovered during the month</th><th rowspan='3'>Total Recovery</th><th rowspan='3'>%</th><th colspan='2'>No. of accounts Closed</th></tr>";

        $output .= "<tr><th rowspan='2'>Beginning of the year</th><th rowspan='2'>No. of Cases financed during the year</th><th rowspan='2'>Total</th><th colspan='3'>Beginning of the year/quarter</th><th colspan='3'>Beginning of the quarter as on.........</th><th colspan='2'>Against Overdue</th><th colspan='2'>Current Demand</th><th colspan='2'>Against Overdue</th><th colspan='2'>Current Demand</th><th rowspan='2'>During the month</th><th rowspan='2'>During the year upto the month</th></tr>";

        $output .= "<tr><th>Prin.</th><th>Intt.</th><th>LD</th><th>Prin.</th><th>Intt.</th><th>LD</th><th>Prin.</th><th>Intt.</th><th>Prin.</th><th>Intt.</th><th>Prin.</th><th>Intt.</th><th>Prin.</th><th>Intt.</th></tr>";
        $sql = "SELECT sm.loan_scheme_id,sm.scheme_name FROM tbl_scheme_master sm WHERE sm.status = 167 AND sm.loan_type = 148 ORDER BY sm.scheme_name";
        $res = db_query($sql);
        $output .= "<tr class='even'>";
        for ($i = 1; $i <= 25; $i++) {
            $output .= "<td>$i</td>";
        }
        $output .= "</tr>";
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

        while ($r = db_fetch_object($res)) {
            $counter++;
            //$lsql = "SELECT COUNT(ld.loan_id) as totalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.sanction_date < '".$begnningofyear."' AND l.alr_status = 0 AND sm.loan_type = 148 GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            $lsql = "SELECT COUNT(ld.loan_id) as totalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l WHERE ld.reg_number = l.reg_number AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.sanction_date < '" . $begnningofyear . "' AND l.alr_status = 0 AND ld.scheme_name = '" . $r->loan_scheme_id . "'  GROUP BY ld.scheme_name";
            //echo $lsql;exit;
            $tloan = db_query($lsql);
            $grandtotal_accounts += $tloan->totalaccounts;
            $fsql = "SELECT COUNT(ld.loan_id) as ftotalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l WHERE ld.reg_number = l.reg_number AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.loan_disburse_date < '" . $_POST['year'] . "-" . $pmonth . "-" . $end_of_month . "' AND l.alr_status = 0 AND ld.scheme_name = '" . $r->loan_scheme_id . "'  GROUP BY ld.scheme_name";
            //echo $lsql;exit;
            $floan = db_query($fsql);
            $gfrandtotal_accounts += $floan->ftotalaccounts;
            if ($counter % 2)
                $cl = 'odd';
            else
                $cl = 'even';
            $sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id  AND sm.loan_scheme_id = '" . $r->loan_scheme_id . "' AND ld.loan_status = 0 AND sm.loan_type = 148 AND ld.closed_date <=" . $_POST['year'] . '-' . $pmonth . "-" . $end_of_month;
            //echo $sql;exit;
            $result = db_query($sql);
            $closedacc = db_fetch_object($result);
            $sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id  AND sm.loan_scheme_id = '" . $r->loan_scheme_id . "' AND ld.loan_status = 0 AND sm.loan_type = 148 AND DATE_FORMAT(ld.closed_date,'%Y-%m') =" . $_POST['year'] . '-' . $pmonth;
            $result = db_query($sql);
            $closedacc1 = db_fetch_object($result);
            $output .= "<tr class='" . $cl . "'>";
            $output .= "<td>$counter</td><td>" . $r->scheme_name . "</td><td align='right'>" . $r->totalaccounts . "</td>";
            $output .= "<td>" . $floan->ftotalaccounts . "</td>"; //Cases Financed During the year
            $output .= "<td align='right'>" . $r->totalaccounts . "</td>";
            $output .= "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";

            //$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '".$r->loan_scheme_id."' AND sm.loan_type = 148 AND  am.payment_date < '".$_POST['year'].'-'.$pmonth."-01' GROUP BY am.loanacc_id,DATE_FORMAT(am.payment_date,'%m') ORDER BY am.loanacc_id";

            $pemisql = "SELECT sm.scheme_name, SUM( principal ) toprin, SUM( interest ) toint, period_diff('" . $_POST['year'] . $ppmonth . "', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) AS month FROM tbl_loan_detail ld,tbl_scheme_master sm,tbl_loan_emi_schedule les WHERE ld.scheme_name = sm.loan_scheme_id AND les.year = '" . $year . "' AND les.month < period_diff('" . $_POST['year'] . $ppmonth . "', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) + 1 AND ld.scheme_name = '" . $r->loan_scheme_id . "' AND sm.loan_type = 148 AND ld.loan_disburse_date <= '" . $_POST['year'] . '-' . $ppmonth . '-' . $end_of_month . "' GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            $pemires = db_query($pemisql);
            $pemitot = db_fetch_object($pemires);


            $psql = "SELECT sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_scheme_master sm WHERE ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '" . $r->loan_scheme_id . "' AND sm.loan_type = 148 AND  am.payment_date < '" . $_POST['year'] . '-' . $ppmonth . "-01' GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            $preshand = db_query($psql);
            $pa = db_fetch_object($preshand);


            $pcur_dem_prin = (($pemitot->toprin - $pa->totprin) > 0) ? ($pemitot->toprin - $pa->totprin) : 0;
            $pcur_dem_int = (($pemitot->toint - $pa->totint) > 0) ? ($pemitot->toint - $pa->totint) : 0;



            //$emisql = "SELECT sm.scheme_name, SUM( principal ) toprin, SUM( interest ) toint, period_diff('".$_POST['year'].$pmonth."', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) AS month FROM tbl_loan_detail ld,tbl_scheme_master sm,tbl_loan_emi_schedule les WHERE ld.scheme_name = sm.loan_scheme_id AND les.month < period_diff('".$_POST['year'].$pmonth."', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) + 1 AND ld.scheme_name = '".$r->loan_scheme_id."' AND sm.loan_type = 148 AND ld.loan_disburse_date <= '".$_POST['year'].'-'.$pmonth.'-'.$end_of_month."' GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            //$emires = db_query($emisql);
            //$emitot = db_fetch_object($emires);

            $sql = "SELECT sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_scheme_master sm WHERE ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '" . $r->loan_scheme_id . "' AND sm.loan_type = 148 AND  am.payment_date < '" . $_POST['year'] . '-' . $pmonth . "-01' GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
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
            /* while($a = db_fetch_object($reshand))
              {
              $co++;
              $cemi = $a->emi_amount;
              if(!$acc)
              {
              $acc = $a->loanacc_id;
              }
              for($s = 4; $s <= $_POST['month'] - 1; $s++)
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
              } */
            $cur_dem_prin = (($a->totprin - $pcur_dem_prin) > 0) ? ($a->totprin - $pcur_dem_prin) : 0;
            $cur_dem_int = (($a->totint - $pcur_dem_int) > 0) ? ($a->totint - $pcur_dem_int) : 0;
            $overdue_prin = (($a->totprin - $pcur_dem_prin) > 0) ? $pcur_dem_prin : $a->totprin;
            $overdue_int = (($a->totint - $pcur_dem_int) > 0) ? $pcur_dem_int : $a->totint;

            $totalcur_dem_prin += $cur_dem_prin;
            $totalcur_dem_int += $cur_dem_int;
            $totaloverdue_prin += $overdue_prin;
            $totaloverdue_int += $overdue_int;


            $total_rec1 = $overdue_prin + $overdue_int + $cur_dem_prin + $cur_dem_int;
            $output .= "<td align='right'>" . round($overdue_prin) . "</td><td align='right'>" . round($overdue_int) . "</td><td align='right'>" . round($cur_dem_prin) . "</td><td align='right'>" . round($cur_dem_int) . "</td>";

            //$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS pdate, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '".$r->loan_scheme_id."' AND DATE_FORMAT(am.payment_date,'%Y-%m') = '".$_POST['year'].'-'.$pmonth."' AND sm.loan_type = 148 GROUP BY am.loanacc_id,DATE_FORMAT(am.payment_date,'%m') ORDER BY am.loanacc_id";
            //$emisql = "SELECT sm.scheme_name, SUM( principal ) toprin, SUM( interest ) toint, period_diff('".$_POST['year'].$pmonth."', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) + 1 AS month FROM tbl_loan_detail ld,tbl_scheme_master sm,tbl_loan_emi_schedule les WHERE ld.scheme_name = sm.loan_scheme_id AND les.month = period_diff('".$_POST['year'].$pmonth."', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) + 1 AND ld.scheme_name = '".$r->loan_scheme_id."' AND sm.loan_type = 148 AND ld.loan_disburse_date <= '".$_POST['year'].'-'.$pmonth.'-'.$end_of_month."'  GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            //$emires = db_query($emisql);
            //$emitot = db_fetch_object($emires);


            $sql = "SELECT sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_scheme_master sm WHERE ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '" . $r->loan_scheme_id . "' AND sm.loan_type = 148 AND  DATE_FORMAT(am.payment_date,'%Y-%m') = '" . $_POST['year'] . '-' . $pmonth . "' GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            $reshand = db_query($sql);
            $a = db_fetch_object($reshand);
            if ($counter == 3) {
                //echo $emisql."<br>";
                //echo $sql;exit;
            }
            //echo $sql;exit;
            $reshand = db_query($sql);
            $acc = 0;
            $printotal = 0;
            $inttotal = 0;
            $ccur_dem_prin = 0;
            $ccur_dem_int = 0;
            $coverdue_prin = 0;
            $coverdue_int = 0;
            $co = 0;
            /* while($a = db_fetch_object($reshand))
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
              if($lemi >= $printotal)
              $overdue_prin += abs($lemi - $printotal);
              else
              $overdue_prin += 0;
              //$overdue_prin += abs($lemi - $printotal);
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
              if($lemi >= $printotal)
              $overdue_prin = abs($lemi - $printotal);
              else
              $overdue_prin = 0;
              //echo $lemi."==".$overdue_prin;exit;
              $overdue_int = $inttotal;
              }
              }
              //echo $cur_dem_prin;exit; */

            $coverdue_prin = (($a->totprin - $overdue_prin) > 0) ? $overdue_prin : $a->totprin;
            $coverdue_int = (($a->totint - $overdue_int) > 0) ? $overdue_int : $a->totint;
            $ccur_dem_prin = (($a->totprin - $overdue_prin) > 0) ? ($a->totprin - $overdue_prin) : '-';
            $ccur_dem_int = (($a->totint - $overdue_int) > 0) ? ($a->totint - $overdue_int) : '-';

            $dtotalcur_dem_prin += $cur_dem_prin;
            $dtotalcur_dem_int += $cur_dem_int;
            $dtotaloverdue_prin += $overdue_prin;
            $dtotaloverdue_int += $overdue_int;

            $total_rec2 = $coverdue_prin + $coverdue_int + $ccur_dem_prin + $ccur_dem_int;
            $total_rec = $total_rec2 + $total_rec1;
            $gtotal_rec += $total_rec;
            $gclosed_during_month += $closedacc1->closed;
            $gclosed_during_year += $closedacc->closed;
            $output .= "<td align='right'>" . round($coverdue_prin) . "</td><td align='right'>" . round($coverdue_int) . "</td><td align='right'>" . round($ccur_dem_prin) . "</td><td align='right'>" . round($ccur_dem_int) . "</td>";
            $output .= "<td align='right'>" . round($total_rec) . "</td><td align='right'>-</td><td align='right'>" . $closedacc1->closed . "</td><td align='right'>" . $closedacc->closed . "</td>";

            $output .= "</tr>";
        }
        $output .= "<tr class='oddrow'><td>&nbsp;</td><td>Total</td><td align='right'>$grandtotal_accounts</td><td>$gfrandtotal_accounts</td><td align='right'>$grandtotal_accounts</td>";
        $output .= "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";
        $output .= "<td align='right'>" . round($totaloverdue_prin) . "</td><td align='right'>" . round($totaloverdue_int) . "</td><td align='right'>" . round($totalcur_dem_prin) . "</td><td align='right'>" . round($totalcur_dem_int) . "</td>";
        $output .= "<td align='right'>" . round($dtotaloverdue_prin) . "</td><td align='right'>" . round($dtotaloverdue_int) . "</td><td align='right'>" . round($dtotalcur_dem_prin) . "</td><td align='right'>" . round($dtotalcur_dem_int) . "</td>";
        $output .= "<td align='right'>" . round($gtotal_rec) . "</td><td align='right'>-</td><td align='right'>$gclosed_during_month</td><td align='right'>$gclosed_during_year</td></tr>";
        $output .= "</table></div>";
        //return $output;
    }
    return $output;
}

function schemewise_outstanding_recovery() {


    global $user;
    global $base_root;
    global $base_url;

    $array = explode('/', $_GET['q']);

    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = 'Recovery and Outstanding Position Report';
    drupal_set_breadcrumb($breadcrumb);
    $current_month = strftime("%B");
    $current_year = date("Y");
    $selectedmonth = date("m");
    $selectedyear = date("Y");
    if (isset($_POST['month']) && $_POST['year']) {
        $selectedmonth = ($_POST['month']) ? $_POST['month'] : $current_month;
        $selectedyear = ($_POST['year']) ? $_POST['year'] : $current_year;
    }
    //echo $selectedmonth."==".$selectedyear;exit;
    $output = <<<EOD
	<div id="errorid" class="messages error" style="display:none;"></div>

	<div id="form-container">
		<form action="" name="onetimesettlementform" method="post" enctype="multipart/form-data">
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="onetimesettlement_container" style="border:0px;">
		<tr>
			<td align="left" class="tdform-width"><fieldset><legend>Recovery and Outstanding Position Report</legend> 
		 <table align="left" class="frmtbl">
		<tr>
			<td width="5%">&nbsp;</td> <td align="left"><strong>Month : <span title="This field is required." class="form-required">*</span></strong></td><td>
EOD;
    $output .= getMonthDropdown($selectedmonth);
    $output .= <<<EOD
			</td>
			<td align="left"><strong>Year : <span title="This field is required." class="form-required">*</span></strong></td><td>
EOD;
    $output .= getYearDropdown($current_year, $selectedyear);
    $output .= <<<EOD
			</td>
		<td colspan="4" align="right"><input type="submit" class="form-submit" value="Generate" id="submit" name="ls"/></td> <td width="5%">&nbsp;</td> </tr>
		</table></fieldset></td></tr></table>
        <br />
</form>
</div>
EOD;
    if (isset($_POST['month']) && $_POST['year']) {
        $ppmonth = ($_POST['month'] < 10) ? '0' . $_POST['month'] - 1 : $_POST['month'];
        $pmonth = ($_POST['month'] < 10) ? '0' . $_POST['month'] : $_POST['month'];
        if ($_POST['month'] >= 4) {
            $year = $_POST['year'];
            $begnningofyear = $_POST['year'] . "-04-01";
        } else {
            $year = $_POST['year'] - 1;
            $begnningofyear = ($_POST['year'] - 1) . "-04-01";
        }
        $datefrom = strtotime($year . "-" . $pmonth . "-01");
        $end_of_month = date("d", mktime(23, 59, 59, date('m', $datefrom) + 1, 00));
        $dateto = strtotime($_POST['year'] . "-" . $pmonth . "-" . $end_of_month);
        //echo $dateto;exit;
        $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";
        $pdfurl = $base_url . "/mainschemewiseoutstandingreportpdf.php?datefrom=$datefrom&dateto=$dateto&year=" . $_POST['year'] . "&month=" . $_POST['month'];
        $output .= '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%" id="form-container">
	<tr class="oddrow"><td align="left"><h2 style="text-align:left; font-size:17px;">Recovery And Outstanding Position Under Different Schemes Being Run By The Corporation For The Month of ' . date("F", strtotime($_POST['year'] . '-' . $pmonth . '-01')) . ' / ' . $_POST['year'] . '</b></tr>
	<tr>';
        $output .= '<td><a target="_blank" href="' . $pdfurl . '"><img style="float:right;" src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" /></a></td></table></div><div class="listingpage_scrolltable"><table><tr>';
        $output .= "<th rowspan='3'>Sr.No.</th><th rowspan='3'>Name of Scheme Office</th><th colspan='3'>Total No. of Running A/C's beginning as on</th><th colspan='3'>Outstanding /Openeing Balance as on " . date("d-m-Y", strtotime($begnningofyear)) . "</th><th colspan='3'>Over due</th><th rowspan='3'>Loan finaced during the year</th><th rowspan='3'>Total demand for the year</th><th colspan='4'>Total Amount recovered upto previous month</th><th colspan='4'>Total Amount recovered during the month</th><th rowspan='3'>Total Recovery</th><th rowspan='3'>%</th><th colspan='2'>No. of accounts Closed</th></tr>";

        $output .= "<tr><th rowspan='2'>Beginning of the year</th><th rowspan='2'>No. of Cases financed during the year</th><th rowspan='2'>Total</th><th colspan='3'>Beginning of the year/quarter</th><th colspan='3'>Beginning of the quarter as on.........</th><th colspan='2'>Against Overdue</th><th colspan='2'>Current Demand</th><th colspan='2'>Against Overdue</th><th colspan='2'>Current Demand</th><th rowspan='2'>During the month</th><th rowspan='2'>During the year upto the month</th></tr>";

        $output .= "<tr><th>Prin.</th><th>Intt.</th><th>LD</th><th>Prin.</th><th>Intt.</th><th>LD</th><th>Prin.</th><th>Intt.</th><th>Prin.</th><th>Intt.</th><th>Prin.</th><th>Intt.</th><th>Prin.</th><th>Intt.</th></tr>";
        //$sql = "SELECT sm.loan_scheme_id,sm.scheme_name FROM tbl_scheme_master sm WHERE sm.status = 167 AND sm.loan_type = 148 ORDER BY sm.scheme_name";
        $sql = "SELECT s.schemeName_id,s.schemeName_name as scheme_name FROM tbl_schemenames s ORDER BY s.schemeName_name";
        $res = db_query($sql);
        $output .= "<tr class='even'>";
        for ($i = 1; $i <= 25; $i++) {
            $output .= "<td>$i</td>";
        }
        $output .= "</tr>";
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

        while ($r = db_fetch_object($res)) {
            $counter++;
            //$lsql = "SELECT COUNT(ld.loan_id) as totalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.sanction_date < '".$begnningofyear."' AND l.alr_status = 0 AND sm.loan_type = 148 GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            $lsql = "SELECT COUNT(ld.loan_id) as totalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_schemenames s,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND s.schemeName_id = sm.main_scheme AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND sm.loan_type = 148 AND ld.payment_order_released_date != '0000-00-00' AND ld.sanction_date < '" . $begnningofyear . "' AND  ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = '" . $r->schemeName_id . "'  GROUP BY s.schemeName_name";

            //$lsql = "SELECT COUNT(ld.loan_id) as totalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l WHERE ld.reg_number = l.reg_number AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.sanction_date < '".$begnningofyear."' AND l.alr_status = 0 AND ld.scheme_name = '".$r->loan_scheme_id."'  GROUP BY ld.scheme_name";
            //echo $lsql.'<br><br>';
            $tloan = db_query($lsql);
            $runingacc = db_fetch_object($tloan);
            $grandtotal_accounts += $runingacc->totalaccounts;
            $fsql = "SELECT COUNT(ld.loan_id) as ftotalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_schemenames s,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND s.schemeName_id = sm.main_scheme AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.loan_disburse_date < '" . $_POST['year'] . "-" . $pmonth . "-" . $end_of_month . "' AND ld.loan_disburse_date >= '" . $begnningofyear . "' AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = '" . $r->schemeName_id . "'  GROUP BY s.schemeName_name";

            //$fsql = "SELECT COUNT(ld.loan_id) as ftotalaccounts FROM tbl_loan_detail ld,tbl_loanee_detail l WHERE ld.reg_number = l.reg_number AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND ld.loan_disburse_date < '".$_POST['year']."-".$pmonth."-".$end_of_month."' AND l.alr_status = 0 AND ld.scheme_name = '".$r->loan_scheme_id."'  GROUP BY ld.scheme_name";
            //echo $fsql.'<br><br>';
            $floan = db_query($fsql);
            $facc = db_fetch_object($floan);
            $gfrandtotal_accounts += $facc->ftotalaccounts;
            $totacc += ($runingacc->totalaccounts + $facc->ftotalaccounts);
            if ($counter % 2)
                $cl = 'odd';
            else
                $cl = 'even';
            $sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND s.schemeName_id = '" . $r->schemeName_id . "' AND ld.loan_status = 0 AND ld.closed_date != '0000-00-00' AND sm.loan_type = 148 AND ld.closed_date <='" . $_POST['year'] . '-' . $pmonth . "-" . $end_of_month . "'";

            //$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id  AND sm.loan_scheme_id = '".$r->loan_scheme_id."' AND ld.loan_status = 0 AND sm.loan_type = 148 AND ld.closed_date <=".$_POST['year'].'-'.$pmonth."-".$end_of_month;
            //echo $sql;exit;
            $result = db_query($sql);
            $closedacc = db_fetch_object($result);
            $sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND s.schemeName_id = '" . $r->schemeName_id . "' AND ld.loan_status = 0 AND ld.closed_date != '0000-00-00' AND sm.loan_type = 148 AND DATE_FORMAT(ld.closed_date,'%Y-%m') ='" . $_POST['year'] . '-' . $pmonth . "'";
            //$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id  AND sm.loan_scheme_id = '".$r->loan_scheme_id."' AND ld.loan_status = 0 AND sm.loan_type = 148 AND DATE_FORMAT(ld.closed_date,'%Y-%m') =".$_POST['year'].'-'.$pmonth;
            $result = db_query($sql);
            $closedacc1 = db_fetch_object($result);
            $output .= "<tr class='" . $cl . "'>";
            $output .= "<td>$counter</td><td>" . $r->scheme_name . "</td><td align='right'>" . $runingacc->totalaccounts . "</td>";
            $output .= "<td>" . $facc->ftotalaccounts . "</td>"; //Cases Financed During the year
            $output .= "<td align='right'>" . ($runingacc->totalaccounts + $facc->ftotalaccounts) . "</td>";
            $output .= "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";

            //$sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '".$r->loan_scheme_id."' AND sm.loan_type = 148 AND  am.payment_date < '".$_POST['year'].'-'.$pmonth."-01' GROUP BY am.loanacc_id,DATE_FORMAT(am.payment_date,'%m') ORDER BY am.loanacc_id";

            $pemisql = "SELECT SUM( principal ) toprin, SUM( interest ) toint, period_diff('" . $_POST['year'] . $ppmonth . "', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) AS month FROM tbl_loan_detail ld,tbl_scheme_master sm,tbl_loan_emi_schedule les,tbl_schemenames s WHERE ld.scheme_name = sm.loan_scheme_id  AND s.schemeName_id = sm.main_scheme AND les.year = '" . $_POST['year'] . "' AND les.month < period_diff('" . $_POST['year'] . $ppmonth . "', DATE_FORMAT(ld.loan_disburse_date, '%Y%m') ) + 1 AND s.schemeName_id = '" . $r->schemeName_id . "' AND sm.loan_type = 148 AND ld.loan_disburse_date <= '" . $_POST['year'] . '-' . $ppmonth . '-' . $end_of_month . "' GROUP BY s.schemeName_id ORDER BY s.schemeName_id";
            $pemires = db_query($pemisql);
            $pemitot = db_fetch_object($pemires);


            $psql = "SELECT sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_scheme_master sm,tbl_schemenames s WHERE ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND am.loan_id = ld.loan_id AND s.schemeName_id = '" . $r->schemeName_id . "' AND sm.loan_type = 148 AND  am.payment_date < '" . $_POST['year'] . '-' . $ppmonth . "-01' GROUP BY s.schemeName_id ";
            $preshand = db_query($psql);
            $pa = db_fetch_object($preshand);


            $pcur_dem_prin = (($pemitot->toprin - $pa->totprin) > 0) ? ($pemitot->toprin - $pa->totprin) : 0;
            $pcur_dem_int = (($pemitot->toint - $pa->totint) > 0) ? ($pemitot->toint - $pa->totint) : 0;

            $sql = "SELECT am.loanacc_id,sm.scheme_name,sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND am.loan_id = ld.loan_id AND s.schemeName_id = '" . $r->schemeName_id . "' AND sm.loan_type = 148 AND  am.payment_date < '" . $_POST['year'] . '-' . $pmonth . "-01' GROUP BY s.schemeName_id";
            if ($counter == 2) {
                //echo $emisql."<br>";
                //echo $sql;exit;
            }
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
            $cur_dem_prin = (($a->totprin - $pcur_dem_prin) > 0) ? ($a->totprin - $pcur_dem_prin) : 0;
            $cur_dem_int = (($a->totint - $pcur_dem_int) > 0) ? ($a->totint - $pcur_dem_int) : 0;
            $overdue_prin = (($a->totprin - $pcur_dem_prin) > 0) ? $pcur_dem_prin : $a->totprin;
            $overdue_int = (($a->totint - $pcur_dem_int) > 0) ? $pcur_dem_int : $a->totint;

            $totalcur_dem_prin += $cur_dem_prin;
            $totalcur_dem_int += $cur_dem_int;
            $totaloverdue_prin += $overdue_prin;
            $totaloverdue_int += $overdue_int;


            $total_rec1 = $overdue_prin + $overdue_int + $cur_dem_prin + $cur_dem_int;
            $output .= "<td align='right'>" . round($overdue_prin) . "</td><td align='right'>" . round($overdue_int) . "</td><td align='right'>" . round($cur_dem_prin) . "</td><td align='right'>" . round($cur_dem_int) . "</td>";


            $sql = "SELECT sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS pdate, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_schemenames s WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND s.schemeName_id = sm.main_scheme AND am.loan_id = ld.loan_id AND s.schemeName_id = '" . $r->schemeName_id . "' AND DATE_FORMAT(am.payment_date,'%Y-%m') = '" . $_POST['year'] . '-' . $pmonth . "' AND sm.loan_type = 148 GROUP BY s.schemeName_id";
            //$sql = "SELECT sm.scheme_name, SUM( principal_paid ) totprin, SUM( interest_paid ) totint, DATE_FORMAT( am.payment_date, '%m' ) AS date, ld.loan_id, ld.emi_amount FROM tbl_loan_amortisaton am, tbl_loan_detail ld,tbl_scheme_master sm WHERE ld.scheme_name = sm.loan_scheme_id AND am.loan_id = ld.loan_id AND ld.scheme_name = '".$r->loan_scheme_id."' AND sm.loan_type = 148 AND  DATE_FORMAT(am.payment_date,'%Y-%m') = '".$_POST['year'].'-'.$pmonth."' GROUP BY ld.scheme_name ORDER BY ld.scheme_name";
            $reshand = db_query($sql);
            $a = db_fetch_object($reshand);
            if ($counter == 3) {
                //echo $emisql."<br>";
                //echo $sql;exit;
            }
            //echo $sql;exit;
            $reshand = db_query($sql);
            $acc = 0;
            $printotal = 0;
            $inttotal = 0;
            $ccur_dem_prin = 0;
            $ccur_dem_int = 0;
            $coverdue_prin = 0;
            $coverdue_int = 0;
            $co = 0;

            $coverdue_prin = (($a->totprin - $overdue_prin) > 0) ? $overdue_prin : $a->totprin;
            $coverdue_int = (($a->totint - $overdue_int) > 0) ? $overdue_int : $a->totint;
            $ccur_dem_prin = (($a->totprin - $overdue_prin) > 0) ? ($a->totprin - $overdue_prin) : '-';
            $ccur_dem_int = (($a->totint - $overdue_int) > 0) ? ($a->totint - $overdue_int) : '-';

            $dtotalcur_dem_prin += $ccur_dem_prin;
            $dtotalcur_dem_int += $ccur_dem_int;
            $dtotaloverdue_prin += $coverdue_prin;
            $dtotaloverdue_int += $coverdue_int;

            $total_rec2 = $coverdue_prin + $coverdue_int + $ccur_dem_prin + $ccur_dem_int;
            $total_rec = $total_rec2 + $total_rec1;
            $gtotal_rec += $total_rec;
            $gclosed_during_month += $closedacc1->closed;
            $gclosed_during_year += $closedacc->closed;
            $output .= "<td align='right'>" . round(abs($coverdue_prin)) . "</td><td align='right'>" . round($coverdue_int) . "</td><td align='right'>" . round($ccur_dem_prin) . "</td><td align='right'>" . round($ccur_dem_int) . "</td>";
            $output .= "<td align='right'>" . round($total_rec) . "</td><td align='right'>-</td><td align='right'>" . $closedacc1->closed . "</td><td align='right'>" . $closedacc->closed . "</td>";

            $output .= "</tr>";
        }
        $output .= "<tr class='oddrow'><td>&nbsp;</td><td>Total</td><td align='right'>$grandtotal_accounts</td><td>$gfrandtotal_accounts</td><td align='right'>$totacc</td>";
        $output .= "<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>";
        $output .= "<td align='right'>" . round($totaloverdue_prin) . "</td><td align='right'>" . round($totaloverdue_int) . "</td><td align='right'>" . round($totalcur_dem_prin) . "</td><td align='right'>" . round($totalcur_dem_int) . "</td>";
        $output .= "<td align='right'>" . round($dtotaloverdue_prin) . "</td><td align='right'>" . round($dtotaloverdue_int) . "</td><td align='right'>" . round($dtotalcur_dem_prin) . "</td><td align='right'>" . round($dtotalcur_dem_int) . "</td>";
        $output .= "<td align='right'>" . round($gtotal_rec) . "</td><td align='right'>-</td><td align='right'>$gclosed_during_month</td><td align='right'>$gclosed_during_year</td></tr>";
        $output .= "</table></div>";
        //return $output;
    }
    return $output;
}

function quarterly_progress() {
    global $user;
    global $base_root;
    global $base_url;

    $array = explode('/', $_GET['q']);

    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = 'Quarterly Progress Report';
    drupal_set_breadcrumb($breadcrumb);
    $scriptcss = '';
    $output = '';
    if (isset($_POST['district'])) {
        if (!$_POST['district']) {
            if (isEmpty('district', $_POST['district'], 'District'))
                $scriptcss .= '$("#districtid").addClass("error");';
        }else {
            $selecteddistrict = $_POST['district'];
        }
    }

    if ($scriptcss) {
        $output .= <<<EOD
	<script>
	$(function() {
EOD;
        $output .= $scriptcss;
        $output .= <<<EOD
	});
	</script>
EOD;
    }

    $output .= <<<EOD
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">
		<form action="" name="onetimesettlementform" method="post" enctype="multipart/form-data">
		<table width="100%" style="border:none;" id="onetimesettlement_container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Quarterly Progress Report</legend> 
	 <table align="left" class="frmtbl">
  <tr>
	<td width="5%">&nbsp;</td><td align="left" class="tdform-width"><b>District : <span title="This field is required." class="form-required">*</span></b></td><td>
				<select name='district' id="districtid">
				<option value=''>Select</option>
EOD;
    $output .= getDistrictDropdown($selecteddistrict);
    $output .= <<<EOD
				</select>
			</td><td><input type="submit" class="form-submit" value="Generate" id="submit" name="ls"/></td>
		</tr>
		  </table></fieldset>
  </td>
    </tr>
  </table>
        <br /><br />
        
</form>
</div>
EOD;

    if (isset($_POST['district']) && $_POST['district']) {
        $district = $_POST['district'];
        if (date("m") >= 4) {
            $current_quarter = '01-04-' . date("Y");
            $lastquarter = '31-03-' . date("Y");
            $qlastdate = '30-06-' . date("Y");
            $year = date("Y");
        } else {
            $current_quarter = date("d-m-Y", mktime(0, 0, 0, 4, 1, date("Y") - 1));
            $lastquarter = date("d-m-Y", mktime(0, 0, 0, 3, 31, date("Y") - 1));
            $qlastdate = date("d-m-Y", mktime(0, 0, 0, 6, 30, date("Y") - 1));
            $year = date("Y") - 1;
        }
        //echo $qlastdate."==".databaseDateFormat($qlastdate,'indian','-');exit;
        $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";
        $pdfurl = $base_url . "/quarterlyprogresspdf.php?district=$district";
        $output .= '<div class="listingpage_scrolltable"><table cellpadding="0" cellspacing="2" border="0" width="100%" id="wrapper1">
	<tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">Quarterly Progress Report</h2></td></tr><tr>
	<td colspan="11" style="text-align:right;"><a target="_blank" href="' . $pdfurl . '"><img style="float:right;" src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" /></a></td></tr></table></div><div class="listingpage_scrolltable"><table><tr>';
        $output .= "<th>S.No.</th><th>Name of Loanee</th><th>Name of Scheme</th><th>Total Amount Financed</th><th colspan='4'>Total Outstanding  at the begining of the quarter as on $current_quarter</th><th>Loan financed during the year</th><th colspan='3'>Over due last quarter as on $lastquarter</th><th colspan='3'>Current demand during the quarter w.e.f $current_quarter to $qlastdate</th><th>Total demand</th><th colspan='4'>Recovery during the quarter</th><th colspan='4'>Over due end of the quarter as on $qlastdate</th><th colspan='4'>Total Outstanding at the end of quarter as on $qlastdate</th><th>Vol. Rec excess on demand</th></tr>";

        $output .= "<tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>Prin.</th><th>Intt.</th><th>LD</th><th>Total</td><th>&nbsp;</td><th>Prin.</th><th>Intt.</th><th>LD</th><th>Prin.</th><th>Intt.</th><th>LD</th><th>&nbsp;</th><th>Prin.</th><th>Intt.</th><th>LD</th><th>Total</td><th>Prin.</th><th>Intt.</th><th>LD</th><th>Total</td><th>Prin.</th><th>Intt.</th><th>LD</th><th>Total</td><th>&nbsp;</th></tr>";

        $output .= '<tr class="odd">';
        for ($i = 1; $i <= 29; $i++) {
            $output .= "<td>$i</td>";
        }
        $output .= "</tr>";

        $sql = "SELECT ld.loan_id,l.account_id,ld.emi_amount,ld.loan_disburse_date,ld.o_principal,ld.o_interest,ld.o_LD,l.fname,l.lname,sm.loan_scheme_id,sm.scheme_name as scheme,ld.loan_requirement FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.loan_status != 0 AND ld.loan_status != 9 AND ld.loan_status != 15 AND ld.loan_status != 17 AND ld.payment_order_released_date != '0000-00-00' AND l.district = $district AND sm.loan_type = 148 AND l.account_id ORDER BY l.lname";
        //echo $sql;exit;
        $res = db_query($sql);
        $counter = 0;
        while ($r = db_fetch_object($res)) {
            if ($counter % 2)
                $cl = 'odd';
            else
                $cl = 'even';
            $counter++;
            $daydiff = dateDiffByDays($r->loan_disburse_date, $current_quarter) / 30;
            //OUTSTANDING INTEREST AND PRINCIPAL AT BEGINING OF QUARTER
            $amortq = "SELECT SUM(principal) as outprin,SUM(interest) outint FROM tbl_loan_emi_schedule WHERE loan_id = '" . $r->loan_id . "' AND month < $daydiff  AND year = '" . $year . "' GROUP BY loan_id";
            $amres = db_query($amortq);
            $oamt = db_fetch_object($amres);

            //OUTSTANDING LD AT BEGINING OF QUARTER
            $ldq = "SELECT SUM(amount) as outld FROM tbl_loan_interestld WHERE account_id = '" . $r->account_id . "' AND calculation_date < '" . databaseDateFormat($current_quarter, 'indian', '-') . "' AND type = 'LD' GROUP BY account_id";
            $ldres = db_query($amortq);
            $oldamt = db_fetch_object($ldres);
            $ctotaloutstanding_amt = $oamt->outprin + $oamt->outint + $oldamt->outld;

            $out_beg_of_quarter_prin = $oamt->outprin;
            $out_beg_of_quarter_int = $oamt->outint;
            $out_beg_of_quarter_ld = $oldamt->outld;

            $output .= '<tr class="' . $cl . '">';
            $output .= '<td>' . $counter . '</td><td>' . ucwords($r->fname . ' ' . $r->lname) . '</td><td>' . ucwords($r->scheme) . '</td><td align="right">' . $r->loan_requirement . '</td>';

            $output .= '<td align="right">' . round($oamt->outprin) . '</td><td align="right">' . round($oamt->outint) . '</td><td align="right">' . round($oldamt->outld) . '</td><td>' . round($ctotaloutstanding_amt) . '</td><td>-</td>';
            //OVERDUE  AT LAST QUARTER
            $overq = "SELECT SUM(principal_paid) as prin,SUM(interest_paid) as interest,SUM(LD_paid) as ld FROM tbl_loan_amortisaton WHERE loanacc_id = '" . $r->account_id . "' AND payment_date < '" . databaseDateFormat($current_quarter, 'indian', '-') . "' GROUP BY loanacc_id";
            $overres = db_query($overq);
            $overamt = db_fetch_object($overres);

            $overdueprin = $oamt->outprin - $overamt->prin;
            $overdueint = $oamt->outint - $overamt->interest;
            $overdueld = $oldamt->outld - $overamt->ld;
            //$overduetotal = $overdueprin + $overdueint + $overdueld ;
            $output .= '<td align="right">' . round($overdueprin) . '</td><td align="right">' . round($overdueint) . '</td><td align="right">' . round($overdueld) . '</td>';

            //CURRENT DEMAND PRINCIPAL, INTEREST DURING THE QUARTER
            $amortq = "SELECT SUM(principal) as outprin,SUM(interest) outint FROM tbl_loan_emi_schedule WHERE loan_id = '" . $r->loan_id . "' AND month >= 4 AND month <= 6 AND year = '" . $year . "' GROUP BY loan_id";
            $amres = db_query($amortq);
            $cdemand = db_fetch_object($amres);

            //CURRENT DEMAND LD DURING THE QUARTER
            $ldq = "SELECT SUM(amount) as outld FROM tbl_loan_interestld WHERE account_id = '" . $r->account_id . "' AND calculation_date >= '" . databaseDateFormat($current_quarter, 'indian', '-') . "' AND calculation_date <= '" . databaseDateFormat($qlastdate, 'indian', '-') . "' GROUP BY account_id";
            $ldres = db_query($ldq);
            $oldamt = db_fetch_object($ldres);

            $cur_demand_prin = $cdemand->outprin;
            $cur_demand_int = $cdemand->outint;
            $cur_demand_ld = $oldamt->outld;

            $totaldemand_amt = $cdemand->outprin + $cdemand->outint + $oldamt->outld + $overdueprin + $overdueint + $overdueld;
            $output .= '<td align="right">' . round($cdemand->outprin) . '</td><td align="right">' . round($cdemand->outint) . '</td><td align="right">' . round($oldamt->outld) . '</td><td align="right">' . round($totaldemand_amt) . '</td>';

            $recovq = "SELECT SUM(principal_paid) as prin,SUM(interest_paid) as interest,SUM(LD_paid) as ld FROM tbl_loan_amortisaton WHERE loanacc_id = '" . $r->account_id . "' AND payment_date >= '" . databaseDateFormat($current_quarter, 'indian', '-') . "' AND payment_date <= '" . databaseDateFormat($qlastdate, 'indian', '-') . "' GROUP BY loanacc_id";
            $overres = db_query($recovq);
            $recovamt = db_fetch_object($overres);

            $rec_during_quarter_prin = $recovamt->prin;
            $rec_during_quarter_int = $recovamt->interest;
            $rec_during_quarter_ld = $recovamt->ld;

            $totalrecovery = $rec_during_quarter_prin + $rec_during_quarter_int + $rec_during_quarter_ld;
            $output .= '<td align="right">' . round($rec_during_quarter_prin) . '</td><td align="right">' . round($rec_during_quarter_int) . '</td><td align="right">' . round($rec_during_quarter_ld) . '</td><td align="right">' . round($totalrecovery) . '</td>';

            //TOTAL OVERDUE AT END OF CURRENT QUARTER ( OVERDUE LAST QUARTER + CURRENT DEMAND)
            $overdue_endof_qprin = ($overdueprin + $cur_demand_prin) - $rec_during_quarter_prin;
            $overdue_endof_qint = ($overdueint + $cur_demand_int) - $rec_during_quarter_int;
            $overdue_endof_qld = ($overdueld + $cur_demand_ld) - $rec_during_quarter_ld;
            $totaloverdue_end_of_quarter = $totaldemand_amt - $totalrecovery;
            $output .= '<td align="right">' . round($overdue_endof_qprin) . '</td><td align="right">' . round($overdue_endof_qint) . '</td><td align="right">' . round($overdue_endof_qld) . '</td><td align="right">' . round($totaloverdue_end_of_quarter) . '</td>';
            //OUTSTANDING AT THE END OF THE CURRENT QUARTER
            $outat_end_of_quarter_prin = ($out_beg_of_quarter_prin + $cur_demand_prin) - $rec_during_quarter_prin;
            $outat_end_of_quarter_int = ($out_beg_of_quarter_int + $cur_demand_int) - $rec_during_quarter_int;
            $outat_end_of_quarter_ld = ($out_beg_of_quarter_ld + $cur_demand_ld) - $rec_during_quarter_ld;
            $grandtotaloutstanding = $outat_end_of_quarter_prin + $outat_end_of_quarter_int + $outat_end_of_quarter_ld;
            $output .= '<td align="right">' . round($outat_end_of_quarter_prin) . '</td><td align="right">' . round($outat_end_of_quarter_int) . '</td><td align="right">' . round($outat_end_of_quarter_ld) . '</td><td align="right">' . round($grandtotaloutstanding) . '</td><td>-</td>';
            $output .= '</tr>';
        }
        $output .= '</table></div>';
    }
    return $output;
}

function sub_schemewise_recovery() {

    global $user;
    global $base_root;
    global $base_url;

    $array = explode('/', $_GET['q']);

    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = 'Scheme Wise Recovery Report';
    drupal_set_breadcrumb($breadcrumb);
    $scriptcss = '';
    if (isset($_POST['datefrom'])) {
        if (isEmpty('datefrom', $_POST['datefrom'], 'From Date'))
            $scriptcss .= '$("#datefromid").addClass("error");';
    }
    if (isset($_POST['dateto'])) {
        if (isEmpty('dateto', $_POST['dateto'], 'To Date'))
            $scriptcss .= '$("#datetoid").addClass("error");';
    }
    if (isset($_POST['datefrom']) && $_POST['datefrom']) {
        $datefrom = $_POST['datefrom'];
        $dateto = $_POST['dateto'];
    }
    $output = <<<EOD
	<script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.core.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.datepicker.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/lib/jquery.timeentry.pack.js?K"></script>
    
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/date_popup.js?K"></script>
	<script>
		$(function() {
			$( "#dateto" ).datepicker();
			$( "#dateto" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
			$( "#datefrom" ).datepicker();
			$( "#datefrom" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
			$( "#submit" ).focus();
		});
	</script>
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">
		<form action="" name="onetimesettlementform" method="post" enctype="multipart/form-data">
		  <table width="100%" style="border:none;" border="0" id="onetimesettlement_container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Scheme Wise Recovery Report</legend>
		 <table align="left" class="frmtbl" id="wrapper">
  <tr>
	  <td align="left"><strong>From Date:</strong> <span title="This field is required." class="form-required">*</span></td>
  	  <td><div class="maincol rptdate"><input type="text" name="datefrom" value="$datefrom" id="datefrom" readonly="readonly" /></div></td>
	  <td align="left"><strong>To Date:</strong> <span title="This field is required." class="form-required">*</span></td>
      <td><div class="maincol rptdate"><input type="text" name="dateto" value="$dateto" id="dateto" readonly="readonly" /></div></td>
      <td colspan="4" align="right"><input type="submit" class="form-submit" value="Generate" id="submit" name="ls"/></td></tr>
		</table></fieldset></td></tr></table>
        <br />
       
</form>
</div>
EOD;


    if (isset($_POST['datefrom']) && $_POST['datefrom'] && !$scriptcss) {
        $datefrom = strtotime(databaseDateFormat($_POST['datefrom'], 'indian', '-'));
        if ($_POST['dateto'])
            $dateto = strtotime(databaseDateFormat($_POST['dateto'], 'indian', '-'));
        else
            $dateto = time();
        $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";
        $pdfurl = $base_url . "/schemerecoverypdf.php?datefrom=$datefrom&dateto=$dateto";
        $sql = "SELECT c.corporation_name,sm.scheme_name,SUM(lr.amount) as amount FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_corporations c,tbl_loan_repayment lr WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.corp_branch = c.corporation_id AND lr.loanee_id = l.loanee_id AND l.alr_status !=2 AND lr.createdon >= " . intval($datefrom) . " AND lr.createdon <= " . intval($dateto) . "  GROUP BY ld.scheme_name,ld.corp_branch ORDER BY c.corporation_name";
        $query = "SELECT SUM(lr.amount) as alramount FROM alr a,tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_corporations c,tbl_loan_repayment lr WHERE l.account_id = a.case_no AND ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.corp_branch = c.corporation_id AND lr.loanee_id = l.loanee_id AND l.alr_status = 2 AND lr.createdon >= " . intval($datefrom) . " AND lr.createdon <=" . intval($dateto) . "";
        $r = db_query($query);
        $alr = db_fetch_object($r);
        //$sql = "SELECT c.corporation_name,sm.scheme_name,SUM(lr.amount) as amount FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_corporations c,tbl_loan_repayment lr WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.corp_branch = c.corporation_id AND lr.loanee_id = l.loanee_id AND lr.createdon BETWEEN '1324646684' AND '1325081443'  GROUP BY ld.scheme_name,ld.corp_branch ORDER BY c.corporation_name";
        //echo $sql;exit;
        $schemes = array();
        $res = db_query($sql);
        $output .= '<div class="listingpage_scrolltable"><table cellpadding="0" cellspacing="2" border="0" width="100%" id="wrapper1">
	<tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">Scheme Wise Recovery Report</h2></td></tr><tr>
	<td colspan="11" style="text-align:right;"><a target="_blank" href="' . $pdfurl . '"><img style="float:right;" src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" /></a></td></tr></table></div><div class="listingpage_scrolltable"><table><tr>';
        $c = 0;
        while ($r = db_fetch_object($res)) {
            $c++;
            if (!in_array($r->scheme_name, $schemes)) {
                $schemes[] = $r->scheme_name;
            }
            $rec[$r->corporation_name][$r->scheme_name] = $r->amount;
        }
        if ($c == 0) {
            $output = "<br><br><center><b>No Records To Show.</b></center>";
            return $output;
        }
        $output .= "<th>S.No.</th><th>Name of District Office</th>";
        foreach ($schemes as $k => $v) {
            $coltotal[$v] = 0;
            $output .= "<th align='center'>$v</th>";
        }
        $output .= "<th align='center'>Total</th><th align='center'>Account Closed</th></tr>";
        $counter = 0;
        $closed = 0;
        $totalofrowtotal = 0;
        foreach ($rec as $key => $val) {
            $sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_corporations c WHERE ld.reg_number = l.reg_number AND ld.corp_branch = c.corporation_id  AND c.corporation_name = '" . $key . "' AND ld.loan_status = 0";
            //echo $sql;exit;
            $result = db_query($sql);
            $closedacc = db_fetch_object($result);
            $counter++;
            if ($counter % 2)
                $cl = 'odd';
            else
                $cl = 'even';
            $output .= "<tr class=$cl><td align='center'>$counter</td><td align='left'>$key</td>";
            $rowtotal = 0;
            foreach ($schemes as $k => $v) {
                $coltotal[$v] = $coltotal[$v] + $val[$v];
                $rowtotal += $val[$v];
                $recovery = ($val[$v]) ? round($val[$v]) : "-";
                $output .= "<td align='center'>" . $recovery . "</td>";
            }
            $output .= "<td align='center'>" . round($rowtotal) . "</td><td align='center'>" . $closedacc->closed . "</td></tr>";
            $totalofrowtotal += $rowtotal;
            $totalofclosed += $closedacc->closed;
        }
        if ($cl == 'odd')
            $cl = 'even';
        else
            $cl = 'odd';
        $output .= "<tr class=$cl><td>&nbsp;</td><td align='center'>Total</td>";
        foreach ($schemes as $k => $v) {
            $output .= "<td align='center'>" . round($coltotal[$v]) . "</td>";
        }
        $output .= "<td align='center'>" . round($totalofrowtotal) . "</td><td align='center'>$totalofclosed</td></tr>";
        //print_r($rec);
        $output .= "<tr><td colspan='2'>";
        for ($i = 1; $i < count($schemes); $i++) {
            $output .= "<td>&nbsp;</td>";
        }
        $alramount = ($alr->alramount) ? $alr->alramount : 0;
        $alltotal = $totalofrowtotal + $alramount;
        $output .= "<td align='right'>ALR</td><td align='center'>" . round($alramount) . "</td></tr>";
        $output .= "<tr><td colspan='2'>";
        for ($i = 1; $i < count($schemes); $i++) {
            $output .= "<td>&nbsp;</td>";
        }
        $output .= "<td align='right'>Total</td><td align='center'>" . round($alltotal) . "</td></tr></table></div>";
        //return $output;
    }
    return $output;
}

//Daily Recovery Schedule Report Source Code.
function schemewise_recovery() {
    global $user;
    global $base_root;
    global $base_url;
    $array = explode('/', $_GET['q']);
    $breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = 'Main Daily Recovery Schedule Report';
    drupal_set_breadcrumb($breadcrumb);
    $scriptcss = '';

    if (isset($_POST['datefrom'])) {
        if (isEmpty('datefrom', $_POST['datefrom'], 'From Date'))
            $scriptcss .= '$("#datefromid").addClass("error");';
    }
    if (isset($_POST['dateto'])) {
        if (isEmpty('dateto', $_POST['dateto'], 'To Date'))
            $scriptcss .= '$("#datetoid").addClass("error");';
    }
    if (isset($_POST['datefrom']) && $_POST['datefrom']) {
        $datefrom = $_POST['datefrom'];
        $dateto = $_POST['dateto'];
    }
    $output = <<<EOD
	<script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.core.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.datepicker.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/lib/jquery.timeentry.pack.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/date_popup.js?K"></script>
	<script>
		$(function() {
			$( "#dateto" ).datepicker();
			$( "#dateto" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
			$( "#datefrom" ).datepicker();
			$( "#datefrom" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
			$( "#submit" ).focus();
		});
	</script>
	<div id="errorid" class="messages error" style="display:none;"></div>
	
	<div id="form-container">
		<form action="" name="onetimesettlementform" method="post" enctype="multipart/form-data">
		  <table width="100%" style="border:none;" border="0" id="onetimesettlement_container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Daily Recovery Schedule Report</legend>
		 <table align="left" class="frmtbl">
  <tr>
	  <td align="left"><strong>From Date: <span title="This field is required." class="form-required">*</span></strong></td>
  	  <td>			<input type="text" name="datefrom" value="$datefrom" id="datefrom" readonly="readonly" style="width:100px;"/>
			</td>
			 <td align="left"><strong>To Date: <span title="This field is required." class="form-required">*</span></strong>
			</td><td>
					<input type="text" name="dateto" value="$dateto" id="dateto" readonly="readonly" style="width:100px;"/>
			</td>
		<td colspan="4" align="right"><input type="submit" class="form-submit" value="Generate" id="submit" name="ls"/></td></tr>
		</table></fieldset></td></tr></table>
        <br />
       
</form>
</div>
EOD;


    if (isset($_POST['datefrom']) && $_POST['datefrom'] && !$scriptcss) {
        $datefrom = databaseDateFormat($_POST['datefrom'], 'indian', '-');
        $datefromstr = strtotime(databaseDateFormat($_POST['datefrom'], 'indian', '-'));
        if ($_POST['dateto']) {
            $dateto = databaseDateFormat($_POST['dateto'], 'indian', '-');
            $datetostr = strtotime($dateto);
        } else {
            $dateto = date("Y-m-d");
            $datetostr = time();
        }

        $pdfimage = $base_url . '/' . drupal_get_path('theme', 'scst') . "/images/pdf_icon.gif";
        $pdfurl = $base_url . "/mainschemewiserecoverypdf.php?datefrom=$datefromstr&dateto=$datetostr";




        $sql = "SELECT `id`,
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
                WHERE `payment_date` BETWEEN '" . $datefrom . "' AND '" . $dateto . "'
                ORDER BY `id`";



        $query = "SELECT SUM(a.amount_recovered) as alramount 
                 FROM alr a,
                      tbl_loanee_detail l 
                      WHERE l.account_id = a.case_no AND l.alr_status = 2 
                      AND a.date >= " . intval($datefromstr) . " AND a.date <=" . intval($datetostr) . "";

        $r = db_query($query);
        $alr = db_fetch_object($r);

        $output .= '<div class="listingpage_scrolltable">
                  <table cellpadding="0" cellspacing="2" border="0" width="100%">
	              <tr class="oddrow">
                  <td colspan="6"><h2 style="text-align:left;">Daily Recovery Schedule Report</h2></td></tr><tr>
	              <td colspan="11" style="text-align:right;">
                  <a target="_blank" href="' . $pdfurl . '">
                  <img style="float:right;" src="' . $pdfimage . '" alt="Export to PDF" title="Export to PDF" /></a>
                  </td></tr></table>
                  </div>
                  <div class="listingpage_scrolltable"><table><tr>';

        $output .= "<th>S.No.</th>
                    <th>District</th>
                    <th>Scheme</th>
                    <th>Account No.</th>
                    <th>Loanee Name</th>
                    <th>Pay Type.</th>
                    <th>Reciept No.</th>
                    <th>FR No.</th>
                    <th>Recovery Dt.</th>
                    <th>Amount Recovered</th>
                    <th>Balance Amount</th>";

        $counter = 0;
        $alltotal = 0;
        $res = db_query($sql);
        while ($rs = db_fetch_object($res)) {
            $counter++;
            $alltotal += $rs->amount;
            $res1 = db_query("SELECT `reg_number` FROM `tbl_loanee_detail` WHERE `account_id` = '" . $rs->account_id . "'");
            $regno = db_fetch_object($res1);
            $reg_number = $regno->reg_number;

            $res2 = db_query("SELECT `scheme_name`,`o_principal` FROM `tbl_loan_detail` WHERE `reg_number` = '" . $reg_number . "'");
            $ress2 = db_fetch_object($res2);
            $scheme_name = $ress2->scheme_name;
            $o_principal = $ress2->o_principal;

            echo $res3 = db_query("SELECT `scheme_name` FROM `tbl_scheme_master` WHERE `loan_scheme_id` = '" . $scheme_name . "'");
            $ress3 = db_fetch_object($res3);
            $schemename = $ress3->scheme_name;

            $res4 = db_query("SELECT `district_name` FROM `tbl_district` WHERE `district_id` = '$rs->district'");
            $ress4 = db_fetch_object($res4);
            $district_name = $ress4->district_name;
            // echo 'fetched values = '.$district_name.' | '.$schemename.' | '.$o_principal.' <br />';

            if ($counter % 2) {
                $cl = 'odd';
            } else {
                $cl = 'even';
            }

            $output .= "<tr class=$cl>
                        <td align='center'>$counter</td>
                        <td align='left'>$district_name</td>
                        <td align='center'>$schemename</td>
                        <td align='center'>$rs->account_id</td>
                        <td align='center'>$rs->fname &nbsp;$rs->lname</td>
                        <td align='center'>$rs->paytype</td>
                        <td align='center'>$rs->id</td>
                        <td align='center'>$rs->cheque_number</td>
                        <td align='center'>$rs->payment_date</td>
                        <td align='center'>$rs->amount</td>
                        <td align='center'>$o_principal</td>";
        }


        $output .= "<tr><td colspan='2'>";

        $output .= "<tr><td colspan='2'>";
        $output .= "<td align='right'>Total Recovery:</td><td align='right'>" . round($alltotal) . "</td></tr></table></div>";
        //return $output;
    }
    return $output;
}
?>