<?php

/* $Id: Stocks.php 4630 2011-07-14 10:27:29Z daintree $ */

include('includes/session.inc');
$title = _('Item Maintenance');
include('includes/header.inc');

/*If this form is called with the StockID then it is assumed that the stock item is to be modified */

if (isset($_GET['StockID'])){
	$StockID =trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])){
	$StockID =trim(mb_strtoupper($_POST['StockID']));
} else {
	$StockID = '';
}

if (isset($StockID) and !isset($_POST['UpdateCategories'])) {
	$sql = "SELECT COUNT(stockid) FROM stockmaster WHERE stockid='".$StockID."' GROUP BY stockid";
	$result = DB_query($sql,$db);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]==0) {
		$New=1;
	} else {
		$New=0;
	}
}

if (isset($_POST['New'])) {
	$New=$_POST['New'];
}

if (isset($_FILES['ItemPicture']) AND $_FILES['ItemPicture']['name'] !='') {

	$result	= $_FILES['ItemPicture']['error'];
 	$UploadTheFile = 'Yes'; //Assume all is well to start off with
	$filename = $_SESSION['part_pics_dir'] . '/' . $StockID . '.jpg';

	 //But check for the worst
	if (mb_strtoupper(mb_substr(trim($_FILES['ItemPicture']['name']),mb_strlen($_FILES['ItemPicture']['name'])-3))!='JPG'){
		prnMsg(_('Only jpg files are supported - a file extension of .jpg is expected'),'warn');
		$UploadTheFile ='No';
	} elseif ( $_FILES['ItemPicture']['size'] > ($_SESSION['MaxImageSize']*1024)) { //File Size Check
		prnMsg(_('The file size is over the maximum allowed. The maximum size allowed in KB is') . ' ' . $_SESSION['MaxImageSize'],'warn');
		$UploadTheFile ='No';
	} elseif ( $_FILES['ItemPicture']['type'] == 'text/plain' ) {  //File Type Check
		prnMsg( _('Only graphics files can be uploaded'),'warn');
		 	$UploadTheFile ='No';
	} elseif (file_exists($filename)){
		prnMsg(_('Attempting to overwrite an existing item image'),'warn');
		$result = unlink($filename);
		if (!$result){
			prnMsg(_('The existing image could not be removed'),'error');
			$UploadTheFile ='No';
		}
	}

	if ($UploadTheFile=='Yes'){
		$result  =  move_uploaded_file($_FILES['ItemPicture']['tmp_name'], $filename);
		$message = ($result)?_('File url') ."<a href='". $filename ."'>" .  $filename . '</a>' : _('Something is wrong with uploading a file');
	}
}

if (isset($Errors)) {
	unset($Errors);
}
$Errors = array();
$InputError = 0;

