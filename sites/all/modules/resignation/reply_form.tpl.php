<?php $doc_id= $form['doc_id']['#value'];

?>

<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="evenrow">
<td align="center"><h2>Comment Reply Form </h2></td>
</tr>

<tr class="oddrow">
        <td><?php print drupal_render($form['comment']); ?></td>
</tr>


<tr class="evenrow" id="rem">
      <td class="back" align="center"><?php echo l('Back','pending-comment-list/'.$doc_id); print drupal_render($form); ?></td>
</tr>
</table>
