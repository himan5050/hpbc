<?php

global $user;


session_start();
function close_comment($cid)
{
	global $user;
	db_query("update tbl_loan_comment SET status=1 where id = '".$cid."' AND status = 0 ");
	$message = 'Comment has been successfully closed';
	drupal_set_message($message);
	drupal_goto('pending-comment-list');
}
function reply_form($form_state,$doc_id,$level,$cid){
	global $user;
    $array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Comments', 'pending-comment-list');
	$breadcrumb[] = l('Comment Reply','comment-reply-form/'.$doc_id.'/'.$level.'/'.$cid);
	
	drupal_set_breadcrumb($breadcrumb);


 $form['empid'] = array(
	'#type' => 'hidden',
	'#value'=>$level,
	);
$form['cid'] = array(
'#type' => 'hidden',
	'#value' =>$cid,
);
 $form['doc_id'] = array(
	'#type' => 'hidden',
	'#value'=>$doc_id,
	);
 
	$form['comment'] = array(
	'#type' =>'textarea',
	'#title' => t('Query/Comment'),
	'#required' => TRUE,
	'#default_value' =>'',
	'#maxlength' =>200,
	'#attributes' => array('onkeypress' =>'return textonlywithdotnemax(event,"edit-comment",200)'),
	);
	$form['submit'] = array(
	'#type' => 'submit',
		'#value' =>t('Send'),
	);
return $form;
} 

function reply_form_submit($form,&$form_state){
global $user;
$to = $form_state['values']['empid'];
$doc_id = $form_state['values']['doc_id'];
$reply = $form_state['values']['comment'];
$cid = $form_state['values']['cid'];


$by = getRole($user->uid);
//docidtoempid($doc_id)
if(getRole($user->uid) == 13 ||getRole($user->uid) == 6 || getRole($user->uid) == 19){
db_query("insert into tbl_loan_comment (commentedby,uid,doc_id,comment,status) VALUES ('".$by."','".$to."','".$doc_id."','".$reply."',0)");
db_query("update tbl_loan_comment SET status=1 where id = '".$cid."' AND status = 0");
}
else{
db_query("insert into tbl_loan_comment (commentedby,commentedto,doc_id,comment,status) VALUES ('".$user->uid."','".$to."','".$doc_id."','".$reply."',0)");
db_query("update tbl_loan_comment SET status=1 where id = '".$cid."' AND status = 0 ");
}
    //db_query("update tbl_workflow_task  SET status=2 where doc_id='".$doc_id."' AND level=1");
//	db_query("update tbl_workflow_task  SET status=0,comment='".$reply."' where doc_id='".$doc_id."' AND level=2");
	//db_query("update tbl_resignation  SET status=6 where doc_id='".$doc_id."'");
	//db_query("insert into tbl_loan_comment (commentedby,commentedto,doc_id,comment,status) VALUES ('".$by."','".$to."','".$doc_id."','".$reply."',6)");
	//db_query("update tbl_loan_comment SET status=6 where commentedby=2 AND commentedto=1 AND doc_id='".$doc_id."'");
	//db_query("update tbl_loan_comment SET status=1 where commentedby='".$to."' AND commentedto='".$by."' AND doc_id='".$doc_id."'");
	//db_query("insert into tbl_loan_comment (commentedby,commentedto,doc_id,comment,status) VALUES ('".$level."','".$to."','".$doc_id."','".$reply."',0)");
	
	//$msgto =getrolebylevel($to); 
	$message = getMessage('resignation', 'code12', array("0"=>getrolenames($to)));
	drupal_set_message($message);

	drupal_goto('pending-comment-list');




}


function comment_creply_page($doc_id,$level,$cid){

$output = drupal_get_form('reply_form',$doc_id,$level,$cid);
return $output;

}


