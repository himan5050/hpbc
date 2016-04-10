 <?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
include('mailfile.php');
/*echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="tour_claim_user.php">List of My Claim</a> &raquo; <a href="'. $_SERVER['PHP_SELF'].'">Tour Claim</a></div>';
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
	if($_POST['total_amount']=='')
   {
     $InputError = 1;
     prnMsg(_('Add Details'),'error');
	}
	if (($_POST['total_amount']!='') && (!is_numeric($_POST['total_amount'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Total Amount'),'error');
	}
	if($_FILES["file"]["name"]=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Bill Against Claim'),'error');
	}
	if($_POST['declaration']!=1)
   {
     $InputError = 1;
     prnMsg(_('Accept Declaration'),'error');
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
	  
    @move_uploaded_file($_FILES["file"]["tmp_name"],"bills/".$rannum.$_FILES["file"]["name"]);
	@chmod("bills/".$rannum.$_FILES["file"]["name"]);
	$corp=0;
	 $role = getRole($_SESSION['uid'],$db);
	if( $role != 13 && $role != 5 && $role != 19 )
	{
		$corp=getCorporationBranch($_SESSION['uid'],$db);
	}
	//$corp=getCorporationBranch($_SESSION['uid'],$db);
	   
	   $ado="insert into tbl_workflow_docket set workflow_id='9',time='".strtotime(date('d-m-Y'))."',status='pending',corp_branch=".$corp."";
	 $adoq=DB_query($ado,$db);
	 
	 $mwf="select max(doc_id) as doc_id from tbl_workflow_docket";
	 $mwfq=DB_query($mwf,$db);
	 $mwfr=DB_fetch_array($mwfq);
	 $doci=$mwfr['doc_id'];
	 
	 /*$awt="insert into tbl_workflow_task set level='1',status='0',doc_id='".$doci."',uid='".$_SESSION['uid']."'";
	 $awtq=DB_query($awt,$db);*/
	 
	 $selu="select program_uid from tbl_joinings where employee_id='".$_POST['emp_id']."'";
		$seluq=DB_query($selu,$db);
		$selur=DB_fetch_array($seluq);
	 
	 $rt="select distinct(corporation_type) as cortype from tbl_corporations,tbl_joinings where tbl_corporations.corporation_id=tbl_joinings.current_officeid and tbl_joinings.program_uid='".$_SESSION['uid']."'";
		$rtq=DB_query($rt,$db);
		$rtr=DB_fetch_array($rtq);
		$offtype=$rtr['cortype'];
		
		$ur="select rid from users_roles where uid='".$_SESSION['uid']."'";
		$urq=DB_query($ur,$db);
		$urr=DB_fetch_array($urq);
		$usrid=$urr['rid'];
	 
	 if($offtype==69 && ($usrid!=5 || $usrid!=19 || $usrid!=6))
		{
		  $lev="3";
		}
		if($offtype==69 && $usrid==5 )
		{
		  $lev="4";
		}
		if($offtype==69 && $usrid==19 )
		{
		  $lev="4";
		}
		if($offtype==70 && $usrid!=13)
		{
		  $lev="1";
		}
		if($offtype==70 && $usrid==13)
		{
		  $lev="3";
		}
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
	 
	 createTask($level,$doci,'','',$_SESSION['uid'],$Is_escalation = '',$writ_level = '',$db);
	 	
	 $mti="select max(task_id) as task_id from tbl_workflow_task";
	 $mtiq=DB_query($mti,$db);
	 $mtir=DB_fetch_array($mtiq);
	 $mtii=$mtir['task_id'];	
		
		
		
		/*if($offtype==69 && ($usrid!=5 || $usrid!=19 || $usrid!=6))
		{
		  $reto="5";
		}
		if($offtype==69 && $usrid==5 )
		{
		  $reto="19";
		}
		if($offtype==69 && $usrid==19 )
		{
		  $reto="19";
		}
		if($offtype==70 && $usrid!=13)
		{
		  $reto="13";
		}
		if($offtype==70 && $usrid==13)
		{
		  $reto="5";
		}*/
		
		$empof="select current_officeid from tbl_joinings where employee_id='".$_POST['emp_id']."'";
		$empofq=DB_query($empof,$db);
		$empofr=DB_fetch_array($empofq);
		$s="insert into tour_claim set 
									  emp_id='".$_POST['emp_id']."',
									   office='".$empofr['current_officeid']."',
									  purpose_journey='".$_POST['purpose_journey']."',
									  claim_type='".$_POST['claim_type']."',
									  basic_pay='".$_POST['basic_pay']."',
									  halting_period='".$_POST['halting_period']."',
									  total_amount='".$_POST['total_amount']."',
									  bill_claim='".$rannum.$_FILES["file"]["name"]."',
									  date='".strtotime(date('d-m-Y'))."',
									  enteredby='".$_SESSION['uid']."',
									  status='0',
									  voucher_generated='0',
									  doc_id='".$doci."',
									  task_id='".$mtii."',
									  reportedto='".$reto."'";
									  
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
				$clty="Tour";
				$clamo=$_POST['total_amount'];
				$parameter = json_encode(array(0=>"$name",1=>"$clty",2=>"$clamo"));
				 createMail('tourclaim',$to,'',$parameter,$db);
				}
				
			
				
		if($q)
		{
		unset($_POST);	
		
		
		}	  
	 $ldi="select max(id) id from tour_claim";
	 $ldiq=DB_query($ldi,$db);
	 $ldir=DB_fetch_array($ldiq);
	 $lastid=$ldir['id'];
					  
									  
					if(isset($_SESSION['amount']))
					{				  
			  $element=count($_SESSION['amount']);	
			 }
			 $nu=$_SESSION['exp'];
			for($i=0;$i<=($nu-1);$i++)
             {  
									  
				if($_SESSION['amount'][$i] && $_SESSION['amount'][$i] != '0.00')
				 {				  
						$tc=  "insert into tour_claim_detail set claim_id='".$lastid."',
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
							
					
														 
			 }
			 $cf="insert into claim_flow set claim_id='".$lastid."',
					                                postedby='".$_SESSION['uid']."',
													remarks='Claim Submitted',
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
			 unset($_SESSION['ptype']);
			 unset($_SESSION['hp']);
			 unset($_SESSION['pj']);
			 
			
    }
	if($cfq)
	{
	header("location:tour_claim_user.php?msg=Claim TA-".$lastid." Added Successfully");
	}
}
if(!isset($_SESSION['num']))
{
$_SESSION['num']=0;
}
if(isset($_REQUEST['op']))
{  
  $no=$_REQUEST['no'];
  if($_REQUEST['op']=='delete')
  {
    $_SESSION['dep_station'][$no]='';
	$_SESSION['arr_station'][$no]='';
    $_SESSION['dep_time'][$no]='';
    $_SESSION['arr_time'][$no]='';
	$_SESSION['arr_time'][$no]='';
	$_SESSION['JournalProcessDate'][$no]='';
	$_SESSION['JournalProcessDate1'][$no]='';
	$_SESSION['no_of_km'][$no]='';
	$_SESSION['amount'][$no]='';
	$_SESSION['daily_allowance'][$no]='';
	header("Location:tour_claim.php");exit;
  }
}


