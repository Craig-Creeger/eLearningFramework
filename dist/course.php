<?php
require_once 'includes/common.php';
require_once INCLUDE_ROOT . 'classes/Assignment.php';
session_start();
makeDbConnection();

$courseId = sanitizeString(array_key_exists('courseId', $_GET) ? $_GET['courseId'] : 0);
$coursePath = COURSES_PATH . 'course' . $courseId . '/';

//The following require will give you variable:
// $pages = {the page structure}
require_once $coursePath . 'course' . $courseId . '.php';

//Retrieve the Course Name and Passing Score
$c = new Course($courseId);
?>
    <!doctype html>
    <html lang=en>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Intel Training Course</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=yes">
        <link href='https://fonts.googleapis.com/css?family=Alegreya+Sans:400,400italic,700' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Roboto:900italic' rel='stylesheet' type='text/css'>
        <link href="css/course.css" rel="stylesheet">
        <style>

        </style>
    </head>

    <body>
        <div id="adminCntl" class="hidden">
            <button onclick="nav.reload(); fetchPage();">Reload</button>
        </div>
        <div id="footerStickWrap">
            <header>
                <div class="title">Racking Up Wins</div>
                <div class="subTitle">Craig Creeger</div>
            </header>
            <div id="contentPadding">
                <output></output>
            </div>
        </div>
        <footer>
            <nav>
                <button id="prev">Prev</button>
                <button id="next">Next</button>
            </nav>
        </footer>
        <div id="loadingIndicator">
            <p id="loadingGfx"><span class="visuallyhidden">Loading...</span></p>
        </div>
        <script src="vendor/jquery.min.js"></script>
    </body>
    <script>
        <?php
echo 'var MSGCONTACT = "' . MSGCONTACT . '";' . PHP_EOL;
echo 'var PID = ' . $_SESSION['PID'] . ';' . PHP_EOL;
echo 'var learnerId = ' . $_SESSION['learnerId'] . ';' . PHP_EOL;
echo 'var pages = ' . json_encode($pages) . ';' . PHP_EOL;
echo 'var courseId = ' . $courseId . ';' . PHP_EOL;
echo 'var coursePath = "' . $coursePath . '";' . PHP_EOL;
echo 'var courseName = "' . addslashes($c->courseName) . '";' . PHP_EOL;
echo 'var passingScore = ' . $c->passingScore . ';' . PHP_EOL;
if (array_key_exists('DEBUG', $_SESSION) && $_SESSION['DEBUG']) {
    echo 'var debugMode = true;' . PHP_EOL;
} else {
    echo 'var debugMode = false;' . PHP_EOL;
}
?>
    </script>
    <script src="vendor/browser-polyfill.min.js"></script>
    <script src="js/Interaction.js"></script>
    <script src="js/Navigation.js"></script>
    <script src="js/course.js"></script>

    </html>