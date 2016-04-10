<style>
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}
#edit-date-to-datepicker-popup-0 {
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
#edit-date-from-datepicker-popup-0 {
    width:100px;}
	table{border:none;}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend><strong>ALR Report</strong></legend>
    
    <table align="left" class="frmtbl">
    <tr>
		<td><strong>From Date:</strong> <span style="color:#ff0000;"><strong>*</strong></span></td>
		<td><div class="maincoldate"><?php print drupal_render($form['date_from']); ?></div></td>
		<td><strong>To Date:</strong> <span style="color:#ff0000;"><strong>*</strong></span></td>
		<td><div class="maincoldate"><?php print drupal_render($form['date_to']); ?></div></td>
		<td><strong>Search Type:</strong> <span style="color:#ff0000;"><strong>*</strong></span></td>
		<td><?php print drupal_render($form['writ_level']); ?></td>
	</tr>
	<tr>
		
		<td colspan="6" align="right"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td>
	</tr>    
    
	</table>
	</fieldset></td></tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];

if($op == 'Generate Report'){
if($_REQUEST['date_from']['date'] != '' && $_REQUEST['date_to']['date'] != ''){
  
	$from = $_REQUEST['date_from']['date'];
	$to = $_REQUEST['date_to']['date'];
	$fromtime = strtotime($from);
	$totime =  strtotime($to);
	$writ_level = $_REQUEST['writ_level'];
$k=1;  
  if(strtotime($_REQUEST['date_from']['date']) > strtotime($_REQUEST['date_to']['date'])){
      $error = '<font color="red"><b>To Date Should be greater than From Date.</b></font>';
	  $k=0;
  }
  else if($_REQUEST['writ_level'] && $_REQUEST['date_from']['date'] && $_REQUEST['date_to']['date']){
    
	 $sql = "SELECT * FROM tbl_writ where (due_date BETWEEN ".$fromtime." AND ".$totime.") and status='".$_REQUEST['writ_level']."'";
	
  }
  else if($_REQUEST['date_from']['date'] && $_REQUEST['date_to']['date'] && $_REQUEST['writ_level'] == ''){
    $sql = "SELECT * FROM tbl_writ where (due_date BETWEEN ".$fromtime." AND ".$totime.")";
  }
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 

	$output = '<table cellspacing="2" cellpadding="1" border="0" width="100%" class="listingpage_scrolltable">
	<tr class="oddrow"><td><h2 style="text-align:left;">ALR Report</h2></td></tr></table>';
   
   //$output .='';
   $output .='<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" id="wrapper2" class="table-border">';
   $output .='<tr>
   				<th>S. No.</th>
				<th>Due Date</th>
				<th>Send Date</th>
				<th>Documents</th>
				<th>Account No.</th>
				<th>Status</th>
				
			 </tr>';
			//  $output .='<thead>'  '</thead>';
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
	//echo $fromtime;
	////dispatch total
	//echo $dispatch_no;
    
   while($rs = db_fetch_object($res)){
     // $m=1;
	  $counter++;
	 if($rs->documents){
	    $file = l('Download',$base_url.'/'.$rs->documents,array('attributes' => array('target'=>'_blank')));
	  }else{
	    $file ='NA';
	  }
      if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td align="center">'.$counter.'</td>
					  <td align="center">'.date('d-m-Y',$rs->due_date).'</td>
					  <td align="center">'.date('d-m-Y',$rs->current_time).'</td>
					  <td>'.$file.'</td>
					   <td align="right">'.$rs->account_number.'</td>
					    <td >'.ucwords($rs->status).'</td>
	            </tr>';
   }
   
  if($counter > 0){
  
   $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
  }else{
	  
	    $error = '<font color="red"><b>No Record found...</b></font>';
	
  }
  if($k == 0 && $counter == 0){
  echo '<font color="red"><b>To Date Should be greater than From Date.</b></font>';
}else if($k == 1 && $counter == 0 ){
   echo '<font color="red"><b>No Record found...</b></font>';
}
}

}
?>