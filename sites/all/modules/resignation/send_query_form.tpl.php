
<table cellspacing='2' cellpadding='1' border='0' id='form-container'>

<tr class="evenrow">
        <td><h2>Write Your Query</h2></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['query']); ?></td>
</tr>


<tr class="evenrow" id="rem">
      <td class="back" align="center"><?php echo l('Back','resignationlist/');print drupal_render($form); ?></td>
</tr>

</table>