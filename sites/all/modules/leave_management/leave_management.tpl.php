<?php
//echo '<pre>';
//print_r($form);
//$empid= $form['emp_id']['#value'];
?>
<table cellspacing='2' cellpadding='1' border='0' id='form-container'>
<tr class="evenrow">
        <td><h2>Leave Entry Form</h2></td>
</tr>
<tr class="oddrow">
        <td ><?php print drupal_render($form['emp_id']); ?></td>
</tr>
<tr class="evenrow">
        <td ><?php print drupal_render($form['emp_name']); ?></td>
</tr>
<tr class="oddrow">
        <td ><?php print drupal_render($form['leave_type']); ?></td>
</tr>
<tr class="evenrow" id="leavetype">
        <td ><?php print drupal_render($form['day_of_leave']); ?></td>
</tr>
<tr class="oddrow" id="fromdatefromclass">
        <td><?php print drupal_render($form['from_date']); ?></td>
</tr>
<tr class="evenrow" id="todatefromclass">
        <td ><?php print drupal_render($form['to_date']); ?></td>
</tr>
<tr class="oddrow">
        <td ><?php print drupal_render($form['no_of_daye']); ?></td>
</tr>
<tr class="evenrow">
        <td ><?php print drupal_render($form['reason']); ?></td>
</tr>


<tr class="oddrow" >
      <td class="back" align="center"><?php echo l('Back','leave-managementlist'); print drupal_render($form); ?></td>
</tr>

</table>