function comment_search_form($form_state,$doc_id){

$form['doc_id'] = array(
'#type' => 'hidden',
	'#value' =>$doc_id,
	
);

$form['search_box']=array(
	'#type' => 'textfield',
	'#title' => '',
	'#required' => TRUE,
	'#size' => 60,
	'#prefix' => '<div class="listsearchie7_leave">',
);

$form['search'] = array(
	'#type' => 'submit',
	'#value' =>t('Search'),
	'#suffix' => '</div>'
);
return $form;
}




function comment_search_form_submit($form,&$form_state){
	$keydata = $form_state['values']['search_box'];
	$doc_id = $form_state['values']['doc_id'];
if(arg(0)=='pending-comment-list' || arg(0)=='view-pending-comment'){
drupal_goto('view-pending-comment/'.$keydata.'/'.$doc_id);
}else{
drupal_goto('comment-search-list/'.$keydata.'/'.$doc_id);
}
}

function comment_list_page($doc_id){
global $user;


$to=getRole($user->uid);
//else if(getRole($user->uid) == 19){ $to=2;}
//else if(getRole($user->uid) == 6){ $to=3;}

    
	$sql = "SELECT * FROM tbl_loan_comment where doc_id='".$doc_id."'";
	$output = commentsearch($sql,$doc_id) ;
	
return $output;
}

function comment_search_page($keydata,$doc_id){
    global $user;
	

$val = '%'.strtoupper($keydata).'%'; $val=addslashes($val);
 $to=getRole($user->uid);


  $sql = " SELECT * FROM tbl_loan_comment INNER JOIN role ON (tbl_loan_comment.commentedto = role.rid) OR (tbl_loan_comment.commentedby = role.rid) LEFT JOIN tbl_joinings ON (tbl_loan_comment.commentedto = tbl_joinings.program_uid) OR (tbl_loan_comment.commentedby = tbl_joinings.program_uid) OR (tbl_loan_comment.uid = tbl_joinings.program_uid) where (comment LIKE '".$val."' OR status LIKE '".$val."' OR UPPER(role.name) LIKE '".$val."' OR UPPER(tbl_joinings.employee_name) LIKE '".$val."' OR DATE_FORMAT(comment_date,'%%d-%%m-%%Y') LIKE '".$val."' OR commentedto LIKE '".$val."' OR commentedby LIKE '".$val."') AND doc_id='".$doc_id."'  GROUP BY id";

  /*
 $sql = " SELECT tbl_loan_comment.comment,tbl_loan_comment.uid as luid,tbl_loan_comment.uid,tbl_loan_comment.commentedto,tbl_loan_comment.commentedby,tbl_loan_comment.comment_date FROM tbl_loan_comment 
INNER JOIN role ON (tbl_loan_comment.commentedto = role.rid) OR (tbl_loan_comment.commentedby = role.rid) 
INNER JOIN users ON (tbl_loan_comment.commentedto = users.uid) OR (tbl_loan_comment.commentedby = users.uid) OR (tbl_loan_comment.uid = users.uid)
where (comment LIKE '".$val."' OR UPPER(users.name) LIKE '".$val."' OR UPPER(role.name) LIKE '".$val."'  OR comment_date LIKE '".date('Y-m-d',strtotime($keydata))."%') AND doc_id='".$doc_id."'";
  */

$output = commentsearch($sql,$doc_id) ;
	
return $output;
}

function pending_comment_page(){

global $user;
$array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('Pending Comment List', 'pending-comment-list');
	
	drupal_set_breadcrumb($breadcrumb);
$to = getRole($user->uid);
$doc_id='';
    
	$sql = "SELECT * FROM tbl_loan_comment where (commentedto='".$to."' OR uid='".$user->uid."') AND status=0";
	$output = search_pending_query($sql,$doc_id) ;
	
return $output;

}


