<?php
?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?>">

<?php print $picture ?>

<?php if ($page == 0): ?>
  <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
<?php endif; ?>

  <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted; ?></span>
  <?php endif; ?>

  <div class="content clear-block">
    <?php print $content ?>
  </div>

  <div class="clear-block">
    <div class="meta">
    <?php if ($taxonomy): ?>
      <div class="terms"><?php print $terms ?></div>
    <?php endif;?>
    </div>

    <?php if ($links): ?>
      <div class="links"><?php print $links; ?></div>
    <?php endif; ?>
	
  <?php if ($teaser && user_access('administer nodes')): ?>
  <?php $content_type_name = node_get_types('name', $node); ?>
  <?php $quick_links['quick-edit'] = array('title' => 'Edit ' . $content_type_name, 'href' => 'node/' . $nid . '/edit'); ?>
  <?php $quick_links['quick-delete'] = array('title' => 'Delete ' . $content_type_name, 'href' => 'node/' . $nid . '/delete'); ?>
  <?php $quick_links['quick-view'] = array('title' => 'View' . $content_type_name, 'href' => 'node/' . $nid . '/view'); ?>
  <div class="links quick-edit-links">
    <?php print theme('links', $quick_links, array('class' => 'links inline')); ?>
  </div>
<?php endif; ?>
	
  </div>

</div>
