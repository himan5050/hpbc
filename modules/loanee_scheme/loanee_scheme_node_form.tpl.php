
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
</script>
<style type="text/css">
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
			<h2>Loan Scheme Form</h2>
			<p>will have the following information:</p>
		</div>						
			<ul ><table width="600" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td width="283"><ul >
      <li id="li_10" style="width:200px;" >
	  <?php print drupal_render($form['field_scheme_name']); ?>
        <!--<label class="description" for="label2">Scheme Name</label>
        <div>
          <input id="label2" name="element_7" class="element text medium" type="text" maxlength="255" value=""/>
        </div>-->
      </li>
    </ul>    </td>
    <td width="310"><ul >
      <li id="li_7" style="width:200px;" >
        <?php print drupal_render($form['field_main_scheme']); ?>
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_3" style="width:200px;" >
	  <?php print drupal_render($form['field_sector']); ?>
        <!--<label class="description" for="label4">Sector</label>
        <div>
          <select class="element select medium" id="element_" name="element_">
            <option value="" selected="selected"></option>
            <option value="1" >Agriculture</option>
            <option value="2" >Transport</option>
            <option value="2" >Hast Shilp</option>
            <option value="2" >Transport</option>
          </select>
        </div>-->
      </li>
    </ul>    </td>
    <td><ul >
      <li id="li_2" style="width:200px;" >
	   <?php print drupal_render($form['field_loantype']); ?>
        <!--<label class="description" for="label4">Loan Type</label>
        <div>
          <select class="element select medium" id="element_2" name="element_2">
            <option value="" selected="selected"></option>
            <option value="1" >Bank</option>
            <option value="2" >Direct</option>
          </select>-->
        </div>
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_" style="width:200px;" class="dfg" >
	  
       <label class="description" for="label4">Loan Class - Interest Rate</label>
	   <div>
        <div style="float:left;" class="sal">
         <?php print drupal_render($form['field_male']); ?>
		 </div>
        <div style="float:left;">  %
         - Male 
		 </div>
		 </div>
          <div>
        <div style="float:left; width:100px;" class="sal">
         <?php print drupal_render($form['field_female']); ?>
		 </div>
        <div style="float:left;">  %
         - Female 
		 </div>
		 </div>
        </div>
      </li>
    </ul>    </td>
    <td><ul >
      <li id="li_9" style="width:200px;" >
	  <?php print drupal_render($form['field_project_cost']); ?>
        <!--<label class="description" for="label7">Total  Cost of Project</label>
        <div>
          <input id="label7" name="label7" class="element text medium" type="text" maxlength="255" value=""/>
        </div>-->
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_4" style="width:300px;" class="dfg">
        <label class="description" for="label">Share</label>
    <table width="400px">
	<tr>
	   <td width="17">
          <?php print drupal_render($form['field_corporation_share']); ?>	</td>
		<td width="201">
        % Corporation Share      </td>
       </tr>
	   <tr>
	   <td>
         <?php print drupal_render($form['field_national_corporation_share']); ?>
		</td>
		 <td>
          % National Corporation Share
         </td>
		 </tr>
		 <tr>
		 <td>
		
		  
           <?php print drupal_render($form['field_promoter_share']); ?>
		   </td>
		   <td>
		  
            % Promoter Share
			</td>
			</tr>
			
        </table>
      </li>
    </ul>    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_5" style="width:200px;" >
	  <?php print drupal_render($form['field_capital_subsidy']); ?>
       <!-- <label class="description" for="label6">Capital Subsidy</label>
        <div>
          <select class="element select medium" id="element_3" name="element_4">
            <option value="" selected="selected"></option>
            <option value="1" >Yes</option>
            <option value="2" >No</option>
          </select>
        </div>-->
      </li>
    </ul>    </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" background="Drag to a file to choose it."><ul >
      <li class="section_break">
        <h3 class="flip"><strong>&gt; In Case of Bank Loan</strong></h3>
        <p></p>
        <div class="panel" style="width:180px; height:10px;">
          <table width="600" border="0" cellspacing="1" cellpadding="1">
            <tr>
			 <?php print drupal_render($form['field_mmfdr']); ?>
              <!--<td width="232" style="width:200px;"><strong>MMD FDR %age of project cost</strong></td>
              <td width="335" style="width:150px;"><ul>
                  <li id="li_24" style="width:170px;"><span>
                    <input id="element_11" name="element_5" class="element text" type="text" maxlength="255" value="" height="10" style="width:150px;"/>
                  </span></li>
              </ul></td>-->
            </tr>
            <tr>
			<?php print drupal_render($form['field_interest_subsidy']); ?>
              <!--<td width="232" style="width:200px;"><strong>Interest Subsidy</strong></td>
              <td style="width:200px;"><div>
                  <select class="element select medium" id="element_4" name="element_6">
                    <option value="" selected="selected"></option>
                    <option value="1" >Yes</option>
                    <option value="2" >No</option>
                  </select>
              </div></td>-->
            </tr>
          </table>
        </div>
      </li>
    </ul>    </td>
    </tr>
  <tr>
    <td><ul >
      <li id="li_6" style="width:200px;" >
	  <?php print drupal_render($form['field_tenure']); ?>
        <!--<label class="description" for="label6">Tenure</label>
        <div>
          <input id="label8" name="label5" class="element text medium" type="text" maxlength="255" value=""/>
        </div>-->
      </li>
    </ul>    </td>
    <td><ul >
      <li id="li_8" style="width:200px;" >
	  <?php print drupal_render($form['field_sourcefund']); ?>
        <!--<label class="description" for="label6">Source of Fund</label>
        <div>
          <input id="label6" name="label4" class="element text medium" type="text" maxlength="255" value=""/>
        </div>-->
      </li>
    </ul>    </td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_11" style="width:200px;">
	  <?php print drupal_render($form['field_interest_calculation']); ?>
        <!--<label class="description" for="label9">Interest Calculation</label>
        <div>
          <select class="element select medium" id="element_5" name="element_8">
            <option value="" selected="selected"></option>
            <option value="1" >Simple</option>
            <option value="2" >Compound</option>
          </select>-->
        </div>
      </li>
    </ul>
    </td>
    <td><ul >,
      <li id="li_12" style="width:200px;" >
	   <?php print drupal_render($form['field_status']); ?>
       <!-- <label class="description" for="label9">Status</label>
        <div>
          <select class="element select medium" id="element_6" name="element_9" style="width:150px;">
            <option value="" selected="selected"></option>
            <option value="1" >Pending for Approval</option>
            <option value="2" >Approved</option>
            <option value="2" >Rejected</option>
          </select>
        </div>-->
      </li>
    </ul>
    </td>
  </tr>
  <tr>
    <td><ul >
      <li id="li_13" style="width:200px;" >
	   <?php print drupal_render($form['field_active']); ?>
        <!--<label class="description" for="label9">Active</label>
        <div>
          <select class="element select medium" id="element_7" name="element_10">
            <option value="" selected="selected"></option>
            <option value="1" >Yes</option>
            <option value="2" >No</option>
          </select>
        </div>-->
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
	