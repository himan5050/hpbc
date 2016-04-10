<?php
include('includes/session.inc');
$title = _('Product Master');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$rec_limit = 10;
$cond='';
if(isset($_POST['search']))
{
  $cond.="and (code like '%".$_POST['search']."%' OR name like '%".$_POST['search']."%' OR category like '%".$_POST['search']."%')";
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
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='code')
{
 if($_REQUEST['order']=='asc')
   {
    $valcode="desc";
	$codeimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valcode="asc";
	  $codeimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valcode="asc";
 $codeimage='';
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
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='itemrate')
{
 if($_REQUEST['order']=='asc')
   {
    $valitemrate="desc";
	$itemrateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valitemrate="asc";
	  $itemrateimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valitemrate="asc";
 $itemrateimage='';
}
if(isset($_REQUEST['sort']) && $_REQUEST['sort']=='openingval')
{
 if($_REQUEST['order']=='asc')
   {
    $valopeningval="desc";
	$openingvalimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-asc.png">';
	}
	else
	{
	  $valopeningval="asc";
	  $openingvalimage='<img height="13" width="13" title="" alt="sort icon" src="images/arrow-desc.png">';
	}
}
else
{
 $valopeningval="asc";
 $openingvalimage='';
}
echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Item Details</a></div>';
echo '<form name="form" method="post">
<input type="hidden" name="FormID" value="'. $_SESSION['FormID'] .'" />';
echo '<input type="hidden" name="name" id="name" value="'.$valname.'">
<input type="hidden" name="category" id="category" value="'.$valcategory.'">
<input type="hidden" name="code" id="code" value="'.$valcode.'">
<input type="hidden" name="openingval" id="openingval" value="'.$valopeningval.'">
<input type="hidden" name="itemrate" id="itemrate" value="'.$valitemrate.'">';
 
 $count_query = "SELECT COUNT(id) FROM item_master where 1=1 ".$cond."";

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
$left_rec = $rec_count - ($page * $rec_limit);

$s="select * from item_master where 1=1 ".$cond." ".$orderby." LIMIT $offset, $rec_limit";
$q=Db_query($s,$db);
$nu=DB_num_rows($q);
if(isset($_REQUEST["search"]))
 {
  echo "<div class='searchrecord'>".$nu." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='itemdetail.php'>View all</a> </div>";
  }
 
echo '<div class="tblHeaderRight"><input type="text" name="search" value="">&nbsp;<input type="submit" name="go" value="Search"></div>';
echo '<div class="tblHeaderLeft"><h1>Item Details</h1><span class="addrecord"><a href="item_master.php">Add Item</a></span></div><br>';
$data="<table cellpadding='2' cellspacing='1'>
<tr><th><b><a href='javascript:void(0)' onclick=sorting('code');>Code</a></b> ".$codeimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('name');>Name</a></b> ".$nameimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('category');>Category</a></b> ".$categoryimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('openingval');>Item Number</a></b> ".$openingvalimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('itemrate');>Item Rate</a></b> ".$itemrateimage."</th><th>Action</th><tr>";

$i=1;
while($r=Db_fetch_array($q))
{ if(($i%2)==0)
	{
	  $cl="even";
	}
	else
	{
	  $cl="odd";
	}
 $data.="<tr class=".$cl."><td>".$r['code']."</td><td>".$r['name']."</td><td>".$r['category']."</td><td>".$r['openingval']."</td><td>".$r['itemrate']."</td><td><a href='edititem.php?id=".$r['id']."'>Edit</a></td><tr>";
 $i++;
}

$data.='</table><br><div class="paging">';
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
	      $datap.= "<a href=\"$_PHP_SELF?page=".($nn-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\">".$pg."</a> ";
	  
	  
	 }
  
	if($left_rec <= $rec_limit && $page!=0)
{   
   $last = $page-2;
  $data.= "<a href=\"$_PHP_SELF?order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous</a>";
}

	else if( $page > 0)
{  
   $last = $page - 2;
      $data.= "<a href=\"$_PHP_SELF?order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;First</a> &nbsp; <a href=\"$_PHP_SELF?page=$last&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> &laquo;Previous </a>&nbsp;  &nbsp;";
	
   $data.= $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo;</a> &nbsp; <a href=\"$_PHP_SELF?page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}
 
else if( $page == 0 && $left_rec > $rec_limit)
{   
   $data.= $datap."&nbsp;&nbsp;<a href=\"$_PHP_SELF?page=$page&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Next&raquo;</a> &nbsp; <a href=\"$_PHP_SELF?page=".($topage-2)."&order=".$_REQUEST['order']."&sort=".$_REQUEST['sort']."\"> Last&raquo;</a>";
}


$data.='</div>';
$data.="";
echo $data;
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
 window.location.href="itemdetail.php?sort="+a+"&order="+order;
 
}
</script>