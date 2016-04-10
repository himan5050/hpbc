<?php
include('includes/session.inc');
$title = _('Add Item');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
 '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">
<script type="text/javascript" src="includes/jquery-1.6.2.js"></script>';
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="billsubmit_user.php">List of My Bills</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Bill Submission</a></div>';
$rannum=rand(1,20000);
$corpbranch=getVendorBranch($_SESSION['uid'],$db);
if (isset($_POST['submit']) ){
  $InputError = 0;
  
  // $date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
  $date=$_POST['JournalProcessDate'];
 /* if($_POST['cheque']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Cheque Number'),'error');
	}*/
  if($_POST['userid']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Name'),'error');
	}
	/*
	if (($_POST['name']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['name'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Name'),'error');
	}*/
 	
	if($_POST['amount']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Amount'),'error');
	}
	if (($_POST['amount']!='') && (!is_numeric($_POST['amount'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Amount'),'error');
	}
	if($_POST['remarks']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Remarks'),'error');
	}
	 if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Remarks, A-Z or 0-9 is Allowed'),'error');
	}
	/*if($_POST['element_3_1']=='' || $_POST['element_3_2']=='' || $_POST['element_3_3']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Date'),'error');
	}*/
	if($date=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Date'),'error');
	}

	if($_FILES["file"]["name"]=='')
   {
     $InputError = 1;
     prnMsg(_('Upload Bill '),'error');
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
	 
	/* $sql="insert into billsubmit set cheque='".$_POST['cheque']."',
										bankname='".$_POST['bankname']."',
										amount='".$_POST['amount']."',
										billno='".$_POST['billno']."',
										issuedto='".$_POST['issuedto']."',
										 date='".strtotime($date)."'";*/
										 
	 if(($_FILES["file"]["name"])!='')
	  {
       @move_uploaded_file($_FILES["file"]["tmp_name"],"../sites/default/files/bill/".$rannum.$_FILES["file"]["name"]);
	   $attachedncs=$rannum.$_FILES["file"]["name"];
	   }
	   else
	   {
	     $attachedncs="";
	   }			
	   
       $ado="insert into tbl_workflow_docket set workflow_id='14',time='".strtotime(date('d-m-Y'))."',status='pending',corp_branch='".$corpbranch."'";
	   $adoq=DB_query($ado,$db);
	 
	 $mwf="select max(doc_id) as doc_id from tbl_workflow_docket";
	 $mwfq=DB_query($mwf,$db);
	 $mwfr=DB_fetch_array($mwfq);
	 $doci=$mwfr['doc_id'];

    createTask('2',$doci,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
		
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];

	   $su="select name from users where uid='".$_POST['userid']."'";	
	   $suq=DB_query($su,$db);
	   $sur=DB_fetch_array($suq);			 
	 $sql="insert into billsubmit set userid='".$_POST['userid']."',
	                                     name='".$sur['name']."',
										amount='".$_POST['amount']."',
										remarks='".$_POST['remarks']."',
										date='".strtotime($date)."',
										refnum='".$_POST['refnum']."',
										bill='".$attachedncs."',
										doc_id='".$doci."',
										task_id='".$mtii."',
										budget_allocated='".$_POST['budget_allocated']."'";									 
	 $query=DB_query($sql,$db);		
	 if($query)	
	 {
	 unset($_POST);
	  unset($_REQUEST);
	  echo "<div class='success'>Bill Submitted succesfully</div>";
	  @header("location:billsubmit_user.php?msg=Bill Submitted succesfully");
	 }						
	}
}

?>

<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellspacing="1" cellpadding="2" border="0">
<tr class="oddrow"><td align="center"><h2>Bill Submission</h2></td></tr>
 <?php /*?> <tr class="evenrow">
    <td><div class="left">Name: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="hidden" name="userid"  size="45" maxlength="45" value="<?php echo $_SESSION['uid'];?>" onKeyPress="return alphanumeric(event)" /><input type="text" name="name"  size="45" maxlength="45" value="<?php echo $_SESSION['UsersRealName'];?>" onKeyPress="return alphanumeric(event)" readonly/></div></td>
  </tr><?php */?>
  
  <tr class="evenrow">
    <td><div class="left">Name: <span style="color:#FF0000">*</span></div>
    <div class="right"><select name="userid" id="userid"><option value="">--Select Name--</option>
    <?php
	if(isset($_POST['userid']))
	{
	  $seu=$_POST['userid'];
	}
	else
	{
	  $seu=$_SESSION['uid'];
	}
	$us="select users.name,users.uid from users,users_roles where users.uid=users_roles.uid and users_roles.rid=41";
	$usq=DB_query($us,$db);
	while($usr=DB_fetch_array($usq))
	{
	   if($seu==$usr['uid']){
	?>
                       <option value="<?php echo $usr['uid']; ?>" selected="selected"><?php echo ucwords($usr['name']); ?></option>
                       <?php } else {?>
                       <option value="<?php echo $usr['uid']; ?>" ><?php echo ucwords($usr['name']); ?></option>
                       <?php } }?>
                       </select>
                       </div></td>
  </tr>
 
  <tr class="oddrow">
    <td><div class="left">Amount: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="amount"  size="45" maxlength="11" id="amount" class="number" value="<?php echo $_POST['amount']?>" /></div></td>
  </tr>
 
 
  <tr class="evenrow">
    <td><div class="left">Remarks: <span style="color:#FF0000">*</span></div>
    <div class="right"><input type="text" name="remarks"  size="45" maxlength="200" id="remarks" value="<?php echo $_POST['remarks']?>" onkeypress = "return alphanumeric(event)" /></div></td>
  </tr>
 <tr class="oddrow">
    <td><div class="left">Date: <span style="color:#FF0000">*</span></div>
    <div class="right"><!--<div id="li" style="width:353px;">
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
		</script>	</div>--><div  id="date"><input type="text"  name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>" readonly="readonly"></div></div></td>
  </tr>
   <tr class="evenrow">
	  <td><div class="left">Reference No.: </div>
	  <div class="right"><input type="text" name="refnum" maxlength="45" onkeypress="return alphanumeric(event)" id="refnum"  value="<?php echo $_POST['refnum'];?>"/></div></td>
    </tr>
  <tr class="oddrow">
	  <td><div class="left">Bills: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="file" name="file" id="file" /></div>&nbsp;(Upload .doc .xls .jpg .gif .jpeg .zip Only)</td>
    </tr>
     <tr class="evenrow">
	  <td><div class="left">Budget Allocated:  </div>
	  <div class="right"><input type="text" name="budget_allocated" maxlength="45" onkeypress="return alphanumeric(event)" id="refnum"  value="<?php echo $_POST['budget_allocated'];?>"/></div></td>
    </tr>
  <tr class="oddrow">
    <td align="center" ><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>
</form>
<?php include('includes/footer.inc'); ?>
<?php
if (isset($_POST['submit']) ){
  $InputError = 0;
   $date=$_POST['JournalProcessDate'];
  // $date=$_POST['element_3_2']."-".$_POST['element_3_1']."-".$_POST['element_3_3'];
 
 
  if($_POST['userid']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('userid').className='ercol';</script>";
	}
	
 	
	if($_POST['amount']=='')
   {
      echo "<script type='text/javascript'>document.getElementById('amount').className='ercol';</script>";
	}
	if (($_POST['amount']!='') && (!is_numeric($_POST['amount'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('amount').className='ercol';</script>";
	}
	if($_POST['remarks']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('remarks').className='ercol';</script>";
	}
	 if (($_POST['remarks']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['remarks'])))
	{
	   echo "<script type='text/javascript'>document.getElementById('remarks').className='ercol';</script>";
	}
	

	if($_FILES["file"]["name"]=='')
   {
     echo "<script type='text/javascript'>document.getElementById('file').className='ercol';</script>";
	}
	if($date=='')
   {  echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate').className='date';</script>";
	}
	}
?>