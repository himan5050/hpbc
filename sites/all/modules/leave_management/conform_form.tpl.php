<?php
//echo '<pre>';
//print_r($form);
//$empid= $form['emp_id']['#value'];
?>
<table cellspacing='2' cellpadding='1' border='0' id='form-container'>

<tr class="oddrow">
        <td >Would you like to continue apply the Leave Application Form?</td>
</tr>

<tr class="evenrow" >
      <td class="back" align="center"><input type="button" class="form-submit" value="Cancel" id="back" onclick="history.back(-1);"/><?php print drupal_render($form); ?></td>
</tr>

</table>