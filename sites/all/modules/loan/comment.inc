<?php

function closecomment($cid = '',$doc_id = '')
{
	$sql = "SELECT doc_id FROM tbl_loan_comment WHERE id = '".$cid."'";
	$res = db_query($sql);
	$c = db_fetch_object($res);

	$updatesql = "UPDATE tbl_loan_comment SET status = 1 WHERE id = ".$cid;
	if(!db_query($updatesql))
	{
		$inserterror = 1;
		drupal_set_message("There is some error while updation.Please try again.");
	}else{
		drupal_set_message("Comment successfully closed.");
	}
	if(!$doc_id)
		drupal_goto('loan/pendingcomments/0');
	else
		drupal_goto('loan/comments/'.$doc_id);
	//drupal_goto('loan/comments/'.$c->doc_id);
}

function replycomment($cid = '',$pid = '',$doc_id = 0)
{
	global $user;
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loan(s)', 'loan/listloans/'.$array[3].'');
	$breadcrumb[] = l('List of Comment(s)', 'loan/comments/'.$doc_id);
	$breadcrumb[] = 'Reply Form';
	$output = '';
	if(isset($_POST['comment']) && $_POST['commentid'])
	{
		$sql = "SELECT doc_id, commentedby FROM tbl_loan_comment WHERE id = ".$_POST['commentid'];
		$res = db_query($sql);
		$c = db_fetch_object($res);
	}
	drupal_set_breadcrumb($breadcrumb);
	if(isset($_POST['comment']) && $_POST['commentid'] && $_POST['comment'])
	{
		db_query('START TRANSACTION');
		db_query('BEGIN');
		
		$commentedto = $c->commentedby;
		if($user->uid == 1)//Admin user
			$myrole = 0;
		else
			$myrole = getRole($user->uid);
		$updatesql = "UPDATE tbl_loan_comment SET status = 1 WHERE id = ".$_POST['commentid'];
		if(!db_query($updatesql))
			$inserterror = 1;
		$insertsql = "INSERT INTO tbl_loan_comment (doc_id, commentedby, commentedto, comment, pid, module_name) VALUES ('".$c->doc_id."', '".$myrole."', '".$commentedto."', '".$_POST['comment']."', '".$_POST['pid']."','loan')";
		if(!db_query($insertsql))
			$inserterror = 1;
		if($inserterror)
		{
			db_query('ROLLBACK');
			drupal_set_message("There is some error while updation.Please try again.");
		}else{
			db_query('COMMIT');
			drupal_set_message("You have replied to the comment successfully.");
		}
		if(!$doc_id)
			drupal_goto('loan/pendingcomments/0');
		else
			drupal_goto('loan/comments/'.$_POST['doc_id']);
	
	}
		if(isset($_POST['comment']) && !$_POST['comment'])
		{
			form_set_error("comment","Comment field can not be blank");
			$output .= '<script>$(function() {$("textarea[name=comment]").addClass("error");});</script>';
		}
		$output .= <<<EOD
		<br>
		<div id="form-container">
			<form action="" name="commentform" method="post" enctype="multipart/form-data" onSubmit="return commentValidation();">
				<table width="100%" cellpadding="2" cellspacing="2" border="0" id="wrapper2">
				
					<tr class="oddrow">
					
						<td align="center" colspan=2><h2>Reply Comment Form</h2></td>   
					
					</tr>
				
					<tr class="evenrow">
						<td><div class="loantext1">Comment: <span title="This field is required." class="form-required">*</span></div>
						<div class="loanform"><div class='form-item'><textarea name="comment" rows="5" cols="30" id="commentid" onkeypress="return textonlywithdotnemax(event,'commentid',200)" maxlength="200"></textarea></div></div></td>
					</tr>
				<tr class="oddrow"><td align="center" class="back">
				<input type="hidden" value="$cid" name="commentid">
EOD;
		$output .= <<<EOD
				<input type="hidden" value="$pid" name="pid">
				<input type="hidden" value="$doc_id" name="doc_id">
				<input type="submit" class="form-submit" value="Send" id="submit" name="ls"/>
EOD;
				$output .= '&nbsp;&nbsp;'.l("Back",'loan/comments/'.$doc_id);
		$output .= <<<EOD
				</td></tr>
			</table></form>
		</div>
EOD;
	return $output;
}
//************************* LIST COMMENTS FOR A LOAN ************************
function loan_comments($doc_id='')
{
	global $user,$base_url;
	$limit = (int)getMessage( 'dsjedispatchAccount', 'code04', NULL);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loan(s)', 'loan/listloans/'.$array[3].'');
	$breadcrumb[] = 'List of Comment(s)';
	drupal_set_breadcrumb($breadcrumb);
	if($doc_id)
		$doc_id = intval($doc_id);
	$ac = $base_url.'/loan/comments/'.$doc_id;	
	$docidcon = "";
	if($doc_id)
		$docidcon = " AND doc_id = '".$doc_id."' ";
	$header = array(
		array('data' => t('S. No')),
		array('data' => t('Comment'), 'field' => 'comment', 'sort' => 'asc'),
		array('data' => t('Commented by'), 'field' => 'commentedby', 'sort' => 'asc'),
		array('data' => t('Commented to'), 'field' => 'commentedto', 'sort' => 'asc'),
		array('data' => t('Commented On'), 'field' => 'comment_date', 'sort' => 'desc'),
		array('data' => t('Status'), 'field' => 'status', 'sort' => 'asc'),
		array('data' => t('Action'), 'class' => 'addeditview'),
	);
	
	if($user->uid == 1)//Admin user
		$role = 0;
	else
		$role = getRole($user->uid);
	
	if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
		$val = '%'.strtoupper($_REQUEST['searchtext']).'%';
		$val=addslashes($val);	 
		$query = "SELECT * FROM tbl_loan_comment lc,role r where (lc.commentedby = r.rid OR lc.commentedto = r.rid) AND (commentedto = '".$role."' OR commentedby = '".$role."') AND (UPPER(comment) LIKE '".$val."' OR IF(status = 0,'OPEN','CLOSED') LIKE '".$val."' OR DATE_FORMAT(comment_date,'%%d-%%m-%%Y') LIKE '%".$val."%' OR  UPPER(r.name) LIKE '".$val."')  AND lc.module_name = 'loan' $docidcon GROUP BY lc.id ".tablesort_sql($header);

		$sqlcount = "SELECT COUNT(DISTINCT(lc.id)) AS count FROM tbl_loan_comment lc,role r where (lc.commentedby = r.rid OR lc.commentedto = r.rid) AND (commentedto = '".$role."' OR commentedby = '".$role."') AND (UPPER(comment) LIKE '".$val."' OR IF(status = 0,'OPEN','CLOSED') LIKE '".$val."' OR DATE_FORMAT(comment_date,'%%d-%%m-%%Y') LIKE '%".$val."%' OR UPPER(r.name) LIKE '".$val."')  AND lc.module_name = 'loan' $docidcon ".tablesort_sql($header);
		$rscount = db_query($sqlcount);
		$rscounter = db_fetch_object($rscount);
	}else{
/*		$query = "SELECT uid, name FROM users WHERE status = 1";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$userarr[$row->uid] = $row->name;
		}
*/
		$query =  "SELECT * FROM tbl_loan_comment WHERE (commentedto = '".$role."' OR commentedby = '".$role."')  AND module_name = 'loan' $docidcon ".tablesort_sql($header);
		//echo $query;exit;
	}
 $output = <<<EOD
 	<form method="POST" action="$ac"><table width="100%" border="0" cellspacing="1" cellpadding="1" id="wrapper">
	<tr><td colspan="3" class="searchrecord">
