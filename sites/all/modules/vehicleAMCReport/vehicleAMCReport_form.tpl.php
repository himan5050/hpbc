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
.maincoldate{margin-top:25px;}
</style>
<div id="rec_participant">
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
   <tr><td align="left" class="tdform-width"><fieldset><legend>Vehicle AMC Report</legend>
   <table align="left" class="frmtbl">
	<tr><td width="5%">&nbsp;</td><td><b>Reg. No.:</b></td><td><div class="maincol"><?php print drupal_render($form['reg_no']); ?></div></td><td><b>From Date:</b></td><td><div class="maincol maincoldate"><?php print drupal_render($form['from_date']); ?></div></td><td><b>To Date:</b></td><td><div class="maincol maincoldate"><?php print drupal_render($form['to_date']); ?></div></td></tr><tr><td colspan="7" align="right"><div style="margin-right:85px;"><?php print drupal_render($form); ?></div></td></tr>
  </table>
  	</fieldset></td></tr>
  </table></div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['reg_no'] == ''){
  form_set_error('form','Please enter any one of the search fields.');
}else if($_REQUEST['from_date']['date'] == '' && ( $_REQUEST['to_date']['date'])!=''){
//  form_set_error('form','Please enter From Date');
}
else if( $_REQUEST['to_date']['date'] == '' && ($_REQUEST['from_date']['date'])!=''){
  //form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] != '' &&(strtotime($_REQUEST['to_date']['date']) < strtotime($_REQUEST['from_date']['date']))){

  //form_set_error('form','To Date should be greater than the From Date');


}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
	$reg_no = $_REQUEST['reg_no'];
  if($_REQUEST['reg_no'] && $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']){
    $sql = "SELECT name_vendor,amc_details,date_from,date_valid,terms,tbl_vehicles.reg_no FROM tbl_vehicleamc INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleamc.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."') and    LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($_REQUEST['reg_no'])."%'  ";
	$pdfurl = $base_url."/vehicleAMCpdf.php?op=vehicleAMC_report&fromtime=$fromtime&totime=$totime&regi_no=$reg_no";
  }
  else if($_REQUEST['reg_no']){
    $sql = "SELECT name_vendor,amc_details,date_from,date_valid,terms,tbl_vehicles.reg_no FROM tbl_vehicleamc INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleamc.reg_no) where LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($_REQUEST['reg_no'])."%'  ";
	$pdfurl = $base_url."/vehicleAMCpdf.php?op=vehicleAMC_report&regi_no=$reg_no";
  }else{
    $sql = "SELECT name_vendor,amc_details,date_from,date_valid,terms,tbl_vehicles.reg_no FROM tbl_vehicleamc INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleamc.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."')";
	$pdfurl = $base_url."/vehicleAMCpdf.php?op=vehicleAMC_report&fromtime=$fromtime&totime=$totime";
  }
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	  <tr class=oddrow><td colspan=6><h2 style="text-align:left;">Vehicle AMC Register</h2></td></tr>
	<tr>
	<td colspan="7">
<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" style="border-collapse: separate !important;">';
   $output .='<tr>
   				<th>S. No.</th>
				<th>Registration Number of Vehicle</th>
				<th>Name of Vendor</th>
				<th>AMC Details</th>
				<th>Start Date</th>
				<th>Valid Upto</th>
				<th>Terms And Conditions</th>
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
					   <td>'.$rs->reg_no.'</td>
					  <td>'.ucwords($rs->name_vendor).'</td>
					  <td>'.ucwords($rs->amc_details).'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->date_from)).'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->date_valid)).'</td>
					  <td>'.ucwords($rs->terms).'</td>
					  
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