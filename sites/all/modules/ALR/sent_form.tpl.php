<style>
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}

input[type="text"] {
width: 100px;
height: 18px;
margin: 0;
padding: 2px;
vertical-align: middle;
font-family: sans-serif;
font-size: 14px;
border: #BCBCBC 1px solid;
}
.maincoldate{margin-top:12px;}
label {
    display: block;
    float: left;
    font-weight: bold;
    padding-right: 10px;
	margin-top:5px;
	margin-right:50px;
}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>ALR Porcess Form</legend>
    
    <table align="left" class="frmtbl">
    <tr>
    	
    	<td><div class="maincol1"><?php print drupal_render($form['due_date']); ?></div></td>        
        <td><div class="maincol1"><?php print drupal_render($form['documents']); ?></div></td>
        <td align="right"><div style="margin-right:103px;"><?php print drupal_render($form); ?></div></td>
        </tr>
	</table>
	</fieldset>
    </td></tr>
  </table>
</div>