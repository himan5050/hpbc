<style>
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}

.maincol input[type="text"] {
width: 100px;
height: 18px;
margin: 0;
padding: 2px;
vertical-align: middle;
font-family: sans-serif;
font-size: 14px;
border: #BCBCBC 1px solid;
}
.maincol label {
width: 100px;
float:left;
}

</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Help Desk Register</legend>
 
            <table align="left" class="frmtbl">
            <tr>
                <td><div class="maincol"><?php print drupal_render($form['from_date']); ?></div></td>
                <td><div class="maincol"><?php print drupal_render($form['to_date']); ?></div></td>
                <td><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td>
            </tr>
            </table>
	</fieldset>
    </td>    
    </tr>  
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' ){
  //form_set_error('form','Please enter the search field..');
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
	//$diary_no = $_REQUEST['diary_no'];
  if( $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']){

    $sql = "SELECT * FROM tbl_helpdesklogcomplaint where date_time between '".$fromtime."' AND  '".$totime."'";
	$pdfurl = $base_url."/HelpdeskReportpdf.php?op=HelpdeskReport&fromtime=$fromtime&totime=$totime";
  }
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class=oddrow><td colspan=6><h2 style="text-align:left;">Help Desk Register</h2></td></tr>
	<tr>
	<td colspan=11 style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>

	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" class="table-border">';
   $output .='<tr>
   				<th width="2%">S. No.</th>
				<th width="8%">Complaint Type</th>
				<th width="8%">Related To</th>
				<th width="12%">Details of the calls</th>
				<th width="8%">Assigned To</th>
				<th width="8%">Priority</th>
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
	//echo $diary_no;
	
 
   while($rs = db_fetch_object($res)){
      $counter++;
	//$type=getLookupName($rs->dispatch_type);
 //$mod=getLookupName($rs->mod);

//$address=db_query("select name from users where uid='".$rs->address_to."'");
//$address_to=db_fetch_object($address);
//$section=getLookupName($rs->sender_details);

$assign=db_query("select name from users where uid='".$rs->assign_to."'");
$assigned_to=db_fetch_object($assign);

// $output .='<tr> <td colspan="9">'.$opening. ' </td></tr>';
      $assigned=$assigned_to->name;

$i=1;
if($assigned==''){$assigned='N/A';}
   if($counter%2==0){ $class='even';}else{$class='odd';}
	  $output .='<tr class="'.$class.'">
					  <td class="center" width="2%">'.$counter.'</td>
					  <td align="left">'.getLookupName($rs->complaint_type).'</td>
					  <td>'.ucwords(getLookupName($rs->related_to)).'</td>
					  <td>'.ucwords($rs->details).'</td>
					  <td >'.ucwords($assigned).'</td>
					  <td>'.ucwords(getLookupName($rs->priority)).'</td>
					  <td>'.ucwords(getLookupName($rs->status2)).'</td>	
	            </tr>';
				$i++;
		}}
   
  if($counter > 0){
  
   $output .='</table>';
   echo $output .= theme('pager', NULL, 10, 0);
  }
 else if($_REQUEST['to_date']['date'] < $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']!='' && $_REQUEST['from_date']['date']!=''){
  
  //form_set_error('form','Please enter From date less then To Date.');
} 
  
  else{
  if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] != '')
    echo '<font color="red"><b>No Record found...</b></font>';
  }
}		

?>