if(isset($_POST['save']) && $_POST['save'] == 'Save')
{   
	$InputError = 0;
	$_SESSION['ptype'] = $_POST['claim_type'];
	$_SESSION['hp'] = $_POST['halting_period'];
	$_SESSION['pj'] = $_POST['purpose_journey'];
	$_SESSION['ta'] = $_POST['total_amount'];
 /* $dipdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
      $arrdate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];*/
	  
	  $dipdate=$_POST['JournalProcessDate'];
	  $arrdate=$_POST['JournalProcessDate1'];
	if(!isset($_SESSION['exp']))
		$_SESSION['exp'] = 0;

 if($_POST['JournalProcessDate']=='' )
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
	
	if($_POST['dep_time']!='')
   {  
      $dpt=explode(':',$_POST['dep_time']);
	  if(($dpt[0]>=23 && $dpt[1]>59) || $dpt[0]>=24)
	  {
       $InputError = 2;
       prnMsg(_('Enter Valid Departure Time'),'error');
	  }
	}
	 if (($_POST['dep_time']!='') && (!eregi('^[0-9:0-9 ]+$' , $_POST['dep_time'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid Departure Time  0-9 is Allowed'),'error');
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
	
	if($_POST['arr_time']!='')
   {  
      $art=explode(':',$_POST['arr_time']);
	  if(($art[0]>=23 && $art[1]>59) || $art[0]>=24)
	  {
       $InputError = 2;
       prnMsg(_('Enter Valid Arrival Time'),'error');
	  }
	}
	 if (($_POST['arr_time']!='') && (!eregi('^[0-9:0-9 ]+$' , $_POST['arr_time'])))
	{
	  $InputError = 2;
     prnMsg(_('Enter valid Arrival Time  0-9 is Allowed'),'error');
	}
	if( $_POST['JournalProcessDate1']==''  )
   {
     $InputError = 2;
     prnMsg(_('Enter Arrival Date'),'error');
	}
	 //$dipdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
     // $arrdate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];
	 $dipdate=$_POST['JournalProcessDate'];
	 $arrdate=$_POST['JournalProcessDate1'];
	  if(strtotime($dipdate) > strtotime($arrdate))
	  {
	     $InputError = 2;
         prnMsg(_('Departure Date Can Not Be Greated Than Arrival Date'),'error');
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
     prnMsg(_('Enter Class of Journey'),'error');
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
  unset($_POST['arr_station']);
  unset($_POST['arr_time']);
  unset($_POST['no_of_km']);
  unset($_POST['mode_journey']);
  unset($_POST['class_journey']);
  unset($_POST['rateperkm']);
  unset($_POST['amount']);
  unset($_POST['daily_allowance']);
  unset($_POST['JournalProcessDate']);
  /*unset($_POST['element_1_1']);
  unset($_POST['element_1_3']);*/
  unset($_POST['JournalProcessDate1']);
  /*unset($_POST['element_2_1']);
  unset($_POST['element_2_3']);*/
  header("Location:tour_claim.php");exit;
}
}

for($i=0;$i<=2;$i++)
{
 //echo $_SESSION['amount'][$i]."==".$_SESSION['no_of_km'][$i]."<br/>";
}

if(isset($_REQUEST['uid']) && $_REQUEST['uid']!='')
{ 
  $ei="select * from tbl_joinings where employee_id='".$_REQUEST['uid']."'ORDER BY employee_id ASC";
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
function finalamount1(a,b,c)
{ 
  document.getElementById('net_amount').value=document.getElementById('total_amount').value - document.getElementById(a).value;
}

</script>
<link href="images/style.css" rel="stylesheet" type="text/css" />

<form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="form" enctype="multipart/form-data">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="553" border="0" cellpadding="2" cellspacing="1">
   <tr class="oddrow"><td align="center"><h2>Tour Claim</h2></td></tr>
   <tr class="evenrow"> <td> <div class="left">Employee Id: <span style="color:#FF0000">*</span> </div> 
       <div class="right"><input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']?>" /><select name="emp_id" id="emp_id" onchange="return getuser(this.value)">
      <option value="">--Select--</option>
                       <?php 
					     $emi="select employee_id, employee_name from tbl_joinings  INNER JOIN users on (tbl_joinings.program_uid = users.uid) where users.status=1 ORDER BY employee_name ASC";
					   // $emi="select * from tbl_joinings ORDER BY employee_name ASC";
						$emiq=DB_query($emi,$db);
						while($emir=DB_fetch_array($emiq))
						{ 
						 if($empr['employee_id']==$emir['employee_id'])
						   {
					   ?>
                       <option value="<?php echo $emir['employee_id'];?>" selected="selected"><?php echo ucwords ($emir['employee_name']."(".$emir['employee_id'].")")  ?></option>
                       <?php
					   }
					   else
					   {?>
                       <option value="<?php echo $emir['employee_id'];?>" ><?php echo ucwords ($emir['employee_name']."(".$emir['employee_id'].")") ?></option>
					   <?php
					   }
					   }
					   ?>  
                       </select></div></td></tr>
      <tr class="oddrow">
      <td> <div class="left">Basic Pay: <span style="color:#FF0000">*</span> </div>
       <div class="right"><input type="text" name="basic_pay" id="basic_pay" size="45" maxlength="45" value="<?php echo $empr['basic_pay']?>" readonly="readonly"onkeypress = "return fononlyn(event)" /></div></td></tr>
	<tr class="evenrow"><td>
	  <div class="left">Claim Type:<span style="color:#FF0000"> *</span> </div>
   <div class="right"><select name="claim_type" id="claim_type">
    <option value="">--Select--</option>
    <?php
$ca="Select * from claim_type where category='T.A. Claim'";
$caq=DB_query($ca,$db);
while($car=DB_fetch_array($caq))
{ 
	$ctype = ($_SESSION['ptype'])?$_SESSION['ptype']:$_POST['claim_type'];
	if($ctype==$car['id'])
	{
?>
<option value="<?php echo ucwords($car['id']);?>" selected="selected"><?php echo ucwords($car['claimtypename'])?></option>
<?php
}
else
{
?>
    <option value="<?php echo ucwords($car['id']);?>" ><?php echo ucwords($car['claimtypename'])?></option>
    <?php } }
	?>                 </select></div></td></tr>
      </table>
       <br/><br/>
	     <div style="float:right; font-weight:bold;"><a href="javascript:void(0)" onClick="showdiv();">Add Details</a></div>
    <table width="553" border="0" cellpadding="2" cellspacing="1">

    <tr > <th>Departure Date </th><th>Arrival Date</th><th>Departure Station</th><th>Arrival Station</th><th>No of Km</th><th>TA</th><th>DA</th><th>Action</th></tr> 
    <?php
	 $nu=$_SESSION['exp'];
	 for($j=0;$j<=($nu-1);$j++)
             { if($_SESSION['amount'][$j]!='')
			 {
	?>
   <tr class="oddrow"> <td ><?php echo date('d-m-Y',$_SESSION['dep_date'][$j]);?> </td><td><?php echo date('d-m-Y',$_SESSION['arr_date'][$j]);?></td><td><?php echo $_SESSION['dep_station'][$j]?></td><td><?php echo ($_SESSION['arr_station'][$j]);?></td><td><?php echo round($_SESSION['no_of_km'][$j],2);?></td><td><?php echo round($_SESSION['amount'][$j]);?></td><td><?php echo round($_SESSION['daily_allowance'][$j]);?></td><td><a href="tour_claim.php?op=delete&no=<?php echo $j;?>">Delete</a></td></tr> 
      <?php
	  }
	  } ?>
       <tr class="oddrow">
	  
	
	</tr>
  </table>
    <br/><br/>
      
  <div id="detail"  <?php if($InputError!=2) { ?>style="display:none" <?php } ?>>
    <table width="762">
	<tr >
    <th colspan="2" ><span style="font-weight: bold; margin-left:180px;">Departure</span></th> 
    <th colspan="2" ><span style="font-weight: bold; margin-left:200px;">Arrival</span></th>
	</tr>
	<tr class="oddrow">
	  <td width="141">Station: <span style="color:#FF0000">*</span> </td> 
	  <td width="213"><input type="text" name="dep_station" id="dep_station" size="40" maxlength="45"  value="<?php echo $_POST['dep_station'];?>" onkeypress="return alphanumeric(event)"></td>
	  <td width="118">Station: <span style="color:#FF0000">*</span> </td>
	  <td width="270"><input type="text" name="arr_station" id="arr_station" maxlength="45" size="40" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['arr_station'];?>"></td>
	</tr>
	<tr class="evenrow">
	  <td>Time: <span style="color:#FF0000">*</span> </td>
	  <td><input type="text" name="dep_time"  size="30" maxlength="10" id="dep_time" onkeypress="return fononly(event)" value="<?php echo $_POST['dep_time'];?>" >(HH:MM)</td>
	  <td>Time: <span style="color:#FF0000">*</span> </td>
      <td><input type="text" name="arr_time"  size="30" maxlength="10" id="arr_time" onkeypress="return fononly(event)" value="<?php echo $_POST['arr_time'];?>">(HH:MM)</td>
	</tr>
	<tr class="oddrow">
	  <td>Date: <span style="color:#FF0000">*</span> </td>
	  <td><!--<div id="li_1" >
	<span>
			<input id="element_1_2" name="element_1_2" class="element text" style="width:25px;" align="middle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_2'];?>" readonly="readonly"> 
			<label for="element_1_2">DD</label>
		</span>	
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" style="width:25px;" align="middle" size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_1_1'];?>" readonly="readonly"> /
			<label for="element_1_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" style="width:40px;" align="middle" size="4" maxlength="4"  type="text" value="<?php echo $_POST['element_1_2'];?>" readonly="readonly">/
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
	  <td>Date: <span style="color:#FF0000">*</span> </td>
	  <td><!--<div id="li_2" >
	<span>
			<input id="element_2_2" name="element_2_2" class="element text" style="width:25px;" align="middle"  size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_2_2'];?>" readonly="readonly"> 
			<label for="element_2_2">DD</label>
		</span>
		<span>
			<input id="element_2_1" name="element_2_1" class="element text" style="width:25px;" align="middle"  size="2" maxlength="2"  type="text" value="<?php echo $_POST['element_2_1'];?>" readonly="readonly"> /
			<label for="element_2_1">MM</label>
		</span>
		
		<span>
	 		<input id="element_2_3" name="element_2_3" class="element text" style="width:40px;" align="middle"  size="4" maxlength="4"  type="text" value="<?php echo $_POST['element_2_3'];?>" readonly="readonly">/
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
		</div>--><div  id="date"><input type="text" id="JournalProcessDate1" name="JournalProcessDate1" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat1'];?>')" value="<?php echo $_POST['JournalProcessDate1'];?>" readonly="readonly"></div></td>
	</tr>
</table><br/><table>
	<tr class="evenrow">
	  <td> <div class="left">No of Km: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text" name="no_of_km" size="45" id="no_of_km" maxlength="15" value="<?php echo $_POST['no_of_km'];?>" class="number" ></div></td>
	  </tr>
	<tr class="oddrow">
	  <td><div class="left">Mode of Journey: <span style="color:#FF0000">*</span> </div>
	<div class="right"><select name="mode_journey" id="mode_journey">
      <option value="">--Select--</option>
     <option value="bike" <?php if($_POST['mode_journey']=='bike') {?> selected="selected" <?php } ?>>Bike</option>
      <option value="bus" <?php if($_POST['mode_journey']=='bus') {?> selected="selected" <?php } ?>>Bus</option>
      <option value="car" <?php if($_POST['mode_journey']=='car') {?> selected="selected" <?php } ?>>Car</option>
      <option value="plane" <?php if($_POST['mode_journey']=='plane') {?> selected="selected" <?php } ?>>Plane</option>
       <option value="train" <?php if($_POST['mode_journey']=='train') {?> selected="selected" <?php } ?>>Train</option>
      </select></div></td>
	  </tr>
	<tr class="evenrow">
	  <td ><div class="left">Class of Journey: <span style="color:#FF0000">*</span> </div>
	<div class="right"><select name="class_journey" id="class_journey">
      <option value="">--Select--</option>
     
      
      <option value="ac1t" <?php if($_POST['class_journey']=='ac1t') {?> selected="selected" <?php } ?>>AC 1 Tier</option>
      <option value="ac2t" <?php if($_POST['class_journey']=='ac2t') {?> selected="selected" <?php } ?>>AC 2 Tier</option>
       <option value="ac3t" <?php if($_POST['class_journey']=='ac3t') {?> selected="selected" <?php } ?>>AC 3 Tier</option>
       <option value="business" <?php if($_POST['class_journey']=='business') {?> selected="selected" <?php } ?>>Business</option>
       <option value="economy" <?php if($_POST['class_journey']=='economy') {?> selected="selected" <?php } ?>>Economy</option>
       <option value="general" <?php if($_POST['class_journey']=='general') {?> selected="selected" <?php } ?>>General</option>
	  <option value="sleeper" <?php if($_POST['class_journey']=='sleeper') {?> selected="selected" <?php } ?>>Sleeper</option>
	  </select></div></td>
	  </tr>
	<tr class="oddrow">
	  <td><div class="left">Rate Per Km: <span style="color:#FF0000">*</span> </div>
	 <div class="right"><input type="text" name="rateperkm" size="45" id="rateperkm" onblur="return finalamount(this.value,'amount','no_of_km');" value="<?php echo $_POST['rateperkm'];?>" class="number" maxlength="11"></div></td>
	  </tr>
	<tr class="evenrow">
	  <td><div class="left">Amount: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text" name="amount" size="45" maxlength="11" id="amount" value="<?php echo $_POST['amount'];?>" onkeypress = "return fononlyn(event)" readonly="readonly"></div></td>
	  </tr>
	<tr class="oddrow">
	  <td> <div class="left">Daily Allowance: <span style="color:#FF0000">*</span> </div>
	 <div class="right"><input type="text"  name="daily_allowance" maxlength="11" size="45" id="daily_allowance" value="<?php echo $_POST['daily_allowance'];?>" class="number"/></div></td>
    </tr>
    <tr class="evenrow">
	
	  <td align="center"><input type="submit" name="save" value="Save" /></td>
    </tr>
   
    </table>
  </div>
  <br/>
    <table>
	<tr class="oddrow">
	  <td><div class="left">Halting Period: <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text"  name="halting_period" id="halting_period" size="45"  maxlength="10" value="<?php echo ($_SESSION['hp'])?$_SESSION['hp']:$_POST['halting_period']?>"onkeypress="return alphanumeric(event)" /></div></td>
    </tr>
	<tr class="evenrow">
	  <td><div class="left">Purpose of Journey: <span style="color:#FF0000">*</span> </div>
	 <div class="right"><input type="text"  name="purpose_journey" id="purpose_journey" size="45"  maxlength="200" value="<?php echo ($_SESSION['pj'])?$_SESSION['pj']:$_POST['purpose_journey']?>"onkeypress="return alphanumeric(event)"/></div></td>
    </tr>
   <tr class="oddrow">
	  <td><div class="left">Total Amount(TA+DA): <span style="color:#FF0000">*</span> </div>
	  <div class="right"><input type="text"  name="total_amount" id="total_amount" size="45"  maxlength="11" value="<?php if(isset($_SESSION['amount'])) { echo array_sum($_SESSION['amount'])+ array_sum($_SESSION['daily_allowance']); }?>" onkeypress = "return fononlyn(event)" readonly="readonly"/></div></td>
    </tr>
    <tr class="evenrow">
	  <td><div class="left">Less-Advance Drawn: </div>
	 <div class="right"><input type="text"  name="advance"  size="45"  id="advance" maxlength="10" value="<?php echo $_POST['advance'];?>"  class="number" onblur="finalamount1('advance',1,1);"/></div></td>
	</tr>
    <tr class="oddrow">
	  <td><div class="left">Net amount payable: <span style="color:#FF0000">*</span> </div>
	 <div class="right"><input type="text"  name="net_amount"  id="net_amount" size="45"  maxlength="10" value="<?php echo $_POST['net_amount']?>" onkeypress = "return fononlyn(event)" readonly="readonly"/></div></td>
    </tr>
    <tr class="evenrow">
	  <td><div class="left">Bill Against Claim: <span style="color:#FF0000">*</span> </div> <div class="right"><input type="file" name="file" id="file"/></div>&nbsp;(Upload .doc .xls .xlsx .jpg .jpeg .pdf .gif .jpeg .zip .png .txt .rar Only)</td>
      </tr>
      <tr class="oddrow">
	  <td><input type="checkbox" value="1"  name="declaration"/>  I hereby declare that the statements in this application are true to the best of my knowledge and belief and that the person for whom medical expenses were incurred is wholly dependent on  me.</td>
	  
	  </tr>
    <tr class="evenrow"><td align="center" class="back"><input  type="submit" name="save" value="Submit" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>

</form>
<br />
<?php
include('includes/footer.inc');
?>
<?php
if (isset($_POST['save']) && $_POST['save'] == 'Submit' ){
  
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
	
	if(isset($_POST['save']) && $_POST['save'] == 'Save')
{  
$InputError = 0;
 /* $dipdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
      $arrdate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];*/
	  
	  $dipdate=$_POST['JournalProcessDate'];
	  $arrdate=$_POST['JournalProcessDate1'];

 if($_POST['JournalProcessDate']=='' )
   {
   echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate').className='date';</script>";
	}
 
  if($_POST['dep_station']=='')
   {  echo '<style>.station {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementById('dep_station').className='station';</script>";
	}
	
	if($_POST['dep_time']=='')
   {   echo '<style>.station {border:1px solid red !important}</style>';
    echo "<script type='text/javascript'>document.getElementById('dep_time').className='station';</script>";
	}
	
	if($_POST['dep_time']!='')
   {  
      $dpt=explode(':',$_POST['dep_time']);
	  if(($dpt[0]>=23 && $dpt[1]>59) || $dpt[0]>=24)
	  { echo '<style>.station {border:1px solid red !important}</style>';
       echo "<script type='text/javascript'>document.getElementById('dep_time').className='station';</script>";
	  }
	}
	 if (($_POST['dep_time']!='') && (!eregi('^[0-9:0-9 ]+$' , $_POST['dep_time'])))
	{ echo '<style>.station {border:1px solid red !important}</style>';
	  echo "<script type='text/javascript'>document.getElementById('dep_time').className='station';</script>";
	}
	 if($_POST['arr_station']=='')
   { echo '<style>.station {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementById('arr_station').className='station';</script>";;
	}
	if($_POST['arr_time']=='')
   { echo '<style>.station {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementById('arr_time').className='station';</script>";
	}
	
	if($_POST['arr_time']!='')
   {  
      $art=explode(':',$_POST['arr_time']);
	  if(($art[0]>=23 && $art[1]>59) || $art[0]>=24)
	  { echo '<style>.station {border:1px solid red !important}</style>';
       echo "<script type='text/javascript'>document.getElementById('arr_time').className='station';</script>";
	  }
	}
	 if (($_POST['arr_time']!='') && (!eregi('^[0-9:0-9 ]+$' , $_POST['arr_time'])))
	{ echo '<style>.station {border:1px solid red !important}</style>';
	  echo "<script type='text/javascript'>document.getElementById('arr_time').className='station';</script>";
	}
	if( $_POST['JournalProcessDate1']==''  )
   {
    echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate1').className='date';</script>";
	}
	 //$dipdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
     // $arrdate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];
	 $dipdate=$_POST['JournalProcessDate'];
	 $arrdate=$_POST['JournalProcessDate1'];
	  if(strtotime($dipdate) > strtotime($arrdate))
	  {
	    echo '<style>.date {border:1px solid red !important}</style>';
     echo "<script type='text/javascript'>document.getElementsByName('JournalProcessDate').className='date';</script>";
	  }
	if($_POST['no_of_km']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('no_of_km').className='ercol';</script>";
	}
	if (($_POST['no_of_km']!='') && (!is_numeric($_POST['no_of_km'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('no_of_km').className='ercol';</script>";
	}
	
	if($_POST['mode_journey']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('mode_journey').className='ercol';</script>";
	}
   if($_POST['class_journey']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('class_journey').className='ercol';</script>";
	}
	
	if($_POST['rateperkm']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('rateperkm').className='ercol';</script>";
	}
	if (($_POST['rateperkm']!='') && (!is_numeric($_POST['rateperkm'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('rateperkm').className='ercol';</script>";
	}
	if($_POST['amount']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('amount').className='ercol';</script>";
	}
	if (($_POST['amount']!='') && (!is_numeric($_POST['amount'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('amount').className='ercol';</script>";
	}
	
  
	if($_POST['daily_allowance']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('daily_allowance').className='ercol';</script>";
	}
	if (($_POST['daily_allowance']!='') && (!is_numeric($_POST['daily_allowance'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('daily_allowance').className='ercol';</script>";
	}
}
?>