<?php
include('includes/session.inc');
$title = _('Condemned items');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
?>
<div class="breadcrumb"><a href="/<?php echo $u[1]; ?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">Advance Detail</a></div>
<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
 <tr>	<td align="left" class="tdform-width"><fieldset><legend>Advance Detail</legend>
 <table align="left" class="frmtbl">
  	<tr> 	<td><div class="divwrapper"><div class="maincol"><b>Section Name:</b></div>
    <div class="maincol"><div id="li_1" >
		<select name="section">
                         <option value="">All</option>
                         <?php 
						 $sec="select * from tbl_lookups where lookupType_id='19'";
						 $secq=DB_query($sec,$db);
						 while($secr=DB_fetch_array($secq))
						 { 
						    if($_POST['section']==$secr['lookup_id'])
							{
						  ?>
                          <option value="<?php echo $secr['lookup_id'];?>" selected="selected" ><?php echo ucwords($secr['lookup_name']);?> </option>
                          <?php
						 
						  }
						  else
						  {
						  ?>
                           <option value="<?php echo $secr['lookup_id'];?>" ><?php echo ucwords($secr['lookup_name']);?> </option>
                           <?php } 
						 }?>
                         </select>
		</div></div></div></td>
  <td><div class="divwrapper"><div class="maincol"><b>Employee Name: <span style="color:#ff0000">*</span></b></div>
    <div class="maincol"><div id="li_2" >
		<input  type="text" name="empname"  value="<?php echo $_POST['empname']?>" style="width:120px;"/>
		</div></div></div></td>
 <td> <div class="generatebtn"><input  type="submit" name="submit" value="Generate" />
		</div></td>
		</tr>	
        <tr><td><div class="divwrapper"><div class="maincol"><b>Loan Type:</b></div>
    <div class="maincol"><div id="li_1" >
		<select name="type" id="type">
      <option value="">--Select--</option>
      <option value="House And Building Advance" <?php if($_POST['type']=='House And Building Advance') { ?> selected="selected" <?php }?>>House & Building Advance</option>
      <option value="Vehicle Advance" <?php if($_POST['type']=='Vehicle Advance') { ?> selected="selected" <?php }?>>Vehicle Advance</option>
      <option value="Warm Clothing Advance" <?php if($_POST['type']=='Warm Clothing Advance') { ?> selected="selected" <?php }?>>Warm Clothing Advance</option>
      <option value="Festival Advance" <?php if($_POST['type']=='Festival Advance') { ?> selected="selected" <?php }?>>Festival Advance</option>
      </select>
		</div></div></div></td><td>&nbsp;</td><td>&nbsp;</td></tr>
		</table></fieldset></td></tr>
		</table></form>
<?php 
if (isset($_POST['submit']) ){
  $InputError = 0;
/*if($_POST['empname']=='' )
   {
     $InputError = 1;
     prnMsg(_('Enter Name'),'error');
	}*/
$cond="";
 if($InputError!=1)
	{  
	  
	   if($_POST['empname'])
	   { 
	     $cond .=" and ( tbl_joinings.employee_name like '%".$_POST['empname']."%' )";
		// $cond .=",tbl_joinings where tbl_joinings.program_uid=loanadvance.empid ".$cond1."";
	   }
	   
	    if($_POST['section'])
	   {
	     $cond .=" and ( loanadvance.section=".$_POST['section'].")";
		 //$tbl=",tbl_lookups";
	   }
	   
	   if(isset($_POST['type']) && $_POST['type']!='')
	   {
		  $cond .=" and (loanadvance.type_loan='".$_POST['type']."')";  
	   }
		 $cond .=" and tbl_joinings.program_uid=loanadvance.empid";
	   
	   $totbal=0;
	   $totadv=0;
	   
   $s="select * from loanadvance,tbl_joinings where loanadvance.approvestatus=1 $cond";
   //echo $s;exit;
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	if($n)
	{
  $rdata="
          <table>
		  <tr class='oddrow'><td colspan='6'><h2>Advance Detail</h2></td></tr>
		  <tr><td colspan='7' align='right'><a href='/".$u[1]."/generateadvancepdf.php?op=advance_detail&section=".$_POST['section']."&empname=".$_POST['empname']."&branch=".$corpbranch."&type=".$_POST['type']."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
<tr><th align='center'><b>S. No.</b></th>
<th><b>Section Name</b></th>
<th><b>Employee Id</b></th>
<th><b>Employee Name</b></th>
<th><b>Amount Received</b></th>
<th><b>Balance Amount</b></th>
</tr>";
	 $i=1;
	
	
	  while($r=DB_fetch_array($q))
	  {  
	    	   $totbal=$totbal+($r['amount']);
			   $totadv=$totadv+$r['advance'];
	  if($i%2==0)
     {
	   $cl="even";
	 }
	 else
	 {
	   $cl="odd";
	 }
	 
	                    $sec="select * from tbl_lookups where lookup_id='".$r['section']."'";
						 $secq=DB_query($sec,$db);
						 $secr=DB_fetch_array($secq);
			
  //getting data from empmonthdeduct where Acccode=id of loanadvance  

   $sqlg = "select sum(Amount) as Amount from empmonthdeduct where Acccode='".$r['id']."'";
   $resg = DB_query($sqlg,$db);
  $rsg=DB_fetch_array($resg);
			
	    $rdata.="<tr class='".$cl."'><td align='center'>".$i."</td><td>".ucwords($secr['lookup_name'])."</td><td>".$r['employee_id']."</td><td>".ucwords($r['employee_name'])."</td>
		<td align='right'>".round($rsg['Amount'])."</td><td align='right'>".round($r['amount'])."</td></tr>";
		$i++;
	  }
	
	  
	  $rdata.="<tr><td colspan='4'><b>Total</b></td><td></td><td align='right'><b>".round($totbal)."</b></td></tr></table>";
	echo $rdata;
	}
	else
	{
	  echo "<div class='error'>No Result Found</div>";
	}
}
}
?>
<?php include('includes/footer.inc');?>