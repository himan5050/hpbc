<?php
function list_loan_schemes(){
	global $user;
	global $base_url;
	//$limit = 2;
	$limit = (int)getMessage( 'dsjedispatchAccount', 'code04', NULL);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	
  $header = array(
		array('data' => t('S. No.')),
        array('data' => t('Scheme Name'), 'field' => 'scheme_name', 'sort' => 'desc'),
        array('data' => t('Main Scheme'), 'field' => 'main_scheme', 'sort' => 'asc'),
        array('data' => t('Sector'), 'field' => 'sector', 'sort' => 'asc'),
		array('data' => t('Status'), 'field' => 'lu.lookup_name', 'sort' => 'asc'),
		array('data' => t('Active Status'), 'field' => 'active', 'sort' => 'desc'),
		array('data' => t('Action'), 'class' => 'addeditview'),
	);
	
	if($array[0] == '' ) {
		$breadcrumb[] = l('List of Loan Scheme(s)', 'loan/listscheme/'.$array[3].'');
	 }  
	 drupal_set_breadcrumb($breadcrumb);
	$query = "SELECT lookup_id as lid, lookup_name as lname FROM tbl_lookups WHERE status = 1 ";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$lookuparr[$row->lid] = $row->lname;
	}
	$query = "SELECT schemeName_id, schemeName_name FROM tbl_schemenames WHERE status = 1";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$mainschemearr[$row->schemeName_id] = $row->schemeName_name;
	}
	$query = "SELECT sector_id, sector_name FROM tbl_sectors WHERE status = 1";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$sectorarr[$row->sector_id] = $row->sector_name;
	}
	$activestatusstr = '';
	if(isset($_REQUEST['as']) && $_REQUEST['as'] != '')
		$activestatusstr = " AND sm.active = '".$_REQUEST['as']."' ";
	if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
		$val = '%'.strtoupper($_REQUEST['searchtext']).'%'; $val=addslashes($val);	 
		$query = "SELECT sm.* FROM tbl_scheme_master sm,tbl_sectors s, tbl_schemenames sn,tbl_lookups lu where lu.lookup_id = sm.status AND sm.main_scheme = sn.schemeName_id AND sm.sector = s.sector_id AND (UPPER(sm.scheme_name) LIKE '".$val."' OR UPPER(sn.schemeName_name) LIKE '".$val."' OR  UPPER(s.sector_name) LIKE '".$val."' OR UPPER(lu.lookup_name) LIKE '".$val."') $activestatusstr".tablesort_sql($header);
		$sqlcount = "SELECT COUNT(*) AS count FROM tbl_scheme_master sm,tbl_sectors s, tbl_schemenames sn,tbl_lookups lu WHERE lu.lookup_id = sm.status AND sm.main_scheme = sn.schemeName_id AND sm.sector = s.sector_id AND (UPPER(sm.scheme_name) LIKE '".$val."' OR UPPER(sn.schemeName_name) LIKE '".$val."' OR  UPPER(s.sector_name) LIKE '".$val."' OR UPPER(lu.lookup_name) LIKE '".$val."') $activestatusstr ".tablesort_sql($header);
		//echo $query;exit;
		$rscount = db_query($sqlcount);
		$rscounter = db_fetch_object($rscount);
	}else{
		if(isset($_REQUEST['as']) && $_REQUEST['as'] != '')
		{
			$query = "SELECT sm.* FROM tbl_scheme_master sm,tbl_sectors s, tbl_schemenames sn,tbl_lookups lu where lu.lookup_id = sm.status AND sm.main_scheme = sn.schemeName_id AND sm.sector = s.sector_id AND sm.active = '".$_REQUEST['as']."'".tablesort_sql($header);
			$sqlcount = "SELECT COUNT(*) AS count FROM tbl_scheme_master sm,tbl_sectors s, tbl_schemenames sn,tbl_lookups lu WHERE lu.lookup_id = sm.status AND sm.main_scheme = sn.schemeName_id AND sm.sector = s.sector_id AND sm.active = '".$_REQUEST['as']."'".tablesort_sql($header);
			//echo $query;exit;
			$rscount = db_query($sqlcount);
			$rscounter = db_fetch_object($rscount);
		}else{
			$query = "SELECT *,sm.status as status FROM tbl_scheme_master sm,tbl_lookups lu,tbl_sectors s, tbl_schemenames sn WHERE lu.lookup_id = sm.status AND sm.main_scheme = sn.schemeName_id AND sm.sector = s.sector_id ".tablesort_sql($header);
		}
	}
	 
 $output = <<<EOD
 	<form method="post" action=""><table width="100%" border="0" cellspacing="1" cellpadding="1" id="wrapper">
	<tr><td colspan="3" class="searchrecord">
