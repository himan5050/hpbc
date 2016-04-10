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
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>In-coming Mails Register</legend>
    
    <table align="left" class="frmtbl">
    <tr><td width="5%">&nbsp;</td><td><b>Diary No.:</b></td><td><?php print drupal_render($form['diary_no']); ?></td><td><b>From Date:</b></td><td  ><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td><td><b>To Date:</b></td><td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td></tr><tr><td colspan="7" align="right"><div style="margin-right:80px;"><?php print drupal_render($form); ?></div></td></tr>    
    
	</table>
	</fieldset></td></tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['diary_no'] == ''){
  //form_set_error('form','Please enter any one of search field..');
}else if($_REQUEST['from_date']['date'] != '' && ($_REQUEST['to_date']['date']) == ''){
  //form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && ($_REQUEST['from_date']['date']) == ''){
 // form_set_error('from_date','Please enter From Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] != '' &&($_REQUEST['to_date']['date'] < $_REQUEST['from_date']['date'])){

 // form_set_error('form','To Date should be greater than the From Date');


}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
	$diary_no = $_REQUEST['diary_no'];
  if($_REQUEST['diary_no'] && $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']){
   
    $sql = "SELECT * FROM tbl_inboundmails where (date1 BETWEEN '".$fromtime."' AND '".$totime."') and LOWER(diary_no) LIKE '%".strtolower($_REQUEST['diary_no'])."%'";
	$pdfurl = $base_url."/inboundMailReportpdf.php?op=inboundMailReport&fromtime=$fromtime&totime=$totime&diary_no=$diary_no";
  }
  else if($_REQUEST['diary_no']){
    $sql = "SELECT * FROM tbl_inboundmails where LOWER(diary_no) LIKE '%".strtolower($_REQUEST['diary_no'])."%'";
	$pdfurl = $base_url."/inboundMailReportpdf.php?op=inboundMailReport&diary_no=$diary_no";
  }else{
    $sql = "SELECT * FROM tbl_inboundmails where (date1 BETWEEN '".$fromtime."' AND '".$totime."')";
	$pdfurl = $base_url."/inboundMailReportpdf.php?op=inboundMailReport&fromtime=$fromtime&totime=$totime";
  }
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">In-coming Mails Register</h2></td></tr>
	<tr>
	<td colspan="11" style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>

	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" class="table-border">';
   $output .='<tr>
   				<th width="5%">S. No.</th>
				<th width="8%">Diary No.</th>
				<th width="10%">Details of the person</th>
				<th width="8%">Subject</th>
				<th width="10%">Date</th>
				<th width="10%">Address To</th>
				<th width="10%" align="right">File No.</th>
				<th width="10%">Mode</th>
				<th width="10%">Entry By</th>
				<th width="10%">Assigned To</th>
				<th width="10%">Status</th>
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
 $mod=getLookupName($rs->mod);

$address=db_query("select username from tbl_joinings where LOWER(employee_id) LIKE '".strtolower($rs->address_to)."'");
$address_to=db_fetch_object($address);
//$section=getLookupName($rs->sender_details);

$assign=db_query("select username from tbl_joinings where LOWER(employee_id) LIKE '".strtolower($rs->assigned_to)."'");
$assigned_to=db_fetch_object($assign);

// $output .='<tr> <td colspan="9">'.$opening. ' </td></tr>';
      $status1=getLookupName($rs->status1);
	  if ($status1==''){
	  $status1="N/A";}
	  if($counter%2==0){$cl="even";}else{$cl="odd";}
	  
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="right" width="10%">'.$rs->diary_no.'</td>
					  <td width="8%"><div style="word-wrap: break-word; width:80px;">'.ucwords($rs->person_details).'</div></td>
					  <td width="10%">'.ucwords($rs->subject).'</td>
					 <td align="center" width="10%">'.date('d-m-Y',strtotime($rs->date1)).'</td>
						  <td>'.ucwords($address_to->username).'</td>
					  <td align="right" width="10%">'.$rs->file_no.'</td>
					     <td width="10%">'.ucwords($mod).'</td>
						  <td width="10%">'.ucwords($rs->entry_by).'</td>
					 
					
					  <td width="10%">'.ucwords($assigned_to->username).'</td>
					  <td width="10%">'.ucwords($status1).'</td>
					 
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