function commentsearch($sqlquery,$doc_id){
global $user;
$array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	if(getRole($user->uid)==13 || getRole($user->uid)==19 || getRole($user->uid)==6){
	$breadcrumb[] = l('List of Resignation', 'resignationlist');
	}else{
	$breadcrumb[] = l('Resignation', 'resignationlist/');
	$breadcrumb[] = l('Query Detail', 'viewquery/'.$doc_id.'/'.getuidtoempID($user->uid));
	}
	$breadcrumb[] = l('List of Comments', 'comment-list/'.$doc_id);
	drupal_set_breadcrumb($breadcrumb);


$limit =(int)getMessage('resignation', 'code04', NULL);
$header = array(
		array('data' => t('S. No.')),
		array('data' => t('Comment'), 'field' => 'tbl_loan_comment.comment', 'sort' => 'desc'),
		array('data' => t('Commented by'), 'field' => 'tbl_loan_comment.commentedby', 'sort' => 'asc'),
		array('data' => t('Commented to'), 'field' => 'tbl_loan_comment.commentedto', 'sort' => 'asc'),
		array('data' => t('Commented On'), 'field' => 'tbl_loan_comment.comment_date', 'sort' => 'desc'),
		array('data' => t('Action')),
	);

	 $sql = $sqlquery.tablesort_sql($header);
	$count_query = "SELECT COUNT(*) as mycount FROM (" . $sql . ") AS count_query";
	$cres = db_query($count_query);
	$crs = db_fetch_object($cres);
	$totalcount = $crs->mycount;
	$result = pager_query($sql,10,0,$count_query);
	if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
if($result){
   

$query2 = "SELECT * from tbl_joinings ";
	$res2 = db_query($query2);
	while($row2 = db_fetch_object($res2))
	{
		$userarr[$row2->employee_id] = $row2->employee_name;
	}

 while($rs = db_fetch_object($result)){
	    $counter++;	    
$searchdoc_id = $rs->doc_id;


$doc_id2=$rs->doc_id;
$level = $rs->commentedby;
$me = getRole($user->uid);

if($rs->commentedby==13 || $rs->commentedby==19 || $rs->commentedby==6 && $rs->uid ==0){
$commentby = getRoleName($rs->commentedby);
}
else{
$commentby = ucwords($userarr[getuidtoempID($rs->commentedby)]);

}
if(($rs->commentedto==13 || $rs->commentedto==19 || $rs->commentedto==6) && $rs->uid ==0){
$commentto = getRoleName($rs->commentedto);
}
else{
$commentto = ucwords($userarr[getuidtoempID($rs->uid)]);
if($commentto ==''){
$commentto = ucwords($userarr[getuidtoempID($rs->commentedto)]);
}
}
//$close = l('Close','');
$ssl = "select status,doc_id,id from tbl_loan_comment where doc_id='".$doc_id2."' AND commentedto='".$me."' ORDER BY comment_date DESC";
$sres = db_query($ssl);
$sr = db_fetch_object($sres);
if($sr->status == 0 && $rs->commentedto == $me){
$links = l('Reply','comment-reply-form/'.$doc_id2.'/'.$level.'/'.$sr->id);
}

	  	$rows[] = array(
			array('data' => $counter),
			array('data' => ucwords($rs->comment)),
			array('data' => $commentby),
			array('data' => $commentto),
			array('data' => date("d-m-Y",strtotime($rs->comment_date))),
			array('data' =>$links),
		);
	
	  }
}
if($rows== NULL)
	$header=NULL;
//$output .='<div class="uploadcss">';	
    if(arg(0)=='comment-search-list'){
	 $records = t(getMessage('resignation', 'code03', array("0"=>$totalcount)));
	 $output .='<table  id="wrapper" class="searchrecord"><tr><td>'.$records.' | '.l('View All','comment-list/'.$doc_id).'</td></tr></table>';
	}
	$title = t(getMessage('resignation', 'code11',NULL));
	 $output .='<table id="wrapper"><tr><td class="tblHeaderLeft">'.$title.'<span class="addrecord">'.l('Add Comment','send-comment/'.$doc_id.'/'.$user->uid).'</span></td><td class="tblHeaderRight">'.drupal_get_form('comment_search_form',$doc_id).'</td></tr></table>';
		 if(getRole($user->uid) == 13 || getRole($user->uid) == 6 || getRole($user->uid) == 19){
			$backlink = l('Back','resignationlist');
			}
			else{
			$backlink = l('Back','viewquery/'.$doc_id.'/'.getuidtoempID($user->uid));
			}
	
	$output .=theme_table($header,$rows);
	 $output .='<br><span class="back" align="center"><center>'.$backlink.'</center></span>';
	 $output .=theme('pager', NULL, 20,0 );
 //$output .='<div>';
  
	return $output;
  }





