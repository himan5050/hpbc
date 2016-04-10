  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-conatiner">
    <tr class="oddrow">
      <td align="center"><h2>Tehsil Entry Form</h2></td>
    </tr>   
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['state_id']); ?></td>
    </tr>
    <tr class="oddrow">
      <td><div id="city"><?php print drupal_render($form['district_id']); ?></div></td>
    </tr>
    <tr class="evenrow">
      <td><div id="city2"><?php print drupal_render($form['tehsil_name']); ?></div></td>
    </tr>    
<tr class="oddrow">
      <td colspan="2" class="back" align="center"><?php print drupal_render($form); ?></td>
    </tr>
  </table>

