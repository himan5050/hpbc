<?php
include('includes/session.inc');
$title = _('Stock Register');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
/*echo '<script type="text/javascript" src="calender/calendar.js"></script>
<link rel="stylesheet" type="text/css" href="calender/calender.css" media="all">';*/
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Detail of claims made by Employee</a></div>';
if (isset($_REQUEST['submit']) ){
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
  $InputError = 0;
  $qua=0;
 /*$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];*/
$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];

if($_REQUEST['JournalProcessDate']=='' )
   {
     //$InputError = 1;
     prnMsg(_('Select From Date'),'error');
	}
	if($_REQUEST['JournalProcessDate1']=='' )
   {
     //$InputError = 1;
     prnMsg(_('Select To Date'),'error');
	}
	
if($sdate!='' && $edate!='' && (strtotime($sdate))>(strtotime($edate)) )
   {
   //  $InputError = 1;
     prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
     if($curdat<strtotime($edate))
 {
 //$InputError = 1;
     prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }
} 
?>


<form action="claim_details.php" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
 <tr>	<td align="left" class="tdform-width"> <fieldset><legend>Detail of claims made by Employee</legend>
 <table align="left" class="frmtbl">
  	<tr> 	<td><div class="divwrapper"><div class="maincol"><strong>Employee:</strong></div>
        <div class="maincolcel"><select name="emp_id" >
      <option value="">--Select--</option>
                       <?php 
					    $emi="select * from tbl_joinings ORDER BY employee_name ASC";
						$emiq=DB_query($emi,$db);
						while($emir=DB_fetch_array($emiq))
						{ 
						 if($emir['employee_id']==$_REQUEST['emp_id'])
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

 <td colspan="2"><div class="divwrapper"><div class="maincol"><strong>From Date: </strong><span style="color:#FF0000">*</span></div>
     <div class="maincol"><!--<div id="li_1" >
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
		</div>--><div  id="date"><input type="text" id="JournalProcessDate" name="JournalProcessDate" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat'];?>')" value="<?php echo $_REQUEST['JournalProcessDate'];?>"></div></div></div></td> 
  </tr>
 
   <tr>
    <td><div class="divwrapper"><div class="maincol"><strong>Claim Type:</strong> <span style="color:#FF0000">*</span></div>
   <div class="maincolcel"><select name="type">
        <option value="m" <?php if($_REQUEST['type']=='m') { ?> selected="selected" <?php } ?>>Medical</option>
         <option value="t"  <?php if($_REQUEST['type']=='t') { ?> selected="selected" <?php } ?>>Tour</option>
        </select></div></div></td>
    <td><div class="divwrapper"><div class="maincol"><div id="date"><strong>To Date:</strong> <span style="color:#FF0000">*</span></div></div>
    <div class="maincol"><!--<div id="li_2" >
		</script></div>--><div  id="date"><input type="text" id="JournalProcessDate1" name="JournalProcessDate1" class="date" alt="<?php echo $_SESSION['DefaultDateFormat'];?>" maxlength=10 size=11 onChange="isDate(this, this.value, '<?php echo $_SESSION['DefaultDateFormat1'];?>')" value="<?php echo $_REQUEST['JournalProcessDate1'];?>"></div></div></div></td>
        <td><div class="generatebtn"><input  type="submit" name="submit" value="Generate" /></div></td>
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
			 
