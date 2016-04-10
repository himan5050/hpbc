<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
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
//$pdf->AddPage();




if($_REQUEST['op'] == 'cumulativerecovery'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];

$fromtime = $_REQUEST['from_date'];
$totime = $_REQUEST['to_date'];

$from =   intval(strtotime("0 day", strtotime($fromtime)));
$to = intval(strtotime("+1 day" ,strtotime($totime)));

/*$from = date("Y-m-d",strtotime($fromtime));
$to = date("Y-m-d",strtotime($totime));
*/
$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size:7pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size:14pt;

font-weight:bold;
background-color:#ffffff;
}
table{
/*width:1200px;*/
}
table.tbl_border{border:1px solid #a7c942;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size:7pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size:7pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size:7pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size:7pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size:7pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size:7pt;
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
<tr><td class="header_report" align="center">District-Wise Scheme-Wise Cumulative Recovery Statement</td></tr></table><br />';

//header close	

  $sql = "SELECT tbl_loanee_detail.corp_branch,tbl_corporations.corporation_name,tbl_scheme_master.loan_scheme_id,tbl_scheme_master.scheme_name ,tbl_loan_detail.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.reg_number as regnumber ,tbl_loanee_detail.account_id,tbl_loan_repayment.createdon,sum(tbl_loan_repayment.amount) as repaymentamount
	 ,sum(tbl_loan_detail.emi_amount) as emiamount,tbl_scheme_master.main_scheme,tbl_schemenames.schemeName_id,tbl_schemenames.	schemeName_name as sche FROM tbl_loanee_detail inner join tbl_corporations on (tbl_corporations.corporation_id=tbl_loanee_detail.corp_branch) 
	 inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
	 inner join tbl_scheme_master on (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
	  inner join tbl_schemenames on (tbl_schemenames.schemeName_id=tbl_scheme_master.main_scheme)
	 inner join tbl_loan_repayment on (tbl_loan_repayment.loanee_id=tbl_loanee_detail.loanee_id)	
	 

where 1=1"; 

	
$cond = '';		
	
	
	if($fromtime!='' && $totime!=''){
		$cond .= " AND (tbl_loan_repayment.createdon BETWEEN $from AND $to) ";
		$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>From Date : </b>'.$fromtime.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date : </b>'.$totime.'</td></tr><tr><td>&nbsp;</td></tr>
</table><br>';
	}else{
		if($fromtime!=''){
			$cond .= " AND tbl_loan_repayment.createdon=$from ";
		}
		if($totime!=''){
			$cond .= " AND tbl_loan_repayment.createdon=$to ";
		}
	}
	
	
	$cond .= ' group by tbl_loan_detail.scheme_name,tbl_loanee_detail.corp_branch';	
	
	

	
 $query = $sql . $cond;	
 $res = db_query($query);
 
 $schemes = array();

   $output .='<table cellspacing="2" cellpadding="3" class="tbl_border"><tr>';
   $output .='<td width="2%" class="header2" align="center">S. No.</td>';
   $output .='<td width="10%" class="header2">Name Of District Office</td>';
   $output .='<td width="5%" class="header2">Target</td>';
 $counter=1;	 
 //$sqlw=db_query("select scheme_name,loan_scheme_id from tbl_scheme_master"); 
 while($row=db_fetch_object($res))
 {
	 if(!in_array($row->sche,$schemes))
	{
	 $schemes[] = $row->sche;
	
	}
	 $rec[ucwords($row->corporation_name)][$row->sche] = $row->repaymentamount;
	 $rec[ucwords($row->corporation_name)]['emi'] += $row->emiamount;
	 
	 //echo "select * from tbl_loan_detail where reg_number ='".$row->regnumber."'";exit;
	
	 
	 //$schemearr[$row->loan_scheme_id] = $row->scheme_name; 
	
	 	
     //$output .='<th>'.$row->sche.'</th>';
                
				
 }
 
  
 
 foreach($schemes as $k => $v)
		{
			
			$coltotal[$v] = 0;
			$output .= '<td width="5%" class="header2">'.ucwords($v).'</td>';
		}
				
		$output .= '<td width="6%" class="header2">Total Received</td>';
		$output .= '<td width="4%" class="header2">A/c Closed</td>';		
		$output .= '<td width="6%" class="header2">%</td></tr>';		
				
				 
  //$outputh .= $header1.$header2.$header3;
  
 
 
 
 $closed = 0;
	$totalofrowtotal = 0;
  foreach($rec as $key => $val)
		{
			
			
			$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_corporations c WHERE ld.reg_number = l.reg_number AND ld.corp_branch = c.corporation_id  AND c.corporation_name = '".$key."' AND ld.loan_status = 0";
			//echo $sql;exit;
			$result = db_query($sql);
			$closedacc = db_fetch_object($result);
			
			
			
			
			if($counter%2==0){ $cl='header4_1';}else{$cl='header4_2';}
			$output .= '<tr><td class="'.$cl.'" align="center">'.$counter.'</td><td class="'.$cl.'">'.$key.'</td><td class="'.$cl.'" align="right">'.$val[emi].'</td>';
			$recovery1 = 0;
			$rowtotal = 0;
			foreach($schemes as $k => $v)
			{
				
				if($counter%2==0)	{ $cl='header4_1';}else{$cl='header4_2';}
				
				$coltotal[$v] = $coltotal[$v] + $val[$v];
				$rowtotal += $val[$v];
				
				$recovery = ($val[$v])?$val[$v]:"-";
				
				$recovery1 += number_format($val[$v],2,'.','');
				$percentage=($recovery1/$val['emi']) * 100;
				
				$output .= '<td class="'.$cl.'" align="right">'.$recovery.'</td>';
				 
			}
				
			$output .= '<td class="'.$cl.'" align="right">'.number_format($recovery1,2,'.','').'</td>';
			$output .= '<td class="'.$cl.'" align="right">'.$closedacc->closed.'</td>';	
			$output .= '<td class="'.$cl.'" align="right">'.number_format($percentage,2,'.','').'%'.'</td></tr>';	
			$totalofrowtotal += $rowtotal;
			$totalofrowtotalval = number_format($totalofrowtotal,2,'.','');
			$totalofclosed += $closedacc->closed;
			$totalofrowtotalll += $val['emi'];	
			$counter++;
			
			$newcounter=$counter;
		}//$cc += $amount;
		
		$percentagetotal=round((($totalofrowtotal/$totalofrowtotalll) * 100),2);
		
		
	$sqlalr=db_query("SELECT tbl_loanee_detail.corp_branch,tbl_corporations.corporation_name,tbl_scheme_master.loan_scheme_id,tbl_scheme_master.scheme_name as sche,tbl_loan_detail.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.reg_number as regnumber ,tbl_loanee_detail.account_id,tbl_loan_repayment.createdon,sum(tbl_loan_repayment.amount) as totalamount 	
	FROM tbl_loanee_detail inner join tbl_corporations on (tbl_corporations.corporation_id=tbl_loanee_detail.corp_branch) 
	 inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
	 inner join tbl_scheme_master on (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
	 inner join tbl_loan_repayment on (tbl_loan_repayment.loanee_id=tbl_loanee_detail.loanee_id)
	 inner join alr on (alr.case_no=tbl_loanee_detail.account_id) group by tbl_loanee_detail.corp_branch
	 
	 
	 ");
	 
	 $apalr=db_fetch_object($sqlalr);
	 
	 $sumalr=$apalr->totalamount;
	 
	 $sqlfdr=db_query("SELECT tbl_loanee_detail.corp_branch,tbl_corporations.corporation_name,tbl_scheme_master.loan_scheme_id,tbl_scheme_master.scheme_name as sche,tbl_loan_detail.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.reg_number as regnumber ,tbl_loanee_detail.account_id,sum(tbl_fdr.maturity_amount) as maturityamount 	
	FROM tbl_loanee_detail inner join tbl_corporations on (tbl_corporations.corporation_id=tbl_loanee_detail.corp_branch) 
	 inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
	 inner join tbl_scheme_master on (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
	 inner join tbl_fdr on (tbl_fdr.account_no=tbl_loanee_detail.loanee_id) group by tbl_loanee_detail.corp_branch
	 
	 
	 ");
	 
	 $apfdr=db_fetch_object($sqlfdr);
	 
	 $sumfdr=$apfdr->maturityamount;
		$grandtotal=$totalofrowtotal+$sumalr+$sumfdr;
		$grandtotalnum=	number_format($grandtotal,2,'.','');			
		if($newcounter%2==0)
		 {$cl = 'header4_1';}
		else
		{$cl = 'header4_2';}
		$output .= '<tr>
						<td class="'.$cl.'">&nbsp;</td>
						<td class="'.$cl.'">Total</td>
						<td class="'.$cl.'" align="right">'.$totalofrowtotalll.'</td>';		
		foreach($schemes as $k => $v)
		{
			$output .= '<td class="'.$cl.'" align="right">'.number_format($coltotal[$v],2,'.', '').'</td>';
			}
			$output .= '<td class="'.$cl.'" align="right">'.$totalofrowtotalval.'</td>
						<td class="'.$cl.'" align="right">'.$totalofclosed.'</td>
						<td class="'.$cl.'" align="right">'.number_format($percentagetotal,2,'.','').'%</td>
		</tr>';
	     $newcounter++;  
		//  $cla = ($i++ % 2) ? 'header4_2' : '';
		if($newcounter%2==0)
		 {$cl = 'header4_1';}
		else
		{$cl= 'header4_2';}
		
		  $output .= "<tr class='$cl'>";
		for($i=1;$i<count($schemes);$i++)
		{
			$output .= '<td class="'.$cl.'">&nbsp;</td>';		
		}		
		$output .= '<td class="'.$cl.'" colspan="4" align="right">ALR</td><td  class="'.$cl.'" align="right">'.$sumalr.'</td><td class="'.$cl.'">&nbsp;</td><td class="'.$cl.'">&nbsp;</td></tr>';
       $newcounter++;       
	   if($newcounter%2==0)
		 {$cl = 'header4_1';}
		else
		{$cl= 'header4_2';}
		
		 $output .= "<tr>";
		for($i=1;$i<count($schemes);$i++)
		{
			$output .= '<td class="'.$cl.'">&nbsp;</td>';
		
		}
	   $output .= '<td class="'.$cl.'" colspan="4" align="right">M.M.D</td><td  class="'.$cl.'" align="right">'.$sumfdr.'</td><td class="'.$cl.'">&nbsp;</td><td class="'.$cl.'">&nbsp;</td></tr>';
        $newcounter++;      
		if($newcounter%2==0)
		 {$cl = 'header4_1';}
		else
		{$cl = 'header4_2';}
		$output .= "<tr>";
		for($i=1;$i<count($schemes);$i++)
		{
			$output .= '<td class="'.$cl.'">&nbsp;</td>';
		
		}
	  $output .= '<td class="'.$cl.'" colspan="4" align="right">Grand Total</td><td  class="'.$cl.'" align="right">'.$grandtotalnum.'</td><td class="'.$cl.'" >&nbsp;</td><td class="'.$cl.'">&nbsp;</td></tr>'; 
		 

$output .='</table>';
 //$outputtt .='<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
 $outputf = $output;
		
	
	
	

	//for($ik=1;$ik <= 10;$ik++){
//$pdf->AddPage();
// print a line
//$pdf->Cell(0, 12, 'DISPLAY PREFERENCES - PAGE 1', 0, 0, 'C');
	//$pdf->writeHTML($output, true, 0, true, true);
//$output .='neshat';
//}
	 // print a block of text using Write()

	//Close and output PDF document
	$pdf->AddPage();
	ob_end_clean();
	$pdf->writeHTML($outputf, true,1, false, false);
	 
	$pdf->Output('cumulativerecovery_'.time().'.pdf', 'I');
}

