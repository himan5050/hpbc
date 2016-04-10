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
// add a page
//$pdf->AddPage();




if($_REQUEST['op'] == 'loanledgerreport'){
global $user, $base_url;
//$rid = $_REQUEST['rid'];
$accountno = $_REQUEST['accountno'];

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
width:1000px;
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
<tr><td class="header_report" align="center">Loan Ledger</td></tr></table><br />';
//header close
  $sql = "SELECT tbl_loanee_detail.loanee_id,tbl_loanee_detail.account_id,tbl_loanee_detail.fname,tbl_loanee_detail.lname,tbl_loanee_detail.fh_name,
	 tbl_loanee_detail.address1,tbl_loanee_detail.address2,tbl_loanee_detail.dob,tbl_loanee_detail.caste,tbl_loanee_detail.reg_number,
	 tbl_loan_detail.scheme_name,tbl_scheme_master.loan_scheme_id,tbl_scheme_master.scheme_name as schemename,tbl_loan_detail.loan_requirement,
	 tbl_scheme_master.promoter_share,tbl_scheme_master.apex_share,tbl_scheme_master.corp_share,
	 tbl_loan_detail.work_place,tbl_scheme_master.tenure,tbl_loan_detail.emi_amount, tbl_loan_detail.ROI,tbl_scheme_master.project_cost,tbl_loan_detail.o_principal
FROM tbl_loanee_detail
left JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number = tbl_loanee_detail.reg_number)
left JOIN tbl_scheme_master ON (tbl_loan_detail.scheme_name = tbl_scheme_master.loan_scheme_id)



WHERE 1=1";


	
$cond = '';	
	

	if($accountno){
		$cond .= " AND tbl_loanee_detail.account_id='$accountno'";
		$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header1" align="left"><b>Account No. : </b>'.$accountno.'</td></tr>
</table><br />';
	}	
	
	
	
$query = $sql . $cond;
  
  $res1=db_query($query);
  
  $row=db_fetch_object($res1);
    $oprincipal=$row->o_principal;
  $loanrequirement=$row->loan_requirement;
  $projectcost=$row->project_cost;
  $pro=$row->promoter_share;
  $apexshare=$row->apex_share;
  $corpshare=$row->corp_share;
