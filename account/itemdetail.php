<?php
include('includes/session.inc');
$title = _('Product Master');

include('includes/header.inc');
include('includes/SQL_CommonFunctions.inc');
$rec_limit = 10;
//$cond='';
if(isset($_GET['op']) && $_GET['op']=='view')
{
  unset($_SESSION['search']);
  unset($_SESSION['rec_count']);
  $count_query = "SELECT COUNT(id) FROM item_master where 1=1 ".$cond."";

	$retval =DB_query( $count_query, $db );
	$row = DB_fetch_array($retval);
	$rec_count = $row[0];	
}
if(isset($_SESSION['search']))
{
	//$_REQUEST['search']=$_SESSION['search'];
}

if(isset($_REQUEST['search']))
{
  $_REQUEST['page'] =-1;
  $cond.="and (code like '%".$_REQUEST['search']."%' OR name like '%".$_REQUEST['search']."%' OR category like '%".$_REQUEST['search']."%')";
  unset($_GET{'page'});
  $_SESSION['search']=$_REQUEST['search'];
  
}
if(isset($_SESSION['search']))
{    
$cond.="and (code like '%".$_SESSION['search']."%' OR name like '%".$_SESSION['search']."%' OR category like '%".$_SESSION['search']."%')";
	  $count_query = "SELECT COUNT(id) FROM item_master where 1=1 ".$cond."";

	$retval =DB_query( $count_query, $db );
	$row = DB_fetch_array($retval);
	$rec_count = $row[0];
	
	$_SESSION['rec_count'] = $rec_count;
	
	
}else{
	  $count_query = "SELECT COUNT(id) FROM item_master where 1=1 ".$cond."";

	$retval =DB_query( $count_query, $db );
	$row = DB_fetch_array($retval);
	$rec_count = $row[0];
	 unset($_SESSION['search']);
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
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Item Details</a></div>
<div >';
if(isset($_REQUEST['msg']))
{
 echo "<div class='success'>".$_REQUEST['msg']."</div>";
}
echo '</div>';
echo '<form name="form" method="post">
<input type="hidden" name="FormID" value="'. $_SESSION['FormID'] .'" />';
echo '<input type="hidden" name="name" id="name" value="'.$valname.'">
<input type="hidden" name="category" id="category" value="'.$valcategory.'">
<input type="hidden" name="code" id="code" value="'.$valcode.'">
<input type="hidden" name="openingval" id="openingval" value="'.$valopeningval.'">
<input type="hidden" name="itemrate" id="itemrate" value="'.$valitemrate.'">';
 


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



 $s="select * from item_master where 1=1 ".$cond." ".$orderby." LIMIT $offset, $rec_limit";
$q=Db_query($s,$db);
$nu=DB_num_rows($q);
if(isset($_REQUEST["search"]) || isset($_SESSION["search"]))
 {
  echo "<div class='searchrecord'>".$rec_count." Record found &nbsp;&nbsp;|&nbsp;&nbsp;<a href='itemdetail.php?op=view'>View all</a> </div>";
  unset($_GET['page']);
  }
 
echo '<div class="tblHeaderRight"><input type="hidden" name="pages" value="-1"><input type="text" name="search" value="'.$_REQUEST['search'].'">&nbsp;<input type="submit" name="go" value="Search"></div>';
echo '<div class="tblHeaderLeft"><h1>Item Details</h1><span class="addrecord"><a href="item_master.php">Add Item</a></span></div><br>';
$data="<table cellpadding='2' cellspacing='1'>
<tr><th><b>S. No.</a></b> ".$codeimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('code');>Code</a></b> ".$codeimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('name');>Name</a></b> ".$nameimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('category');>Category</a></b> ".$categoryimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('openingval');>Item Number</a></b> ".$openingvalimage."</th><th><b><a href='javascript:void(0)' onclick=sorting('itemrate');>Item Rate</a></b> ".$itemrateimage."</th><th>Action</th><tr>";

$i=1;
if(isset($_REQUEST['page']) && $_REQUEST['page']>1)
	{
	  $pp=($_REQUEST['page']*10)+11;
	}
	else if(isset($_REQUEST['page']) && $_REQUEST['page']==0)
	{
	   $pp=11;
	}
	else if(isset($_REQUEST['page']) && $_REQUEST['page']==1)
	{
	  $pp=21;
	}
	else
	{
	  $pp=1;
	}
    $nn=1*($pp);
while($r=Db_fetch_array($q))
{ if(($i%2)==0)
	{
	  $cl="even";
	}
	else
	{
	  $cl="odd";
	}
 $data.="<tr class=".$cl."><td>".$nn."</td><td>".$r['code']."</td><td>".$r['name']."</td><td>".$r['category']."</td><td>".$r['openingval']."</td><td>".round(abs($r['itemrate']))."</td><td><a href='edititem.php?id=".$r['id']."'>Edit</a></td><tr>";
 $i++;
 $nn++;
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