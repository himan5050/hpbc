
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
.maincoldate{margin-top:12px;}
.maincol #edit-deputation-type-wrapper select{width:89px;}
.maincol #edit-section-wrapper select{width:89px;}
</style>
<div id="rec_participant">
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
   <tr><td align="left" class="tdform-width"><fieldset><legend>List of Employees on Deputation</legend>
   <table align="left" class="frmtbl">
	<tr>
    <td><strong></strong></td>
    <td><?php print drupal_render($form['deputation_type']); ?></td>
    <td><strong></strong></td>
    <td id="deputationitem"><?php print drupal_render($form['section']); ?></td>
    <td><?php print drupal_render($form); ?></td>
    </tr>
  </table>
  	</fieldset></td></tr>
  </table></div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){

if($_REQUEST['deputation_type'] == '' && $_REQUEST['section'] == '' ){
 // form_set_error('form','Please enter any one of the search fields.');
}
else {
	$deputation_type = $_REQUEST['deputation_type'];
	$section = $_REQUEST['section'];
	
  if($_REQUEST['deputation_type'] && $_REQUEST['section']){
  //inbound-203 outbound-204
  		if($deputation_type==203){
		
		
		
		$sql="select * from tbl_inbounddeputation where department LIKE '%".$section."%' and status2=95";
			$pdfurl = $base_url."/deputationReportpdf.php?op=deputationReport_report&deputation_type=$deputation_type&section=$section";
		
		
	
		}else{
		
	
		
			
		$sql="select * from tbl_outbounddeputation where LOWER(prev_Departmentid) LIKE '%".strtolower($section)."%' ";
$pdfurl = $base_url."/deputationReportpdf.php?op=deputationReport_report&deputation_type=$deputation_type&section=$section";

		}
		}else if($_REQUEST['section']==''){
  
  		if($deputation_type==203){
		
		
		
		$sql="select * from tbl_inbounddeputation where status2=95";
			$pdfurl = $base_url."/deputationReportpdf.php?op=deputationReport_report&deputation_type=$deputation_type&section=$section";

		}else{
		
		
$sql="select * from tbl_outbounddeputation ";
$pdfurl = $base_url."/deputationReportpdf.php?op=deputationReport_report&deputation_type=$deputation_type&section=$section";


  }
  
  }

   
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	  <tr class="oddrow"><td colspan="6"><h2 style="text-align:left;">List of Employees on Deputation</h2></td></tr>
	<tr>
	<td colspan="7">
<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	</table>';
   
   //$output .='';
   
  
   
   $output .='<table class="listingpage_scrolltable" style="border-collapse: separate !important;">';
   $output .='<tr>
   				<th>S. No.</th>
				<th>Employee Id</th>
				<th>Previous Section</th>
				<th>Previous Designation</th>
				<th>Contact No.</th>
				<th>Email Address</th>
				
				</tr>';
				$limit=10;
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
      $counter++;
	  $phone=$rs->phone; $mobile=$rs->mobile;
	  if($phone==''){$phone='N/A';} if($mobile==''){$mobile='N/A';}
	  if ($counter%2==0){$cl="even";}else{$cl="odd";}
	if($deputation_type==203){
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="6%">'.$counter.'</td>
					   <td>'.$rs->employee_id.'</td>
					  <td>'.ucwords($rs->department).'</td>
					  <td>'.ucwords($rs->designation).'</td>
					  <td >Phone:'.$phone.'Mobile'.$mobile.'</td>
					  <td >'.$rs->email.'</td>
										  
	            </tr>';
   }else{
   
     $output .='<tr class="'.$cl.'">
					  <td class="center" width="6%">'.$counter.'</td>
					   <td>'.$rs->employee_id.'</td>
					  <td>'.ucwords(getLookupName($rs->prev_Departmentid)).'</td>
					  <td>'.ucwords(getLookupName($rs->prev_designationid)).'</td>
					  <td >Phone:'.$phone.'Mobile'.$mobile.'</td>
					  <td >'.$rs->email.'</td>
										  
	            </tr>';
   
   }
   
   
   }
   
  if($counter > 0){
  
   $output .='</table>';
   echo $output .= theme('pager', NULL, 20, 0);
  }else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
}		
}

?>