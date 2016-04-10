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
select{ width:120px; }
.maincoldate{margin-top:30px;}
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Loan Disbursement Report</legend>
	
    <table align="left" class="frmtbl">
    <tr><td>&nbsp;</td>
	  <td align="left"><b>District:</b></td>
      <td><div id="city"><?php print drupal_render($form['district_id']); ?></div></td>

  	  <td><b>From Date:</b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><b>To Date:</b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  
  </tr>
  <tr>
 <tr><td colspan="7" align="right"><div style="margin-right:85px;"><?php print drupal_render($form); ?></div></td></tr>
  
  </table></fieldset>
  </td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
    if($_REQUEST['district_id'] == '' && $_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '')
    {
        form_set_error('form','Please enter any one of search field..');
    }	
    else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
        form_set_error('form','Please enter To Date');
    }
    else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
        form_set_error('form','Please enter From Date');
    }
    else {
	    $from = $_REQUEST['from_date']['date'];
	    $to = $_REQUEST['to_date']['date'];
	    $fromtime =  strtotime("0 day", strtotime($from));
	    $totime = strtotime("+1 day" ,strtotime($to));
        $district_id = $_REQUEST['district_id'];
        
	    $sql = "SELECT tbl_scheme_master.scheme_name,
	                   tbl_loan_detail.reg_number,
		               tbl_loanee_detail.account_id,
		               tbl_loanee_detail.fname,
		               tbl_loanee_detail.lname,
		               tbl_loanee_detail.loanee_id,
		               tbl_loanee_detail.district,
	                   tbl_loanee_detail.tehsil,
	                   tbl_loanee_detail.address1,
	                   tbl_loanee_detail.address2,
	                   tbl_loan_detail.loan_requirement,
	                   tbl_loan_disbursement.createdon,
	                   tbl_loan_disbursement.cheque_number,
	                   tbl_loan_disbursement.amount
	            FROM   tbl_loanee_detail 
				inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     
                inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
                inner join tbl_loan_disbursement on (tbl_loan_disbursement.loanee_id=tbl_loanee_detail.loanee_id) 
                where 1=1 ";

$cond = '';	

 
	
	
	
	if($district_id){
	 $cond .= " AND tbl_loanee_detail.district='$district_id'";
	}

		
		
	
	
		
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_loan_disbursement.createdon BETWEEN '$fromtime' AND '$totime')";
	}else{
		if($from!=''){
			$cond .= " AND tbl_loan_disbursement.createdon='$from'";
		}
		if($to!=''){
			$cond .= " AND tbl_loan_disbursement.createdon='$to'";
		}
	}
	$cond .= 'order by tbl_loanee_detail.account_id';	
	
	$query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/disbursementpdf.php?op=disbursement_report";
   
	
	if($district_id){
		 $pdfurl.= "&district_id=$district_id";
	}			
		
	if($from){
		 $pdfurl.= "&from_date=$from";
	}
	
	if($to){
		 $pdfurl.= "&to_date=$to";
	}
	
	$pdfurl1=$pdfurl;
   
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%" id="form-container">
	
	<tr class=oddrow><td align="left" colspan=15 ><h2 style="text-align:left">Disbursement Report</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
			 
			 $output .='<tr>
                <th>S. No.</th>
                <th>District Name</th>
   				<th>Disburse Date</th>
				<th>Account No.</th>
				<th>Loanee Name</th>
				<th>Address</th>
				<th>Tehsil</th>
				<th>Scheme</th>
				<th>Loan Sanctioned Amount</th>
				<th>Disburse Amount</th>
				<th>Check Number/DD No.</th>
				<th>Balance Amount</th>
				
				
				
				
				
				
				
										
			 </tr>';
			 
	$limit=10;		
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
	
	$accountid ='';
   while($rs = db_fetch_object($res)){
     $counter++;
	 
	 $wer=$rs->loan_requirement;
	 $dis_amount=$rs->amount;
	 
	 //$accountid=$rs->account_id;
	 if(!$accountid)
	 {
		$accountid=$rs->account_id;
		 
		 
	 }
	 if($accountid !=$rs->account_id)
	
	 {
		 $accountid=$rs->account_id;
		 $ending_balance= $wer;
		 $balance_amount=$ending_balance-$dis_amount;
		// $ending_balance=$ending_balance; 
	 }
	 else{
	 
	 
	 if($ending_balance==0)
	 {
		 
		$ending_balance= $wer;
	 }
	 else{
		 
		$ending_balance =$balance_amount;
	 }
	 $balance_amount=$ending_balance-$dis_amount;
	 
	 }
	 
	 
	 
	// $balance_amount1= $balance_amount-$wer1;
	 
	 
	 /*$sqlh = "select * from tbl_loan_disbursement where loanee_id='".$rs->loanee_id."'";
	 $resh = db_query($sqlh);
	 while($rsh = db_fetch_object($resh)){
	  
	 
	  $amountdis=$rsh->amount;
	  $cheque_number=$rsh->cheque_number;
	
	
	 }*/
	 
		
	
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	 $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords(getdistrict($rs->district)).'</td>
					  <td align="center">'.date("d-m-Y",$rs->createdon).'</td>
					  <td align="left">'.$rs->account_id.'</td>
					  <td align="left">'.ucwords($rs->fname).' '.ucwords($rs->lname).'</td>
					  <td align="left">'.ucwords($rs->address1).' '.ucwords($rs->address2).'</td>
					  <td align="left">'.ucwords(gettehsil($rs->tehsil)).'</td>
					  <td align="left">'.ucwords($rs->scheme_name).'</td>
					  <td align="right">'.round($wer).'</td>
					  <td align="right">'.round($dis_amount).'</td>
					  <td align="right">'.$rs->cheque_number.'</td>
					  <td align="right">'.round($balance_amount).'</td>
			  
					  
					  
					  
	            </tr>';
				
			
	
				
				
				}
				
				//$cc += $amount;
				
		
	
 
   
 
  
 if($counter > 0){
  
  $output .='</table></div>';
    echo $output .= theme('pager', NULL, 10, 0);
     //echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
  }
  else if($fromtime >= $totime){
	  
	form_set_error('form','To Date should be more than From Date.');  
	  
  }
  
  
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
 }
 }
 
	 
	 
	
}

		

?>