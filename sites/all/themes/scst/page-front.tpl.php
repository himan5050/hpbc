<?php

error_reporting(0);
global $user, $base_url,$language;
//echo '<PRE>';
//print_r($language); exit;

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
  <body class="body">
  <?php print $header; ?>
<!-- top header -->
<div class="main-container">
<div id="top-gradient"></div>
<?php print $header; ?>
<div id="header">

<div id="top">
<div id="top-left">
<div id="top-right">
<div class="hindi"><?php print $lbox; ?> </div>
<img src="<?php print $base_path . $directory; ?>/images/logo.png" width="364" height="38" alt="Himachal Backward Classes Finance And Development Corporation" title="Himachal Backward Classes Finance And Development Corporation"/>
</div>
</div>
</div>
<div class="menu-block">
<div class="top-search middle">
         <?php print $searchblock; ?>
</div>

 <?php if (isset($primary_links)) : ?>
          <?php print theme('links', $primary_links, array('class' => 'menu-nav')) ?>
        <?php endif; ?>

</div>
</div>
<!-- top header end here -->

<!-- top header -->

<div id="headerslider"><img src="<?php print $base_path . $directory; ?>/images/header.png" alt="Himachal Backward Classes Finance & Development Corporation" title="Himachal Backward Classes Finance & Development Corporation"/>
</div>

<!-- top header end here-->

<!-- main content start here-->
<div id="main-content">
<div class="adminpanel">
<?php
  $username = $user->name;
if($language->language == 'hi'){
$node211 = $base_url.'/hi/welcome-deshboard';
$node111 = $base_url.'/hi/loan/applyloan';
$logout=$base_url.'/hi/logout';
$change_password = $base_url.'/hi/scst/resetpassword';
}else{
$node211 = $base_url.'/welcome-deshboard';
$node111 = $base_url.'/loan/applyloan';
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
 // code for the logout button
  echo ''.'Hi&nbsp; Guest !&nbsp; Do You Want Loan? &nbsp;'."<a href='$node111'><b>Apply Here</b></a>&nbsp; &nbsp;";
}
?>
</div>
<div class="RightSidebarHome">
<?php if($login) {?>
<div id="login">
   <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
   <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
  
<?php print $login; ?>

</div>
<?php } ?>
<div id="latestnews">
<?php print $latestnews; ?>
</div>
</div>
<div id="main-content-home">
<?php if ($show_messages && $messages): print $messages; endif; ?>
<?php print $welcome; ?>

</div>

<div class="block1" id="homediv">
 <?php print $scheduled; ?>
 <div class="clr"></div>
</div>
<div class="clr"></div>

</div>
<!-- footer start here-->
 <div id="footer" class="png">
        <div class="footer-right png">
          <div class="footer-bg png">
            <div class="space"><?php print $left; ?></div>
          </div>
        </div>
      </div>
<!-- footer end-->
</div>
</div>
<!-- main content end here-->
<?php print $closure ?>
</body>
</html>
<?php
//echo '<pre>';
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
//$ct = time();
//if($user->uid > 0 && ($user->access == $ct)){
if( $_SESSION['usnam']!='' && $cook!=1)
   { 
    $_SESSION['cou']=0;
	setcookie($_COOKIE['ersess'],1);
   combineuserlogin($_SESSION['usnam'],$_SESSION['uspass']);
  //}
}
// $Id: page.tpl.php,v 1.18.2.1 2009/04/30 00:13:31 goba Exp $
?>