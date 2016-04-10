  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
	<tr>
    	<td align="left" class="tdform-width"><fieldset><legend>Create ALR</legend>
            <table align="left" class="frmtbl">
            <tr>
            	<td><strong>Account No.:</strong> <span title="This field is required." class="form-required">*</span></td>
                <td><div class="maincol1"><?php print drupal_render($form['account']); ?></div></td>
	            <td><strong>Is ALR:</strong> <span title="This field is required." class="form-required">*</span></td>
                <td><div class="maincol"><?php print drupal_render($form['isalr']); ?></div></td>
    	        <td align="right"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td>
            </tr>    
    </table>
	</fieldset>
  </table>
