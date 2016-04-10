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
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>RTI Report</legend>
	
    <table align="left" class="frmtbl">
  <tr>
	  <td><strong>Section:</strong></td> 
	  <td><?php print drupal_render($form['section']); ?></td>
	  <td><strong>District:</strong></td>
      <td><?php print drupal_render($form['district_id']); ?></td>
      <td><strong>Status:</strong></td>
      <td><?php print drupal_render($form['status']); ?></td>            
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
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['section'] == '' && $_REQUEST['district_id'] == '' && $_REQUEST['status'] == '' ){
  form_set_error('form','Please enter any one of search field..');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
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
	$status = $_REQUEST['status'];
	
  
     $sql = "SELECT * FROM tbl_rti_management   

where 1=1";

//$sql = "select 


$cond = '';	
	
	if($section){
		$cond .= " AND tbl_rti_management.section='$section'";
	}
	
	if($district_id){
		$cond .= " AND tbl_rti_management.district_id='$district_id' ";
	}
	
	if($status){
		$cond .= " AND tbl_rti_management.rti_management_status='$status' ";
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
	
	
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/rtigeneratepdf.php?op=rti_report";
   if($section){
		 $pdfurl.= "&section=$section";
	}
	
	 if($district_id){
		 $pdfurl.= "&district_id=$district_id";
	}
	
	 if($status){
		 $pdfurl.= "&status=$status";
	}
		
		
	if($from){
		 $pdfurl.= "&from_date=$from";
	}
	
	if($to){
		 $pdfurl.= "&to_date=$to";
	}
	
	$pdfurl1=$pdfurl;
   
   
   $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table class="listingpage_scrolltable">
	
	<tr class="oddrow"><td align="left" colspan="15"><h2 style="text-align:left">RTI Report</h2></td></tr>
	<tr>
	<td align="right" colspan="15">
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   
   $sqlcl = db_query("SELECT * FROM tbl_rti_management where rti_management_status = 'Close'");
   $dfg=  db_fetch_object($sqlcl); 
   if($status == 'close')
   {



   $output .='<tr>
                <th><div style="width:35px;">S. No.</div></th>
                <th>Application No.</th>
   				<th>Section</th>
				<th>Application Type</th>
				<th>District</th>
				<th>Office</th>
				<th>Date</th>
				<th>Applicant Name</th>
				<th>Application Category</th>
				<th>Corrspondance Address</th>
				<th>Mobile No.</th>
				<th>Email Address</th>
				<th>Status</th>
				<th>Remarks/Action Taken</th>
				
			 </tr>';
   }
   
   else if($status == '')
   {



   $output .='<tr>
                <th><div style="width:35px;">S. No.</div></th>
                <th>Application No.</th>
   				<th>Section</th>
				<th>Application Type</th>
				<th>District</th>
				<th>Office</th>
				<th>Date</th>
				<th>Applicant Name</th>
				<th>Application Category</th>
				<th>Corrspondance Address</th>
				<th>Mobile No.</th>
				<th>Email Address</th>
				<th>Status</th>
				<th>Remarks/Action Taken</th>
				
			 </tr>';
   }
   
   
   else{
	   
	  $output .='<tr>
                <th><div style="width:35px;">S. No.</div></th>
                <th>Application No.</th>
   				<th>Section</th>
				<th>Application Type</th>
				<th>District</th>
				<th>Office</th>
				<th>Date</th>
				<th>Applicant Name</th>
				<th>Application Category</th>
				<th>Corrspondance Address</th>
				<th>Mobile No.</th>
				<th>Email Address</th>
				<th>Status</th>
				
				
			 </tr>';   
	   
	   
   }
   
   $limit=10;		 
  
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
	
	if($rs->correspondence_address)
	{
		$cross=$rs->correspondence_address;
	}else{
		$cross='N/A';
	}
	
	if($rs->remarks)
	{
		$remarks=$rs->remarks;
	}else{
		$remarks='N/A';
	}
	
	if($rs->rti_management_status=='Close')
	{
	 	
		
	
	 /*if($rs->status==0){
		       $st='Hearing';
		    }

             else if($rs->status==1){
			   $st ='Argument';
			}

            else if($rs->status==2){

               $st ='Pending For Decision';

               }

        else if($rs->status==3){

            $st ='Decision';

        }*/
	
	  $output .='<tr class="'.$cl.'">
					  <td class="center">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->section)).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
					   <td align="left">'.ucwords(getdistrict($rs->district_id)).'</td>
					  <td align="left">'.ucwords(getCorporationname($rs->office)).'</td>
					  <td align="right"><div style="width:65px;">'.$sd.'</div></td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="left">'.getLookupName($rs->application_category).'</td>
					  <td align="left">'.$cross.'</td>
					   <td align="right">'.$rs->mobile_number.'</td>
					  <td align="left">'.$rs->email_address.'</td>
					  <td align="left">'.$rs->rti_management_status.'</td>
					  <td align="left">'.$remarks.'</td> 
					 
					 
					 
	            </tr>';
	}
	
	else if($status == '')
	{
	$output .='<tr class="'.$cl.'">
					  <td class="center">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->section)).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
					    <td align="left">'.ucwords(getdistrict($rs->district_id)).'</td>
					  <td align="left">'.ucwords(getCorporationname($rs->office)).'</td>
					  <td align="right"><div style="width:65px;">'.$sd.'</div></td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="left">'.getLookupName($rs->application_category).'</td>
					  <td align="left">'.$cross.'</td>
					   <td align="right">'.$rs->mobile_number.'</td>
					  <td align="left">'.$rs->email_address.'</td>
					  <td align="left">'.$rs->rti_management_status.'</td>
					 <td align="left">'.$remarks.'</td> 
				
					 
					 
					 
	            </tr>';	
			
		
	}
	else{
	$output .='<tr class="'.$cl.'">
					  <td class="center">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->section)).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
					    <td align="left">'.ucwords(getdistrict($rs->district_id)).'</td>
					  <td align="left">'.ucwords(getCorporationname($rs->office)).'</td>
					  <td align="right"><div style="width:65px;">'.$sd.'</div></td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="left">'.getLookupName($rs->application_category).'</td>
					  <td align="left">'.$rs->correspondence_address.'</td>
					   <td align="right">'.$rs->mobile_number.'</td>
					  <td align="left">'.$rs->email_address.'</td>
					  <td align="left">'.$rs->rti_management_status.'</td>
					 
				
					 
					 
					 
	            </tr>';	
		
	}
				
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