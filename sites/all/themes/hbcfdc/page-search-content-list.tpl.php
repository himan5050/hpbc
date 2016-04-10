<?php
global $user, $base_url,$language;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/style.css" type="text/css"/>
  </head>
  <body class="homebody">
<!-- top header -->
<div class="main-container">
<?php print $header; ?>
<div id="header">
<div class="hindi"><?php print $lbox; ?> </div>
<img src="<?php print $base_path . $directory; ?>/images/hpscst.png" width="564" height="51" hspace="0" vspace="0" alt="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation" title="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation"/>
<div class="menu-block">
<div class="top-search middle">
         <?php print $searchblock; ?>
</div>

 <?php if (isset($primary_links)) : ?>
          <?php print theme('links', $primary_links, array('class' => 'topmenu')) ?>
        <?php endif; ?>

</div>
</div>
<!-- top header end here -->

<!-- top header -->

<div id="headerslider"><img src="<?php print $base_path . $directory; ?>/images/slide1.jpg" width="934" height="194" hspace="0" vspace="0" alt="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation" title="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation"/>
</div>

<!-- services block -->
<div id="servicesblocks" style="margin-bottom:-25px;">
<?php global $base_url; ?>

<div id="block-block-1" class="block">
<?php print $applicationforms; ?>
<?php if($language->language == 'hi') {?>
<a href="<?php print $base_url; ?>/hi/success_story" class="more">More</a>
<?php } else {?>
<a href="<?php print $base_url; ?>/success_story" class="more">More</a>
<?php }?>
</div>
<div id="block-block-2" class="block">
<?php print $newschemes; ?>
<?php if($language->language == 'hi') {?>
<a href="<?php print $base_url; ?>/hi/our-schemes" class="more">More</a>
<?php } else{?>
<a href="<?php print $base_url; ?>/our-schemes" class="more">More</a>
<?php } ?>
</div>
<div id="block-block-3" class="block">
<?php print $casts; ?>
<?php if($language->language == 'hi') {?>
<a href="<?php print $base_url; ?>/hi/essential-links" class="more">More</a>
<?php } else{?>
<a href="<?php print $base_url; ?>/essential-links" class="more">More</a>
<?php } ?>
</div>
<div id="block-block-4" class="block">
<?php print $listofscst; ?>
<?php if($language->language == 'hi') {?>
<a href="<?php print $base_url; ?>/hi/list-of-scheduled-castes" class="more">More</a>
<?php } else{?>
<a href="<?php print $base_url; ?>/list-of-scheduled-castes" class="more">More</a>
<?php } ?>
</div>
</div>
<!-- top header end here-->

<!-- main content start here-->
<div id="main-content">
<div class="adminpanel">
<?php
  $username = $user->name;

if($language->language == 'hi'){
$node211 = $base_url.'/hi/welcome-deshboard';
$logout=$base_url.'/hi/logout';
$change_password = $base_url.'/hi/scst/resetpassword';
}else{
$node211 = $base_url.'/welcome-deshboard';
$logout=$base_url.'/logout';
$change_password = $base_url.'/scst/resetpassword';
}
if ($user->uid != 0) {
  // code for the logout button
  echo ''.'Hi&nbsp;'.$username.'!&nbsp; | &nbsp;'."<a href='$node211'>Dashboard</a>&nbsp; | &nbsp;";
  echo "<a href=' $logout'>Log out</a>&nbsp; | &nbsp;";
 echo "<a href=' $change_password'>Change Password</a>";
}
else {
 //cho "<a href=\"?q=user\">Login</a>";
}
?>
</div>
<div class="RightSidebarHome">
<div id="login">
<?php print $login; ?>
<div class="clr"></div>
</div>
<div id="latestnews">
<h1>Latest News</h1>
<?php print $latestnews; ?>
</div>
</div>
<div id="main-content-home">
<?php print $content; ?>
</div>

<!-- footer start here-->
<div id="footer">

</div>
<!-- footer end-->
</div>
</div>
<!-- main content end here-->
<?php print $closure ?>
</body>
</html>
<?php
session_start();
//echo $_SESSION['usnam'];
 $pgtime= $_REQUEST['times'];
 
 if(isset($pgtime))
 {
   $pgtime=$pgtime;
   $useri=0;
 }
 else
 {
   $pgtime=0;
   $useri=$user->uid;
 }
 $useri=$user->uid;
if(isset($_COOKIE['ersess']))
 {
    $cook= $_COOKIE['ersess'];
 }
 else
 {
   $cook=0;
 }
 //if(isset($_COOKIE['ersess']))
 
//$file = fopen("/hp-shimla/ersession.txt","r");
//$seval=fread($file,"10");
//fclose($file);
if( $_SESSION['usnam']!='' && $cook!=1)
   { 
    $_SESSION['cou']=0;
   combineuserlogin($_SESSION['usnam'],$_SESSION['uspass']);
  }
// $Id: page.tpl.php,v 1.18.2.1 2009/04/30 00:13:31 goba Exp $
?>