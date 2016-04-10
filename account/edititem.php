<?php
include('includes/session.inc');
$title = _('Product Master');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
echo '<div class="breadcrumb">Home &raquo; <a href="itemdetail.php">Item Details</a> &raquo; <a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'">Edit Item</a></div>';
$id=$_REQUEST['id'];
$s="select * from item_master where id='".$id."'";
$q=Db_query($s,$db);
$r=Db_fetch_array($q);

if (isset($_POST['submit']) ){
  $InputError = 0;

    if($_POST['itemcode']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Item Code'),'error');
	}
   if($_POST['itemname']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Item Name'),'error');
	}
	 if (($_POST['itemname']!='') && (!eregi('^[0-9a-zA-Z ]+$' , $_POST['itemname'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter valid Item Name,A-Z and 0-9 is Allowed'),'error');
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
     prnMsg(_('Enter valid Description,A-Z and 0-9 is Allowed'),'error');
	}
	
	if($_POST['opennumber']=='')
   {
     $InputError = 1;
     prnMsg(_('Enter Opening Number of Item'),'error');
	}
	if (($_POST['opennumber']!='') && (!is_numeric($_POST['opennumber'])))
	{
	  $InputError = 1;
     prnMsg(_('Enter Numeric Value For Open Number of Item'),'error');
	}
	if($_POST['category']=='')
   {
     $InputError = 1;
     prnMsg(_('Select category'),'error');
	}
	
	if($InputError!=1)
	{
	   $sql="update item_master set name='".$_POST['itemname']."',
	                                    description='".$_POST['description']."',
										category='".$_POST['category']."',
										openingval='".$_POST['opennumber']."',
										
										itemrate='".$_POST['itemrate']."' 
										where id='".$_POST['id']."'";
	 $query=DB_query($sql,$db);		
	 if($query)	
	 { 
	   header("location:itemdetail.php?msg=Item Type Updated");
	  echo "Item Type Updated";
	 }						
	}
}


$data='<form action="' . $_SERVER['PHP_SELF'] . '" method="post" name="form">
<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />

<table width="448" height="148" border="0">
   
   <tr class="oddrow"><td colspan="2" align="center"><h2>Edit Item</h2></td></tr>
   <tr class="evenrow">
    <td>
		<div class="left">Item Code: <span style="color:#FF0000">*</span></div>
		<div class="right"><select name="itemcode"><option value="">--Select Item--</option>';
	$ic="select code,name from item_master order by code";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{  if($r['code']==$icr['code'])
	    {
	      $data.= '<option value="'.$icr['code'].'" selected>'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
	    }
		else
		{
		  $data.= '<option value="'.$icr['code'].'" >'.ucwords($icr['code']).'-'.ucwords($icr['name']).'</option>';
		}
	}
	$data.='</select></div>
		</td>	
  </tr>
  <tr class="oddrow">
    <td><div class="left">Item Name: <span style="color:#FF0000">*</span></div>
		<div class="right"><input type="text" name="itemname"  size="45" maxlength="40" value="'.$r['name'].'" onkeypress="return alphanumeric(event)"/><input type="hidden" name="id"  size="45" maxlength="40" value="'.$_REQUEST['id'].'" /></div>
	</td>   
  </tr>
    <tr class="evenrow">
    <td>
		<div class="left">Item Rate: <span style="color:#FF0000">*</span></div>
		<div class="right"><input type="text" name="itemrate"  size="45" maxlength="11" value="'.$r['itemrate'].'" class="number""/></div>
		</td>    
  </tr>
   <tr class="oddrow">
    <td>
		<div class="left">Description: <span style="color:#FF0000">*</span></div>
		<div class="right"><input type="text" name="description"  size="45" maxlength="200" value="'.$r['description'].'" onkeypress="return alphanumeric(event)"/></div>
		</td>   
  </tr>
  <tr class="evenrow">
    <td>
		<div class="left">Opening Number of Item: <span style="color:#FF0000">*</span></div>
		<div class="right"><input type="text" name="opennumber"  size="45" maxlength="20" value="'.$r['openingval'].'" onkeypress = "return fononlyn(event)"/></div>
		</td>
  </tr> 
  <tr class="oddrow">
    <td>
		<div class="left">Category: <span style="color:#FF0000">*</span></div>
		<div class="right"><select  name="category">
	     <option value="">--Select Category--</option>';
	$sql = "SELECT categoryid, categorydescription FROM stockcategory";
$resultStkLocs = DB_query($sql,$db);
while ($myrow=DB_fetch_array($resultStkLocs)){
       if($r['category']==$myrow['categoryid'])
	   {
	   $data.= '<option Value="'.$myrow['categoryid'].'" selected>'.$myrow['categorydescription'].'</option>';
		}
		else
		{
		 $data.= '<option Value="'.$myrow['categoryid'].'" >'.$myrow['categorydescription'].'</option>';
		}
		 
	}


$data.= '</select>';
	$data.='</div>
		</td>   
  </tr> 
  <tr class="evenrow">
   
    <td align="center"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table></form>';
echo $data;
include('includes/footer.inc');
?>