EOD;
	if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
	$output .= t(getMessage( 'dsjedispatchAccount', 'code03', array("0"=>$rscounter->count)))." | ".l('View All','loan/comments/'.$doc_id);
	}
	
	$output .='</td></tr>';
	
	$addurl = ($doc_id)?l('Add Comment',"loan/commentform/".$doc_id):'';
   	$lising = 'List of Comments';
		
	$output .='<tr>'.
	'<td colspan="3" class="tblHeaderLeft">'.$lising.'<span class="addrecord">'.$addurl.'</span></td>'.

	'   <td colspan="3" class="tblHeaderRight">'.
	'<input type="text" name="searchtext" value="'.$_POST['searchtext'].'">'.
	'&nbsp;<input type="submit" name="search" value="Search"></td></tr>'.
	'</table></form>';
	
	$result = pager_query($query, $limit);

	if($_REQUEST['page']){
		$counter = $_REQUEST['page']*$limit;
	}else{
		$counter = 0;
	}
	$query = "SELECT r.* FROM tbl_workflow w, tbl_workflow_details wdetail, role r WHERE w.workflow_id = wdetail.workflow_id AND r.rid = wdetail.role AND w.workflow_name = 'loan' "; 
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$roles[$row->rid] = $row->name;
	}
	while($row=db_fetch_object($result)) {
		$counter++;
		$replyurl = '';
		$closeurl = '';
		if($row->pid)
		{
			$pid = $row->pid;
			$sql = "SELECT commentedby FROM tbl_loan_comment WHERE id = ".$row->pid;
			$res = db_query($sql);
			$c = db_fetch_object($res);
			if($c->commentedby == $role && $row->status == 0)
				$closeurl = l("Close","loan/closecomment/".$row->id.'/'.$doc_id);
			else
				$closeurl = '';
		}else{
			$pid = $row->id;
			if($row->status == 0 && $row->commentedby == $role)
				$closeurl = l("Close","loan/closecomment/".$row->id.'/'.$doc_id);
		}
		if($row->status == 0 && $row->commentedto == $role)
			$replyurl = l("Reply","loan/replycomment/".$row->id."/".$pid."/".$doc_id);
		if($replyurl && $closeurl)
			$closeurl = ' | '.$closeurl;
		$st = ($row->status)?'Closed':'Open';
		if($row->commentedby == 0)
			$commentedby = 'Admin';
		else
			$commentedby = $roles[$row->commentedby];
		$rows[] = array(
			
			array('data' => $counter),
			array('data' => $row->comment),
			array('data' => $commentedby),
			array('data' => $roles[$row->commentedto]),
			array('data' => date("d-m-Y",strtotime($row->comment_date))),
			array('data' => $st),
			array('data' => $replyurl.$closeurl),
		);
	}
	if($rows== NULL)
		$header=NULL;
	if($counter < 1)
	{
		$output .= "<br><center><b>No Comments To show.</b></center>";
	}else{
		$output .= theme_table($header,$rows, $attributes = array(), $caption = NULL);
		$output .= theme('pager', NULL, $limit,0 );
	}
	$output .= '<br><div class="back" align="center">'.l("Back",'loan/listloans/').'</div>';
	return $output;
}
function commentform($doc_id = '')
{
	global $user;
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loan(s)', 'loan/listloans/'.$array[3].'');
	$breadcrumb[] = l('List of Comment(s)', 'loan/comments/'.$doc_id);
	$breadcrumb[] = l('Comment Form', 'loan/commentform/'.$doc_id);
	
	drupal_set_breadcrumb($breadcrumb);
	$role = getRole($user->uid);
	$output = '';
	if(isset($_POST['comment']) && $_POST['level'] && $_POST['comment'])
	{
		if($_POST['doc_id'])
		{
			db_query('START TRANSACTION');
			db_query('BEGIN');
			if($user->uid == 1)//Admin user
				$myrole = 0;
			else
				$myrole = getRole($user->uid);
			if(isset($_POST['commentid']) && $_POST['commentid'])
			{
				$updatesql = "UPDATE tbl_loan_comment SET status = 0 WHERE id = ".$_POST['commentid'];
				if(!db_query($updatesql))
					$inserterror = 1;
			}
			$insertsql = "INSERT INTO tbl_loan_comment (doc_id, commentedby, commentedto, comment, module_name) VALUES ('".$_POST['doc_id']."', '".$myrole."', '".$_POST['level']."', '".$_POST['comment']."','loan')";
			if(!db_query($insertsql))
			{
				$inserterror = 1;
			}
			if($inserterror)
			{
				db_query('ROLLBACK');
				drupal_set_message("There is some error while updation.Please try again.");
				drupal_goto('loan/comments/'.$_POST['doc_id']);
			}else{
				db_query('COMMIT');
				$query = "SELECT r.* FROM tbl_workflow w, tbl_workflow_details wdetail, role r WHERE w.workflow_id = wdetail.workflow_id AND r.rid = wdetail.role AND w.workflow_name = 'loan' AND r.rid =  '".$_POST['level']."'"; 
				$res = db_query($query);
				$role = db_fetch_object($res);
				drupal_set_message("You have successfully sent the comment to $role->name.");
			}
			drupal_goto('loan/comments/'.$_POST['doc_id']);
			
		}else{
			drupal_set_message("There is some error while updation.Please try again.");
			drupal_goto('loan/comments/'.$_POST['doc_id']);
		}
	}else{
		if(isset($_POST['comment']))
		{
			form_set_error("comment","Comment field can not be blank");
			$output .= '<script>$(function() {$("textarea[name=comment]").addClass("error");});</script>';
		}
		$output .= <<<EOD
		<br>
		<div id="form-container">
			<form action="" name="commentform" method="post" enctype="multipart/form-data" onSubmit="return commentValidation();">
				<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper2">
				
					<tr class="oddrow">
					
						<td align="center" colspan=2><h2>Comment Entry Form</h2></td>   
					
					</tr>
				
					<tr class="evenrow">
						<td><div class="loantext1">To: <span title="This field is required." class="form-required">*</span></div>
						<div class="loanform1">
						<select name="level">
EOD;
			$query = "SELECT r.* FROM tbl_workflow w, tbl_workflow_details wdetail, role r WHERE w.workflow_id = wdetail.workflow_id AND r.rid = wdetail.role AND w.workflow_name = 'loan' "; 
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				if($row->rid != $role)
					$output .= '<option value="'.$row->rid.'">'.ucwords($row->name).'</option>';
			}
						
		$output .= <<<EOD
						</select>
						</div></td>
					</tr>
					<tr class="oddrow">
						<td class="form-text1"><div class="loantext1">Comment: <span title="This field is required." class="form-required">*</span></div>
			<div class='loanform1'><textarea name="comment" id="commentid" rows="5" cols="30" onkeypress="return textonlywithdotnemax(event,'commentid',200)" maxlength="200"></textarea></div></td>
					</tr>
					<tr class="evenrow"><td class="back" align="center">
EOD;
			if($doc_id)
                $output .= l("Back",'loan/comments/'.$doc_id);
            else
            	$output .= l("Back",'loan/listloans/');
$output .= <<<EOD
					&nbsp; &nbsp;<input type="submit" class="form-submit" value="Save" id="submit" name="ls"/></td></tr>
				</table>
				<input type="hidden" value="$commentto" name="commentto">
EOD;
		$output .= <<<EOD
				<input type="hidden" value="$doc_id" name="doc_id">
				
			</form>
		</div>
EOD;
	return $output;
	}
}
//********************************* LIST ALL PENDING COMMENTS ***************************

