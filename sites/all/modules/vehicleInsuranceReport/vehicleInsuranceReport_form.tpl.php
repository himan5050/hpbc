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
  <tr><td align="left" class="tdform-width"><fieldset><legend>Vehicle Insurance Report</legend>
	 <table align="left" class="frmtbl">
    <tr>
      <td width="5%">&nbsp;</td><td><b>Reg. No.:</b></td><td><div class="maincol"><?php print drupal_render($form['reg_no']); ?></div></td><td><b>From Date:</b></td><td><div class="maincol maincoldate"><?php print drupal_render($form['from_date']); ?></div></td><td><b>To Date:</b></td><td><div class="maincol maincoldate"><?php print drupal_render($form['to_date']); ?></div></td></tr><tr><td colspan="7" align="right"><div style="margin-right:80px;"><?php print drupal_render($form); ?></div></td>
    </tr>
	</table>
    </fieldset></td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['reg_no'] == ''){
  form_set_error('form','Please enter any one of the search fields.');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
 // form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  //form_set_error('form','Please enter From Date');
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
    $sql = "SELECT tbl_vehicles.reg_no,date_insurance,date_to,policy_no,date_from,date_due,sum_insured,person_name,add_line1,add_line2,block,panchayat,pincode,tehsil_id,state_id,district_id FROM tbl_vehicleinsurance INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleinsurance.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."') and LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($_REQUEST['reg_no'])."%'";
	$pdfurl = $base_url."/vehicleInsurancepdf.php?op=vehicleInsurance_report&fromtime=$fromtime&totime=$totime&regi_no=$reg_no";
  }
  else if($_REQUEST['reg_no']){
    $sql = "SELECT tbl_vehicles.reg_no,date_insurance,date_to,policy_no,date_from,date_due,sum_insured,person_name,add_line1,add_line2,block,panchayat,pincode,tehsil_id,state_id,district_id FROM tbl_vehicleinsurance INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleinsurance.reg_no) where LOWER(tbl_vehicles.reg_no) LIKE '%".strtolower($_REQUEST['reg_no'])."%'";
	$pdfurl = $base_url."/vehicleInsurancepdf.php?op=vehicleInsurance_report&regi_no=$reg_no";
  }else{
    $sql = "SELECT tbl_vehicles.reg_no,date_insurance,date_to,policy_no,date_from,date_due,sum_insured,person_name,add_line1,add_line2,block,panchayat,pincode,tehsil_id,state_id,district_id FROM tbl_vehicleinsurance INNER JOIN tbl_vehicles ON (tbl_vehicles.vehicle_id=tbl_vehicleinsurance.reg_no) where (date_from BETWEEN '".$fromtime."' AND '".$totime."')";
	$pdfurl = $base_url."/vehicleInsurancepdf.php?op=vehicleInsurance_report&fromtime=$fromtime&totime=$totime";
  }
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class=oddrow><td align="left" colspan=11 ><h2 style="text-align:left">Vehicle Insurance Report</h2></td></tr>
	<tr>
	<td style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable">';
   $output .='<tr>
   				<th>S. No.</th>
				<th>Reg. No. of Vehicle</th>
				<th>Date of Insurance</th>
				
				<th>Start Date</th>
				<th>Valid Upto</th>
				<th>Due Date</th>
				<th>Policy No.</th>
				<th>Sum Insured</th>
				<th>Insurer Name</th>
				<th>Insurer Address</th>
				</tr>';
	$limit=10;			
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
      $counter++;
	
				$getstate1=db_query("select state_name from tbl_dsjestate where state_id='".$rs->state_id."'");
				$getstate=db_fetch_object($getstate1);
				$getdistrict1=db_query("select district_name from tbl_district where district_id='".$rs->district_id."'");
				$getdistrict=db_fetch_object($getdistrict1);
				$gettehsil1=db_query("select tehsil_name from tbl_tehsil where tehsil_id='".$rs->tehsil_id."'");
				$gettehsil=db_fetch_object($gettehsil1);
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  
					  <td>'.$rs->reg_no.'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->date_insurance)).'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->date_from)).'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->date_to)).'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->date_to)).'</td>
					   <td align="right">'.$rs->policy_no.'</td>
					   <td align="right">'.round($rs->sum_insured).'</td>
					   <td>'.ucwords($rs->person_name).'</td>
					  <td>'.ucwords($rs->add_line1).', '.ucwords($rs->add_line2).', '.ucwords($rs->panchayat).',  '.ucwords($rs->block).','.ucwords($gettehsil->tehsil_name).', '.ucwords($getdistrict->district_name).', '.ucwords($getstate->state_name).', '.$rs->pincode.'</td>
					  
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