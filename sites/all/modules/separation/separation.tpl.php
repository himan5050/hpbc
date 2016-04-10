<?php
//echo '<pre>';
//print_r($form);
//exit;
?>
<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="evenrow">
<td align="center"><h2> Separation Entry Form </h2></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['emp_id']); ?></td>
</tr>
<tr><td><div id="rempdetail"></div></td></tr>
<tr class="evenrow">
        <td><?php print drupal_render($form['separation_type']); ?></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['separation_detail']); ?></td>
</tr>
<tr class="evenrow" id="rem">
      <td class="back" align="center"><?php echo l(t('Back'), 'separationlist'); print drupal_render($form); ?></td>
</tr>
</table>