if (isset($_REQUEST['submit']) ){
  $InputError = 0;
  $qua=0;
 /*$sdate=$_POST['element_1_2']."-".$_POST['element_1_1']."-".$_POST['element_1_3'];
$edate=$_POST['element_2_2']."-".$_POST['element_2_1']."-".$_POST['element_2_3'];*/
$sdate=$_REQUEST['JournalProcessDate'];
$edate=$_REQUEST['JournalProcessDate1'];

if($sdate=='' )
   {
     $InputError = 1;
     //prnMsg(_('Select From Date'),'error');
	}
	if($edate=='' )
   {
     $InputError = 1;
     //prnMsg(_('Select To Date'),'error');
	}
	
if($sdate!='--' && $edate!='--' && (strtotime($sdate))>(strtotime($edate)) )
   {
     $InputError = 1;
     //prnMsg(_('From Date Can Not Be Greater Than To Date'),'error');
	}
     if($curdat<strtotime($edate))
 {
 $InputError = 1;
     //prnMsg(_('End Date Can Not Be A Future Date'),'error');
 
 }
 if($InputError!=1)
	{ 
	 
		$cond='';
		if(isset($_REQUEST['emp_id']) && $_REQUEST['emp_id']!='')
		{
			$cond.='and at.emp_id="'.$_REQUEST['emp_id'].'"';
		}
		$rec_limit = 10;
		if($_REQUEST['type']=='m')
		{
			$count_query=" select count(at.id) from medical_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.emp_id=tj.employee_id";
		}else{
			$count_query=" select count(at.id) from tour_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.emp_id=tj.employee_id";
		}
		//$count_query = "select count(id) from billsubmit where (date>='".strtotime($sdate)."' and date<='".strtotime($edate)."')";
		$retval =DB_query( $count_query, $db );
		$row = DB_fetch_array($retval);
		$rec_count = $row[0];

		$topage=ceil($rec_count/$rec_limit);
		if( isset($_GET{'page'} ) )
		{
		$page = $_GET{'page'} + 1;
		$offset = $rec_limit * $page ;
		}
		else
		{
		$page = 0;
		$offset = 0;
		}
		$rec_count;
		$left_rec = $rec_count - ($page * $rec_limit);



	 
	  
    $s=" select at.emp_id,at.date,at.net_amount,at.status,tj.employee_name from medical_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.emp_id=tj.employee_id  LIMIT $offset, $rec_limit";
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	
	 $sa="select at.emp_id,at.date,at.total_amount,at.status,tj.employee_name from tour_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".strtotime($sdate)."' and at.date<='".strtotime($edate)."') and at.emp_id=tj.employee_id  LIMIT $offset, $rec_limit";
	$qa=DB_query($sa,$db);
	$na=DB_num_rows($qa);
	$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	if($n || $na)
	{
	
  $rdata="<br />
          <table  cellpadding='2' cellspacing='1'>
		  <tr class=oddrow><td colspan=7><h2>Details of Claim made by Employee</h2></td></tr>
		  <tr><td colspan=7 align=right><a href='/".$u[1]."/generateaccountpdf.php?op=claim&sdate=".strtotime($sdate)."&edate=".strtotime($edate)."&type=".$_REQUEST['type']."&emp_id=".$_REQUEST['emp_id']."&branch=".$corpbranch."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
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
	 $i=1;
	
	if(isset($_GET['page']) && $_GET['page']>1)
	{
	$pp=($_GET['page']*10)+11;
	}
	else if(isset($_GET['page']) && $_GET['page']==0)
	{
	$pp=11;
	}
	else if(isset($_GET['page']) && $_GET['page']==1)
	{
	$pp=21;
	}
	else
	{
	$pp=1;
	}
	$nn=1*($pp);
 if($_REQUEST['type']=='m')
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
						  
	    $rdata.="<tr class='".$cl."'><td>".$nn."</td><td>".$r['emp_id']."</td><td>".ucwords($r['employee_name'])."</td><td>Medical</td><td align='center'>".date('d-m-Y',$r['date'])."</td><td align='right'>".round(abs($r['net_amount']))."</td><td>". $st."</td></tr>";
		$i++;
		$nn++;
	  }
	}
	}
	

	


	if($_REQUEST['type']=='t')
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
		if($ra['status']==1)
		{
		$st= "Approved";
		}
		else if($ra['status']==2)
		{
		$st= "Rejected";
		}
		else if($ra['status']==3)
		{
		$st= "Queried";
		}
		else if($ra['status']==0)
		{
		$st= "Pending";
		}
	    $rdata.="<tr class='".$cl."'><td>".$nn."</td><td>".$ra['emp_id']."</td><td>".ucwords($ra['employee_name'])."</td><td>Tour</td><td align='center'>".date('d-m-Y',$ra['date'])."</td><td align='right'>".round(abs($ra['total_amount']))."</td><td>".$st."</td></tr>";
		$i++;
		$nn++;
	  }
	}
  }
	$rdata.="</table><div class='paging'>";
	echo $rdata;


	 if(isset($_GET['page']) && $_GET['page'] >3){
		$nn = $_GET['page']-3;
		for($nn;$nn<=($_GET['page']+3);$nn++){

			if($_GET['page']==($nn-2))
			{
			$pg="<strong>".$nn."</strong>";
			}
			else
			{
			$pg=$nn;
			}
			//$pg = $nn;

			$datap .="<a href=\"$_PHP_SELF?page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";

		}
		if(($_GET['page']+ 2) != $topage){
			$datap .= '..';
		}
 }else{
    if($topage > 7){
	   $tp = 7;
	}else if($topage < 7 && $topage > 1){
	   $tp = $topage;
	}
     for($nn=1;$nn<=$tp;$nn++){
		if(isset($_GET['page']))
		{
			if($_GET['page']==($nn-2))
			{
			$pg="<strong>".$nn."</strong>";
			}
			else
			{
			$pg=$nn;
			}
		}else{
			if($nn==1)
			{
			$pg="<strong>".$nn."</strong>";
			}
			else
			{
			$pg=$nn;
			}
		}	
      $datap .="<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	 } 
 }
	
if($left_rec <= $rec_limit && $page!=0)
{   
   $last = $page-2;
   echo "<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous</a>&nbsp;&nbsp;".$datap;
}

	else if( $page > 0)
{  
   $last = $page - 2;
      echo "<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous </a>&nbsp;  &nbsp;";
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}
 
else if( $page == 0 && $left_rec > $rec_limit)
{   
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?submit=submit&JournalProcessDate=".$sdate."&JournalProcessDate1=".$edate."&emp_id=".$_REQUEST['emp_id']."&type=".$_REQUEST['type']."&page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}
	echo '</div>';

	
	
	
	
	
	
	
	
	
	
	}
	else
	{
	  echo "<div class='error'>No Records Found</div>";
	}
}
}
include('includes/footer.inc');
?>