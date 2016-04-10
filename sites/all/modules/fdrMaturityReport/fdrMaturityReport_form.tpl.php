
<style type="text/css">
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}
select { width:130px; }
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
.maincoldate{margin-top:24px;}
</style>
<div id="rec_participant">
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
   <tr><td align="left" class="tdform-width"><fieldset><legend>FDR Due for Maturity</legend>
   <table align="left" class="frmtbl">
	<tr><td width="5%">&nbsp;</td><td><strong>Bank Name:</strong></td>
    <td><?php print drupal_render($form['bank_name']); ?></td>
    <td><strong>From Date:</strong></td>
    
    <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?> </div></td>
    <td><strong>To Date:</strong></td>
    <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>
    </tr>
    <tr><td colspan="7" align="right"><div style="margin-right:72px;"><?php print drupal_render($form); ?></div></td></tr>
  </table>
  	</fieldset></td></tr>
  </table></div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['bank_name'] == ''){
  form_set_error('form','Please enter any one of the search fields.');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  //form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  //form_set_error('form','Please enter From Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] != '' &&($_REQUEST['to_date']['date'] < $_REQUEST['from_date']['date'])){

  //form_set_error('form','To Date should be greater than the From Date');


}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
	$bank_name = $_REQUEST['bank_name'];
  if($_REQUEST['bank_name'] && $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']){
    $sql = "SELECT amount,fdr_no,fdr_date,interest_rate,maturity_amount,maturity_date,fdr_type,tbl_fdr.bank_name  FROM tbl_fdr INNER JOIN tbl_bank ON (tbl_bank.bank_id=tbl_fdr.bank_name) where (fdr_date BETWEEN '".$fromtime."' AND '".$totime."') and    (tbl_fdr.bank_name) LIKE '%".($_REQUEST['bank_name'])."%' and tbl_fdr.status1=230 ";
	$pdfurl = $base_url."/fdrMaturitypdf.php?op=fdrMaturity_report&fromtime=$fromtime&totime=$totime&bank_name=$bank_name";
  }
  else if($_REQUEST['bank_name']){
    $sql = "SELECT amount,fdr_no,fdr_date,interest_rate,maturity_amount,maturity_date,fdr_type,tbl_fdr.bank_name FROM tbl_fdr INNER JOIN tbl_bank ON (tbl_bank.bank_id=tbl_fdr.bank_name) where (tbl_fdr.bank_name) LIKE '%".($_REQUEST['bank_name'])."%' and tbl_fdr.status1=230 ";
	$pdfurl = $base_url."/fdrMaturitypdf.php?op=fdrMaturity_report&bank_name=$bank_name";
  }else{
    $sql = "SELECT amount,fdr_no,fdr_date,interest_rate,maturity_amount,maturity_date,fdr_type FROM tbl_fdr INNER JOIN tbl_bank ON (tbl_bank.bank_id=tbl_fdr.bank_name) where (fdr_date BETWEEN '".$fromtime."' AND '".$totime."') and tbl_fdr.status1=230";
	$pdfurl = $base_url."/fdrMaturitypdf.php?op=fdrMaturity_report&fromtime=$fromtime&totime=$totime";
  }
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	  <tr class=oddrow><td colspan=6><h2 style="text-align:left;">FDR Due for Maturity</h2></td></tr>
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
				<th>FDR Date</th>
				<th>Interest Rate</th>
				<th>Amount</th>
				<th>Maturity Date</th>
				<th>FDR Type</th>
				</tr>';
	$limit=10;			
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
      $counter++;
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					   <td>'.$rs->fdr_no.'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->fdr_date)).'</td>
					  <td align="right">'.$rs->interest_rate.'</td>
					  <td align="right">'.round($rs->amount).'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->maturity_date)).'</td>
					  <td>'.ucwords(getLookupName($rs->fdr_type)).'</td>
					  
	            </tr>';
   }
   
  if($counter > 0){
  
   $output .='</table>';
   echo $output .= theme('pager', NULL, 10, 0);
  }else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
}		
}

?>