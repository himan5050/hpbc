<style>
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}
select{ width:120px; }
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
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>Court Case Report</legend>
	
    <table align="left" class="frmtbl">
  <tr>
	<td width="5%">&nbsp;</td><td align="left" class="tdform-width"><b>Case no.</b></td><td><?php print drupal_render($form['case_no']); ?></td>
	  <td><b>Court Name:</b></td><td><?php print drupal_render($form['court_name_name']); ?></td>
	  <td><b>Loan Account No.:</b></td><td><?php print drupal_render($form['loan_account']); ?></td>
 <td width="5%">&nbsp;</td> </tr>
  <tr>
	<td width="5%">&nbsp;</td>  <td><b>Lawyer Name:</b></td> <td><?php print drupal_render($form['lawyer_name']); ?></td>
	  <td><b>From Date:</b></td> <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	   <td><b>To Date:</b></td><td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td><td width="5%">&nbsp;</td>
</tr>	
 <tr><td colspan="8" align="right"><div style="margin-right: 88px;"><?php print drupal_render($form); ?></div></td>
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
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['case_no'] == '' && $_REQUEST['court_name_name'] == '' && $_REQUEST['loan_account'] == '' && $_REQUEST['lawyer_name'] == ''){
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
	$fromtime =  date('Y-m-d',strtotime($from));
	$totime = date('Y-m-d',strtotime($to));
	$case_no = $_REQUEST['case_no'];
	$court_name_name = $_REQUEST['court_name_name'];
	$loan_account = $_REQUEST['loan_account'];
	$lawyer_name = $_REQUEST['lawyer_name'];
  
     $sql = "SELECT tbl_courtcasehearing.courtcase_id,tbl_courtcasehearing.court_name_id,tbl_courtcasehearing.	hearing_date,tbl_courtcasehearing.date1,tbl_courtcasehearing.loan_account,tbl_courtcasehearing.lawyer_id,tbl_courtcasehearing.court_states,tbl_court_names.court_name_name,tbl_lawyer.lawyer_name,tbl_courtcasehearing.title_case,tbl_courtcasehearing.case_detail,tbl_courtcasehearing.fee_charge FROM tbl_courtcasehearing  
INNER JOIN tbl_court_names ON(tbl_courtcasehearing.court_name_id=tbl_court_names.court_name_id)
 INNER JOIN tbl_lawyer ON(tbl_courtcasehearing.lawyer_id=tbl_lawyer.lawyer_id) where 1=1";

//$sql = "select 


$cond = '';	
	
	if($case_no){
		$cond .= " AND tbl_courtcasehearing.courtcase_id ='$case_no'";
	}
	
	if($court_name_name){
		$cond .= " AND tbl_courtcasehearing.court_name_id = '$court_name_name' ";
	}
	
	if($lawyer_name){
		$cond .= " AND UCASE(tbl_lawyer.lawyer_name) LIKE '%".strtoupper($lawyer_name)."%' ";
	}
	
	
	if($loan_account){
		$cond .= " AND tbl_courtcasehearing.loan_account ='$loan_account' ";
	}
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_courtcasehearing.date1 BETWEEN '$fromtime' AND '$totime') ";
	}else{
		if($from!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$from' ";
		}
		if($to!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$to' ";
		}
	}
	
	
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/courtgeneratepdf.php?op=courtcase_report";
   if($case_no){
		 $pdfurl.= "&case_no=$case_no";
	}
	
	 if($court_name_name){
		 $pdfurl.= "&court_name_name=$court_name_name";
	}
	
	 if($lawyer_name){
		 $pdfurl.= "&lawyer_name=$lawyer_name";
	}
	
	
	 if($loan_account){
		 $pdfurl.= "&loan_account=$loan_account";
	}
	
	
	if($from){
		 $pdfurl.= "&from_date=$from";
	}
	
	if($to){
		 $pdfurl.= "&to_date=$to";
	}
	
	$pdfurl1=$pdfurl;
   
   
   $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%" id="form-container">
	
	<tr class=oddrow><td align="left" colspan="11"><h2 style="text-align:left">Court Case Register</h2></td></tr>
	<tr>
	<td colspan="11">
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
 
   $output .='<tr>
   				<th>S. No.</th>
				<th>Case No.</th>
				<th>Court Name</th>
				<th>Case Title</th>
				<th>Case Detail</th>
				<th>Lawyer Name</th>
				<th>Loan Account</th>
				<th>Hearing Date</th>
				<th>Fee Detail</th>
				<th>Status</th>
				<th>Hearing Action Comment</th>
				
			 </tr>';
	$limit=10;		 
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
      $counter++;
	 $sd=$rs->hearing_date;
	 $dsd=substr($sd,0,10);
	 $sd1=$rs->date;
	 $dsd1=substr($sd1,0,10);
	 //$sdf=$rs->current_hearing_date;
	 $ert=substr($sdf,0,10); 
	 
	 $hearingdate ="";
	 
	 
	   
	  $sqlh = "select tbl_courtcase.hearing_date,tbl_courtcase.status,tbl_courtcase.current_hearing_date,tbl_courtcasehearing.court_name_id,tbl_lawyer.lawyer_name from tbl_courtcase inner join tbl_courtcasehearing on (tbl_courtcasehearing.courtcase_id=tbl_courtcase.case_no) INNER JOIN tbl_lawyer ON (tbl_courtcasehearing.lawyer_id=tbl_lawyer.lawyer_id) where 1=1";
	 
	 
	 
	  
	  $cond = " and tbl_courtcase.case_no ='".$rs->courtcase_id."'";	
	
	if($case_no){
		$cond .= " AND tbl_courtcasehearing.courtcase_id ='$case_no'";
	}
	
	if($court_name_name){
		$cond .= " AND tbl_courtcasehearing.court_name_id = '$court_name_name' ";
	}
	
	if($lawyer_name){
		$cond .= " AND UCASE(tbl_lawyer.lawyer_name) LIKE '%".strtoupper($lawyer_name)."%' ";
	}
	
	
	if($loan_account){
		$cond .= " AND tbl_courtcasehearing.loan_account ='$loan_account' ";
	}
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_courtcasehearing.date1 BETWEEN '$fromtime' AND '$totime') ";
	}else{
		if($from!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$from' ";
		}
		if($to!=''){
			$cond .= " AND tbl_courtcasehearing.date1='$to' ";
		}
	}
	
	
	
  
 $query1 = $sqlh . $cond;
  
 
	  
	  
	  
	 $resh = db_query($query1);
	 $comment="";
	 $hearingdate ="";
	 while($rsh = db_fetch_object($resh)){
	  $hearingdate .= date('d-m-Y',strtotime($rsh->hearing_date)).'<br />';
	 $comment .=$rsh->current_hearing_date.'<br />';
	   
	   
	   
	   
	   //$statust .=ucwords($rsh->status).'<br />';
	   
	    
	   
	   
	   
	  /* if($rsh->status=='hearing')
	   
	   {
	   $statust='Hearing';
	   
	   }
	   
	   else if($rsh->status=='argument')
	   {
	   
	    $statust='Argument';
	   
	   
	   }
	   
	   else if($rsh->status=='decision')
	   {
	   
	    $statust='Decision';
	   
	   
	   }*/
	  
	 }
	 
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
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->courtcase_id).'</td>
					  <td align="left">'.ucwords($rs->court_name_name).'</td>
					  <td align="left">'.ucwords($rs->title_case).'</td>
					  <td align="left">'.ucwords($rs->case_detail).'</td>
					  
					  <td>'.ucwords($rs->lawyer_name).'</td>
					  <td align="right">'.$rs->loan_account.'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->hearing_date)).'</td>
					  <td align="right">'.round($rs->fee_charge).'</td>
					  <td align="left">'.getLookupName($rs->court_states).'</td>
					  <td align="left">'.ucwords($comment).'</td>
					  
	            </tr>';
   }
   
  if($counter > 0){
  
    $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
  }
  else if($fromtime > $totime){
	  
	form_set_error('form','To Date should be greater than From Date.');  
	  
  }
  
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 }
		
}
?>