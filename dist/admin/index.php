<?php 
require_once '../includes/common.php';
if (ENVIRONMENT === 'local') {
	require "/Users/craig/Sites/motivaction2015/dist/admin/login.php";
} elseif (ENVIRONMENT == 'dev') {
	require "/home/pixelp12/subdomains/widget/admin/login.php";
} else {
	require "/home/motiva25/public_html/intel/admin/login.php";
}

makeDbConnection();
session_start();
require_once INCLUDE_ROOT . 'classes/Promotion.php';
if (array_key_exists('PID', $_SESSION)) {
    $p = new Promotion($_SESSION['PID']);
} else {
    $p = new Promotion(1);
}
?>
<!doctype html>
<html lang=en>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Motivaction’s Admin Screens</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:600,300' rel='stylesheet' type='text/css'>
    <link href="../css/admin.css" rel="stylesheet">
    <style>

    </style>
</head>

<body>
    <header>
        <h1>Admin Screens</h1>
        <p><?php echo $p->promotion; ?></p>
    </header>
    <main>
        <p><a href="export/lms.php" class="button">Export Learner Info</a></p>
        <?php
if (array_key_exists('showdata', $_GET)) {
    global $db;
    $stmt = $db->query("Select p.promotion, l.learnerId, l.email, l.fullName, c.courseName, a.score, if(a.pass, 'Yes', 'No') As passed, a.completionDate
    From learners l
    Inner Join assignments a On a.learnerId = l.learnerId
    Inner Join courses c On c.courseId = a.courseId
    Inner Join promotions p On p.pid = l.pid
    Where l.pid = " . $_SESSION['PID'] . "
    Order by p.promotion, l.learnerId, c.courseName;");

    echo '<table class="dataTable">' . PHP_EOL;
    echo '  <thead><tr>' . PHP_EOL;
    echo '      <th>Promotion</th><th>Learner Id</th><th>Email</th><th>Full Name</th><th>Course Name</th><th>Score</th><th>Passed</th><th>Date Passed</th></tr>' . PHP_EOL;
    echo '  </thead>' . PHP_EOL;
    echo '  <tbody>' . PHP_EOL;

    // Add all values in the table to $out. 
    while ($l = $stmt->fetch()) {
        echo '      <tr>' . PHP_EOL;
        for ($i = 0; $i < 8; $i++) {
            $curValue = $l[$i];
            echo '            <td>' . htmlspecialchars($curValue) . '</td>' . PHP_EOL;
        }
        echo '      </tr>' . PHP_EOL;
    }
    echo '  </tbody>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
} else {
    echo '<p><a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?showdata" class="button">Show Data</a></p>' . PHP_EOL;
}
        ?>
    </main>
</body>

</html>