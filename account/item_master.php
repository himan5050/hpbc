<?php
include('includes/session.inc');
$title = _('Product Master');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="itemdetail.php">Item Details</a> &raquo; <a href="'.$_SERVER['SCRIPT_NAME'].'">Item Master</a></div>';
if (isset($_POST['submit']) ){
  $InputError = 0;

    if($_POST['itemcode']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Item Code'),'error');
	}
	 if (($_POST['itemcode']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemcode'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Item Code A-Z and 0-9 is Allowed'),'error');
	}
   if($_POST['itemname']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Item Name'),'error');
	}
	 if (($_POST['itemname']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemname'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Item Name A-Z and 0-9 is Allowed'),'error');
	}
	if($_POST['itemrate']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Itemrate'),'error');
	}
	if (($_POST['itemrate']!='') && (!is_numeric($_POST['itemrate'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Itemrate'),'error');
	}
  
	
	if($_POST['description']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Description'),'error');
	}
	 if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Description A-Z and 0-9 is Allowed'),'error');
	}
	
	if($_POST['opennumber']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Opening Number of Item'),'error');
	}
	if (($_POST['opennumber']!='') && (!is_numeric($_POST['opennumber'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Opening Number of Item'),'error');
	}
	if($_POST['category']=='')
   {
     $InputError = 1;
     prnMsg(_('Select category'),'error');
	}
	
	$it="select * from item_master where code='".$_POST['itemcode']."'";
	$itq=DB_query($it,$db);
	$itn=DB_num_rows($itq);
	if($itn>0)
	{
	  $InputError = 1;
     prnMsg(_('Item Code Already Exists'),'error');
	}
	if($InputError!=1)
	{
	  $sql="insert into item_master set name='".$_POST['itemname']."',
	                                    description='".$_POST['description']."',
										category='".$_POST['category']."',
										openingval='".$_POST['opennumber']."',
										openval='".$_POST['opennumber']."',
										code='".$_POST['itemcode']."',
										itemrate='".$_POST['itemrate']."'";
	 $query=DB_query($sql,$db);		
	 if($query)	
	 { 
	   unset($_POST);
	  echo "<div class='success'>Item type has been added successfully</div>";
	  @header("location:itemdetail.php?msg=Item type has been added successfully");
	 }						
	}
}
if(isset($_POST['reset']))
{
//echo "hjgvjhghjgghj";
 unset($_POST);
}


$data='<form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post" name="form">
<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
<table cellspacing="1" cellpadding="2">
<tr class="oddrow"><td colspan="2" align="center"><h2>Item Master</h2></td></tr>
   <tr class="evenrow">
    <td colspan="2"><div class="left"><font size="2">Item Code: <span style="color:#FF0000">*</span></font></div><div class="right">
  <input type="text" name="itemcode" id="itemcode" size="45" maxlength="6" value="'.$_POST['itemcode'].'" id="itemcode" onkeypress="return alphanumeric(event)"></div></td>
  </tr>
  <tr class="oddrow">
    <td colspan="2"><div class="left"><font size="2">Item Name: <span style="color:#FF0000">*</span></font></div><div class="right"><input type="text" name="itemname" id="itemname" size="45" maxlength="45" value="'.$_POST['itemname'].'" onkeypress="return alphanumeric(event)" /></div></td>
  </tr>
    <tr class="evenrow">
    <td colspan="2"><div class="left"><font size="2">Item Rate: <span style="color:#FF0000">*</span></font></div><div class="right"><input type="text" name="itemrate" id="itemrate" size="45" maxlength="11" value="'.$_POST['itemrate'].'" class="number" /></div></td>
  </tr>
   <tr class="oddrow">
    <td colspan="2"><div class="left"><font size="2">Description: <span style="color:#FF0000">*</span></font></div>
    <div class="right"><input type="text" name="description" id="description"  size="45" maxlength="200" value="'.$_POST['description'].'" onkeypress="return alphanumeric(event)"/></div></td>
  </tr>
  <tr class="evenrow">
    <td colspan="2"><div class="left"><font size="2">Opening Number of Item: <span style="color:#FF0000">*</span></font></div>
    <div class="right"><input type="text" name="opennumber" id="opennumber"  size="45" maxlength="20" value="'.$_POST['opennumber'].'" onkeypress = "return fononlyn(event)"/></div></td>
  </tr>
 
  <tr class="oddrow">
    <td><div class="left"><font size="2">Category: <span style="color:#FF0000">*</span></font></div>
    <div class="right"><select  name="category" id="category">
	     <option value="">--Select Category--</option>';
	$sql = "SELECT categoryid, categorydescription FROM stockcategory";
$resultStkLocs = DB_query($sql,$db);
while ($myrow=DB_fetch_array($resultStkLocs)){
   if($_POST['category']==$myrow['categoryid'])
       {
	   $data.= '<option Value="'.$myrow['categoryid'].'" selected>'.$myrow['categorydescription'].'</option>';
	   }
	   else
	   {
	      $data.= '<option Value="'.$myrow['categoryid'].'" >'.$myrow['categorydescription'].'</option>';
	   }
		
		 
	}


$data.= '</select></div>';
	$data.='</td>
  </tr>
 
  <tr class="evenrow">
    
   <td colspan="2" align="center"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table></form>';
echo $data;
echo'<br/>';
include('includes/footer.inc');
?>
<?php
if (isset($_POST['submit']) ){
  $InputError = 0;

    if($_POST['itemcode']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('itemcode').className='ercol';</script>";
	}
	
	$it="select * from item_master where code='".$_POST['itemcode']."'";
	$itq=DB_query($it,$db);
	$itn=DB_num_rows($itq);
	if($itn>0)
	{
	  $InputError = 1;
     echo "<script type='text/javascript'>document.getElementById('itemcode').className='ercol';</script>";
	}
	
	
	 if (($_POST['itemcode']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemcode'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('itemcode').className='ercol';</script>";
	}
   if($_POST['itemname']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('itemname').className='ercol';</script>";
	}
	 if (($_POST['itemname']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemname'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('itemname').className='ercol';</script>";
	}
	if($_POST['itemrate']=='')
   {
    echo "<script type='text/javascript'>document.getElementById('itemrate').className='ercol';</script>";
	}
	if (($_POST['itemrate']!='') && (!is_numeric($_POST['itemrate'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('itemrate').className='ercol';</script>";
	}
  
	
	if($_POST['description']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	 if (($_POST['description']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['description'])))
	{
	 echo "<script type='text/javascript'>document.getElementById('description').className='ercol';</script>";
	}
	
	if($_POST['opennumber']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('opennumber').className='ercol';</script>";
	}
	if (($_POST['opennumber']!='') && (!is_numeric($_POST['opennumber'])))
	{
	  echo "<script type='text/javascript'>document.getElementById('opennumber').className='ercol';</script>";
	}
	if($_POST['category']=='')
   {
     echo "<script type='text/javascript'>document.getElementById('category').className='ercol';</script>";
	}
	
	
	}
?>