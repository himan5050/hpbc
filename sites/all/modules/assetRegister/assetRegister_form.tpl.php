<style type="text/css">
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;

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

 <?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
	
//echo strtotime($_REQUEST['to_date']['date']) .'test'. strtotime($_REQUEST['from_date']['date']);
		if(strtotime($_REQUEST['to_date']['date']) < strtotime($_REQUEST['from_date']['date']) && ($_REQUEST['to_date']['date'])!=''){
//echo $_REQUEST['to_date']['date'];exit;
		  echo '<font color="red"><b>To Date should be greater than the From Date</b></font>';

		}
}
?>
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
   <tr><td align="left" class="tdform-width"><fieldset><legend>Asset Register </legend>
   <table width="226" align="left" class="frmtbl">
	<tr>
    	
      <td width="5%">&nbsp;</td><td><b>From Date</b> <span style="color:#F00">*</span></td><td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
       
        <td><b>To Date</b> <span style="color:#F00">*</span></td><td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>
        <td><div class="maincol"><?php print drupal_render($form); ?></div></td><td width="5%">&nbsp;</td>
    </tr>
   </table>
  	</fieldset>
    </td></tr>
  </table></div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
	
//echo strtotime($_REQUEST['to_date']['date']) .'test'. strtotime($_REQUEST['from_date']['date']);
if(strtotime($_REQUEST['to_date']['date']) < strtotime($_REQUEST['from_date']['date']) && !empty($_REQUEST['to_date']['date'])){

 // echo '<font color="red"><b>To Date should be greater than the From Date</b></font>';


}


else if($_REQUEST['to_date']['date'] !="" && $_REQUEST['from_date']['date'] !=""){
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
	

$append="";
	
	if($fromtime){
	 if($fromtime !='1970-01-01' && $totime !='1970-01-01' ){

	$append .= " date_amc BETWEEN '".$fromtime."' AND '".$totime."' AND ";
	

	$pdfurl = $base_url."/assetRegisterpdf.php?op=assetRegister_report&fromtime=$fromtime&totime=$totime";
	 }}
  $append .= " 1=1 ";
  
  
  $sql="select * from tbl_itassets where $append";
  
 
  
  
  
  
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";



////////////


  
	$output = '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0">
	  <tr class="oddrow"><td colspan="15"><h2 style="text-align:left;">Asset Register</h2></td></tr>
	<tr>
	<td colspan="15">
<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	';
   
   //$output .='';

   $output .='<tr>
   				<th>S. No.</th>
				<th>Section</th>
				<th>Asset Type</th>
				<th>Quantity</th>
				<th>Amount</th>
				<th>Procurement Cost</th>
				<th>Asset Details</th>
				<th>Insurance Company</th>
				<th>Sum Insured</th>
				<th>Date of Renewal</th>
				<th>Claim Details</th>
				<th>AMC Vendor Name</th>
				<th width="10%">AMC Date</th>
				<th>AMC Amount</th>
				<th>Contract Details</th>
				
				
				</tr>';
				
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*10;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
      $counter++;


$comp_name=ucwords($rs->company_name); if($comp_name==''){$comp_name='N/A';}
$sum_insured=($rs->sum_insured); if($sum_insured==''){$sum_insured='N/A';}
$date_renewal=date('d-m-Y',strtotime($rs->date_renewal)); if($date_renewal==''){$date_renewal='N/A';}
$claim_det=ucwords($rs->claim_details); if($claim_det==''){$claim_det='N/A';}
$vendor_name=ucwords($rs->vendor_name);if($vendor_name==''){$vendor_name='N/A';}
$date_amc=date('d-m-Y',strtotime($rs->date_amc)); if($date_amc==''){$date_amc='N/A';}
$amt_amc=$rs->amount_amc; if($amt_amc==''){$amt_amc='N/A';}
$contract_det=ucwords($rs->contract_details);if($contract_det==''){$contract_det='N/A';}

	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="6%">'.$counter.'</td>
					   <td>'.ucwords(getLookupName($rs->section)).'</td>
					    <td>'.ucwords(getLookupName($rs->asset_type)).'</td>
					    <td>'.ucwords($rs->quantity).'</td>
						 <td>'.round($rs->amount).'</td>
					<td>'.round($rs->proc_cost).'</td>
					  <td>'.ucwords($rs->asset_details).'</td>
					  
					  
					  
					    <td>'.($comp_name).'</td>
						  <td>'.round($sum_insured).'</td>
						    <td align="center">'.$date_renewal.'</td>
							  <td>'.($claim_det).'</td>
							    <td>'.($vendor_name).'</td>
									  <td>'.$date_amc.'</td>
					   <td align="right">'.round($amt_amc).'</td>
					   <td>'.$contract_det.'</td>
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