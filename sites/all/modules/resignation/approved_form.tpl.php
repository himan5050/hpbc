<?php
//echo '<pre>';
//print_r($form);
//$empid= $form['emp_id']['#value'];
?>
<table cellspacing='2' cellpadding='1' border='0' id='form-container'>
<tr class="evenrow">
        <td colspan="2"><?php print drupal_render($form['approved_detail']); ?><?php print drupal_render($form['emp_id']); ?><?php print drupal_render($form['doc_id']); ?></td>
</tr>


<tr class="oddrow" id="rem">
      <td class="back" align="center" colspan="2"><input type="button" class="form-submit" value="Back" id="back" onclick="history.back(-1);"/><?php print drupal_render($form); ?></td>
</tr>

</table>