function send_comment_form($form_state,$doc_id,$level){
global $user;
    $array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	if(getRole($user->uid)==13 || getRole($user->uid)==19 || getRole($user->uid)==6){
	$breadcrumb[] = l('List of Resignation', 'resignationlist');
	}else{
	$breadcrumb[] = l('View Profile', 'viewprofile/'.docidtoempid($doc_id));
	$breadcrumb[] = l('Query Detail', 'viewquery/'.$doc_id.'/'.docidtoempid($doc_id));
	}
	$breadcrumb[] = l('List of Comments', 'comment-list/'.$doc_id);
	$breadcrumb[] = l('Write Your Comment','send-comment/'.$doc_id.'/'.$level);
	
	
	
	drupal_set_breadcrumb($breadcrumb);


 $form['empid'] = array(
	'#type' => 'hidden',
	'#value'=>$level,
	);
 $form['doc_id'] = array(
	'#type' => 'hidden',
	'#value'=>$doc_id,
	);
	$form['query_to'] = array(
	'#type' => 'select', 
    '#title' => t('Query/Comment for'), 
     '#required' => TRUE, 
	'#options' => commenttolist($doc_id),
    );
	$form['comment'] = array(
	'#type' =>'textarea',
	'#title' => t('Query/Comment'),
	'#required' => TRUE,
	'#default_value' =>'',
	'#maxlength' =>200,
	'#attributes' => array('onkeypress' =>'return textonlywithdotnemax(event,"edit-comment",200)'),
	);
	$form['submit'] = array(
	'#type' => 'submit',
		'#value' =>t('Send'),
	);
return $form;
} 


function send_comment_page($doc_id,$level){
 global $user;

 $output .=drupal_get_form('send_comment_form',$doc_id,$level); 
return $output;
}

function send_comment_form_submit($form,&$form_state){
global $user;


$emp_id = $form_state['values']['empid'];
$doc_id = $form_state['values']['doc_id'];
$reply = $form_state['values']['comment'];
 $query_to = $form_state['values']['query_to'];

if(getRole($emp_id) == 13 || getRole($emp_id) == 6 ||getRole($emp_id) == 19){$level = getRole($emp_id);}
else{$level = $emp_id;}

//echo getRole($emp_id);exit;

		    $to=$query_to;
		if($query_to == 13 || $query_to== 19 || $query_to == 6){
					
    //db_query("update tbl_workflow_task  SET status=0 where doc_id='".$doc_id."' AND level='".$level."'");
	db_query("insert into tbl_loan_comment (commentedby,commentedto,doc_id,comment,status) VALUES ('".$level."','".$query_to."','".$doc_id."','".$reply."',0)");
			
		}
		else{
			
		$empsql ="select program_uid from tbl_joinings where employee_id='".$to."'";
		$empres = db_query($empsql);
		$emprs = db_fetch_object($empres);
		$empuid =$emprs->program_uid;
			//db_query("update tbl_workflow_task  SET status=0 where doc_id='".$doc_id."' AND level='".$level."'");
	db_query("insert into tbl_loan_comment (commentedby,uid,doc_id,comment,status) VALUES ('".$level."','".$empuid."','".$doc_id."','".$reply."',0)");
		}

	$query = "SELECT r.* FROM tbl_workflow w, tbl_workflow_details wdetail, role r WHERE w.workflow_id = wdetail.workflow_id AND r.rid = wdetail.role AND w.workflow_id = 8 "; 
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		$roles[$row->rid] = $row->name;
	}
	if($query_to == 13 || $query_to== 19 || $query_to == 6){
		$message = getMessage('resignation', 'code12', array("0"=>$roles[$query_to]));
	}else{
		$message = getMessage('resignation', 'code12', array("0"=>empname($query_to)));
	}
	drupal_set_message($message);
	
	
	drupal_goto('comment-list/'.$doc_id);

	//drupal_goto('viewprofile/'.$emp_id);



}


