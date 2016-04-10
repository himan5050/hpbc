<?php
//echo '<pre>';
//print_r($form);
//exit;
?>
<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="oddrow">
<?php if(arg(2) == 'edit'){ ?>
<td align="center"><h2> Document Edit Form </h2></td>
<?php } else { ?>
<td align="center"><h2> Document Entry Form </h2></td>
<?php } ?>
</tr>
<tr class="evenrow">
        <td><?php print drupal_render($form['file_number']); ?></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['file_title']); ?></td>
</tr>
<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['field_documentupload_file']); ?></td>
</tr>
<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['file_discription']); ?></td>
</tr>
<tr class="evenrow">
            
             <td><?php print drupal_render($form['department']); ?></td>
</tr>   
<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['branchoffice']); ?></td>
</tr>
<tr class="evenrow">
            
             <td><?php print drupal_render($form['file_author']); ?></td>
</tr>
<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['expire_date']); ?></td>
</tr>
<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['file_keywords']); ?></td>
</tr>
<tr class="oddrow">            
             <td><?php print drupal_render($form['access_level']); ?></td>
</tr>
<tr class="evenrow">
		<td align="left" class="dgetuser"><?php print drupal_render($form['user_list']); ?></td>
</tr>
<tr class="evenrow">            
             <td class="droleget"><?php print drupal_render($form['role_list']); ?></td>
</tr>   
<tr class="oddrow" id="rem">
      <td class="back" align="center" id="nodriv"><?php echo l(t('Back'), 'documentlist'); print drupal_render($form); ?></td>
</tr>
</table>
