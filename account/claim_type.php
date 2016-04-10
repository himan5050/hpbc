<?php
include('includes/session.inc');
$title = _('Journal Entry');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='claimtypename')
{
   if($_REQUEST['order']=='asc')
   {
    $valclaimtypename="desc";
	$claimtypenameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valclaimtypename="asc";
	  $claimtypenameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valclaimtypename="asc";
 $claimtypenameimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='claimtypeid')
{
 if($_REQUEST['order']=='asc')
   {
    $valclaimtypeid="desc";
	$claimtypeidimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valclaimtypeid="asc";
	  $claimtypeidimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valclaimtypeid="asc";
 $claimtypeidimage='';
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='account')
{
 if($_REQUEST['order']=='asc')
   {
    $valaccount="desc";
	$accountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valaccount="asc";
	  $accountimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valaccount="asc";
 $accountimage='';
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='category')
{
 if($_REQUEST['order']=='asc')
   {
    $valcategory="desc";
	$categoryimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valcategory="asc";
	  $categoryimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valcategory="asc";
 $categoryimage='';
}

echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'. $_SERVER['SCRIPT_NAME'].'">Claim Type</a></div>';
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
	if (($_POST['id']!=''))
	{
		  $ui="select * from claim_type where id='".$_POST['id']."'";
		  $uiq=DB_query($ui,$db);
		  $uin=DB_num_rows($uiq);
		  if($uin>0)
		  {
	    $InputError = 1;
        prnMsg(_('Claim Type Id Already Exist'),'error');
		  }
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
		//	
		echo "<div class='success'>Claim Type ".$_POST['name']." Submitted</div>";
		
		}	  
	 unset($_POST);
	
    }
	
}
?>

 <form action="claim_type.php" method="post" name="form">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />

<input type="hidden" name="claimtypename" id="claimtypename" value="<?php echo $valclaimtypename;?>">
<input type="hidden" name="claimtypeid" id="claimtypeid" value="<?php echo $valclaimtypeid;?>">
<input type="hidden" name="account" id="account" value="<?php echo $valaccount;?>">
<input type="hidden" name="category" id="category" value="<?php echo $valcategory;?>">
<table>
<tr class="oddrow">
  <td colspan="6" align="center"><h2>Claim Type Form</h2></td>
</tr>

<tr><th align="center">S. No.</th>
<th align="center"><a href='javascript:void(0)' onclick=sorting('claimtypename');>Name</a><?php echo $claimtypenameimage;?></th>
<th align="center"><a href='javascript:void(0)' onclick=sorting('claimtypeid');>Id</a><?php echo $claimtypeidimage;?></th>
<th align="center"><a href='javascript:void(0)' onclick=sorting('account');>Account</a><?php echo $accountimage;?></th>
<th align="center"><a href='javascript:void(0)' onclick=sorting('category');>Category</a><?php echo $categoryimage;?></th>
<th align="center">Action</th></tr>
<?php
$ca="Select * from claim_type ".$orderby."";
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
<tr class="<?php echo $cl;?>"><td><?php echo $i;?></td><td><?php echo $car['claimtypename'];?></td><td><?php echo $car['claimtypeid'];?></td><td><?php echo $car['account'];?></td><td><?php echo $car['category'];?></td><td><a href="claim_type.php?delete=1&id=<?php echo $car['id']?>">Delete</a></td></tr>
<?php
 $i++;
}
?>
</table>
<br /><br/>
<table width="553" border="0" cellpadding="2" cellspacing="1">
    <tr class="evenrow"> <td ><div class="left">Name: <span style="color:#FF0000">*</span></div> 
    <div class="right"><input type="text" name="name"  size="45" id="name" maxlength="45" onkeypress="return textcoursename(event)"  value="<?php echo $_POST['name']?>"/></div></td></tr>
    <tr class="oddrow">
      <td><div class="left">Id: <span style="color:#FF0000">*</span></div>
     <div class="right"><input type="text" name="id"  id="id" size="45" maxlength="15" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['id']?>"/></div></td></tr>
	<tr class="evenrow">
	  <td><div class="left">Description: <span style="color:#FF0000">*</span></div>
	 <div class="right"><input type="text"  name="description" id="description" size="45"  maxlength="200" onkeypress="return alphanumeric(event)" value="<?php echo $_POST['description']?>"/></div></td>
    </tr>
	<tr class="oddrow">
	  <td><div class="left">Account: <span style="color:#FF0000">*</span></div>
	  <div class="right"><select  name="account" id="accountt">
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
	<div class="right"><select name="category" id="categoryy">
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

<?php
include('includes/footer.inc');

if (isset($_POST['submit']) ){
  $InputError = 0;

    if($_POST['name']=='')
	{
	 echo "<script type='text/javascript'>document.getElementById('name').className='ercol';</script>";
	}
	if (($_POST['name']!='') && (!eregi('^[a-zA-Z ]+$' , $_POST['name'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('name').className='ercol';</script>";
	}
	if($_POST['id']=='')
	{
	  echo "<script type='text/javascript'>document.getElementById('id').className='ercol';</script>";
	}
	if (($_POST['id']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['id'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('id').className='ercol';</script>";
	}
	if($_POST['description']=='')
	{
	  echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	if($_POST['account']=='')
	{
	  echo "<script type='text/javascript'>document.getElementById('accountt').className='ercol';</script>";
	}
	if($_POST['category']=='')
	{ //echo '<style>.dat {border:1px solid red !important}</style>';
	 echo "<script type='text/javascript'>document.getElementById('categoryy').className='ercol';</script>";
	}
}
?>
<script>
function sorting(a)
{  
 
var order=document.getElementById(a).value;

var corder;
if(order=='asc')
 {
   corder='desc';
 }
 else if(order=='desc')
 {
  corder='asc';
 }
  //alert(order);
 document.getElementById(a).value=corder;
//alert(document.getElementById(a).value);
 window.location.href="claim_type.php?sort="+a+"&order="+order;
 
}
</script>