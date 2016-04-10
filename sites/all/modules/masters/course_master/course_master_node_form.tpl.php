<?php
/*echo '<pre>';
print_r($form);
echo '<pre>'; */
?>
<div id="dms-agreement">
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
<tr class="oddrow">
<?php 
  if($form['#parameters'][2]->nid){
	   ?>
	   <td align="center"><h2>REC Offices Edit Form</h2></td>
	   <?php
  }else{
  ?>
	<td align="center"><h2>REC Offices Entry Form</h2></td>
	<?php
  }
 ?>
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['office_name']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['office_abbreviation']); ?></td>
    
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['office_address']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['country_id']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left"><div id="zone"><?php print drupal_render($form['zone_id']); ?></div></td>
    
</tr>
<tr class="oddrow">
	<td align="left"><div id="state"><?php print drupal_render($form['state_id']); ?></div></td>
</tr>
<tr class="evenrow">
	<td align="left"><div id="city"><?php print drupal_render($form['city_id']); ?></div></td>
    
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['office_pincode']); ?></td>
    
</tr>


<tr class="evenrow">
	<td class="evenrow"><table style="width:350px;" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td align="left"><div class="text-alignment">Phone No:<font color="red">*</font></div></td><td style="width:5%;"><?php print drupal_render($form['office_std']); ?></td><td class="form-text-sml"><?php print drupal_render($form['office_phone']); ?></td></tr></table></td>
</tr>

<tr class="oddrow">
	<td align="left">
	<table style="width:350px;" border="0" cellpadding="2" cellspacing="0">
          <tr class="oddrow">
            <td width="33%" align="left"><div class="text-alignment">Mobile No:</div></td>
            <td width="39%">+91-</td>
            <td width="28%" class="form-text-lrg"><?php print drupal_render($form['office_mobile']); ?></td>
          </tr>
      </table>	
	</td>
	</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['office_email']); ?></td>
</tr>


<tr class="oddrow">
	<td>
	<table style="width:350px;" border="0" cellpadding="2" cellspacing="0">
          <tr class="oddrow">
            <td align="left"><div class="text-alignment">Fax No:<font color="red">*</font></div></td>
            <td style="width:5%;"><?php print drupal_render($form['office_fax_std']); ?></td>
            <td class="form-text-sml"><?php print drupal_render($form['office_fax']); ?></td>
          </tr>
      </table></td>
</tr>


<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['office_website']); ?></td>
    
</tr>
<tr class="oddrow">
	<td>
	<table style="width:350px;" border="0" cellpadding="2" cellspacing="0">
          <tr>
            <td 

><div class="text-alignment">Name of HOD:<font 

color="red">*</font></div></td>
            <td width="90"><?php print 

drupal_render($form['nametitle']); ?></td>
            <td class="form-text3"><?php print drupal_render($form['office_hod']); ?></td>
          </tr>
      </table></td>
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['designation_id']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['office_total_staff']); ?></td>
    
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['office_status']); ?></td>
</tr>

<tr>
	<td colspan="2"><?php print drupal_render($form); ?></td>
</tr>
</table>
</div>