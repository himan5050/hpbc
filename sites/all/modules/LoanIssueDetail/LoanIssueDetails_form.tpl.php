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
.maincol select{
	width: 100px;
	}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="2" cellspacing="0" border="0" id="wrapper">
  <tr><td align="left" class="tdform-width"><fieldset><legend>Loan Issue Detail Report</legend>
	 <table align="left" class="frmtbl">
    <tr>
      <td width="5%">&nbsp;</td><td><b>District:</b></td><td align="left"><div class="maincol"><?php print drupal_render($form['district']); ?></div></td><td><b>Sector:</b></td><td><div class="maincol"><?php error_reporting(0); print drupal_render($form['sector9']); ?></div></td><td><b>Scheme:</b></td><td><div class="maincol" id="sector"><?php print drupal_render($form['scheme4']); ?></div>
	  </td></tr><tr> <td width="5%">&nbsp;</td><td><b>Account:</b></td><td><div class="maincol"><?php print drupal_render($form['account']); ?></div></td><td colspan="5" align="right">
	 <div style="margin-right:90px;"><?php print drupal_render($form); ?></div></td>
    </tr>
	</table></fieldset>
    </td>
    </tr>
  </table>
</div>
<?php

global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){

 $district=$_REQUEST['district'];
 $sector=$_REQUEST['sector9'];
 $scheme=$_REQUEST['scheme4'];
 $account=$_REQUEST['account'];

//print_r($_REQUEST);exit;
//drupal_set_message($sector.$scheme);
//$val = '%'.strtoupper($district).'%'; $key=addslashes($val);
if($district == '' && $sector == '' && $account=='' )
{
  form_set_error('form','Please enter the district or sector .');
     
}else{
	
 if($district && (empty($sector ))&& (empty($scheme)) && (empty($account)) ){
   $cond = 'and tbl_district.district_name Like "'. $district.'"';
    $_REQUEST['page']=0;
}
else if((empty($district))&& $sector && (empty($scheme)) && (empty($account)) ){
	 $_REQUEST['page']=0;

 $cond = "and tbl_sectors.sector_id='".$sector."' OR tbl_scheme_master.loan_scheme_id='".$scheme."' ";
}
else if((empty($district)) && (empty($sector)) && (empty($scheme)) && $account ){
  $_REQUEST['page']=0;
  //$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'"';
 $cond = "and tbl_loanee_detail.account_id Like'".$account."'";
}
else if($district  && $sector && (empty($scheme)) &&  (empty($account))  ){
  $cond = "and tbl_sectors.sector_id Like '".$sector."' AND tbl_district.district_name Like '". $district."'  ";
   $_REQUEST['page']=0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';

}else if($district &&  $account   && (empty($sector)) && (empty($scheme))  ){
   $cond = " and tbl_district.district_name Like '". $district."' and tbl_loanee_detail.account_id = '".$account."'";
   $_REQUEST['page']=0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';

}else if((empty($district)) &&  $account   && $sector && (empty($scheme))  ){
  $cond = "and tbl_loanee_detail.account_id Like'".$account."' and tbl_sectors.sector_id Like '".$sector."'";
  $_REQUEST['page']=0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';

}
else if($district  && $sector && $scheme && $account){
  $cond = "and tbl_sectors.sector_id Like'".$sector."' AND tbl_district.district_name Like '". $district."' AND tbl_scheme_master.loan_scheme_id Like'".$scheme."' and tbl_loanee_detail.account_id Like'".$account."' ";
    $_REQUEST['page']=0;
}
else if((empty($district))  && $sector && $scheme && $account ){
  $cond = "and tbl_sectors.sector_id Like'".$sector."'  AND tbl_scheme_master.loan_scheme_id Like'".$scheme."' and tbl_loanee_detail.account_id Like'".$account."' ";
    $_REQUEST['page']=0;
  
}else if($district  && $sector && (empty($scheme)) && $account ){
  $cond = "and tbl_sectors.sector_id Like'".$sector."'  AND tbl_district.district_name Like '". $district."' and tbl_loanee_detail.account_id Like'".$account."' ";
   $_REQUEST['page']=0;
}

else if($district  && $sector && (empty($account)) && $scheme ){
  $cond = "and tbl_sectors.sector_id Like'".$sector."'  and tbl_district.district_name Like '". $district."' and tbl_scheme_master.loan_scheme_id Like'".$scheme."'";
   $_REQUEST['page']=0;
   
}
else if((empty($district))  && $sector && $scheme && (empty($account)) ){
  $cond = "and tbl_sectors.sector_id Like'".$sector."'  AND tbl_scheme_master.loan_scheme_id Like'".$scheme."'";
  $_REQUEST['page']=0;
}

$sql = "SELECT tbl_loan_detail.scheme_name,
               tbl_loan_detail.reg_number,
               tbl_loan_detail.loan_amount,
               tbl_loan_detail.o_other_charges,
               tbl_loan_detail.o_interest,
               tbl_loan_detail.o_principal,
               tbl_loan_detail.o_LD,
               tbl_loanee_detail.account_id,
               tbl_loanee_detail.loanee_id,
			   tbl_loanee_detail.fname,
			   tbl_loanee_detail.lname,
			   tbl_loanee_detail.district,
			   tbl_loanee_detail.gender,
			   tbl_loanee_detail.address1,
			   tbl_loanee_detail.address2,
			   tbl_district.district_name,
               tbl_scheme_master.scheme_name as schemename ,
			   tbl_scheme_master.tenure ,
			   tbl_sectors.sector_name,
			   tbl_scheme_master.loan_scheme_id,
			   tbl_scheme_master.apex_share,
			   tbl_scheme_master.corp_share,
			   tbl_scheme_master.promoter_share,
			   SUM(tbl_loan_repayment.amount) as amount 
	    FROM tbl_loanee_detail 
	    INNER JOIN tbl_loan_detail ON  (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number)
        INNER JOIN tbl_scheme_master ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
	    INNER JOIN tbl_sectors ON  (tbl_scheme_master.sector=tbl_sectors.sector_id) 
	    INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id)
	    LEFT OUTER JOIN tbl_loan_repayment   ON (tbl_loanee_detail.loanee_id=tbl_loan_repayment.loanee_id) 
	    where 1=1  $cond GROUP BY tbl_loan_repayment.loanee_id";
	
	// echo $sql;
//$sql = "SELECT * FROM tbl_district  where district_name Like '%".$district."%'";
	$pdfurl = $base_url."/LoaneeIssueDetailReportpdf.php?op=loanissuedetail_report&district=$district&sector=$sector&scheme=$scheme&account=$account";
 
 
$count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";



}

  // $res = db_query($sql);
  $res = pager_query($sql, 10, 0, $count_query);
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%">
	<tr class="oddrow"><td align="left" colspan="15"><h2 style="text-align:left;">Loan Issue Detail Report</h2></td>
	<tr>
	<td align="right"  colspan="15">
	<a target="_blank" href="'.$pdfurl.'"><img src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" style="float:right;"/></a></td>
	</tr>
	</table></div>';
   
   //$output .='';

   $output .='<div class="listingpage_scrolltable"><table cellpadding="2" cellspacing="1" border="0" width="100%" id="wrapper2"><tr><th colspan="5"></th><th colspan="3" style="text-align:center">Loanee Detail</th><th colspan="3" style="text-align:center">Gaurantor Detail</th><th colspan="4"></th></tr>
               <tr>
   				<th width="5%">S. No.</th>
				<th >District Name</th>
				<th>Sector Name</th>
				<th>Name of Scheme</th>
				<th>Account No.</th>
				<th>Name </th>
				<th>Sex</th>
				<th >Address</th>
				<th>Name </th>
				
				<th colspan="2">Address</th>
				<th>Loan Amount Sanctioned</th>
				<th>Tenure</th>
				<th>Amount Received</th>
				<th>Balance Amount</th>
				</tr>';
				
			
				
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*10;
	}else{
	$counter = 0;
	}
	//$counter =0;
	
   while($rs = db_fetch_object($res)){
          $gender=getlookupName($rs->gender);
	      $am=$rs->o_other_charges;
	      $am1=$rs->o_intrest;
	      $am2=$rs->o_principal;
	      $am3=$rs->o_LD;
	  
	      $gsql = "SELECT * FROM tbl_guarantor_detail WHERE loanee_id = '".$rs->loanee_id."' LIMIT 1"; 
	      $gres = db_query($gsql);
	      $g = db_fetch_object($gres);
		  
		  $dsql = "SELECT SUM(amount) AS disamount FROM tbl_loan_disbursement WHERE loanee_id = '".$rs->loanee_id."'"; 
	      $dres = db_query($dsql);
	      $d = db_fetch_object($dres);
		  
	      $counter++;
	      if($counter%2==0){$cla="even";}else{$cla="odd";}
		  $accno = ($rs->account_id)?$rs->account_id:'N/A';
		  $amt = ($rs->amount)?$rs->amount:'N/A';
	      $balamount=$am+$am1+$am2+$am3;
	      //$balamount = ($balamount && $accno != 'N/A')?$balamount:'N/A';
	      $gname = ($g->gname)?$g->gname:'N/A';
	      $gaddress = ($g->address)?$g->address:'N/A';
		  $disbamount = ($d->disamount)?$d->disamount:'N/A';
	      $output .='<tr class="'.$cla.'">
					 <td class="center" width="10%">'.$counter.'</td> 
					 <td >'.ucwords($rs->district_name).'</td>
					 <td >'.ucwords($rs->sector_name).'</td>
					 <td >'.ucwords($rs->schemename).'</td>
					 <td >'.$accno.'</td>
					 <td >'.ucwords($rs->fname).'&nbsp;'.ucwords($rs->lname).'</td>
					 <td align="right">'.$gender.'</td>
					 <td align="right">'.ucwords($rs->address1." ".$rs->address2).'</td>
					 <td align="right">'.$gname.'</td>
					 <td align="right" colspan="2">'.ucwords($gaddress).'</td>
					 <td >'.round($disbamount).'</td>
					 <td align="right">'.$rs->tenure.'</td>
					 <td align="right">'.round($amt).'</td>
					 <td align="right">'.round(abs($balamount)).'</td>
	            </tr>';
   }
   
  if($counter > 0){
  
    $output .='</table></div>';
   echo  $output .= theme('pager', NULL, 10, 0);
  }else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
}
?>