EOD;
	if((isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!='') || (isset($_REQUEST['as']) && $_REQUEST['as'] != '')){
	$output .= t(getMessage( 'dsjedispatchAccount', 'code03', array("0"=>$rscounter->count)))." | ".l('View All','loan/listscheme');
	}
	
	$output .='</td></tr>';
	
	$addurl = l(getMessage( 'loanaddscheme', 'code10', NULL),"loan/addscheme");
   	$lising = getMessage( 'loanaddscheme', 'code11', NULL);
	$selectedactive = '';
	$selectedblock = '';
	if(isset($_REQUEST['as']) && $_REQUEST['as'] != '')
	{
		if($_REQUEST['as'])
			$selectedactive = " selected = 'selected'";
		else
			$selectedblock = " selected = 'selected'";
	}
	$output .='<tr>'.
	'<td colspan="3" class="tblHeaderLeft">'.$lising.'<span class="addrecord">'.$addurl.'</span></td>'.

	'   <td colspan="3" class="tblHeaderRight">'.
	'Active Status : <select name="as"><option value="">Select</option><option value="1" '.$selectedactive.'>Active</option><option value="0" '.$selectedblock.'>Blocked</option></select><input type="text" name="searchtext" value="'.$_POST['searchtext'].'" />'.
	'&nbsp;<input type="submit" name="search" value="Search" /></td></tr>'.
	'</table></form>';
	
