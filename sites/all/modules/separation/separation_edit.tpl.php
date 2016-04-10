<?php
//echo '<pre>';
//print_r($_GET['q']);
//exit;
session_start();
$emp_id = $form['empid']['#value'];
?>
<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="evenrow">
        <td align="left"><h2>NOC Detail</h2></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['branchoffice']); ?></td>
</tr>

<tr class="evenrow" >
        <td><?php print drupal_render($form['ufile']); print drupal_render($form['uploadfile']); ?></td>
</tr>


<tr class="oddrow" id="noccss1">
      <td class="back" align="center"><?php echo l(t('Back'), 'noc-detail/'.$emp_id);  print drupal_render($form); ?></td>
</tr>
</table>
