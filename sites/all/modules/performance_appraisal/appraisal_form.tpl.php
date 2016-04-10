<table cellspacing="2" cellpadding="1" border="0" id="form-container">
<tr class="evenrow">
<td align="center"><h2> Appraisal Entry Form </h2></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['employee_id']); ?></td>
</tr>

<tr class="evenrow">
        <td><?php print drupal_render($form['employee_name']); ?></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['officeid']); ?></td>
</tr>

<tr class="evenrow">
        <td><?php print drupal_render($form['designationid']); ?></td>
</tr>

<tr class="oddrow">
        <td><?php print drupal_render($form['Departmentid']); ?></td>
</tr>
<tr class="evenrow">
        <td><?php print drupal_render($form['prev_year_appraisal']); ?></td>
</tr>

<tr class="oddrow">
        <td><?php print drupal_render($form['prev_year_acr']); ?></td>
</tr>
<tr class="evenrow">
        <td><?php print drupal_render($form['prev_year_acr_status']); ?></td>
</tr>

<tr class="oddrow">
        <td><?php print drupal_render($form['appraisal_year']); ?></td>
</tr>

<tr class="evenrow">
        <td><?php print drupal_render($form['appraisal_remark']); ?></td>
</tr>
<tr class="oddrow">
        <td><?php print drupal_render($form['acr_of_appriasal']); ?></td>
</tr>


<tr class="evenrow" id="rem">
      <td class="back" align="center"><?php echo l(t('Back'), 'appraisallist'); print drupal_render($form); ?></td>
</tr>
</table>