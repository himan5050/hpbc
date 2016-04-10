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
select{
	width:120px;}
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Loan Ledger Report</legend>
	
    <table align="left" class="frmtbl">
  <tr><td>&nbsp;</td>
	  <td align="left"><b>Scheme Name:</b></td>
	  <td><?php print drupal_render($form['scheme_name']); ?></td>
  	  <td><b>From Date:</b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><b>To Date:</b></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  
  </tr>
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
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['scheme_name'] == ''){
  form_set_error('form','Please enter any one of search field..');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  form_set_error('form','Please enter From Date');
}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime =  date('Y-m-d',strtotime($from));
	$totime = date('Y-m-d',strtotime($to));
	$scheme_name = $_REQUEST['scheme_name'];
	
	
  
     $sql = "SELECT tbl_scheme_master.scheme_name as schemename,tbl_loan_detail.scheme_name,tbl_loan_detail.reg_number,tbl_loanee_detail.loanee_id,sum(tbl_loan_detail.loan_requirement) as loanrequirement,tbl_interestsubsidy1.corp_reg_no,
	 sum(tbl_loan_detail.o_interest) as ointerest,sum(tbl_loan_detail.capital_subsidy) as capitalsubsidy ,sum(tbl_fdr.maturity_amount) as maturityamount,sum(tbl_fdr.amount) as amountt,sum(tbl_interestsubsidy1.interest_sub_due) as interestsubdue
FROM tbl_loanee_detail
left JOIN tbl_loan_detail ON ( tbl_loan_detail.reg_number = tbl_loanee_detail.reg_number )
left JOIN tbl_scheme_master ON ( tbl_loan_detail.scheme_name = tbl_scheme_master.loan_scheme_id )
left JOIN tbl_fdr ON (tbl_loanee_detail.loanee_id = tbl_fdr.account_no)
left JOIN tbl_interestsubsidy1 ON (tbl_interestsubsidy1.corp_reg_no = tbl_loanee_detail.reg_number)

WHERE 1=1";

//$sql = "select 


$cond = '';	
	
	if($scheme_name){
		$cond .= " AND tbl_loan_detail.scheme_name='$scheme_name'";
	}
		
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_loan_detail.sanction_date BETWEEN '$fromtime' AND '$totime') ";
	}else{
		if($from!=''){
			$cond .= " AND tbl_loan_detail.sanction_date='$from' ";
		}
		if($to!=''){
			$cond .= " AND tbl_loan_detail.sanction_date='$to' ";
		}
	}
	
$cond .= ' group by tbl_loan_detail.scheme_name';		
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
  $pdfurl = $base_url."/loanledgerreportpdf.php?op=loanledger_report";
   if($scheme_name){
		 $pdfurl.= "&scheme_name=$scheme_name";
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
	
	<tr class=oddrow><td align="left" colspan=15><h2 style="text-align:left">Loan Ledger</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   


   $output .='<tr>
                <th>S. No.</th>
                <th>Scheme Name</th>
   				<th>Total Loan Sanctioned</th>
				<th>Total interest Received</th>
				<th>Total Capital Subsidy</th>
				<th>Total Interest Subsidy</th>
				<th>Total MMD</th>
				<th>Total FDR</th>		
			 </tr>';
			 
	
	$limit=10;		
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
     $counter++;
	 //echo "select sum(cashipo+cashmo+cashcash) as sumamount, cashipo,cashmo,cashcash,ipono,currdatefield,currdatemo,currdatecash from tbl_rti_management GROUP BY nid";exit;
	
	 
	 //$sum = "select sum ";
	
	
	
	//echo "select sum(maturity_amount) as maturityamount,sum(amount) as fdramount from tbl_fdr where account_no = '".$rs->loanee_id."'";exit;
	 //echo "select sum(".$cond1.") as sumamount from tbl_rti_management where $conddate";exit;
	$sqlamount=db_query("select sum(maturity_amount) as maturityamount,sum(amount) as fdramount from tbl_fdr where account_no = '".$rs->loanee_id."'");
	
$sqlqu=db_fetch_object($sqlamount);

//echo $lo1 =$sqlqu->ipo;exit;

$maturityamount =$sqlqu->maturityamount;
$fdramount =$sqlqu->fdramount;
	
	
						
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
				$output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					   <td align="left">'.$rs->schemename.'</td>
					  <td align="left">'.$rs->loanrequirement.'</td>
					  <td align="left">'.$rs->ointerest.'</td>
					  <td>'.$rs->capitalsubsidy.'</td>
					  <td align="right">'.$rs->interestsubdue.'</td>
					  <td align="right">'.$rs->maturityamount.'</td>
					  <td align="right">'.$rs->amountt.'</td>
					  
					  
					  
					  
					  
	            </tr>';
				
				
				
				
				
				
				
		
	
   }
   
 
  
  if($counter > 0){
  
 $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
     //echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
  }
  else if($fromtime > $totime){
	  
	form_set_error('form','To Date should be greater than From Date.');  
	  
  }
  
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 }
		
}
?>