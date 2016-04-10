<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
include('mailfile.php');
 /*'<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/

echo '<div class="breadcrumb"><a href="/"'.$u[1].'">Home</a> &raquo; <a href="audit_mylist.php">Audit List</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Submit Audit Report</a></div>';
if(isset($_GET['auid']))
{
 $_SESSION['auid']=$_GET['auid'];
}
$aud="select * from audit_plan where id='".$_SESSION['auid']."'";
$audq=DB_query($aud,$db);
$audr=DB_fetch_array($audq);

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
     prnMsg(_('Select Section Name'),'error');
	}
	/*if (($_POST['section']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['section'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Section, A-Z or 0-9 is Allowed'),'error');
	}*/
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
       move_uploaded_file($_FILES["file"]["tmp_name"],"../sites/default/files/audit/".$rannum.$_FILES["file"]["name"]);
	   $attachedncs=$rannum.$_FILES["file"]["name"];
	   }
	   else
	   {
	     $attachedncs="";
	   }
		
		$s="insert into audit_detail set audit_id='".$_POST['auid']."',
		                              auditdate='".strtotime($date)."',
									  auditoffice='".$_POST['auditoffice']."',
									  section='".$_POST['section']."',
									 
									  auditor='".$_POST['auditor']."',
									  remark='".$_POST['remark']."',
									  date='".strtotime(date('d-m-Y'))."',
									  attachedncs='".$attachedncs."'
									  ";
									  
				$q=DB_query($s,$db);
				
				$adi="select doc_id,task_id from audit_plan where id='".$_POST['auid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
				
				/*$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
				$taq=DB_query($ta,$db);*/
				updateTask($adir['task_id'],$db);
				
				 /*$awt="insert into tbl_workflow_task set level='3',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);*/
	 createTask('3',$adr,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];
				
				$aus="update audit_plan set levelstatus='1',task_id='".$mtii."' where id='".$_POST['auid']."'";	
				$ausq=DB_query($aus,$db);		
				
				 $ma="SELECT users.`uid` , mail,name
FROM `users` , users_roles
WHERE users.uid = users_roles.uid
AND users_roles.rid = '9'";
				$maq=DB_query($ma,$db);
				while($mar=DB_fetch_array($maq))
				{ $to=$mar['mail'];
				$name=$mar['name'];
				$dat=strtotime($date);
				$parameter = json_encode(array(0=>"$name",1=>"$dat"));
				
				 createMail('auditreportsubmit',$to,'',$parameter,$db);
				}
				
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
			 
			 header("location:audit_mylist.php?msg=Audit Report Submitted Successfully!");
			
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

if(isset($_POST['reset']))
{
  	  
  unset($_SESSION['exp']);
  unset($_SESSION['ncs']);
  unset($_SESSION['severity']);
  unset($_SESSION['clause']);
  unset($_SESSION['description']);
  unset($_POST);
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

<form action="<?php $_SERVER['SCRIPT_NAME']?>" method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="553" border="0" cellpadding="2" cellspacing="1">
<tr class="oddrow">
  <td colspan="2" align="center"><h2>Submit Audit Report</h2></td>
</tr>
    <tr class="evenrow"> <td ><div class="left">Audit Date: <span style="color:#FF0000">*</span> </div> 
      <input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']?>" /><div class="right"><!--<div id="li" style="width:353px;">
			<span>
			<input id="element_3_2" name="element_3_2" class="element text" size="2" style="width:40px;" maxlength="2" value="<?php echo $_POST['element_3_2'];?>" type="text" readonly="readonly"> 
			<label for="element_3_2">DD</label>
		</span>
            <span><input id="element_3_1" name="element_3_1" class="element text" style="width:40px;" size="2" maxlength="2" value="<?php echo $_POST['element_3_1'];?>" type="text" readonly="readonly"> /
			<label for="element_3_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_3_3" name="element_3_3" class="element text" size="4" style="width:67px;" maxlength="4" value="<?php echo $_POST['element_3_3'];?>" type="text" readonly="readonly">/
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
		</script>	</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>" readonly="readonly"></div></div></tr>
                       
    
	<tr class="oddrow">
	  <td><div class="left">Office: <span style="color:#FF0000">*</span> </div><div class="right"><select name="auditoffice" id="auditoffice">
    <?php
	$ic="select * from tbl_corporations";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{
	  if($audr['auditoffice']==$icr['corporation_id'])
	 {
	  echo '<option value="'.$icr['corporation_id'].'" "selected"="selected" >'.ucwords($icr['corporation_name']).'</option>';
	  }
	 /* else
	  {
	     echo '<option value="'.$icr['corporation_id'].'">'.ucwords($icr['corporation_name']).'</option>';
	  }*/
	}
	?>
	</select><input type="hidden" name="auid" value="<?php echo $_SESSION['auid']?>"  />  </div> </td></tr>
	<tr class="evenrow">
	  <td><div class="left">Section Name: <span style="color:#FF0000">*</span> </div><div class="right"><select name="section" id="section">
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
	  <td><div class="left">Auditee: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text" name="auditee"  size="45" maxlength="45" value="<?php echo $_POST['auditee'];  ?>" /></div></td></tr>-->
	<tr class="oddrow"> 
    <td><div class="left">Auditor: <span style="color:#FF0000">*</span> </div>
    <div class="right"><select name="auditor" id="auditor"><option value="">--Select --</option>
    <?php
	$au="select users.name as name,users.uid as uid from users,users_roles where (users_roles.rid=7 or users_roles.rid=8) and users.uid=users_roles.uid";
	$auq=DB_query($au,$db);
	while($aur=Db_fetch_array($auq))
	{
	  if($_SESSION['uid']==$aur['uid'])
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
	  <div class="right"><input type="text" name="remark" id="remark" maxlength="200" size="45" onkeypress="return alphanumeric(event)"  value="<?php echo $_POST['remark']?>" /></div></td></tr>
      </table>
       <br/><br/>
    <table width="553" border="0" cellpadding="2" cellspacing="1">
    <tr class="oddrow"><td colspan="5" align="center"><h2>NCs</h2>
    </tr>
    <tr class="evenrow"> <td >NCs </td><td>Description</td><td>Severity</td><td>Clause</td></tr> 
    <?php
	$nu=$_SESSION['exp'];
	 for($j=0;$j<=($nu-1);$j++)
             {  
				    if($j%2==0)
					{
					 $cl="evenrow";
					}
					else
					{
					  $cl="oddrow";
					}
	?>
   <tr class="<?php echo $cl;?>"> <td ><?php echo $_SESSION['ncs'][$j];?> </td> <td ><?php echo $_SESSION['description'][$j];?> </td><td><?php echo $_SESSION['severity'][$j];?></td><td><?php echo $_SESSION['clause'][$j];?></td></tr> 
      <?php
	  
	  } ?>
        <tr class="oddrow">
	  
	  <td align="right" colspan="5"><a href="javascript:void(0)" onClick="showdiv();">Add NCs</a></td>
	</tr>
  </table>
    <br/>
      
      <div id="detail"  <?php if($InputError!=2) { ?>style="display:none" <?php } ?>>
      <table>
      <tr class="evenrow">
	  <td><div class="left">NCs: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text" name="ncs" id="ncs" maxlength="200" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['ncs'];?>"></div></td>
    </tr>
	<tr class="oddrow">
    <td><div class="left">Description: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="description" id="description"  size="45" onkeypress="return alphanumeric(event)" maxlength="200" value="<?php echo $_POST['description'];?>"/></div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Severity: <span style="color:#FF0000">*</span></div>
	  <div class="right"><input type="text"  name="severity" id="severity" size="45" onkeypress="return alphanumeric(event)" maxlength="11" value="<?php echo $_POST['severity'];?>"/></div></td></tr>
	<tr class="oddrow">
	  <td><div class="left">Standard/Clause: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text"  name="clause" id="clause"  size="45" onkeypress="return alphanumeric(event)"  maxlength="45" value="<?php echo $_POST['clause'];?>"/></div></td>
    </tr>
	
    <tr class="evenrow">
	  
	  <td colspan="2" align="center"><input type="submit" name="save" value="Add" /> </td>
    </tr>
    </table>
    </div>
	<br/>
    <table>
      <tr class="oddrow"><td><div class="left">Attach NCs Copy :</div><div class="right"><input type="file" name="file" /></div>&nbsp;(Upload .doc .xls .xlsx .jpg .gif .jpeg .png .zip .rar .pdf .txt Only)</td></tr>
    <tr class="evenrow"><td colspan="2" align="center"><input  type="submit" name="submit" value="Save" /> <input  type="submit" name="reset" value="Reset" /></td>
  </tr>
</table>

</form>
<br />
<?php
include('includes/footer.inc');
?>
<?php
if (isset($_POST['submit']) ){
  $InputError = 0;
 $date=$_POST['JournalProcessDate'];
 // $date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
 if($date=='')
   {  echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate').className='date';</script>";
	}
   if($_POST['auditoffice']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('auditoffice').className='ercol';</script>";
	}
	
    
	if($_POST['section']=='')
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
	if(isset($_POST['save']))
{ 
   if($_POST['ncs']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('ncs').className='ercol';</script>";
	}
	if (($_POST['ncs']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['ncs'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('ncs').className='ercol';</script>";
	}
if($_POST['description']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
		
	if($_POST['severity']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('severity').className='ercol';</script>";
	}
   if (($_POST['severity']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['severity'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('severity').className='ercol';</script>";
	}
	if($_POST['clause']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('clause').className='ercol';</script>";
	}
   if (($_POST['clause']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['clause'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('clause').className='ercol';</script>";
	}
}
?>