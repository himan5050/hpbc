<style type="text/css">
.links {visibility:hidden;}
</style>
<?php
//print_r($node);exit;
?>

<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center" colspan="2"><h2>Grievance Detail</h2></td>
</tr>
<tr class="evenrow">
	<td align="left" width="50%">Application No.</td>
    <td align="left" class="normal"><?php echo $appno; ?></td>
</tr>

<tr class="oddrow">
	<td align="left" width="50%">Section</td>
    <td align="left" class="normal"><?php echo $section; ?></td>
</tr>
<tr class="evenrow">
	<td align="left" width="50%">Application Type</td>
    <td align="left" class="normal"><?php echo $application_type; ?></td>
</tr>
<tr class="oddrow">
	<td align="left" width="50%">Office</td>
    <td align="left" class="normal"><?php echo $office; ?></td>
</tr>
<tr class="evenrow">
	<td align="left" width="50%">Current Date</td>
    <td align="left" class="normal"><?php echo $datecurrent; ?></td>
</tr>
<tr class="oddrow">
	<td align="left" width="50%">Applicant Name</td>
    <td align="left" class="normal"><?php echo $application_name; ?></td>
</tr>
<!--<tr class="evenrow">
	<td align="left" width="50%">Application Detail</td>
    <td align="left" class="normal"><?php //echo $application_detail; ?></td>
</tr>
--><tr class="evenrow">
	<td align="left" width="50%">Application Category</td>
    <td align="left" class="normal"><?php echo $application_category; ?></td>
</tr>
<tr class="oddrow">
	<td align="left" width="50%">Address</td>
    <td align="left" class="normal"><?php echo $permanent_address; ?></td>
</tr>

<tr class="evenrow">
	<td align="left" width="50%">Correspondence Address</td>
    <td align="left" class="normal"><?php echo $correspondence_address; ?></td>
</tr>
<!--<tr class="oddrow">
	<td align="left" width="50%">State Name</td>
    <td align="left" class="normal"><?php //echo $state_id; ?></td>
</tr>
<tr class="evenrow">
	<td align="left" width="50%">District Name</td>
    <td align="left" class="normal"><?php //echo $district_id; ?></td>
</tr>
<tr class="oddrow">
	<td align="left" width="50%">Tehsil Name</td>
    <td align="left" class="normal"><?php //echo $tehsil_id; ?></td>
</tr>-->
<tr class="oddrow">
	<td align="left" width="50%">Telephone No.</td>
    <td align="left" class="normal"><?php echo $telephone_number; ?></td>
</tr>
<tr class="evenrow">
	<td align="left" width="50%">Mobile No.</td>
    <td align="left" class="normal"><?php echo $mobile_number; ?></td>
</tr>
<tr class="oddrow">
	<td align="left" width="50%">Email Address</td>
    <td align="left" class="normal"><?php echo $email_address; ?></td>
</tr>
<tr class="evenrow">
	<td align="left" width="50%">Status</td>
    <td align="left" class="normal"><?php echo $grievance_status; ?></td>
</tr>

<tr class="oddrow">
	<td align="left" width="50%">Remarks/Action Taken</td>
    <td align="left" class="normal"><?php echo $remarks; ?></td>
</tr>
<tr class="evenrow">
	    <td align="center" colspan="2" class='back'><?php echo l("Back","dsje/listgrievance/view"); ?></td>
</tr>
</table>
