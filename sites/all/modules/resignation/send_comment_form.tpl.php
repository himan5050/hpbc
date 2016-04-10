<?php 

$doc_id= $form['doc_id']['#value'];
?>

<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="oddrow">
<td align="center"><h2>Comment Form </h2></td>
</tr>
<tr class="evenrow">
        <td><?php print drupal_render($form['query_to']); ?></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['comment']); ?></td>
</tr>


<tr class="evenrow" id="rem">
      <td class="back" align="center"><?php echo l('Back','comment-list/'.$doc_id); print drupal_render($form); ?></td>
</tr>
</table>
