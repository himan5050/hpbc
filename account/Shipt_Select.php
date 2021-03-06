<?php

/* $Id: Shipt_Select.php 4632 2011-07-16 02:59:14Z daintree $*/

include('includes/session.inc');
$title = _('Search Shipments');
include('includes/header.inc');
echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/magnifier.png" title="' . _('Search') .
	'" alt="" />' . ' ' . $title . '</p>';

if (isset($_GET['SelectedStockItem'])){
	$SelectedStockItem=$_GET['SelectedStockItem'];
} elseif (isset($_POST['SelectedStockItem'])){
	$SelectedStockItem=$_POST['SelectedStockItem'];
}

if (isset($_GET['ShiptRef'])){
	$ShiptRef=$_GET['ShiptRef'];
} elseif (isset($_POST['ShiptRef'])){
	$ShiptRef=$_POST['ShiptRef'];
}

if (isset($_GET['SelectedSupplier'])){
	$SelectedSupplier=$_GET['SelectedSupplier'];
} elseif (isset($_POST['SelectedSupplier'])){
	$SelectedSupplier=$_POST['SelectedSupplier'];
}

echo '<form action="' . $_SERVER['PHP_SELF'] . '" method=post>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';


If (isset($_POST['ResetPart'])) {
     unset($SelectedStockItem);
}

If (isset($ShiptRef) && $ShiptRef!="") {
	if (!is_numeric($ShiptRef)){
		  echo '<br />';
		  prnMsg( _('The Shipment Number entered MUST be numeric') );
		  unset ($ShiptRef);
	} else {
		echo _('Shipment Number'). ' - '. $ShiptRef;
	}
} else {
	if (isset($SelectedSupplier)) {
		echo '<br />' ._('For supplier'). ': '. $SelectedSupplier . ' ' . _('and'). ' ';
		echo '<input type=hidden name="SelectedSupplier" value="'. $SelectedSupplier. '">';
	}
	If (isset($SelectedStockItem)) {
		 echo _('for the part'). ': ' . $SelectedStockItem . '.';
		echo '<input type=hidden name="SelectedStockItem" value="'. $SelectedStockItem. '">';
	}
}

if (isset($_POST['SearchParts'])) {

	If ($_POST['Keywords'] AND $_POST['StockCode']) {
		echo '<br />';
		prnMsg( _('Stock description keywords have been used in preference to the Stock code extract entered'),'info');
	}
	$SQL = "SELECT stockmaster.stockid,
			description,
			SUM(locstock.quantity) AS qoh,
			units,
			SUM(purchorderdetails.quantityord-purchorderdetails.quantityrecd) AS qord
		FROM stockmaster INNER JOIN locstock
			ON stockmaster.stockid = locstock.stockid
		INNER JOIN purchorderdetails
			ON stockmaster.stockid=purchorderdetails.itemcode";

	If ($_POST['Keywords']) {
		//insert wildcard characters in spaces
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		$SQL .= " WHERE purchorderdetails.shiptref IS NOT NULL
			AND purchorderdetails.shiptref<>0
			AND stockmaster.description " . LIKE . " '$SearchString'
			AND categoryid='" . $_POST['StockCat'] . "'";

	 } elseif ($_POST['StockCode']){

		$SQL .= " WHERE purchorderdetails.shiptref IS NOT NULL
			AND purchorderdetails.shiptref<>0
			AND stockmaster.stockid " . LIKE . " '%" . $_POST['StockCode'] . "%'
			AND categoryid='" . $_POST['StockCat'] ."'";

	 } elseif (!$_POST['StockCode'] AND !$_POST['Keywords']) {
		$SQL .= " WHERE purchorderdetails.shiptref IS NOT NULL
			AND purchorderdetails.shiptref<>0
			AND stockmaster.categoryid='" . $_POST['StockCat'] . "'";

	 }
	$SQL .= "  GROUP BY stockmaster.stockid,
				stockmaster.description,
				stockmaster.units
		 	ORDER BY stockmaster.stockid";

	$ErrMsg = _('No Stock Items were returned from the database because'). ' - '. DB_error_msg($db);
	$StockItemsResult = DB_query($SQL,$db, $ErrMsg);

}

