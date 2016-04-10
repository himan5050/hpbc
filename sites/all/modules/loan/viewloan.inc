<?php
function view_loan1($lid){
	
	echo 'Yes! we find out that function'; exit;
	global $user;
	global $base_url;
	//return $lid;
    $breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	if(authorised_user())
		$breadcrumb[] = l('List of Loan','loan/listloans');
	    $breadcrumb[] = l('View Loan','loan/view/'.$lid.'');
	 drupal_set_breadcrumb($breadcrumb);
	//echo"hiiii";
	
	/*$sql = "SELECT  node.nid,node.uid, employee_name, tbl_corporations.corporation_name, username,statusnodal FROM node
	 INNER JOIN tbl_joinings ON (node.nid=tbl_joinings.nid)
	 INNER JOIN tbl_corporations ON (tbl_corporations.corporation_id=tbl_joinings.current_officeid)
	 WHERE node.uid='".$user->uid."' AND( tbl_joinings.employee_name LIKE '".$val."'  OR tbl_corporations.corporation_name LIKE '".$val."' OR tbl_joinings.username LIKE '".$val."' OR tbl_joinings.statusnodal LIKE '".$val."') ".tablesort_sql($header);
   */
	
	$output = getloanview($lid);
	
	if(authorised_user())
		$output .= '<br><div class="back" align="center">'.l("Back",'loan/listloans')."</div>";	
	return $output;
	
	
}
