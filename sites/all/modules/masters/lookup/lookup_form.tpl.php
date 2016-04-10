  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
    <tr class="oddrow">
      <td align="center"><h2>Lookup Entry Form</h2></td>
    </tr>
    
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['lookupType_id']); ?></td>
    </tr>
    
  <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['lookup_name']); ?></td>
    </tr>
    <!--<tr class="oddrow">
      <td><?php //print drupal_render($form['remarks']); ?></td>
    </tr>
    <tr class="evenrow">
      <td><div id="state1"><?php print drupal_render($form['status']); ?></div></td>
    </tr>-->
    <tr class="evenrow" >
      <td colspan="2" align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>

