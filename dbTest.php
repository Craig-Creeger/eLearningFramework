<?php
if (array_key_exists('phpinfo',$_GET)) {
	phpinfo();
	return;
}
/*
This page is used to help diagnose MySQL database connection issues. After calling this page from a web browser, open it in source view to look at any generated comments.
*/
$dbHost = 'mysql:host=localhost';
$dbDb = 'test';
$dbUserId = 'root';
$dbPassword = '';
$dbPort = '3306';
$dbDSN = "$dbHost;port=$dbPort;dbname=$dbDb";
$db = 'this will become the db connection.';
$statusMessage = '';

function debug($msg, $clear=false) {
	echo "<!-- $msg -->";
	if ($clear) {
		file_put_contents(APP_ROOT . "debugLog.txt", $date = date('Y-m-d H:i:s').'  '.$msg."\n");
	} else {
		file_put_contents(APP_ROOT . 'debugLog.txt', $date = date('Y-m-d H:i:s').'  '.$msg."\n", FILE_APPEND);
	}
}
function makeDbConnection($dbDSN, $dbUserId, $dbPassword) {
	global $db;

	try {
		$db = new PDO($dbDSN, $dbUserId, $dbPassword, array(
    		PDO::ATTR_PERSISTENT => true,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
		));
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("SET CHARACTER SET utf8");
	} catch(PDOException $e) {
		debug("unable to make connection: ".$e->getMessage());
		print($e->getMessage());
	}
}
makeDbConnection($dbDSN, $dbUserId, $dbPassword);
try {
	$db = new PDO($dbDSN, $dbUserId, $dbPassword, array(
		PDO::ATTR_PERSISTENT => true,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
	));
	echo '<!-- $db was set to a PDO object -->' . PHP_EOL;
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo '<!-- $db->setAttribute was called -->' . PHP_EOL;
	$db->exec("SET CHARACTER SET utf8");
	echo '<!-- $db->exec was called -->' . PHP_EOL;
} catch(PDOException $e) {
	echo "<!-- Unable to make connection: " . $e->getMessage() . ' -->' . PHP_EOL;
}

try {
	$stmt = $db->query("Select count(*) as aNumber from clients;");
	echo '<!-- set $stmt -->' . PHP_EOL;
	if ($row = $stmt->fetchObject()) {
		echo '<!-- retrieved a row -->' . PHP_EOL;
		$aNumber = $row->aNumber;
	}
} catch(PDOException $e) {
	echo "<!-- Unable to query the table: " . $e->getMessage() . ' -->' . PHP_EOL;
}

/* BEGINNING OF THE EMAIL TEST */
/*
//Ensure user has sufficient rights to run email test.
$userId = $_SESSION['userId'];
$playerRole = Player::playerRole($userId);
if ($playerRole === 'ADMIN') {
	// These POST variable come from the HTML form
	$email = (array_key_exists('email',$_POST)) ? sanitizeString($_POST['email']) : false;
	$fullName = (array_key_exists('fullName',$_POST)) ? sanitizeString($_POST['fullName']) : false;
	$phone = (array_key_exists('phone',$_POST)) ? sanitizeString($_POST['phone']) : false;
	$comments = (array_key_exists('comments',$_POST)) ? sanitizeString($_POST['comments']) : false;

	// These are the constants that you want applied to ALL emails.
	$toAddress = 'ccreeger@umn.edu'; //All emails will get sent to this address
	$subject = 'Website Comment Form';
	$fromAddress = ' ';

	// Build the email message
	$emailMsg = "The following information was entered on the website comment form.\n\n"; // \n = newline
	$emailMsg .= "Full Name: $fullName\n\n";
	$emailMsg .= "eMail: $email\n";
	$emailMsg .= "Phone: $phone\n\n";
	$emailMsg .= "Comment: $comments\n";

	//Uncomment the following line to make it actually send an email.
	//$delivered = mail($toAddress, $subject, $emailMsg, $fromAddress);
	  $delivered = '"Email been shut off for this test page."';
	if ($delivered) {
		$statusMessage = "<p>The email appeared to send correctly. The return code is " . $delivered . "</p>";
	} else {
		$statusMessage = "<p>The email failed. The return code is " . $delivered . "</p>";
	}
}
*/
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Database Connection Test</title>
<style type="text/css">
.box {
	padding:5px 1em; background-color:papayaWhip; font-size:larger; border:thin solid hotpink; border-radius:6px;
}
</style>
</head>

<body>
<p>If you see a number in this box, </p>
<p><span class="box"><?php echo $aNumber; ?></span></p>
<p>then your database connection is working properly.</p>
<hr>
<p><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?phpinfo=1'; ?>">Show phpinfo();</a></p>
<hr>
<h1>eMail Test</h1>
<h1>Contact Us</h1>
<form action="dbTest.php" method="post" name="contact">
	<h2>Contact Information</h2>
	<p> <label for="fullName">Full Name:</label> <input type="text" name="fullName" id="fullName" value="Samantha Claussen"> </p>
	<p> <label for="phone">Phone:</label> <input type="text" name="phone" id="phone" value="(651) 555-1234"> </p>
	<p> <label for="email">Email:</label> <input type="text" name="email" id="email" value="nop@pixelpro.biz"> </p>
	<p> <label for="comments" style="width:auto;">Comments or Questions</label>
		<textarea name="comments" rows="6" style="width:100%;">Boom Chicka Wow Wow</textarea>
	</p>
	<p><input name="submitButton" type="submit" value="Send"></p>
</form>
<?php
echo $statusMessage;
?>
</body>
</html>
