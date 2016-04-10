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
.form-item label {
display: inline;
font-weight: bold;
float: left;
width: 110px;
margin-top: 5px;
}
.maincoldate{margin-top:30px;}
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Capital Subsidy Report</legend>
    <table align="left" class="frmtbl">
  <tr><td align="left"><div class="maincol"><?php print drupal_render($form['district']); ?></div></td>
     
  	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  
  </tr>
  <tr>
<td colspan="3" align="right"><div style="margin-right: 75px;"><?php print drupal_render($form); ?></div></td></tr>
  
  </table></fieldset>
  </td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];




if($op == 'Generate'){
		
	
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['district'] == ''){
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
	$fromtime = databaseDateFormat($from,'indian','-');
	$totime = databaseDateFormat($to,'indian','-');
	
	$district = $_REQUEST['district'];
	
	

	
	
	
	
	$sql = "SELECT ld.*,l.* FROM tbl_loan_detail ld LEFT JOIN tbl_loanee_detail l ON(ld.reg_number = l.reg_number) WHERE ld.capital_subsidy != '0.00' ";

//$sql = "select 

	
$cond = '';	

    
	
	
	
	if($district){
	 $cond .= " AND l.district='$district' ";
	}
	
		
	
	if($from!='' && $to!='' && $from!='01-01-1970' && $to!='01-01-1970'){
		 $cond .= " AND (ld.sanction_date BETWEEN '$fromtime' AND '$totime')";
	}else{
		if($from!=''){
			$cond .= " AND ld.sanction_date='$from' ";
		}
		if($to!=''){
			$cond .= " AND ld.sanction_date='$to' ";
		}
	}
  
  $query = $sql . $cond;  
 // echo $query;exit;
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/capitalsubsidyreportpdf.php?op=capitalsubsidyreport_report";
   
	
	if($district){
		 $pdfurl.= "&district=$district";
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
	
	<tr class="oddrow"><td align="left" colspan="15" ><h2 style="text-align:left">Capital Subsidy Report</h2></td></tr>
	<tr>
	<td align="right" colspan="15">
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   

			 
	
			 
			  $output .='<tr>
                <th>S. No.</th>
                <th>Scheme Name</th>
   				<th>Account No.</th>
				<th>Loanee Name</th>
				<th>Disburse Amount</th>
				
				<th>Loan Issue Bank </th>
				<th>Bank Branch </th>
				
				
				
				
				
				
				
										
			 </tr>';
			 
			
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*10;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
     $counter++;
	 
	 
	 
	 
	  $amountdis=0;
	   $sqlh = "select sum(amount) as amountdis from tbl_loan_disbursement where loanee_id='".$rs->loanee_id."' group by loanee_id";
	 //echo $sqlh;
	 $resh = db_query($sqlh);
	 if($rsh = db_fetch_object($resh)){
	  
	 	  $amountdis=$rsh->amountdis;
	
	
	 }

	 $bankdisburseamount = $rs->disbursed_amount;
	 $disbursamount = ($amountdis)?$amountdis:$bankdisburseamount;
		
	$bacc = ($rs->bank_acc_no)?$rs->bank_acc_no:'-';
	$bname = ($rs->bank)?ucwords(getBankName($rs->bank)):'-';
	$bbranch = ($rs->bank_branch)?ucwords(GetBankBranch($rs->bank_branch)):'-';
		
	
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.getscheme1($rs->scheme_name).'</td>
					  <td align="right">'.$bacc.'</td>
					  <td align="left">'.ucwords($rs->fname).' '.ucwords($rs->lname).'</td>
					  <td align="right">'.round(abs($disbursamount)).'</td>
					   
					 <td align="left">'.$bname.'</td>
					 <td align="left">'.$bbranch.'</td>
					 
					  
					  
					  
					  
	            </tr>';
				
			
	
				
				
				}
				
				//$cc += $amount;
				
		
	
 
   
 
  
  if($counter > 0){
  
  $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
     //echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
  }else{
		if($_REQUEST['from_date']['date'] <= $_REQUEST['to_date']['date'])
			echo '<font color="red"><b>No Record found...</b></font>';
  }
 	
}
	
 
	 
	 
	
}

		

?>