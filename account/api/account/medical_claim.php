<?php
include('includes/session.inc');
$title = _('Journal Entry');


include('includes/SQL_CommonFunctions.inc');
include('includes/header.inc');
include('mailfile.php');
echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
$InputError;
$rannum=rand(1,20000);
 $corp=getCorporationBranch($_SESSION['uid'],$db);
if (isset($_POST['submit']) ){
  $InputError = 0;

   if($_POST['emp_id']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Employee Id'),'error');
	}
	
    
	if($_POST['name_rotaion']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Name of patient and rotation with the claimant'),'error');
	}
	if (($_POST['name_rotaion']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['name_rotaion'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Name of patient and rotation with the claimant, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['period_illness']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Period of illness'),'error');
	}
	if (($_POST['period_illness']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['period_illness'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Period of Illness, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['claim_type']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Claim Type'),'error');
	}
	
	
	if($_POST['tot_claim']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Total Claim'),'error');
	}
	if (($_POST['tot_claim']!='') && (!is_numeric($_POST['tot_claim'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Total Claim'),'error');
	}
	
	
	if($_POST['net_amount']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Net Amount'),'error');
	}
	if (($_POST['net_amount']!='') && (!is_numeric($_POST['net_amount'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Net Amount'),'error');
	}
	
	if($_FILES["file"]["name"]=='')
   {
     $InputError = 1;
     prnMsg(_('Upload Bill Against Claim'),'error');
	}
	if($_POST['declaration']!=1)
   {
     $InputError = 1;
     prnMsg(_('Accept Declaration'),'error');
	}
	

  if($InputError!=1)
	{ 
	  
       @move_uploaded_file($_FILES["file"]["tmp_name"],"bills/" . $rannum.$_FILES["file"]["name"]);
	   
	   $corp=getCorporationBranch($_SESSION['uid'],$db);
	   
	   $ado="insert into tbl_workflow_docket set workflow_id='3',time='".strtotime(date('d-m-Y'))."',status='pending',corp_branch=".$corp."";
	 $adoq=DB_query($ado,$db);
	 
	 $mwf="select max(doc_id) as doc_id from tbl_workflow_docket";
	 $mwfq=DB_query($mwf,$db);
	 $mwfr=DB_fetch_array($mwfq);
	 $doci=$mwfr['doc_id'];
	 
	 $awt="insert into tbl_workflow_task set level='1',status='0',doc_id='".$doci."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);
	 	
	 $mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];	
		
		$s="insert into medical_claim set 
									  emp_id='".$_POST['emp_id']."',
									  designation='".$_POST['designation']."',
									  mobile='".$_POST['mobile']."',
									  email='".$_POST['email']."',
									  office='".$_POST['office']."',
									  basic_pay='".$_POST['basic_pay']."',
									  name_rotaion='".$_POST['name_rotaion']."',
									  period_illness='".$_POST['period_illness']."',
									  tot_claim='".$_POST['tot_claim']."',
									  net_amount='".$_POST['net_amount']."',
									  claim_type='".$_POST['claim_type']."',
									  enteredby='".$_SESSION['uid']."',
									  advance='".$_POST['advance']."',
									  date='".strtotime(date('d-m-Y'))."',
									  bill='".$rannum.$_FILES["file"]["name"]."',
									  status='0',
									  voucher_generated='0',
									  doc_id='".$doci."',
									  task_id='".$mtii."'";
									  
				$q=DB_query($s,$db);
				
				$ma="SELECT users.`uid` , email,tbl_joinings.employee_name as name
FROM `users` , users_roles, tbl_joinings
WHERE users.uid = users_roles.uid
AND tbl_joinings.program_uid = users.`uid`
AND tbl_joinings.current_officeid = '".$_POST['office']."'
AND users_roles.rid = '13'";
				$maq=DB_query($ma,$db);
				while($mar=DB_fetch_array($maq))
				{ $to=$mar['email'];
				$name=$mar['name'];
				$clty="Medical";
				$clamo=$_POST['net_amount'];
				$parameter = json_encode(array(0=>"$name",1=>"$clty",2=>"$clamo"));
				 createMail('medicalclaim',$to,'',$parameter,$db);
				}
				/*$clf="insert into tbl_workflow_docket set workflow_id='3',time='".strtotime(date('d-m-Y'))."',status='pending'";
				$clfq=DB_query($clf,$db);
				
				 $ldfi="select max(doc_id) id from tbl_workflow_docket";
				 $ldfiq=DB_query($ldfi,$db);
				 $ldfir=DB_fetch_array($ldfiq);
				 $lastdocid=$ldfir['doc_id'];*/
				 
				 
				 
				 
			 
				
		if($q)
		{
		unset($_POST);	
		
		
		}	  
	 $ldi="select max(id) id from medical_claim";
	 $ldiq=DB_query($ldi,$db);
	 $ldir=DB_fetch_array($ldiq);
	 $lastid=$ldir['id'];
					  
	/*$sqllevel = "select level from tbl_workflow_details where workflow_id=3";
	$reslevel = DB_query($sqllevel,$db);
	$rslevel = DB_fetch_array($reslevel);*/
	
	/*$clft="insert into tbl_workflow_task set message_time='".strtotime(date('d-m-Y'))."',level='".$rslevel['level']."',status='',comment='',doc_id='".$lastdocid."',work_id='".$lastid."'";		
		$clftq=DB_query($clft,$db);		*/		  
					if(isset($_SESSION['amount']))
					{				  
			  $element=count($_SESSION['amount']);	
			 }
			 $nu=$_SESSION['exp'];
			for($i=0;$i<=($nu-1);$i++)
             {  
									  
						$tc=  "insert into medical_claim_detail set claim_id='".$lastid."',
						              charges='".$_SESSION['charges'][$i]."',
									  detail_cash_name='".$_SESSION['detail_cash_name'][$i]."',
									  medicine='".$_SESSION['medicine'][$i]."',
									  type='".$_SESSION['type'][$i]."'									  									 
									 ";
									 
							$tcq=DB_query($tc,$db);		
							
							
					
														 
			 }
			 $cf="insert into mediclaim_flow set claim_id='".$lastid."',
					                                postedby='".$_SESSION['uid']."',
													remarks='Claim Submitted',
													dateon='".strtotime(date('d-M-Y'))."'";
					$cfq=DB_query($cf,$db);	
					if($cfq)
					{
					  echo "Medical Claim Submitted";
					}
					
			 unset($_SESSION['charges']);
			 unset($_SESSION['detail_cash_name']);
			 unset($_SESSION['medicine']);
			 unset($_SESSION['type']);
			 unset($_SESSION['exp']);
			 
			
    }
	if($cfq)
					{
	header("location:medical_claim_user.php");
	}
}
if(!isset($_SESSION['num']))
{
$_SESSION['num']=0;
}
if(isset($_POST['save']))
{ 
   if($_POST['type']=='')
   {
     $InputError = 2;
     prnMsg(_('Select Type'),'error');
	}
if($_POST['charges']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter charges'),'error');
	}
	if (($_POST['charges']!='') && (!is_numeric($_POST['charges'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter Numeric Value For charges'),'error');
	}
	
	if($_POST['detail_cash_name']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Details of cash Memos'),'error');
	}
   if (($_POST['detail_cash_name']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['detail_cash_name'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid Enter Details of cash Memos, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['medicine']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Description'),'error');
	}
   if (($_POST['medicine']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['medicine'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid Description, A-Z or 0-9 is Allowed'),'error');
	}
 if($InputError!=2)
	{
  $_SESSION['exp']++;
  
  $_SESSION['charges'][]=$_POST['charges'];
  $_SESSION['detail_cash_name'][]=$_POST['detail_cash_name'];
  $_SESSION['medicine'][]=$_POST['medicine'];
  $_SESSION['type'][]=$_POST['type'];
  
  
  unset($_POST['charges']);
  unset($_POST['detail_cash_name']);
  unset($_POST['medicine']);
  unset($_POST['type']);
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
    $_SESSION['charges'][$no]='';
	$_SESSION['detail_cash_name'][$no]='';
    $_SESSION['medicine'][$no]='';
    $_SESSION['type'][$no]='';
  }
}

if(isset($_REQUEST['uid']) && $_REQUEST['uid']!='')
{ 
  $ei="select * from tbl_joinings where employee_id='".$_REQUEST['uid']."' ";
  $eiq=DB_query($ei,$db);
  $eir=DB_fetch_array($eiq);
  $uid=$eir['program_uid'];
}
else
{
  $uid=$_SESSION['uid'];
}
  $emp="select * from tbl_joinings where program_uid='".$uid."' ";
$empq=DB_query($emp,$db);
$empr=DB_fetch_array($empq);

$des="select lookup_name,lookup_id from tbl_lookups where lookup_id='".$empr['designationid']."'";
$desq=DB_query($des,$db);
$desr=DB_fetch_array($desq);

$cor="select corporation_name from tbl_corporations where corporation_id='".$empr['current_officeid']."'";
$corq=DB_query($cor,$db);
$corr=DB_fetch_array($corq);



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

function deletedetail(a)
{
  window.location.href='medical_claim.php?op=delete&no='+a;
}
</script>
<link href="images/style.css" rel="stylesheet" type="text/css" />
<div class="breadcrumb">Home &raquo; <a href="medical_claim_user.php">List of My Claim</a> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Medical Claim</a></div>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="553" border="0" cellpadding="2" cellspacing="1">
<tr class="oddrow"><td colspan="2" align="center"><h2>Medical Claim</h2></td>
</tr>
    <tr class="evenrow"> <td ><div class="left">Employee Id: <span style="color:#FF0000">*</span> </div> 
      <div class="right"><input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']?>" /><select name="emp_id" onchange="return getuser(this.value)" id="emp_id">
      <option value="">--Select--</option>
                       <?php 
					    $emi="select * from tbl_joinings ORDER BY employee_name ASC";
						$emiq=DB_query($emi,$db);
						while($emir=DB_fetch_array($emiq))
						{ 
						 if($empr['employee_id']==$emir['employee_id'])
						   {
					   ?>
                       <option value="<?php echo $emir['employee_id'];?>" selected="selected"><?php echo ucwords($emir['employee_name']."(".$emir['employee_id'].")")  ?></option>
                       <?php
					   }
					   else
					   {?>
                       <option value="<?php echo $emir['employee_id'];?>" ><?php echo ucwords($emir['employee_name']."(".$emir['employee_id'].")") ?></option>
					   <?php
					   }
					   }
					   ?>  
                       </select></div></td></tr>
                       
    
	<tr class="oddrow">
	  <td><div class="left">Designation: <span style="color:#FF0000">*</span> </div><div class="right"><input type="text" name="designatio" id="designatio" size="45" value="<?php echo $desr['lookup_name'];?>"  readonly="readonly"/><input type="hidden" name="designation" size="45" value="<?php echo $desr['lookup_id'];?>"  readonly="readonly"/>
    </div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Mobile No.: <span style="color:#FF0000">*</span> </div> <div class="right"><input type="text" name="mobile" id="mobile" size="45" maxlength="11" value="<?php echo $empr['mobile'];  ?>" readonly="readonly"/></div></td></tr>
	<tr class="oddrow">
	  <td><div class="left">Email Id: <span style="color:#FF0000">*</span> </div><div class="right"><input type="text" name="email" id="email" size="45" maxlength="21" value="<?php echo $empr['email'];  ?>" readonly="readonly"/></div></td></tr>
	<tr class="evenrow"> 
    <td><div class="left">Office in which employed: <span style="color:#FF0000">*</span> </div> <div class="right"><input type="text" name="office1" id="office1" value="<?php echo $corr['corporation_name'];?>" readonly="readonly"/><input type="hidden" name="office" value="<?php echo $empr['current_officeid'];?>" /></div></td></tr>
	<tr class="oddrow">
	  <td><div class="left">Basic Pay: <span style="color:#FF0000">*</span> </div> <div class="right"><input type="text" name="basic_pay" id="basic_pay" size="45" maxlength="45" value="<?php echo $empr['basic_pay']?>" readonly="readonly"/></div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Name of patient and rotation with the claimant: <span style="color:#FF0000">*</span> </div>
    <div class="right"><input type="text" name="name_rotaion" id="name_rotaion" size="45" maxlength="45" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['name_rotaion']?>"/></div></td></tr>
	<tr class="oddrow"> <td><div class="left">Period of Illness: <span style="color:#FF0000">*</span> </div>
  <div class="right"><input type="text" id="period_illness"  name="period_illness"  size="45" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['period_illness']?>" maxlength="10"/></div></td></tr>
	
       <tr class="evenrow">
	  <td ><div class="left">Claim Type: <span style="color:#FF0000">*</span> </div>
   <div class="right"><select name="claim_type" id="claim_type">
    <option value="">--Select--</option>
    <?php
$ca="Select * from claim_type where category='Medical Claim' ORDER BY claimtypename ASC";
$caq=DB_query($ca,$db);
while($car=DB_fetch_array($caq))
{ 
  if($_POST['claim_type']==$car['id'])
  {
?>
<option value="<?php echo $car['id'];?>"  selected="selected"><?php echo ucwords ($car['claimtypename'])?></option>
<?php
}
else
{
?>
    <option value="<?php echo $car['id'];?>" ><?php echo ucwords( $car['claimtypename'])?></option>
    <?php } }
	?>                 </select></div></td></tr>
   
      </table>
       <br/><br/>
    <table width="553" border="0" cellpadding="2" cellspacing="1">
    <tr><td class="tblHeaderLeft"><h1>Particulars of treatment</h1><span class="addrecord"><a href="javascript:void(0)" onclick="showdiv();">Add Particulars</a></span></td>
    </tr>
    <tr><th >Type </th> <th >Details of cash Memos </th><th>Description</th><th>Charges</th><th>Action</th></tr> 
    <?php
	$nu=$_SESSION['exp'];
	 for($j=0;$j<=($nu-1);$j++)
             { if($_SESSION['charges'][$j]!='')
			     { 
				    $t="select * from tbl_lookups where lookup_id='".$_SESSION['type'][$j]."'";
			          $tq=DB_query($t,$db);
					  $tr=DB_fetch_array($tq);
	?>
   <tr class="odd"> <td ><?php echo $tr['lookup_name'];?> </td> <td ><?php echo $_SESSION['detail_cash_name'][$j];?> </td><td><?php echo $_SESSION['medicine'][$j];?></td><td><?php echo $_SESSION['charges'][$j];?></td><td><a href="javascript:void(0)" onclick="deletedetail(<?php echo $j;?>)">Delete</a></td></tr> 
      <?php
	  }
	  } ?>
   
  </table>
    <br/><br/>
      
      <div id="detail"  <?php if($InputError!=2) { ?>style="display:none" <?php } ?>>
      <table>
      <tr class="evenrow">
	  <td><div class="left">Type:<span style="color:#FF0000">*</span> </div>
	 <div class="right"><select name="type">
      <option value="">--Select--</option>
           <?php 
		    $ty="select * from tbl_lookups where lookupType_id='65' ORDEr BY lookup_name ASC"; 
			$tyq=DB_query($ty,$db);
			while($tyr=DB_fetch_array($tyq))
			{  if($_POST['type']==$tyr['lookup_id'])
			   {
		   ?>
           <option value="<?php echo ucwords ($tyr['lookup_id']);?>" selected="selected"><?php echo ucwords ($tyr['lookup_name']);?></option>
           <?php
		   }
		   else
		   {
		     
		   ?>
           <option value="<?php echo ucwords ( $tyr['lookup_id']);?>"><?php echo ucwords ($tyr['lookup_name']);?></option>
           
           <?php
		   }
		   }
		   ?>
           </select></div></td>
    </tr>
	<tr class="oddrow">
    <td><div class="left">Charges: <span style="color:#FF0000">*</span> </div> <div class="right"><input type="text" name="charges"  size="45" maxlength="11"  value="<?php echo $_POST['charges'];?>"onkeypress = "return fononlyn(event)"/></div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Details of cash Memos: <span style="color:#FF0000">*</span> </div> <div class="right"><input type="text"  name="detail_cash_name"  size="45" maxlength="11"  value="<?php echo $_POST['detail_cash_name'];?>"onkeypress="return alphanumeric(event)"/></div></td></tr>
	<tr class="oddrow">
	  <td><div class="left">Description: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text"  name="medicine"  size="45"  maxlength="45" value="<?php echo $_POST['medicine'];?>" onkeypress="return alphanumeric(event)"/></div></td>
    </tr>
	
    <tr class="evenrow">
	  <td align="center"><input type="submit" name="save" value="Add" /> </td>
    </tr>
    </table>
    </div><br />
    <table>
   <tr class="oddrow"><td colspan="2" align="center"><h2>Summary</h2></td>
   </tr>
	<tr class="evenrow">
	  <td><div class="left">Total claim: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text"  name="tot_claim"  id="tot_claim" size="45"  maxlength="10" value="<?php if(isset($_SESSION['charges'])) { echo array_sum($_SESSION['charges']); }?>" onkeypress = "return fononlyn(event)"/></div></td>
	</tr>
    <tr class="oddrow">
	  <td><div class="left">Less-Advance Drawn: </div>
	 <div class="right"><input type="text"  name="advance"  size="45"  id="advance" maxlength="10" value="<?php echo $_POST['advance'];?>" onblur="return finalamount(this.value,'net_amount','tot_claim');"onkeypress = "return fononlyn(event)"/></div></td>
	</tr>
	
	<tr class="evenrow">
	  <td><div class="left">Net amount payable: <span style="color:#FF0000">*</span> </div>
	 <div class="right"><input type="text"  name="net_amount"  id="net_amount" size="45"  maxlength="10" value="<?php echo $_POST['net_amount']?>" onkeypress = "return fononlyn(event)"/></div></td>
    </tr>
    
	<tr class="oddrow">
	  <td><div class="left">Bills: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="file" name="file" size="10"  id="file"/></div></td>
    </tr>
   <tr class="evenrow">
	  <td><input type="checkbox" value="1"  name="declaration"/>  I hereby declare that the statements in this application are true to the best of my knowledge and belief and that the person for whom medical expenses were incurred is wholly dependent on  me.</td>
	  
	  </tr>
      
    <tr class="oddrow"><td align="center"><input  type="submit" name="submit" value="Save" /> <input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>

</form>
<br />
<?php
include('includes/footer.inc');
?>
<?php
if (isset($_POST['submit']) ){
  
   if($_POST['emp_id']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('emp_id').className='ercol';</script>";
	}
	    
	if($_POST['name_rotaion']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('name_rotaion').className='ercol';</script>";
	}
	if (($_POST['name_rotaion']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['name_rotaion'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('name_rotaion').className='ercol';</script>";
	}
	if($_POST['period_illness']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('period_illness').className='ercol';</script>";
	}
	if (($_POST['period_illness']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['period_illness'])))
	{
	echo "<script type='text/javascript'>document.getElementById('period_illness').className='ercol';</script>";
	}
	if($_POST['claim_type']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('claim_type').className='ercol';</script>";
	}
	
	
	if($_POST['tot_claim']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('tot_claim').className='ercol';</script>";
	}
	if (($_POST['tot_claim']!='') && (!is_numeric($_POST['tot_claim'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('tot_claim').className='ercol';</script>";
	}
	
	
	if($_POST['net_amount']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('net_amount').className='ercol';</script>";
	}
	if (($_POST['net_amount']!='') && (!is_numeric($_POST['net_amount'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('net_amount').className='ercol';</script>";
	}
	
	if($_FILES["file"]["name"]=='')
   {
    echo "<script type='text/javascript'>document.getElementById('file').className='ercol';</script>";
	}
	
	}
?>