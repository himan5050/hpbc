
<style type="text/css">
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 130px;
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
.maincoldate{margin-top:25px;}
</style>
<div id="rec_participant">
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
   <tr><td align="left" class="tdform-width"><fieldset><legend>FDR Details Report </legend>
   <table align="left" class="frmtbl">
	<tr><td width="5%">&nbsp;</td><td><b>FDR No.:</b></td><td ><?php print drupal_render($form['fdr_no']); ?></td><td><b>Interest Rate:</b></td><td><?php print drupal_render($form['interest_rate']); ?></td><td><b>Principal Amount:</b></td><td><?php print drupal_render($form['amount']); ?></td></tr><tr><td width="5%">&nbsp;</td><td><b>From Date:</b></td><td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td><td><b>To Date:</b></td><td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td><td align="right" colspan="2"><div style="margin-right:55px;"><?php print drupal_render($form); ?></div></td></tr>
  </table>
  	</fieldset></td></tr>
  </table></div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['fdr_no'] == '' && $_REQUEST['interest_rate'] == '' && $_REQUEST['amount'] == ''){
  form_set_error('form','Please enter any one of the search fields.');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
 // form_set_error('to_date','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
 // form_set_error('from_date','Please enter From Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] != '' &&($_REQUEST['to_date']['date'] < $_REQUEST['from_date']['date'])){

 // form_set_error('to_date','To Date should be greater than the From Date');


}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	//print $fromtime;exit
	$totime =date('Y-m-d',strtotime($to));
	$fdr_no = $_REQUEST['fdr_no'];
	$interest_rate = $_REQUEST['interest_rate'];
	$amount = $_REQUEST['amount'];

//echo $from;exit;
$append="";
	if($_REQUEST['fdr_no']){
	
	$append .= " fdr_no ='".$fdr_no."' OR fdr_no LIKE '%".$fdr_no."%' AND ";
	$pdfurl = $base_url."/fdrpdf.php?op=fdr_report&fromtime=$fromtime&totime=$totime&fdr_no=$fdr_no&interest_rate=$interest_rate&amount=$amount";
	
	}
	if($_REQUEST['interest_rate']){
	
	$append .= " interest_rate = '".$interest_rate."' AND ";
	$pdfurl = $base_url."/fdrpdf.php?op=fdr_report&fromtime=$fromtime&totime=$totime&fdr_no=$fdr_no&interest_rate=$interest_rate&amount=$amount";

	}
	if($_REQUEST['amount']){
	
	$append .= " amount = '".$amount."' AND ";
	$pdfurl = $base_url."/fdrpdf.php?op=fdr_report&fromtime=$fromtime&totime=$totime&fdr_no=$fdr_no&interest_rate=$interest_rate&amount=$amount";
	
	}
	//if(!empty($from)){
	;
	 if($fromtime !='1970-01-01' && $totime !='1970-01-01' ){
//echo $fromtime;exit
	$append .= " fdr_date BETWEEN '".$fromtime."' AND '".$totime."' AND ";
	

	$pdfurl = $base_url."/fdrpdf.php?op=fdr_report&fromtime=$fromtime&totime=$totime&fdr_no=$fdr_no&interest_rate=$interest_rate&amount=$amount";
	 }
	// }
  $append .= " 1=1 ";
  
  
  $sql="select * from tbl_fdr where $append";
  
 
  
  
  
  
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";



////////////


  
	$output = '<div class="listingpage_scrolltable"><table class="listingpage_scrolltable" >
	  <tr class=oddrow><td colspan=6><h2 style="text-align:left;">FDR Details Report</h2></td></tr>
	<tr>
	<td colspan="7">
<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" style="border-collapse: separate !important;">';
   $output .='<tr>
   				<th>S. No.</th>
				<th>FDR No.</th>
				<th>Bank Name</th>
                <th>Registration No.</th>
				<th>FDR Date</th>
				<th>Principal Amount</th>
				<th>Maturity Date</th>
				<th>Interest Accrued</th>
				<th> Interest Rate</th>
				<th> Maturity Amount</th>
				<th>FDR Type</th>
				<th>Status</th>
				</tr>';
				
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*10;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
      $counter++;

	  $reggg=db_query("select reg_number from tbl_loanee_detail where loanee_id='".$rs->account_no."'");
	  $regno=db_fetch_object($reggg);
$acc=getAccountNo($rs->account_no); if($acc){}else{$acc='N/A';}
$bankname=getBankName($rs->bank_name); if($bankname==''){$bankname='N/A';}
$regnoo=$regno->reg_number; if($regnoo==''){$regnoo='N/A';}
$prince=round($rs->amount,2); if($prince==''){$prince='N/A';}

	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="6%">'.$counter.'</td>
					   <td>'.$rs->fdr_no.'</td>
					    <td>'.$bankname.'</td>
					    <td>'.$regnoo.'</td>

					  <td width="10%" align="center">'.date('d-m-Y',strtotime($rs->fdr_date)).'</td>
					   <td align="right">'.round($prince).'</td>
					    <td width="10%" align="center">'.date('d-m-Y',strtotime($rs->maturity_date)).'</td>
						 <td align="right">'.$rs->interest_accrued.'</td>
					  <td align="right">'.$rs->interest_rate.'</td>
					  <td align="right">'.round($rs->maturity_amount).'</td>
					 
					  <td>'.ucwords(getLookupName($rs->fdr_type)).'</td>
					    <td>'.ucwords(getLookupName($rs->status1)).'</td>
	            </tr>';
   }
   
  if($counter > 0){
  
   $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
  }else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
}
}
?>