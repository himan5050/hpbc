<style type="text/css">
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
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>District-Wise Scheme-Wise Cumulative Recovery Statement</legend>
	
    <table align="left" class="frmtbl">
    <tr><td width="5%">&nbsp;</td>
	 

  	  <td><b>From Date:<font color="#FF0000">*</font></b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><b>To Date:<font color="#FF0000">*</font></b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  <td align="right">
  <div><?php print drupal_render($form); ?></div></td><td width="5%">&nbsp;</td></tr>
  
  </table></fieldset>
  </td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date']==''){
  //form_set_error('form','Please enter any one of search field..');
}		
	
else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  //form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  //form_set_error('form','Please enter From Date');
}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime =  intval(strtotime("0 day", strtotime($from)));
	$totime = intval(strtotime("+1 day" ,strtotime($to)));
	
	$fromfdrdate=date("Y-m-d",strtotime($from));
	$tofdrdate=date("Y-m-d",strtotime($to));
	
	
	
    $sql = "SELECT tbl_loanee_detail.corp_branch,tbl_corporations.corporation_name,tbl_scheme_master.loan_scheme_id,tbl_scheme_master.scheme_name ,tbl_loan_detail.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.reg_number as regnumber ,tbl_loanee_detail.account_id,tbl_loan_repayment.createdon,sum(tbl_loan_repayment.amount) as repaymentamount
	 ,sum(tbl_loan_detail.emi_amount) as emiamount,tbl_scheme_master.main_scheme,tbl_schemenames.schemeName_id,tbl_schemenames.	schemeName_name as sche FROM tbl_loanee_detail inner join tbl_corporations on (tbl_corporations.corporation_id=tbl_loanee_detail.corp_branch) 
	 inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
	 inner join tbl_scheme_master on (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
	 inner join tbl_schemenames on (tbl_schemenames.schemeName_id=tbl_scheme_master.main_scheme)
	 inner join tbl_loan_repayment on (tbl_loan_repayment.loanee_id=tbl_loanee_detail.loanee_id)
	
	 

where 1=1";

//$sql = "select 


$cond = '';	

 
	
	
	
	
		
		
	
	
		
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_loan_repayment.createdon BETWEEN $fromtime AND $totime)";
	}else{
		if($from!=''){
			$cond .= " AND tbl_loan_repayment.createdon=$from";
		}
		if($to!=''){
			$cond .= " AND tbl_loan_repayment.createdon=$to";
		}
	}
	$cond .= ' group by tbl_schemenames.schemeName_name,tbl_loanee_detail.corp_branch';	
	
	
	
  
  $query = $sql . $cond;
  
  $query1 = db_query($query);
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/cumulativerecovery.php?op=cumulativerecovery";
   
	
				
		
	if($from){
		 $pdfurl.= "&from_date=$from";
	}
	
	if($to){
		 $pdfurl.= "&to_date=$to";
	}
	
	$pdfurl1=$pdfurl;
   
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%" id="form-container">
	
	<tr class=oddrow><td align="left" colspan=15 ><h2 style="text-align:left">District-Wise Scheme-Wise Cumulative Recovery Statement</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	

	</tr>
	';
   
   //$output .='';
   $output .='<tr>';
   $output .='<th align="center">S.No.</th>';
   $output .='<th>Name Of District Office</th>';
   $output .='<th>Target</th>';
	 
 //$sqlw=db_query("select scheme_name,loan_scheme_id from tbl_scheme_master"); 
 
 $schemes = array();
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
			$output .= "<th align='center'>$v</th>";
		}
				
		$output .= "<th align='center'>Total Received</th>";
		$output .= "<th align='center'>A/c Closed</th>";		
		$output .= "<th align='center'>%</th>";		
				
										
			 '</tr>';			
				
				
	 //$output .='';
   
	 
 //$sqlw=db_query("select scheme_name,loan_scheme_id from tbl_scheme_master"); 

				
				
				
				
										
						
													
		

			 
  
			 
			
	$limit=10;		 
			
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
	
	$closed = 0;
	$totalofrowtotal = 0;
  foreach($rec as $key => $val)
		{
			$counter++;
			
			$sql = "SELECT COUNT(*) as closed FROM tbl_loan_detail ld,tbl_loanee_detail l,tbl_corporations c WHERE ld.reg_number = l.reg_number AND ld.corp_branch = c.corporation_id  AND c.corporation_name = '".$key."' AND ld.loan_status = 0";
			//echo $sql;exit;
			$result = db_query($sql);
			$closedacc = db_fetch_object($result);
			
			
			
			
			if($counter % 2)
				$cl = 'odd';
			else
				$cl = 'even';
			$output .= "<tr class=$cl><td align='center'>$counter</td><td >$key</td><td align='right'>".round(abs($val[emi]))."</td>";
			$recovery1 = 0;
			$rowtotal = 0;
			foreach($schemes as $k => $v)
			{
				
				
				
				$coltotal[$v] = $coltotal[$v] + $val[$v];
				$rowtotal += $val[$v];
				
				$recovery = ($val[$v])?$val[$v]:"-";
				
				$recovery1 += round($val[$v]);
				$percentage=($recovery1/$val['emi']) * 100;
				//$toper += $percentage ;
				
				$output .= "<td align='right'>".round($recovery)."</td>";
			}
				
			$output .= "<td  align='right'>".round($recovery1)."</td>";
			$output .= "<td align='right'>".$closedacc->closed."</td>";	
			$output .= "<td  align='right'>".round($percentage,1).'%'."</td>";	
			$output .= "</tr>";
			$totalofrowtotal += $rowtotal;
			$totalofrowtotalval = round($totalofrowtotal);
			$totalofclosed += $closedacc->closed;
			$totalofrowtotalll += $val['emi'];	
		}//$cc += $amount;
		
	 $percentagetotal=round(($totalofrowtotal/$totalofrowtotalll) * 100,1);
		
	
		
	/* $sqlalr=db_query("SELECT tbl_loanee_detail.corp_branch,tbl_corporations.corporation_name,tbl_scheme_master.loan_scheme_id,tbl_scheme_master.scheme_name as sche,tbl_loan_detail.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.reg_number as regnumber ,tbl_loanee_detail.account_id,tbl_loan_repayment.createdon,sum(tbl_loan_repayment.amount) as totalamount 	
	FROM tbl_loanee_detail inner join tbl_corporations on (tbl_corporations.corporation_id=tbl_loanee_detail.corp_branch) 
	 inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
	 inner join tbl_scheme_master on (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
	 inner join tbl_loan_repayment on (tbl_loan_repayment.loanee_id=tbl_loanee_detail.loanee_id)
	 inner join alr on (alr.case_no=tbl_loanee_detail.account_id) group by tbl_loanee_detail.corp_branch
	 
	 
	 ");*/
	 
	 //echo "select sum(amount_recovered) as totalamount from alr where alr.date BETWEEN $fromtime AND $totime";exit;
	 
	$sqlalr=db_query("select sum(amount_recovered) as totalamount from alr where alr.date BETWEEN $fromtime AND $totime"); 
	 
	 $apalr=db_fetch_object($sqlalr);
	 
	 $sumalr=$apalr->totalamount;
	 
	//echo "select sum(maturity_amount) as maturityamount from tbl_fdr where tbl_fdr.maturity_date BETWEEN $fromfdrdate AND $tofdrdate
	 
	 
	 //";exit;
	 
	 $sqlfdr=db_query("SELECT sum(maturity_amount) as maturityamount from tbl_fdr where maturity_date BETWEEN '$fromfdrdate' AND '$tofdrdate'
	 
	 
	 ");
	 
	 $apfdr=db_fetch_object($sqlfdr);
	 
	    $sumfdr=$apfdr->maturityamount;
		$grandtotal=$totalofrowtotal+$sumalr+$sumfdr;
		$grandtotalnum=	round($grandtotal);	
		if($cl == 'oddrow')
		 $cl = 'evenrow';
		else
			$cl = 'oddrow';
		$output .= "<tr class=$cl><td>&nbsp;</td><td align='center'>Total</td><td align='center' align='right'>".round($totalofrowtotalll)."</td>";
		foreach($schemes as $k => $v)
		{
			$output .= "<td align='center' align='right'>".round(abs($coltotal[$v]))."</td>";
		}
		$output .= "<td  align='right'>$totalofrowtotalval</td><td align='right'>$totalofclosed</td><td align='right'>$percentagetotal%</td>";
	    
		  $output .= "<tr><td colspan='3'>";
		for($i=1;$i<count($schemes);$i++)
		{
			$output .= "<td>&nbsp;</td>";
		
		}
		
		 
		$output .= "<td align='right' class=$cl>ALR</td><td align='center' class=$cl>".round(abs($sumalr))."</td></tr>"; 
		  $output .= "<tr><td colspan='3'>";
		for($i=1;$i<count($schemes);$i++)
		{
			$output .= "<td>&nbsp;</td>";
		
		}
		
        $output .= "<td align='right' class=$cl>M.M.D</td><td align='center' class=$cl>".round(abs($sumfdr))."</td></tr>";
		 $output .= "<tr><td colspan='3'>";
		for($i=1;$i<count($schemes);$i++)
		{
			$output .= "<td>&nbsp;</td>";
		
		}
        $output .= "<td align='right' class=$cl><strong>Grand Total</strong></td><td align='center' class=$cl><strong>".round($grandtotalnum)."</strong></td></tr>"; 
		 
		
  
 if($counter > 0){
  
   $output .='</table></div>';
    echo $output .= theme('pager', NULL, 10, 0);
     //echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
  }
  else if($fromtime >= $totime)
  {
	form_set_error('from',"From Date should be greater than To Date.");  
	  
  }
  
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 	
}
	
 
	 
	 
	
}

		

?>