if (!isset($ShiptRef) or $ShiptRef==""){
	echo '<table class=selection><tr><td>';
	echo _('Shipment Number'). ': <input type="text" name="ShiptRef" MAXLENGTH =10 size=10> '.
		_('Into Stock Location').' :<select name="StockLocation"> ';
	$sql = "SELECT loccode, locationname FROM locations";
	$resultStkLocs = DB_query($sql,$db);
	while ($myrow=DB_fetch_array($resultStkLocs)){
		if (isset($_POST['StockLocation'])){
			if ($myrow['loccode'] == $_POST['StockLocation']){
			echo '<option selected Value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			} else {
			echo '<option Value="' . $myrow['loccode'] . '">' . $myrow['locationname'] . '</option>';
			}
		} elseif ($myrow['loccode']==$_SESSION['UserStockLocation']){
			$_POST['StockLocation'] = $_SESSION['UserStockLocation'];
			echo '<option selected Value="' . $myrow['loccode'] . '">' . $myrow['locationname']  . '</option>';
		} else {
			echo '<option Value="' . $myrow['loccode'] . '">' . $myrow['locationname']  . '</option>';
		}
	}

	echo '</select>';
	echo ' <select name="OpenOrClosed">';
	if ($_POST['OpenOrClosed']==1){
		echo '<option selected VALUE=1>'. _('Closed Shipments Only')  . '</option>';
		echo '<option VALUE=0>'. _('Open Shipments Only')  . '</option>';
	} else {
		$_POST['OpenOrClosed']=0;
		echo '<option VALUE=1>'. _('Closed Shipments Only')  . '</option>';
		echo '<option selected VALUE=0>'. _('Open Shipments Only')  . '</option>';
	}
	echo '</select></td></tr></table>';

	echo '<br /><div class=centre><input type=submit name="SearchShipments" VALUE="'. _('Search Shipments'). '"></div></div><br />';
}

$SQL="SELECT categoryid,
		categorydescription
	FROM stockcategory
	WHERE stocktype<>'D'
	ORDER BY categorydescription";
$result1 = DB_query($SQL,$db);

echo '<table class=selection>';
echo '<tr><th colspan=5><font size=3 color=navy>'._('To search for shipments for a specific part use the part selection facilities below') . '</font></th></tr>';
?>
<tr>
<td><font size=1><?php echo _('Select a stock category');?>:</font>
<select name="StockCat">
<?php
while ($myrow1 = DB_fetch_array($result1)) {
	if (isset($_POST['StockCat']) and $myrow1['categoryid']==$_POST['StockCat']){
		echo '<option selected value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription']  . '</option>';
	} else {
		echo '<option value="'. $myrow1['categoryid'] . '">' . $myrow1['categorydescription']  . '</option>';
	}
}
?>
</select>
<td><font size=1><?php echo _('Enter text extracts in the');?> <b><?php echo _('description');?></b>:</font></td>
<td><input type="Text" name="Keywords" size=20 maxlength=25></td></tr>
<tr><td></td>
<td><font SIZE 3><b><?php echo _('OR');?> </b></font><font size=1><?php echo _('Enter extract of the');?> <b><?php echo _('Stock Code');?></b>:</font></td>
<td><input type="Text" name="StockCode" size=15 maxlength=18></td>
</tr>
</table><br />

<?php
echo '<div class=centre><input type=submit name="SearchParts" value="'._('Search Parts Now').'">';
echo '<input type=submit name="ResetPart" value="'. _('Show All') .'"></div><br />';