function comment_on_application()
{
	global $user;
	global $base_url;
	//$limit = 2;
	$limit = (int)getMessage( 'dsjedispatchAccount', 'code04', NULL);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	
	$header = array(
		array('data' => t('S. No.')),
		array('data' => t('Docket'), 'field' => 'doc_id', 'sort' => 'desc'),
		array('data' => t('Comment'), 'field' => 'comment', 'sort' => 'asc'),
		array('data' => t('Commented by'), 'field' => 'uid', 'sort' => 'asc'),
		array('data' => t('Commented On'), 'field' => 'comment_date', 'sort' => 'desc'),
		array('data' => t('Action')),
	);

	if($array[0] == '' ) {
		$breadcrumb[] = l('List Of Loans', 'loan/listloans/'.$array[3].'');
	}  
	drupal_set_breadcrumb($breadcrumb);
	$query = "SELECT uid, name FROM users WHERE status = 1";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$userarr[$row->uid] = $row->name;
	}
	$pendingcommentsql = "SELECT * FROM tbl_loan_comment WHERE commentedto = '".$user->uid."' AND status = 1 GROUP BY doc_id ".tablesort_sql($header);
	$res = db_query($pendingcommentsql);
	while($row=db_fetch_object($res)) {
		$counter++;
		$replyurl = l("Reply","loan/comments/".$row->doc_id."/".$row->commentedby."/".$row->id);
		$viewurl = l("View All Comments","loan/comments/".$row->doc_id);
		$rows[] = array(
			
			array('data' => $counter),
			array('data' => $row->doc_id),
			array('data' => $row->comment),
			array('data' => $userarr[$row->uid]),
			array('data' => date("d-m-Y",strtotime($row->comment_date))),
			array('data' => $viewurl."|".$replyurl),
		);
	}
	if($rows== NULL)
		$header=NULL;

	$output .= theme_table($header,$rows, $attributes = array(), $caption = NULL);
	$output .= theme('pager', NULL, $limit,0 );
	$output .= '<div align="center">'.l("Back",'loan/repayment/'.$doc_id).'</div>';
	return $output;
}
function pendingcomments($docket = '')
{
	global $user;
	$cuser = user_load($user->uid);
	$roles = implode(',',array_flip($cuser->roles));


	$limit = (int)getMessage( 'dsjedispatchAccount', 'code04', NULL);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loan(s)', 'loan/listloans/');
	$breadcrumb[] = 'List of Pending Comment(s)';
	
	$header = array(
		array('data' => t('S. No')),
		array('data' => t('Loan Docket'), 'field' => 'doc_id', 'sort' => 'desc'),
		array('data' => t('Comment'), 'field' => 'comment', 'sort' => 'asc'),
		array('data' => t('Commented by'), 'field' => 'uid', 'sort' => 'asc'),
		array('data' => t('Commented On'), 'field' => 'comment_date', 'sort' => 'desc'),
		array('data' => t('Action'), 'class' => 'addeditview'),
	);
	
	drupal_set_breadcrumb($breadcrumb);
	
	$append = '';
	$conditionstr = '';
	$corp_branch = getCorporationBranch($user->uid);

	$role = getRole($user->uid);
	if($corp_branch && ($role != 5 && $role != 6 && $role != 18 && $role != 19 && $role != 37))
	{
		$conditionstr = "AND wd.corp_branch = $corp_branch";
	}
	//if($corp_branch)
		//$conditionstr = " AND wd.corp_branch = $corp_branch";
	if($docket)
		$append = " AND doc_id = $docket ";
	if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
		$val = '%'.strtoupper($_REQUEST['searchtext']).'%'; $val=addslashes($val);	 
		$query = "SELECT * FROM tbl_loan_comment lc,role r, tbl_workflow_docket wd where lc.doc_id = wd.doc_id AND (lc.commentedto = r.rid OR lc.commentedby = r.rid) AND (lc.commentedto = '".$role."') AND lc.status = 0 AND (UPPER(comment) LIKE '".$val."' OR UPPER(lc.doc_id) LIKE '".$val."' OR lc.status LIKE '".$val."' OR DATE_FORMAT(comment_date,'%%d-%%m-%%Y') LIKE '%".$val."%' OR  UPPER(r.name) LIKE '".$val."')  AND lc.module_name = 'loan' $conditionstr $append  GROUP BY lc.id ".tablesort_sql($header);

		$sqlcount = "SELECT COUNT(DISTINCT(lc.id)) AS count FROM tbl_loan_comment lc,role r, tbl_workflow_docket wd where lc.doc_id = wd.doc_id AND (lc.commentedto = r.rid OR lc.commentedby = r.rid) AND (lc.commentedto = '".$role."') AND lc.status = 0 AND (UPPER(lc.comment) LIKE '".$val."' OR lc.status LIKE '".$val."' OR UPPER(lc.doc_id) LIKE '".$val."' OR DATE_FORMAT(comment_date,'%%d-%%m-%%Y') LIKE '%".$val."%' OR UPPER(r.name) LIKE '".$val."')  AND lc.module_name = 'loan' $conditionstr $append ";
		//$query = "SELECT * FROM tbl_loan_comment where (UPPER(comment) LIKE '".$val."' OR UPPER(comment_date) LIKE '".$val."' OR UPPER(doc_id) LIKE '".$val."') AND module_name = 'loan' $conditionstr ".tablesort_sql($header);
		//$sqlcount = "SELECT COUNT(*) AS count FROM tbl_loan_comment where (UPPER(comment) LIKE '".$val."' OR UPPER(comment_date) LIKE '".$val."' OR UPPER(doc_id) LIKE '".$val."')  AND module_name = 'loan' $conditionstr ".$append.tablesort_sql($header);
		$rscount = db_query($sqlcount);
		$rscounter = db_fetch_object($rscount);
	}else{
		$query = "SELECT uid, name FROM users WHERE status = 1";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$userarr[$row->uid] = $row->name;
		}

		$query = "SELECT lc.* FROM tbl_loan_comment lc, tbl_workflow_docket wd WHERE lc.doc_id = wd.doc_id AND lc.commentedto = '".$role."' AND lc.status = 0 AND lc.module_name = 'loan' $conditionstr ".$append.tablesort_sql($header); 
			//return $query;
	}
	//echo $query;
 $output = <<<EOD
 	<form method="POST" action=""><table width="100%" border="0" cellspacing="1" cellpadding="1" id="wrapper">
	<tr><td colspan="3" class="searchrecord">
