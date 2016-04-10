<?php
include('includes/session.inc');
$title = _('Add Item');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
include('mailfile.php');
 '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="audit_report_approvelist.php">Audit List</a> &raquo; <a href="'. $_SERVER['PHP_SELF'].'">Audit Plan</a></div>';
if (isset($_POST['submit']) ){
  $InputError = 0;
  
  //$date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
   $date=$_POST['JournalProcessDate'];
  if($_POST['auditoffice']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Office To Be Audited'),'error');
	}
	/*if($_POST['auditee']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Auditee'),'error');
	}
	if (($_POST['auditee']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['auditee'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Auditee'),'error');
	}*/
   if($_POST['auditor']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Auditor'),'error');
	}
     
	
	 if($_POST['period']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Period'),'error');
	}
     if (($_POST['period']!='') && (!eregi('^[0-9a-zA-Z ]+$'  , $_POST['period'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Period, A-Z or 0-9 is Allowed'),'error');
	}
	
	
	/*if($_POST['element_3_1']=='' || $_POST['element_3_2']=='' || $_POST['element_3_3']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Date'),'error');
	}*/
	if($date=='')
   {
     $InputError = 1;
     prnMsg(_('Select Date'),'error');
	}
	
	if($InputError!=1)
	{
	 
	 
	 
	 $ado="insert into tbl_workflow_docket set workflow_id='4',time='".strtotime(date('d-m-Y'))."',status='pending',corp_branch='".$_POST['auditoffice']."'";
	 $adoq=DB_query($ado,$db);
	 
	 $mwf="select max(doc_id) as doc_id from tbl_workflow_docket";
	 $mwfq=DB_query($mwf,$db);
	 $mwfr=DB_fetch_array($mwfq);
	 $doci=$mwfr['doc_id'];
	 
	 /*$awt="insert into tbl_workflow_task set level='2',status='0',doc_id='".$doci."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);*/
	 	
		 createTask('2',$doci,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
		
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];
		
   $sql="insert into audit_plan set auditoffice='".$_POST['auditoffice']."',
										
										auditor='".$_POST['auditor']."',
										startdate='".strtotime($date)."',
										period='".$_POST['period']."',
										date='".strtotime(date('d-m-Y'))."',
										addedby='".$_SESSION['uid']."',
										doc_id='".$doci."',
										task_id='".$mtii."'
										";
	 $query=DB_query($sql,$db);	
		
		$ao="select * from tbl_corporations where corporation_id='".$_POST['auditoffice']."'";
		$aoq=db_query($ao);
		$aor=db_fetch_array($aoq);
		$ma="SELECT mail,name from users where uid='".$_POST['auditor']."'";
				$maq=DB_query($ma,$db);
				$mar=DB_fetch_array($maq);
				 $to=$mar['mail'];
				 $name=$mar['name'];
				 $dat=strtotime(date('d-m-Y'));
				 $aof=$aor['corporation_name'];
				 $parameter = json_encode(array(0=>"$name",1=>"$aof",2=>"$dat"));
				
				 createMail('caoaudit',$to,'',$parameter,$db);
				
		
	 if($query)	
	 {
	 unset($_POST);
	  echo "Plan Added";
	  header("location:audit_report_approvelist.php?msg=New Audit Plan Added");
	 }						
	}
}

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellspacing="1" cellpadding="2" border="0">
<tr class="oddrow"><td colspan="2" align="center"><h2>Audit Plan</h2></td></tr>
 <tr class="evenrow">
    <td><div class="left">Office to be Audited: <span style="color:#FF0000">*</span></div>
      <div class="right"><select name="auditoffice" id="auditoffice"><option value="">--Select --</option>
    <?php
	$ic="select * from tbl_corporations order by corporation_name";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{
	  if($_POST['auditoffice']==$icr['corporation_id'])
	 {
	  echo  '<option value="'.$icr['corporation_id'].'" selected >'.ucwords($icr['corporation_name']).'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$icr['corporation_id'].'">'.ucwords($icr['corporation_name']).'</option>';
	  }
	}
	?>
	</select></div></td>
  </tr>
<!--  <tr class="evenrow">
    <td><div class="left">Name of Auditee:<span style="color:#FF0000">*</span></div>
      <div class="right"><input type="text" name="auditee"  size="45" maxlength="45" value="<?php echo $_POST['itemdetails']?>"/></div></td>
  </tr>-->
 
  <tr class="oddrow">
    <td><div class="left">Name of Auditor: <span style="color:#FF0000">*</span></div>
      <div class="right"><select name="auditor" id="auditor"><option value="">--Select --</option>
    <?php
	$au="select users.name as name,users.uid as uid from users,users_roles where (users_roles.rid=7) and users.uid=users_roles.uid";
	$auq=DB_query($au,$db);
	while($aur=Db_fetch_array($auq))
	{
	  if($_POST['auditor']==$aur['uid'])
	 {
	  echo '<option value="'.$aur['uid'].'" selected >'.ucwords($aur['name']).'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$aur['uid'].'">'.ucwords($aur['name']).'</option>';
	  }
	}
	?>
	</select></div></td>
  </tr>
 <tr class="evenrow">
    <td><div class="left">Audit Start Date: <span style="color:#FF0000">*</span></div>
    <div class="right"><!--<div id="li" style="width:353px;">
			<span>
			<input id="element_3_2" name="element_3_2" class="element text" size="2" style="width:40px;" maxlength="2" value="<?php echo $_POST['element_3_2'];?>" type="text" readonly="readonly"> 
			<label for="element_3_2">DD</label>
		</span>
            <span><input id="element_3_1" name="element_3_1" class="element text" style="width:40px;" size="2" maxlength="2" value="<?php echo $_POST['element_3_1'];?>" type="text" readonly="readonly"> /
			<label for="element_3_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_3_3" name="element_3_3" class="element text" size="4" style="width:65px;" maxlength="4" value="<?php echo $_POST['element_3_3'];?>" type="text" readonly="readonly">/
			<label for="element_3_3">YYYY</label>
		</span>
	
		<span id="calendar_3">
			<img id="cal_img_3" class="datepicker" src="calender/calendar.gif" alt="Pick a date.">		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_3_3",
			baseField    : "element_3",
			displayArea  : "calendar_3",
			button		 : "cal_img_3",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>	</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></td>
  </tr>
    <tr class="oddrow">
    <td><div class="left">Period: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="period" id="period" size="45" maxlength="45" onkeypress="return alphanumericdot(event)" value="<?php echo $_POST['period'] ?>" /></div></td>
  </tr>
  
  <tr class="evenrow">
    <td align="center" class="back"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>
</form>
<?php include('includes/footer.inc'); ?>
<?php
if (isset($_POST['submit']) ){
   $date=$_POST['JournalProcessDate'];
  //$date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
  if($_POST['auditoffice']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('auditoffice').className='ercol';</script>";
	}
	
   if($_POST['auditor']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('auditor').className='ercol';</script>";
	}
     
	
	 if($_POST['period']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('period').className='ercol';</script>";
	}
     if (($_POST['period']!='') && (!eregi('^[0-9a-zA-Z ]+$'  , $_POST['period'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('period').className='ercol';</script>";
	}
	
	if($date=='')
   {  echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate').className='date';</script>";
	}
	}
?>