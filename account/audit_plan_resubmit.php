<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
 '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="audit_mylist.php">Audit List</a>&raquo; <a href="audit_plan_resubmit.php">Audit Plan Resubmit</a></div>';
if(isset($_GET['auid']))
{

$aud="select * from audit_detail where audit_id='".$_REQUEST['auid']."'";
$audq=DB_query($aud,$db);
$audr=DB_fetch_array($audq);
if (!isset($_POST['submit']) ){
$date=$audr['auditdate'];
$_POST['JournalProcessDate']=date('d-m-Y',$date);
/*$_POST['element_3_1']=date('m',$date);
$_POST['element_3_3']=date('Y',$date);*/
$_POST['auid']=$audr['audit_id'];
$_POST['auditoffice']=$audr['auditoffice'];
$_POST['section']=$audr['section'];
$_POST['auditor']=$audr['auditor'];
//$_POST['auditee']=$audr['auditee'];

$_POST['remark']=$audr['remark'];
$_POST['attachedncs']=$audr['attachedncs'];
	}
	if(!isset($_SESSION['fetch']))
{
  $pt="select * from nsc_detail where audit_id='".$_REQUEST['auid']."'";
$ptq=DB_query($pt,$db);
while($ptr=DB_fetch_array($ptq))
{ $_SESSION['exp']++;
 $_SESSION['ncs'][]=$ptr['nsc'];
  $_SESSION['description'][]=$ptr['description'];
  $_SESSION['severity'][]=$ptr['sevirity'];
  $_SESSION['clause'][]=$ptr['clause'];
}
$_SESSION['fetch']=1;
}
		                              
}
$InputError;
$rannum=rand(1,20000);
if (isset($_POST['submit']) ){
  $InputError = 0;

 // $date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
 $date=$_POST['JournalProcessDate'];
	if($date=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Date'),'error');
	}
   if($_POST['auditoffice']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Office'),'error');
	}
	
    
	if($_POST['section']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Section Name'),'error');
	}
	if (($_POST['section']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['section'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Section, A-Z or 0-9 is Allowed'),'error');
	}
	/*if($_POST['auditee']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Auditee'),'error');
	}
	if (($_POST['auditee']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['auditee'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Auditee, A-Z or 0-9 is Allowed'),'error');
	}*/
	if($_POST['auditor']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Auditor'),'error');
	}
	
	
	if($_POST['remark']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter remark'),'error');
	}
	if (($_POST['remark']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remark'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Remark, A-Z or 0-9 is Allowed'),'error');
	}
	
	if($_FILES["file"]["name"]!='')
   {
      $filename=$_FILES["file"]["name"];
	  $ext = end(explode('.', $filename));
     $ext = substr(strrchr($filename, '.'), 1);
     $ext = substr($filename, strrpos($filename, '.') + 1);
     $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $filename);
     $exts = split("[/\\.]", $filename);
     $n = count($exts)-1;
     $ext = $exts[$n];
	 if($ext=='exe')
	 {
     $InputError = 1;
     prnMsg(_('This File Type Is Not Allowed For Uploading '),'error');
	 }
	}

  if($InputError!=1)
	{ 
	  if(($_FILES["file"]["name"])!='')
	  {
       move_uploaded_file($_FILES["file"]["tmp_name"],"../sites/default/files/audit/" . $rannum.$_FILES["file"]["name"]);
	   $attachedncs=$rannum.$_FILES["file"]["name"];
	   }
	   else
	   {
	     $attachedncs=$audr['attachedncs'];
	   }
	 $adi="select doc_id,task_id from audit_plan where id='".$_REQUEST['auid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
		 $level = 1;
		if($role == 7)
		{
		 $level = 1;
		}
		
		updateTask($adir['task_id'],$db);
		createTask($level,$adr,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
		$mti="select max(task_id) as task_id from tbl_workflow_task";
		$mtiq=DB_query($mti,$db);
		$mtir=DB_fetch_array($mtiq);
		$mtii=$mtir['task_id'];  
		$s="update audit_detail set   auditdate='".strtotime($date)."',
									  auditoffice='".$_POST['auditoffice']."',
									  section='".$_POST['section']."',
									 
									  auditor='".$_POST['auditor']."',
									  remark='".$_POST['remark']."',
									  date='".strtotime(date('d-m-Y'))."',
									  attachedncs='".$attachedncs."'
									  where audit_id='".$_POST['auid']."'
									  ";
									  
				$q=DB_query($s,$db);
				
				
				
				$aus="update audit_plan set resubmitstatus='1',
				                            querystatus='2',
				                            task_id = '".$mtii."'
									        
									        where id='".$_POST['auid']."' ";	
				$ausq=DB_query($aus,$db);
					
				$tc=  "delete from nsc_detail where audit_id='".$_POST['auid']."'";
									 
							$tcq=DB_query($tc,$db);		 
				
		if($q)
		{
		unset($_POST);	
		
		
		}	  
	
	
					
			 $nu=$_SESSION['exp'];
			for($i=0;$i<=($nu-1);$i++)
             {  
									  
						$tc=  "insert into nsc_detail set audit_id='".$_REQUEST['auid']."',
						              nsc='".$_SESSION['ncs'][$i]."',
									  description='".$_SESSION['description'][$i]."',
									  sevirity='".$_SESSION['severity'][$i]."',
									  clause='".$_SESSION['clause'][$i]."',
									  status='0'									  									 
									 ";
									 
							$tcq=DB_query($tc,$db);		
							
							
					
														 
			 }
			 
					if($q)
					{
					  echo "Audit Submitted";
					}
					
			 unset($_SESSION['ncs']);
			 unset($_SESSION['description']);
			 unset($_SESSION['severity']);
			 unset($_SESSION['clause']);
			 unset($_SESSION['exp']);
			 unset($_SESSION['fetch']);
			 
			header("location:audit_mylist.php?msg=Audit Resubmitted Successfully!");
    }
	if($q)
					{
	//header("location:audit_mylist.php");
	}
}
if(!isset($_SESSION['num']))
{
$_SESSION['num']=0;
}
if(isset($_POST['save']))
{ 
   if($_POST['ncs']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter NCs'),'error');
	}
	if (($_POST['ncs']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['ncs'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid NCs, A-Z or 0-9 is Allowed'),'error');
	}
if($_POST['description']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Description'),'error');
	}
	if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid Descrition, A-Z or 0-9 is Allowed'),'error');
	}
		
	if($_POST['severity']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Severity'),'error');
	}
   if (($_POST['severity']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['severity'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid Severity, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['clause']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Clause'),'error');
	}
   if (($_POST['clause']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['clause'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid Clause, A-Z or 0-9 is Allowed'),'error');
	}
 if($InputError!=2)
	{
  $_SESSION['exp']++;
  
  $_SESSION['ncs'][]=$_POST['ncs'];
  $_SESSION['description'][]=$_POST['description'];
  $_SESSION['severity'][]=$_POST['severity'];
  $_SESSION['clause'][]=$_POST['clause'];
  
  
  unset($_POST['ncs']);
  unset($_POST['description']);
  unset($_POST['severity']);
  unset($_POST['clause']);
  }
  
}


for($i=0;$i<=2;$i++)
{
 //echo $_SESSION['charges'][$i]."==".$_SESSION['medicine'][$i]."<br/>";
}

if(isset($_REQUEST['op']))
{  
  $no=$_REQUEST['no'];
  if($_REQUEST['op']=='delete')
  {
    $_SESSION['ncs'][$no]='';
	$_SESSION['description'][$no]='';
    $_SESSION['severity'][$no]='';
    $_SESSION['clause'][$no]='';
  }
}

?>
<script>
function showdiv()
{
 document.getElementById('detail').style.display='block';
}

function closediv()
{
 document.getElementById('detail').style.display='';
}

function finalamount(a,b,c)
{ 
  document.getElementById('net_amount').value=document.getElementById('tot_claim').value-a;
}

function getuser(a)
{
  window.location.href='medical_claim.php?uid='+a;
}


</script>
<link href="images/style.css" rel="stylesheet" type="text/css" />

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="553" border="0" cellpadding="2" cellspacing="1" id="form-container">
<tr class="odd">
  <td colspan="2" align="center"><h2>Submit Audit Report</h2></td>
</tr>
    <tr class="evenrow"> <td><div class="left">Audit Date: <span style="color:#FF0000">*</span> </div> 
      <div class="right"><input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']?>" /><div class=""><!--<div id="li" style="width:353px;">
			<span>
			<input id="element_3_2" name="element_3_2" class="element text" size="2" style="width:25px;" maxlength="2" value="<?php echo $_POST['element_3_2'];?>" type="text" readonly="readonly"> 
			<label for="element_3_2">DD</label>
		</span>
            <span><input id="element_3_1" name="element_3_1" class="element text" style="width:25px;" size="2" maxlength="2" value="<?php echo $_POST['element_3_1'];?>" type="text" readonly="readonly"> /
			<label for="element_3_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_3_3" name="element_3_3" class="element text" size="4" style="width:40px;" maxlength="4" value="<?php echo $_POST['element_3_3'];?>" type="text" readonly="readonly">/
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
		</script>	</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>"></div></div></td></tr>
                       
    
	<tr class="oddrow">
	  <td><div class="left">Office: <span style="color:#FF0000">*</span> </div>
   <div class="right"><select name="auditoffice" id="auditoffice"><option value="">--Select --</option>
    <?php
	$ic="select * from tbl_corporations";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{
	  if($audr['auditoffice']==$icr['corporation_id'])
	 {
	  echo '<option value="'.$icr['corporation_id'].'" selected >'.$icr['corporation_name'].'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$icr['corporation_id'].'">'.$icr['corporation_name'].'</option>';
	  }
	}
	?>
	</select><input type="hidden" name="auid" value="<?php echo $_REQUEST['auid']?>"  />   </div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Section Name: <span style="color:#FF0000">*</span> </div> 
	  <div class="right"><select name="section" id="auditoffice">
                         <option value="">--Select Section--</option>
                         <?php 
						 $sec="select * from tbl_lookups where lookupType_id='19' order by lookup_name";
						 $secq=DB_query($sec,$db);
						 while($secr=DB_fetch_array($secq))
						 { 
						    if($_POST['section']==$secr['lookup_name'])
							{
						  ?>
                          <option value="<?php echo $secr['lookup_name'];?>" selected="selected"><?php echo ucwords($secr['lookup_name']);?> </option>
                          <?php
						  }
						  else
						  {
						  ?>
                           <option value="<?php echo $secr['lookup_name'];?>"><?php echo ucwords($secr['lookup_name']);?> </option>
                          <?php
						  }
						  }
						  ?>
                         </select></div></td></tr>
	<!--<tr class="evenrow">
	  <td>Auditee:<span style="color:#FF0000">*</span> </td>
	  <td><input type="text" name="auditee"  size="45" maxlength="21" value="<?php echo $_POST['auditee'];  ?>" /></td></tr>-->
	<tr class="oddrow"> <td>
  <div class="left">Auditor: <span style="color:#FF0000">*</span> </div>
    <div class="right"><select name="auditor" id="auditoffice"><option value="">--Select --</option>
    <?php
	$au="select users.name as name,users.uid as uid from users,users_roles where (users_roles.rid=7 or users_roles.rid=8) and users.uid=users_roles.uid";
	$auq=DB_query($au,$db);
	while($aur=Db_fetch_array($auq))
	{
	  if($_POST['auditor']==$aur['uid'])
	 {
	  echo '<option value="'.$aur['uid'].'" selected >'.$aur['name'].'</option>';
	  }
	  else
	  {
	     echo '<option value="'.$aur['uid'].'">'.$aur['name'].'</option>';
	  }
	}
	?>
	</select></div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Remark: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text" name="remark" id="remark" size="45" maxlength="45" value="<?php echo $_POST['remark']?>" onkeypress="return alphanumeric(event)" /></div></td></tr>
      </table>
       <br/>
    <table width="553" border="0" cellpadding="2" cellspacing="1" id="form-container">
    <tr class="odd" align="center"><td colspan="5"><h2>NCs</h2></td>
    </tr>
    <tr class="evenrow"> <td >NCs </td><td>Description</td><td>Severity</td><td>Clause</td><td>Action</td></tr> 
    <?php
	 $nu=$_SESSION['exp'];
	 for($j=0;$j<=($nu-1);$j++)
             {  
			 if($_SESSION['ncs'][$j]!='')
			 {
				    
	?>
   <tr class="oddrow"> <td ><?php echo $_SESSION['ncs'][$j];?> </td> <td ><?php echo $_SESSION['description'][$j];?> </td><td><?php echo $_SESSION['severity'][$j];?></td><td><?php echo $_SESSION['clause'][$j];?></td><td><a href="audit_plan_resubmit.php?op=delete&no=<?php echo $j;?>&auid=<?php echo $_REQUEST['auid'];?>">Delete</a></td></tr> 
      <?php
	  }
	  } ?>
        <tr class="oddrow">
	  
	  <td align="right" colspan="5"><a href="javascript:void(0)" onClick="showdiv();">Add NCs</a></td>
	</tr>
  </table>
    
      
      <div id="detail"  <?php if($InputError!=2) { ?>style="display:none" <?php } ?>>
      <table style="margin-top:25px;">
      <tr class="evenrow">
	  <td width="50%">NCs: <span style="color:#FF0000">*</span> </td>
	  <td><input type="text" name="ncs" value="<?php echo $_POST['ncs'];?>" onkeypress="return alphanumericdot(event)" /></td>
    </tr>
	<tr class="oddrow">
    <td>Description: <span style="color:#FF0000">*</span> </td> 
    <td><input type="text" name="description"  size="45" maxlength="11" value="<?php echo $_POST['description'];?>" onkeypress="return alphanumericdot(event)"/></td></tr>
	<tr class="evenrow">
	  <td>Severity: <span style="color:#FF0000">*</span> </td> 
	  <td><input type="text"  name="severity"  size="45" maxlength="11" value="<?php echo $_POST['severity'];?>" onkeypress="return alphanumericdot(event)" /></td></tr>
	<tr class="oddrow">
	  <td>Standard/Clause: <span style="color:#FF0000">*</span> </td>
	  <td><input type="text"  name="clause"  size="45"  maxlength="45" value="<?php echo $_POST['clause'];?>" onkeypress="return alphanumericdot(event)" /></td>
    </tr>
	
    <tr class="evenrow">
	  <td>&nbsp;</td>
	  <td><input type="submit" name="save" value="Add" /> </td>
    </tr>
    </table>
    </div>
	<br/>
    <table width="100%" border="0" cellpadding="2" cellspacing="1" id="form-container">
      <tr class="oddrow">
		  <td><div class="left">Attach NCs Copy :</div>
		  <div class="right"><input type="file" name="file" />&nbsp;(Upload .doc .xls .xlsx .jpg .gif .jpeg .png .zip .rar .pdf .txt Only)</div>
	  </tr>
      <tr class="evenrow">
		
		  <td align="center"><input  type="submit" name="submit" value="Save" /> <input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>

</form>
<br />
<?php
include('includes/footer.inc');
?>
<?php
if (isset($_POST['submit']) ){
 

 // $date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];

   if($_POST['auditoffice']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('auditoffice').className='ercol';</script>";
	}
	
    
	if($_POST['section']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('section').className='ercol';</script>";
	}
	if (($_POST['section']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['section'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('section').className='ercol';</script>";
	}
	
	if($_POST['auditor']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('auditor').className='ercol';</script>";
	}
	
	
	if($_POST['remark']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('remark').className='ercol';</script>";
	}
	if (($_POST['remark']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remark'])))
	{
	   echo "<script type='text/javascript'>document.getElementById('remark').className='ercol';</script>";
	}
	}
?>