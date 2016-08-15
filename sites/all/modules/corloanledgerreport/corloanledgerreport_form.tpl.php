<style>
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}

input[type="text"] {
width: 100px;
height: 18px;
margin: 0;
padding: 2px;
vertical-align: middle;
font-family: sans-serif;
font-size: 14px;
border: #BCBCBC 1px solid;
}

.maincoldate{margin-top:30px;}
select{width:120px;}
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr><td align="left" class="tdform-width"><fieldset><legend>Loan Ledger Report</legend>
	
    <table align="left" class="frmtbl">
  <tr><td>&nbsp;</td>
	  <td align="left"><b>Account No:<font color="#FF0000">*</font></b></td>
	  <td><?php print drupal_render($form['accountno']); ?></td>
  	   
  </tr>
  <tr>
 <tr><td colspan="7" align="right"><div style="margin-right: 55px;"><?php print drupal_render($form); ?></div></td></tr>
  
  </table></fieldset>
  </td>
    </tr>
  </table>
</div>


<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
if($_REQUEST['accountno'] == ''){
  //form_set_error('form','Please Enter Account No..');
}
else {
	
	$accountno = $_REQUEST['accountno'];
	$rfirst = true;
	
	
	
	
  
     $sql = "SELECT   tbl_loanee_detail.loanee_id,
	 tbl_loanee_detail.refno,
	 tbl_loanee_detail.account_id,
	 tbl_loanee_detail.fname,
	 tbl_loanee_detail.lname,
	 tbl_loanee_detail.fh_name,
	 tbl_loanee_detail.address1,
	 tbl_loanee_detail.address2,
	 tbl_loanee_detail.dob,
	 tbl_loanee_detail.caste,
	 tbl_loanee_detail.reg_number,
	 tbl_loan_detail.scheme_name,
	 tbl_loan_detail.project_cost,
	 tbl_scheme_master.loan_scheme_id,
	 tbl_scheme_master.scheme_name as schemename,
	 tbl_loan_detail.loan_requirement,
	 tbl_scheme_master.promoter_share,
	 tbl_scheme_master.apex_share,
	 tbl_scheme_master.corp_share,
	 tbl_loan_detail.work_place,
	 tbl_scheme_master.tenure,
	 tbl_loan_detail.emi_amount,
	 tbl_loan_detail.ROI,
	 tbl_loan_detail.o_principal
     
	 FROM tbl_loanee_detail
     INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number = tbl_loanee_detail.reg_number)
     INNER JOIN tbl_scheme_master ON (tbl_loan_detail.scheme_name = tbl_scheme_master.loan_scheme_id)
     WHERE 1=1";



//$sql = "select 


$cond = '';	
	
	if($accountno){
		$cond .= " AND tbl_loanee_detail.account_id='".$accountno."'";
	}
		
	//echo 'Account no is = '.$accountno;
	
	
