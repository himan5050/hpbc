<?php
include('includes/session.inc');
//$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if($_REQUEST['type']=='medical')
{
if($_REQUEST['vou']=='journal')
{

 $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);
	header("location:GLJournal.php?Debit=".$_REQUEST['Debit']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=medical");
	}
	
if($_REQUEST['vou']=='payment')
{

$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);
	header("location:Payments.php?GLAmount=".$_REQUEST['GLAmount']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=medical");
	}	
}	

if($_REQUEST['type']=='tour')
{
if($_REQUEST['vou']=='journal')
{

 $adi="select doc_id,task_id from tour_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);
	header("location:GLJournal.php?Debit=".$_REQUEST['Debit']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=medical");
	}
	
if($_REQUEST['vou']=='payment')
{

$adi="select doc_id,task_id from tour_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);
	header("location:Payments.php?Debit=".$_REQUEST['Debit']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=medical");
	}	
}			
if($_REQUEST['type']=='loan')
{
	if($_REQUEST['vou']=='payment')
	{
		header("location:Payments.php?GLAmount=".$_REQUEST['GLAmount']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=loan");
	}
	if($_REQUEST['vou']=='journal')
	{
		header("location:GLJournal.php?Debit=".$_REQUEST['Debit']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=loan");
	}
	if($_REQUEST['vou']=='receipt')
	{
		header("location:CustomerReceipt.php?Amount=".$_REQUEST['GLAmount']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=loan&NewReceipt=Yes&Type=GL");
	}
}


if($_REQUEST['type']=='loanadvance')
{
if($_REQUEST['vou']=='journal')
{

 /*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);*/
	header("location:GLJournal.php?Debit=".$_REQUEST['Debit']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=loanadvance");
	}
	
if($_REQUEST['vou']=='payment')
{

/*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);*/
	header("location:Payments.php?GLAmount=".$_REQUEST['GLAmount']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=loanadvance");
	}	
}	
if($_REQUEST['type']=='salary')
{
if($_REQUEST['vou']=='journal')
{

 /*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);*/
	header("location:GLJournal.php?Debit=".$_REQUEST['Debit']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=salary");
	}
	
if($_REQUEST['vou']=='payment')
{

/*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);*/
	header("location:Payments.php?GLAmount=".$_REQUEST['GLAmount']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=salary");
	}	
}	
if($_REQUEST['type']=='billsubmit')
{
if($_REQUEST['vou']=='journal')
{

 /*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);*/
	header("location:GLJournal.php?Debit=".$_REQUEST['Debit']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=billsubmit");
	}
	
if($_REQUEST['vou']=='payment')
{

/*$adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				
				$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);*/
	header("location:Payments.php?GLAmount=".$_REQUEST['GLAmount']."&GLManualCode=".$_REQUEST['GLManualCode']."&GLCode=".$_REQUEST['GLCode']."&clid=".$_REQUEST['clid']."&type=billsubmit");
	}	
}	
?>