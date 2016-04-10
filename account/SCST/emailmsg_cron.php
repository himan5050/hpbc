<?php

include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//drupal_cron_run();
 $sql_escalation = "SELECT  tbl_workflow_task.task_id,tbl_escalation.roles,tbl_escalation.users,tbl_escalation.level
FROM tbl_workflow_task
INNER JOIN tbl_workflow_docket ON tbl_workflow_task.doc_id = tbl_workflow_docket.doc_id
INNER JOIN tbl_workflow_details ON tbl_workflow_docket.workflow_id = tbl_workflow_details.workflow_id
AND tbl_workflow_details.level = tbl_workflow_task.level
INNER JOIN tbl_escalation ON tbl_escalation.workflow_details_id = tbl_workflow_details.workflow_details_id
WHERE tbl_workflow_task.status =0 
and tbl_escalation.level>tbl_workflow_task.is_escalation
AND tbl_escalation.workflow_details_id != ''
AND tbl_escalation.Email =1
 and hour( TIMEDIFF( now( ) , task_date ) ) >= tbl_escalation.sla LIMIT 0,5";
 $res_escalation = db_query($sql_escalation);
 while($rs_escalation = db_fetch_object($res_escalation)){
      
   if($rs_escalation->roles){
      $rid = @explode(",", $rs_escalation->roles);
	  for($i=0;$i<sizeof($rid);$i++){
		  $sql = "SELECT u.mail,u.name FROM users u, users_rples ur WHERE u.uid = ur.uid AND (ur.rid = '".$rid[$i]."')";
		 $res = db_query($sql);
		 while($u = db_fetch_object($res)){
				$parameter = '';
				$to = $u->mail;
				$parameter = json_encode(array($u->name));
				createMail('emailnotification',$to,'',$parameter);
				db_query("update tbl_workflow_task set is_escalation=tbl_escalation.level where task_id='".$rs_escalation->task_id."'");
		 }
	  }
   }else{
	  $uid = @explode(",", $rs_escalation->users); 
	   for($i=0;$i<sizeof($uid);$i++){
			$u = user_load($uid[$i]);
			$parameter = '';
			$to = $u->mail;
			$parameter = json_encode(array($u->name));
			createMail('emailnotification',$to,'',$parameter);
			db_query("update tbl_workflow_task set is_escalation=tbl_escalation.level where task_id='".$rs_escalation->task_id."'");
	   }
   }

     
    
 }
?>