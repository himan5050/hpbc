<div id="dms-agreement">

<table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">

<tr class="oddrow">

	<td align="center"><h2>Checklist Item Entry Form</h2></td>   

</tr>

<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['checklist_item_name']); ?></td>

</tr>

<tr class="oddrow">

	<td><?php print drupal_render($form['description']); ?></td>

</tr>

<tr class="evenrow">

	<td><?php print drupal_render($form['status']); ?></td>

</tr>
<tr class="oddrow">

	<td align="left"><?php //print drupal_render($form['remarks']); ?></td>

</tr>
<tr>

	<td colspan="2"><?php print drupal_render($form); ?></td>

</tr>



</table>

</div>