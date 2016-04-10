<?php
//echo '<pre>';
//print_r($form);
$doc_id= $form['doc_id']['#value'];
$sql = "select emp_id from tbl_resignation where doc_id='".$doc_id."'";
$res = db_query($sql);
$rs = db_fetch_object($res);
$emp_id = $rs->emp_id;

?>
<table cellspacing='2' cellpadding='1' border='0' id='form-container'>

<tr class="evenrow">
        <td><?php print drupal_render($form['comment']); ?></td>
</tr>

<tr class="oddrow">
      <td class="back" align="center" colspan="2"><?php echo l('Back','viewprofile/'.$emp_id); print drupal_render($form); ?></td>
</tr>

</table>