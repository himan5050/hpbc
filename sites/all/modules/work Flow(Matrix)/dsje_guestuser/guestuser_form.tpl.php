
  <table width="100%" cellpadding="2" cellspacing="1" border="1" id="form_container">
  <tr class="oddrow">
  <td align="center"><h2>Guest User Registration Form</h2></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['employee_name']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['dob']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['gender']); ?></td>
  </tr>
  
  <tr class="oddrow">
  <td><?php print drupal_render($form['email']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['mobile']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['phone']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['add_type']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['add_line1']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['add_line2']); ?></td>
  </tr>
  <tr class="oddrow">
   <td align="left" class="form-text1"><?php print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="evenrow">
   <td class="form-text1"><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="oddrow">
  <td align="left" class="form-text1"><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>
   <tr class="evenrow">
  <td><?php print drupal_render($form['block']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['pincode']); ?></td>
  </tr>
  
 
  <tr class="evenrow">
  <td><?php print drupal_render($form['logindetails']); ?></td>
  </tr>
   <tr class="oddrow">
  <td align="center"><?php print drupal_render($form); ?></td>
  </tr>   
  </table>