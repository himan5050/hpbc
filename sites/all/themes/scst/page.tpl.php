<?php
error_reporting(0);

global $user, $base_url,$language;


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
  <?php
		   $nodec = node_load(arg(1));
		  	if($nodec->type == 'page' || $nodec->type == 'scheme_name' || $nodec->type == 'application_forms' || $nodec->type == 'list_scst' || $nodec->type == 'story' || $nodec->type == 'latestnews' || $nodec->type == 'welcome' || $nodec->type == 'scheduled'|| $nodec->type == 'cast'){
			echo '<style type="text/css">.field-label{display:none;}</style>';
			
			}else{
			  $title = "";
			  echo '<style type="text/css">ul.primary li a {display:none; border:none;}ul.primary {
    border: 0;
    border-collapse: collapse;
    height: auto;
    line-height: normal;
    list-style: none outside none;
    margin: 5px;
    padding: 0 0 0 1em;
    white-space: nowrap;
}</style>';
			}
			?>


<?php
$uidd=$user->uid;
if($uidd == 1){
}else{
echo '<style type="text/css">.tabs{display:none;}</style>';		
}
?> 

    <?php print $head ?>
    <?php 
		if(arg(0) != 'loan')
		{
			$_SESSION['sstatus'] = '';
			unset($_SESSION['sstatus']);
		}
	  //drupal_set_message(arg(0) .'-'. arg(1).$_GET['q']);
	  if($_GET['q'] == 'loan/quarterly_progress'){
		  ?>
          <title><?php print 'Quaterly Progress Report';  ?></title>
          <?php
	  }else	if($_GET['q'] == 'loan/repaymentform/onetime')
	  {?>
          <title><?php print 'One Time Settlement Form';  ?></title>
          <?php
	  }else{
	?>
    
    <title><?php print $head_title; } ?></title>
    
    
    
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
    <!--[if IE 7]>
    	<style type="text/css" media="all">@import "<?php print $base_path . path_to_theme() ?>/css/ie7.css";</style>
    <![endif]-->
    
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/style.css" type="text/css" />
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
 <?php
		   $nodec = node_load(arg(1));
		  	if($nodec->type == 'page' || $nodec->type == 'scheme_name' || $nodec->type == 'application_forms' || $nodec->type == 'list_scst' || $nodec->type == 'story' || $nodec->type == 'latestnews' || $nodec->type == 'welcome' || $nodec->type == 'scheduled'|| $nodec->type == 'cast'){
				?>
<div id="headerslider"><img src="<?php print $base_path . $directory; ?>/images/header.png" hspace="0" vspace="0" alt="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation" title="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation"/>
</div>
<?php } ?>
   <?php 
     $nid =  arg(1);
		    $cnode = node_load($nid);
			 
		  if(($cnode->type=='latestnews' && arg(2) == "") || (arg(0) == 'emi_cal') || ($cnode->type=='story' && arg(2) == "") || ($cnode->type=='event' && arg(2) == "")  || arg(1) == '1155'){
		  	?>
          
<?php 
		  } ?>
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

<?php 
  if(arg(0) == 'emi_cal1'){
?>
<!-- services block -->

<!-- top header end here-->


<?php 
  }
?>

<?php 
 if(arg(0) == 'successstory'){
echo '<div class="breadcrumb">
<a href="/hpbc/">Home</a>
»
<a class="active" href="/hpbc/successstory"> Success Story</a>
</div>'  ;	 
	 
 }else{

print $breadcrumb; 

 }?>

   	  
		  <?php
		   ///for news page
		      $nid =  arg(1);
		    $cnode = node_load($nid);
			 
		  if((($cnode->type=='latestnews' && arg(2) == "" && arg(0) == 'node') || (arg(0) == 'emi_cal') || ($cnode->type=='story' && arg(2) == "" && arg(0) == 'node') || ($cnode->type=='event' && arg(2) == "") || arg(1) == '1155' && arg(0) == 'node') ){
			
			  ?>
                <div id="main-content-home">   
   
          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
		
          <?php 
		  $nid =  arg(1);
		    $cnode = node_load($nid);
		  if(arg(1)){
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title.'</h2>';
		  }else{
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title.'</h2>';
		  }
		 // if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title; .'</h2>'; endif; ?>
		  
		  
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>
          <?php print $help; ?>         
          <?php
	      echo '<div id="content-news">';
			  print $content;
			  echo '</div>';
		  	   ?> 
          <?php print $feed_icons ?>
              <?php
		
		    echo '</div><div id="latest-news">';
			echo '<div id="lastest-news-block"><div class="RightSidebarHome">';
			//echo '<div id="emicalpagenews">';
			echo '<div id="latestnews">';
		  
			    print $latestnews;
			  echo '</div></div></div>';
			  
			
			 			  
			  echo '</div>';
			
		  }else{
			  
			  ?>
             
          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
		
          <?php 
		  $nid =  arg(1);
		    $cnode = node_load($nid);
		  if(arg(1) && arg(0) == 'node'){
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title.'</h2>';
		  }else{
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title.'</h2>';
		   }
		 // if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title; .'</h2>'; endif; ?>
		  
		  
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>
          <?php print $help; ?>         
          
	     
		  	    
          <?php print $feed_icons ?>
              <?php
		    print $content.'</div>';
			
		  }
		  
		  ?>

		  
<!-- footer start here-->

<div class="clrpagetpl"></div>
<?php
  if( (arg(0)  == 'hi' && arg(1) == 'emi_cal') || arg(0)  == 'emi_cal'){
	  ?>
      
      

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
      <?php
	  }else{
 ?>

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

<?php 


	  }
?>

</div>
<!-- main content end here-->
<?php print $closure ?>
<!-- /layout -->

  
  </body>
</html>
