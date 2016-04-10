<?php
session_start();

function resignsearch_form(){

$form['search_box']=array(
	'#type' => 'textfield',
	'#title' => '',
	'#required' => TRUE,
	'#size' => 60,
	'#prefix' => '<div class="listsearchie7 frightie7">',
);

$form['search'] = array(
	'#type' => 'submit',
	'#value' =>t('Search'),\
	'#suffix' => '</div>'
);
return $form;
}

function resignsearch_form_submit($form,&$form_state){
	$keydata = $form_state['values']['search_box'];
drupal_goto('resignation-search-list/'.$keydata);

}

function resignation_list_page(){
global $user;


$array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Resignation', 'resignationlist');
	drupal_set_breadcrumb($breadcrumb);
	
	/*$uso="select current_officeid from tbl_joinings where program_uid='".$user->uid."'";
	$usoq=db_query($uso);
	$usor=db_fetch_array($usoq);

	 $sql = "select tbl_resignation.comment as comment, tbl_resignation.doc_id as doc_id,tbl_resignation.effectdate as effectdate,tbl_joinings.employee_id as emp_id ,tbl_resignation.regdate as regdate,tbl_resignation.status as status,tbl_joinings.employee_name as empname,tbl_workflow_task.status as wstatus from tbl_resignation INNER JOIN tbl_joinings ON (tbl_resignation.emp_id = tbl_joinings.program_uid) INNER JOIN tbl_workflow_task ON (tbl_resignation.doc_id=tbl_workflow_task.doc_id) where tbl_workflow_task.status=0 AND tbl_workflow_task.level='".getclevel($user->uid)."' AND tbl_joinings.current_officeid='".$usor['current_officeid']."' GROUP BY tbl_resignation.doc_id ";
	 */


	 $uso="select current_officeid from tbl_joinings where program_uid='".$user->uid."'";
	 $usoq=db_query($uso);
	 $usor=db_fetch_array($usoq);
       
    if(getRole($user->uid) == 19 || getRole($user->uid) == 6){
	  $cond = "GROUP BY tbl_resignation.doc_id";
	}elseif(getRole($user->uid) == 13){
	  $cond = "AND tbl_joinings.current_officeid='".$usor['current_officeid']."' GROUP BY tbl_resignation.doc_id";
	}
	if(getRole($user->uid) == 25)
	{
	//---------------mohit-------------//
	$sql = "SELECT tbl_resignation.doc_id as doc_id,tbl_resignation.effectdate as effectdate,tbl_joinings.employee_id as emp_id ,tbl_resignation.regdate as regdate,tbl_resignation.status as status,tbl_joinings.employee_name as empname,tbl_resignation.emp_id as myid from tbl_resignation INNER JOIN tbl_joinings ON (tbl_resignation.emp_id = tbl_joinings.program_uid) where tbl_resignation.emp_id=".$user->uid;	
		
	/* $sql = "SELECT tbl_workflow_task.comment as comment, tbl_resignation.doc_id as doc_id,tbl_resignation.effectdate as effectdate,tbl_joinings.employee_id as emp_id ,tbl_resignation.regdate as regdate,tbl_resignation.status as status,tbl_joinings.employee_name as empname,tbl_workflow_task.status as wstatus,tbl_resignation.emp_id as myid from tbl_resignation INNER JOIN tbl_joinings ON (tbl_resignation.emp_id = tbl_joinings.program_uid) INNER JOIN tbl_workflow_task ON (tbl_resignation.doc_id=tbl_workflow_task.doc_id) where tbl_workflow_task.uid=".$user->uid;*/
	}else{
		 $sql = "select tbl_workflow_task.comment as comment, tbl_resignation.doc_id as doc_id,tbl_resignation.effectdate as effectdate,tbl_joinings.employee_id as emp_id ,tbl_resignation.regdate as regdate,tbl_resignation.status as status,tbl_joinings.employee_name as empname,tbl_workflow_task.status as wstatus,tbl_resignation.emp_id as myid from tbl_resignation INNER JOIN tbl_joinings ON (tbl_resignation.emp_id = tbl_joinings.program_uid) INNER JOIN tbl_workflow_task ON (tbl_resignation.doc_id=tbl_workflow_task.doc_id) where tbl_workflow_task.status=0 AND tbl_workflow_task.level='".getclevel($user->uid)."' $cond ";
	}
	//echo getRole($user->uid)."==".$sql;exit;
	$output = resignsearch($sql) ;
	
return $output;
}

function resignation_search_page($empid){
global $user;
	$array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Resignation', 'resignationlist');
	drupal_set_breadcrumb($breadcrumb);

$val = '%'.strtoupper($empid).'%'; $val=addslashes($val);

$sql = "select tbl_resignation.comment as comment,tbl_resignation.doc_id as doc_id,tbl_resignation.effectdate as effectdate,tbl_joinings.employee_id as emp_id ,tbl_resignation.regdate as regdate,tbl_resignation.status as status,tbl_joinings.employee_name as empname ,tbl_workflow_task.status as wstatus,tbl_resignation.emp_id as myid from tbl_resignation INNER JOIN tbl_joinings ON (tbl_resignation.emp_id = tbl_joinings.program_uid) LEFT JOIN tbl_workflow_task ON (tbl_resignation.doc_id=tbl_workflow_task.doc_id)  WHERE (tbl_joinings.employee_id LIKE '".$empid."' OR tbl_joinings.employee_name LIKE '".$val."' OR DATE_FORMAT(tbl_resignation.effectdate,'%%d-%%m-%%Y') LIKE '".$val."' OR DATE_FORMAT(tbl_resignation.regdate,'%%d-%%m-%%Y') LIKE '".$val."') AND tbl_workflow_task.status=0 AND tbl_workflow_task.level='".getclevel($user->uid)."'  GROUP BY tbl_resignation.doc_id";
$output = resignsearch($sql) ;
	
return $output;
}

