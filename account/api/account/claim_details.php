<?php
include('includes/session.inc');
$title = _('Stock Register');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';

?>
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Detail of claims made by Employee</a></div>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
 <tr>	<td align="left" class="tdform-width"> <fieldset><legend>Detail of claims made by Employee</legend>
 <table align="left" class="frmtbl">
  	<tr> 	<td><div class="divwrapper"><div class="maincol">Employee:</div>
        <div class="maincolcel"><select name="emp_id" >
      <option value="">--Select--</option>
                       <?php 
					    $emi="select * from tbl_joinings";
						$emiq=DB_query($emi,$db);
						while($emir=DB_fetch_array($emiq))
						{ 
						 if($emir['employee_id']==$_POST['emp_id'])
						 {
					   ?>
                       <option value="<?php echo $emir['employee_id'];?>" selected="selected" ><?php echo ucwords(($emir['employee_name']."(".$emir['employee_id'].")"))  ?></option>
                       <?php
					    }
						else
						{
					   ?>  
                       <option value="<?php echo $emir['employee_id'];?>" ><?php echo ucwords($emir['employee_name']."(".$emir['employee_id'].")")  ?></option>
                       <?php
					   }
					   }
					   ?>
                       </select></div></div></div></td>

 <td><div class="divwrapper"><div class="maincol">From Date:</div>
     <div class="maincolcel"><div id="li_1" >
		<span>
			<input id="element_1_2" name="element_1_2" class="element text" style="width:17px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_1_2'];?>" type="text" /> /
			<label for="element_1_2"></label>
		</span>
		<span>
			<input id="element_1_1" name="element_1_1" class="element text" style="width:17px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_1_1'];?>" type="text" /> /
			<label for="element_1_1"></label>
		</span>
		
		<span>
	 		<input id="element_1_3" name="element_1_3" class="element text" style="width:30px;" align="middle" size="4" maxlength="4" value="<?php echo $_POST['element_1_3'];?>" type="text" /> /
			<label for="element_1_3"></label>
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
		</div></div></div></td>
 <td rowspan="2"><div class="generatebtn"><input  type="submit" name="submit" value="Generate" /></div></td>
  </tr>
 
   <tr>
    <td><div class="divwrapper"><div class="maincol">Claim Type :</div>
   <div class="maincolcel"><select name="type">
     <option value="">--Select--</option>
        <option value="m" <?php if($_POST['type']=='m') { ?> selected="selected" <?php } ?>>Medical</option>
         <option value="t"  <?php if($_POST['type']=='t') { ?> selected="selected" <?php } ?>>Tour</option>
        </select></div></td>
    <td><div class="divwrapper"><div class="maincol">To Date:</div>
    <div class="maincolcel"><div id="li_2" >
	   <span>
			<input id="element_2_2" name="element_2_2" class="element text" style="width:17px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_2_2'];?>" type="text" /> /
			<label for="element_2_2"></label>
		</span>
		<span>
			<input id="element_2_1" name="element_2_1" class="element text" style="width:17px;" align="middle" size="2" maxlength="2" value="<?php echo $_POST['element_2_1'];?>" type="text" /> /
			<label for="element_2_1"></label>
		</span>
		
		<span>
	 		<input id="element_2_3" name="element_2_3" class="element text" style="width:30px;" align="middle" size="4" maxlength="4" value="<?php echo $_POST['element_2_3'];?>" type="text" /> /
			<label for="element_2_3"></label>
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
		</script></div></div></div></td>
		</tr>
		</table></fieldset></td></tr>
		</table></form>
<?php 
if(substr(date('d'),0,1)=='0')
			 {
			  $d= substr(date('d'),1);
			  $m=date('m');
			  $y= date('Y');
			 }
			 else
			 {
			  $d= date('d');
			  $m=date('m');
			  $y= date('Y');
			 }
			 $curdat=strtotime($d."-".$m."-".$y);
			 
if (isset($_POST['submit']) ){
  $InputError = 0;
  $qua=0;
 $sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];


