<?php 
//echo '<pre>';
//print_r($form);
//exit;
?>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="contactfeedback">
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['name']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" id="contactpage"><?php print drupal_render($form['mail']); ?></td>
</tr>
 <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['subject']); ?></td>
    </tr>
    <tr class="oddrow">
      <td id="contactpage"><?php print drupal_render($form['message']); ?></td>
    </tr>
	  <tr class="evenrow">
      <td><?php print drupal_render($form['copy']); ?></td>
 </tr>
<tr class="oddrow">
	<td align="center" class="back"><?php print drupal_render($form); ?></td>
</tr></table>
