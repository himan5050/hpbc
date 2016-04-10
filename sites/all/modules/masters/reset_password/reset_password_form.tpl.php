
  <table width="100%" cellpadding="1" cellspacing="2" border="0" id="form-container">
    <tr class="oddrow">
      <td align="center"><h2>Change Password</h2></td>
    </tr>
	<tr class="evenrow">
      <td><?php print drupal_render($form['current_password']); ?></td>
    </tr>
    <tr class="oddrow">
      <td><?php print drupal_render($form['password']); ?></td>
    </tr>
    <tr class="evenrow">
      <td><?php print drupal_render($form['confirm_password']); ?></td>
    </tr>
    <tr class="oddrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>

