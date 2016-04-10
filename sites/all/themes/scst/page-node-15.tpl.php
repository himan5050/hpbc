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
<img src="<?php print $base_path . $directory; ?>/images/logo.png" width="364" height="38" hspace="0" vspace="0" alt="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation" title="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation"/>
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

<div id="headerslider"><img src="<?php print $base_path . $directory; ?>/images/header.png" hspace="0" vspace="0" alt="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation" title="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation"/>
</div>
<!-- top header end here -->
<div id="main-content">

<!-- services block -->

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
<?php print $breadcrumb; ?>
   <div id="main-content-home">  
          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
          <?php if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h2>'; endif; ?>
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>
          <?php print $help; ?>         
          <?php print $content ?>         
          <?php print $feed_icons ?></div>	
          <div class="RightSidebarHome">
		  <div id="latestnews">
<h1>Latest News</h1>
<?php print $latestnews; ?>
</div>
</div>
<!-- footer start here-->
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
<!-- main content end here-->
<?php print $closure ?>
<!-- /layout -->

  
  </body>
</html>
