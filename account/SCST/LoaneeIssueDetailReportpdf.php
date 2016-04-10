<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, A3, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SC and ST');
$pdf->SetTitle('SC and ST');
$pdf->SetSubject('SC and ST');
$pdf->SetKeywords('SC and ST');

$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, 'Nikhil Bhawan, Power House Road Saproon, Solan-173211');
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('helvetica', '', 10);
// add a page
$pdf->AddPage();
//getting number of page $pdf->pagenumber()

if($_REQUEST['op'] == 'loanissuedetail_report'){
global $user, $base_url;
$district = $_REQUEST['district'];
$sector = $_REQUEST['sector'];
$scheme = $_REQUEST['scheme'];
$account=$_REQUEST['account'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:1040px;
}
table.tbl_border{border:1px solid #a7c942; 
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">';

$output .='<tr><td colspan="0" align="center" class="header_report">Loan Issue Detail Report</td></tr><tr><td>&nbsp;</td></tr>

</table>';
	

/*if($district == '' and $sector == '' )
{
  form_set_error('form','Please enter the district or sector .');
     
}else{
if($district && $sector == ''){
   $cond = 'and tbl_district.district_name Like "'.'%'.$district.'%'.'"';
}else if($district == '' && $sector ){

  $cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'"';
//  $cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_scheme_master.schemename Like "'.'%'.$scheme.'%'.'"';
}else if($district  && $sector ){
  //$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'" AND tbl_scheme_master.schemename Like "'.'%'.$scheme.'%'.'"';

$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';
}
*/

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
  echo $cond = " and tbl_district.district_name Like '". $district."' and tbl_loanee_detail.account_id = '".$account."'";
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


$sql = "SELECT tbl_loan_detail.scheme_name,tbl_loan_detail.reg_number,tbl_loan_detail.loan_amount,tbl_loan_detail.o_other_charges,tbl_loan_detail.o_interest,tbl_loan_detail.o_principal,tbl_loan_detail.o_LD,tbl_loanee_detail.account_id,tbl_loanee_detail.loanee_id,	tbl_loanee_detail.fname,tbl_loanee_detail.lname,tbl_loanee_detail.district,tbl_loanee_detail.gender,tbl_loanee_detail.address1,tbl_loanee_detail.address2,tbl_district.district_name,
tbl_scheme_master.scheme_name as schemename ,tbl_scheme_master.tenure ,tbl_sectors.sector_name,tbl_scheme_master.loan_scheme_id,tbl_scheme_master.apex_share,tbl_scheme_master.corp_share,tbl_scheme_master.promoter_share,SUM(tbl_loan_repayment.amount) as amount

	 
	 
	 
	 FROM tbl_loanee_detail 
	INNER JOIN tbl_loan_detail ON  (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number)
    INNER JOIN tbl_scheme_master ON  (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
	INNER JOIN tbl_sectors ON  (tbl_scheme_master.sector=tbl_sectors.sector_id) 
	INNER JOIN tbl_district ON  (tbl_loanee_detail.district=tbl_district.district_id)
	LEFT OUTER JOIN tbl_loan_repayment   ON (tbl_loanee_detail.loanee_id=tbl_loan_repayment.loanee_id) 
	where 1=1  $cond GROUP BY tbl_loan_repayment.loanee_id";
	
	$result = db_query($sql);
	$rs2 = db_fetch_object($result);
	
	



if($district == '' && $sector == '' && $account=='' )
{
  form_set_error('form','Please enter the district or sector .');
     
}else{
	
 if($district && (empty($sector ))&& (empty($scheme)) && (empty($account)) ){
   $output .='<tr><td><b>District Name :</b> '.ucwords($rs2->district_name).'<br></td></tr>';
    $_REQUEST['page']=0;
}
else if((empty($district))&& $sector && (empty($scheme)) && (empty($account)) ){
	 $_REQUEST['page']=0;

 $output .='<tr><td><b>Sector Name : </b>'.ucwords($rs2->sector_name).'<br></td></tr>';
}
else if((empty($district)) && (empty($sector)) && (empty($scheme)) && $account ){
  $_REQUEST['page']=0;
  //$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'"';
$output .='<tr><td><b>Account Id : </b>'.ucwords($rs2->account_id).'<br></td></tr>';
}
else if($district  && $sector && (empty($scheme)) &&  (empty($account))  ){
  $output .='<tr><td><b>District Name : </b>'.ucwords($rs2->district_name).'<br><br><b>Sector Name :</b> '.ucwords($rs2->sector_name).'<br><br></td></tr>';
   $_REQUEST['page']=0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';

}else if($district &&  $account   && (empty($sector)) && (empty($scheme))  ){
  $output .='<tr><td><b>District Name : </b>'.ucwords($rs2->district_name).'<br><br><b>Account Id : </b>'.ucwords($rs2->account_id).'<br><br></td></tr>';
   $_REQUEST['page']=0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';

}else if((empty($district)) &&  $account   && $sector && (empty($scheme))  ){
   $output .='<tr><td><br><b>Sector Name :</b> '.ucwords($rs2->sector_name).'<br><br><b>Account Id : </b>'.ucwords($rs2->account_id).'<br><br></td></tr>';
  $_REQUEST['page']=0;
//$cond = 'and tbl_sectors.sector_name Like  "'.'%'.$sector.'%'.'" AND tbl_district.district_name Like "'.'%'.$district.'%'.'"';

}
else if($district  && $sector && $scheme && $account){
 $output .='<tr><td><b>District : </b>'.ucwords($rs2->district_name).'<br><br><b>Sector Name : </b>'.ucwords($rs2->sector_name).'<br><br>
 <b>Scheme Name: </b>'.ucwords($rs2->schemename).'<br><br><b>Account Id : </b>'.ucwords($rs2->account_id).'<br>
 
 </td></tr>';
    $_REQUEST['page']=0;
}
else if((empty($district))  && $sector && $scheme && $account ){
 $output .='<tr><td><b>Sector Name : </b>'.ucwords($rs2->sector_name).'<br>
 <b>Scheme Name: </b>'.ucwords($rs2->schemename).'<br><br><b>Account Id : </b>'.ucwords($rs2->account_id).'<br>
 
 </td></tr>';

    $_REQUEST['page']=0;
  
}else if($district  && $sector && (empty($scheme)) && $account ){
 $output .='<tr><td><b>District : </b>'.ucwords($rs2->district_name).'<br><br><b>Sector Name : </b>'.ucwords($rs2->sector_name).'<br>
 <br><b>Account Id : </b>'.ucwords($rs2->account_id).'<br>
 
 </td></tr>';

   $_REQUEST['page']=0;
}

else if($district  && $sector && (empty($account)) && $scheme ){
 $output .='<tr><td><b>District : </b>'.ucwords($rs2->district_name).'<br><br><b>Sector Name : </b>'.ucwords($rs2->sector_name).'<br><br>
 <b>Scheme Name: </b>'.ucwords($rs2->schemename).'<br>
 
 </td></tr>';

   $_REQUEST['page']=0;
   
}
else if((empty($district))  && $sector && $scheme && (empty($account)) ){
  $output .='<tr><td><b>Sector Name : </b>'.ucwords($rs2->sector_name).'<br><br>
 <b>Scheme Name: </b>'.ucwords($rs2->schemename).'<br>
 
 </td></tr>';

  $_REQUEST['page']=0;
}

}
   $output .='<table cellpadding="2" cellspacing="2" id="wrapper" class="tbl_border">';
  $output .='<tr><td style="border:1px solid #fff;" width="51%" colspan="6" class="header2">&nbsp;</td><td colspan="3" align="center" class="header2" width="28%" style="border:1px solid #fff;">Loanee Detail</td><td colspan="2" align="center" class="header2" width="20%" style="border:1px solid #fff;">Gaurantor Detail</td><td style="border:1px solid #fff;" colspan="3" class="header2" width="38%">&nbsp;</td></tr>';
               
   		$output .='<tr><td width="5%" class="header2">S.No.</td>
				<td width="10%" class="header2">District Name</td>
				<td width="15%" class="header2">Sector Name</td>
				<td width="11%" class="header2">Name of Scheme</td>
				<td width="10%" class="header2">Account No.</td>
				<td width="9%" class="header2">Name</td>
				<td width="7%" class="header2">Sex</td>
				<td width="12%" class="header2">Address</td>
				<td width="8%" class="header2">Name</td>
				<td width="12%" class="header2">Address</td>
				<td width="9%" class="header2">Loan Amount</td>
				<td width="5%" class="header2">Tenure</td>
				<td width="12%" class="header2">Amount Received</td>
				<td width="12%" class="header2">Balance Amount</td>
				
				</tr>';
				





 $res = db_query($sql);
 $counter=1;
 while($rs = db_fetch_object($res)){
	 $gender=getlookupName($rs->gender);
	  
	  $am=$rs->o_other_charges;
	  $am1=$rs->o_intrest;
	  $am2=$rs->o_principal;
	  $am3=$rs->o_LD;
		$accno = ($rs->account_id)?$rs->account_id:'N/A';
		$amt = ($rs->amount)?$rs->amount:'N/A';
	  
	  $balamount=$am+$am1+$am2+$am3;
	$gsql = "SELECT * FROM tbl_guarantor_detail WHERE loanee_id = '".$rs->loanee_id."' LIMIT 1"; 
	$gres = db_query($gsql);
	$g = db_fetch_object($gres);
	$gname = ($g->gname)?$g->gname:'N/A';
	$gaddress = ($g->address)?$g->address:'N/A';
 
   if($counter%2==0){ $class='header4_1';}else{$class='header4_2';}
				
				
				 $output .='<tr>
					  <td class="'.$class.'">'.$counter.'</td>
					  <td class="'.$class.'">'.ucwords($rs->district_name).'</td>
					  <td class="'.$class.'">'.ucwords($rs->sector_name).'</td>
					   <td class="'.$class.'">'.ucwords($rs->schemename).'</td>
					    <td class="'.$class.'">'.$accno.'</td>
						 <td class="'.$class.'">'.ucwords($rs->fname).'&nbsp;'.ucwords($rs->lname).'</td>
						  <td class="'.$class.'">'.ucwords($gender).'</td>
						  <td class="'.$class.'">'.ucwords($rs->address1." ".$rs->address2).'</td>
						  <td class="'.$class.'" >'.ucwords($gname).'</td>
						  <td class="'.$class.'" >'.ucwords($gaddress).'</td>
						  <td class="'.$class.'" align="right">'.$rs->loan_amount.'</td>
						   <td class="'.$class.'" align="right">'.$rs->tenure.'</td>
						    <td class="'.$class.'" align="right">'.$rs->amount.'</td>
							<td class="'.$class.'" align="right">'.$balamount.'</td>
	            </tr>';
				$counter++;
 }


 


		
		 $output .='</table>';
	ob_end_clean();
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('loanissuedetail_report_'.time().'.pdf', 'I');
}
}