EOD;
	if(isset($_REQUEST['searchtext']) && $_REQUEST['searchtext']!=''){
	$output .= t(getMessage( 'dsjedispatchAccount', 'code03', array("0"=>$rscounter->count)))." | ".l('View All','loan/pendingcomments/0');
	}
	
	$output .='</td></tr>';
	
   	$lising = 'List of Pending Comments';
		
	$output .='<tr>'.
	'<td colspan="3" class="tblHeaderLeft">'.$lising.'<span class="addrecord">'.$addurl.'</span></td>'.

	'   <td colspan="3" class="tblHeaderRight">'.
	'<input type="text" name="searchtext" value="'.$_POST['searchtext'].'">'.
	'&nbsp;<input type="submit" name="search" value="Search"></td></tr>'.
	'</table></form>';
	
	$result = pager_query($query, $limit);

	if($_REQUEST['page']){
		$counter = $_REQUEST['page']*$limit;
	}else{
		$counter = 0;
	}
	$query = "SELECT r.* FROM tbl_workflow w, tbl_workflow_details wdetail, role r WHERE w.workflow_id = wdetail.workflow_id AND r.rid = wdetail.role AND w.workflow_name = 'loan' "; 
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$rolearr[$row->rid] = $row->name;
	}
	while($row=db_fetch_object($result)) {
		$counter++;
		if($row->pid)
			$pid = $row->pid;
		else
			$pid = $row->id;
		$replyurl = l("Reply","loan/replycomment/".$row->id."/".$pid);
		if($row->pid)
		{
			$sql = "SELECT commentedby FROM tbl_loan_comment WHERE id = ".$row->pid;
			$res = db_query($sql);
			$c = db_fetch_object($res);
			if($c->commentedby == $user->uid)
				$closeurl = ' | '.l("Close","loan/closecomment/".$row->id);
			else
				$closeurl = '';
		}else{
			$closeurl = ' | '.l("Close","loan/closecomment/".$row->id);
		}	
		if($row->commentedby == 0)
			$commentedby = 'Admin';
		else
			$commentedby = $rolearr[$row->commentedby];
		$rows[] = array(
			array('data' => $counter),
			array('data' => $row->doc_id),
			array('data' => $row->comment),
			array('data' => $commentedby),
			array('data' => date("d-m-Y",strtotime($row->comment_date))),
			array('data' => $replyurl.$closeurl),
		);
	}
	if($rows== NULL)
		$header=NULL;
	$output .= theme_table($header,$rows, $attributes = array(), $caption = NULL);
	$output .= theme('pager', NULL, $limit,0 );

	$output .= '<br><div class="back" align="center">'.l("Back",'loan/listloans').'</div>';
	return $output;
	
}
?>