//$cond .= ' group by tbl_loan_detail.scheme_name';		
	
  
 $query = $sql . $cond;
 

  
  $res1=db_query($query);
  
  $row=db_fetch_object($res1);
  $oprincipal=$row->o_principal;
  $projectcost=$row->project_cost;
  //echo 'Project Cost = '.$projectcost;
  $pro=$row->promoter_share;
  $apexshare=$row->apex_share;
  $corpshare=$row->corp_share;
  
  $loanesql=db_query("select sum(amount) as disamountid from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."'");
  $rowloane=db_fetch_object($loanesql);
  $sisamount= $rowloane->disamountid;
  
  
  $share=($projectcost * $pro) / 100;
  $apexsharenb=($projectcost * $apexshare) / 100;
  $corpsharehb=($projectcost * $corpshare) / 100;
  
  $loan_term=$sisamount - $share;
  //$bal=$oprincipal-$share;
  
  $emiamount = $row->emi_amount;
 
  $pdfurl = $base_url."/coloanledger.php?op=loanledgerreport";
   if($accountno){
		 $pdfurl.= "&accountno=$accountno";
	}
				
		

	
	$pdfurl1=$pdfurl;
   
   
   $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	
	$outputt = '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%" id="form-container">
	
	<tr class=oddrow><td align="left" colspan=15><h2 style="text-align:left">Loan Ledger</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
  if($row->account_id)
 { 
	 

if($row->refno)
   $outputt .='<tr class="odd"><td colspan="4"><b>Reference No.:</b>&nbsp;<span style="text-decoration:underline;">'.$row->refno.'</span></td></tr>';

   $outputt .='<tr class="even">
                <td><b>1)&nbsp;Account No.:</b>&nbsp;<span style="text-decoration:underline;">'.$row->account_id.'</span></td><td><b>2)&nbsp;Name Of Loanee:</b>&nbsp;<span style="text-decoration:underline;">'.$row->fname.' '.$row->lname.'</td><td><b>3)&nbsp;S/o D/o,W/o:</b>&nbsp;<span style="text-decoration:underline;">'.$row->fh_name.'</span></td><td><b>4)&nbspAddress:</b>&nbsp;<span style="text-decoration:underline;">'.$row->address1.' '.$row->address2.'</span></td></tr>
                <tr class="odd"><td>&nbsp;</td><td><b>D.O.B.:</b>&nbsp;<span style="text-decoration:underline;">'.date("d-m-Y",strtotime($row->dob)).'</span></td><td><b>Caste</b>&nbsp;<span style="text-decoration:underline;">'.getCastemain($row->caste).'</span></td><td><b>Scheme</b>&nbsp;<span style="text-decoration:underline;">'.$row->schemename.'</span></td></tr>
                 
				<tr class="even"><td><b>5)&nbsp;Sanction Order No.:</b>&nbsp;<span style="text-decoration:underline;">'.$row->reg_number.'</span></td><td><b>& Amount Of Sanction:</b>&nbsp;<span style="text-decoration:underline;">'.round($projectcost).'</span></td><td><b>NBCFDC:</b>&nbsp;<span style="text-decoration:underline;">'.round($apexsharenb).'</span></td><td><b>HBCFDC:</b>&nbsp;<span style="text-decoration:underline;">'.round($corpsharehb).'</span></td></tr>
				
				<tr class="odd"><td colspan="2">&nbsp;</td><td><b>Share:</b>&nbsp;<span style="text-decoration:underline;">'.round($row->promoter_share)."%".'</span></td><td><b>Share:</b>&nbsp;<span style="text-decoration:underline;">'. round($share).'</span></td></tr>
				
				<tr class="even"><td colspan="2">&nbsp;</td><td><b>Total Term Loan:</b>&nbsp;<span style="text-decoration:underline;">'.round($loan_term).'</span></td><td><b>Place Of Work:</b>&nbsp;<span style="text-decoration:underline;">'.$row->work_place.'</span></td></tr></table><br/><table><tr><th>Amount Released</th><th>Check.No</th><th>Date</th></tr>';
				//echo "select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."'";exit;
				$dissql=db_query("select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."'");
				$counter = 0;
				while($rowdis=db_fetch_object($dissql))
				{		
				$counter++;
					if($counter%2==0){$cl="even";}else{$cl="odd";}			
   				$outputt .='<tr class="'.$cl.'"><td align="right">'.round($rowdis->amount).'</td><td align="right">'.$rowdis->cheque_number.'</td><td align="center">'.date("d-m-Y",$rowdis->createdon).'</td></tr>';
				}
				  $outputt .='</table><br /><table><tr class="odd"><td><b>5)&nbsp;Number Of Monthly Instalments:</b>&nbsp;<span style="text-decoration:underline;">'.$row->tenure.'</span></td><td><b>Amount of Instalment:</b>&nbsp;<span style="text-decoration:underline;">'.round($row->emi_amount).'</span></td><td><b>Rate Of Interest:</b>&nbsp;<span style="text-decoration:underline;">'.$row->ROI.'</span></td></tr><br /><tr class="even"><td colspan="3"><b>7)&nbsp;Gurantor Adddress:</b></td>';
				 
				//echo "select * from tbl_guarantor_detail where loanee_id = '".$row->loanee_id."'";
				
				$sqlgran=db_query("select * from tbl_guarantor_detail where loanee_id = '".$row->loanee_id."'");
				while($asdgran=db_fetch_object($sqlgran)){
				
				//$darr[$asdgran->loanee_id] = $asdgran->address;
				
				
				$counter++;
					if($counter%2==0){$cl="even";}else{$cl="odd";}
									 
				 $outputt .= '<tr class="'.$cl.'"><td colspan="3"><span style="text-decoration:underline;">'.$asdgran->address.'</span></td></tr>';
				}
				echo $outputt .='</tr></table><br /><table>';


$count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = db_query($query);
			 
			 $output .='<tr>
                <th>Date</th>
                <th>PartiCulars</th>
   				<th>Dr.</th>
				<th>Cr.</th>
				<th>Balance</th>
				<th>Period of Retention of Principal</th>
				<th>Interest</th>
				<th>Amount of Default</th>
				<th>Period of Retention</th>
				<th>Amount of LD</th>
				<th>Remarks</th>
				<th>Amount of Other Charges</th>
				<th>Remarks</th>
				</tr>';
	
			if ($counter%2==0){$cl="even";}else{$cl="odd";} 
			
			
			 
			 
			 
			 
			 
			 
			
			//echo "select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."' order by createdon desc";exit;
			 $dissql=db_query("select * from tbl_loan_disbursement where loanee_id = '".$row->loanee_id."' order by createdon ");
			 $rowdis=db_fetch_object($dissql);
			 
			$disdate = date("Y-m-d", $rowdis->createdon);
			$psharesql = "SELECT SUM(amount) as pshare FROM tbl_loan_repayment WHERE loanee_id = '".$row->loanee_id."' AND payment_date = '".$disdate."' AND paytype = 'Promoter Share' GROUP BY loanee_id";
			//echo $psharesql;
			$pres = db_query($psharesql);
			$ps = db_fetch_object($pres);
			$pshare = ($ps->pshare)?round($ps->pshare):0;
			 
			 
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
		
		$bal = $sisamount;
		
if($bal < 0){
$ball=0;	
	
}
else{
	
$ball=round($bal);	
	
	
}
			 
			 $output .='<tr class="even">
					  <td align="center">'.date("d-m-Y",$rowdis->createdon).'</td>
					  <td align="left">'.'To Loan A/c'.'</td>
					  <td align="right">'.round($sisamount).'</td>
					  <td align="right">'.round($pshare).'</td>
					  <td align="right">'.$ball.'</td>
					  <td align="right"></td>
					  <td align="right">'.' '.'</td>
					  <td align="right">'.' '.'</td>
					  <td align="right">'.' '.'</td>
					  <td align="right">'.' '.'</td>
					  <td align="right">'.' '.'</td>
					  <td align="right">'.' '.'</td>
					  <td align="right">'.' '.'</td>
					  </tr>';
				
	}

