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
table {
    border: 1px solid #CCCCCC;
}
.maincol #edit-Departmentid-wrapper select{width:89px;}
</style>


  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	
    	<td align="left"><fieldset><legend>Budget Detail Register</legend>
    
    <table align="left" class="frmtbl" style="border:0px;" width="100%" >
    <tr id="valchange">
		
        <td id="valchange"><?php print drupal_render($form['headcode']); ?></td>        
          
        <td id="valchange"><?php print drupal_render($form['headname']); ?></td>
		
        <td id="valchange"><?php print drupal_render($form['schemename']); ?></td> 
    </tr>
    <tr>     
		
        <td align="left" id="sectionname"><?php print drupal_render($form['sectionname']); ?></td>
         
        <td align="right" colspan="2"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td>  
    </tr>
	</table>
	</fieldset>
    </td>
    </tr>
  </table>

<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate Report'){
if($_REQUEST['headcode'] == '' && $_REQUEST['headname'] == '' && $_REQUEST['schemename'] == '' && $_REQUEST['sectionname'] == ''){
  form_set_error('form','Please enter any one of search field..');
}else {
	$sectionname= $_REQUEST['sectionname'];
	$schemename = $_REQUEST['schemename'];
	$headcode = $_REQUEST['headcode'];
	$headname = $_REQUEST['headname'];
	


	$append="";
	if($_REQUEST['headcode']){
	
	
	
	$append .= " headtype=".$headcode." AND ";
$pdfurl = $base_url."/budgetDetailReportpdf.php?op=budgetDetailReport&sectionname=$sectionname&schemename=$schemename&headcode=$headcode&headname=$headname";
	
	
	
	}

	
	if($_REQUEST['sectionname']){
	
	//$append .= " LOWER(headname) LIKE '%".strtolower($headname)."%' AND ";
	$append .= " branch LIKE '%".$sectionname."%' AND ";
$pdfurl = $base_url."/budgetDetailReportpdf.php?op=budgetDetailReport&sectionname=$sectionname&schemename=$schemename&headcode=$headcode&headname=$headname";	}
	


	$append .= " 1=1 "; 
	 
	   $sql1=db_query("select nid from tbl_budgetdistribution where $append");
	   $sql2=db_fetch_object($sql1);
  $sql="select * from tbl_budgetmonths where nid='".$sql2->nid."'";

	
if($_REQUEST['schemename']){

		 if(!empty($sectionname) || !empty($headcode) || !empty($headname)){
	
	$sql="select * from tbl_budgetmonths where nid='".$sql2->nid."' and schemename='".$schemename."'";
	
$pdfurl = $base_url."/budgetDetailReportpdf.php?op=budgetDetailReport&sectionname=$sectionname&schemename=$schemename&headcode=$headcode&headname=$headname";	
		 }else {
		 
		 $sql="select * from tbl_budgetmonths where schemename='".$schemename."'";
	
$pdfurl = $base_url."/budgetDetailReportpdf.php?op=budgetDetailReport&sectionname=$sectionname&schemename=$schemename&headcode=$headcode&headname=$headname";
		 
		 }
}  else {
  $sql="select * from tbl_budgetmonths where nid='".$sql2->nid."'";
	
$pdfurl = $base_url."/budgetDetailReportpdf.php?op=budgetDetailReport&sectionname=$sectionname&schemename=$schemename&headcode=$headcode&headname=$headname";
  }
  
  

  
 //echo $sql;
  
  

  
  
  $count_query = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";

  $res = pager_query($sql, 10, 0, $count_query);
//$res=db_query($sql);
 
   
    $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table class="listingpage_scrolltable">
	<tr class=oddrow><td colspan=6><h2 style="text-align:left;">Budget Detail Register</h2></td></tr>
	<tr>
	<td colspan=11 style="text-align:right;">
	<a target="_blank" href="'.$pdfurl.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>

	</tr>
	</table>';
   
   //$output .='';
   $output .='<table class="listingpage_scrolltable" class="table-border">';
   $output .='<tr>
   				<th width="4%" align="center">S. No.</th>
				<th width="8%">Head Code</th>
				<th width="8%">Head Name</th>
				<th width="8%">Scheme Name</th>
				<th width="8%">Branch</th>
				<th width="8%">Amount Allocated</th>
				<th width="8%">Amount Consumed</th>
				<th width="8%">Balance Amount</th>
			
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
	//echo $sectionname;
	
 
   while($rs = db_fetch_object($res)){
  
	  
	  $counter++;
	
	

$stuff=db_query("select tbl_headmaster.code,tbl_headmaster.name1,tbl_corporations.corporation_name  from tbl_budgetdistribution 
 inner join tbl_headmaster on (tbl_headmaster.vid=tbl_budgetdistribution.headtype)
 inner join tbl_corporations on (tbl_corporations.corporation_id=tbl_budgetdistribution.branch) where tbl_budgetdistribution.nid='".$rs->nid."'");
 

 $stuff1=db_fetch_object($stuff);


 if($headcode==''){$headty=$headname;}
else if($headname==''){$headty=$headcode;}
else if($headcode && $headname){$headty=$headname;}

$headtype1=db_query("select type1 from tbl_headmaster where vid='".$headty."'");
$headtype2=db_fetch_object($headtype1);
$headtype_id=$headtype2->type1;
if($headtype_id==248){
	//loan
$stuff2=db_query("select schemeName_name from tbl_schemenames where schemeName_id='".$rs->schemename."'");


} else if($headtype_id==249){
	//exp
	
$stuff2=db_query("select lookup_name from tbl_lookups where lookup_id='".$rs->schemename."'");

}

//$stuff2=db_query("select lookup_name from tbl_lookups where lookup_id='".$rs->schemename."'");

//$stuff2=db_query("select schemeName_name from tbl_schemenames where schemeName_id='".$rs->schemename."'");

//if($stuff2==''){
//$stuff2=db_query("select lookup_name from tbl_lookups where lookup_id='".$rs->schemename."'");
//}
$stuff3=db_fetch_object($stuff2);


$allocated= $rs->apr + $rs->may + $rs->jun + $rs->jul + $rs->aug + $rs->sept + $rs->oct + $rs->nov + $rs->dec + $rs->jan + $rs->feb + $rs->mar;
$consumed= $rs->consume_apr + $rs->consume_may + $rs->consume_jun + $rs->consume_jul + $rs->consume_aug + $rs->consume_sept + $rs->consume_oct + $rs->consume_nov + $rs->consume_dec + $rs->consume_jan + $rs->consume_feb + $rs->consume_mar;
$bal=$allocated-$consumed;


if($headtype_id==248){$stuff7=$stuff3->schemeName_name;} else {$stuff7=$stuff3->lookup_name;}

	  if($counter%2==0){ $cl="even"; }else{ $cl="odd"; }
	  $output .='<tr class="'.$cl.'">
					  <td align="center">'.$counter.'</td>
					  <td >'.$stuff1->code.'</td>
					  <td >'.ucwords($stuff1->name1).'</td>
					   <td >'.ucwords($stuff7).'</td>
					  <td >'.ucwords($stuff1->corporation_name).'</td>
					  <td align="right">'.round($allocated).'</td>
					  <td align="right">'.round($consumed).'</td>
					  <td align="right">'.round($bal).'</td>					 
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