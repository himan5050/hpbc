

<?php


include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_GET['delete']))
{
  $de="delete from claim_type where id='".$_GET['id']."'";
  $deq=DB_query($de,$db);
  echo "<div class='success'>Claim Type has been deleted successfully</div>";
}

if (isset($_POST['submit']) ){
  $InputError = 0;

    if($_POST['name']=='')
	{
	  $InputError = 1;
     prnMsg(_('Enter Claim Type Name'),'error');
	}
	if (($_POST['name']!='') && (!eregi('^[a-zA-Z ]+$' , $_POST['name'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Claim Type Name, A-Z or a-z is Allowed'),'error');
	}
	if($_POST['id']=='')
	{
	  $InputError = 1;
     prnMsg(_('Enter Claim Type Id'),'error');
	}
	if (($_POST['id']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['id'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Claim Type Id, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['description']=='')
	{
	  $InputError = 1;
     prnMsg(_('Enter Claim Type Description'),'error');
	}
	if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Description, A-Z or 0-9 is Allowed'),'error');
	}
	if($_POST['account']=='')
	{
	  $InputError = 1;
     prnMsg(_('Select Claim Type Account'),'error');
	}
	if($_POST['category']=='')
	{
	  $InputError = 1;
     prnMsg(_('Select Claim Type Category'),'error');
	}

  if($InputError!=1)
	{ 
	  

	   
		
		$s="insert into claim_type set claimtypename='".$_POST['name']."',
		                               claimtypeid='".$_POST['id']."',
									   description='".$_POST['description']."',
									   account='".$_POST['account']."',
									   category='".$_POST['category']."'";
		$q=DB_query($s,$db);
		if($q)
		{
		unset($_POST);	
		echo "<div class='success'>Your Claim Type is Submitted</div>";
		}	  
	 
	
    }
	
}
?>
<div class="breadcrumb">Home &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>">Claim Type</a></div>
 <form action="<?php $_SERVER['PHP_SELF']?>" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<table width="553" border="0" cellpadding="2" cellspacing="1">
<tr class="oddrow">
  <td colspan="2" align="center"><h2>Claim Type Form</h2></td>
</tr>
    <tr class="evenrow"> <td ><div class="left">Name: <span style="color:#FF0000">*</span></div> 
    <div class="right"><input type="text" name="name"  size="45" maxlength="45" onkeypress="return textcoursename(event)"  value="<?php echo $_POST['name']?>"/></div></td></tr>
    <tr class="oddrow">
      <td><div class="left">Id: <span style="color:#FF0000">*</span></div>
     <div class="right"><input type="text" name="id"  size="45" maxlength="15" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['id']?>"/></div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Description: <span style="color:#FF0000">*</span></div>
	 <div class="right"><input type="text"  name="description"  size="45"  maxlength="200" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['description']?>"/></div></td>
    </tr>
	<tr class="oddrow">
	  <td><div class="left">Account: <span style="color:#FF0000">*</span></div>
	  <div class="right"><select  name="account">
      <option value="">--Select--</option>
  <?php 
	$sql = "SELECT accountcode,
				accountname
			FROM chartmaster,
				accountgroups
			WHERE chartmaster.group_ = accountgroups.groupname
			AND accountgroups.pandl = 0
			ORDER BY accountname";
	
	$result = DB_query($sql,$db);
	while ($myrow = DB_fetch_array($result)) {
		if (isset($_POST['account']) and $myrow['accountcode']==$_POST['account']) {
			echo '<option selected value="';
		} else {
			echo '<option value="';
		}
		echo $myrow['accountcode'] . '">' . $myrow['accountname'] . '</option>';

	} //end while loop
  ?>
	</select></div></td>
    </tr>
	<tr class="evenrow">
	  <td><div class="left">Category: <span style="color:#FF0000">*</span></div>
	<div class="right"><select name="category">
      <option value="">--Select--</option>
         <?php
		 $cat="select * from tbl_lookups where lookupType_id='48'";
		 $catq=DB_Query($cat,$db);
		 while($catr=DB_fetch_array($catq))
		 {  
		 if($_POST['category']==$catr['lookup_name'])
		 {
		 ?>
         <option value="<?php echo $catr['lookup_name'];?>" selected="selected"><?php echo $catr['lookup_name'];?></option>
         <?php
		 } 
		 else
		 {?>
         <option value="<?php echo $catr['lookup_name'];?>" ><?php echo $catr['lookup_name'];?></option>
         <?php
		 }
		 }
		 ?>
         </select></div></td>
    </tr>
   
      
    <tr class="oddrow"><td align="center" style="border-right:hidden;"><input  type="submit" name="submit" value="Submit" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table>
</form>
<br />
<br />

<table><tr><th align="center">Name</th><th align="center">Id</th><th align="center">Account</th><th align="center">Category</th><th align="center">Action</th></tr>
<?php
$ca="Select * from claim_type";
$caq=DB_query($ca,$db); 
$i=1;
while($car=DB_fetch_array($caq))
{  if($i%2==0)
    {
	  $cl="even";
	  }
	  else
	  {
	    $cl="odd";
	  }  
?>
<tr class="<?php echo $cl;?>"><td><?php echo $car['claimtypename'];?></td><td><?php echo $car['claimtypeid'];?></td><td><?php echo $car['account'];?></td><td><?php echo $car['category'];?></td><td><a href="claim_type.php?delete=1&id=<?php echo $car['id']?>">Delete</a></td></tr>
<?php
 $i++;
}
?>
</table>
<?php
include('includes/footer.inc');
?>
