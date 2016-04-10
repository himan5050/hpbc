<?php
//define('UL_OK',  1);		/* User verified, session initialised */
define('UL_NOTVALID', 1);	/* User/password do not agree */
define('UL_BLOCKED', 2);	/* Account locked, too many failed logins */
define('UL_CONFIGERR', 3);	/* Configuration error in webERP or server */
define('UL_SHOWLOGIN', 4);
define('UL_MAINTENANCE', 5);
	global $debug;

$Name=$_REQUEST['uname'];
$Password=$_REQUEST['upass'];
$burl=$_GET['url'];
//$db=$_REQUEST['db'];
session_start();
mysql_connect("10.10.1.22","dsjescst","dsje@321");
//mysql_connect("localhost","root","");
$db=mysql_select_db("hpbcnew");
	if (!isset($_SESSION['AccessLevel']) OR $_SESSION['AccessLevel'] == '' OR
		(isset($Name) AND $Name != '')) {
	/* if not logged in */
		$_SESSION['AccessLevel'] = '';
		$_SESSION['CustomerID'] = '';
		$_SESSION['UserBranch'] = '';
		$_SESSION['SalesmanLogin'] = '';
		$_SESSION['Module'] = '';
		$_SESSION['PageSize'] = '';
		$_SESSION['UserStockLocation'] = '';
		//$_SESSION['AttemptsCounter']++;
		// Show login screen
		if (!isset($Name) or $Name == '') {
			return  UL_SHOWLOGIN;
		}
		$sql = "SELECT *
						FROM www_users
						WHERE www_users.userid='" . $Name . "'
						AND (www_users.password='" . md5($Password) . "'
						OR  www_users.password='" . $Password . "')";
		//$ErrMsg = _('Could not retrieve user details on login because');
		$debug =1;
		$Auth_Result = mysql_query($sql);
		// Populate session variables with data base results
		if (mysql_num_rows($Auth_Result) > 0) {
			$myrow = mysql_fetch_array($Auth_Result);
			if ($myrow['blocked']==0){
			//the account is blocked
				return  UL_BLOCKED;
			}
			/*reset the attempts counter on successful login */
			$_SESSION['UserID'] = $myrow['userid'];
			$_SESSION['uid'] = $myrow['uid'];
			$_SESSION['AttemptsCounter'] = 0;
			$_SESSION['AccessLevel'] = $myrow['fullaccess'];
			$_SESSION['CustomerID'] = $myrow['customerid'];
			$_SESSION['UserBranch'] = $myrow['branchcode'];
			$_SESSION['DefaultPageSize'] = $myrow['pagesize'];
			$_SESSION['UserStockLocation'] = $myrow['defaultlocation'];
			$_SESSION['UserEmail'] = $myrow['email'];
			$_SESSION['ModulesEnabled'] = explode(",", $myrow['modulesallowed']);
			$_SESSION['UsersRealName'] = $myrow['realname'];
			$_SESSION['Theme'] = $myrow['theme'];
			$_SESSION['Language'] = $myrow['language'];
			$_SESSION['SalesmanLogin'] = $myrow['salesman'];
			$_SESSION['DatabaseName']='hpbcnew';
			if (isset($myrow['pdflanguage'])) {
				$_SESSION['PDFLanguage'] = $myrow['pdflanguage'];
			} else {
				$_SESSION['PDFLanguage'] = '0'; //default to latin western languages
			}

			if ($myrow['displayrecordsmax'] > 0) {
				$_SESSION['DisplayRecordsMax'] = $myrow['displayrecordsmax'];
			} else {
				$_SESSION['DisplayRecordsMax'] = $_SESSION['DefaultDisplayRecordsMax'];  // default comes from config.php
			}

			$sql = "UPDATE www_users SET lastvisitdate='". date('Y-m-d H:i:s') ."'
							WHERE www_users.userid='" . $Name . "'";
			$Auth_Result = mysql_query($sql);
			/*get the security tokens that the user has access to */
			$sql = "SELECT tokenid FROM securitygroups
							WHERE secroleid =  '" . $_SESSION['AccessLevel'] . "'";
			$Sec_Result = mysql_query($sql);
			$_SESSION['AllowedPageSecurityTokens'] = array();
			if (mysql_num_rows($Sec_Result)==0){
				return  UL_CONFIGERR;
			} else {
				$i=0;
				while ($myrow = mysql_fetch_row($Sec_Result)){
					$_SESSION['AllowedPageSecurityTokens'][$i] = $myrow[0];
					$i++;
				}
			}
			//  Temporary shift - disable log messages - how temporary?
		} 
	}		// End of userid/password check
	// Run with debugging messages for the system administrator(s) but not anyone else

	//return   UL_OK;		    /* All is well */
	$sqluid = "SELECT *
						FROM users
						WHERE www_users.userid='" . $Name . "'
						AND (www_users.password='" . md5($Password) . "'
						OR  www_users.password='" . $Password . "')";
						
	$quid=mysql_query($sqluid);
	$ruid=mysql_fetch_array($quid);
	$usid=$ruid['uid'];		
	
	if($usid!='1')
	{
	$lin="location:/scst/home/'".$usid."'";
	}
	else
	{
	 $lin="location:/scst/";
	}
	
	$file=fopen("/hp-shimla/ersession.txt",w);
	fwrite($file,'1');
	fclose($file);
	
	setcookie('ersess', '1', time() + 3600,'/');
	
//header("location:/hp-shimla/index.php?times=1");

header("location:".$burl.'/welcome-deshboard');
?>