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
.form-item {margin:0; padding:0;}
.maincoldate{margin-top:12px;}
</style>

<div id="form-container">
  <table width="100%" style="border:none;" >
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>RTI Filled, Section Wise, Zone Wise</legend>
	
    <table align="left" class="frmtbl">
  <tr>
  
  <td><strong>District:</strong></td>
      <td><?php print drupal_render($form['district_id']); ?></td>
	  <td><strong>Section:</strong></td> 
	  <td><?php print drupal_render($form['section']); ?></td>
	  
      <td><strong>Complaint Type:</strong></td>
      <td><?php print drupal_render($form['type_complaint']); ?></td>            
  </tr>
  <tr>
  	  <td><strong>From Date:</strong></td>
	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td align="left"><strong>To Date:</strong></td>
      <td align="left"><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>
      <td align="left" colspan="2">&nbsp;</td>
  </tr>
  <tr>
  	  <td align="right" colspan="6"><div style="margin-right:73px;"><?php print drupal_render($form); ?></div></td>
  </tr>
  </table></fieldset>
  </td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  form_set_error('form','Please enter From Date');
}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime =  date('Y-m-d',strtotime('0 day',strtotime($from)));
	$totime = date('Y-m-d',strtotime('+1 day',strtotime($to)));
	$section = $_REQUEST['section'];
	$district_id = $_REQUEST['district_id'];
	$type_complaint = $_REQUEST['type_complaint'];
	
  
     $sql = "select count(*) as no, tbl_rti_management.section,tbl_rti_management.type_complaint,tbl_rti_management.district_id from tbl_rti_management 

	

where 1=1";


 
	



//$sql = "select 


$cond = '';	
	
	if($section){
		$cond .= " AND tbl_rti_management.section='$section'";
	}
	
	if($district_id){
		$cond .= " AND tbl_rti_management.district_id='$district_id'";
	}
	
	
	
	if($type_complaint){
		$cond .= " AND tbl_rti_management.type_complaint='$type_complaint' ";
	}
	
	
	
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_rti_management.datecurrent BETWEEN '$fromtime' AND '$totime') ";
	}else{
		if($from!=''){
			$cond .= " AND tbl_rti_management.datecurrent='$from' ";
		}
		if($to!=''){
			$cond .= " AND tbl_rti_management.datecurrent='$to' ";
		}
	}
	
	
	
	$cond .= " group by tbl_rti_management.district_id,tbl_rti_management.type_complaint,tbl_rti_management.section";
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*)  FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/complaintgeneratepdf.php?op=complaintreport&section=$section&district_id=$district_id&type_complaint=$type_complaint&from_date=$from&to_date=$to";
		
	
	
	
	$pdfurl1=$pdfurl;
   
   
   $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%" id="wrapper1">
	
	<tr class="oddrow"><td align="left" colspan="5"><h2 style="text-align:left">RTI Filled, Section Wise, Zone Wise</h2></td></tr>
	<tr>
	<td align="right" colspan="5">
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';

   $output .='<tr>
                <th><div style="width:35px;">S. No.</div></th>
               	<th>Section</th>
				<th>District</th>
				<th>Complaint Type</th>
				<th>No. of Complaint</th>
				
				
			 </tr>';
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}
	else{
	$counter = 0;
	}
	$z=1;
   while($rs = db_fetch_object($res)){
   	if($z%2==1){
     $cl ="odd";
	 }
	 else
	 {
	 $cl ="even";
	 }
     $counter++;
	 $sd= date('d-m-Y',strtotime($rs->datecurrent));
	 $dsd=substr($sd,0,10);
	 $sd1=$rs->date;
	 $dsd1=substr($sd1,0,10);
	 //$sdf=$rs->current_hearing_date;
	 $ert=substr($sdf,0,10); 
	 
	$z++;
	 
	if(getLookupName(ucwords($rs->section)))
	{
		$sec=ucwords(getLookupName($rs->section));
	}else{
		$sec='N/A';
	}
	
	if(getLookupName($rs->type_complaint))
	{
		$complaint=ucwords(getLookupName($rs->section));
	}else{
		$complaint='N/A';
	}
	
	  $output .='<tr class="'.$cl.'">
					  <td class="center">'.$counter.'</td>
					  <td align="left">'.$sec.'</td>
					  <td align="left">'.ucwords(getdistrict($rs->district_id)).'</td>
					  <td align="left">'.$complaint.'</td>
					  <td align="right">'.$rs->no.'</td>					  
	            </tr>';
				
   }
   
  if($counter > 0){
  
  $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
  }
  else if($fromtime >= $totime){
	  
	form_set_error('form','To Date should be more than From Date.');  
	  
  }
  
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 }
		
}
?>