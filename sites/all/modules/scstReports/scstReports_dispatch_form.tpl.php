<style>
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 130px;
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
label{float:left; margin:3px 5px 0 0;}
.maincoldate{margin-top:12px;}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Dispatch Report</legend>
    
    <table align="left" class="frmtbl">
    <tr><td valign="top"><div class="maincol"><?php print drupal_render($form['dispatch_number']); ?></div></td  style="width:200px;"><td><div class="maincol maincoldate"><?php print drupal_render($form['from_date']); ?></div></td><td><div class="maincol maincoldate"><?php print drupal_render($form['to_date']); ?></div></td></tr><tr><td colspan="3" align="right"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td></tr>    
    </td></tr>
	</table>
	</fieldset>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['dispatch_number'] == ''){
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
	$fromtime = date('Y-m-d',strtotime($from));
	$totime =date('Y-m-d',strtotime($to));
	$dispatch_no = $_REQUEST['dispatch_number'];
  if($_REQUEST['dispatch_number'] && $_REQUEST['from_date']['date'] && $_REQUEST['to_date']['date']){
    $sql = "SELECT * FROM tbl_dispatchforms where (date1 BETWEEN '".$fromtime."' AND '".$totime."') and LOWER(dispatch_no) LIKE '%".strtolower($_REQUEST['dispatch_number'])."%'";
	$pdfurl = $base_url."/generatepdf.php?op=dispatch_report&fromtime=$fromtime&totime=$totime&dispatch_no=$dispatch_no";
  }
  else if($_REQUEST['dispatch_number']){
    $sql = "SELECT * FROM tbl_dispatchforms where LOWER(dispatch_no) LIKE '%".strtolower($_REQUEST['dispatch_number'])."%'";
	$pdfurl = $base_url."/generatepdf.php?op=dispatch_report&dispatch_no=$dispatch_no";
  }else{
    $sql = "SELECT * FROM tbl_dispatchforms where (date1 BETWEEN '".$fromtime."' AND '".$totime."')";
	$pdfurl = $base_url."/generatepdf.php?op=dispatch_report&fromtime=$fromtime&totime=$totime";
  }
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);

 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class=oddrow><td colspan=6><h2 style="text-align:left;">Dispatch Report</h2></td></tr>
	<tr>
	<td colspan=11 style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>

	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" class="table-border">';
   $output .='<tr>
   				<th width="6%">S. No.</th>
				<th width="8%">Dispatch Number</th>
				<th width="8%">Details of the person to whom it is dispatched</th>
				<th width="12%">Details of the sender</th>
				<th width="8%">Subject</th>
				<th width="8%" align="right">File Number</th>
				<th width="8%">Date</th>
				<th width="8%">Mode of Dispatch</th>
				<th width="8%">Amount of stamp</th>
				<th width="8%">Balance Amount</th>
				<th width="8%">Type</th>
			 </tr>';
			//  $output .='<thead>'  '</thead>';
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
	//echo $fromtime;
	////dispatch total
	//echo $dispatch_no;
	if($fromtime != "1970-01-01" && $dispatch_no ==NULL){
	$bbs=db_query("select sum(amount) as amount from tbl_dispatchforms where date1 < '".$fromtime."'");
$bbamt=db_fetch_object($bbs);

$sss=db_query("select sum(amount) as amount from tbl_dispatchaccounts");
$ssamt=db_fetch_object($sss);

$opening= $ssamt->amount-$bbamt->amount;
	//echo $opening;
	$open=$opening;
	}else{ 
	$dhakan=db_query("select nid from tbl_dispatchforms where LOWER(dispatch_no) LIKE '%".$dispatch_no."%'");
	$makhan=db_fetch_object($dhakan);
	$bbs=db_query("select sum(amount) as amount from tbl_dispatchforms where nid < '".$makhan->nid."'");
$bbamt=db_fetch_object($bbs);

$sss=db_query("select sum(amount) as amount from tbl_dispatchaccounts");
$ssamt=db_fetch_object($sss);
//echo $ssamt->amount;
$opening= $ssamt->amount-$bbamt->amount;
	//echo $opening;
	$open=$opening;}
   $output .='<tr> <td colspan="11" align="left"><b>Opening Balance = '.round($opening,2).'</b></td></tr>';
   while($rs = db_fetch_object($res)){
      $counter++;
	$type=getLookupName($rs->dispatch_type);
 $mod=getLookupName($rs->mod);

$ballu= $open - $rs->amount ;
$open=$ballu;


$section=getLookupName($rs->sender_details);

// $output .='<tr> <td colspan="9">'.$opening. ' </td></tr>';
      if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="right">'.$rs->dispatch_no.'</td>
					  <td>'.ucwords($rs->person_details).'</td>
					  <td>'.ucwords($rs->person_name).', '.ucwords($section).'</td>
					  <td>'.ucwords($rs->subject).'</td>
					  <td align="right">'.$rs->file_no.'</td>
					  <td align="center">'.date('d-m-Y',strtotime($rs->date1)).'</td>
					  <td>'.ucwords($mod).'</td>
					  <td align="right">'.round($rs->amount).'</td>
					  <td align="right">'.round($ballu).'</td>
					  <td>'.ucwords($type).'</td>
	            </tr>';
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