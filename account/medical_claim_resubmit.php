<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="medical_claim_user.php">List of My Claim</a> &raquo; <a href="'. $_SERVER['PHP_SELF'].'">Resubmit Claim</a></div>';
$rannum=rand(1,20000);

if (isset($_POST['save']) && $_POST['save'] == 'Save'){
  $InputError = 0;
//print_r($_POST);
//exit;
   
   if($_POST['emp_id']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Employee Id'),'error');
	}
	/*if (($_POST['emp_id']!='') && (!is_numeric($_POST['emp_id'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Employee Id'),'error');
	}*/
    
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
     prnMsg(_('Enter Total Amount'),'error');
	}
	if (($_POST['net_amount']!='') && (!is_numeric($_POST['net_amount'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Net Amount'),'error');
	}
	if($_FILES["file"]["name"]=='' && $_POST['uploadedfile']=='')
   {
     $InputError = 1;
     prnMsg(_('Upload Bill Against Claim'),'error');
	}

	if(!isset($_POST['declarationn']) || $_POST['declarationn']!=1)
   {
     $InputError = 1;
     prnMsg(_('Accept Declaration'),'error');
	}

  if($InputError!=1)
	{ 
	  if(($_FILES["file"]["name"])!='')
	  {
       @move_uploaded_file($_FILES["file"]["tmp_name"],"bills/" . $_FILES["file"]["name"]);
	   
	   $filename=$rannum.$_FILES["file"]["name"];
	  }
	  else 
	  {
		   $filename=$_POST['uploadedfile'];
	  }
	  
	   
	    $adi="select doc_id,task_id from medical_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
 
 /*$ta="update tbl_workflow_task set status='1' where task_id='".$adir['task_id']."'";
 $taq=DB_query($ta,$db);*/
 
 updateTask($adir['task_id'],$db);
	$corp=0;
	 $role = getRole($_SESSION['uid'],$db);
	if( $role != 13 && $role != 5 && $role != 19 )
	{
		$corp=getCorporationBranch($_SESSION['uid'],$db);
		 $reto="13";
	}
	 $level = 1;
	 if($role == 13)
	{
		 //$corp=getCorporationBranch($_SESSION['uid'],$db);
		 $level = 2;
		 $reto="5";
	}
	 if($role == 5)
	{
		 $level = 3;
		 $reto="19";
	}
	 if($role == 19)
	{
		 $level = 4;
		 $reto="6";
	}
 
 /* $awt="insert into tbl_workflow_task set level='1',status='0',doc_id='".$adr."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);*/
	 createTask($level,$adr,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
	 	
		$mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];  
		
		 $s="update medical_claim set 
									  emp_id='".$_POST['emp_id']."',
									  mobile='".$_POST['mobile']."',
									  email='".$_POST['email']."',
									  basic_pay='".$_POST['basic_pay']."',
									  name_rotaion='".$_POST['name_rotaion']."',
									  period_illness='".$_POST['period_illness']."',
									  tot_claim='".$_POST['tot_claim']."',
									  net_amount='".$_POST['net_amount']."',
									  claim_type='".$_POST['claim_type']."',
									  enteredby='".$_SESSION['uid']."',
									  advance='".$_POST['advance']."',
									  date='".strtotime(date('d-m-Y'))."',
									  bill='".$filename."',
									  status='0',
									  task_id='".$mtii."',
									  voucher_generated='0' where id='".$_REQUEST['clid']."'";
									  
				$q=DB_query($s,$db);
				
		
			$tc=  "delete from medical_claim_detail where claim_id='".$_REQUEST['clid']."'";
									 
							$tcq=DB_query($tc,$db);		
							
								  
									  
					if(isset($_SESSION['amount']))
					{				  
			  $element=count($_SESSION['amount']);	
			 }
			 $nu=$_SESSION['exp'];

			for($i=0;$i<=($nu-1);$i++)
             {  
				if($_SESSION['charges'][$i] && $_SESSION['charges'][$i] != '0.00')
				 {				  
					$tc=  "insert into medical_claim_detail set claim_id='".$_REQUEST['clid']."',
						              charges='".$_SESSION['charges'][$i]."',
									  detail_cash_name='".$_SESSION['detail_cash_name'][$i]."',
									  medicine='".$_SESSION['medicine'][$i]."',
									  type='".$_SESSION['type'][$i]."'									  									 
									 ";
									 
							$tcq=DB_query($tc,$db);		
							
				 }		
					
														 
			 }
			$cf="insert into mediclaim_flow set claim_id='".$lastid."',
					                                postedby='".$_SESSION['uid']."',
													remarks='Claim Re Submitted',
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
			 unset($_SESSION['fetch']);
			 unset($_SESSION['pname']);
			 unset($_SESSION['pillness']);
			 unset($_SESSION['ptype']);
			
    }
	header("location:medical_claim_user.php?msg=Medical Claim MD-".$_GET['clid']." Is Resubmitted");
}
if(!isset($_SESSION['num']))
{
$_SESSION['num']=0;
}
if(isset($_POST['save']) && $_POST['save'] == 'Add')
{ 
	$_SESSION['pname'] = $_POST['name_rotaion'];
	$_SESSION['pillness'] = $_POST['period_illness'];
	$_SESSION['ptype'] = $_POST['claim_type'];
$InputError=0;
if($_POST['type']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Type'),'error');
	}
if($_POST['charges']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter charges'),'error');
	}
	if (($_POST['charges']!='') && (!is_numeric($_POST['charges'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For charges'),'error');
	}
	
	if($_POST['detail_cash_name']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Details of cash Memos'),'error');
	}
   if (($_POST['detail_cash_name']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['detail_cash_name'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Enter Details of cash Memos, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['medicine']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Description'),'error');
	}
   if (($_POST['medicine']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['medicine'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Description, A-Z or 0-9 is Allowed'),'error');
	}
	if($InputError!=1)
	{
		$_SESSION['exp']++;

		$_SESSION['charges'][]=$_POST['charges'];
		$_SESSION['detail_cash_name'][]=$_POST['detail_cash_name'];
		$_SESSION['medicine'][]=$_POST['medicine'];
		$_SESSION['type'][]=$_POST['type'];
		header("Location:medical_claim_resubmit.php?clid=".$_REQUEST['clid']);exit;
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
	header("Location:medical_claim_resubmit.php?clid=".$_REQUEST['clid']);exit;
  }
}

if(isset($_REQUEST['uid']) && $_REQUEST['uid']!='')
{ 
  $ei="select * from tbl_joinings where employee_id='".$_REQUEST['uid']."'";
  $eiq=DB_query($ei,$db);
  $eir=DB_fetch_array($eiq);
  $uid=$eir['program_uid'];
}
else
{
  $uid=$_SESSION['uid'];
}
  $emp="select * from tbl_joinings where program_uid='".$uid."'";
$empq=DB_query($emp,$db);
$empr=DB_fetch_array($empq);


$des="select lookup_name from tbl_lookups where lookup_id='".$empr['designationid']."'";
$desq=DB_query($des,$db);
$desr=DB_fetch_array($desq);

$cor="select corporation_name from tbl_corporations where corporation_id='".$empr['current_officeid']."'";
$corq=DB_query($cor,$db);
$corr=DB_fetch_array($corq);
//echo $_SESSION['exp'];
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
  document.getElementById('net_amount').value=document.getElementById('tot_claim').value - document.getElementById(a).value;
}

function getuser(a)
{
  window.location.href='medical_claim.php?uid='+a;
}


</script>
<?php 
if(isset($_REQUEST['clid']))
{
$eu="select * from medical_claim where id='".$_REQUEST['clid']."'";
$euq=DB_query($eu,$db);
$eur=DB_fetch_array($euq);
$_REQUEST['uid']=$eur['emp_id'];
$_POST['name_rotaion']=$eur['name_rotaion'];
$_POST['period_illness']=$eur['period_illness'];
$_POST['claim_type']=$eur['claim_type'];
$_POST['advance']=$eur['advance'];
if(!(isset($_POST['save'])))
{
$_SESSION['advance']=$eur['advance'];
$_POST['net_amount']=$eur['net_amount'];
$_POST['charges']=$eur['tot_claim'];
}
if(!isset($_SESSION['fetch']))
{
 $pt="select * from medical_claim_detail where claim_id='".$_REQUEST['clid']."'";
$ptq=DB_query($pt,$db);
$_SESSION['exp'] = 0;
while($ptr=DB_fetch_array($ptq))
{ $_SESSION['exp']++;
 $_SESSION['charges'][]=$ptr['charges'];
  $_SESSION['detail_cash_name'][]=$ptr['detail_cash_name'];
  $_SESSION['medicine'][]=$ptr['medicine'];
  $_SESSION['type'][]=$ptr['type'];
}
$_SESSION['fetch']=1;
}
}?>
<link href="images/style.css" rel="stylesheet" type="text/css" />
<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="553" border="0" cellpadding="2" cellspacing="1">
<tr class="oddrow"><td colspan="2" align="center"><h2>Medical Claim</h2></td>
</tr>
    <tr class="evenrow"> <td width="272">Employee Id:<span style="color:#FF0000">*</span> </td> 
      <td width="270"><input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']?>" /><select name="emp_id" >
      <option value="">--Select--</option>
                       <?php 
					    $emi="select * from tbl_joinings";
						$emiq=DB_query($emi,$db);
						while($emir=DB_fetch_array($emiq))
						{ 
						 if($empr['employee_id']==$emir['employee_id'])
						   {
					   ?>
                       <option value="<?php echo $emir['employee_id'];?>" selected="selected"><?php echo ($emir['employee_name']."(".$emir['employee_id'].")")  ?></option>
                       <?php
					   }
					   else
					   {?>
                       <option value="<?php echo $emir['employee_id'];?>" ><?php echo ($emir['employee_name']."(".$emir['employee_id'].")") ?></option>
					   <?php
					   }
					   }
					   ?>  
                       </select></td></tr>
                       
    
	<tr class="oddrow">
	  <td>Designation:<span style="color:#FF0000">*</span> </td> <td><input type="text" name="designatio" size="45" value="<?php echo $desr['lookup_name'];?>"  readonly="readonly"/><input type="hidden" name="designation" size="45" value="<?php echo $desr['lookup_id'];?>"  readonly="readonly"/>
    </td></tr>
	<tr class="evenrow">
	  <td>Mobile No:<span style="color:#FF0000">*</span> </td> <td><input type="text" name="mobile"  size="45" maxlength="11" value="<?php echo $empr['mobile'];  ?>" readonly="readonly"/></td></tr>
	<tr class="oddrow">
	  <td>Email Id:<span style="color:#FF0000">*</span> </td><td><input type="text" name="email"  size="45" maxlength="21" value="<?php echo $empr['email'];  ?>" readonly="readonly"/></td></tr>
	<tr class="evenrow"> 
    <td>Office in which employed:<span style="color:#FF0000">*</span> </td> <td><input type="text" name="office1" value="<?php echo $corr['corporation_name'];?>" readonly="readonly"/><input type="hidden" name="office" value="<?php echo $empr['current_officeid'];?>" /></td></tr>
	<tr class="oddrow">
	  <td>Basic pay:<span style="color:#FF0000">*</span> </td> <td><input type="text" name="basic_pay"  size="45" maxlength="45" value="<?php echo $empr['basic_pay']?>" readonly="readonly"/></td></tr>
	<tr class="evenrow">
	  <td>Name of patient and rotation with the claimant:<span style="color:#FF0000">*</span> </td>
    <td><input type="text" name="name_rotaion"  size="45" maxlength="45" value="<?php echo ($_SESSION['pname'])?$_SESSION['pname']:$_POST['name_rotaion']?>"/></td></tr>
	<tr class="oddrow"> <td>Period of illness:<span style="color:#FF0000">*</span> </td>
    <td><input type="text"  name="period_illness"  size="45" value="<?php echo ($_SESSION['pillness'])?$_SESSION['pillness']:$_POST['period_illness']?>" maxlength="10"/></td></tr> 
	
       <tr class="evenrow">
	  <td >Claim Type:<span style="color:#FF0000">*</span> </td>
    <td ><select name="claim_type">
    <option value="">--Select--</option>
    <?php
$ca="Select * from claim_type where category='Medical Claim'";
$caq=DB_query($ca,$db);
while($car=DB_fetch_array($caq))
{ 
	$ctype = ($_SESSION['ptype'])?$_SESSION['ptype']:$_POST['claim_type'];
  if($ctype==$car['id'])
  {
?>
<option value="<?php echo $car['id'];?>" selected="selected"><?php echo $car['claimtypename']?></option>
<?php
}
else
{
?>
    <option value="<?php echo $car['id'];?>" ><?php echo $car['claimtypename']?></option>
    <?php } }
	?>                 </select></td></tr>
    <tr class="oddrow">
	  <td>&nbsp;</td>
	  <td><a href="javascript:void(0)" onClick="showdiv();">Add Details</a></td>
	</tr>
      </table>
       <br/><br/>
    <table width="553" border="0" cellpadding="2" cellspacing="1">
    <tr class="oddrow"><td colspan="5" align="center"><h2>Particulars of treatment</h2></td>
    </tr>
    <tr class="evenrow"><td >&nbsp; </td> <td >Details of cash Memos </td><td>Description</td><td>Charges</td><td></td></tr> 
    <?php
	$nu=$_SESSION['exp'];
	 for($j=0;$j<=($nu-1);$j++)
             { if($_SESSION['charges'][$j]!='' && $_SESSION['detail_cash_name'][$j]!='' )
			     { 
				    $t="select * from tbl_lookups where lookup_id='".$_SESSION['type'][$j]."'";
			          $tq=DB_query($t,$db);
					  $tr=DB_fetch_array($tq);
	?>
   <tr class="oddrow"> <td ><?php echo $tr['lookup_name'];?> </td> <td ><?php echo $_SESSION['detail_cash_name'][$j];?> </td><td><?php echo $_SESSION['medicine'][$j];?></td><td><?php echo round($_SESSION['charges'][$j]);?></td><td><a href="medical_claim_resubmit.php?op=delete&no=<?php echo $j;?>&clid=<?php echo $_REQUEST['clid'];?>">Delete</a></td></tr> 
      <?php
	  }
	  } ?>
       
  </table>
    <br/><br/>
      
      <div id="detail"  style="display:none">
      <table>
      
      <tr class="evenrow">
	  <td>Type:<span style="color:#FF0000">*</span> </td>
	  <td><select name="type">
      <option value="">--Select--</option>
           <?php 
		    $ty="select * from tbl_lookups where lookupType_id='65'";
			$tyq=DB_query($ty,$db);
			while($tyr=DB_fetch_array($tyq))
			{
		   ?>
           <option value="<?php echo $tyr['lookup_id'];?>"><?php echo $tyr['lookup_name'];?></option>
           <?php
		   }
		   ?>
           </select></td>
    </tr>
	<tr class="oddrow">
    <td>Charges:<span style="color:#FF0000">*</span> </td> <td><input type="text" name="charges"  size="45" maxlength="11" class="number" /></td></tr>
	<tr class="evenrow">
	  <td>Details of cash Memos:<span style="color:#FF0000">*</span> </td> <td><input type="text"  name="detail_cash_name"  onkeypress="return alphanumeric(event)"  size="45" maxlength="11" /></td></tr>
	<tr class="oddrow">
	  <td>Description:<span style="color:#FF0000">*</span> </td>
	  <td><input type="text"  name="medicine"  size="45"  maxlength="45"  onkeypress="return alphanumeric(event)" /></td>
    </tr>
	
    <tr class="evenrow">
	  <td>&nbsp;</td>
	  <td><input type="submit" name="save" value="Add" /> </td>
    </tr>
    </table>
    </div>
    <table>
   <tr class="oddrow"><td colspan="2" align="center"><h2>Summery</h2></td>
   </tr>
	<tr class="evenrow">
	  <td width="50%">Total claim:<span style="color:#FF0000">*</span> </td>
	  <td><input type="text"  name="tot_claim"  id="tot_claim" size="45"  maxlength="45" value="<?php if(isset($_SESSION['charges'])) { echo array_sum($_SESSION['charges']); } else { echo $_POST['tot_claim']; }?>"/></td>
	</tr>
    <tr class="oddrow">
	  <td>Less-Advance Drawn:</td>
	  <td><input type="text"  name="advance"  size="45"  id="advance" maxlength="45" value="<?php echo $_POST['advance'];?>" onBlur="return finalamount('advance','net_amount','tot_claim');"/></td>
	</tr>
	
	<tr class="evenrow">
	  <td>Net amount payable:<span style="color:#FF0000">*</span> </td>
	  <td><input type="text"  name="net_amount"  id="net_amount" size="45"  maxlength="10" value="<?php echo $_POST['net_amount'];?>"/></td>
    </tr>
    
	<tr class="oddrow">
	  <td>Bills:<span style="color:#FF0000">*</span> </td>
	  <td><input type="file" name="file"  /><input type="hidden" name="uploadedfile" value="<?php echo $eur['bill'];?>" /><a  href="bills/<?php echo $eur['bill'];?>" target="_blank">View Bill</a></td>
    </tr>
   <tr class="evenrow">
	  <td colspan="2"><input type="checkbox" value="1"  name="declarationn" checked="checked" onclick="finalamount('advance',1,1);"/>  I hereby declare that the statements in this application are true to the best of my knowlwdge and belief and that the person for whom medical expenses were incurred is wholly dependent on  me.</td>
	  
	  </tr>
      
    <tr class="oddrow"><td colspan="2" align="center"><input  type="submit" name="save" value="Save" onclick="finalamount('advance',1,1);" /> <input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>

</form>
<br />
<?php
include('includes/footer.inc');
?>