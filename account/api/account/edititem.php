<?php
include('includes/session.inc');
$title = _('Product Master');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
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
	   header("location:itemdetail.php");
	  echo "Item Type Updated";
	 }						
	}
}


$data='<form action="' . $_SERVER['PHP_SELF'] . '" method="post" name="form">
<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
<table width="448" height="148" border="0">
   
   <tr class="oddrow"><td colspan="2" align="center"><h1> Edit Item</h1></td></tr>
   <tr class="evenrow">
    <td width="131">Item Code :</td>	
    <td width="301"><select name="itemcode"><option value="">--Select Item--</option>';
	$ic="select code,name from item_master";
	$icq=DB_query($ic,$db);
	while($icr=Db_fetch_array($icq))
	{  if($r['code']==$icr['code'])
	    {
	      $data.= '<option value="'.$icr['code'].'" selected>'.$icr['code'].'-'.$icr['name'].'</option>';
	    }
		else
		{
		  $data.= '<option value="'.$icr['code'].'" >'.$icr['code'].'-'.$icr['name'].'</option>';
		}
	}
	$data.='</select></td>
  </tr>
  <tr class="oddrow">
    <td width="131">Item Name :</td>
    <td width="301"><input type="text" name="itemname"  size="45" maxlength="40" value="'.$r['name'].'"/><input type="hidden" name="id"  size="45" maxlength="40" value="'.$_REQUEST['id'].'"/></td>
  </tr>
    <tr class="evenrow">
    <td width="131">Item Rate :</td>
    <td width="301"><input type="text" name="itemrate"  size="45" maxlength="11" value="'.$r['itemrate'].'"/></td>
  </tr>
   <tr class="oddrow">
    <td width="131">Description :</td>
    <td width="301"><input type="text" name="description"  size="45" maxlength="200" value="'.$r['description'].'"/></td>
  </tr>
  <tr class="evenrow">
    <td>Opening Number Of Item :</td>
    <td><input type="text" name="opennumber"  size="45" maxlength="20" value="'.$r['openingval'].'"/></td>
  </tr>
 
  <tr class="oddrow">
    <td>Category :</td>
    <td><select  name="category">
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
	$data.='</td>
  </tr>
 
  <tr class="evenrow">
   
    <td colspan="2" align="center"><input  type="submit" name="submit" value="Save" />&nbsp;&nbsp;<input  type="reset" name="reset" value="Reset" /></td>
  </tr>
</table></form>';
echo $data;
include('includes/footer.inc');
?>