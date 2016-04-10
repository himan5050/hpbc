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
.maincol #edit-Departmentid-wrapper select{width:89px;}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Transfer/Promotion Register</legend>
    
    <table align="left" class="frmtbl">
    <tr>
    	<td><strong>Status:</strong></td>
        <td><div class="maincol"><?php print drupal_render($form['status']); ?></div></td>
        <td><strong>Employee ID:</strong></td>
        <td><div class="maincol"><?php print drupal_render($form['employee_id']); ?></div></td>
        <td><strong>Employee Name:</strong></td>
        <td><div class="maincol"><?php print drupal_render($form['employee_name']); ?></div></td>
    </tr>
    <tr>
        <td><strong>Section Name:</strong></td>
        <td><div class="maincol"><?php print drupal_render($form['Departmentid']); ?></div></td>
        <td colspan="4" align="right"><div style="margin-right:66px;"><?php print drupal_render($form); ?></div></td>
    </tr>    
	</table>
	</fieldset></td></tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['status'] == '' && $_REQUEST['employee_id'] == '' && $_REQUEST['employee_name'] == '' && $_REQUEST['Departmentid'] == ''){
  form_set_error('form','Please enter any one of search field..');
}else {
	$employee_id= $_REQUEST['employee_id'];
	$employee_name = $_REQUEST['employee_name'];
	$section = $_REQUEST['Departmentid'];
	$status = $_REQUEST['status'];
	
	$append="";
	if($_REQUEST['status']){
	
	$append .= " LOWER(action)='".strtolower($status)."' AND ";
$pdfurl = $base_url."/transferPromotionReportpdf.php?op=transferPromotionReport&status=$status&Departmentid=$section&employee_id=$employee_id&employee_name=$employee_name";
	}
	if($_REQUEST['employee_id']){
	
	$append .= " employee_id LIKE '%". $employee_id ."%' AND ";
	
	$pdfurl = $base_url."/transferPromotionReportpdf.php?op=transferPromotionReport&status=$status&Departmentid=$section&employee_id=$employee_id&employee_name=$employee_name";

	}
	if($_REQUEST['employee_name']){
	
	//$append .= " LOWER(employee_name) LIKE '%".strtolower($employee_name)."%' AND ";
	$append .= " employee_name LIKE '%".$employee_name."%' AND ";
$pdfurl = $base_url."/transferPromotionReportpdf.php?op=transferPromotionReport&status=$status&Departmentid=$section&employee_id=$employee_id&employee_name=$employee_name";	}
	 if($_REQUEST['Departmentid']){
	
	$append .= " LOWER(current_Departmentid) LIKE '%".strtolower($section)."%' AND ";
$pdfurl = $base_url."/transferPromotionReportpdf.php?op=transferPromotionReport&status=$status&Departmentid=$section&employee_id=$employee_id&employee_name=$employee_name";	}
  
  $append .= " 1=1 ";
  
  
  $sql="select * from tbl_transferpromotions where $append";
  
 //echo $sql;
  
  
  
  
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);
//$res=db_query($sql);
 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">Transfer/Promotion Register</h2></td></tr>
	<tr>
	<td colspan="11" style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>

	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable">';
   $output .='<tr>
   				<th width="6%" align="center">S. No.</th>
				<th width="8%">Employee Name</th>
				<th width="8%">Employee Id</th>
				<th width="8%">Current Office</th>
				<th width="8%">Current Section</th>
				<th width="8%">Current Designation</th>
				<th width="8%">Previous Office</th>
				<th width="8%">Previous Section</th>
				<th width="8%">Previous Designation</th>
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
      
	 // echo $rs->employee_name;
	  if($counter%2==0){ $cl="even"; }else{ $cl="odd"; }
	  $output .='<tr class="'.$cl.'">
					  <td width="5%" align="center">'.$counter.'</td>
					  <td >'.ucwords($rs->employee_name).'</td>
					  <td>'.ucwords($rs->employee_id).'</td>
					  <td>'.ucwords(getCorporationName($rs->current_officeid)).'</td>
					  <td >'.ucwords(getLookupName($rs->current_Departmentid)).'</td>
					  <td>'.ucwords(getLookupName($rs->current_designationid)).'</td>
					   <td>'.ucwords($rs->prev_officeid).'</td>
					  <td >'.ucwords(getLookupName($rs->prev_Departmentid)).'</td>
					  <td>'.ucwords($rs->prev_designationid).'</td>
					  <td>'.ucwords(getLookupName($rs->action)).'</td>
					    
					 
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