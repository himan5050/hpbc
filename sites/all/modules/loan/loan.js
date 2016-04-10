// JavaScript Document
	$(function() {
		base_url = $("#baseurlid").val();
		calculatePromotorshare();
		if($("#roi").val())
		{
			getroi($("#roi").val());
		}
		if($("#loan_typeid").val())
		{
			check_through_bank($("#loan_typeid option:selected").text());
		}else{
			$("#through_bank").hide();
			$("#through_bank1").hide();
			$("#loan_typetextid").val('');
		}
		//setRuralUrban($("#addresstypeid").val());
		setDisable();
//		religion = $("#religionid").val();
//		getLowerLevelData(religion,base_url,'casteid','caste');
//		district = $("#districtid").val();
//		getLowerLevelData(district,base_url,'tehsilid','tehsil');
//		tehsil = $("#tehsilid").val();
//		getLowerLevelData(tehsil,base_url,'blockid','block');
//		blockid = $("#blockid").val();
//		getLowerLevelData(blockid,base_url,'panchayatid','panchayat');
	});

	function validateRemark()
	{
		error = '';
		if(!$("#remarkid").val())
		{
			error += '<li>Remark field is required.</li>';
			$("#remarkid").addClass('error');
		}else{
			$("#remarkid").removeClass('error');
		}
		if(error)
		{
			error = '<ul>'+error+'</ul>';
			if($("#customerror"))
			{
				$("#customerror").html('');
				$("#customerror").remove();
			}
			if(!$(".messages").html())
			{
				$("#errorid").append('<div id="customerror">'+error+'</div>');
				$("#errorid").css({'display':''});
				
			}else{
				$(".messages").append('<div id="customerror">'+error+'</div>');
			}
			return false;
		}
	}
	function sanctionletterValidation(val)
	{
		if(val == 'Reject')
		{
			if(!confirm("Are you sure you want to reject this application."))
				return false;
		}else{
			
			error = '';
			if($("#edit-upload").val())
			{
				$("#edit-upload").removeClass('error');
			}else{
				error += '<li>Sanction letter field is required.</li>';
				$("#edit-upload").addClass('error');
			}
			if(error)
			{
				error = '<ul>'+error+'</ul>';
				if($("#customerror"))
				{
					$("#customerror").html('');
					$("#customerror").remove();
				}
				if(!$(".messages").html())
				{
					$("#errorid").append('<div id="customerror">'+error+'</div>');
					$("#errorid").css({'display':''});
					
				}else{
					$(".messages").append('<div id="customerror">'+error+'</div>');
				}
				return false;
			}
		}
		return true;
	}
	function roiValidation()
	{
		error = '';
		if($("#roi").val())
		{
			$("#roi").removeClass('error');
		}else{
			error += '<li>Interest class is required.</li>';
			$("#roi").addClass('error');
		}
		if(error)
		{
			error = '<ul>'+error+'</ul>';
			if($("#customerror"))
			{
				$("#customerror").html('');
				$("#customerror").remove();
			}
			if(!$(".messages").html())
			{
				$("#errorid").append('<div id="customerror">'+error+'</div>');
				$("#errorid").css({'display':''});
				
			}else{
				$(".messages").append('<div id="customerror">'+error+'</div>');
			}
			return false;
		}
	}
	function interestValidation()
	{
		error = '';
		if($("#accountid").val())
		{
			$("#accountid").removeClass('error');
		}else{
			error += '<li>Account number field is required.</li>';
			$("#accountid").addClass('error');
		}
		if($("#ldid").val() != '')
		{
			$("#ldid").removeClass('error');
		}else{
			error += '<li>LD charge amount field is required.</li>';
			$("#ldid").addClass('error');
		}
		if($("#LDreasonid").val())
		{
			$("#LDreasonid").removeClass('error');
		}else{
			error += '<li>LD reason field is required.</li>';
			$("#LDreasonid").addClass('error');
		}
		if($("#otherid").val() != '')
		{
			$("#otherid").removeClass('error');
		}else{
			error += '<li>Other charge amount field is required.</li>';
			$("#otherid").addClass('error');
		}
		if($("#otherreasonid").val())
		{
			$("#otherreasonid").removeClass('error');
		}else{
			error += '<li>Other charge reason field is required.</li>';
			$("#otherreasonid").addClass('error');
		}
		if(error)
		{
			error = '<ul>'+error+'</ul>';
			if($("#customerror"))
			{
				$("#customerror").html('');
				$("#customerror").remove();
			}
			if(!$(".messages").html())
			{
				$("#errorid").append('<div id="customerror">'+error+'</div>');
				$("#errorid").css({'display':''});
				
			}else{
				$(".messages").append('<div id="customerror">'+error+'</div>');
			}
			return false;
		}
	}
	function validateEmail(elementValue)
	{
		var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
		return emailPattern.test(elementValue);		
	}
	
	function validateMobile(elementValue)
	{
		var mobilePattern = /^([1-9]{1})([0-9]{9})$/;  
		return mobilePattern.test(elementValue);		
	}
	
	function validateDecimal(elementValue)
	{
		var decimalPattern = /^(?:0|[1-9]\d*)(?:\.(?!.*000)\d+)?$/;  
		return decimalPattern.test(elementValue);		
	}
	function validateAlphanumeric(elementValue)
	{
		var alphanumericPattern = /^[\ba-zA-Z0-9]$/;  
		return alphanumericPattern.test(elementValue);		
	}
	
	

	function check_through_bank(loantype)
	{
		if(loantype != 'Bank')
		{
			$("#through_bank").hide();
			$("#through_bank1").hide();
			$("#loan_typetextid").val('');
		}else{
			$("#through_bank").show();
			$("#through_bank1").show();
			$("#loan_typetextid").val('Bank');
		}
	}
	function copytoList(fromSelectBox,toSelectBox)
	{
		selectedItem = 0;
		for (x=0;x<=fromSelectBox.length;x++)
		{
			if (fromSelectBox[x].selected)
			{
				selectedItem = 1;
				str = '';
				var selectedText = fromSelectBox[x].text;
				var selectedValue = fromSelectBox[x].value;
				var toSelectBoxLength = toSelectBox.length;
				toSelectBox.length = toSelectBoxLength + 1;
				toSelectBox[toSelectBoxLength].text = selectedText;
				toSelectBox[toSelectBoxLength].value = selectedValue;
				fromSelectBox.remove(x);
				x = x -1;
			}
			
		}
			if (!selectedItem)
			{
				alert("No fieldname selected. \n \n Please select a field name.");
				return;
			}
		// call subroutine to sort the field names in 'F' box
		//sortFromList();
	
	}
	
	function onetimesettlementValidation()
	{
		error = '';
		if(!$("#account_idid").val())
		{
			error += '<li>Account number is required.</li>';
			$("#account_idid").addClass('error');
		}else{
			$("#account_idid").removeClass('error');
		}
		if($("#amountid").val())
		{
			if(!validateDecimal(parseFloat($("#amountid").val())))
			{
				error += '<li>Amount should be in decimal format.</li>';
				$("#amountid").addClass('error');
			}else{
				$("#amountid").removeClass('error');
			}
		}else{
			error += '<li>Amount is required.</li>';
			$("#amountid").addClass('error');
		}
		if($("#interestid").val())
		{
			if(!validateDecimal(parseFloat($("#interestid").val())))
			{
				error += '<li>Interest amount should be in decimal format.</li>';
				$("#interestid").addClass('error');
			}else{
				$("#interestid").removeClass('error');
			}
		}else{
			error += '<li>Interest amount is required.</li>';
			$("#interestid").addClass('error');
		}
		if(error)
		{
			error = '<ul>'+error+'</ul>';
			if($("#customerror"))
			{
				$("#customerror").html('');
				$("#customerror").remove();
			}
			if(!$(".messages").html())
			{
				$("#errorid").append('<div id="customerror">'+error+'</div>');
				$("#errorid").css({'display':''});
				
			}else{
				$(".messages").append('<div id="customerror">'+error+'</div>');
			}
			return false;
		}
	}
	function repaymentValidation()
	{
		error = '';
		if(!$("#cheque_numberid").val())
		{
			error += '<li>Cheque number is required.</li>';
			$("#cheque_numberid").addClass('error');
		}else{
			$("#cheque_numberid").removeClass('error');
		}
		if($("#amountid").val())
		{
			if(!validateDecimal(parseFloat($("#amountid").val())))
			{
				error += '<li>Amount should be in decimal format.</li>';
				$("#amountid").addClass('error');
			}else{
				$("#amountid").removeClass('error');
			}
		}else{
			error += '<li>Amount is required.</li>';
			$("#amountid").addClass('error');
		}
		if(!$("#in_favour_ofid").val())
		{
			error += '<li>In favour of is required.</li>';
			$("#in_favour_ofid").addClass('error');
		}else{
			$("#in_favour_ofid").removeClass('error');
		}
		if(!$("#cheque_dateid").val())
		{
			error += '<li>Cheque date is required.</li>';
			$("#cheque_dateid").addClass('error');
		}else{
			$("#cheque_dateid").removeClass('error');
		}
		if(!$("#bankid").val())
		{
			error += '<li>Bank name is required.</li>';
			$("#bankid").addClass('error');
		}else{
			$("#bankid").removeClass('error');
		}
		if(error)
		{
			error = '<ul>'+error+'</ul>';
			if($("#customerror"))
			{
				$("#customerror").html('');
				$("#customerror").remove();
			}
			if(!$(".messages").html())
			{
				$("#errorid").append('<div id="customerror">'+error+'</div>');
				$("#errorid").css({'display':''});
				
			}else{
				$(".messages").append('<div id="customerror">'+error+'</div>');
			}
			return false;
		}
	}
	function setdocuments()
	{
		document.getElementById("eligibilitydocsid").value = '';
		eligibility = document.forms['addschemeform'].elements['eligibility'].options;
		for (var i=0;i<eligibility.length;i++)
		{
			document.getElementById("eligibilitydocsid").value += eligibility[i].value+',';
		}
	}
	function addeditSchemeValidation()
	{
		error = '';
		document.getElementById("eligibilitydocsid").value = '';
		eligibility = document.forms['addschemeform'].elements['eligibility'].options;
		for (var i=0;i<eligibility.length;i++)
		{
			document.getElementById("eligibilitydocsid").value += eligibility[i].value+',';
		}

		if(!$("#schemenameid").val())
		{
			error += '<li>Scheme name field is required.</li>';
			$("#schemenameid").addClass('error');
		}else{
			$("#schemenameid").removeClass('error');
		}
		if(!$("#main_schemeid").val())
		{
			error += '<li>Main Scheme name field is required.</li>';
			$("#main_schemeid").addClass('error');
		}else{
			$("#main_schemeid").removeClass('error');
		}
		
		if(!$("#sectorid").val())
		{
			error += '<li>Sector field is required.</li>';
			$("#sectorid").addClass('error');
		}else{
			$("#sectorid").removeClass('error');
		}
		
		if(!$("#loan_typeid").val())
		{
			error += '<li>Loan type field is required.</li>';
			$("#loan_typeid").addClass('error');
		}else{
			$("#loan_typeid").removeClass('error');
		}
		
		if(!$("#tenureid").val())
		{
			error += '<li>Tenure field is required.</li>';
			$("#tenureid").addClass('error');
		}else{
			$("#tenureid").removeClass('error');
		}
		if(!$("#scheme_codeid").val())
		{
			error += '<li>Scheme code field is required.</li>';
			$("#scheme_codeid").addClass('error');
		}else{
			if($("#scheme_codeid").val().length != 3)
			{
				error += '<li>Scheme code should be 3 digit.</li>';
				$("#scheme_codeid").addClass('error');
			}else{
				$("#scheme_codeid").removeClass('error');
			}
		}
		
		if(!$("#fund_sourceid").val())
		{
			error += '<li>Fund source field is required.</li>';
			$("#fund_sourceid").addClass('error');
		}else{
			$("#fund_sourceid").removeClass('error');
		}
		
		if(!$("#frequencyid").val())
		{
			error += '<li>Frequency field is required.</li>';
			$("#frequencyid").addClass('error');
		}else{
			$("#frequencyid").removeClass('error');
		}
		
		if(!$("#interest_typeid").val())
		{
			error += '<li>Interest calculatio fieldn is required.</li>';
			$("#interest_typeid").addClass('error');
		}else{
			$("#interest_typeid").removeClass('error');
		}
		
	/*	if($("#processing_feeid").val())
		{
			flag = validateDecimal($("#processing_feeid").val());
			if(!flag)
			{
				error += '<li>Processing fee should be in decimal format.</li>';
				$("#processing_feeid").addClass('error');
			}else{
				$("#processing_feeid").removeClass('error');
			}
		}else{
			error += '<li>Processing fee is required.</li>';
			$("#processing_feeid").addClass('error');
		}*/
		if($("#prjcostid").val())
		{
			if(!validateDecimal(parseFloat($("#prjcostid").val())))
			{
				error += '<li>Project cost should be in decimal format.</li>';
				$("#prjcostid").addClass('error');
			}else{
				$("#prjcostid").removeClass('error');
			}
		}else{
			error += '<li>Project cost field is required.</li>';
			$("#prjcostid").addClass('error');
		}
		if($("#apexid").val() == '')
		{
			error += '<li>Apex body share field is required.</li>';
			$("#apexid").addClass('error');
		}
		if($("#corpid").val() == '')
		{
			error += '<li>Corporation share field is required.</li>';
			$("#corpid").addClass('error');
		}
		totalshare = parseFloat($("#apexid").val()) + parseFloat($("#corpid").val());
		if(totalshare > 100)
		{
			error += '<li>Sum of Apex share and Corporation share can not exceed 100%.</li>';
			$("#apexid").addClass('error');
			$("#corpid").addClass('error');
		}
		
		if($("#loan_typetextid").val() == 'Bank')
		{
			if($("#mmdfdrid").val())
			{
				if(!validateDecimal(parseFloat($("#mmdfdrid").val())))
				{
					error += '<li>MMD FDR should be in decimal format.</li>';
					$("#mmdfdrid").addClass('error');
				}else{
					$("#mmdfdrid").removeClass('error');
				}
			}else{
				error += '<li>MMD FDR field is required.</li>';
				$("#mmdfdrid").addClass('error');
			}
		}
 		if($("#eligibilityid").text().length > 2)
		{
			$("#eligibilityid").removeClass('error');
		}else{
			error += '<li>Atleast 1 document required.</li>';
			$("#eligibilityid").addClass('error');
		}
		cerror = 0;
		var tbl = document.getElementById('loan_classid');
		var lastRow = tbl.rows.length;
		derror = 0;
		for(i=0;i < (lastRow - 1);i++)
		{
			if($("#cid"+i).val() && $("#rid"+i).val())
				cerror = 1;
			if($("#rid"+i).val() && !validateDecimal(parseFloat($("#rid"+i).val())))
			{
				$("#rid"+i).addClass('error');
				derror = 1;
			}
		}
		if(derror)
		{
			error += '<li>ROI value should be in decimal.</li>';
		}
		if(!cerror)
		{
			error += '<li>Atleast 1 loan class required.</li>';
			$("#cid0").addClass('error');
			$("#rid0").addClass('error');
		}else{
			$("#cid0").removeClass('error');
			$("#rid0").removeClass('error');
		}
		if($("#statusid").val() != 166)
		{
			if(!$("#LOI_fileid"))
			{
				if(!$("#LOI_docid").val() && !$("#LOIid").is(':checked'))
				{
					error += '<li>LOI received field is mandatory if status is approved and please ensure that LOI check box is checked properly.</li>';
				}
			}else if(!$("#LOIid").is(':checked')){
				error += '<li>LOI received field is mandatory if status is approved and please ensure that LOI check box is checked properly.</li>';
				
			}
		}
		if(error)
		{
			error = '<ul>'+error+'</ul>';
			if($("#customerror"))
			{
				$("#customerror").html('');
				$("#customerror").remove();
			}
			if(!$(".messages").html())
			{
				$("#errorid").append('<div id="customerror">'+error+'</div>');
				$("#errorid").css({'display':''});
				
			}else{
				$(".messages").append('<div id="customerror">'+error+'</div>');
			}
			return false;
		}
	}
	function addeditLoanValidation()
	{	
		error = '';
		if(!$("#fname").val())
		{
			error += '<li>First name is required.</li>';
			$("#fname").addClass('error');
		}else{
			$("#fname").removeClass('error');
		}
		if(!$("#lname").val())
		{
			error += '<li>Last name is required.</li>';
			$("#lname").addClass('error');
		}else{
			$("#lname").removeClass('error');
		}
		if(!$("#fh_name").val())
		{

			error += '<li>Father/Husband name is required.</li>';
			$("#fh_name").addClass('error');
		}else{
			$("#fh_name").removeClass('error');
		}
		if(!$("#dob").val())
		{
			error += '<li>DOB field is required.</li>';
			$("#dob").addClass('error');
		}else{
			$("#dob").removeClass('error');
		}
		if(!$("#religionid").val())
		{
			error += '<li>Religion field is required.</li>';
			$("#religionid").addClass('error');
		}else{
			$("#religionid").removeClass('error');
		}
		if(!$("#categoryid").val())
		{
			error += '<li>Category field is required.</li>';
			$("#categoryid").addClass('error');
		}else{
			$("#categoryid").removeClass('error');
		}
		if(!$("#casteid").val())
		{
			error += '<li>Caste field is required.</li>';
			$("#casteid").addClass('error');
		}else{
			$("#casteid").removeClass('error');
		}
		if(!$("#income_catid").val())
		{
			error += '<li>Income category field is required.</li>';
			$("#income_catid").addClass('error');
		}else{
			$("#income_catid").removeClass('error');
		}
		if(!$("#stateid").val())
		{
			error += '<li>State field is required.</li>';
			$("#stateid").addClass('error');
		}else{
			$("#stateid").removeClass('error');
		}
		if(!$("#districtid").val())
		{
			error += '<li>District field is required.</li>';
			$("#districtid").addClass('error');
		}else{
			$("#districtid").removeClass('error');
		}
		if(!$("#tehsilid").val())
		{
			error += '<li>Tehsil field is required.</li>';
			$("#tehsilid").addClass('error');
		}else{
			$("#tehsilid").removeClass('error');
		}
		if(!$("#blockid").val())
		{
			error += '<li>Block field is required.</li>';
			$("#blockid").addClass('error');
		}else{
			$("#blockid").removeClass('error');
		}
		if(!$("#address2id").val())
		{
			error += '<li>Address Line2 field is required.</li>';
			$("#address2id").addClass('error');
		}else{
			$("#address2id").removeClass('error');
		}
		if(!$("#address1id").val())
		{
			error += '<li>Address Line1 field is required.</li>';
			$("#address1id").addClass('error');
		}else{
			$("#address1id").removeClass('error');
		}
		if($("#addresstypeid").val() == 175 && !$("#panchayatid").val())
		{
			error += '<li>Panchayat field is required.</li>';
			$("#panchayatid").addClass('error');
		}else{
			$("#panchayatid").removeClass('error');
		}
		if(!$("#addresstypeid").val())
		{
			error += '<li>Address Type field is required.</li>';
			$("#addresstypeid").addClass('error');
		}else{
			$("#addresstypeid").removeClass('error');
		}
		if(!$("#schemeid").val())
		{
			error += '<li>Scheme field is required.</li>';
			$("#schemeid").addClass('error');
		}else{
			$("#schemeid").removeClass('error');
		}
		if(!$("#corp_branchid").val())
		{
			error += '<li>Corporation Branch field is required.</li>';
			$("#corp_branchid").addClass('error');
		}else{
			$("#corp_branchid").removeClass('error');
		}
		
		
		
		
		
		
		
		if($("#fincomeid").val())
		{
			if(!validateDecimal(parseFloat($("#fincomeid").val())))
			{
				error += '<li>Family income should be in decimal format.</li>';
				$("#fincomeid").addClass('error');
			}else{
				$("#fincomeid").removeClass('error');
			}
		}else{
			error += '<li>Family income field is required.</li>';
			$("#fincomeid").addClass('error');
		}
		if($("#prjcostid").val())
		{
			flag = validateDecimal(parseFloat($("#prjcostid").val()));
			if(!flag)
			{
				error += '<li>Project cost should be in decimal format.</li>';
				$("#prjcostid").addClass('error');
			}else{
				$("#prjcostid").removeClass('error');
			}
		}else{
			error += '<li>Project cost field is required.</li>';
			$("#prjcostid").addClass('error');
		}
		/*if($("#emailid").val())
		{
			if(!validateEmail($("#emailid").val()))
			{
				error += '<li>Email should be a valid email id.</li>';
				$("#emailid").addClass('error');
			}else{
				$("#emailid").removeClass('error');
			}
		}else{
			error += '<li>Email is required.</li>';
			$("#emailid").addClass('error');
		}*/
		if($("#mobileid").val())
		{
			if(!validateMobile($("#mobileid").val()))
			{
				error += '<li>Mobile should be a valid Indian mobile number without preceeding 0.</li>';
				$("#mobileid").addClass('error');
			}else{
				$("#mobileid").removeClass('error');
			}
		}else{
			error += '<li>Mobile field is required.</li>';
			$("#mobileid").addClass('error');
		}
		if(!$("#qualificationid").val())
		{
			error += '<li>Qualification field is required.</li>';
			$("#qualificationid").addClass('error');
		}else{
			$("#qualificationid").removeClass('error');
		}
		if(!$("#pinid").val())
		{
			error += '<li>Pincode field is required.</li>';
			$("#pinid").addClass('error');
		}else{
			$("#pinid").removeClass('error');
		}
		if($("#loanrequirementid").val())
		{
			l = 0;
			if(!validateDecimal(parseFloat($("#loanrequirementid").val())))
			{
				error += '<li>Loan requirement should be in decimal format.</li>';
				$("#loanrequirementid").addClass('error');
				l = 1;
			}
			if(parseFloat($('#maxloanid').val()) < parseFloat($("#loanrequirementid").val()))
			{
				error += '<li>Loan requirement can not be greater than maximum loan amount '+$('#maxloanid').val()+'.</li>';
				$("#loanrequirementid").addClass('error');
				l = 1;
			}
			if(!l){
				$("#loanrequirementid").removeClass('error');
			}
		}else{
			error += '<li>Loan requirement is required.</li>';
			$("#loanrequirementid").addClass('error');
		}
		
		if ($("#loanyes").is(':checked'))
		{
			error += '<li>You have already running loan.You can not apply for this loan.</li>';
			$("#loan_error").html('<span style="color:red;">You have already running loan.You can not apply for this loan.</span>');
		}
		if(error)
		{
			error = '<ul>'+error+'</ul>';
			if($("#customerror"))
			{
				$("#customerror").html('');
				//$("#customerror").remove();
			}
			if(!$(".messages").html())
			{
				$("#errorid").append('<div id="customerror">'+error+'</div>');
				$("#errorid").css({'display':''});
				
			}else{
				$(".messages").html('');
				$(".messages").append('<div id="customerror">'+error+'</div>');
			}
			return false;
		}
	}
	function calculatePromotorshare()
	{
		error = '';
		if($("#apexid").val() && $("#corpid").val())
		{
			totalshare = parseFloat($("#apexid").val()) + parseFloat($("#corpid").val());
			if(parseFloat(totalshare) <= 100)
			{
				promshare = 100 - (parseFloat($("#apexid").val()) + parseFloat($("#corpid").val()));
				$("#promdivid").html(Math.round(promshare * 100)/100+'%');
				$("#promid").val(promshare);
			}
			return true;
		}
	}
	function remove_loanclass(id)
	{
		$("#"+id).remove();

	}
	function addMoreField(url,imgpath)
	{
		var tbl = document.getElementById('loan_classid');
		var lastRow = tbl.rows.length;
		// if there's no header row in the table, then iteration = lastRow + 1
		var iteration = lastRow;
		//  var iteration = lastRow + 1;
		var row = tbl.insertRow(lastRow);
		if((iteration - 1) % 2)
			row.className = 'oddrow';
		else
			row.className = 'evenrow';
		row.id = 'lc'+(lastRow - 1);
		
		//  cell 0
		var cell0 = row.insertCell(0);
		cell0.innerHTML = '<div class="loantext">Class Name<span title="This field is required.">*</span></div><div class="loanform"><input type="text" name="class[]" maxlength="45" onKeyPress="return alphabet(event);"  id="cid'+(lastRow - 1)+'" value="" />';
		cell0.className = 'form-text1';
		
		cell0.innerHTML += '</div>';
				
		var cell1 = row.insertCell(1);
		eid = "'rid"+(lastRow - 1)+"'";
		cell1.innerHTML = '<div class="loantext">ROI<span title="This field is required.">*</span></div><div class="loanform"><input type="text" name="ROI[]"  id="rid'+(lastRow - 1)+'" value="" maxlength="6" onkeypress="return paypaymain_custom(event,'+eid+',6);" />';
		cell1.className = 'form-text1';
		
		cell1.innerHTML += '</div><img src="'+url+'/'+imgpath+'/images/b_drop.png" onclick="return remove_loanclass(\'lc'+(lastRow - 1)+'\');" style="cursor:pointer;" />';
	}
	function openGuarantor(element)
	{
		if($("#gid"+element).is(':checked'))
		{
			$("#gdetailid"+element).slideDown('slow');
		}else{
			if(confirm("Are you sure you want to remove this guarantor?"))
			{
				url = $("#baseurl").val();
				id = $("#guidid"+element).val();
				$("#gdetailid"+element).slideUp('slow');
			//if(element != 0)
				$("#gdetailid"+element).remove();
				if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else
				{// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						var val = xmlhttp.responseText;	
						//$('#gnumid').val('deleted');
					}
				}
				
				xmlhttp.open("GET",url+"/loan.php?action=remove_guarantor&q="+id,true);
				xmlhttp.send();
				
			}else{
				$("#gid"+element).attr('checked', true);
			}
		}
	}
	function removeloanfile(lid,loaneeid,action)
	{
		if(!confirm("Are you sure you want to remove this document?"))
			return false;
		url = $("#baseurl").val();
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				share = val.split(",");
				$('#df'+lid).html('');
				$('#doc'+lid).attr('checked', false);
			}
		}
		
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&loaneeid="+loaneeid+"&q="+lid,true);
		xmlhttp.send();
	}
	function getProjectCost(id, url, action)
	{
		if (id=="")
		{
			$('#allshareid').html('');
			$('#loanrequirementid').val('');
			$('#maxloanid').val('');
			$('#documentrequiredid').html('');
			return;
		} 
		imgpath = $("#imagepathid").val();
		$('#schemep').html('<img src="'+url+'/'+imgpath+'waiting.gif">');
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				share = val.split(",");
				$('#loanrequirementid').val(share[0]);
				$('#maxloanid').val(share[0]);
				$('#allshareid').html("Corporation Share : "+share[3]+"% Apex Share : "+share[2]+"% Promoter Share : "+share[1]+"%");
				getDocuments(id,url,'schemedocument');
			}
		}
		
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&q="+id,true);
		xmlhttp.send();
		
	}
	function getDocuments(id, url, action)
	{
		if (id=="")
		{
			return;
		} 
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				$('#documentrequiredid').html(val);
				$('#schemep').html('');
			}
		}
		
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&q="+id,true);
		xmlhttp.send();
		
	}
	
	function getLowerLevelData(id, url, divid, action, waitingid)
	{
		if (id=="")
		{
			return;
		} 
		if(action =='panchayat')
		{
			if($("#addresstypeid").val() == 72)
			{
				return;
			}
		}
		imgpath = $("#imagepathid").val();
		//alert(url+'/'+imgpath+'waiting.gif');
		$("#"+waitingid).html('<img src="'+url+'/'+imgpath+'waiting.gif">');
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				$('#'+divid).html(val);
				$("#"+waitingid).html('');
			}
		}
		
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&q="+id,true);
		xmlhttp.send();
	}
	
	function checkrunningloan()
	{
		if ($("#loanyes").is(':checked'))
		{
			$("#loan_error").html('&nbsp;&nbsp;&nbsp;<span style="color:red;">You can not apply for this loan.</span>');
			$("#applyloanid").hide('slow');
		}else{
			$("#loan_error").html('');
			$("#applyloanid").show('slow');
		}

	}
	function getroi(roi)
	{
		if(roi)
			$("#roiid").html('Applied ROI will be '+roi+' %');
		else
			$("#roiid").html('');
	}
	function paid(a,b,c,d,e,url)
	{  
		b = '';	
		type = '';
		if(e == 'loanadvance')
			type = 'loanadvance';
		if(e == 'billsubmit')
			type = 'billsubmit';
		if(!type)
			type = 'loan';
		//url = $("#baseurl").val();

	   if(c=='journal')
	   {
		  window.location.href=url+"/account/claim_pay.php?Debit="+a+"&GLManualCode="+b+"&GLCode="+b+"&clid="+d+"&type="+type+"&vou=journal";
	   }
	   else if(c=='payment')
	   {
		 window.location.href=url+"/account/claim_pay.php?GLAmount="+a+"&GLCode="+b+"&GLManualCode="+b+"&clid="+d+"&type="+type+"&vou=payment";
	   }
	   else if(c=='receipt')
	   {
		 window.location.href=url+"/account/claim_pay.php?GLAmount="+a+"&GLCode="+b+"&GLManualCode="+b+"&clid="+d+"&type="+type+"&vou=receipt&NewReceipt=Yes";
	   }
	   
	}
	function showAccountDetail(url)
	{
		id = $("#account_idid").val();
		if(!id)
			return false;
		
		action = 'account_detail';
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				$('#accdetail').html(val);
			}
		}
		
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&q="+id,true);
		xmlhttp.send();
		
	}
	function calculateInterest(url)
	{
		id = $("#account_idid").val();
		if(!id)
			return false;
		
		action = 'interest';
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				$('#cinterestid').html(val);
			}
		}
		
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&q="+id,true);
		xmlhttp.send();
		
	}
	function myCases(url)
	{
		action = 'mycases';
		if($("#mycasesid").is(':checked'))
		{
			id = 1;
		}else{
			id = 0;
		}
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				window.location = url+"/loan/listloans";
			}
		}
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&q="+id,true);
		xmlhttp.send();
	}
	function setStatus(url,id)
	{
		if(!id)
			return false;
		
		action = 'loanstatus';
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				var val = xmlhttp.responseText;	
				window.location = url+"/loan/listloans";
			}
		}
		xmlhttp.open("GET",url+"/loan.php?action="+action+"&q="+id,true);
		xmlhttp.send();
	}
	function showhide()
	{
		if($("#hideid").val() == 'hide')
		{
			$("#accdetailid").hide('slow');
			$("#hideid").val('show');
			$("#showhide").html('Show');
		}else{
			$("#accdetailid").show('slow');
			$("#hideid").val('hide');
			$("#showhide").html('Hide');
		}
	}
	function setRuralUrban(val)
	{
		if(val == 72)
		{
			$("#villageid").attr('disabled', true);
			$("#panchayatid").attr('disabled', true);
		}else if(val == 175){
			url = $("#baseurl").val();
			if($("#blockid").val() && !$("#lid").val())
			{
				getLowerLevelData($("#blockid").val(),url,'panchayatid','panchayat');
			}
			$("#villageid").attr('disabled', '');
			$("#panchayatid").attr('disabled', '');
		}
	}
	function setDisable()
	{
		if($("#addresstypeid").val() == 72)
		{
			$("#villageid").attr('disabled', true);
			$("#panchayatid").attr('disabled', true);
		}
	}
	function reportValidation()
	{
		error = '';
		if($("#repid").val() && $("#repid").val() != 0)
		{
			return confirm('This action will replace the previously uploaded file.Are you sure you want to remove previusly uploaded file?')
		}
		
	}
	function getfilepath()
	{
		alert($("input[name=doc2]").val());
	}