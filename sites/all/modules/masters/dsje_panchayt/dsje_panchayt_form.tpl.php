<div id="form-conatiner">
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper">
    <tr class="oddrow">
      <td align="center"><h2>Panchayat Entry Form</h2></td>
    </tr>
   
    <tr class="evenrow">
   <td align="left"><?php print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="oddrow">
   <td ><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="evenrow">
  <td align="left" ><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>
    <tr class="oddrow">
      <td align="left"><div id="block_test"><?php print drupal_render($form['block_name']); ?></div></td>
    </tr>
	
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['panchayt_name']); ?></td>
    </tr>
   
<tr class="oddrow">
      <td class="back" align="center"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
</div>
