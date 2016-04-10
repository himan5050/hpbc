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
.maincoldate{margin-top:25px;}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Ticket Received by Dispatch Section from Account</legend>
    
    <table align="left" class="frmtbl">
    <tr>
        <!--<td><strong>From Date: <span style="color:red;">*</span></strong></td>-->
        <td><b>From Date:</b></td><td><div class="maincol maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
        <!--<td><strong>To Date: <span style="color:red;">*</span></strong></td>-->
        <td><b>To Date:</b><td><div class="maincol maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>
        <td ><div><?php print drupal_render($form); ?></div></td>
    </tr>    
    
	</table>
	</fieldset></td></tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' ){
 // form_set_error('form','Please enter the search field..');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
 // form_set_error('form','Please enter To Date');
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
	//$diary_no = $_REQUEST['diary_no'];
  if( $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']){
   
    $sql = "SELECT * FROM tbl_dispatchaccounts where (date BETWEEN '".($fromtime)."' AND '".($totime)."')";
	$pdfurl = $base_url."/dispatchAccountReportpdf.php?op=dispatchAccountReport&fromtime=$fromtime&totime=$totime";
  }
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">Ticket Received by Dispatch Section from Account</h2></td></tr>
	<tr>
	<td colspan="11" style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>

	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" class="table-border">';
   $output .='<tr>
   				<th width="1%">S. No.</th>
				<th width="8%">Date</th>
				<th width="8%">Amount</th>
				<th width="12%">Remarks</th>
				
				
			 </tr>';

			
			//  $output .='<thead>'  '</thead>';
$limit=10;
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
	//echo $fromtime;
	////dispatch total
	//echo $diary_no;
	
 
   while($rs = db_fetch_object($res)){
      $counter++;
	//$type=getLookupName($rs->dispatch_type);
 //$mod=getLookupName($rs->mod);

//$address=db_query("select name from users where uid='".$rs->address_to."'");
//$address_to=db_fetch_object($address);
//$section=getLookupName($rs->sender_details);

//$assign=db_query("select name from users where uid='".$rs->assign_to."'");
//$assigned_to=db_fetch_object($assign);

// $output .='<tr> <td colspan="9">'.$opening. ' </td></tr>';
      $date=$rs->date;
	  $remark=$rs->remarks; if($remark==''){$remark='N/A';}
   if($counter%2==0){ $class='even';}else{$class='odd';}
	  $output .='<tr class="'.$class.'">
					  <td class="center" width="10">'.$counter.'</td>
					   <td align="center">'.date('d-m-Y',strtotime($date)).'</td>
					  <td align="right">'.round($rs->amount).'</td>
					  <td >'.ucwords($remark).'</td>
					   
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