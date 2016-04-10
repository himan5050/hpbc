<?php

/**
 * Sets the body-tag class attribute.
 *
 * Adds 'sidebar-left', 'sidebar-right' or 'sidebars' classes as needed.
 */

function phptemplate_body_class($left, $right) {
  if ($left != '' && $right != '') {
    $class = 'sidebars';
  }
  else {
    if ($left != '') {
      $class = 'sidebar-left';
    }
    if ($right != '') {
      $class = 'sidebar-right';
    }
  }

  if (isset($class)) {
    print ' class="'. $class .'"';
  }
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 
function phptemplate_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    return '<div class="breadcrumb">'. implode(' › ', $breadcrumb) .'</div>';
  }
}
*/

function phptemplate_breadcrumb($breadcrumb) {
     
	 if (!empty($breadcrumb)) {
	    $nodec = node_load(arg(1));
		//print_r($nodec);exit;
		/*
		 page
scheme_name
application_forms
list_scst
story
latestnews
welcome
scheduled
cast
		
$breadcrumb = array();
    $breadcrumb[] = l('Home', '<front>');
   
    if($array[0] == '' ) {
     $breadcrumb[] = l('List of Document', 'documentlist'.$array[2].'');
	 }  
	 drupal_set_breadcrumb($breadcrumb);

*/
//echo arg(0);exit;

//echo '<pre>';
//print_r($nodec);
//exit;


if(arg(1) == 1045){
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l($nodec->title, 'node/'.arg(1));
	return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';	
	}
if($nodec->type == 'story'){
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('Latest News', 'latestnews_detail_page');
	$breadcrumb[] = l($nodec->title, 'node/'.arg(1));
	return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';	
	}
	if($nodec->type == 'event'){
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('Event Details', 'event_details');
	$breadcrumb[] = l($nodec->title, 'node/'.arg(1));
	return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';	
	}

if(arg(2)=='revisions' && arg(3)==''){
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Document', 'documentlist');
	$breadcrumb[] = l('Document Revisions', 'node/'.arg(1).'/revisions');
	return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
}

if($nodec->type == 'documentUpload' && arg(2) == "" && is_numeric(arg(1))){
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Document', 'documentlist');
	$breadcrumb[] = l('View Document ', 'node/'.arg(1));
	return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
}


if(arg(2)=='revisions' && arg(4)=='revert'){
	
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Document', 'documentlist');
	$breadcrumb[] = l('Document Revisions', 'node/'.arg(1).'/revisions');
	$breadcrumb[] = l('Document Revert', 'node/'.arg(1).'/revisions/'.arg(3).'/revert');
	return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
}
if(arg(2)=='revisions' && arg(4)=='delete'){
		
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Document', 'documentlist');
	$breadcrumb[] = l('Document Revisions', 'node/'.arg(1).'/revisions');
	$breadcrumb[] = l('Delete', 'node/'.arg(1).'/revisions/'.arg(3).'/delete');
	return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
}

		if(arg(0) == 'success_story'){
			$breadcrumb = array();
			$breadcrumb[] = l('Home', '<front>');
			$breadcrumb[] = l('Success Story', 'success_story');
			return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
		}
		
		if(arg(0) == 'latestnews_detail_page'){
			$breadcrumb = array();
			$breadcrumb[] = l('Home', '<front>');
			$breadcrumb[] = l('Latest News', 'latestnews_detail_page');
			return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
		}
		if(arg(0) == 'welcome'){
			$breadcrumb = array();
			$breadcrumb[] = l('Home', '<front>');
			$breadcrumb[] = l('Welcome', 'welcome');
			return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
		}
	if(arg(0) == 'event_details'){
			$breadcrumb = array();
			$breadcrumb[] = l('Home', '<front>');
			$breadcrumb[] = l('Event Details', 'event_details');
			return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
		}
		if($nodec->type == 'page' || $nodec->type == 'story' || $nodec->type == 'scheme_name' || $nodec->type == 'application_forms' || $nodec->type == 'list_scst' || $nodec->type == 'story' || $nodec->type == 'latestnews' || $nodec->type == 'welcome' || $nodec->type == 'scheduled'|| $nodec->type == 'cast'){
          $breadcrumb[] = drupal_get_title();
		}
        return '<div class="breadcrumb">'. implode(' » ', $breadcrumb) .'</div>';
    }
}
/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {
  $vars['tabs2'] = menu_secondary_local_tasks();
  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
  
  
  $val = arg(1);
  $nodeb = node_load($val);
 // drupal_set_message($vars['template_files'][1]);
//latestnews_detail_page 
	  if($vars['template_files'][0] == 'page-search-content-list'){
		  $vars['template_files'] == 'page-search-content-list';
	  }
	  
	  if($vars['template_files'][0] == 'latestnews_detail_page' ){
		  $vars['template_files'] == 'latestnews_detail_page';
	  }//
	  if($vars['template_files'][0] == 'page-latestnews_detail_page'){
		  $vars['template_files'] == 'page-latestnews_detail_page';
		  }
	  if($vars['template_files'][1] == 'page-success_story'){
		  $vars['template_files'] == 'page-success_story';
	 }
	 if($vars['template_files'][1] == 'page-event_details'){
		  $vars['template_files'] == 'page-event_details';
	 }
	
	if($vars['template_files'][4] == 'page-node-revisions-revert'){
		  $vars['template_files'] == 'page-revisions-revert';
	}	
	
	if($vars['template_files'][4] == 'page-node-revisions-delete'){
		  $vars['template_files'] == 'page-revisions-delete';
	}
	
	//if($vars['template_files'][0] == 'page-contact'){
		 // $vars['template_files'] == 'page-contact';
	//}	
		
//page-contact
//if(arg(2)=='revisions' && arg(4)=='revert'){

}

/**
 * Add a "Comments" heading above comments except on forum pages.
 */
function garland_preprocess_comment_wrapper(&$vars) {
  if ($vars['content'] && $vars['node']->type != 'forum') {
    $vars['content'] = '<h2 class="comments">'. t('Comments') .'</h2>'.  $vars['content'];
  }
}

/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}

/**
 * Returns the themed submitted-by string for the comment.
 */
function phptemplate_comment_submitted($comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

/**
 * Returns the themed submitted-by string for the node.
 */
function phptemplate_node_submitted($node) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function phptemplate_get_ie_styles() {
  global $language;

  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie.css" />';
  if ($language->direction == LANGUAGE_RTL) {
    $iecss .= '<style type="text/css" media="all">@import "'. base_path() . path_to_theme() .'/fix-ie-rtl.css";</style>';
  }

  return $iecss;
}


function scst_theme() {
return array(
		'contact_mail_page' => array(
					'arguments' => array('form' => NULL),
					'template' => 'contact-mail',
					),
		);
}