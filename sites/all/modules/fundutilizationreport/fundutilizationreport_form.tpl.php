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
select { width:120px; }
.maincol .form-item label {
display: inline;
font-weight: bold;
float: left;
width: 110px;
margin-top: 5px;
}
.maincoldate{margin-top:30px;}
</style>


  <table width="100%" style="border:none;" id="form-container">
  <tr>	
  	<td align="left" class="tdform-width"><fieldset><legend>Fund Utilization Report</legend>
	
<table align="left" class="frmtbl">
  <tr><td width="5%">&nbsp;</td>
   <td align="left"><b>Head Name:</b></td>
  <td><?php print drupal_render($form['head_name']); ?></div></td>
  <td align="left"><b>Scheme Name:</b></td> 
  <td><?php print drupal_render($form['scheme_name']); ?></div></td>  	 
 
  	<td align="right"><?php print drupal_render($form); ?></td><td width="5%">&nbsp;</td></tr>
  
  </table></fieldset>
  </td>
    </tr>
  </table>

<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
		
	
if($_REQUEST['head_name'] == '' && $_REQUEST['scheme_name'] == ''){
  form_set_error('form','Please enter any one of search field..');
}
else {
		
	$head_name = $_REQUEST['head_name'];
	$scheme_name = $_REQUEST['scheme_name'];
	
     $sql = "SELECT tbl_headmaster.name1,tbl_headmaster.vid,tbl_scheme_master.scheme_name,tbl_headmaster.createdon,tbl_headmaster.code,tbl_loan_detail.loan_requirement, 
		 tbl_loan_detail.o_disburse_amount,tbl_scheme_master.promoter_share,tbl_scheme_master.apex_share, tbl_scheme_master.corp_share,tbl_loanee_detail.district,
		 tbl_loan_detail.sanction_date,tbl_loanee_detail.reg_number,tbl_schemenames.schemeName_name
		 
		 FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_schemenames on (tbl_schemenames.schemeName_id=tbl_scheme_master.main_scheme) 
inner join tbl_headmaster on (tbl_headmaster.vid=tbl_schemenames.head) 
where 1=1 AND tbl_scheme_master.loan_type=148";

//$sql = "select 


$cond = '';	

    
	
	
	
	if($head_name){
	 $cond .= " AND tbl_headmaster.name1='$head_name'";
	}
	
	if($scheme_name){
	 $cond .= " AND tbl_schemenames.schemeName_name='$scheme_name'";
	}
	
		
	//$cond .= ' group by tbl_schemenames.schemeName_name,tbl_loanee_detail.district';	
	

	
	
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/fundutilizationreportpdf.php?op=fundutilizationreport";
   
	
	if($head_name){
		 $pdfurl.= "&head_name=$head_name";
	}			
		
	if($scheme_name){
		 $pdfurl.= "&scheme_name=$scheme_name";
	}		
		
	
	
	$pdfurl1=$pdfurl;
   
   
   $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%">
	
	<tr class=oddrow><td align="left" colspan=15 ><h2 style="text-align:left">Fund Utilization Report</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   

			 
	
			 
			  $output .='<tr>
                <th>S. No.</th>
                <th width="10%">Date</th>
   				<th>Registration No.</th>
				<th>Amount Disbursed</th>
				<th>Promoter Share</th>
				<th>Term Loan </th>
				<th>NBCFDC Share</th>
				<th>HBCFDC Share</th>
				<th>District</th>
				
				
				
				
				
				
				
										
			 </tr>';
			 
	$limit=10;		
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
     $counter++;
	 
	 $sqltotal = "SELECT tbl_loan_detail.loan_requirement as totalloan_requirement ,
		 tbl_loan_detail.o_disburse_amount as totaldis,tbl_scheme_master.promoter_share as totalpromoter_share,tbl_scheme_master.apex_share totalapex_share, tbl_scheme_master.corp_share as totalcorp_share
		 FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_schemenames on (tbl_schemenames.schemeName_id=tbl_scheme_master.main_scheme) 
inner join tbl_headmaster on (tbl_headmaster.vid=tbl_schemenames.head) 
 where 1=1";

//$sql = "select 


$condt = '';	

    
	
	
	
	if($head_name){
	 $condt .= " AND tbl_headmaster.name1='$head_name'";
	}
	
	if($scheme_name){
	 $condt .= " AND tbl_schemenames.schemeName_name='$scheme_name'";
	}
	
		
	$condt .= ' group by tbl_schemenames.schemeName_name,tbl_loanee_detail.district';	

$queryt =  $sqltotal . $condt;
$qu=db_query($queryt);
$rss=db_fetch_object($qu);

	 
	 $totaldis=$rss->totaldis;
	 $totalpromoter_share=$rss->totalpromoter_share;
	 $totalcorp_share=$rss->totalcorp_share;
	 $totalapex_share=$rss->totalapex_share;
	
	
	$loan_requirement=$rs->loan_requirement;
	$o_disburse_amount=$rs->o_disburse_amount;
	$totalloan_requirement=$rss->totalloan_requirement;
	 
	$disamount= $loan_requirement-$o_disburse_amount;
	$totaldisamount = $totalloan_requirement-$totaldis;
	
	$dist +=$disamount;
	$promoter +=$rs->promoter_share;
	$apex +=$rs->apex_share;
	$corp +=$rs->corp_share;
	
	//$loan_term=$rs->apex_share+$rs->corp_share;
	
	$loan +=$loan_term;
	
	 $loan_term=(($rs->apex_share/100)*$loan_requirement)+(($rs->corp_share/100)*$loan_requirement);
	 
	
	
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="6%">'.$counter.'</td>
					  <td align="center" width="10%">'.date("d-m-Y",strtotime($rs->sanction_date)).'</td>
					  <td align="right">'.$rs->reg_number.'</td>
					  <td align="right">'.round(abs($disamount)).'</td>
					  <td align="right">'.round(abs($rs->promoter_share)).'</td>
					  <td align="right">'.round(abs($loan_term)).'</td>
					  <td align="right">'.round(abs($rs->apex_share)).'</td>
					  <td align="right">'.round(abs($rs->corp_share)).'</td>
					  <td align="left">'.ucwords(getdistrict($rs->district)).'</td>
							 
					  
					  
					  
					  
	            </tr>';
				
			
	
				
				
				}
				
				//$cc += $amount;
		 $output .= '<tr>
					  <td class="center" width="6%">&nbsp;</td>
					  <td align="center">&nbsp;</td>
					  <td align="right">Total</td>
					  <td align="right">'.round($dist).'</td>
					  <td align="right">'.round($promoter).'</td>
					  <td align="right">'.round($loan).'</td>
					  <td align="right">'.round($apex).'</td>
					  <td align="right">'.round($corp).'</td>
					  <td align="left">&nbsp;</td>
							 
					  
					  
					  
					  
	            </tr>';
				 		
		
	
 
   
 
  
  if($counter > 0){
  
  $output .='</table></div>';
  
   echo $output .= theme('pager', NULL, 10, 0);
  
  }else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 	
}
	
 
	 
	 
	
}

		

?>