function commenttolist($doc_id){
	
	global $user;
$query = "SELECT r.* FROM tbl_workflow w, tbl_workflow_details wdetail, role r WHERE w.workflow_id = wdetail.workflow_id AND r.rid = wdetail.role AND w.workflow_name = 'resignation' AND r.rid !='".getRole($user->uid)."'"; 
			$res = db_query($query);
			$senderarray[''] = '--Select--';
			if($res){
				while($row = db_fetch_object($res))
				{
					$senderarray[$row->rid] = ucwords($row->name);
				}
			}

 $usersql = "select emp_id from tbl_resignation where doc_id='".$doc_id."' AND emp_id !='".$user->uid."'";

$userres = db_query($usersql);
$userrs = db_fetch_object($userres);
if($userrs){
$senderarray[getuidtoempID($userrs->emp_id)] = ucwords(getUidtoEmployeeName($userrs->emp_id).'('.getuidtoempID($userrs->emp_id).')');
}		
return $senderarray;


}
function view_pending_query($keydata,$doc_id){
	 global $user;
	//global $user;
$array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('Pending Comment List', 'pending-comment-list');
	
	drupal_set_breadcrumb($breadcrumb);

$val = '%'.strtoupper($keydata).'%'; $val=addslashes($val);
 $to=getRole($user->uid);

$doccond = "";
if($doc_id)
	$doccond = " AND doc_id='".$doc_id."'";

    $sql = " SELECT * FROM tbl_loan_comment INNER JOIN role ON (tbl_loan_comment.commentedto = role.rid) OR (tbl_loan_comment.commentedby = role.rid) LEFT JOIN tbl_joinings ON (tbl_loan_comment.commentedto = tbl_joinings.program_uid) OR (tbl_loan_comment.commentedby = tbl_joinings.program_uid) OR (tbl_loan_comment.uid = tbl_joinings.program_uid) where (comment LIKE '".$val."' OR UPPER(role.name) LIKE '".$val."' OR UPPER(tbl_joinings.employee_name) LIKE '".$val."' OR comment_date LIKE '".date('Y-m-d',strtotime($keydata))."%') $doccond  AND (commentedto='".$to."' OR uid='".$user->uid."') AND status=0";

$output = search_pending_query($sql,$doc_id) ;
	
return $output;
}

