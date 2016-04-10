<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>RTI Form</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>
<script type="text/javascript" src="calendar.js"></script>
<script type="text/javascript" src="jquery-1.6.2.js"></script>
<script type="text/javascript" src="selectiondrop.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){
$(".flip").click(function(){
    $(".panel").slideToggle("slow");
  });
});
$(document).ready(function(){
$(".flip2").click(function(){
    $(".panel2").slideToggle("slow");
  });
});
$(document).ready(function(){
$(".flip3").click(function(){
    $(".panel3").slideToggle("slow");
  });
});
</script>
<style type="text/css">

<link rel="stylesheet" type="text/css" href="theme.css" />

div.panel,p.flip
{
margin:0px;
padding:0px 400px 250px 0px;
text-align:left;
background:#e5eecc;
border:solid 1px #c3c3c3;
}
div.panel
{
height:120px;
display:none;
}
</style>
</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1><a>Untitled Form</a></h1>
		<form id="form_235795" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>RTI Form</h2>
			<p>will have the following information:</p>
		</div>						
			<ul ><table width="600" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td><ul >
      <li id="li_3" style="width:200px;" >
	  <?php print drupal_render($form['field_department']); ?>
        <!--<label class="description" for="label2">Department</label>
        <div>
          <select class="element select medium" id="element_3" name="element_4">
            <option value="" selected="selected"></option>
            <option value="1" >BPL</option>
            <option value="2" >DBPL</option>
            <option value="3" >IRDP</option>
            <option value="4" >General</option>
          </select>
        </div>-->
      </li>
    </ul>    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_10" style="width:200px;" >
	  <?php print drupal_render($form['field_application_type']); ?>
       <!-- <label class="description" for="label2">Application Type</label>
        <div>
          <select class="element select medium" id="element_5" name="element_7">
            <option value="" selected="selected"></option>
            <option value="1" >BPL</option>
            <option value="2" >DBPL</option>
            <option value="3" >IRDP</option>
            <option value="4" >General</option>
          </select>
        </div>-->
      </li>
    </ul>    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_1" style="width:200px;" >
	  <?php print drupal_render($form['field_district']); ?>
        <!--<label class="description" for="element_1">District</label>
        <div>
          <select class="element select medium" id="element_" name="element_">
            <option value="" selected="selected"></option>
            <option value="1" >BPL</option>
            <option value="2" >DBPL</option>
            <option value="3" >IRDP</option>
            <option value="4" >General</option>
          </select>
        </div>-->
      </li>
    </ul>    </td>
    <td><ul >
      <li id="li_2" style="width:200px;" >
	  <?php print drupal_render($form['field_office']); ?>
        <!--<label class="description" for="element_2">Office</label>
        <div>
          <input id="element_2" name="element_2" class="element text medium" type="text" maxlength="255" value="Master"/>
        </div>-->
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td colspan="2"><li class="section_break"></li></td>
    </tr>
  <tr>
    <td><ul >
      <li id="li_4" style="width:200px;" >
	  <?php print drupal_render($form['field_applicant_name']); ?>
        <!--<label class="description" for="label">Application Name</label>
        <div>
          <input id="label" name="element_3" class="element text medium" type="text" maxlength="255" value=""/>
        </div>-->
      </li>
    </ul>    </td>
    <td><ul >
      <li id="li_5" style="width:200px;" >
	  <?php print drupal_render($form['field_applicant_category']); ?>
        <!--<label class="description" for="label2">Application Category</label>
        <div>
          <select class="element select medium" id="element_4" name="element_5">
            <option value="" selected="selected"></option>
            <option value="1" >BPL</option>
            <option value="2" >DBPL</option>
            <option value="3" >IRDP</option>
            <option value="4" >General</option>
          </select>
        </div>-->
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td colspan="2"><ul >
      <li class="section_break">
	  
        <h3 class="flip"><strong>&gt; Application Details</strong></h3>
        <p></p>
        <div class="panel" style="width:175px;">
          <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="329" style="width:200px;">&nbsp;</td>
            </tr>
            <tr>
              <td style="width:200px;"><ul >
                  <li id="li_12" style="width:560px;">
				  <?php print drupal_render($form['field_remarks']); ?>
                    <!--<label class="description" for="element_12">Remarks </label>
                    <div>
                      <input id="element_12" name="element_12" class="element text large" type="text" maxlength="255" value="" style="height:250px; width:500px;"/>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
          </table>
        </div>
      </li>
    </ul>    </td>
    </tr>
  <tr>
    <td colspan="2"><ul >
      <li class="section_break">
        <h3 class="flip3"><strong>&gt; Permanent Address</strong></h3>
        <p></p>
        <div class="panel3" style="width:30px; height:180px;">
          <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="258" style="width:200px;"><ul >
                  <li id="li_32" style="width:200px;" >
				  <?php print drupal_render($form['field_p_type']); ?>
                   <!-- <label class="description" for="element_32">Type </label>
                    <div>
                      <select class="element select medium" id="element_32" name="element_32">
                        <option value="" selected="selected"></option>
                        <option value="1" >Rural</option>
                        <option value="2" >Urban</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
              <td width="329" style="width:200px;">&nbsp;</td>
            </tr>
            <tr>
              <td style="width:200px;"><ul >
                  <li id="li_14" style="width:200px;">
				  <?php print drupal_render($form['field_p_address_line1']); ?>
                    <!--<label class="description" for="label7">Address Line 1 </label>
                    <div>
                      <input id="label7" name="element_9" class="element text large" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_15" style="width:200px;">
				  <?php print drupal_render($form['field_p_address_line2']); ?>
                   <!-- <label class="description" for="element_13">Address Line 2 </label>
                    <div>
                      <input id="element_13" name="element_13" class="element text large" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td style="width:200px;"><ul >
                  <li id="li_33" name="li_33" style="width:200px;">
				  <?php print drupal_render($form['field_p_village']); ?>
                    <!--<label class="description" for="element_33">Village </label>
                    <div>
                      <select class="element select medium" id="element_33" name="element_33">
                        <option value="" selected="selected"></option>
                        <option value="1" >Data From Master</option>
                        <option value="2" >Second option</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_16" style="width:200px;">
				  <?php print drupal_render($form['field_p_post_office']); ?>
                    <!--<label class="description" for="element_14">PO Office </label>
                    <div>
                      <input id="element_14" name="element_14" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td style="width:200px;"><ul >
                  <li id="li_34" style="width:200px;">
				  <?php print drupal_render($form['field_p_district']); ?>
                    <!--<label class="description" for="element_34">District </label>
                    <div>
                      <select class="element select medium" id="element_34" name="element_34">
                        <option value="" selected="selected"></option>
                        <option value="1" >Data From Master</option>
                        <option value="2" >Second option</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_35" style="width:200px;">
				  <?php print drupal_render($form['field_p_tehsil']); ?>
                    <!--<label class="description" for="element_35">Tehsil </label>
                    <div>
                      <select class="element select medium" id="element_35" name="element_35">
                        <option value="" selected="selected"></option>
                        <option value="1" >Data</option>
                        <option value="2" >Second option</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td><ul >
                  <li id="li_17" style="width:200px;">
				  <?php print drupal_render($form['field_p_block']); ?>
                    <!--<label class="description" for="element_15">Block </label>
                    <div>
                      <input id="element_15" name="element_15" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_18" style="width:200px;">
				  <?php print drupal_render($form['field_p_panchayat']); ?>
                    <!--<label class="description" for="element_16">Panchayat </label>
                    <div>
                      <input id="element_16" name="element_16" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td><ul >
                  <li id="li_19" style="width:200px;">
				  <?php print drupal_render($form['field_p_pin_code']); ?>
                    <!--<label class="description" for="element_17">PIN Code </label>
                    <div>
                      <input id="element_17" name="element_17" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div>
      </li>
    </ul>    </td>
    </tr>
  <tr>
    <td colspan="2"><ul >
      <li class="section_break">
        <h3 class="flip2"><strong>&gt;     
          Correspondence Address</strong></h3>
        <p></p>
        <div class="panel2" style="width:30px; height:165px;">
          <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr>
              <td width="258" style="width:200px;"><ul >
                  <li id="li_6" style="width:200px;" >
				  <?php print drupal_render($form['field_c_type']); ?>
                    <!--<label class="description" for="label6">Type </label>
                    <div>
                      <select class="element select medium" id="label6" name="element_6">
                        <option value="" selected="selected"></option>
                        <option value="1" >Rural</option>
                        <option value="2" >Urban</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
              <td width="329" style="width:200px;">&nbsp;</td>
            </tr>
            <tr>
              <td style="width:200px;"><ul >
                  <li id="li_20" style="width:200px;">
				  <?php print drupal_render($form['field_c_address_line1']); ?>
                   <!-- <label class="description" for="label8">Address Line 1 </label>
                    <div>
                      <input id="label8" name="label4" class="element text large" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_21" style="width:200px;">
				  <?php print drupal_render($form['field_c_address_line2']); ?>
                   <!-- <label class="description" for="label9">Address Line 2 </label>
                    <div>
                      <input id="label9" name="label4" class="element text large" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td style="width:200px;"><ul >
                  <li id="li_25" name="li_33" style="width:200px;">
				  <?php print drupal_render($form['field_c_village']); ?>
                   <!-- <label class="description" for="label10">Village </label>
                    <div>
                      <select class="element select medium" id="label10" name="element_6">
                        <option value="" selected="selected"></option>
                        <option value="1" >Data From Master</option>
                        <option value="2" >Second option</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_26" style="width:200px;">
				  <?php print drupal_render($form['field_c_post_office']); ?>
                   <!-- <label class="description" for="label11">PO Office </label>
                    <div>
                      <input id="label11" name="label4" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td style="width:200px;"><ul >
                  <li id="li_28" style="width:200px;">
				  <?php print drupal_render($form['field_c_district']); ?>
                  <!--  <label class="description" for="label12">District </label>
                    <div>
                      <select class="element select medium" id="label12" name="element_6">
                        <option value="" selected="selected"></option>
                        <option value="1" >Data From Master</option>
                        <option value="2" >Second option</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_29" style="width:200px;">
				  <?php print drupal_render($form['field_c_tehsil']); ?>
                    <!--<label class="description" for="label13">Tehsil </label>
                    <div>
                      <select class="element select medium" id="label13" name="element_6">
                        <option value="" selected="selected"></option>
                        <option value="1" >Data</option>
                        <option value="2" >Second option</option>
                      </select>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td><ul >
                  <li id="li_30" style="width:200px;">
				  <?php print drupal_render($form['field_c_block']); ?>
                    <!--<label class="description" for="label14">Block </label>
                    <div>
                      <input id="label14" name="label4" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
              <td style="width:200px;"><ul >
                  <li id="li_31" style="width:200px;">
				  <?php print drupal_render($form['field_c_panchayat']); ?>
                   <!-- <label class="description" for="label15">Panchayat </label>
                    <div>
                      <input id="label15" name="label4" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
            </tr>
            <tr>
              <td><ul >
                  <li id="li_36" style="width:200px;">
				  <?php print drupal_render($form['field_c_pin_code']); ?>
                    <!--<label class="description" for="label16">PIN Code </label>
                    <div>
                      <input id="label16" name="label4" class="element text medium" type="text" maxlength="255" value=""/>
                    </div>-->
                  </li>
              </ul></td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div>
      </li>
    </ul>    </td>
    </tr>
  <tr>
    <td><ul >
      <li id="li_7" style="width:200px;" >
	  <?php print drupal_render($form['field_telephone']); ?>
        <!--<label class="description" for="label3">Telephone No.</label>
        <div>
          <input id="label3" name="label2" class="element text medium" type="text" maxlength="255" value=""/>
        </div>-->
      </li>
    </ul>    </td>
    <td><ul >
      <li id="li_8" style="width:200px;" >
	  <?php print drupal_render($form['field_mobile']); ?>
        <!--<label class="description" for="label17">Mobile No..</label>
        <div>
          <input id="label17" name="label5" class="element text medium" type="text" maxlength="255" value=""/>
        </div>-->
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_9" style="width:200px;" >
	  <?php print drupal_render($form['field_email']); ?>
        <!--<label class="description" for="label18">Employee Email Address</label>
        <div>
          <input id="label18" name="label6" class="element text medium" type="text" maxlength="255" value="" style="width:200px;"/>
        </div>-->
      </li>
    </ul>    </td>
    <td><ul >
      <li id="li_11" style="width:200px;" >
	  <?php print drupal_render($form['field_complaint_type']); ?>
        <!--<label class="description" for="label2">Type of Complaint</label>
        <div>
          <select class="element select medium" id="element_6" name="element_8">
            <option value="" selected="selected"></option>
            <option value="1" >Information</option>
            <option value="2" >Inspection</option>
          </select>
        </div>-->
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_13" style="width:200px;" >
	  <?php print drupal_render($form['field_bpl']); ?>
       <!-- <label class="description" for="label2">If Applicant is BPL?</label>
   <div class="radio">
          <label for="upload_file"><input type="radio" name="yes" value="Yes" rel="upload_file" />Yes</label>
          <label for=""><input type="radio" name="Yes" value="No" rel="none" id="none" />No</label>
     </div>-->
      <table>
        <tr rel="upload_file">
            <td class="question"><label for="upload_file"></label></td>
          <td>Certificate of BPL : <input type="file" name="datafile" size="20" value="" /></td>
        </tr>
      </table>
      </p>
    </li>
      </ul>
    </td>
    <td>&nbsp;</td>
  </tr>
</table>	
			  <li class="buttons">
			  
			   <?php print drupal_render($form); ?>
		</li>
			</ul>
	  </form>	
		<div id="footer"></div>
</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>