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
.maincoldate{margin-top:12px;}
</style>
<div id="rec_participant">
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
   <tr><td align="left" class="tdform-width"><fieldset><legend>Loanee Report</legend>
   <table align="left" class="frmtbl">
	<tr><td width="5%">&nbsp;</td><td><b>Account No.: </b><span title="This field is required." class="form-required">*</span></td><td><div class="maincol"><?php print drupal_render($form['acc_no']); ?></div></td><td align="right"><div><?php print drupal_render($form); ?></div></td><td width="5%">&nbsp;</td></tr>
  </table>
  	</fieldset>
  </table></div>

<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report' && $_REQUEST['acc_no'] != ''){
$acc=$_REQUEST['acc_no'];
if( $acc == ''){
  form_set_error('form','Please enter the search field.');
}
else {
	  $sql = "SELECT  tbl_loan_detail.work_place,
	                 tbl_loan_detail.loan_requirement,
					 tbl_loan_detail.reg_number,  
					 tbl_loanee_detail.account_id,
					 tbl_loanee_detail.fname,
					 tbl_loanee_detail.lname,
					 tbl_loanee_detail.fh_name,
					 tbl_loanee_detail.address1,
					 tbl_loanee_detail.address2,	
					 tbl_district.district_name,
					 tbl_tehsil.tehsil_name,
					 tbl_loan_disbursement.cheque_date,
					 SUM(tbl_loan_disbursement.amount) AS disbamount,
                     tbl_scheme_master.scheme_name,
                     tbl_guarantor_detail.gname,
                     tbl_guarantor_detail.address
            FROM tbl_loanee_detail 
	        INNER JOIN tbl_loan_detail ON (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number) 
   	        INNER JOIN tbl_scheme_master  ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
	        INNER JOIN tbl_loan_disbursement ON  (tbl_loanee_detail.loanee_id=tbl_loan_disbursement .loanee_id) 
	        left JOIN tbl_guarantor_detail ON  (tbl_loanee_detail.loanee_id=tbl_guarantor_detail.loanee_id) 
	        INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id) 
	        INNER JOIN tbl_tehsil  ON  (tbl_loanee_detail.tehsil=tbl_tehsil.tehsil_id) 
 	        where tbl_loanee_detail.account_id like '%".$acc."%' ";
			
	$pdfurl = $base_url."/LoaneeDetailsReportpdf.php?op=LoaneeDetailsReport&acc_no=$acc";
  }
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
  $res = pager_query($sql, 10, 0, $count_query);
  $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";

  $output = '<table style="width:866px;">
	  <tr class=oddrow><td colspan=6><h2 style="text-align:left;">Loanee Report</h2></td></tr>
      <tr>
	  <td colspan=""> <a target="_blank" href="'.$pdfurl.'">
	  <img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	  </tr>
	  </table>';
   
   //$output .='';
   
   $output .='<div class="listingpage_scrolltable"><table>';
   
   $output .='<tr>
                 <th>S. No.</th>
   				<th>Account No.</th>
				<th>Loanee Name</th>
				<th>Guardian Name</th>
				<th>Address</th>
				<th>District</th>
				<th>Tehsil</th>
				<th>Schemes</th>
				<th>Loan Amount Disbursed</th>
				<th>Disbursement Date</th>
				<th>Gurantor Name</th>
				<th>Gurantor Address</th>
				<th>Business Place</th>
				
				</tr>';
				
				 
	
	$limit=10;			
   if($_REQUEST['page']){
	   $counter = $_REQUEST['page']*$limit;
	}else{
	   $counter = 0;
	}
   
   while($rs = db_fetch_object($res)){
      $counter++;
	  if($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					   <td>'.$rs->account_id.'</td>
					  <td>'.ucwords($rs->fname).' '.ucwords($rs->lname).'</td>
					  <td>'.ucwords($rs->fh_name).'</td>
					   <td>'.ucwords($rs->address1.$rs->address2).'</td>
					  <td>'.ucwords($rs->district_name).'</td>
					   <td>'.ucwords($rs->tehsil_name).'</td>
					  <td>'.ucwords($rs->scheme_name).'</td>
					   <td align="right">'.round($rs->disbamount).'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->cheque_date)).'</td>
					   <td>'.ucwords($rs->gname).'</td>
					  <td>'.ucwords($rs->address).'</td>
					  <td>'.ucwords($rs->work_place).'</td>
					  
					 
	            </tr>';
   }
   
  if($counter > 0){
  
   $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
  }else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
}		


?>