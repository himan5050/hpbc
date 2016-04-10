<?php
//echo '<pre>';
//print_r($form);
$empid= $form['emp_id']['#value'];
?>
<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="oddrow">
<td align="center"><h2> Resignation Form </h2></td>
</tr>
<tr class="evenrow">
        <td><?php print drupal_render($form['resignation_detail']); ?><?php print drupal_render($form['emp_id']); ?></td>
</tr>


<tr class="oddrow">
      <td class="back" align="center"><?php echo l('Back','viewprofile/'.$empid);print drupal_render($form); ?></td>
</tr>
</table>