$emiamount = $row->emi_amount;
  $loan_term=$row->apex_share+$row->corp_share;
  
  $share=($projectcost * $pro) / 100;
  $apexsharenb=($projectcost * $apexshare) / 100;
  $corpsharehb=($projectcost * $corpshare) / 100;
  
  $bal= $oprincipal-$share;	
	


 if($row->account_id)
{

$output .='<table border="0" width="1000px;" class="tbl_border"><tr>
                <td class="header4_1" width="26%"><b>1)&nbsp;Account No.:</b>&nbsp;<span style="text-decoration:underline;">'.$row->account_id.'</span></td><td class="header4_1"><b>2)&nbsp;Name Of Loanee:</b>&nbsp;<span style="text-decoration:underline;">'.$row->fname.' '.$row->lname.'</span></td> <td class="header4_1" width="22%"><b>3)&nbsp;S/o D/o,W/o</b>&nbsp;<span style="text-decoration:underline;">'.$row->fname.'</span></td><td class="header4_1"><b>4)&nbsp;Address:</b>&nbsp;<span style="text-decoration:underline;">'.$row->address1.' '.$row->address2.'</span></td></tr><tr><td class="header4_2" colspan="4">&nbsp;</td></tr>
                <tr><td class="header4_1">&nbsp;</td><td class="header4_1"><b>D.O.B:</b>&nbsp;<span style="text-decoration:underline;">'.date("d-m-Y",strtotime($row->dob)).'</span></td><td class="header4_1"><b>Caste:</b>&nbsp;<span style="text-decoration:underline;">'.getCastemain($row->caste).'</span></td><td class="header4_1"><b>Scheme:</b>&nbsp;<span style="text-decoration:underline;">'.$row->schemename.'</span></td></tr><tr><td class="header4_2" colspan="4">&nbsp;</td></tr>
                 
				<tr><td class="header4_1" ><b>5)&nbsp;Sanction Order No:</b>&nbsp;<span style="text-decoration:underline;">'.$row->reg_number.'</span></td><td class="header4_1"><b>& &nbsp;amount of Sanction</b>&nbsp;<span style="text-decoration:underline;">'.$row->project_cost.'</span></td><td class="header4_1"><b>NBCFDC</b>&nbsp;<span style="text-decoration:underline;">'.$apexsharenb.'</span></td><td class="header4_1"><b>HBCFDC</b> <span style="text-decoration:underline;">'.$corpsharehb.'</span></td></tr>
				<tr><td class="header4_2">&nbsp;</td><td class="header4_2">&nbsp;</td><td class="header4_2"><b>Share:</b>&nbsp;<span style="text-decoration:underline;">'.$row->promoter_share."%".'</span></td><td class="header4_2"><b>Share:&nbsp;</b><span style="text-decoration:underline;">'. $share.'</span></td></tr>
				
				<tr><td class="header4_1">&nbsp;</td><td class="header4_1">&nbsp;</td><td class="header4_1"><b>Total Term Loan:</b>&nbsp;<span style="text-decoration:underline;">'.$row->loan_requirement.'</span></td><td class="header4_1"><b>Place Of Work:</b>&nbsp;<span style="text-decoration:underline;">'.$row->work_place.'</span></td></tr><tr><td class="header4_2" colspan="4">&nbsp;</td></tr></table><br /><table class="tbl_border" width="980px" cellspacing="2" cellpadding="3"><tr><td class="header2">Amount Released</td><td class="header2">DD/Cheque No.</td><td class="header2">Date</td></tr>';
				//echo "select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."'";exit;
				$dissql=db_query("select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."'");
				$counter = 0;
				while($rowdis=db_fetch_object($dissql))
				{
					$counter++;
					if($counter%2==0){$cl="header4_2";}else{$cl="header4_1";}	
   				$output .='<tr><td class="'.$cl.'" align="right">'.$rowdis->amount.'</td><td class="'.$cl.'" align="right">'.$rowdis->cheque_number.'</td><td class="'.$cl.'" align="center">'.date("d-m-Y",$rowdis->createdon).'</td></tr>';
			}
				$output .='</table><br /><table width="980px" class="tbl_border"><tr><td class="header4_1"><b>6)&nbsp;Number Of Monthly Instalmants:</b>&nbsp;<span style="text-decoration:underline;">'.$row->tenure.'</span></td><td class="header4_1"><b>Amount of Instalment:</b>&nbsp;<span style="text-decoration:underline;">'.$row->emi_amount.'</span></td><td class="header4_1"><b>Rate Of Interest</b>&nbsp;<span style="text-decoration:underline;">'.$row->ROI.'</span></td></tr><tr><td class="header4_2" colspan="3"><b>7)&nbsp;Gurantor Adddress:</b></td></tr>';
				
				
				$sqlgran=db_query("select * from tbl_guarantor_detail where loanee_id = '".$row->loanee_id."'");
				while($asdgran=db_fetch_object($sqlgran)){
				$counter++;
					if($counter%2==0){$cl="header4_2";}else{$cl="header4_1";}			
				$output .='<tr><td class="'.$cl.'" colspan="3"><span style="text-decoration:underline;">'.$asdgran->address.'</span></td>			
			 </tr>';
				}
			 $output .='</table><br/><br/>';
}
else{
echo '<font color="red"><b>No Record found...</b></font>';		
	
}

   $output .='<table class="tbl_border" cellpadding="3" cellspacing="2"><tr>
                <td class="header2">Date</td>
                <td class="header2">PartiCulars</td>
   				<td class="header2">Dr.</td>
				<td class="header2">Cr.</td>
				<td class="header2">Balance</td>
				<td class="header2">Period of Retention of Principal</td>
				<td class="header2">Interest</td>
				<td class="header2">Amount of Default</td>
				<td class="header2">Period of Retention</td>
				<td class="header2">Amount of LD</td>
				<td class="header2">Remarks</td>
				<td class="header2">Amount of Other Charges</td>
				<td class="header2">Remarks</td>
			</tr>';
	
			 
			if ($counter%2==0){$cl="even";}else{$cl="odd";} 
			
			 $loanesql=db_query("select sum(amount) as disamountid from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."'");
			 $rowloane=db_fetch_object($loanesql);
			 $sisamount= $rowloane->disamountid;
			 
			 
			 
			 
			 
			 
			 
			
			//echo "select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."' order by createdon desc";exit;
			 $dissql=db_query("select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."' order by createdon ");
			 $rowdis=db_fetch_object($dissql);
			 
			$disdate = date("Y-m-d", $rowdis->createdon);
			$psharesql = "SELECT SUM(amount) as pshare FROM tbl_loan_repayment WHERE loanee_id = '".$row->loanee_id."' AND payment_date = '".$disdate."' AND paytype = 'Promoter Share' GROUP BY loanee_id";
			//echo $psharesql;
			$pres = db_query($psharesql);
			$ps = db_fetch_object($pres);
			$pshare = ($ps->pshare)?round($ps->pshare,2):0;
			 
			
			 
			$sqlin=db_query("select tbl_loan_interestld.calculation_date,tbl_loan_interestld.type, tbl_loan_interestld.amount as intamount from tbl_loanee_detail inner join tbl_loan_interestld on (tbl_loan_interestld.account_id=tbl_loanee_detail.account_id)
	 where tbl_loanee_detail.loanee_id='".$row->loanee_id."' and tbl_loan_interestld.type='interest' order by tbl_loan_interestld.calculation_date asc limit 0,1");
	  
	$wsd=db_fetch_object($sqlin);
	  
	$wsdd=$wsd->calculation_date;
	 // $startTimeStamp = strtotime($disdate);
//$endTimeStamp = strtotime($wsdd);


if(!$wsdd){


}
else{
$numberDays = dateDiffByDays($disdate,$wsdd);  // 86400 seconds in one day

// and you might want to convert to integer


$numberDays = intval($numberDays);
	
	
}



        
	
		
		$dateg=dateDiffByDays($disdate,$wsdd);
		
		$bal = $sisamount-$pshare;
		
		if($bal < 0){
$ball=0;	
	
}
else{
	
$ball=round($bal,2);	
	
	
}
			 
			 $output .='<tr>
					 
					  <td align="center" class="header4_1">'.date("d-m-Y",$rowdis->createdon).'</td>
					  <td align="left" class="header4_1">'.'To Loan A/c'.'</td>
					  <td align="right" class="header4_1">'.round($sisamount,2).'</td>
					  <td class="header4_1" align="right">'.$pshare.'</td>
					  <td align="right" class="header4_1">'.$ball.'</td>
					  <td align="right" class="header4_1">'.$numberDays.'</td>
					  <td align="right" class="header4_1">'.$wsd->intamount.'</td>
					  <td align="right" class="header4_1">'.' '.'</td>
					  <td align="right" class="header4_1">'.' '.'</td>
					  <td align="right" class="header4_1">'.' '.'</td>
					  <td align="right" class="header4_1">'.' '.'</td>
					  <td align="right" class="header4_1">'.' '.'</td>
					  <td align="right" class="header4_1">'.' '.'</td>
					  
					  
					  
					  
	            </tr>';
			
				
  
  $query = $sql . $cond;
  $sql_count = "SELECT count(*) as count_neshat FROM tbl_loanee_detail
INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number = tbl_loanee_detail.reg_number)
INNER JOIN tbl_scheme_master ON (tbl_loan_detail.scheme_name = tbl_scheme_master.loan_scheme_id)
left JOIN tbl_guarantor_detail ON (tbl_guarantor_detail.loanee_id = tbl_loanee_detail.loanee_id)


WHERE 1=1";

  $query_count = $sql_count . $cond;
  $rescount = db_query($query_count);
  $rscount = db_fetch_object($rescount);
  
  $res = db_query($query);
 
$counter=1;	
 $neshatcount =1;
 

 
 while($rs = db_fetch_object($res)){

	 
	$retdate=$rowdis->createdon;
	
	
	
$sqlll=db_query("select tbl_loan_interestld.calculation_date, tbl_loan_interestld.reason,sum(tbl_loan_interestld.amount) as intamount,tbl_loan_interestld.type from tbl_loanee_detail inner join tbl_loan_interestld on (tbl_loan_interestld.account_id=tbl_loanee_detail.account_id)
	where tbl_loanee_detail.account_id='$accountno' group by tbl_loan_interestld.type,tbl_loan_interestld.calculation_date");
	
	while($resins=db_fetch_object($sqlll))
	{
		
	
		if($resins->type == 'interest')
		{
			$intldarr[$resins->calculation_date] = $resins->intamount;
		}elseif(!array_key_exists($resins->calculation_date,$intldarr)){
			$intldarr[$resins->calculation_date] = '-';
		}
		if($resins->type == 'LD'){
			$ldarr[$resins->calculation_date]['amount'] = $resins->intamount;
			$ldarr[$resins->calculation_date]['reason'] = ucwords(getLookupName($resins->reason));
		}
		if($resins->type == 'other')
		{
			$otherarr[$resins->calculation_date]['amount'] = $resins->intamount;
			$otherarr[$resins->calculation_date]['reason'] = ucwords(getLookupName($resins->reason));
		}
	
		
		
	}
	
	
	
	
	$sqllre=db_query("select tbl_loan_repayment.payment_date,tbl_loan_repayment.amount as repamount from tbl_loanee_detail inner join tbl_loan_interestld on (tbl_loan_interestld.account_id=tbl_loanee_detail.account_id)  inner join tbl_loan_repayment on (tbl_loan_repayment.loanee_id=tbl_loanee_detail.loanee_id) where tbl_loanee_detail.account_id='$accountno'");	
	
	while($resre=db_fetch_object($sqllre))
	{
		
		$repldarr[$resre->payment_date] = $resre->repamount;
	}
	

	
	$farr=array_merge($intldarr,$repldarr);
	
	
	
	//print_r($farr);exit;
	ksort($farr);
	
	//print_r($farr);exit;
	
	$sqlrep=db_query("select tbl_loan_repayment.payment_date,tbl_loan_repayment.amount as repamount from tbl_loanee_detail inner join tbl_loan_interestld on (tbl_loan_interestld.account_id=tbl_loanee_detail.account_id)  inner join tbl_loan_repayment on (tbl_loan_repayment.loanee_id=tbl_loanee_detail.loanee_id) where tbl_loanee_detail.account_id='$accountno' ORDER BY tbl_loan_repayment.payment_date");	
	while($r=db_fetch_object($sqlrep))
	{
		
		foreach($farr as $k => $vall)
		{
			
			if(!$intldarr[$k])
			{
				$fdarr[$r->payment_date]['cr']= $r->repamount;	
				$fdarr[$r->payment_date]['dr']=0;	
				
			}
			else{
				$fdarr[$k]['dr']=$intldarr[$k];	
				
				if($k == $r->payment_date){
					
					$fdarr[$r->payment_date]['cr']= $r->repamount;		
					
				}
				else{
					
					$fdarr[$k]['cr']=0;		
				}
			
			
		}
		}
		
		
		
		
		/*if($intldarr[$r->payment_date])
		{
			
			$fdarr[$r->payment_date]['dr']=$intldarr[$r->payment_date];
			
			$fdarr[$r->payment_date]['cr']= $r->repamount;	
		
		}else{
			$fdarr[$r->payment_date]['cr']=$r->repamount;
			
			$fdarr[$r->payment_date]['dr']=0;	
		}*/
	}
	
	//print_r($fdarr);exit;
	
	$keyarr=array_keys($fdarr);
	//print_r($keyarr);exit;
	$counter = 0;
	$totalrepay = 0;
	ksort($fdarr);
	//print_r($fdarr);exit;
	foreach($fdarr as $key => $v)
	{
		$counter++;
		if ($counter%2==0){$cl="header4_1";}else{$cl="header4_2";} 
		$output .='<tr class="'.$cl.'">';
		$output .='<td class="'.$cl.'" align="center">'.date("d-m-Y",strtotime($key)).'</td>';
		
		if($v['cr'])
		
		{
			$part='To Loan A/c';	
			
		}
		
		
		
		else if($v['dr'] && $v['dr'] != '-'){
			
			$part='To int';		
		}else{
			$part='To LD and Other charges';	
		}
		
		
		
		$output .='<td class="'.$cl.'" align="left">kkkkk'.$part.'</td>';
		
		
		
		//$output .='<td>';
		
		
		
		if($v['dr'] && $v['dr'] != '-')
		{
			$output .='<td class="'.$cl.'" align="right">'.$v['dr'].'</td>';
		}
		else{
			$output .='<td class="'.$cl.'">'.' '.'</td>';	
		}
		if($v['cr'])
		{
			$output .='<td class="'.$cl.'" align="right">'.$v['cr'].'</td>';
		}
		else{
			$output .='<td class="'.$cl.'">'.' '.'</td>';	
		}
			
		$bal =($bal + $v['dr']) - $v['cr'];
		
		if($bal < 0){
			
		$ball =0;	
		}else{
			$ball =$bal;
		//$ball =round(($bal + $v['dr']) - $v['cr'],2);
		}
		
		$output .='<td class="'.$cl.'" align="right">'.$ball.'</td>';	
		
		if($v['dr'] && $v['dr'] != '-')
		{
			$current = $key;
			
			$ordinal = array_search($current,$keyarr) + 1;
			$nextk = $keyarr[$ordinal];
			if($fdarr[$nextk]['dr'])
			{
				$perret = dateDiffByDays($key,$nextk);
				$output .='<td class="'.$cl.'" align="right">'.$perret.'</td>';
				$output .='<td class="'.$cl.'">'.$fdarr[$nextk]['dr'].'</td>';
			}
			else if($fdarr[$nextk]['cr'])
			{
				$output .='<td class="'.$cl.'">'.' '.'</td>';
				$output .='<td class="'.$cl.'">'.' '.'</td>';
			}
		}else{
			$output .='<td class="'.$cl.'">'.' '.'</td>';
			$output .='<td class="'.$cl.'">'.' '.'</td>';
		}
		$nil= '';
		$totalrepay+=$fdarr[$nextk]['dr'];
		
		if($counter%4 == 0)
		{
						
						
			$retdate = $calculationdate1;
			
			$timeDiff = abs($calculationdate1-$retdate);
			
			$years = floor($timeDiff / (365*60*60*24));
	
			
			$months = dateDiffByDays($disdate,$key) / 30;
	
			$defualt=($months * $emiamount)-$totalrepay;	
			$nil= 'nil';
			
			
			$output .='<td class="'.$cl.'">'.$defualt.'</td>';
			$output .='<td class="'.$cl.'">'.$nil.'</td>';
			
			$totalrepay=0;
			
			
		}else{
			$output .='<td class="'.$cl.'">&nbsp;</td>';
			$output .='<td class="'.$cl.'">&nbsp;</td>';
		}
		if($ldarr[$key]){
			$output .='<td class="'.$cl.'" align="right">'.$ldarr[$key]['amount'].'</td>';
			$output .='<td class="'.$cl.'">'.$ldarr[$key]['reason'].'</td>';
		}
		else{
		$output .='<td class="'.$cl.'">&nbsp;</td>';
		$output .='<td class="'.$cl.'">&nbsp;</td>';	
			
		}
		if($otherarr[$key]){
			$output .='<td class="'.$cl.'" align="right">'.$otherarr[$key]['amount'].'</td>';
			$output .='<td class="'.$cl.'">'.$otherarr[$key]['reason'].'</td>';
		}
		else{
		$output .='<td class="'.$cl.'">&nbsp;</td>';
		$output .='<td class="'.$cl.'">&nbsp;</td>';	
			
		}
		
		$output .= '</tr>';
	}
		
	//}
	//$output .= '</table>';
	
		
		/*}*/
				
	
				
				
			  /*  if($neshatcount+1 != $pdf->pagenumber1()){
				
				}else{
				   $neshat .='neshat';
				   $neshatcount =1;
				
				}
				$neshatcount++;	*/
		
 }

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
	 
	$pdf->Output('rti_report_'.time().'.pdf', 'I');
}