if (isset($StockItemsResult)) {

	echo '<table cellpadding=2 colspan=7 class="selection">';
	$TableHeader = '<tr>
			<th>'. _('Code').'</th>
			<th>'. _('Description').'</th>
			<th>'. _('On Hand').'</th>
			<th>'. _('Orders') . '<br />' . _('Outstanding').'</th>
			<th>'. _('Units').'</th>
			</tr>';

	echo $TableHeader;

	$j = 1;
	$k=0; //row colour counter

	while ($myrow=DB_fetch_array($StockItemsResult)) {

		if ($k==1){
			echo '<tr class="EvenTableRows">';
			$k=0;
		} else {
			echo '<tr class="OddTableRows">';
			$k=1;
		}
/*
Code	 Description	On Hand		 Orders Ostdg     Units		 Code	Description 	 On Hand     Orders Ostdg	Units	 */
		printf('<td><input type=submit name="SelectedStockItem" VALUE="%s" /></td>
			<td>%s</td>
			<td class=number>%s</td>
			<td class=number>%s</td>
			<td>%s</td></tr>',
			$myrow['stockid'], $myrow['description'], $myrow['qoh'], $myrow['qord'],$myrow['units']);

		$j++;
		If ($j == 15){
			$j=1;
			echo $TableHeader;
		}
//end of page full new headings if
	}
//end of while loop

	echo '</table>';

}
//end if stock search results to show
  else {

	//figure out the SQL required from the inputs available

	if (isset($ShiptRef) && $ShiptRef !="") {
		$SQL = "SELECT shipments.shiptref,
				vessel,
				voyageref,
				suppliers.suppname,
				shipments.eta,
				shipments.closed
			FROM shipments INNER JOIN suppliers
				ON shipments.supplierid = suppliers.supplierid
			WHERE shipments.shiptref='". $ShiptRef . "'";
	} else {
		$SQL = "SELECT DISTINCT shipments.shiptref, vessel, voyageref, suppliers.suppname, shipments.eta, shipments.closed
			FROM shipments INNER JOIN suppliers
				ON shipments.supplierid = suppliers.supplierid
			INNER JOIN purchorderdetails
				ON purchorderdetails.shiptref=shipments.shiptref
			INNER JOIN purchorders
				ON purchorderdetails.orderno=purchorders.orderno";

		if (isset($SelectedSupplier)) {

			if (isset($SelectedStockItem)) {
					$SQL .= " WHERE purchorderdetails.itemcode='". $SelectedStockItem ."'
						AND shipments.supplierid='" . $SelectedSupplier ."'
						AND purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
						AND shipments.closed='" . $_POST['OpenOrClosed'] . "'";
			} else {
				$SQL .= " WHERE shipments.supplierid='" . $SelectedSupplier ."'
					AND purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
					AND shipments.closed='" . $_POST['OpenOrClosed'] ."'";
			}
		} else { //no supplier selected
			if (isset($SelectedStockItem)) {
				$SQL .= " WHERE purchorderdetails.itemcode='". $SelectedStockItem ."'
					AND purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
					AND shipments.closed='" . $_POST['OpenOrClosed'] . "'";
			} else {
				$SQL .= " WHERE purchorders.intostocklocation = '". $_POST['StockLocation'] . "'
					AND shipments.closed='" . $_POST['OpenOrClosed'] . "'";
			}

		} //end selected supplier
	} //end not order number selected

	$ErrMsg = _('No shipments were returned by the SQL because');
	$ShipmentsResult = DB_query($SQL,$db,$ErrMsg);


	if (DB_num_rows($ShipmentsResult)>0){
		/*show a table of the shipments returned by the SQL */

		echo '<table cellpadding=2 colspan=7 width=95% class=selection>';
		$TableHeader = '<tr>
				<th>'. _('Shipment'). '</th>
				<th>'. _('Supplier'). '</th>
				<th>'. _('Vessel'). '</th>
				<th>'. _('Voyage'). '</th>
				<th>'. _('Expected Arrival'). '</th>
				</tr>';

		echo $TableHeader;

		$j = 1;
		$k=0; //row colour counter
		while ($myrow=DB_fetch_array($ShipmentsResult)) {


			if ($k==1){ /*alternate bgcolour of row for highlighting */
				echo '<tr class="EvenTableRows">';
				$k=0;
			} else {
				echo '<tr class="OddTableRows">';
				$k++;
			}

			$URL_Modify_Shipment = $rootpath . '/Shipments.php?SelectedShipment=' . $myrow['shiptref'];
			$URL_View_Shipment = $rootpath . '/ShipmentCosting.php?SelectedShipment=' . $myrow['shiptref'];

			$FormatedETA = ConvertSQLDate($myrow['eta']);
			/* ShiptRef   Supplier  Vessel  Voyage  ETA */

			if ($myrow['closed']==0){

				$URL_Close_Shipment = $URL_View_Shipment . '&Close=Yes';

				printf('<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="%s">'._('Costing').'</a></td>
					<td><a href="%s">'._('Modify').'</a></td>
					<td><a href="%s"><b>'._('Close').'</b></a></td>
					</tr>',
					$myrow['shiptref'],
					$myrow['suppname'],
					$myrow['vessel'],
					$myrow['voyageref'],
					$FormatedETA,
					$URL_View_Shipment,
					$URL_Modify_Shipment,
					$URL_Close_Shipment);

			} else {
				printf('<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="%s">'._('Costing').'</a></td>
					</tr>',
					$myrow['shiptref'],
					$myrow['suppname'],
					$myrow['vessel'],
					$myrow['voyage'],
					$FormatedETA,
					$URL_View_Shipment);
			}
			$j++;
			If ($j == 15){
				$j=1;
				echo $TableHeader;
			}
		//end of page full new headings if
		}
		//end of while loop

		echo '</table>';
	} // end if shipments to show
}

echo '</form>';
include('includes/footer.inc');
?>