/*$output .=<<<EOD
	<table class="sticky-enabled">
		<thead>
			<tr>
				<th>S.No</th>
				<th><a href="/SC&ST/loan/listscheme?sort=asc&order=scheme_name" title="sort by Scheme Name" class="active">Scheme Name</a>
				</th>
				<th>Main Scheme</th>
				<th>Sector</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>

	<tbody>
EOD;
*/	
	$result = pager_query($query, $limit);

	if($_REQUEST['page']){
     $counter = $_REQUEST['page']*$limit;
	}else{
	 $counter = 0;
    }
	while($row=db_fetch_object($result)) {
		$counter++;
		$editurl = '';
		$blockurl = "";
		if($row->status == 166 && $row->active)
			$editurl = ' | '.l("Edit","loan/addscheme/".$row->loan_scheme_id);
		$viewurl = l("View","loan/viewscheme/".$row->loan_scheme_id);
		if($row->active == 1)
			$blockurl = " | ".l("Block","loan/blockscheme/".$row->loan_scheme_id);
		else
			$blockurl = " | ".l("Activate","loan/blockscheme/".$row->loan_scheme_id."/1");
		$activestatus = ($row->active)?'Active':'Blocked';
		if($row->status == 168)
		{
			$activestatus = '-';
			$blockurl = "";
		}
		$rows[] = array(
			
			array('data' => $counter),
			array('data' => ucwords($row->scheme_name)),
			array('data' => ucwords($mainschemearr[$row->main_scheme])),
			array('data' => ucwords($sectorarr[$row->sector])),
			array('data' => ucwords($lookuparr[$row->status])),
			array('data' => ucwords($activestatus)),
			array('data' => $viewurl.$editurl.$blockurl),
		);
	}
	if($rows== NULL)
	$header=NULL;
	$output .= theme_table($header,$rows, $attributes = array(), $caption = NULL);
	$output .= theme('pager', NULL, $limit,0 );
	 
  return $output;
}
function view_loan_scheme($sid)
{



	$array = explode('/',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Schemes', 'loan/listscheme');
	if($array[1] == 'viewscheme'){
		$breadcrumb[] = l('View Loan Scheme', 'loan/viewscheme/'.$array[2].'');
	}

	drupal_set_breadcrumb($breadcrumb);
	$query = "SELECT lookup_id as lid, lookup_name as lname FROM tbl_lookups WHERE status = 1 ";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$lookuparr[$row->lid] = $row->lname;
	}
	$query = "SELECT loanDoc_id as lid, loanDoc_name as lname FROM tbl_loandocs WHERE status = 1 ";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$docstr[$row->lid]= $row->lname;
	}
	$query = "SELECT class, ROI FROM tbl_scheme_class WHERE scheme_id = $sid";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$classarr[] = $row;
	}
    $sql = "SELECT sm.*,sm.status as sstatus, sn.schemeName_name,s.sector_name FROM tbl_scheme_master sm, tbl_sectors s,tbl_schemenames sn WHERE sm.sector = sector_id AND sm.main_scheme = sn.schemeName_id AND sm.loan_scheme_id=$sid ";
	$res = db_query($sql);
	$rs = db_fetch_object($res);
	if($rs->active ==1){
		$active ='Enable';
	 }else{
	   $active ='Disable';
	 }
	 if($rs->capital_subsidy == 1)
	 {
	 	$capital_subsidy = 'Yes';
	 }else{
	 	$capital_subsidy = 'No';
	 }
	$output .='<div id="form-container">';
	$output .='<table cellpadding="2" cellspacing="1" border="0" id="wrapper"><tr class="oddrow"><td colspan="2"><h2>Scheme Details</h2></td></tr>';
	$output .='<tr class="evenrow"><td style="width:450px;">Scheme Name:</td><td class="normal">'.ucwords($rs->scheme_name).'</td></tr>';
	$output .='<tr class="oddrow"><td>Main Scheme:</td><td class="normal">'.ucwords($rs->schemeName_name).'</td></tr>';
	$output .='<tr class="evenrow"><td>Sector:</td><td class="normal">'.ucwords($rs->sector_name).'</td></tr>';
	$output .='<tr class="oddrow"><td>Loan Type:</td><td class="normal">'.ucwords($lookuparr[$rs->loan_type]).'</td></tr>';
	$output .='<tr class="evenrow"><td colspan="2">Loan Class:</td></tr>';
	$classstr = '';
	$cl = 'evenrow';
	foreach($classarr as $key => $val)
	{
		if($key % 2)
			$cl = 'evenrow';
		else
			$cl = 'oddrow';
		$classstr .= "<tr class='".$cl."'><td>Class :  <span class='normal'>".$val->class."</span></td><td>ROI : <span class='normal'>".$val->ROI." %</span></td></tr>";
	}
	if($cl = 'oddrow')
		$ncl = 'evenrow';
	else
		$ncl = 'oddrow';
	$output .=$classstr;
	$output .='<tr class="'.$ncl.'"><td>Tenure:</td><td class="normal">'.$rs->tenure.' Months</td></tr>';
	$output .='<tr class="'.$cl.'"><td>Fund Source:</td><td class="normal">'.ucwords($lookuparr[$rs->fund_source]).'</td></tr>';
	$output .='<tr class="'.$ncl.'"><td>Project Total Cost:</td><td class="normal">Rs. '.round($rs->project_cost).'</td></tr>';
	$output .='<tr class="'.$cl.'"><td>Apex Share:</td><td class="normal">'.$rs->apex_share.' %</td></tr>';
	$output .='<tr class="'.$ncl.'"><td>Corporation Share:</td><td class="normal">'.$rs->corp_share.' %</td></tr>';
	$output .='<tr class="'.$cl.'"><td>Promotor Share:</td><td class="normal">'.$rs->promoter_share.' %</td></tr>';
	$output .='<tr class="'.$ncl.'"><td>Capital Subsidy:</td><td class="normal">'.$capital_subsidy.'</td></tr>';
	if($lookuparr[$rs->loan_type] == 'Bank')
	{
		$subsidy = ($rs->interest_subsidy)?'Yes':'No';
		$output .='<tr class="'.$cl.'"><td>MMD FDR:</td><td class="normal">'.$rs->MMD_FDR.' %</td></tr>';
		$output .='<tr class="'.$ncl.'"><td>Interest Subsidy:</td><td class="normal">'.ucwords($subsidy).'</td></tr>';
	}
	$output .='<tr class="'.$cl.'"><td>Scheme Code:</td><td class="normal">'.$rs->scheme_code.'</td></tr>';
	$output .='<tr class="'.$ncl.'"><td>Processing Fee:</td><td class="normal">'.$rs->processing_fee.' %</td></tr>';
	$output .='<tr class="'.$cl.'"><td>Interest Type:</td><td class="normal">'.ucwords($lookuparr[$rs->interest_type]).'</td></tr>';
	$output .='<tr class="'.$ncl.'"><td>Frequency:</td><td class="normal">'.ucwords($lookuparr[$rs->frequency]).'</td></tr>';
	$output .='<tr class="'.$cl.'"><td>Document Required:</td><td class="normal">';
	$docs = explode(',',unserialize($rs->eligibility_criteria));
	$documents = '';
	foreach($docs as $key => $val)
	{
		$documents .= $docstr[$val].', ';
	}
	$output .= trim($documents,', ').'</td></tr>';
	$output .='<tr class="'.$ncl.'"><td>Status:</td><td class="normal">'.ucwords($lookuparr[$rs->sstatus]).'</td></tr>';
	if($lookuparr[$rs->sstatus] == 'Approved')
	{
		$output .='<tr class="'.$cl.'"><td>LOI:</td><td class="normal">'.l("Download",$base_path.'sites/default/files/loan/'.$rs->LOI_doc).'</td></tr>';
		$cl = $ncl;
	}
	$output .='<tr class="'.$cl.'"><td align="center" colspan="2" class="back">'.l(t('Back'), 'loan/listscheme').'</td></tr>';
	$output .='</table></div>';
 return $output;
}

function blockscheme($sid,$active = '')
{
	if($sid)
	{
		$sql = "select scheme_name FROM tbl_scheme_master WHERE loan_scheme_id = $sid LIMIT 1";
		$res = db_query($sql);
		$sname = db_fetch_object($res);
		if($active)
		{
			$activevalue = 1;
			$msg = "Scheme $sname->scheme_name activated successfully.";
		}else{
			$activevalue = 0;
			$msg = "Scheme $sname->scheme_name blocked successfully.";
		}
		$sql = "UPDATE tbl_scheme_master SET active = $activevalue WHERE loan_scheme_id = $sid";
		if(!db_query($sql))
		{
			form_set_error('','There is some error while updating data.');
		}else{
			drupal_set_message($msg);
		}		
		drupal_goto('loan/listscheme');
	}
}