if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test

	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	$i=1;


	if (!isset($_POST['Description']) or mb_strlen($_POST['Description']) > 50 OR mb_strlen($_POST['Description'])==0) {
		$InputError = 1;
		prnMsg (_('The stock item description must be entered and be fifty characters or less long') . '. ' . _('It cannot be a zero length string either') . ' - ' . _('a description is required'),'error');
		$Errors[$i] = 'Description';
		$i++;
	}
	if (mb_strlen($_POST['LongDescription'])==0) {
		$InputError = 1;
		prnMsg (_('The stock item description cannot be a zero length string') . ' - ' . _('a long description is required'),'error');
		$Errors[$i] = 'LongDescription';
		$i++;
	}
	if (mb_strlen($StockID) ==0) {
		$InputError = 1;
		prnMsg (_('The Stock Item code cannot be empty'),'error');
		$Errors[$i] = 'StockID';
		$i++;
	}
	if (ContainsIllegalCharacters($StockID) OR mb_strpos($StockID,' ')) {
		$InputError = 1;
		prnMsg(_('The stock item code cannot contain any of the following characters') . " - ' & + \" \\ " . _('or a space'),'error');
		$Errors[$i] = 'StockID';
		$i++;
		$StockID='';
	}
	if (mb_strlen($_POST['Units']) >20) {
		$InputError = 1;
		prnMsg(_('The unit of measure must be 20 characters or less long'),'error');
		$Errors[$i] = 'Units';
		$i++;
	}
	if (mb_strlen($_POST['BarCode']) >20) {
		$InputError = 1;
		prnMsg(_('The barcode must be 20 characters or less long'),'error');
		$Errors[$i] = 'BarCode';
		$i++;
	}
	if (!is_numeric($_POST['Volume'])) {
		$InputError = 1;
		prnMsg (_('The volume of the packaged item in cubic metres must be numeric') ,'error');
		$Errors[$i] = 'Volume';
		$i++;
	}
	if ($_POST['Volume'] <0) {
		$InputError = 1;
		prnMsg(_('The volume of the packaged item must be a positive number'),'error');
		$Errors[$i] = 'Volume';
		$i++;
	}
	if (!is_numeric($_POST['KGS'])) {
		$InputError = 1;
		prnMsg(_('The weight of the packaged item in KGs must be numeric'),'error');
		$Errors[$i] = 'KGS';
		$i++;
	}
	if ($_POST['KGS']<0) {
		$InputError = 1;
		prnMsg(_('The weight of the packaged item must be a positive number'),'error');
		$Errors[$i] = 'KGS';
		$i++;
	}
	if (!is_numeric($_POST['EOQ'])) {
		$InputError = 1;
		prnMsg(_('The economic order quantity must be numeric'),'error');
		$Errors[$i] = 'EOQ';
		$i++;
	}
	if ($_POST['rateitem']=='') {
		$InputError = 1;
		prnMsg(_('The Rate of Item must be Entered'),'error');
		$Errors[$i] = 'EOQ';
		$i++;
	}
	if (!is_numeric($_POST['rateitem'])) {
		$InputError = 1;
		prnMsg(_('The Rate of Item must be numeric'),'error');
		$Errors[$i] = 'EOQ';
		$i++;
	}
	if ($_POST['EOQ'] <0) {
		$InputError = 1;
		prnMsg (_('The economic order quantity must be a positive number'),'error');
		$Errors[$i] = 'EOQ';
		$i++;
	}
	if ($_POST['Controlled']==0 AND $_POST['Serialised']==1){
		$InputError = 1;
		prnMsg(_('The item can only be serialised if there is lot control enabled already') . '. ' . _('Batch control') . ' - ' . _('with any number of items in a lot/bundle/roll is enabled when controlled is enabled') . '. ' . _('Serialised control requires that only one item is in the batch') . '. ' . _('For serialised control') . ', ' . _('both controlled and serialised must be enabled'),'error');
		$Errors[$i] = 'Serialised';
		$i++;
	}
	if ($_POST['NextSerialNo']!=0 AND $_POST['Serialised']==0){
		$InputError = 1;
		prnMsg(_('The item can only have automatically generated serial numbers if it is a serialised item'),'error');
		$Errors[$i] = 'NextSerialNo';
		$i++;
	}
	if ($_POST['NextSerialNo']!=0 AND $_POST['MBFlag']!='M'){
		$InputError = 1;
		prnMsg(_('The item can only have automatically generated serial numbers if it is a manufactured item'),'error');
		$Errors[$i] = 'NextSerialNo';
		$i++;
	}
	if (($_POST['MBFlag']=='A' OR $_POST['MBFlag']=='K' OR $_POST['MBFlag']=='D' OR $_POST['MBFlag']=='G') AND $_POST['Controlled']==1){
		$InputError = 1;
		prnMsg(_('Assembly/Kitset/Phantom/Service/Labour items cannot also be controlled items') . '. ' . _('Assemblies/Dummies/Phantom and Kitsets are not physical items and batch/serial control is therefore not appropriate'),'error');
		$Errors[$i] = 'Controlled';
		$i++;
	}
	if (trim($_POST['CategoryID'])==''){
		$InputError = 1;
		prnMsg(_('There are no inventory categories defined. All inventory items must belong to a valid inventory category,'),'error');
		$Errors[$i] = 'CategoryID';
		$i++;
	}
	if (!is_numeric($_POST['Pansize'])) {
		$InputError = 1;
		prnMsg(_('Pansize quantity must be numeric'),'error');
		$Errors[$i] = 'Pansize';
		$i++;
	}
	if (!is_numeric($_POST['ShrinkFactor'])) {
		$InputError = 1;
		prnMsg(_('Shrinkage factor quantity must be numeric'),'error');
		$Errors[$i] = 'ShrinkFactor';
		$i++;
	}

	if ($InputError !=1){
		if ($_POST['Serialised']==1){ /*Not appropriate to have several dp on serial items */
			$_POST['DecimalPlaces']=0;
		}
		if ($New==0) { /*so its an existing one */

			/*first check on the changes being made we must disallow:
			- changes from manufactured or purchased to Service, Assembly or Kitset if there is stock			- changes from manufactured, kitset or assembly where a BOM exists
			*/
			$sql = "SELECT mbflag,
							controlled,
							serialised
					FROM stockmaster WHERE stockid = '".$StockID."'";
			$MBFlagResult = DB_query($sql,$db);
			$myrow = DB_fetch_row($MBFlagResult);
			$OldMBFlag = $myrow[0];
			$OldControlled = $myrow[1];
			$OldSerialised = $myrow[2];

			$sql = "SELECT SUM(locstock.quantity) 
					FROM locstock 
					WHERE stockid='".$StockID."' 
					GROUP BY stockid";
			$result = DB_query($sql,$db);
			$stkqtychk = DB_fetch_row($result);

			if ($OldMBFlag != $_POST['MBFlag']){
				if (($OldMBFlag == 'M' OR $OldMBFlag=='B') AND ($_POST['MBFlag']=='A' OR $_POST['MBFlag']=='K' OR $_POST['MBFlag']=='D' OR $_POST['MBFlag']=='G')){ /*then need to check that there is no stock holding first */
					/* stock holding OK for phantom (ghost) items */
					if ($stkqtychk[0]!=0 AND $OldMBFlag!='G'){
						$InputError=1;
						prnMsg( _('The make or buy flag cannot be changed from') . ' ' . $OldMBFlag . ' ' . _('to') . ' ' . $_POST['MBFlag'] . ' ' . _('where there is a quantity of stock on hand at any location') . '. ' . _('Currently there are') . ' ' . $stkqtychk[0] .  ' ' . _('on hand') , 'errror');
					}
					/* don't allow controlled/serialized  */
					if ($_POST['Controlled']==1){
						$InputError=1;
						prnMsg( _('The make or buy flag cannot be changed from') . ' ' . $OldMBFlag . ' ' . _('to') . ' ' . $_POST['MBFlag'] . ' ' . _('where the item is to be lot controlled') . '. ' . _('Kitset, phantom, dummy and assembly items cannot be lot controlled'), 'error');
					}
				}
				/*now check that if the item is being changed to a kitset, there are no items on sales orders or purchase orders*/
				if ($_POST['MBFlag']=='K') {
					$sql = "SELECT quantity-qtyinvoiced
							FROM salesorderdetails
							WHERE stkcode = '".$StockID."'
							AND completed=0";

					$result = DB_query($sql,$db);
					$ChkSalesOrds = DB_fetch_row($result);
					if ($ChkSalesOrds[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed to a kitset where there is a quantity outstanding to be delivered on sales orders') . '. ' . _('Currently there are') .' ' . $ChkSalesOrds[0] . ' '. _('outstanding'), 'error');
					}
				}
				/*now check that if it is to be a kitset or assembly or dummy there is no quantity on purchase orders outstanding*/
				if ($_POST['MBFlag']=='K' OR $_POST['MBFlag']=='A' OR $_POST['MBFlag']=='D') {

					$sql = "SELECT quantityord-quantityrecd
							FROM purchorderdetails
							WHERE itemcode = '".$StockID."'
							AND completed=0";

					$result = DB_query($sql,$db);
					$ChkPurchOrds = DB_fetch_row($result);
					if ($ChkPurchOrds[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed to'). ' ' . $_POST['MBFlag'] . ' '. _('where there is a quantity outstanding to be received on purchase orders') . '. ' . _('Currently there are'). ' ' . $ChkPurchOrds[0] . ' '. _('yet to be received'). 'error');
					}
				}

				/*now check that if it was a Manufactured, Kitset, Phantom or Assembly and is being changed to a purchased or dummy - that no BOM exists */
				if (($OldMBFlag=='M' OR $OldMBFlag =='K' OR $OldMBFlag=='A' OR $OldMBFlag=='G') AND ($_POST['MBFlag']=='B' OR $_POST['MBFlag']=='D')) {
					$sql = "SELECT COUNT(*) 
							FROM bom 
							WHERE parent = '".$StockID."' 
							GROUP BY parent";
					$result = DB_query($sql,$db);
					$ChkBOM = DB_fetch_row($result);
					if ($ChkBOM[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed from manufactured, kitset or assembly to'). ' ' . $_POST['MBFlag'] . ' '. _('where there is a bill of material set up for the item') . '. ' . _('Bills of material are not appropriate for purchased or dummy items'), 'error');
					}
				}

				/*now check that if it was Manufac, Phantom or Purchased and is being changed to assembly or kitset, it is not a component on an existing BOM */
				if (($OldMBFlag=='M' OR $OldMBFlag =='B' OR $OldMBFlag=='D' OR $OldMBFlag=='G') AND ($_POST['MBFlag']=='A' OR $_POST['MBFlag']=='K')) {
					$sql = "SELECT COUNT(*) FROM bom WHERE component = '".$StockID."' GROUP BY component";
					$result = DB_query($sql,$db);
					$ChkBOM = DB_fetch_row($result);
					if ($ChkBOM[0]!=0){
						$InputError = 1;
						prnMsg( _('The make or buy flag cannot be changed from manufactured, purchased or dummy to a kitset or assembly where the item is a component in a bill of material') . '. ' . _('Assembly and kitset items are not appropriate as components in a bill of materials'), 'error');
					}
				}
			}

			/* Do some checks for changes in the Serial & Controlled setups */
			if ($OldControlled != $_POST['Controlled'] AND $stkqtychk[0]!=0){
				$InputError=1;
				prnMsg( _('You can not change a Non-Controlled Item to Controlled (or back from Controlled to non-controlled when there is currently stock on hand for the item') , 'error');

			}
			if ($OldSerialised != $_POST['Serialised'] AND $stkqtychk[0]!=0){
				$InputError=1;
				prnMsg( _('You can not change a Serialised Item to Non-Serialised (or vice-versa) when there is a quantity on hand for the item') , 'error');
			}


			if ($InputError == 0){
				$sql = "UPDATE stockmaster
						SET longdescription='" . $_POST['LongDescription'] . "',
							description='" . $_POST['Description'] . "',
							discontinued='" . $_POST['Discontinued'] . "',
							controlled='" . $_POST['Controlled'] . "',
							serialised='" . $_POST['Serialised']."',
							perishable='" . $_POST['Perishable']."',
							categoryid='" . $_POST['CategoryID'] . "',
							units='" . $_POST['Units'] . "',
							mbflag='" . $_POST['MBFlag'] . "',
							eoq='" . $_POST['EOQ'] . "',
							rateitem='" . $_POST['rateitem'] . "',
							volume='" . $_POST['Volume'] . "',
							kgs='" . $_POST['KGS'] . "',
							barcode='" . $_POST['BarCode'] . "',
							discountcategory='" . $_POST['DiscountCategory'] . "',
							taxcatid='" . $_POST['TaxCat'] . "',
							decimalplaces='" . $_POST['DecimalPlaces'] . "',
							appendfile='" . $_POST['ItemPDF'] . "',
							shrinkfactor='" . $_POST['ShrinkFactor'] . "',
							pansize='" . $_POST['Pansize'] . "',
							nextserialno='" . $_POST['NextSerialNo'] . "'
					WHERE stockid='".$StockID."'";

				$ErrMsg = _('The stock item could not be updated because');
				$DbgMsg = _('The SQL that was used to update the stock item and failed was');
				$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

				//delete any properties for the item no longer relevant with the change of category
				$result = DB_query("DELETE FROM stockitemproperties
									WHERE stockid ='" . $StockID . "'",
									$db);

				//now insert any item properties
				for ($i=0;$i<$_POST['PropertyCounter'];$i++){

					if ($_POST['PropType' . $i] ==2){
						if ($_POST['PropValue' . $i]=='on'){
							$_POST['PropValue' . $i]=1;
						} else {
							$_POST['PropValue' . $i]=0;
						}
					}
					$result = DB_query("INSERT INTO stockitemproperties (stockid,
																		stkcatpropid,
																		value)
														VALUES ('" . $StockID . "',
																'" . $_POST['PropID' . $i] . "',
																'" . $_POST['PropValue' . $i] . "')",
										$db);
				} //end of loop around properties defined for the category
				prnMsg( _('Stock Item') . ' ' . $StockID . ' ' . _('has been updated'), 'success');
				echo '<br />';
			}

		} else { //it is a NEW part
			//but lets be really sure here
			$result = DB_query("SELECT stockid 
								FROM stockmaster 
								WHERE stockid='" . $StockID ."'",$db);
			if (DB_num_rows($result)==1){
				prnMsg(_('The stock code entered is actually already in the database - duplicate stock codes are prohibited by the system. Try choosing an alternative stock code'),'error');
				exit;
			} else {
				$sql = "INSERT INTO stockmaster (stockid,
												description,
												longdescription,
												categoryid,
												units,
												mbflag,
												eoq,
												rateitem,
												actualcost,
												discontinued,
												controlled,
												serialised,
												perishable,
												volume,
												kgs,
												barcode,
												discountcategory,
												taxcatid,
												decimalplaces,
												appendfile,
												shrinkfactor,
												pansize)
							VALUES ('".$StockID."',
								'" . $_POST['Description'] . "',
								'" . $_POST['LongDescription'] . "',
								'" . $_POST['CategoryID'] . "',
								'" . $_POST['Units'] . "',
								'" . $_POST['MBFlag'] . "',
								'" . $_POST['EOQ'] . "',
								'" . $_POST['rateitem'] . "',
								'" . $_POST['rateitem'] . "',
								'" . $_POST['Discontinued'] . "',
								'" . $_POST['Controlled'] . "',
								'" . $_POST['Serialised']. "',
								'" . $_POST['Perishable']. "',
								'" . $_POST['Volume'] . "',
								'" . $_POST['KGS'] . "',
								'" . $_POST['BarCode'] . "',
								'" . $_POST['DiscountCategory'] . "',
								'" . $_POST['TaxCat'] . "',
								'" . $_POST['DecimalPlaces']. "',
								'" . $_POST['ItemPDF']. "',
								'" . $_POST['ShrinkFactor'] . "',
								'" . $_POST['Pansize'] . "')";

				$ErrMsg =  _('The item could not be added because');
				$DbgMsg = _('The SQL that was used to add the item failed was');
				$result = DB_query($sql,$db, $ErrMsg, $DbgMsg);
				if (DB_error_no($db) ==0) {

					$sql = "INSERT INTO locstock (loccode,
													stockid)
										SELECT locations.loccode,
										'" . $StockID . "'
										FROM locations";

					$ErrMsg =  _('The locations for the item') . ' ' . $StockID .  ' ' . _('could not be added because');
					$DbgMsg = _('NB Locations records can be added by opening the utility page') . ' <i>Z_MakeStockLocns.php</i> ' . _('The SQL that was used to add the location records that failed was');
					$InsResult = DB_query($sql,$db,$ErrMsg,$DbgMsg);

					if (DB_error_no($db) ==0) {
						prnMsg( _('New Item') .' ' . '<a href="SelectProduct.php?StockID=' . $StockID . '">' . $StockID . '</a> '. _('has been added to the database') . 
							'<br />' . _('NB: The item cost and pricing must also be setup') .
							'<br />' . '<a target="_blank" href="StockCostUpdate.php?StockID=' . $StockID . '">' . _('Enter Item Cost') . '</a> 
							<br />' . '<a target="_blank" href="Prices.php?Item=' . $StockID . '">' . _('Enter Item Prices') . '</a> ','success');
						echo '<br />';
						unset($_POST['Description']);
						unset($_POST['LongDescription']);
						unset($_POST['EOQ']);
						unset($_POST['rateitem']);
// Leave Category ID set for ease of batch entry
//						unset($_POST['CategoryID']);
						unset($_POST['Units']);
						unset($_POST['MBFlag']);
						unset($_POST['Discontinued']);
						unset($_POST['Controlled']);
						unset($_POST['Serialised']);
						unset($_POST['Perishable']);
						unset($_POST['Volume']);
						unset($_POST['KGS']);
						unset($_POST['BarCode']);
						unset($_POST['ReorderLevel']);
						unset($_POST['DiscountCategory']);
						unset($_POST['DecimalPlaces']);
						unset($_POST['ItemPDF']);
						unset($_POST['ShrinkFactor']);
						unset($_POST['Pansize']);
						unset($StockID);
					}//ALL WORKED SO RESET THE FORM VARIABLES
				}//THE INSERT OF THE NEW CODE WORKED SO BANG IN THE STOCK LOCATION RECORDS TOO
			}//END CHECK FOR ALREADY EXISTING ITEM OF THE SAME CODE
		}
		$New=1;

	} else {
		echo '<br />'. "\n";
		prnMsg( _('Validation failed, no updates or deletes took place'), 'error');
	}

} elseif (isset($_POST['delete']) AND mb_strlen($_POST['delete']) >1 ) {
//the button to delete a selected record was clicked instead of the submit button

	$CancelDelete = 0;

// PREVENT DELETES IF DEPENDENT RECORDS IN 'StockMoves'

	$sql= "SELECT COUNT(*) FROM stockmoves WHERE stockid='".$StockID."' GROUP BY stockid";
	$result = DB_query($sql,$db);
	$myrow = DB_fetch_row($result);
	if ($myrow[0]>0) {
		$CancelDelete = 1;
		prnMsg( _('Cannot delete this stock item because there are stock movements that refer to this item'),'warn');
		echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('stock movements that refer to this item');

	} else {
		$sql= "SELECT COUNT(*) FROM bom WHERE component='".$StockID."' GROUP BY component";
		$result = DB_query($sql,$db);
		$myrow = DB_fetch_row($result);
		if ($myrow[0]>0) {
			$CancelDelete = 1;
			prnMsg( _('Cannot delete this item record because there are bills of material that require this part as a component'),'warn');
			echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('bills of material that require this part as a component');
		} else {
			$sql= "SELECT COUNT(*) FROM salesorderdetails WHERE stkcode='".$StockID."' GROUP BY stkcode";
			$result = DB_query($sql,$db);
			$myrow = DB_fetch_row($result);
			if ($myrow[0]>0) {
				$CancelDelete = 1;
				prnMsg( _('Cannot delete this item record because there are existing sales orders for this part'),'warn');
				echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('sales order items against this part');
			} else {
				$sql= "SELECT COUNT(*) FROM salesanalysis WHERE stockid='".$StockID."' GROUP BY stockid";
				$result = DB_query($sql,$db);
				$myrow = DB_fetch_row($result);
				if ($myrow[0]>0) {
					$CancelDelete = 1;
					prnMsg(_('Cannot delete this item because sales analysis records exist for it'),'warn');
					echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('sales analysis records against this part');
				} else {
					$sql= "SELECT COUNT(*) FROM purchorderdetails WHERE itemcode='".$StockID."' GROUP BY itemcode";
					$result = DB_query($sql,$db);
					$myrow = DB_fetch_row($result);
					if ($myrow[0]>0) {
						$CancelDelete = 1;
						prnMsg(_('Cannot delete this item because there are existing purchase order items for it'),'warn');
						echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('purchase order item record relating to this part');
					} else {
						$sql = "SELECT SUM(quantity) AS qoh FROM locstock WHERE stockid='".$StockID."' GROUP BY stockid";
						$result = DB_query($sql,$db);
						$myrow = DB_fetch_row($result);
						if ($myrow[0]!=0) {
							$CancelDelete = 1;
							prnMsg( _('Cannot delete this item because there is currently some stock on hand'),'warn');
							echo '<br />' . _('There are') . ' ' . $myrow[0] . ' ' . _('on hand for this part');
						}
					}
				}
			}
		}

	}
	if ($CancelDelete==0) {
		$result = DB_Txn_Begin($db);

			/*Deletes LocStock records*/
			$sql ="DELETE FROM locstock WHERE stockid='".$StockID."'";
			$result=DB_query($sql,$db,_('Could not delete the location stock records because'),'',true);
			/*Deletes Price records*/
			$sql ="DELETE FROM prices WHERE stockid='".$StockID."'";
			$result=DB_query($sql,$db,_('Could not delete the prices for this stock record because'),'',true);
			/*and cascade deletes in PurchData */
			$sql ="DELETE FROM purchdata WHERE stockid='".$StockID."'";
			$result=DB_query($sql,$db,_('Could not delete the purchasing data because'),'',true);
			/*and cascade delete the bill of material if any */
			$sql = "DELETE FROM bom WHERE parent='".$StockID."'";
			$result=DB_query($sql,$db,_('Could not delete the bill of material because'),'',true);
			$sql="DELETE FROM stockmaster WHERE stockid='".$StockID."'";
			$result=DB_query($sql,$db, _('Could not delete the item record'),'',true);

		$result = DB_Txn_Commit($db);

		prnMsg(_('Deleted the stock master record for') . ' ' . $StockID . '....' .
		'<br />. . ' . _('and all the location stock records set up for the part') .
		'<br />. . .' . _('and any bill of material that may have been set up for the part') .
		'<br /> . . . .' . _('and any purchasing data that may have been set up for the part') .
		'<br /> . . . . .' . _('and any prices that may have been set up for the part'),'success');
		echo '<br />';
		unset($_POST['LongDescription']);
		unset($_POST['Description']);
		unset($_POST['EOQ']);
		unset($_POST['rateitem']);
		unset($_POST['CategoryID']);
		unset($_POST['Units']);
		unset($_POST['MBFlag']);
		unset($_POST['Discontinued']);
		unset($_POST['Controlled']);
		unset($_POST['Serialised']);
		unset($_POST['Perishable']);
		unset($_POST['Volume']);
		unset($_POST['KGS']);
		unset($_POST['BarCode']);
		unset($_POST['ReorderLevel']);
		unset($_POST['DiscountCategory']);
		unset($_POST['TaxCat']);
		unset($_POST['DecimalPlaces']);
		unset($_POST['ItemPDF']);
		unset($_SESSION['SelectedStockItem']);
		unset($StockID);
		
		$New=1;
	} //end if Delete Part
}


echo '<form name="ItemForm" enctype="multipart/form-data" method="post" action="' . $_SERVER['PHP_SELF'] . '">
	<table cellspacing="1" cellpadding="2">
	<tr class="oddrow"><td align="center" colspan="2" ><h2>Stock</h2></td></tr> '; // Nested table
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<input type="hidden" name="New" value="'.$New.'">';

if (!isset($StockID) or $StockID=='' or isset($_POST['UpdateCategories'])) {

/*If the page was called without $StockID passed to page then assume a new stock item is to be entered show a form with a part Code field other wise the form showing the fields with the existing entries against the part will show for editing with only a hidden StockID field. New is set to flag that the page may have called itself and still be entering a new part, in which case the page needs to know not to go looking up details for an existing part*/
	if (!isset($StockID)) {
		$StockID='';
	}
	if ($New==1) {
		echo '<tr class="evenrow"><td colspan="2"> <div class="left">'. _('Item Code: <span style="color:#FF0000">*</span>'). '</div><div class="right"><input ' . (in_array('StockID',$Errors) ?  'class="inputerror"' : '' ) .'  type="text"
			value="'.$StockID.'" name="StockID" size=21 maxlength=20 /></div></td></tr>';
	} else {
		echo '<tr class="evenrow"><td colspan="2"> <div class="left">'. _('Item Code: <span style="color:#FF0000">*</span>'). '</div>
					<div class="right">'.$StockID.'</div></td>
				</tr>';
		echo '<input type="hidden" name ="StockID" value="'.$StockID.'" />';
	}

} elseif (!isset($_POST['UpdateCategories']) AND $InputError!=1) { // Must be modifying an existing item and no changes made yet

	$sql = "SELECT stockid,
					description,
					longdescription,
					categoryid,
					units,
					mbflag,
					discontinued,
					controlled,
					serialised,
					perishable,
					eoq,
					rateitem,
					actualost,
					volume,
					kgs,
					barcode,
					discountcategory,
					taxcatid,
					decimalplaces,
					appendfile,
					nextserialno
			FROM stockmaster
			WHERE stockid = '".$StockID."'";
	
	$result = DB_query($sql, $db);
	$myrow = DB_fetch_array($result);

	$_POST['LongDescription'] = $myrow['longdescription'];
	$_POST['Description'] = $myrow['description'];
	$_POST['EOQ']  = $myrow['eoq'];
	$_POST['rateitem']  = $myrow['rateitem'];
	$_POST['CategoryID']  = $myrow['categoryid'];
	$_POST['Units']  = $myrow['units'];
	$_POST['MBFlag']  = $myrow['mbflag'];
	$_POST['Discontinued']  = $myrow['discontinued'];
	$_POST['Controlled']  = $myrow['controlled'];
	$_POST['Serialised']  = $myrow['serialised'];
	$_POST['Perishable']  = $myrow['perishable'];
	$_POST['Volume']  = $myrow['volume'];
	$_POST['KGS']  = $myrow['kgs'];
	$_POST['BarCode']  = $myrow['barcode'];
	$_POST['DiscountCategory']  = $myrow['discountcategory'];
	$_POST['TaxCat'] = $myrow['taxcatid'];
	$_POST['DecimalPlaces'] = $myrow['decimalplaces'];
	$_POST['ItemPDF']  = $myrow['appendfile'];
	$_POST['NextSerialNo'] = $myrow['nextserialno'];

	echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Item Code') . ':</div>
			<div class="right">'.$StockID.'</div></td>
			</tr>';
	echo '<input type="hidden" name="StockID" value="' . $StockID . '" />';

} else { // some changes were made to the data so don't re-set form variables to DB ie the code above
	echo '<tr class="evenrow" ><td colspan="2"> <div class="left">' . _('Item Code: <span style="color:#FF0000">*</span>') . '</div><div class="right" >'.$StockID.'<div></td></tr>';
	echo '<input type="hidden" name="StockID" value="' . $StockID . '" />';
}

if (isset($_POST['Description'])) {
	$Description = $_POST['Description'];
} else {
	$Description ='';
}
echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Part Description') . ' (' . _('short') . '): <span style="color:#FF0000">*</span></div><div class="right"><input ' . (in_array('Description',$Errors) ?  'class="inputerror"' : '' ) .' type="Text" name="Description" size=52 maxlength=50 value="' . $Description . '"></div></td></tr>';

if (isset($_POST['LongDescription'])) {
	$LongDescription = AddCarriageReturns($_POST['LongDescription']);
} else {
	$LongDescription ='';
}
echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Part Description') . ' (' . _('long') . '): <span style="color:#FF0000">*</span></div><div class="right"><textarea ' . (in_array('LongDescription',$Errors) ?  'class="texterror"' : '' ) .'  name="LongDescription" cols=28 rows=4>' . stripslashes($LongDescription) . '</textarea></div></td></tr>';

// Generate selection drop down from pdf_append directory - by emdx,
// developed with examples from http://au2.php.net/manual/en/function.opendir.php
function select_files($dir, $label = '', $select_name = 'ItemPDF', $curr_val = '', $char_length = 60) {
	$teller = 0;
	if (!file_exists($dir)) {
		mkdir($dir);
		chmod($dir, 0777);
	}
	if ($handle = opendir($dir)) {
		$mydir = '<select name="' . $select_name . '">';
		$mydir .= '<option value=0>' . _('none') . '</option>';
		if (isset($_POST['ItemPDF'])) {
			$curr_val = $_POST['ItemPDF'];
		} else {
			$curr_val .=  _('none');
		}
		while (false !== ($file = readdir($handle)))	{
			$files[] = $file;
		}
		closedir($handle);
		sort($files);
		foreach ($files as $val) {
			if (is_file($dir.$val)) {
				$mydir .= '<option value="' . $val . '"';
				$mydir .= ($val == $curr_val) ? ' selected>' : '>';
				$mydir .= $val . '</option>';
				$teller++;
			}
		}
		$mydir .= '';
	}
	return $mydir;
}
if (!isset($_POST['ItemPDF'])) {
	$_POST['ItemPDF'] = '';
}
echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('PDF attachment (.pdf)') . ': <span style="color:#FF0000">*</span>' . '</div>
		<div class="right">' . select_files('companies/' . $_SESSION['DatabaseName'] .'/pdf_append/','' , 'ItemPDF', $_POST['ItemPDF'], '60') . '</div></td></tr>';

// Add image upload for New Item  - by Ori
echo '<tr class="evenrow"><td colspan="2"> <div class="left">'. _('Image File (.jpg)') . ': <span style="color:#FF0000">*</span></div><div class="right" ><input type="file" id="ItemPicture" name="ItemPicture"></div></td>';

 if (function_exists('imagecreatefromjpg')){
	$StockImgLink = '<img src="GetStockImage.php?automake=1&textcolor=FFFFFF&bgcolor=CCCCCC'.
		'&StockID='.urlencode($StockID).
		'&text='.
		'&width=64'.
		'&height=64'.
		'" >';
} else {
	if( isset($StockID) and file_exists($_SESSION['part_pics_dir'] . '/' .$StockID.'.jpg') ) {
		$StockImgLink = '<img src="GetStockImage.php?automake=1&textcolor=FFFFFF&bgcolor=CCCCCC&StockID=' . $StockID . '&text=&width=120&height=120">';
	} else {
		$StockImgLink = _('No Image');
	}
}

if ($StockImgLink!=_('No Image')) {
	echo '</td><td>' . _('Image') . '<br />'.$StockImgLink . '</td></tr>';
}

// EOR Add Image upload for New Item  - by Ori

 echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Category') . ': <span style="color:#FF0000">*</span></div><div class="right"><select name="CategoryID" onChange="ReloadForm(ItemForm.UpdateCategories)">';

$sql = "SELECT categoryid, categorydescription FROM stockcategory";
$ErrMsg = _('The stock categories could not be retrieved because');
$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
$result = DB_query($sql,$db,$ErrMsg,$DbgMsg);

while ($myrow=DB_fetch_array($result)){
	if (!isset($_POST['CategoryID']) or $myrow['categoryid']==$_POST['CategoryID']){
		echo '<option selected value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
	} else {
		echo '<option value="'. $myrow['categoryid'] . '">' . $myrow['categorydescription'];
	}
	$Category=$myrow['categoryid'];
}

if (!isset($_POST['CategoryID'])) {
	$_POST['CategoryID']=$Category;
}

echo '</select></div><span><a target="_blank" href="'. $rootpath . '/StockCategories.php">' . _('Add or Modify Stock Categories') . '</a></span></td></tr>';

if (!isset($_POST['EOQ']) or $_POST['EOQ']==''){
	$_POST['EOQ']=0;
}

if (!isset($_POST['rateitem']) or $_POST['rateitem']==''){
	$_POST['rateitem']=0;
}

if (!isset($_POST['Volume']) or $_POST['Volume']==''){
	$_POST['Volume']=0;
}
if (!isset($_POST['KGS']) or $_POST['KGS']==''){
	$_POST['KGS']=0;
}
if (!isset($_POST['Controlled']) or $_POST['Controlled']==''){
	$_POST['Controlled']=0;
}
if (!isset($_POST['Serialised']) or $_POST['Serialised']=='' || $_POST['Controlled']==0){
	$_POST['Serialised']=0;
}
if (!isset($_POST['DecimalPlaces']) or $_POST['DecimalPlaces']==''){
	$_POST['DecimalPlaces']=0;
}
if (!isset($_POST['Discontinued']) or $_POST['Discontinued']==''){
	$_POST['Discontinued']=0;
}
if (!isset($_POST['Pansize'])) {
	$_POST['Pansize']=0;
}
if (!isset($_POST['ShrinkFactor'])) {
	$_POST['ShrinkFactor']=0;
}
if (!isset($_POST['NextSerialNo'])) {
	$_POST['NextSerialNo']=0;
}


echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Economic Order Quantity') . ': <span style="color:#FF0000">*</span></div><div class="right"><input ' . (in_array('EOQ',$Errors) ?  'class="inputerror"' : '' ) .'   type="Text" class="number" name="EOQ" size=12 maxlength=10 value="' . $_POST['EOQ'] . '"></div></td></tr>';
echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Rate of Items') . ': <span style="color:#FF0000">*</span></div><div class="right" ><input ' . (in_array('rateitem',$Errors) ?  'class="inputerror"' : '' ) .'   type="Text" class="number" name="rateitem" size=12 maxlength=10 value="' . $_POST['rateitem'] . '"></div></td></tr>';
echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Packaged Volume (metres cubed)') . ': <span style="color:#FF0000">*</span></div><div class="right" ><input ' . (in_array('Volume',$Errors) ?  'class="inputerror"' : '' ) .'   type="Text" class="number" name="Volume" size=12 maxlength=10 value="' . $_POST['Volume'] . '"></div></td></tr>';

echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Packaged Weight (KGs)') . ': <span style="color:#FF0000">*</span></div><div class="right"><input ' . (in_array('KGS',$Errors) ?  'class="inputerror"' : '' ) .'   type="Text" class="number" name="KGS" size=12 maxlength=10 value="' . $_POST['KGS'] . '"></div></td></tr>';

echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Units of Measure') . ': <span style="color:#FF0000">*</span></div><div class="right"><select ' . (in_array('Description',$Errors) ?  'class="selecterror"' : '' ) .'  name="Units">';



$sql = "SELECT unitname FROM unitsofmeasure ORDER by unitname";
$UOMResult = DB_query($sql,$db);

if (!isset($_POST['Units'])) {
	$UOMrow['unitname']=_('each');
}
while( $UOMrow = DB_fetch_array($UOMResult) ) {
	 if (isset($_POST['Units']) and $_POST['Units']==$UOMrow['unitname']){
		echo '<option selected value="' . $UOMrow['unitname'] . '">' . $UOMrow['unitname'] . '</option>';
	 } else {
		echo '<option value="' . $UOMrow['unitname'] . '">' . $UOMrow['unitname']  . '</option>';
	 }
}

echo '</select></div></td></tr>';

echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Assembly, Kit, Manufactured or Service/Labour') . ': <span style="color:#FF0000">*</span></div><div class="right" ><select name="MBFlag">';
if ($_POST['MBFlag']=='A'){
	echo '<option selected value="A">' . _('Assembly') . '</option>';
} else {
	echo '<option value="A">' . _('Assembly') . '</option>';
}
if (!isset($_POST['MBFlag']) or $_POST['MBFlag']=='K'){
	echo '<option selected value="K">' . _('Kit') . '</option>';
} else {
	echo '<option value="K">' . _('Kit') . '</option>';
}
if (!isset($_POST['MBFlag']) or $_POST['MBFlag']=='M'){
	echo '<option selected value="M">' . _('Manufactured') . '</option>';
} else {
	echo '<option value="M">' . _('Manufactured') . '</option>';
}
if (!isset($_POST['MBFlag']) or $_POST['MBFlag']=='G' OR !isset($_POST['MBFlag']) OR $_POST['MBFlag']==''){
	echo '<option selected value="G">' . _('Phantom') . '</option>';
} else {
	echo '<option value="G">' . _('Phantom') . '</option>';
}
if (!isset($_POST['MBFlag']) or $_POST['MBFlag']=='B' OR !isset($_POST['MBFlag']) OR $_POST['MBFlag']==''){
	echo '<option selected value="B">' . _('Purchased') . '</option>';
} else {
	echo '<option value="B">' . _('Purchased') . '</option>';
}

if (isset($_POST['MBFlag']) and $_POST['MBFlag']=='D'){
	echo '<option selected value="D">' . _('Service/Labour') . '</option>';
} else {
	echo '<option value="D">' . _('Service/Labour') . '</option>';
}

echo '</select></div></td></tr>';

echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Current or Obsolete') . ': <span style="color:#FF0000">*</span></div><div class="right"  ><select name="Discontinued">';
if ($_POST['Discontinued']==0){
	echo '<option selected value=0>' . _('Current') . '</option>';
} else {
	echo '<option value=0>' . _('Current') . '</option>';
}
if ($_POST['Discontinued']==1){
	echo '<option selected value=1>' . _('Obsolete') . '</option>';
} else {
	echo '<option value=1>' . _('Obsolete') . '</option>';
}
echo '</select></div></td></tr>';

echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Batch, Serial or Lot Control') . ': <span style="color:#FF0000">*</span></div><div class="right"><select name="Controlled">';

if ($_POST['Controlled']==0){
	echo '<option selected value=0>' . _('No Control') . '</option>';
} else {
		echo '<option value=0>' . _('No Control') . '</option>';
}
if ($_POST['Controlled']==1){
	echo '<option selected value=1>' . _('Controlled'). '</option>';
} else {
	echo '<option value=1>' . _('Controlled'). '</option>';
}
echo '</select></div></td></tr>';

echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Serialised') . ': <span style="color:#FF0000">*</span></div><div class="right"><select ' . (in_array('Serialised',$Errors) ?  'class="selecterror"' : '' ) .'  name="Serialised">';

if ($_POST['Serialised']==0){
		echo '<Option selected value=0>' . _('No'). '</option>';
} else {
		echo '<option value=0>' . _('No'). '</option>';
}
if ($_POST['Serialised']==1){
		echo '<option selected value=1>' . _('Yes') . '</option>';
} else {
		echo '<option value=1>' . _('Yes'). '</option>';
}
echo '</select></div>&nbsp;&nbsp;<i>' . _('Note') . ', ' . _('this has no effect if the item is not Controlled') . '</i></td></tr>';

if ($_POST['Serialised']==1 AND $_POST['MBFlag']=='M'){
	echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Next Serial No (>0 for auto numbering)') . ': <span style="color:#FF0000">*</span></div><div class="right"><input ' . (in_array('NextSerialNo',$Errors) ?  'class="inputerror"' : '' ) .' type="text" name="NextSerialNo" size=15 maxlength=15 value="' . $_POST['NextSerialNo'] . '"></div></td></tr>';
} else {
	echo '<input type="hidden" name="NextSerialNo" value="0">';
}

echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Perishable') . ': <span style="color:#FF0000">*</span></div><div class="right" ><select name="Perishable">';

if (!isset($_POST['Perishable']) or $_POST['Perishable']==0){
		echo '<option selected value=0>' . _('No'). '</option>';
} else {
		echo '<option value=0>' . _('No'). '</option>';
}
if (isset($_POST['Perishable']) and $_POST['Perishable']==1){
		echo '<option selected value=1>' . _('Yes'). '</option>';
} else {
		echo '<option value=1>' . _('Yes'). '</option>';
}
echo '</select></div></td></tr>';

echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Decimal Places for display Quantity') . ': <span style="color:#FF0000">*</span></div><div class="right"><input type="text" class="number" name="DecimalPlaces" size=1 maxlength=1 value="' . $_POST['DecimalPlaces'] . '"></div></td></tr>';

if (isset($_POST['BarCode'])) {
	$BarCode = $_POST['BarCode'];
} else {
	$BarCode='';
}
echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Bar Code') . ': <span style="color:#FF0000">*</span></div><div class="right"><input ' . (in_array('BarCode',$Errors) ?  'class="inputerror"' : '' ) .'  type="Text" name="BarCode" size=22 maxlength=20 value="' . $BarCode . '"></div></td></tr>';

if (isset($_POST['DiscountCategory'])) {
	$DiscountCategory = $_POST['DiscountCategory'];
} else {
	$DiscountCategory='';
}
echo '<tr class="evenrow"><td colspan="2"> <div class="left">' . _('Discount Category') . ': <span style="color:#FF0000">*</span></div><div class="right"><input type="Text" name="DiscountCategory" size=2 maxlength=2 value="' . $DiscountCategory . '"></div></td></tr>';

echo '<tr class="oddrow"><td colspan="2"> <div class="left">' . _('Tax Category') . ': <span style="color:#FF0000">*</span></div><div class="right" ><select name="TaxCat">';
$sql = "SELECT taxcatid, taxcatname FROM taxcategories ORDER BY taxcatname";
$result = DB_query($sql, $db);

if (!isset($_POST['TaxCat'])){
	$_POST['TaxCat'] = $_SESSION['DefaultTaxCategory'];
}

while ($myrow = DB_fetch_array($result)) {
	if ($_POST['TaxCat'] == $myrow['taxcatid']){
		echo '<option selected value=' . $myrow['taxcatid'] . '>' . $myrow['taxcatname'] . '</option>';
	} else {
		echo '<option value=' . $myrow['taxcatid'] . '>' . $myrow['taxcatname'] . '</option>';
	}
} //end while loop

echo '</select><div></td></tr>';

echo '<tr class="evenrow">
		<td colspan="2"> <div class="left">' . _('Pan Size') . ': <span style="color:#FF0000">*</span></div>
		<div class="right"><input type="Text" class="number" name="Pansize" size="6" maxlength="6" value="' . $_POST['Pansize'] . '"></div>
	</tr>
	 <tr class="oddrow">
		<td colspan="2"> <div class="left">' . _('Shrinkage Factor') . ': <span style="color:#FF0000">*</span></div>
		<div class="right" ><input type="Text" class="number" name="ShrinkFactor" size="6" maxlength="6" value="' . $_POST['ShrinkFactor'] . '"></div></td>
	</tr>';

echo '<tr class="evenrow" ><td  align="center" colspan="2">

<input type="hidden" name="PropertyCounter" value="' . $PropertyCounter . '" />';

if ($New==1) {
	echo '<input type="Submit" name="submit" value="' . _('Insert New Item') . '" />';
	echo '<input type="submit" name="UpdateCategories" style="visibility:hidden;width:1px" value="' . _('Categories') . '" />';
	echo '<a href="' . $rootpath . '/SelectProduct.php">' . _('Back to Items') . '</a><br />' . "\n";
} else {

	// Now the form to enter the item properties

	echo '<input type="submit" name="submit" value="' . _('Update') . '" />';
	echo '<input type="submit" name="UpdateCategories" style="visibility:hidden;width:1px" value="' . _('Categories') . '" />';
	echo '<p>';
	prnMsg( _('Only click the Delete button if you are sure you wish to delete the item!') .  _('Checks will be made to ensure that there are no stock movements, sales analysis records, sales order items or purchase order items for the item') . '. ' . _('No deletions will be allowed if they exist'), 'warn', _('WARNING'));
	echo '<p><input type="Submit" name="delete" value="' . _('Delete This Item') . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');">';
}
echo '</td></tr><div class="centre">';

if (!isset($_POST['CategoryID'])) {
	$_POST['CategoryID'] = '';
}

$sql = "SELECT stkcatpropid,
				label,
				controltype,
				defaultvalue,
				numericvalue,
				minimumvalue,
				maximumvalue
		FROM stockcatproperties
		WHERE categoryid ='" . $_POST['CategoryID'] . "'
		AND reqatsalesorder =0
		ORDER BY stkcatpropid";

$PropertiesResult = DB_query($sql,$db);
$PropertyCounter = 0;
$PropertyWidth = array();

echo '<br /><table >';
if (DB_num_rows($PropertiesResult)>0) {
	echo '<tr><th colspan="2">' . _('Item Category Properties') . '</th></tr>';
}

while ($PropertyRow=DB_fetch_array($PropertiesResult)){

	if (isset($StockID)) {
		$PropValResult = DB_query("SELECT value FROM
									stockitemproperties
									WHERE stockid='" . $StockID . "'
									AND stkcatpropid ='" . $PropertyRow['stkcatpropid']."'",
								$db);
		$PropValRow = DB_fetch_row($PropValResult);
		$PropertyValue = $PropValRow[0];
	} else {
		$PropertyValue =  '';
	}
	echo '<input type="hidden" name="PropID' . $PropertyCounter . '" value="' .$PropertyRow['stkcatpropid'] .'" />';

	echo '<tr><td>' . $PropertyRow['label'] . '</td>
				<td>';
	switch ($PropertyRow['controltype']) {
	 	case 0; //textbox
	 		if ($PropertyRow['numericvalue']==1) {
				echo '<input type="textbox" class="number" name="PropValue' . $PropertyCounter . '" size="20" maxlength="100" value="' . $PropertyValue . '">';
				echo _('A number between') . ' ' . $PropertyRow['minimumvalue'] . ' ' . _('and') . ' ' . $PropertyRow['maximumvalue'] . ' ' . _('is expected');
			} else {
				echo '<input type="textbox" name="PropValue' . $PropertyCounter . '" size="20" maxlength="100" value="' . $PropertyValue . '">';
			}
	 		break;
	 	case 1; //select box
	 		$OptionValues = explode(',',$PropertyRow['defaultvalue']);
			echo '<select name="PropValue' . $PropertyCounter . '">';
			foreach ($OptionValues as $PropertyOptionValue){
				if ($PropertyOptionValue == $PropertyValue){
					echo '<option selected value="' . $PropertyOptionValue . '">' . $PropertyOptionValue . '</option>';
				} else {
					echo '<option value="' . $PropertyOptionValue . '">' . $PropertyOptionValue . '</option>';
				}
			}
			echo '</select>';
			break;
		case 2; //checkbox
			echo '<input type="checkbox" name="PropValue' . $PropertyCounter . '"';
			if ($PropertyValue==1){
				echo '"checked"';
			}
			echo '>';
			break;
	} //end switch
	echo '<input type="hidden" name="PropType' . $PropertyCounter .'" value=' . $PropertyRow['controltype'] . '>';
	echo '</td></tr>';
	$PropertyCounter++;
	
} //end loop round properties for the item category
unset($StockID);

echo '</table>';

/*echo '<input type="hidden" name="PropertyCounter" value="' . $PropertyCounter . '" />';

if ($New==1) {
	echo '<input type="Submit" name="submit" value="' . _('Insert New Item') . '" />';
	echo '<input type="submit" name="UpdateCategories" style="visibility:hidden;width:1px" value="' . _('Categories') . '" />';

} else {

	// Now the form to enter the item properties

	echo '<input type="submit" name="submit" value="' . _('Update') . '" />';
	echo '<input type="submit" name="UpdateCategories" style="visibility:hidden;width:1px" value="' . _('Categories') . '" />';
	echo '<p>';
	prnMsg( _('Only click the Delete button if you are sure you wish to delete the item!') .  _('Checks will be made to ensure that there are no stock movements, sales analysis records, sales order items or purchase order items for the item') . '. ' . _('No deletions will be allowed if they exist'), 'warn', _('WARNING'));
	echo '<p><input type="Submit" name="delete" value="' . _('Delete This Item') . '" onclick="return confirm(\'' . _('Are You Sure?') . '\');">';
}*/

echo '</form>';

include('includes/footer.inc');
echo'</div>';

?>