else{
	
echo '<font color="red"><b>No Record found...</b></font>';	
}	
	
	$limit=10;		
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
	
   $intbal=0;
   while($rs = db_fetch_object($res)){
     $counter++;
	
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
	
	
	
	$sqllre=db_query("SELECT DISTINCT (
tbl_loan_repayment.id
), tbl_loan_repayment.payment_date, SUM(tbl_loan_repayment.amount) AS repamount
FROM tbl_loan_repayment
INNER JOIN tbl_loanee_detail ON ( tbl_loan_repayment.loanee_id = tbl_loanee_detail.loanee_id )
WHERE tbl_loanee_detail.account_id = '$accountno'
GROUP BY tbl_loan_repayment.payment_date");	
	
	while($resre=db_fetch_object($sqllre))
	{
		
		$repldarr[$resre->payment_date] = $resre->repamount;
	}
	
	//$rsinsdate=strtotime($resins->calculation_date);
	//$intamount=$resins->intamount;
	
	//print_r($intldarr);exit;
	
	//print_r($repldarr);exit;
	if($intldarr)
		$farr=array_merge($intldarr,$repldarr);
	else
		$farr=$repldarr;
	
	
	//print_r($farr);exit;
	ksort($farr);
	
	//print_r($farr);exit;
	//print_r($intldarr);exit;
	
	$sqlrep=db_query("SELECT tbl_loan_repayment.cheque_number AS recno, tbl_loan_repayment.payment_date, SUM(tbl_loan_repayment.amount) AS repamount
FROM tbl_loan_repayment
INNER JOIN tbl_loanee_detail ON ( tbl_loan_repayment.loanee_id = tbl_loanee_detail.loanee_id )
WHERE tbl_loanee_detail.account_id = '$accountno'
GROUP BY tbl_loan_repayment.payment_date");	
	while($r=db_fetch_object($sqlrep))
	{
		
		foreach($farr as $k => $vall)
		{
			
			if(!$intldarr[$k])
			{
				$fdarr[$r->payment_date]['cr']= $r->repamount;
				$fdarr[$r->payment_date]['id']= $r->recno;	
				$fdarr[$r->payment_date]['dr']=0;	
				
			}
			else{
				$fdarr[$k]['dr']=$intldarr[$k];	
				//echo $k." == ".$r->payment_date;exit;
				if($k == $r->payment_date){
					
					$fdarr[$r->payment_date]['cr']= $r->repamount;
					$fdarr[$r->payment_date]['id']= $r->recno;		
					
				}
				else{
					if(!$fdarr[$k]['cr'])
						$fdarr[$k]['cr']=0;		
				}
			
		}
		}
			//print_r($fdarr);exit;
		
		
		
		
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
	
	ksort($fdarr);
	$keyarr=array_keys($fdarr);
	//print_r($keyarr);exit;
	$counter = 0;
	$totalrepay = 0;
	
	$current_date = '';
	$previous_date = date("Y-m-d",$rowdis->createdon);
	//print_r($fdarr);exit;
	foreach($fdarr as $key => $v)
	{
		$counter++;
		if ($counter%2==0){$cl="even";}else{$cl="odd";} 
		$output .='<tr class="'.$cl.'">';
		$diffwith_disdate = strtotime($key) - strtotime(date("Y-m-d",$rowdis->createdon));
		if(($diffwith_disdate >= 0)){$current_date = $key;}
		else{$current_date = date("Y-m-d",$rowdis->createdon);}
		$nextk = '';
		$ordinal = array_search($key,$keyarr) + 1;
		if(isset($keyarr[$ordinal])){$nextk = $keyarr[$ordinal];}
		else{$nextk = $key;}
		//$output .= 'Current date = '.$current_date.' And Disbursement Date = '.date("Y-m-d",$rowdis->createdon);
		if(strtotime($current_date) == strtotime(date("Y-m-d",$rowdis->createdon))){
		    // $output .= 'Condition Satisfied';
		     $retention_time = abs(strtotime($nextk) - strtotime($current_date));
		     $retention_period = round(($retention_time / (60 * 60 * 24))) ;
			 //$retention_period++;
	    }else{
		     //$next_date = $fdarr[$nextk];
			 //$output .= 'Condition Not Satisfied';
		     $retention_time = abs(strtotime($nextk) - strtotime($current_date));
		     $retention_period = round(($retention_time / (60 * 60 * 24)));
		}
		
		$output .='<td align="center">'.date("d-m-Y",strtotime($current_date)).'</td>';
		//$output .= 'Current Date= '.$current_date.'and Next date= '.$nextk.'and retention= '.$retention_period;
		
		
		if($v['cr'])
		
		{
			$part='To Loan A/c and Reciept No.'.$v['id'];	
			
		}
		
		
		
		else if($v['dr'] && $v['dr'] != '-'){
			
			$part='To interest';		
		}else{
			$part='To LD and Other charges';	
		}
		
		
		
		$output .='<td align="left">'.$part.'</td>';
		
		
		
		//$output .='<td>';
		
		
		
		if($v['dr'] && $v['dr'] != '-')
		{
			$output .='<td align="right">'.round($v['dr']).'</td>';
			if($fdarr[$nextk]['cr'] && ($current_date != date("Y-m-d",$rowdis->createdon))){
			   $retention_period--;
			}
		}
		else{
			$output .='<td>'.'0 '.'</td>';	
		}
		if($v['cr'])
		{
			$output .='<td align="right">'.round($v['cr']).'</td>';
			
			if($fdarr[$nextk]['dr'] && ($current_date != date("Y-m-d",$rowdis->createdon))){
			   $retention_period = $retention_period + 1;
			}
			
		}
		else{
			$output .='<td align="right">'.'0 '.'</td>';	
		}
		$dramt = ($v['dr'] != '-')?$v['dr']:0;
		$bal =($bal + $dramt) - $v['cr'];
		
		if($bal < 0){
			
		$ball =0;	
		}else{
			
		//$ball =round(($bal + $dramt) - $v['cr'],2);
		$ball = $bal;
		}
		
		$output .='<td align="right">'.round($ball).'</td>';	
		
		if($v['dr'] && $v['dr'] != '-')
		{
			$current = $key;
			
			$ordinal = array_search($current,$keyarr) + 1;
			$nextk = $keyarr[$ordinal];
			if($fdarr[$nextk]['dr'])
			{
				$perret = dateDiffByDays($key,$nextk);
				$output .='<td>'.$retention_period.'</td>';
				$output .='<td>'.$fdarr[$nextk]['dr'].'</td>';
				
			}
			else if($fdarr[$nextk]['cr'])
			{
				$output .='<td>'.$retention_period.'</td>';
				$interest_paid = round((round($ball)*$retention_period*$row->ROI) / 36500);
				$output .='<td>'.$interest_paid.'</td>';
				
			}
		}else{
			$output .='<td>'.$retention_period.'</td>';
			$interest_paid = round((round($ball)*$retention_period*$row->ROI) / 36500);
			$output .='<td>'.$interest_paid.'</td>';
			
		}
		$nil= '';
		$totalrepay+=$fdarr[$nextk]['dr'];
		
		if($counter%4 == 0)
		{
						
						
			$retdate = $calculationdate1;
			
			$timeDiff = abs($calculationdate1-$retdate);
			
			$years = floor($timeDiff / (365*60*60*24));
	
			
			$months = dateDiffByDays($disdate,$key) / 30;
	
			$defualt= abs(($months * $emiamount)-$totalrepay);
			$nil= 'nil';
			
			
			$output .='<td>'.round($defualt,2).'</td>';
			$output .='<td>'.$nil.'</td>';
			
			$totalrepay=0;
			
			
		}else{
			$output .='<td>&nbsp;</td>';
			$output .='<td>&nbsp;</td>';
			//$output .='<td>&nbsp;</td>';
		}
		if($ldarr[$key]){
			$output .='<td align="right">'.round($ldarr[$key]['amount']).'</td>';
			$output .='<td>'.$ldarr[$key]['reason'].'</td>';
			
		}
		else{
		$output .='<td>&nbsp;</td>';
		$output .='<td>&nbsp;</td>';	
		
			
		}
		if($otherarr[$key]){
			$output .='<td align="right">'.round($otherarr[$key]['amount']).'</td>';
			$output .='<td>'.$otherarr[$key]['reason'].'</td>';
		}
		else{
		$output .='<td>&nbsp;</td>';
		$output .='<td>&nbsp;</td>';
		
			
		}
		
		
		$output .= '</tr>';
		$previous_date = $current_date;
	}
	//$output .= '</table>';
	
	
   }
   
  
 

 $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
     //echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
  
 }
		
}
?>