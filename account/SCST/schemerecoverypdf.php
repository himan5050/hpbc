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
width:1230px;
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

	$datefrom = $_REQUEST['datefrom'];
	$dateto = $_REQUEST['dateto'];
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">Scheme-wise Recovery for the period of '.date("d-m-Y",$datefrom).' to '.date("d-m-Y",$dateto).' </td></tr></table><br />';
			
		$sql = "SELECT c.corporation_name,sm.scheme_name,SUM(lr.amount) as amount FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_corporations c,tbl_loan_repayment lr WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.corp_branch = c.corporation_id AND lr.loanee_id = l.loanee_id AND l.alr_status !=2 AND lr.createdon >= ".intval($datefrom)." AND lr.createdon <= ".intval($dateto)."  GROUP BY ld.scheme_name,ld.corp_branch ORDER BY c.corporation_name";
		$query = "SELECT SUM(a.total_amount) as alramount FROM alr a,tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_corporations c,tbl_loan_repayment lr WHERE l.account_id = a.case_no AND ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.corp_branch = c.corporation_id AND lr.loanee_id = l.loanee_id AND l.alr_status = 2 AND lr.createdon >= ".intval($datefrom)." AND lr.createdon <=".intval($dateto)."";
		$r = db_query($query);
		$alr = db_fetch_object($r);
		//$sql = "SELECT c.corporation_name,sm.scheme_name,SUM(lr.amount) as amount FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_scheme_master sm,tbl_corporations c,tbl_loan_repayment lr WHERE ld.reg_number = l.reg_number AND ld.scheme_name = sm.loan_scheme_id AND ld.corp_branch = c.corporation_id AND lr.loanee_id = l.loanee_id AND lr.createdon BETWEEN '1324646684' AND '1325081443'  GROUP BY ld.scheme_name,ld.corp_branch ORDER BY c.corporation_name";
	
		
		$schemes = array();
		$res = db_query($sql);
		$output .= '<table class="tbl_border" cellpadding="2" cellspacing="2"><tr>';
		$c = 0;
		while($r = db_fetch_object($res))
		{
			$c++;
			if(!in_array($r->scheme_name,$schemes))
			{
				$schemes[] = $r->scheme_name;
			}
			$rec[$r->corporation_name][$r->scheme_name] = $r->amount;
		}
		if($c == 0)
		{
			$output = '<br><br><center><b>No Records To Show.</b></center>';
			return $output;
		}
		$output .= '<td  class="header2" width="5%">S. No.</td><td class="header2">Name of District Office</td>';
		foreach($schemes as $k => $v)
		{
			$coltotal[$v] = 0;
			$output .= '<td class="header2" align="center">'.$v.'</td>';
		}
		$output .= '<td class="header2" align="center">Total</td><td align="center" class="header2">Account Closed</td></tr>';
		$counter = 0;
		$closed = 0;
		$totalofrowtotal = 0;
		foreach($rec as $key => $val)
		{
			$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_corporations c WHERE ld.reg_number = l.reg_number AND ld.corp_branch = c.corporation_id  AND c.corporation_name = '".$key."' AND ld.loan_status = 0";
			//echo $sql;exit;
			$result = db_query($sql);
			$closedacc = db_fetch_object($result);
			$counter++;
			if($counter % 2)
				$cl = 'header4_1';
			else
				$cl = 'header4_2';
			$output .= '<tr><td class="'.$cl.'" align="center">'.$counter.'</td><td align="left" class="'.$cl.'">'.$key.'</td>';
			$rowtotal = 0;
			foreach($schemes as $k => $v)
			{
				$coltotal[$v] = $coltotal[$v] + $val[$v];
				$rowtotal += $val[$v];
				$recovery = ($val[$v])?round($val[$v],2):"-";
				$output .= '<td align="right" class="'.$cl.'">'.number_format($recovery,2,'.','').'</td>';
			}
			$output .= '<td align="right" class="'.$cl.'">'.number_format($rowtotal,2,'.','').'</td><td align="right" class="'.$cl.'">'.$closedacc->closed.'</td></tr>';	
			$totalofrowtotal += $rowtotal;
			$totalofclosed += $closedacc->closed;
		}
		if($cl == 'header4_1')
			$cl = 'header4_2';
		else
			$cl = 'header4_1';
		$output .= '<tr><td class="'.$cl.'"></td><td align="center" class="'.$cl.'">Total</td>';
		foreach($schemes as $k => $v)
		{
			$output .= '<td align="right" class="'.$cl.'">'.number_format($coltotal[$v],2,'.','').'</td>';
		}
		$output .= '<td align="right" class="'.$cl.'">'.number_format($totalofrowtotal,2,'.','').'</td><td align="right" class="'.$cl.'">'.$totalofclosed.'</td></tr>';
		//print_r($rec);
		$output .= '<tr><td colspan="2" class="'.$cl.'"></td>';
		for($i=2;$i<=count($schemes);$i++)
		{
			$output .= '<td class="'.$cl.'"></td>';
		}
		$alramount = ($alr->alramount)?$alr->alramount:0;
		$alltotal = $totalofrowtotal + $alramount;
		$output .= '<td align="right" class="'.$cl.'">ALR</td><td align="right" class="'.$cl.'">'.number_format($alramount,2,'.','').'</td><td class="'.$cl.'"></td></tr>';
		$output .= '<tr><td colspan="2" class="'.$cl.'"></td>';
		for($i=2;$i<=count($schemes);$i++)
		{
			$output .= '<td class="'.$cl.'"></td>';
		}
		$output .= '<td align="right" class="'.$cl.'">Total</td><td align="right" class="'.$cl.'">'.number_format($alltotal,2,'.','').'</td><td class="'.$cl.'"></td></tr></table>';
	ob_end_clean();
$pdf->writeHTML($output, true, 0, true, true);
//Close and output PDF document
$pdf->Output('schemerecovery_'.time().'.pdf', 'I');
?>