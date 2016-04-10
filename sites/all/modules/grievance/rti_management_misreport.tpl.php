<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>RTI Management Form</h2></td>
</tr>
<tr>
	<td colspan='2'><?php print drupal_render($form['from_period']); ?></td>
   <td colspan='2'><?php print drupal_render($form['to_period']); ?></td>
</tr>
<tr>
	<td colspan='2'><?php print drupal_render($form['district_id']); ?></td>
	<td colspan='2'><?php print drupal_render($form['registeredstatus']); ?></td>
</tr>
<tr>
	<td align="center" colspan="2"><?php print drupal_render($form['generate_report']); ?></td>
</tr>
<tr>
	<td colspan="2">
    	<div id="misreport"></div>
    </td>
</tr>
</table>



<?php
$op = $_REQUEST['op'];
if($op=="Generate Report") {
	
	$from_period = $_REQUEST['from_period']['date'];
	$to_period = $_REQUEST['to_period']['date'];
	$district_id = $_REQUEST['district_id'];
	$registeredstatus = $_REQUEST['registeredstatus'];
	
	
	/*$queryString = "generateexcel.php?op=insurancemanagement";
	if($company_name){
		$queryString .= "&company_name=$company_name";
	}
	
	if($nature_policy){
		$queryString .= "&nature_policy=$nature_policy";
	}
	
	if($from_period){
		$queryString .= "&from_period=$from_period";
	}
	
	if($to_period){
		$queryString .= "&to_period=$to_period";
	}
	
	if($policystatus){
		$queryString .= "&policystatus=$policystatus";
	}
	
	$excelurl = $queryString;*/
	
	
	//echo $query = "select node.nid, node.title from node INNER JOIN tbl_account_details ON (node.vid = tbl_account_details.vid) WHERE (account_format='$account_format' OR '$account_format'='') AND (company_name='$company_name' OR '$company_name'='') AND ((DATE(opening_date) BETWEEN DATE($from_opening_date) AND DATE($to_opening_date)) OR ('$from_opening_date'='') OR ('$to_opening_date'='')) AND ((DATE(closing_date) BETWEEN DATE($from_closing_date) AND DATE($to_closing_date)) OR ('$from_closing_date'='') OR ('$to_closing_date'='')) AND (account_type='$account_type' OR '$account_type'='')";
	/*$excelimage = drupal_get_path('theme','dms')."/images/logo_excel.gif";
	$pdfimage = drupal_get_path('theme','dms')."/images/adobe_pdf_icon.png";*/
	$header = array(
		array('data' => t('')),
		array('data' => t('Period(From)')),
		array('data' => t('Period(To)')),
		array('data' => t('District')),
		array('data' => t('Status of the Registered RTI ')),
		
	);
	
	$sqlQuery = "SELECT node.nid,tbl_rtimanagement.datecurrent,tbl_rtimanagement.section ,tbl_rtimanagement.application_type ,tbl_rtimanagement.application_name, tbl_district.district_name FROM node
	 INNER JOIN tbl_rtimanagement ON (node.nid=tbl_rtimanagement.nid) 
	 INNER JOIN tbl_district ON (tbl_rtimanagement.district_id=tbl_district.district_id) 
				
				WHERE 1=1";
	$cond = '';
	
	

	if($from_period!='' && $to_period!=''){
		$cond .= " AND (tbl_rtimanagement.datecurrent BETWEEN '$from_period' AND '$to_period') ";
	}else{
		if($from_period!=''){
			$cond .= " AND tbl_rtimanagement.datecurrent='$from_period' ";
		}
		if($to_period!=''){
			$cond .= " AND tbl_rtimanagement.datecurrent='$to_period' ";
		}
	}
	
	if($district_id){
		$cond .= " AND tbl_district.district_name='$district_id' ";
	}
	
	/*if($registeredstatus){
		$cond .= " AND TI.nature_policy='$nature_policy' ";
	}*/
	
	
	
	$query = $sqlQuery . $cond;
	
	
	$count_query = "SELECT COUNT(*) FROM (" . $query . ") AS count_query";
	
	
	
	$result = pager_query($query, 20, 0, $count_query);
	
	
	/*$sqlQuery = "select 
			node.nid,node.title,TI.company_name,TI.nature_policy, TPN.policy_nature_name
		from 
			node left outer join tbl_insurancemanagement TI ON (node.vid = TI.vid) 
			left outer join tbl_policy_nature_master TPN ON (TI.nature_policy = TPN.policy_nature_id) 
		WHERE 
			(TI.company_name='$company_name' OR '$company_name'='0' OR '$company_name'='') 
			and (TI.nature_policy='$nature_policy' OR '$nature_policy'='0' OR '$nature_policy'='') 
			AND (DATE(TI.from_period) BETWEEN '$from_period' AND '$to_period')";

	if($policystatus == 'Expired')
	{
		$sqlQuery = $sqlQuery . " and DATE(TI.to_period) < NOW()";
	}
	elseif ($policystatus == 'Running')
	{
		$sqlQuery = $sqlQuery . " and DATE(TI.to_period) >= NOW()";
	}
	*/
	
	
	
	//$query = pager_query(db_rewrite_sql($sqlQuery));

	


	while($row=db_fetch_object($result)) {
	
	
	 
		$nodeobj = node_load($row->nid);
		$nodeurl = $addurl = l("View Account",'node/'.$row->nid);
		$from_period  = explode(" ",$nodeobj->from_period);
		$to_period  = explode(" ",$nodeobj->to_period);
		
		/*if(strtotime($to_period[0]) < strtotime(date("Y-m-d"))){
			$policyStatus = 'Expired';
		}else{
			$policyStatus = 'Running';
		}*/
		
		
		$rows[] = array(
			array('data' => $row->section),
		    array('data' => $row->application_type),
			array('data' => $row->application_name),
			array('data' => $from_period['0']),
			array('data' => $to_period['0']),
			
	
		);
	}
	//print $output = "<table border='0' width='100%'><tr><td colspan='9' align='right'><a target='_blank' href='$excelurl'><img src='$excelimage' alt='Export to Excel' width='20' height='20' title='Export to Excel' /></a> </td></table>";
	//print theme_table($header,$rows);
	$output .=theme_table($header,$rows);
	$output .= theme('pager', NULL, 20, 0);
	
	echo "<table border='0' width='100%'><tr><td colspan='9' align='right'><a target='_blank' href='$excelurl'><img src='$excelimage' alt='Export to Excel' width='20' height='20' title='Export to Excel' /></a> </td></table>".$output;

	}




?>