function search_pending_query($sqlquery,$doc_id){

global $user;
$limit =(int)getMessage('resignation', 'code04', NULL);
$header = array(
		array('data' => t('S. No.')),
		array('data' => t('Comment'), 'field' => 'tbl_loan_comment.comment', 'sort' => 'desc'),
		array('data' => t('Commented by'), 'field' => 'tbl_loan_comment.commentedby', 'sort' => 'asc'),
		array('data' => t('Commented to'), 'field' => 'tbl_loan_comment.commentedto', 'sort' => 'asc'),
		array('data' => t('Commented On'), 'field' => 'tbl_loan_comment.comment_date', 'sort' => 'desc'),
		array('data' => t('Action')),
	);

	 $sql = $sqlquery.tablesort_sql($header);
	$count_query = "SELECT COUNT(*) as mycount FROM (" . $sql . ") AS count_query";
	$cres = db_query($count_query);
	$crs = db_fetch_object($cres);
	$totalcount = $crs->mycount;
	$result = pager_query($sql,10,0,$count_query);
	if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
if($result){
  
$query2 = "SELECT * from tbl_joinings ";
	$res2 = db_query($query2);
	while($row2 = db_fetch_object($res2))
	{
		$userarr[$row2->employee_id] = $row2->employee_name;
	}
$searchdoc_id = '0';

	  while($rs = db_fetch_object($result)){
	    $counter++;	    
//$searchdoc_id = $rs->doc_id;
if($rs->commentedby==13 || $rs->commentedby==19 || $rs->commentedby==6 && $rs->uid ==0){
$commentby = getRoleName($rs->commentedby);
}
else{
$commentby = ucwords($userarr[getuidtoempID($rs->commentedby)]);

}
if(($rs->commentedto==13 || $rs->commentedto==19 || $rs->commentedto==6) && $rs->uid ==0){
$commentto = getRoleName($rs->commentedto);
}
else{
$commentto = ucwords($userarr[getuidtoempID($rs->uid)]);

}

$links = '';
$doc_id2=$rs->doc_id;
$level = $rs->commentedby;
$me = getRole($user->uid);
$ssl = "select status,doc_id,id from tbl_loan_comment where doc_id='".$doc_id2."' AND (commentedto='".$me."' || uid = '".$user->uid."') ORDER BY comment_date DESC";
$sres = db_query($ssl);
$sr = db_fetch_object($sres);
if($me==13 || $me==6 ||$me==19){
	$con = $me;
	$links .= l('Close','close-comment/'.$sr->id);
}
else{$con = $user->uid;}
if($sr->status == 0 && ($rs->uid == $con || $rs->commentedto== $con)){
	if($links)
		$links .= ' | '.l('Reply','comment-reply-form/'.$doc_id2.'/'.$level.'/'.$sr->id);
	else
		$links .= l('Reply','comment-reply-form/'.$doc_id2.'/'.$level.'/'.$sr->id);
}

	  	$rows[] = array(
			array('data' => $counter),
			array('data' => ucwords($rs->comment)),
			array('data' => $commentby),
			array('data' => $commentto),
			array('data' => date("d-m-Y",strtotime($rs->comment_date))),
			array('data' =>$links),
		);
	
	  }
}
if($rows== NULL)
	$header=NULL;
//$output .='<div class="uploadcss">';


    if(arg(0)=='view-pending-comment'){
	 $records = t(getMessage('resignation', 'code03', array("0"=>$totalcount)));
	 $output .='<table  id="wrapper" class="searchrecord"><tr><td>'.$records.' | '.l('View All','pending-comment-list').'</td></tr></table>';
	 $addcommentlink = '';
	}
	
	$title = t(getMessage('resignation', 'code11',NULL));
	 $output .='<table id="wrapper"><tr><td class="tblHeaderLeft">'.$title.'<span class="addrecord"></span></td><td class="tblHeaderRight">'.drupal_get_form('comment_search_form',$searchdoc_id).'</td></tr></table>';
	 
	
	$output .=theme_table($header,$rows);
	 $output .=theme('pager', NULL, 20,0 );
// $output .='<div>';
  
	return $output;



}


function all_list_comment_page($doc_id){
   $array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
    $breadcrumb[] = l('List of Pending Task', 'alr_pending');
	$breadcrumb[] = l('All Comments', 'allcomments/'.arg(1));
	drupal_set_breadcrumb($breadcrumb);
   $sql = "select * from tbl_loan_comment where doc_id='".$doc_id."'";
   $res = db_query($sql);
     
   $output ='<table cellspacing="1" cellpadding="1" border="0" width="100%" id="wrapper"><tr><td class="tblHeaderLeft">All Comments</td><td colspan="3" align="right"></td></tr><tr><th>Comment Date</th><th>Comment</th><th>Commented By</th><th>Commented To</th></tr>';
   
   $i=1;
   while($rs = db_fetch_object($res)){
	   if($i%2==0){
		   $cl = 'even';
		   }
		   else{
			$cl = 'odd';   
			   }
	   
	   $cuser = user_load($rs->uid);
	   $name = $cuser->name;if($rs->comment){
       $output .='<tr class="'.$cl.'"><td>'.date('d-m-Y',strtotime($rs->comment_date)).'</td><td>'.$rs->comment.'</td><td>'.rolename($rs->commentedby).'</td><td>'.rolename($rs->commentedto).'</td></tr>';
	   }
	   $i++;
   }

   return $output .='</table>';
}