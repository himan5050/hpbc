<?php

include('includes/session.inc');
$title = _('Purchase Order');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');

if(isset($_GET['op']) && $_GET['op']=='view')
{
  unset($_SESSION['searchtext']);
  unset($_SESSION['rec_count']);
  
 $sql = "SELECT od.id,od.itemcode,od.referencenum,od.quantity_required,od.date,od.status,im.name from purchase_order_details as od,item_master as im where 1=1 and im.code=od.itemcode ".$cond."";

 $count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";

$retval =DB_query( $count_query, $db );

$row = DB_fetch_array($retval);
$rec_count = $row[0];
   //unset($_SESSION['searchtext']);
  
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']!='')
{
  $orderby="order by ".$_REQUEST['sort']." ". $_REQUEST['order'];
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='name')
{
   if($_REQUEST['order']=='asc')
   {
    $valname="desc";
	$nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valname="asc";
	  $nameimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valname="asc";
 $nameimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='referencenum')
{
 if($_REQUEST['order']=='asc')
   {
    $valreferencenum="desc";
	$referencenumimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valreferencenum="asc";
	  $referencenumimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valreferencenum="asc";
 $referencenumimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='quantity_required')
{
 if($_REQUEST['order']=='asc')
   {
    $valquantity_required="desc";
	$quantity_requiredimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valquantity_required="asc";
	  $quantity_requiredimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valquantity_required="asc";
 $quantity_requiredimage='';
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='date')
{
 if($_REQUEST['order']=='asc')
   {
    $valdate="desc";
	$dateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valdate="asc";
	  $dateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valdate="asc";
 $dateimage='';
}

if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='amountitem')
{
 if($_REQUEST['order']=='asc')
   {
    $valamountitem="desc";
	$amountitemimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valamountitem="asc";
	  $amountitemimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valamountitem="asc";
 $amountitemimage='';
}


if(isset($_GET['status']))
{
  $sq="update purchase_order_details set status='".$_GET['status']."' where referencenum='".$_GET['refid']."'";
  $sqq=DB_query($sq,$db);
  header("location:purchase_order_detail.php");
}
?>
<script>
function change(a,b)
{
 
 window.location.href="purchase_order_detail.php?status="+a+"&refid="+b;
}
</script>
<form name="form" method="post">
<input type="hidden" name="FormID" value="<?php echo $_SESSION['FormID'] ?>" />
<div class="breadcrumb"><a href="/<?php echo $u[1];?>">Home</a> &raquo; <a href="purchase_order_detail.php">List of Purchase Order Details</a></div>
<?php
if(isset($_SESSION['searchtext']))
{
	//$_REQUEST['search']=$_SESSION['search'];
}
  

if(isset($_POST['searchtext']))
{ 
  $cond.="and (im.name LIKE '%".$_POST['searchtext']."%' OR od.referencenum = '".$_POST['searchtext']."' OR od.quantity_required = '".$_POST['searchtext']."')";
  unset($_GET{'page'});
  $sql = "SELECT od.id,od.itemcode,od.referencenum,od.quantity_required,od.date,od.status,im.name from purchase_order_details as od,item_master as im where 1=1 and im.code=od.itemcode ".$cond."";

 $count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";

$retval =DB_query( $count_query, $db );

$row = DB_fetch_array($retval);
$rec_count = $row[0];
  unset($_GET{'page'});
   $_SESSION['searchtext']=$_REQUEST['searchtext'];
}else{
	if(isset($_SESSION['searchtext']) && !isset($_REQUEST['page'])){
	 unset($_SESSION['searchtext']);
	}
}

if(isset($_SESSION['searchtext']))
{ 
  $cond.="and (im.name LIKE '%".$_SESSION['searchtext']."%' OR od.referencenum = '".$_SESSION['searchtext']."' OR od.quantity_required = '".$_SESSION['searchtext']."')";
  $sql = "SELECT od.id,od.itemcode,od.referencenum,od.quantity_required,od.date,od.status,im.name from purchase_order_details as od,item_master as im where 1=1 and im.code=od.itemcode ".$cond."";

 $count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";

$retval =DB_query( $count_query, $db );

$row = DB_fetch_array($retval);
$rec_count = $row[0];
  $_SESSION['rec_count'] = $rec_count;
   $_SESSION['searchtext']=$_REQUEST['searchtext'];
   //unset($_SESSION['searchtext']);
}else{
	$cond.="and (im.name LIKE '%".$_SESSION['searchtext']."%' OR od.referencenum = '".$_SESSION['searchtext']."' OR od.quantity_required = '".$_SESSION['searchtext']."')";
  $sql = "SELECT od.id,od.itemcode,od.referencenum,od.quantity_required,od.date,od.status,im.name from purchase_order_details as od,item_master as im where 1=1 and im.code=od.itemcode ".$cond."";

 $count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";

$retval =DB_query( $count_query, $db );

$row = DB_fetch_array($retval);
$rec_count = $row[0];
   unset($_SESSION['searchtext']);
	
}

$rec_limit = 10;

/*$sql = "SELECT od.id,od.itemcode,od.referencenum,od.quantity_required,od.date,od.status,im.name from purchase_order_details as od,item_master as im where 1=1 and im.code=od.itemcode ".$cond."";

 $count_query = "SELECT COUNT(*) FROM (" .$sql . ") AS count_query";

$retval =DB_query( $count_query, $db );

$row = DB_fetch_array($retval);
$rec_count = $row[0];*/
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
if(isset($_SESSION['rec_count'])){
	$left_rec = $_SESSION['rec_count'] - ($page * $rec_limit);
	}else{
		$left_rec = $rec_count - ($page * $rec_limit);
		}

 $s="select od.id,od.itemcode,od.referencenum,od.quantity_required,od.amountitem,od.date,od.status,im.name from purchase_order_details as od,item_master as im where 1=1 and im.code=od.itemcode ".$cond." ".$orderby." LIMIT $offset, $rec_limit";
$q=DB_query($s,$db);
$nu=DB_num_rows($q);
 $i=1;
 if(isset($_REQUEST['searchtext']))
 {
  echo "<div class='searchrecord'> ".$rec_count." Record(s) Found. &nbsp;| <a href='purchase_order_detail.php?op=view'>View all</a></div>";
  }
  if(isset($_REQUEST['msg']))
  {
  echo "<span style='color:green'>".$_REQUEST['msg']."</span>";
  }
?>
<input type="hidden" name="name" id="name" value="<?php echo $valname;?>">
<input type="hidden" name="referencenum" id="referencenum" value="<?php echo $valreferencenum;?>">
<input type="hidden" name="quantity_required" id="quantity_required" value="<?php echo $valquantity_required;?>">
<input type="hidden" name="date" id="date" value="<?php echo $valdate;?>">
<input type="hidden" name="amountitem" id="amountitem" value="<?php echo $valamountitem;?>">
<div class="tblHeaderLeft">
  <h1>Purchase Order Details</h1><span class="addrecord"><a href="purchase_order.php"> Add Purchase Order </a></span></div><div class="tblHeaderRight"><input type="text" name="searchtext" value="">&nbsp;<input type="submit" name="search" value="Search"></div>
<table>

<tr><th><b>S. No.</b></th>
<th><a href='javascript:void(0)' onclick=sorting('name');><b>Item Name</b></a> <?php echo $nameimage;?></th>
<th><a href='javascript:void(0)' onclick=sorting('referencenum');><b>Reference No.</b></a> <?php echo $referencenumimage;?></th>
<th><a href='javascript:void(0)' onclick=sorting('quantity_required');><b>Quantity Required</b></a> <?php echo $quantity_requiredimage;?></th>
<th><a href='javascript:void(0)' onclick=sorting('amountitem');><b>Amount of Item</b></a> <?php echo $amountitemimage;?></th>
<th><a href='javascript:void(0)' onclick=sorting('date');><b>Date</b></a> <?php echo $dateimage;?></th>
<th><b>Status</b></th>
<th align="center">Action</th></tr>

<?php
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
while($r=Db_fetch_array($q))
{   if($i%2==0)
     {
	   $cl="even";
	 }
	 else
	 {
	   $cl="odd";
	 }
?>
 
 <tr class="<?php echo $cl;?>"><td><?php echo $nn;?></td><td><?php echo $r['name'];?></td><td><?php echo $r['referencenum']?></td><td><?php echo $r['quantity_required']?></td><td><?php echo round(abs($r['amountitem']))?></td><td><?php echo date('d-m-Y',$r['date'])?></td>
 <td><?php if($r['status']=='pending') {?>Pending <?php } else if($r['status']=='po_generated') {?>Po Generated<?php } else {?>Supplied From Store<?php } ?></td><td><?php $eo="select id from item_details where refnum='".$r['referencenum']."'"; $eoq=DB_query($eo,$db); $eon=DB_num_rows($eoq); if(!$eon) {?><a href="editpurchaseorder.php?id=<?php echo $r['id'];?>">Edit</a> <?php } ?></td></tr>
<?php  $i++;
      $nn++;
} ?>
</table><br /><div class="paging"><?php 
  
	 for($nn=1;$nn<=$topage;$nn++)
	 {
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
		}
		else
		{
		  if($nn==1)
		  {
		   $pg="<strong>".$nn."</strong>";
		  }
		  else
		  {
		    $pg=$nn;
		  }
		}	
	      $datap .="<a href=\"$_PHP_SELF?page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	  
	  
	 }
	
if($left_rec <= $rec_limit && $page!=0)
{   
   $last = $page-2;
   echo "<a href=\"$_PHP_SELF?order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous</a>";
}

	else if( $page > 0)
{  
   $last = $page - 2;
      echo "<a href=\"$_PHP_SELF?order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous </a>&nbsp;  &nbsp;";
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}
 
else if( $page == 0 && $left_rec > $rec_limit)
{   
   echo $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo; </a> &nbsp; <a href=\"$_PHP_SELF?page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}


?></div>

</form>
<?php 
include("includes/footer.inc");
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
 window.location.href="purchase_order_detail.php?sort="+a+"&order="+order;
 
}
</script>