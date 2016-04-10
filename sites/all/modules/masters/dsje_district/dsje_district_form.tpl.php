  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
    <tr class="oddrow">
      <td align="center"><h2>District Entry Form</h2></td>
    </tr>
    
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['state_id']); ?></td>
    </tr> 
 <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['short_code']); ?></td>
    </tr>
  <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['district_name']); ?></td>
    </tr>
    <!--<tr class="oddrow">
      <td><?php print drupal_render($form['remarks']); ?></td>
    </tr>
    <tr class="evenrow">
      <td id="state"><?php print drupal_render($form['status']); ?></td>
    </tr>-->
    <tr class="oddrow">
      <td colspan="2" class="back" align="center"><?php print drupal_render($form); ?></td>
    </tr>
  </table>