if($_POST['element_1_2']=='' || $_POST['element_1_1']=='' || $_POST['element_1_3']=='')
   {
     $InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($_POST['element_2_2']=='' || $_POST['element_2_1']=='' || $_POST['element_2_3']=='' )
   {
     $InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	
if($sdate!='--' && $edate!='--' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
     if($curdat<strtotime($edate))
 {
 $InputError = 1;
     prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }
 if($InputError!=1)
	{ $cond='';
	  if(isset($_POST['emp_id']) && $_POST['emp_id']!='')
	  {
	    $cond.='and at.emp_id="'.$_POST['emp_id'].'"';
	  }
	 
	  
    $s=" select at.emp_id,at.date,at.net_amount,at.status,tj.employee_name from medical_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.emp_id=tj.employee_id";
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	
	 $sa="select at.emp_id,at.date,at.total_amount,at.status,tj.employee_name from tour_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.emp_id=tj.employee_id";
	$qa=DB_query($sa,$db);
	$na=DB_num_rows($qa);
	
	if($n || $na)
	{
	
  $rdata="<br />
          <table  cellpadding='2' cellspacing='1'>
		  <tr class=oddrow><td colspan=7><h2>Details of Claim made by Employee</h2></td></tr>
		  <tr><td colspan=7 align=right><a href='/".$u[1]."/generateaccountpdf.php?op=claim&sdate=".strtotime($sdate)."&edate=".strtotime($edate)."&type=".$_POST['type']."&emp_id=".$_POST['emp_id']."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
<tr>
<th >S. No.</th>
<th >Employee Id</th>
<th >Employee Name</th>
<th >Claim Type</th>
<th >Claim Date</th>
<th >Amount</th>
<th >Status</th>

</tr>
";
	 if($_POST['type']=='m' || $_POST['type']=='' )
	  {
	
	if($n)
	{
	  while($r=DB_fetch_array($q))
	  { $qua++; 
	  if($qua%2==0)
	  {
	    $cl="even";
	  }
	  else
	  {
	    $cl="odd";
	  }  
	                     if($r['voucher_generated']==1)
						  {
						   $st= "Complete";
						  }
						 else if($r['status']==1)
		                  {
						   $st= "Approved";
						  }
						  else if($r['status']==2)
						  {
						    $st= "Rejected";
						  }
						  else if($r['status']==3)
						  {
						    $st= "Queried";
						  }
						  else if($r['status']==0)
						  {
						   $st= "Pending";
						  }
						  
	    $rdata.="<tr class='".$cl."'><td>".$qua."</td><td>".$r['emp_id']."</td><td>".ucwords($r['employee_name'])."</td><td>Medical</td><td align='center'>".date('d-m-Y',$r['date'])."</td><td align='right'>".$r['net_amount']."</td><td>". $st."</td></tr>";
		
	  }
	}
	}
	
	
	 if($_POST['type']=='t' || $_POST['type']=='' )
	  {
	if($na)
	{
	  while($ra=DB_fetch_array($qa))
	  {  
	    
	      $qua++;
		  if($qua%2==0)
	  {
	    $cl="even";
	  }
	  else
	  {
	    $cl="odd";
	  }
	     if($r['status']==1)
		                  {
						   $st= "Approved";
						  }
						  else if($r['status']==2)
						  {
						    $st= "Rejected";
						  }
						  else if($r['status']==3)
						  {
						    $st= "Queried";
						  }
						  else if($r['status']==0)
						  {
						   $st= "Pending";
						  }
	    $rdata.="<tr class='".$cl."'><td>".$qua."</td><td>".$ra['emp_id']."</td><td>".ucwords($ra['employee_name'])."</td><td>Tour</td><td align='center'>".date('d-m-Y',$ra['date'])."</td><td align='right'>".$ra['total_amount']."</td><td>".$st."</td></tr>";
	  }
	}
	  }
	  $rdata.="</table>";
	echo $rdata;
	}
	else
	{
	   echo "No Result Found";
	}
}
}
include('includes/footer.inc');
?>