function resignsearch($sqlquery){
global $user;


$limit =(int)getMessage('resignation', 'code04', NULL);
$header = array(
	array('data' => t('S. No.')),
	array('data' => t('Employee Name'), 'field' => 'tbl_resignation.emp_id', 'sort' => 'asc'),
	array('data' => t('Effective Date'), 'field' => 'tbl_resignation.effectdate', 'sort' => 'asc'),
	array('data' => t('Last Working Date'), 'field' => 'tbl_resignation.regdate', 'sort' => 'asc'),
	array('data' => t('Status'), 'field' => 'tbl_resignation.status', 'sort' => 'asc'),
	array('data' => t('Action')),
	
	);
	$sql = $sqlquery.tablesort_sql($header);
	 $count_query = "SELECT COUNT(*) as mycount FROM (" . $sql . ") AS count_query";
	$cres = db_query($count_query);
	$crs = db_fetch_object($cres);
	 $totalcount = $crs->mycount;
	if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
	$result = pager_query($sql,10,0,$count_query);
if($result){
        $level = '';
	if(getRole($user->uid) == 13){
		$level = 1;
	}else if(getRole($user->uid) == 6){
		$level = 3;
	}else if(getRole($user->uid) == 19){
		$level = 2;
	}
	  while($rs = db_fetch_object($result)){
	    $counter++;
		
       
	    $wfsql ="select status,comment from tbl_workflow_task where doc_id='".$rs->doc_id."' AND level='".$level."' ORDER BY task_date DESC";
		$wfres= db_query($wfsql);
		$wfrs = db_fetch_object($wfres);
		$doc_id = $rs->doc_id;
		$emp_id = $rs->emp_id;
		
		//echo $rs->status.'/';
		//echo $wfrs->status.'<br>';
		
		$status = 'Pending';
		
		//$approved = l('View Queries','');
		
		if(getRole($user->uid) == 6){
		$asms = 'Approved';
		}
		else{
		$asms = 'Forward';
		}

		if($wfrs->status == 2 || $wfrs->status == 1){
		$links = l('View','rviewprofile/'.$emp_id);
		$links .= ' | '.l('View Comment ','comment-list/'.$doc_id);
		if($wfrs->comment){
			$links .= ' | '.l('View Queries','viewquery/'.$doc_id.'/'.$emp_id);
			}
		}
		else{
			if(getRole($user->uid) == 13 || getRole($user->uid) == 6 || getRole($user->uid) == 19)
			{
				$links = l('View','rviewprofile/'.$emp_id);
				$links .= ' | '.l($asms,'aresignation/'.$doc_id);
				if($rs->myid != $user->uid)
					$links .= ' | '.l('Reject','cresignation/'.$emp_id);
				if($rs->myid != $user->uid)
					$links .= ' | '.l('Send Query','send-query/'.$doc_id);
				$links .= ' | '.l('Comments ','comment-list/'.$doc_id);
				if($wfrs->comment){
					$links .= ' | '.l('View Queries','viewreply/'.$doc_id.'/'.$emp_id);
				}
			}else{
				$links = l('View','rviewprofile/'.$emp_id);
				if($wfrs->comment){
					$links .= ' | '.l('View Queries','viewquery/'.$doc_id.'/'.$emp_id);
				}
			}
		}
		//if($rs->status == 13 &&($wfrs->status != 1)){//
		//$links .= ' | '.l('View Queries','viewreply/'.$doc_id.'/'.$emp_id);
		//}
		
		//if( $rs->status == 12){
		//$links .= ' | '.l('View Query','viewquery/'.$doc_id.'/'.$emp_id);
		//}

		$enewdate = date("d-m-Y" ,strtotime($rs->effectdate));
		$lnewdate = date("d-m-Y" ,strtotime($rs->regdate));
     
	  $valsql = "select level from tbl_workflow_task WHERE doc_id='".$rs->doc_id."' AND level IN ('".$level."')";
	  $valres =  db_query($valsql);
	  $valrs = db_fetch_object($valres);
     $rolelevel =$valrs->level;

		 if($level ==  $rolelevel && (getRole($user->uid) == 13 || getRole($user->uid) == 6 || getRole($user->uid) == 19)){
		
		 }else{ 
			 if($rs->status == 11){$status ='Approved';}
			else{$status = 'Pending';}
		 }
		  $rolelinks = $links;
		$rows[] = array(
			array('data' => $counter),
			array('data' => $rs->empname.'('.$rs->emp_id.')'),
		array('data' => $enewdate),
		array('data' => $lnewdate),
		array('data' => $status),
		array('data' => $rolelinks),
            
          
		);
	
	  }
}
if($rows== NULL)
	$header=NULL;
//$output .='<div class="uploadcss">';	
    if(arg(0)=='resignation-search-list'){
	 $records = t(getMessage('resignation', 'code03', array("0"=>$totalcount)));
	 $output .='<table  id="wrapper" class="searchrecord"><tr><td>'.$records.' | '.l('View All','resignationlist').'</td></tr></table>';
	}
	$title = t(getMessage('resignation', 'code02',NULL));

	 $output .='<table id="wrapper"><tr><td class="tblHeaderLeft">'.$title.'<span class="addrecord"></span></td><td class="tblHeaderRight">'.drupal_get_form('resignsearch_form').'</td></tr></table>';
	 
	
	$output .=theme_table($header,$rows);
	 $output .=theme('pager', NULL, 20,0 );
/// $output .='<div>';
  
	return $output;
  }
  