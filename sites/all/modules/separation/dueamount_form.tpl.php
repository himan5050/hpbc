<?php
//echo '<pre>';
//print_r($form);
//exit;
?>
<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="evenrow">
        <td align="left"><h2>Due Form</h2></td>
</tr>

<tr class="oddrow" >
        <td><?php print drupal_render($form['particulars']); ?></td>
</tr>
<tr class="evenrow" >
        <td><?php print drupal_render($form['amount_due']); ?></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['remarks']); ?></td>
</tr>

<tr class="evenrow">
      <td class="back" align="center"><?php echo l(t('Back'), 'separationlist'); print drupal_render($form); ?></td>
</tr>
</table>
