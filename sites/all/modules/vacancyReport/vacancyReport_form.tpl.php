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

.maincol label{
width: 100px;
float:left;
vertical-align: middle;
}
.maincol #edit-from-date-wrapper label{
width: 100px;
float:left;
vertical-align: middle;
}

.maincoldate{margin-top:12px;}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr><td align="left" class="tdform-width"> <fieldset><legend>Vacancy list with Status Details</legend>
    <table align="left" class="frmtbl">
        <tr>
            <td><div class="maincol"><?php print drupal_render($form['status']); ?></div></td>
            <td><div class="maincol maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
            <td><div class="maincol maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>
        </tr>
        <tr>
            <td colspan="3" align="right"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td>
        </tr>       
	</table>
	</fieldset>
   </td> </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['status'] == ''){
  //form_set_error('form','Please enter any one of search field..');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  //form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  //form_set_error('form','Please enter From Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] != '' &&($_REQUEST['to_date']['date'] < $_REQUEST['from_date']['date'])){

 // form_set_error('form','To Date should be greater than the From Date');


}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
	$status = $_REQUEST['status'];
  if($_REQUEST['status'] && $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']){
   
    $sql = "SELECT * FROM tbl_inbounddeputation where (date_from BETWEEN '".$fromtime."' AND '".$totime."') and status2='".$_REQUEST['status']."'";
	$pdfurl = $base_url."/vacancyReportpdf.php?op=vacancyReport&fromtime=$fromtime&totime=$totime&status=$status";
  }
  else if($_REQUEST['status']){
    $sql = "SELECT * FROM tbl_inbounddeputation where status2='".$_REQUEST['status']."'";
	$pdfurl = $base_url."/vacancyReportpdf.php?op=vacancyReport&status=$status";
  }else{
    $sql = "SELECT * FROM tbl_inbounddeputation where (date_from BETWEEN '".$fromtime."' AND '".$totime."')";
	$pdfurl = $base_url."/vacancyReportpdf.php?op=vacancyReport&fromtime=$fromtime&totime=$totime";
  }
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">Vacancy list with Status Details</h2></td></tr>
	<tr>
	<td colspan="11" style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" class="table-border">';
   $output .='<tr>
   				<th width="6%">S. No.</th>
				<th width="8%">Vacancy Title</th>
				<th width="8%">Description</th>
				<th width="8%">Pay Detail</th>
				<th width="8%">Starting From</th>
				<th width="8%">Last Date of Application</th>
				<th width="8%">Status</th>
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
	//echo $status;
	
 
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
      if ($counter%2==0){$class="even";}else{$class="odd";}
	  $output .='<tr class="'.$class.'">
					  <td class="'.$class.'" width="5%">'.$counter.'</td>
					  <td >'.ucwords($rs->vacancy_title).'</td>
					  <td>'.ucwords($rs->job_description).'</td>
					  <td>'.ucwords($rs->pay_details).'</td>
					 <td align="center">'.date('d-m-Y',strtotime($rs->date_from)).'</td>
						   <td align="center">'.date('d-m-Y',strtotime($rs->date_last)).'</td>
					  <td >'.ucwords(getLookupName($rs->status2)).'</td>
					    
					 
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