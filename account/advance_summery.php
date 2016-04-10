<?php
include('includes/session.inc');
$title = _('Condemned items');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '';


?>
<div class="breadcrumb"><a href="/<?php echo $u[1]; ?>">Home</a> &raquo; <a href="<?php echo $_SERVER['SCRIPT_NAME'];?>">Advance Summary</a></div>
<form action="<?php $_SERVER['SCRIPT_NAME'] ?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table cellpadding="2" cellspacing="1" border="0" style="border:none;">
 <tr>	<td align="left" class="tdform-width"><fieldset><legend>Advance Summary</legend>
 <table align="left" class="frmtbl">
  	<tr> 	<td><div class="divwrapper"><div class="maincol"><b>Section Name:</b> <span style="color:#FF0000">*</span></div>
    <div class="maincol"><div id="li_1" >
		<select name="section">
                         <option value="">All</option>
                         <?php 
						 $sec="select * from tbl_lookups where lookupType_id='19'";
						 $secq=DB_query($sec,$db);
						 while($secr=DB_fetch_array($secq))
						 { 
						    
						  ?>
                          <option value="<?php echo $secr['lookup_id'];?>" ><?php echo ucwords($secr['lookup_name']);?> </option>
                          <?php
						 
						  }
						  ?>
                         </select>
		</div></div></div></td>
  <td><div class="divwrapper"><div class="maincol"><b>Loan Type:</b></div>
    <div class="maincol"><div id="li_1" >
		<select name="type" id="type">
      <option value="">--Select--</option>
      <option value="House And Building Advance" <?php if($_POST['type']=='House And Building Advance') { ?> selected="selected" <?php }?>>House & Building Advance</option>
      <option value="Vehicle Advance" <?php if($_POST['type']=='Vehicle Advance') { ?> selected="selected" <?php }?>>Vehicle Advance</option>
      <option value="Warm Clothing Advance" <?php if($_POST['type']=='Warm Clothing Advance') { ?> selected="selected" <?php }?>>Warm Clothing Advance</option>
      <option value="Festival Advance" <?php if($_POST['type']=='Festival Advance') { ?> selected="selected" <?php }?>>Festival Advance</option>
      </select>
		</div></div></div></td>
 <td> <div class="generatebtn"><input  type="submit" name="submit" value="Generate" />
		</div></td>
		</tr>
		
		</table></fieldset></td></tr>
		</table></form>
<?php 
if (isset($_POST['submit']) ){
  $InputError = 0;
 /* if($_POST['section']=='')
   {
     $InputError = 1;
     prnMsg(_('Select Department'),'error');
	}*/
$totbal=0;
$cond="";
 if($InputError!=1)
	{  
	  
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
	$q=DB_query($s,$db);
	$n=DB_num_rows($q);
	$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	if($n)
	{
  $rdata="
          <table>
		  <tr class='oddrow'><td colspan='6'><h2>Advance Summary</h2></td></tr>
		  <tr><td colspan='6' align='right'><a href='/".$u[1]."/generateadvancepdf.php?op=advance_summary&section=".$_POST['section']."&branch=".$corpbranch."&type=".$_POST['type']."' target='_blank'><img src='images/pdf_icon.gif'/></a></td></tr>
<tr><th><b>S. No.</b></th>
<th><b>Section Name</b></th>
<th><b>Amount Received</b></th>
<th><b>Balance Amount</b></th>
</tr>";
	 $i=1;
	
	 
	  while($r=DB_fetch_array($q))
	  {  $totbal=$totbal+($r['amount']);
	                    $sec="select * from tbl_lookups where lookup_id='".$r['section']."'";
						 $secq=DB_query($sec,$db);
						 $secr=DB_fetch_array($secq);
	    	   
	  if($i%2==0)
     {
	   $cl="even";
	 }
	 else
	 {
	   $cl="odd";
	 }
	 //getting data from empmonthdeduct where Acccode=id of loanadvance  
	 if($r['type_loan'] == 'House And Building Advance'){
    $typeloan = 7.00;
  }
  if($r['type_loan'] == 'Vehicle Advance'){
    $typeloan = 8.00;
  }
  if($r['type_loan'] == 'Warm Clothing Advance'){
    $typeloan = 9.00;
  }
   if($r['type_loan'] == 'Festival Advance'){
    $typeloan = 10.00;
  }
	
 

   $sqlg = "select sum(Amount) as Amount from empmonthdeduct where DeductCode='".$typeloan."'";
   $resg = DB_query($sqlg,$db);
  $rsg=DB_fetch_array($resg);
	
	    $rdata.="<tr class='".$cl."'><td>".$i."</td><td>".ucwords($secr['lookup_name'])."</td><td align='right'>".round(abs($rsg['Amount']))."</td><td align='right'>".round(abs($r['amount']))."</td></tr>";
		$i++;
	  }
	
	  
	  $rdata.="<tr><td colspan='3'><b>Total</b></td><td align='right'><b>".round(abs($totbal))."</b></td></tr></table>";
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