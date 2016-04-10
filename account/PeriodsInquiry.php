<?php

/* $Id: PeriodsInquiry.php 4574 2011-05-25 10:30:45Z daintree $*/

include ('includes/session.inc');

$title = _('Periods Inquiry');

include('includes/header.inc');

$SQL = "SELECT periodno ,
		lastdate_in_period
		FROM periods
		ORDER BY periodno";

$ErrMsg =  _('No periods were returned by the SQL because');
$PeriodsResult = DB_query($SQL,$db,$ErrMsg);

echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/transactions.png" title="' . $title . '" alt="" />' . ' '
		. $title . '</p>';

/*show a table of the orders returned by the SQL */

$NumberOfPeriods = DB_num_rows($PeriodsResult);
$PeriodsInTable = round($NumberOfPeriods/3,0);

$TableHeader = '<tr><th>' . _('Period Number') . '</th>
					<th>' . _('Date of Last Day') . '</th>
				</tr>';

echo '<table><tr>';

for ($i=0;$i<3;$i++) {
	echo '<td valign="top">';
	echo '<table cellpadding=2 colspan=2 class="selection">';
	echo $TableHeader;
	$k=0;
	$j=0; 
	while ($myrow=DB_fetch_array($PeriodsResult)){
		if ($k==1){
			echo '<tr class="EvenTableRows">';
			$k=0;
		} else {
			echo '<tr class="OddTableRows">';
			$k++;
		}
		echo '<td>' . $myrow['periodno'] . '</td>
			  <td>' . ConvertSQLDate($myrow['lastdate_in_period']) . '</td>
			</tr>';
		$j++;
		if ($j==$PeriodsInTable){
			break;
		}
	}
	echo '</table>';
	echo '</td>';
}

echo '</tr></table>';
//end of while loop

include('includes/footer.inc');
?>
