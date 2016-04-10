<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/*echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="tour_claim_user.php">List of My Claim</a> &raquo; <a href="">Resubmit Claim</a></div>';
$rannum=rand(1,20000);
/*function filecheck($path)
{
$fn=explode("/",$path);
  $mn=count($fn);
 return $fn[$mn-1];
  
}
$sr=filecheck($_SERVER['HTTP_REFERER']);
$sel=filecheck($_SERVER['PHP_SELF']);
if($sr==$sel)
{
 
}
else
{
        unset($_SESSION['dep_station']);
			 unset($_SESSION['dep_time']);
			 unset($_SESSION['dep_date']);
			 unset($_SESSION['arr_station']);
			 unset($_SESSION['arr_time']);
			 unset($_SESSION['arr_date']);
			 unset($_SESSION['no_of_km']);
			 unset($_SESSION['mode_journey']);
			 unset($_SESSION['class_journey']);
			 unset($_SESSION['rateperkm']);
			 unset($_SESSION['amount']);
			 unset($_SESSION['daily_allowance']);
			 unset($_SESSION['exp']);	
}
*/



if ( isset($_POST['save']) && $_POST['save'] == 'Submit' ){
  $InputError = 0;

      $dipdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
      $arrdate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];

 
   
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
    if($_POST['claim_type']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Claim Type'),'error');
	}
	
	
	if($_POST['halting_period']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Halting Period'),'error');
	}
	
	if($_POST['purpose_journey']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Purpose of Journey'),'error');
	}
	 if (($_POST['purpose_journey']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['purpose_journey'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Purpose of Journey, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['tot_amount']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Total Amount'),'error');
	}
	if (($_POST['tot_amount']!='') && (!is_numeric($_POST['tot_amount'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Total Amount'),'error');
	}
	if($_FILES["file"]["name"]=='' && $_POST['uploadedfile']=='')
   {
     $InputError = 1;
     prnMsg(_('Upload Bill Against Claim'),'error');
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
		
		 $adi="select doc_id,task_id from tour_claim where id='".$_REQUEST['clid']."'";
				$adiq=DB_query($adi,$db);
				$adir=DB_fetch_array($adiq);
				$adr=$adir['doc_id'];
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
		
		 createTask($level,$adr,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);

		$mti="select max(task_id) as task_id from tbl_workflow_task";
		$mtiq=DB_query($mti,$db);
		$mtir=DB_fetch_array($mtiq);
		$mtii=$mtir['task_id'];
		
		 $empof="select current_officeid from tbl_joinings where employee_id='".$_POST['emp_id']."'";
		$empofq=DB_query($empof,$db);
		$empofr=DB_fetch_array($empofq);
		
		$s="update  tour_claim set 
									  emp_id='".$_POST['emp_id']."',
										  office='".$empofr['current_officeid']."',
									  purpose_journey='".$_POST['purpose_journey']."',
									  claim_type='".$_POST['claim_type']."',
									  basic_pay='".$_POST['basic_pay']."',
									  halting_period='".$_POST['halting_period']."',
									  total_amount='".$_POST['tot_amount']."',
									  bill_claim='".$filename."',
									  date='".strtotime(date('d-m-Y'))."',
									  enteredby='".$_SESSION['uid']."',
									  status='0',
									  task_id='".$mtii."',
									  voucher_generated='0' where id='".$_REQUEST['clid']."'";
									  
				$q=DB_query($s,$db);
		$tcd=  "delete from tour_claim_detail where claim_id='".$_REQUEST['clid']."'";
									 
							$tcdq=DB_query($tcd,$db);		
	
					  
									  
					if(isset($_SESSION['amount']))
					{				  
			  $element=count($_SESSION['amount']);	
			 }
			 $nu=$_SESSION['exp'];
			for($i=0;$i<=($nu-1);$i++)
             {  
									  
						 $tc=  "insert into tour_claim_detail set claim_id='".$_REQUEST['clid']."',
						              dep_station='".$_SESSION['dep_station'][$i]."',
									  dep_time='".$_SESSION['dep_time'][$i]."',
									  dep_date='".$_SESSION['dep_date'][$i]."',
									  arr_station='".$_SESSION['arr_station'][$i]."',
									  arr_time='".$_SESSION['arr_time'][$i]."',
									  arr_date='".$_SESSION['arr_date'][$i]."',
									  no_of_km='".$_SESSION['no_of_km'][$i]."',
									  mode_journey='".$_SESSION['mode_journey'][$i]."',
									  class_journey='".$_SESSION['class_journey'][$i]."',
									  rateperkm='".$_SESSION['rateperkm'][$i]."',
									  amount='".$_SESSION['amount'][$i]."',
									  daily_allowance='".$_SESSION['daily_allowance'][$i]."'									 
									 ";
									 
							$tcq=DB_query($tc,$db);		
							
							
					
														 
			 }
			 $cf="insert into claim_flow set claim_id='".$lastid."',
					                                postedby='".$_SESSION['uid']."',
													remarks='Claim Re Submitted',
													dateon='".strtotime(date('d-M-Y'))."'";
					$cfq=DB_query($cf,$db);	
			 unset($_SESSION['dep_station']);
			 unset($_SESSION['dep_time']);
			 unset($_SESSION['dep_date']);
			 unset($_SESSION['arr_station']);
			 unset($_SESSION['arr_time']);
			 unset($_SESSION['arr_date']);
			 unset($_SESSION['no_of_km']);
			 unset($_SESSION['mode_journey']);
			 unset($_SESSION['class_journey']);
			 unset($_SESSION['rateperkm']);
			 unset($_SESSION['amount']);
			 unset($_SESSION['daily_allowance']);
			 unset($_SESSION['exp']);
			 unset($_SESSION['fetch']);
			 
		
		header("location:tour_claim_user.php?msg=Tour Claim TA-".$_GET['clid']." Is Resubmitted");	
    }
	
}
if(!isset($_SESSION['num']))
{
$_SESSION['num']=0;
}
if(isset($_POST['save']) && $_POST['save'] == 'Save')
{  
$InputError = 0;
  //$dipdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
     // $arrdate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];

	  $dipdate=$_POST['JournalProcessDate'];
	  $arrdate=$_POST['JournalProcessDate1'];

/* if(($_POST['element_1_2']=='' || $_POST['element_1_1']=='' || $_POST['element_1_3']=='') && $_SESSION['date']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Departure Date'),'error');
	}
	*/
	 if($dipdate=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Departure Date'),'error');
	}
 
  if($_POST['dep_station']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Departure Office'),'error');
	}
	
	if($_POST['dep_time']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Departure Time'),'error');
	}
	 if($_POST['arr_station']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Arrival Office'),'error');
	}
	if($_POST['arr_time']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Arrival Time'),'error');
	}
	/*if( $_POST['element_2_2']=='' || $_POST['element_2_1']=='' || $_POST['element_2_3']=='' )
   {
     $InputError = 2;
     prnMsg(_('Enter Arrival Date'),'error');
	}*/
	if($arrdate=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Arrival Date'),'error');
	}
	if($_POST['no_of_km']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter No. of Km'),'error');
	}
	if (($_POST['no_of_km']!='') && (!is_numeric($_POST['no_of_km'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter Numeric Value For No of Km'),'error');
	}
	
	if($_POST['mode_journey']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Mode of Journey'),'error');
	}
   if($_POST['class_journey']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Class of journey'),'error');
	}
	
	if($_POST['rateperkm']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Rate Per Km'),'error');
	}
	if (($_POST['rateperkm']!='') && (!is_numeric($_POST['rateperkm'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter Numeric Value For Rate Per Km'),'error');
	}
	if($_POST['amount']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Amount'),'error');
	}
	if (($_POST['amount']!='') && (!is_numeric($_POST['amount'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter Numeric Value For Amount'),'error');
	}
	
  
	if($_POST['daily_allowance']=='')
   {
     $InputError = 2;
     prnMsg(_('Enter Daily Allowance'),'error');
	}
	if (($_POST['daily_allowance']!='') && (!is_numeric($_POST['daily_allowance'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter Numeric Value For Daily Allowance'),'error');
	}
 if($InputError != 2)
 {
  $_SESSION['exp']++;
  
  $_SESSION['dep_station'][]=$_POST['dep_station'];
  $_SESSION['dep_time'][]=$_POST['dep_time'];
  $_SESSION['dep_date'][]=strtotime($dipdate);
  $_SESSION['arr_station'][]=$_POST['arr_station'];
  $_SESSION['arr_time'][]=$_POST['arr_time'];
  $_SESSION['arr_date'][]=strtotime($arrdate);
  $_SESSION['no_of_km'][]=$_POST['no_of_km'];
  $_SESSION['mode_journey'][]=$_POST['mode_journey'];
  $_SESSION['class_journey'][]=$_POST['class_journey'];
  $_SESSION['rateperkm'][]=$_POST['rateperkm'];
  $_SESSION['amount'][]=$_POST['amount'];
  $_SESSION['daily_allowance'][]=$_POST['daily_allowance'];
  
  unset($_POST['dep_station']);
  unset($_POST['dep_time']);
  unset($_POST['JournalProcessDate']);
  unset($_POST['arr_station']);
  unset($_POST['arr_time']);
  unset($_POST['JournalProcessDate1']);
  unset($_POST['no_of_km']);
  unset($_POST['mode_journey']);
  unset($_POST['class_journey']);
  unset($_POST['rateperkm']);
  unset($_POST['amount']);
  unset($_POST['daily_allowance']);
}
}

for($i=0;$i<=2;$i++)
{
 //echo $_SESSION['amount'][$i]."==".$_SESSION['no_of_km'][$i]."<br/>";
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

if(isset($_REQUEST['clid']))
{
$eu="select * from tour_claim where id='".$_REQUEST['clid']."'";
$euq=DB_query($eu,$db);
$eur=DB_fetch_array($euq);
$_REQUEST['uid']=$eur['emp_id'];
$_POST['claim_type']=$eur['claim_type'];
$_POST['halting_period']=$eur['halting_period'];
if(!(isset($_POST['save'])))
{
$_POST['purpose_journey']=$eur['purpose_journey'];
$_POST['total_amount']=$eur['total_amount'];

}
if(!isset($_SESSION['fetch']))
{
 $pt="select * from tour_claim_detail where claim_id='".$_REQUEST['clid']."'";
$ptq=DB_query($pt,$db);
while($ptr=DB_fetch_array($ptq))
{ $_SESSION['exp']++;
 $_SESSION['dep_station'][]=$ptr['dep_station'];
  $_SESSION['dep_time'][]=$ptr['dep_time'];
  $_SESSION['dep_date'][]=$ptr['dep_date'];
  $_SESSION['arr_station'][]=$ptr['arr_station'];
  $_SESSION['arr_time'][]=$ptr['arr_time'];
  $_SESSION['arr_date'][]=$ptr['arr_date'];
  $_SESSION['no_of_km'][]=$ptr['no_of_km'];
  $_SESSION['mode_journey'][]=$ptr['mode_journey'];
  $_SESSION['class_journey'][]=$ptr['class_journey'];
  $_SESSION['rateperkm'][]=$ptr['rateperkm'];
  $_SESSION['amount'][]=$ptr['amount'];
  $_SESSION['daily_allowance'][]=$ptr['daily_allowance'];
}
$_SESSION['fetch']=1;
}
}
?>
<script>
function showdiv()
{
 document.getElementById('detail').style.display='block';
}
function getuser(a)
{
  window.location.href='tour_claim.php?uid='+a;
}
function finalamount(a,b,c)
{ 
  document.getElementById('amount').value=document.getElementById('rateperkm').value*document.getElementById('no_of_km').value;
}
</script>
<link href="images/style.css" rel="stylesheet" type="text/css" />
<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="553" border="0" cellpadding="2" cellspacing="1">
   <tr class="evenrow"> <td width="50%">Employee Id:<span style="color:#FF0000">*</span> </td> 
      <td><input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']?>" /><select name="emp_id" id="emp_id" onchange="return getuser(this.value)">
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
      <td>Basic Pay:<span style="color:#FF0000">*</span> </td>
      <td><input type="text" name="basic_pay"  size="45" id="basic_pay" maxlength="45" value="<?php echo $empr['basic_pay']?>" readonly="readonly"/></td></tr>
	<tr class="evenrow">
	  <td >Claim Type:<span style="color:#FF0000">*</span> </td>
    <td ><select name="claim_type" id="claim_type">
    <option value="">--Select--</option>
    <?php
$ca="Select * from claim_type where category='T.A. Claim'";
$caq=DB_query($ca,$db);
while($car=DB_fetch_array($caq))
{ 
  if($_POST['claim_type']==$car['id'])
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
      </table>
       <br/><br/>
    <table width="553" border="0" cellpadding="2" cellspacing="1">

    <tr class="evenrow"> <td >Departure Date </td><td>Arrival Date</td><td>Departure Station</td><td>Arrival Station</td><td>No of Km</td><td>TA</td><td>DA</td></tr> 
    <?php
	 $nu=$_SESSION['exp'];
	 for($j=0;$j<=($nu-1);$j++)
             { if($_SESSION['amount'][$j]!='')
			 {
	?>
   <tr class="oddrow"> <td ><?php echo date('d-m-Y',$_SESSION['dep_date'][$j]);?> </td><td><?php echo date('d-m-Y',$_SESSION['arr_date'][$j]);?></td><td><?php echo $_SESSION['dep_station'][$j]?></td><td><?php echo ($_SESSION['arr_station'][$j]);?></td><td><?php echo $_SESSION['no_of_km'][$j];?></td><td><?php echo round($_SESSION['amount'][$j]);?></td><td><?php echo round($_SESSION['daily_allowance'][$j]);?></td></tr> 
      <?php
	  }
	  } ?>
       <tr class="evenrow">
	  
	  <td colspan="7" align="right"><a href="javascript:void(0)" onClick="showdiv();">Add Details</a></td>
	</tr>
  </table>
    <br/><br/>
      
  <div id="detail"  <?php if($InputError!=2) { ?>style="display:none" <?php } ?>>
    <table width="762">
	<tr class="oddrow">
    <td colspan="2" align="center"><span style="font-weight: bold">Departure</span></td> 
    <td colspan="2" align="center"><span style="font-weight: bold">Arrival</span></td>
	</tr>
	<tr class="evenrow">
	  <td width="141">Station:<span style="color:#FF0000">*</span></td> 
	  <td width="213"><input type="text" name="dep_station"  size="40" value="<?php echo $_POST['dep_station']?>" onkeypress="return alphanumeric(event)"></td>
	  <td width="118">Station:<span style="color:#FF0000">*</span></td>
	  <td width="270"><input type="text" name="arr_station"  size="40" value="<?php echo $_POST['arr_station']?>" onkeypress="return alphanumeric(event)"></td>
	</tr>
	<tr class="oddrow">
	  <td>Time:<span style="color:#FF0000">*</span> </td>
	  <td><input type="text" name="dep_time"  size="30" value="<?php echo $_POST['dep_time']?>" onkeypress="return fononly(event)">(HH:MM)</td>
	  <td>Time:<span style="color:#FF0000">*</span> </td>
      <td><input type="text" name="arr_time"  size="30" value="<?php echo $_POST['arr_time']?>" onkeypress="return fononly(event)">(HH:MM)</td>
	</tr>
	<tr class="oddrow">
	  <td>Date:<span style="color:#FF0000">*</span> </td>
	  <td><!--<div id="li_1" >
		
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" style="width:25px;" align="middle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_1'];?>"> 
			<label for="element_1_1">MM</label>
		</span>
		<span>
			<input id="element_1_2" name="element_1_2" class="element text" style="width:25px;" align="middle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_2'];?>"> /
			<label for="element_1_2">DD</label>
		</span>
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" style="width:40px;" align="middle" size="4" maxlength="4"  type="text" value="<?php echo $_POST['element_1_2'];?>">/
			<label for="element_1_3">YYYY</label>
		</span>
	
		<span id="calendar_1">
			<img id="cal_img_1" class="datepicker" src="calendar.gif" alt="Pick a date.">		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_1_3",
			baseField    : "element_1",
			displayArea  : "calendar_1",
			button		 : "cal_img_1",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		</div>--><div  id="date"><input type="text" id="JournalProcessDate" name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate'];?>" readonly="readonly"></div></td>
	  <td>Date:<span style="color:#FF0000">*</span> </td>
	  <td><!--<div id="li_2" >
	
		<span>
			<input id="element_2_1" name="element_2_1" class="element text" style="width:25px;" align="middle"  size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_2_1'];?>"> 
			<label for="element_2_1">MM</label>
		</span>
		<span>
			<input id="element_2_2" name="element_2_2" class="element text" style="width:25px;" align="middle"  size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_2_2'];?>"> /
			<label for="element_2_2">DD</label>
		</span>
		<span>
	 		<input id="element_2_3" name="element_2_3" class="element text" style="width:40px;" align="middle"  size="4" maxlength="4"  type="text" value="<?php echo $_POST['element_2_3'];?>">/
			<label for="element_2_3">YYYY</label>
		</span>
	
		<span id="calendar_2">
			<img id="cal_img_2" class="datepicker" src="calendar.gif" alt="Pick a date.">		</span>
		<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_2_3",
			baseField    : "element_2",
			displayArea  : "calendar_2",
			button		 : "cal_img_2",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
		</div>--><div  id="date"><input type="text" id="JournalProcessDate1" name="JournalProcessDate1" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_POST['JournalProcessDate1'];?>" readonly="readonly"></div></td>
	</tr>
	<tr class="oddrow">
	  <td colspan="2">No of Km:<span style="color:#FF0000">*</span> </td>
	  <td colspan="2"><input type="text" name="no_of_km" size="45" id="no_of_km" value="<?php echo $_POST['no_of_km']?>" class="number"></td>
	  </tr>
	<tr class="oddrow">
	  <td colspan="2">Mode of Journey:<span style="color:#FF0000">*</span> </td>
	  <td colspan="2">
      <select name="mode_journey">
      <option value="train" <?php if($_POST['mode_journey']=='train') {?> selected="selected" <?php } ?>>Train</option>
      <option value="bus" <?php if($_POST['mode_journey']=='bus') {?> selected="selected" <?php } ?>>Bus</option>
      <option value="car" <?php if($_POST['mode_journey']=='car') {?> selected="selected" <?php } ?>>Car</option>
      <option value="plane" <?php if($_POST['mode_journey']=='plane') {?> selected="selected" <?php } ?>>Plane</option>
       <option value="bike" <?php if($_POST['mode_journey']=='bike') {?> selected="selected" <?php } ?>>Bike</option>
      </select></td>
	  </tr>
	<tr class="oddrow">
	  <td colspan="2">Class of Journey:<span style="color:#FF0000">*</span> </td>
	  <td colspan="2"><select name="class_journey">
      <option value="general" <?php if($_POST['mode_journey']=='general') {?> selected="selected" <?php } ?>>General</option>
      <option value="sleeper" <?php if($_POST['mode_journey']=='sleeper') {?> selected="selected" <?php } ?>>Sleeper</option>
      <option value="ac1t" <?php if($_POST['mode_journey']=='ac1t') {?> selected="selected" <?php } ?>>AC 1 Tier</option>
      <option value="ac2t" <?php if($_POST['mode_journey']=='ac2t') {?> selected="selected" <?php } ?>>AC 2 Tier</option>
       <option value="ac3t" <?php if($_POST['mode_journey']=='ac3t') {?> selected="selected" <?php } ?>>AC 3 Tier</option>
       <option value="business" <?php if($_POST['mode_journey']=='business') {?> selected="selected" <?php } ?>>Business</option>
       <option value="economy" <?php if($_POST['mode_journey']=='economy') {?> selected="selected" <?php } ?>>Economy</option>
      </select></td>
	  </tr>
	<tr class="oddrow">
	  <td colspan="2">Rate Per Km:<span style="color:#FF0000">*</span> </td>
	  <td colspan="2"><input type="text" name="rateperkm" class="number" size="45" id="rateperkm" onblur="return finalamount(this.value,'amount','no_of_km');" value="<?php echo $_POST['rateperkm']?>"></td>
	  </tr>
	<tr class="oddrow">
	  <td colspan="2">Amount:<span style="color:#FF0000">*</span> </td>
	  <td colspan="2"><input type="text" name="amount" size="45" class="number" id="amount" value="<?php echo $_POST['amount']?>"></td>
	  </tr>
	<tr class="oddrow">
	  <td colspan="2">Daily Allowance:<span style="color:#FF0000">*</span> </td>
	  <td colspan="2"><input type="text"  name="daily_allowance" class="number"  size="45"  maxlength="45" value="<?php echo $_POST['daily_allowance']?>"/></td>
    </tr>
    <tr class="evenrow">
	  <td colspan="2">&nbsp;</td>
	  <td colspan="2"><input type="submit" name="save" value="Save" /></td>
    </tr>
   
    </table>
  </div>
    <table width="762">
    
	<tr class="evenrow">
	  <td width="50%">Halting Period:<span style="color:#FF0000">*</span> </td>
	  <td><input type="text"  name="halting_period"  size="45" id="halting_period" maxlength="45" value="<?php echo $_POST['halting_period']?>" onkeypress="return alphanumeric(event)" /></td>
    </tr>
	<tr class="oddrow">
	  <td>Purpose of Journey:<span style="color:#FF0000">*</span> </td>
	  <td><input type="text"  name="purpose_journey"  size="45" id="purpose_journey" maxlength="45" value="<?php echo $_POST['purpose_journey']?>" onkeypress="return alphanumeric(event)"/></td>
    </tr>
   <tr class="evenrow">
	  <td>Total Amount(TA+DA):<span style="color:#FF0000">*</span> </td>
	  <td><input type="text"  name="tot_amount"  size="45" id="tot_amount" maxlength="10" value="<?php if(isset($_SESSION['amount'])) { echo (array_sum($_SESSION['amount']))+(array_sum($_SESSION['daily_allowance'])) ;} else { echo $_POST['total_amount']; }?>" readonly="readonly"/></td>
    </tr>
    <tr class="oddrow">
	  <td>Bill Against Claim:<span style="color:#FF0000">*</span> </td> <td><input type="file" name="file" id="file"/><input type="hidden" name="uploadedfile" value="<?php echo $eur['bill_claim'];?>" /> &nbsp;&nbsp;Allowed extensions: pdf doc docx txt xls xlsx pptx ppt&nbsp;&nbsp;<a href="bills/<?php echo $eur['bill_claim'];?>" target="_blank"> View Bill</a></td>
      </tr>
      
    <tr class="evenrow"><td>&nbsp;</td><td><input  type="submit" name="save" value="Submit" /> <input  type="reset" name="reset" value="Reset" /></td>
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
	
    if($_POST['claim_type']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('claim_type').className='ercol';</script>";
	}
	
	
	if($_POST['halting_period']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('halting_period').className='ercol';</script>";
	}
	
	if($_POST['purpose_journey']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('purpose_journey').className='ercol';</script>";
	}
	 if (($_POST['purpose_journey']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['purpose_journey'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('purpose_journey').className='ercol';</script>";
	}
	if($_POST['total_amount']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('total_amount').className='ercol';</script>";
	}
	if (($_POST['total_amount']!='') && (!is_numeric($_POST['total_amount'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('total_amount').className='ercol';</script>";
	}
	if($_FILES["file"]["name"]=='')
   {
     echo "<script type='text/javascript'>document.getElementById('file').className='ercol';</script>";
	}
	}
?>